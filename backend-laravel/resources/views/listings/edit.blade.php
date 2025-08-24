@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="card-title mb-0">Listing Details</h2>
                <div>
                    <a href="{{ route('listings.edit', $listing) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <a href="{{ route('listings.index') }}" class="btn btn-sm btn-outline-primary">Back to Listings</a>
                </div>
            </div>
            <div class="card-body">
                <!-- Image Carousel -->
                @if($listing->images->count() > 0)
                <div id="listingCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach($listing->images as $index => $image)
                        <button type="button" data-bs-target="#listingCarousel" 
                                data-bs-slide-to="{{ $index }}" 
                                class="{{ $index === 0 ? 'active' : '' }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($listing->images as $index => $image)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image->image_url) }}" 
                                 class="d-block w-100" alt="Property image" style="height: 400px; object-fit: cover;">
                            @if($image->is_cover)
                            <div class="carousel-caption d-none d-md-block">
                                <span class="badge bg-primary">Cover Image</span>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#listingCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#listingCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                @else
                <div class="bg-secondary d-flex align-items-center justify-content-center mb-4" style="height: 200px;">
                    <span class="text-white">No Images Available</span>
                </div>
                @endif

                <!-- Property Details -->
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="text-primary">{{ $listing->address }}</h3>
                        <p class="text-muted">{{ $listing->description }}</p>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-primary">${{ number_format($listing->regularPrice, 2) }}</h4>
                                @if($listing->offer && $listing->discountedPrice)
                                <h5 class="card-subtitle mb-2 text-danger">
                                    Discounted: ${{ number_format($listing->discountedPrice, 2) }}
                                </h5>
                                @endif
                                <div class="d-flex justify-content-between mt-3">
                                    <span class="badge bg-{{ $listing->type == 'sale' ? 'primary' : 'success' }}">
                                        For {{ $listing->type }}
                                    </span>
                                    @if($listing->offer)
                                    <span class="badge bg-danger">Special Offer</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Property Features -->
                <div class="row mb-4">
                    <div class="col-md-3 col-6 text-center">
                        <div class="p-3 border rounded">
                            <i class="bi bi-house-door fs-1 text-primary"></i>
                            <h5>{{ $listing->bedrooms }} Bedrooms</h5>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 text-center">
                        <div class="p-3 border rounded">
                            <i class="bi bi-droplet fs-1 text-primary"></i>
                            <h5>{{ $listing->bathrooms }} Bathrooms</h5>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 text-center">
                        <div class="p-3 border rounded">
                            <i class="bi bi-car-front fs-1 text-primary"></i>
                            <h5>Parking: {{ $listing->parking ? 'Yes' : 'No' }}</h5>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 text-center">
                        <div class="p-3 border rounded">
                            <i class="bi bi-box-seam fs-1 text-primary"></i>
                            <h5>Furnished: {{ $listing->furnished ? 'Yes' : 'No' }}</h5>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Location</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $listing->address }}</p>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Latitude:</strong> {{ $listing->latitude }}
                            </div>
                            <div class="col-md-6">
                                <strong>Longitude:</strong> {{ $listing->longitude }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Additional Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>User Reference:</strong> {{ $listing->userRef }}
                            </div>
                            <div class="col-md-6">
                                <strong>Created:</strong> {{ $listing->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection