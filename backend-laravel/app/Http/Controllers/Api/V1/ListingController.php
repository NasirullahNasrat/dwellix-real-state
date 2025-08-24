<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::with('images')->get();
        return response()->json($listings);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:sale,rent',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'parking' => 'sometimes|boolean',
            'furnished' => 'sometimes|boolean',
            'address' => 'required|string',
            'description' => 'required|string',
            'offer' => 'sometimes|boolean',
            'regularPrice' => 'required|numeric|min:0',
            'discountedPrice' => 'nullable|numeric|min:0',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            $data['parking'] = $request->has('parking') ? (bool)$request->parking : false;
            $data['furnished'] = $request->has('furnished') ? (bool)$request->furnished : false;
            $data['offer'] = $request->has('offer') ? (bool)$request->offer : false;
            $data['userRef'] = 'user_' . uniqid(); // Generate a user reference

            $listing = Listing::create($data);

            // Handle image uploads to public directory
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    // Store in public directory instead of storage/app/public
                    $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = public_path('uploads/listings');
                    
                    // Create directory if it doesn't exist
                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }
                    
                    // Move file to public directory
                    $image->move($path, $fileName);
                    
                    $listing->images()->create([
                        'image_url' => '/uploads/listings/' . $fileName, // Public URL path
                        'is_cover' => $index === 0, // First image as cover
                        'upload_order' => $index + 1
                    ]);
                }
            }

            return response()->json([
                'message' => 'Listing created successfully',
                'data' => $listing->load('images')
            ], 201);

        } catch (\Exception $e) {
            Log::error('Listing creation error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create listing',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $listing = Listing::with('images')->find($id);

        if (!$listing) {
            return response()->json([
                'message' => 'Listing not found'
            ], 404);
        }

        return response()->json($listing);
    }

    public function update(Request $request, $id)
    {
        $listing = Listing::with('images')->find($id);

        if (!$listing) {
            return response()->json([
                'message' => 'Listing not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|required|in:sale,rent',
            'bedrooms' => 'sometimes|required|integer|min:0',
            'bathrooms' => 'sometimes|required|integer|min:0',
            'parking' => 'sometimes|in:0,1,true,false',
            'furnished' => 'sometimes|in:0,1,true,false',
            'offer' => 'sometimes|in:0,1,true,false',
            'address' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'regularPrice' => 'sometimes|required|numeric|min:0',
            'discountedPrice' => 'nullable|numeric|min:0',
            'latitude' => 'sometimes|required|numeric',
            'longitude' => 'sometimes|required|numeric',
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images_to_delete' => 'sometimes|array',
            'images_to_delete.*' => 'sometimes|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Update basic fields
            $updateData = $request->only([
                'type', 'bedrooms', 'bathrooms', 'address', 'description',
                'regularPrice', 'discountedPrice', 'latitude', 'longitude'
            ]);

            // Handle boolean fields
            if ($request->has('parking')) {
                $updateData['parking'] = filter_var($request->parking, FILTER_VALIDATE_BOOLEAN);
            }
            if ($request->has('furnished')) {
                $updateData['furnished'] = filter_var($request->furnished, FILTER_VALIDATE_BOOLEAN);
            }
            if ($request->has('offer')) {
                $updateData['offer'] = filter_var($request->offer, FILTER_VALIDATE_BOOLEAN);
            }

            $listing->update($updateData);

            // Handle image deletions
            if ($request->has('images_to_delete')) {
                $imagesToDelete = $request->images_to_delete;
                $images = $listing->images()->whereIn('id', $imagesToDelete)->get();
                
                foreach ($images as $image) {
                    // Delete physical file from public directory
                    $filePath = public_path($image->image_url);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $image->delete();
                }
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                $existingImagesCount = $listing->images()->count();
                foreach ($request->file('images') as $index => $image) {
                    // Store in public directory
                    $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = public_path('uploads/listings');
                    
                    // Create directory if it doesn't exist
                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }
                    
                    // Move file to public directory
                    $image->move($path, $fileName);
                    
                    $listing->images()->create([
                        'image_url' => '/uploads/listings/' . $fileName, // Public URL path
                        'is_cover' => $existingImagesCount === 0 && $index === 0,
                        'upload_order' => $existingImagesCount + $index + 1
                    ]);
                }
            }

            return response()->json([
                'message' => 'Listing updated successfully',
                'data' => $listing->load('images')
            ]);

        } catch (\Exception $e) {
            Log::error('Listing update error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update listing',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $listing = Listing::find($id);

        if (!$listing) {
            return response()->json([
                'message' => 'Listing not found'
            ], 404);
        }

        try {
            // Delete associated images first
            $images = $listing->images()->get();
            foreach ($images as $image) {
                // Delete physical file from public directory
                $filePath = public_path($image->image_url);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $image->delete();
            }
            
            $listing->delete();

            return response()->json([
                'message' => 'Listing deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Listing deletion error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to delete listing',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}