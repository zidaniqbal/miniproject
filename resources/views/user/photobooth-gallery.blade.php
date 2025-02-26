@extends('layouts.app')

@section('cssPage')
<style>
    /* Main Content Layout */
    .main-content {
        flex: 1;
        min-height: 100vh;
        padding: 20px;
        transition: all 0.3s;
    }

    .container-fluid {
        padding: 0;
        width: 100%;
    }

    .settings-wrapper {
        background: transparent;
        width: 100%;
        margin: 0;
        padding: 1.5rem;
    }

    /* Filter Section */
    .filter-section {
        background: #ffffff;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 1.5rem;
        border: 1px solid #edf2f7;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 0 0.5rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    /* Gallery Container */
    .gallery-container {
        padding: 20px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid #edf2f7;
    }

    .gallery-item {
        margin-bottom: 30px;
    }

    .gallery-item img {
        width: 100%;
        height: 400px;
        object-fit: contain;
        border-radius: 8px;
        background: #f8f9fa;
        transition: transform 0.3s ease;
    }

    .gallery-item img:hover {
        transform: scale(1.02);
    }

    /* Gallery Actions */
    .gallery-actions {
        margin-top: 15px;
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    /* Button Styles */
    .btn {
        padding: 0.625rem 1.25rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background-color: #4F46E5;
        border-color: #4F46E5;
        color: #ffffff;
    }

    .btn-primary:hover {
        background-color: #4338CA;
        border-color: #4338CA;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .btn-danger {
        background-color: #EF4444;
        border-color: #EF4444;
        color: #ffffff;
    }

    .btn-danger:hover {
        background-color: #DC2626;
        border-color: #DC2626;
    }

    /* Empty Gallery State */
    .empty-gallery {
        text-align: center;
        padding: 50px;
        background: #f8f9fa;
        border-radius: 8px;
        margin: 20px 0;
    }

    .empty-gallery i {
        font-size: 3rem;
        color: #6B7280;
        margin-bottom: 1rem;
    }

    .empty-gallery h3 {
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .empty-gallery p {
        color: #6B7280;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 16px;
        border: none;
    }

    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #edf2f7;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid #edf2f7;
    }

    .modal-title {
        font-weight: 600;
        color: #1a1a1a;
    }

    /* Success Icon in Modal */
    .text-success {
        color: #10B981 !important;
    }

    .text-warning {
        color: #F59E0B !important;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .gallery-actions {
            flex-direction: column;
            width: 100%;
        }

        .gallery-actions .btn {
            width: 100%;
        }

        .gallery-item img {
            height: 300px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="settings-wrapper">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title">Photo Gallery</h1>
            <a href="{{ route('user.photobooth') }}" class="btn btn-primary">
                <i class="bi bi-camera me-2"></i>Take Photos
            </a>
        </div>

        <!-- Gallery Section -->
        <div class="filter-section">
            @if(count($photos) > 0)
                <div class="row">
                    @foreach($photos as $photo)
                        <div class="col-md-4 gallery-item">
                            <img src="{{ Storage::url($photo) }}" alt="Photobooth Image">
                            <div class="gallery-actions">
                                <a href="{{ Storage::url($photo) }}" class="btn btn-sm btn-primary" download>
                                    <i class="bi bi-download"></i> Download
                                </a>
                                <button type="button" class="btn btn-sm btn-danger delete-photo" 
                                        data-photo="{{ basename($photo) }}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-gallery">
                    <i class="bi bi-images" style="font-size: 3rem;"></i>
                    <h3 class="mt-3">No Photos Yet</h3>
                    <p>Start by taking some photos in the photobooth!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                <p class="mt-3 mb-0">Are you sure you want to delete this photo?</p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" action="" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Photo</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                <p class="mt-3">Photo deleted successfully!</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-x-circle text-danger" style="font-size: 3rem;"></i>
                <p class="mt-3">Failed to delete photo. Please try again.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('jsPage')
<script>
$(document).ready(function() {
    // Handle delete button click
    $('.delete-photo').click(function() {
        const photoName = $(this).data('photo');
        const deleteUrl = "{{ route('user.photobooth.delete', ['photo' => ':photo']) }}".replace(':photo', photoName);
        $('#deleteForm').attr('action', deleteUrl);
    });

    // Handle form submission
    $('#deleteForm').submit(function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();

        // Disable button and show loading state
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Deleting...');

        $.ajax({
            type: 'POST',
            url: url,
            data: form.serialize(),
            success: function(response) {
                // Hide delete modal and its backdrop
                $('#deleteModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                
                // Remove the gallery item with animation
                const photoName = url.split('/').pop();
                const galleryItem = $(`.delete-photo[data-photo="${photoName}"]`).closest('.gallery-item');
                galleryItem.fadeOut(300, function() {
                    $(this).remove();
                    
                    // Show success modal
                    $('#successModal').modal('show');
                    
                    // Check if gallery is empty after removal
                    if ($('.gallery-item').length === 0) {
                        setTimeout(function() {
                            location.reload(); // Reload to show empty state
                        }, 1500); // Wait for success modal to be visible
                    }
                });
            },
            error: function(xhr) {
                // Hide delete modal and its backdrop
                $('#deleteModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                
                $('#errorModal').modal('show');
                console.error('Delete request failed:', xhr.responseText);
            },
            complete: function() {
                // Reset button state
                submitBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });

    // Reset form and button state when modal is hidden
    $('#deleteModal').on('hidden.bs.modal', function() {
        const form = $('#deleteForm');
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', false).html('Delete Photo');
        
        // Ensure modal backdrop is removed
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    });

    // Auto hide success modal after 2 seconds
    $('#successModal').on('shown.bs.modal', function() {
        setTimeout(() => {
            $(this).modal('hide');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        }, 2000);
    });

    // Clean up modal artifacts when any modal is hidden
    $('.modal').on('hidden.bs.modal', function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    });
});
</script>
@endsection 