<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="type" class="form-label">Listing Type</label>
            <select class="form-select" id="type" name="type" required>
                <option value="sale" {{ old('type', $listing->type ?? '') == 'sale' ? 'selected' : '' }}>For Sale</option>
                <option value="rent" {{ old('type', $listing->type ?? '') == 'rent' ? 'selected' : '' }}>For Rent</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="userRef" class="form-label">User Reference</label>
            <input type="text" class="form-control" id="userRef" name="userRef" 
                   value="{{ old('userRef', $listing->userRef ?? '') }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label for="bedrooms" class="form-label">Bedrooms</label>
            <input type="number" class="form-control" id="bedrooms" name="bedrooms" 
                   value="{{ old('bedrooms', $listing->bedrooms ?? '') }}" min="0" required>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label for="bathrooms" class="form-label">Bathrooms</label>
            <input type="number" class="form-control" id="bathrooms" name="bathrooms" 
                   value="{{ old('bathrooms', $listing->bathrooms ?? '') }}" min="0" required>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label for="regularPrice" class="form-label">Regular Price ($)</label>
            <input type="number" step="0.01" class="form-control" id="regularPrice" name="regularPrice" 
                   value="{{ old('regularPrice', $listing->regularPrice ?? '') }}" min="0" required>
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label for="discountedPrice" class="form-label">Discounted Price ($)</label>
            <input type="number" step="0.01" class="form-control" id="discountedPrice" name="discountedPrice" 
                   value="{{ old('discountedPrice', $listing->discountedPrice ?? '') }}" min="0">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="parking" name="parking" 
                   {{ old('parking', $listing->parking ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="parking">Parking Available</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="furnished" name="furnished" 
                   {{ old('furnished', $listing->furnished ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="furnished">Furnished</label>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="offer" name="offer" 
                   {{ old('offer', $listing->offer ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="offer">Special Offer</label>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="address" class="form-label">Address</label>
    <textarea class="form-control" id="address" name="address" rows="2" required>{{ old('address', $listing->address ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $listing->description ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="number" step="any" class="form-control" id="latitude" name="latitude" 
                   value="{{ old('latitude', $listing->latitude ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="number" step="any" class="form-control" id="longitude" name="longitude" 
                   value="{{ old('longitude', $listing->longitude ?? '') }}" required>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="images" class="form-label">Property Images</label>
    <input class="form-control" type="file" id="images" name="images[]" multiple accept="image/*">
    <div class="form-text">You can select multiple images. The first image will be set as cover.</div>
</div>

@if(isset($listing) && $listing->images->count() > 0)
<div class="mb-3">
    <label class="form-label">Current Images</label>
    <div class="row">
        @foreach($listing->images as $image)
        <div class="col-md-3 mb-3">
            <div class="card">
                <img src="{{ asset('storage/' . $image->image_url) }}" class="card-img-top" alt="Property image">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between">
                        <form action="{{ route('listings.set-cover-image', $image) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $image->is_cover ? 'btn-success' : 'btn-outline-secondary' }}">
                                <i class="bi {{ $image->is_cover ? 'bi-star-fill' : 'bi-star' }}"></i>
                            </button>
                        </form>
                        <form action="{{ route('listings.destroy-image', $image) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                    onclick="return confirm('Are you sure you want to delete this image?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif