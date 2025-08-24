<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ListingViewController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        // Set the base API URL - adjust if needed
        $this->apiBaseUrl = config('app.url') . '/api/v1';
    }

    /**
     * Display the listing management interface
     */
    public function index()
    {
        return view('listings.index');
    }

    /**
     * Show the form for creating a new listing
     */
    public function create()
    {
        return view('listings.create');
    }

    /**
     * Store a newly created listing via API
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
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

            // Prepare the data for API
            $data = $request->only([
                'type', 'bedrooms', 'bathrooms', 'parking', 'furnished',
                'address', 'description', 'offer', 'regularPrice',
                'discountedPrice', 'latitude', 'longitude'
            ]);

            // Convert boolean values
            $data['parking'] = (bool)($data['parking'] ?? false);
            $data['furnished'] = (bool)($data['furnished'] ?? false);
            $data['offer'] = (bool)($data['offer'] ?? false);

            // Create FormData for the API request
            $formData = [];
            foreach ($data as $key => $value) {
                $formData[$key] = $value;
            }

            // Handle image uploads if present
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $formData["images[$index]"] = fopen($image->getPathname(), 'r');
                }
            }

            // Make API request to create listing
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->attach($formData)->post("{$this->apiBaseUrl}/listings");

            if ($response->successful()) {
                return redirect()->route('listings.manage')
                    ->with('success', 'Listing created successfully!');
            } else {
                $errorMessage = $response->json()['message'] ?? 'Failed to create listing';
                return back()->withInput()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Listing creation error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while creating the listing.');
        }
    }

    /**
     * Display the specified listing
     */
    public function show($id)
    {
        try {
            // Fetch listing from API
            $response = Http::get("{$this->apiBaseUrl}/listings/{$id}");

            if ($response->successful()) {
                $listing = $response->json();
                return view('listings.show', compact('listing'));
            } else {
                return redirect()->route('listings.manage')
                    ->with('error', 'Listing not found.');
            }
        } catch (\Exception $e) {
            Log::error('Listing fetch error: ' . $e->getMessage());
            return redirect()->route('listings.manage')
                ->with('error', 'An error occurred while fetching the listing.');
        }
    }

    /**
     * Show the form for editing the specified listing
     */
    public function edit($id)
    {
        try {
            // Fetch listing from API
            $response = Http::get("{$this->apiBaseUrl}/listings/{$id}");

            if ($response->successful()) {
                $listing = $response->json();
                return view('listings.edit', compact('listing'));
            } else {
                return redirect()->route('listings.manage')
                    ->with('error', 'Listing not found.');
            }
        } catch (\Exception $e) {
            Log::error('Listing fetch error: ' . $e->getMessage());
            return redirect()->route('listings.manage')
                ->with('error', 'An error occurred while fetching the listing.');
        }
    }

    /**
     * Update the specified listing via API
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate the request
            $validated = $request->validate([
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

            // Prepare the data for API
            $data = $request->only([
                'type', 'bedrooms', 'bathrooms', 'parking', 'furnished',
                'address', 'description', 'offer', 'regularPrice',
                'discountedPrice', 'latitude', 'longitude'
            ]);

            // Convert boolean values
            $data['parking'] = (bool)($data['parking'] ?? false);
            $data['furnished'] = (bool)($data['furnished'] ?? false);
            $data['offer'] = (bool)($data['offer'] ?? false);

            // Create FormData for the API request
            $formData = [];
            foreach ($data as $key => $value) {
                $formData[$key] = $value;
            }

            // Handle image uploads if present
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $formData["images[$index]"] = fopen($image->getPathname(), 'r');
                }
            }

            // Make API request to update listing
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->attach($formData)->put("{$this->apiBaseUrl}/listings/{$id}");

            if ($response->successful()) {
                return redirect()->route('listings.manage')
                    ->with('success', 'Listing updated successfully!');
            } else {
                $errorMessage = $response->json()['message'] ?? 'Failed to update listing';
                return back()->withInput()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Listing update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while updating the listing.');
        }
    }

    /**
     * Remove the specified listing via API
     */
    public function destroy($id)
    {
        try {
            // Make API request to delete listing
            $response = Http::delete("{$this->apiBaseUrl}/listings/{$id}");

            if ($response->successful()) {
                return redirect()->route('listings.manage')
                    ->with('success', 'Listing deleted successfully!');
            } else {
                $errorMessage = $response->json()['message'] ?? 'Failed to delete listing';
                return redirect()->route('listings.manage')
                    ->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Listing deletion error: ' . $e->getMessage());
            return redirect()->route('listings.manage')
                ->with('error', 'An error occurred while deleting the listing.');
        }
    }
}