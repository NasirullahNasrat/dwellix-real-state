<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listing Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .listing-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
            height: 100%;
        }
        .listing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .cover-image {
            height: 200px;
            object-fit: cover;
        }
        .price-tag {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .feature-badge {
            margin-right: 5px;
        }
        .image-preview {
            max-height: 150px;
            object-fit: cover;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        #imagePreviewContainer, #editImagePreviewContainer {
            min-height: 50px;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .action-buttons {
            margin-top: 10px;
        }
        .existing-image {
            position: relative;
            margin: 5px;
            display: inline-block;
        }
        .existing-image img {
            height: 100px;
            width: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .remove-image-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #ff4444;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Real Estate Listings</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="showListingsBtn"><i class="fas fa-list"></i> View Listings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="showCreateFormBtn"><i class="fas fa-plus"></i> Create Listing</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Success/Error Messages -->
        <div id="alertContainer"></div>

        <!-- Create Listing Form Section -->
        <div id="createFormSection" class="card mb-4 d-none">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fas fa-home"></i> Create New Listing</h4>
            </div>
            <div class="card-body">
                <form id="listingForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Listing Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="sale">For Sale</option>
                                    <option value="rent">For Rent</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="bedrooms" class="form-label">Bedrooms</label>
                                <input type="number" class="form-control" id="bedrooms" name="bedrooms" min="0" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="bathrooms" class="form-label">Bathrooms</label>
                                <input type="number" class="form-control" id="bathrooms" name="bathrooms" min="0" required>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="parking" name="parking">
                                    <label class="form-check-label" for="parking">Parking Available</label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="furnished" name="furnished">
                                    <label class="form-check-label" for="furnished">Furnished</label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="offer" name="offer">
                                    <label class="form-check-label" for="offer">Accepting Offers</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="regularPrice" class="form-label">Regular Price ($)</label>
                                <input type="number" class="form-control" id="regularPrice" name="regularPrice" step="0.01" min="0" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="discountedPrice" class="form-label">Discounted Price ($) - Optional</label>
                                <input type="number" class="form-control" id="discountedPrice" name="discountedPrice" step="0.01" min="0">
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="number" class="form-control" id="latitude" name="latitude" step="any" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="number" class="form-control" id="longitude" name="longitude" step="any" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="images" class="form-label">Upload Images</label>
                                <input class="form-control" type="file" id="images" name="images[]" multiple accept="image/*">
                                <div class="form-text">Select multiple images for your listing</div>
                            </div>
                            
                            <div id="imagePreviewContainer" class="d-flex flex-wrap mb-3"></div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-secondary me-md-2" id="cancelCreateBtn">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Listing</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Listing Form Section -->
        <div id="editFormSection" class="card mb-4 d-none">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Listing</h4>
            </div>
            <div class="card-body">
                <form id="editListingForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editId" name="id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editType" class="form-label">Listing Type</label>
                                <select class="form-select" id="editType" name="type" required>
                                    <option value="sale">For Sale</option>
                                    <option value="rent">For Rent</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="editBedrooms" class="form-label">Bedrooms</label>
                                <input type="number" class="form-control" id="editBedrooms" name="bedrooms" min="0" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="editBathrooms" class="form-label">Bathrooms</label>
                                <input type="number" class="form-control" id="editBathrooms" name="bathrooms" min="0" required>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="editParking" name="parking">
                                    <label class="form-check-label" for="editParking">Parking Available</label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="editFurnished" name="furnished">
                                    <label class="form-check-label" for="editFurnished">Furnished</label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="editOffer" name="offer">
                                    <label class="form-check-label" for="editOffer">Accepting Offers</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editRegularPrice" class="form-label">Regular Price ($)</label>
                                <input type="number" class="form-control" id="editRegularPrice" name="regularPrice" step="0.01" min="0" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="editDiscountedPrice" class="form-label">Discounted Price ($) - Optional</label>
                                <input type="number" class="form-control" id="editDiscountedPrice" name="discountedPrice" step="0.01" min="0">
                            </div>
                            
                            <div class="mb-3">
                                <label for="editAddress" class="form-label">Address</label>
                                <textarea class="form-control" id="editAddress" name="address" rows="2" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="editLatitude" class="form-label">Latitude</label>
                                <input type="number" class="form-control" id="editLatitude" name="latitude" step="any" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="editLongitude" class="form-label">Longitude</label>
                                <input type="number" class="form-control" id="editLongitude" name="longitude" step="any" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="editDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="editDescription" name="description" rows="4" required></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Existing Images</label>
                                <div id="existingImagesContainer" class="d-flex flex-wrap mb-3"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="editImages" class="form-label">Add New Images</label>
                                <input class="form-control" type="file" id="editImages" name="images[]" multiple accept="image/*">
                                <div class="form-text">Select additional images for your listing</div>
                            </div>
                            
                            <div id="editImagePreviewContainer" class="d-flex flex-wrap mb-3"></div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-secondary me-md-2" id="cancelEditBtn">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Listing</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Listings Display Section -->
        <div id="listingsSection">
            <h2 class="mb-4">Property Listings</h2>
            <div id="listingsContainer" class="row">
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading listings...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Listing Details Modal -->
    <div class="modal fade" id="listingDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Listing Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="listingDetailsContent">
                    <!-- Details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this listing? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Listing</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM elements
            const showCreateFormBtn = document.getElementById('showCreateFormBtn');
            const showListingsBtn = document.getElementById('showListingsBtn');
            const createFormSection = document.getElementById('createFormSection');
            const editFormSection = document.getElementById('editFormSection');
            const listingsSection = document.getElementById('listingsSection');
            const cancelCreateBtn = document.getElementById('cancelCreateBtn');
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            const listingForm = document.getElementById('listingForm');
            const editListingForm = document.getElementById('editListingForm');
            const imagesInput = document.getElementById('images');
            const editImagesInput = document.getElementById('editImages');
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');
            const editImagePreviewContainer = document.getElementById('editImagePreviewContainer');
            const alertContainer = document.getElementById('alertContainer');
            const listingsContainer = document.getElementById('listingsContainer');
            const existingImagesContainer = document.getElementById('existingImagesContainer');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            
            // State variables
            let currentListingId = null;
            let imagesToDelete = [];
            
            // CSRF token for API requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            
            // Event listeners
            showCreateFormBtn.addEventListener('click', showCreateForm);
            showListingsBtn.addEventListener('click', showListings);
            cancelCreateBtn.addEventListener('click', showListings);
            cancelEditBtn.addEventListener('click', showListings);
            listingForm.addEventListener('submit', handleFormSubmit);
            editListingForm.addEventListener('submit', handleEditFormSubmit);
            imagesInput.addEventListener('change', handleImagePreview);
            editImagesInput.addEventListener('change', handleEditImagePreview);
            confirmDeleteBtn.addEventListener('click', handleDeleteListing);
            
            // Load listings on page load
            loadListings();
            
            // Function to show create form
            function showCreateForm() {
                createFormSection.classList.remove('d-none');
                editFormSection.classList.add('d-none');
                listingsSection.classList.add('d-none');
                window.scrollTo(0, 0);
            }
            
            // Function to show edit form
            function showEditForm(listing) {
                // Populate form fields
                document.getElementById('editId').value = listing.id;
                document.getElementById('editType').value = listing.type;
                document.getElementById('editBedrooms').value = listing.bedrooms;
                document.getElementById('editBathrooms').value = listing.bathrooms;
                document.getElementById('editParking').checked = listing.parking;
                document.getElementById('editFurnished').checked = listing.furnished;
                document.getElementById('editOffer').checked = listing.offer;
                document.getElementById('editRegularPrice').value = listing.regularPrice;
                document.getElementById('editDiscountedPrice').value = listing.discountedPrice || '';
                document.getElementById('editAddress').value = listing.address;
                document.getElementById('editDescription').value = listing.description;
                document.getElementById('editLatitude').value = listing.latitude;
                document.getElementById('editLongitude').value = listing.longitude;
                
                // Display existing images
                displayExistingImages(listing.images || []);
                
                // Show the edit form
                createFormSection.classList.add('d-none');
                editFormSection.classList.remove('d-none');
                listingsSection.classList.add('d-none');
                window.scrollTo(0, 0);
            }
            
            // Function to display existing images in edit form
            function displayExistingImages(images) {
                existingImagesContainer.innerHTML = '';
                imagesToDelete = [];
                
                if (images.length === 0) {
                    existingImagesContainer.innerHTML = '<p class="text-muted">No images uploaded yet.</p>';
                    return;
                }
                
                images.forEach(image => {
                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'existing-image';
                    imageDiv.innerHTML = `
                        <img src="${image.image_url}" alt="Listing image">
                        <span class="remove-image-btn" data-image-id="${image.id}">
                            <i class="fas fa-times"></i>
                        </span>
                    `;
                    existingImagesContainer.appendChild(imageDiv);
                });
                
                // Add event listeners to remove image buttons
                document.querySelectorAll('.remove-image-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const imageId = this.getAttribute('data-image-id');
                        imagesToDelete.push(imageId);
                        this.parentElement.remove();
                    });
                });
            }
            
            // Function to show listings
            function showListings() {
                createFormSection.classList.add('d-none');
                editFormSection.classList.add('d-none');
                listingsSection.classList.remove('d-none');
                window.scrollTo(0, 0);
            }
            
            // Function to handle image preview for create form
            function handleImagePreview() {
                imagePreviewContainer.innerHTML = '';
                const files = imagesInput.files;
                
                if (files.length > 0) {
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        if (file.type.match('image.*')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.classList.add('image-preview');
                                img.style.width = '150px';
                                imagePreviewContainer.appendChild(img);
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                }
            }
            
            // Function to handle image preview for edit form
            function handleEditImagePreview() {
                editImagePreviewContainer.innerHTML = '';
                const files = editImagesInput.files;
                
                if (files.length > 0) {
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        if (file.type.match('image.*')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.classList.add('image-preview');
                                img.style.width = '150px';
                                editImagePreviewContainer.appendChild(img);
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                }
            }
            
            // Function to show alert message
            function showAlert(message, type = 'success') {
                alertContainer.innerHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    const alert = document.querySelector('.alert');
                    if (alert) {
                        bootstrap.Alert.getOrCreateInstance(alert).close();
                    }
                }, 5000);
            }
            
            // Function to prepare form data with checkbox handling
            function prepareFormData(form) {
                const formData = new FormData(form);
                
                // Handle checkboxes - set to '0' if not checked, '1' if checked
                const checkboxes = ['parking', 'furnished', 'offer'];
                checkboxes.forEach(field => {
                    const checkbox = form.querySelector(`[name="${field}"]`);
                    if (checkbox) {
                        formData.set(field, checkbox.checked ? '1' : '0');
                    }
                });
                
                return formData;
            }
            
            // Function to handle form submission for creating listing
            async function handleFormSubmit(e) {
                e.preventDefault();
                
                const formData = prepareFormData(listingForm);
                
                try {
                    const response = await fetch('/api/v1/listings', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        showAlert('Listing created successfully!', 'success');
                        listingForm.reset();
                        imagePreviewContainer.innerHTML = '';
                        loadListings();
                        showListings();
                    } else {
                        let errorMessage = 'Failed to create listing';
                        if (data.errors) {
                            errorMessage = Object.values(data.errors).join('<br>');
                        } else if (data.message) {
                            errorMessage = data.message;
                        }
                        showAlert(errorMessage, 'danger');
                    }
                } catch (error) {
                    showAlert('An error occurred while creating the listing.', 'danger');
                    console.error('Error:', error);
                }
            }
            
            // Function to handle form submission for editing listing
            async function handleEditFormSubmit(e) {
                e.preventDefault();
                
                const formData = prepareFormData(editListingForm);
                
                // Add images to delete to form data
                imagesToDelete.forEach(imageId => {
                    formData.append('images_to_delete[]', imageId);
                });
                
                try {
                    const listingId = document.getElementById('editId').value;
                    
                    // Use POST method with override for PUT to handle file uploads
                    const response = await fetch(`/api/v1/listings/${listingId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-HTTP-Method-Override': 'PUT'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        showAlert('Listing updated successfully!', 'success');
                        editListingForm.reset();
                        editImagePreviewContainer.innerHTML = '';
                        imagesToDelete = [];
                        loadListings();
                        showListings();
                    } else {
                        let errorMessage = 'Failed to update listing';
                        if (data.errors) {
                            errorMessage = Object.values(data.errors).join('<br>');
                        } else if (data.message) {
                            errorMessage = data.message;
                        }
                        showAlert(errorMessage, 'danger');
                    }
                } catch (error) {
                    showAlert('An error occurred while updating the listing.', 'danger');
                    console.error('Error:', error);
                }
            }
            
            // Function to load listings
            async function loadListings() {
                try {
                    listingsContainer.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading listings...</p>
                        </div>
                    `;
                    
                    const response = await fetch('/api/v1/listings');
                    const listings = await response.json();
                    
                    displayListings(listings);
                } catch (error) {
                    console.error('Error loading listings:', error);
                    showAlert('Failed to load listings.', 'danger');
                    listingsContainer.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3 text-danger"></i>
                            <h4 class="text-danger">Error Loading Listings</h4>
                            <p>Please try again later</p>
                        </div>
                    `;
                }
            }
            
            // Function to display listings
            function displayListings(listings) {
                listingsContainer.innerHTML = '';
                
                if (listings.length === 0) {
                    listingsContainer.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-home fa-3x mb-3 text-muted"></i>
                            <h4 class="text-muted">No listings found</h4>
                            <p>Be the first to create a listing!</p>
                        </div>
                    `;
                    return;
                }
                
                listings.forEach(listing => {
                    const coverImage = listing.images && listing.images.length > 0 ? 
                        listing.images.find(img => img.is_cover) || listing.images[0] : 
                        null;
                    
                    const card = document.createElement('div');
                    card.className = 'col-md-6 col-lg-4';
                    card.innerHTML = `
                        <div class="card listing-card">
                            ${coverImage ? 
                                `<img src="${coverImage.image_url}" class="card-img-top cover-image" alt="Property image">` : 
                                `<div class="cover-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-home fa-3x text-muted"></i>
                                </div>`
                            }
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-${listing.type === 'sale' ? 'primary' : 'success'}">${listing.type === 'sale' ? 'For Sale' : 'For Rent'}</span>
                                    <span class="price-tag text-primary">$${listing.discountedPrice || listing.regularPrice}</span>
                                </div>
                                <h5 class="card-title">${listing.address.substring(0, 30)}${listing.address.length > 30 ? '...' : ''}</h5>
                                <p class="card-text">${listing.description.substring(0, 100)}${listing.description.length > 100 ? '...' : ''}</p>
                                <div class="d-flex flex-wrap mb-2">
                                    ${listing.bedrooms ? `<span class="feature-badge badge bg-secondary me-1 mb-1"><i class="fas fa-bed"></i> ${listing.bedrooms} Bed</span>` : ''}
                                    ${listing.bathrooms ? `<span class="feature-badge badge bg-secondary me-1 mb-1"><i class="fas fa-bath"></i> ${listing.bathrooms} Bath</span>` : ''}
                                    ${listing.parking ? `<span class="feature-badge badge bg-secondary me-1 mb-1"><i class="fas fa-car"></i> Parking</span>` : ''}
                                    ${listing.furnished ? `<span class="feature-badge badge bg-secondary me-1 mb-1"><i class="fas fa-couch"></i> Furnished</span>` : ''}
                                </div>
                                <div class="action-buttons">
                                    <button class="btn btn-outline-primary view-details-btn" data-id="${listing.id}">View Details</button>
                                    <button class="btn btn-outline-info edit-listing-btn" data-id="${listing.id}">Edit</button>
                                    <button class="btn btn-outline-danger delete-listing-btn" data-id="${listing.id}">Delete</button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    listingsContainer.appendChild(card);
                });
                
                // Add event listeners to action buttons
                document.querySelectorAll('.view-details-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const listingId = this.getAttribute('data-id');
                        showListingDetails(listingId);
                    });
                });
                
                document.querySelectorAll('.edit-listing-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const listingId = this.getAttribute('data-id');
                        editListing(listingId);
                    });
                });
                
                document.querySelectorAll('.delete-listing-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const listingId = this.getAttribute('data-id');
                        confirmDelete(listingId);
                    });
                });
            }
            
            // Function to show listing details
            async function showListingDetails(listingId) {
                try {
                    const response = await fetch(`/api/v1/listings/${listingId}`);
                    const listing = await response.json();
                    
                    if (response.ok) {
                        const modalContent = document.getElementById('listingDetailsContent');
                        modalContent.innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="listingCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            ${listing.images && listing.images.length > 0 ? 
                                                listing.images.map((image, index) => `
                                                    <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                                        <img src="${image.image_url}" class="d-block w-100" alt="Property image" style="height: 300px; object-fit: cover;">
                                                    </div>
                                                `).join('') : 
                                                `<div class="carousel-item active">
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                                                        <i class="fas fa-home fa-5x text-muted"></i>
                                                    </div>
                                                </div>`
                                            }
                                        </div>
                                        ${listing.images && listing.images.length > 1 ? `
                                            <button class="carousel-control-prev" type="button" data-bs-target="#listingCarousel" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#listingCarousel" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        ` : ''}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>${listing.address}</h4>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-${listing.type === 'sale' ? 'primary' : 'success'}">${listing.type === 'sale' ? 'For Sale' : 'For Rent'}</span>
                                        <span class="price-tag text-primary">$${listing.discountedPrice || listing.regularPrice}</span>
                                    </div>
                                    <p>${listing.description}</p>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <strong><i class="fas fa-bed"></i> Bedrooms:</strong> ${listing.bedrooms}
                                        </div>
                                        <div class="col-6">
                                            <strong><i class="fas fa-bath"></i> Bathrooms:</strong> ${listing.bathrooms}
                                        </div>
                                        <div class="col-6">
                                            <strong><i class="fas fa-car"></i> Parking:</strong> ${listing.parking ? 'Yes' : 'No'}
                                        </div>
                                        <div class="col-6">
                                            <strong><i class="fas fa-couch"></i> Furnished:</strong> ${listing.furnished ? 'Yes' : 'No'}
                                        </div>
                                        <div class="col-6">
                                            <strong><i class="fas fa-tag"></i> Offer:</strong> ${listing.offer ? 'Yes' : 'No'}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Location Coordinates:</strong> 
                                        <div>Lat: ${listing.latitude}, Lng: ${listing.longitude}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        const modal = new bootstrap.Modal(document.getElementById('listingDetailsModal'));
                        modal.show();
                    } else {
                        showAlert('Failed to load listing details.', 'danger');
                    }
                } catch (error) {
                    console.error('Error loading listing details:', error);
                    showAlert('Failed to load listing details.', 'danger');
                }
            }
            
            // Function to fetch and display listing for editing
            async function editListing(listingId) {
                try {
                    const response = await fetch(`/api/v1/listings/${listingId}`);
                    const listing = await response.json();
                    
                    if (response.ok) {
                        showEditForm(listing);
                    } else {
                        showAlert('Failed to load listing for editing.', 'danger');
                    }
                } catch (error) {
                    console.error('Error loading listing for editing:', error);
                    showAlert('Failed to load listing for editing.', 'danger');
                }
            }
            
            // Function to confirm deletion
            function confirmDelete(listingId) {
                currentListingId = listingId;
                const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
                modal.show();
            }
            
            // Function to handle listing deletion
            async function handleDeleteListing() {
                try {
                    const response = await fetch(`/api/v1/listings/${currentListingId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    
                    if (response.ok) {
                        showAlert('Listing deleted successfully!', 'success');
                        loadListings();
                        
                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                        modal.hide();
                    } else {
                        const data = await response.json();
                        const errorMessage = data.message || 'Failed to delete listing';
                        showAlert(errorMessage, 'danger');
                    }
                } catch (error) {
                    console.error('Error deleting listing:', error);
                    showAlert('An error occurred while deleting the listing.', 'danger');
                }
            }
        });
    </script>
</body>
</html>