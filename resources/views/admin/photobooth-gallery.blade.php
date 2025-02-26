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

    /* Photo Card */
    .photo-card {
        background: #ffffff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        transition: all 0.2s ease;
        border: 1px solid #edf2f7;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .photo-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .photo-card img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 16px 16px 0 0;
    }

    .photo-actions {
        position: absolute;
        bottom: 10px;
        right: 10px;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .photo-card:hover .photo-actions {
        opacity: 1;
    }

    .photo-date {
        position: absolute;
        bottom: 10px;
        left: 10px;
        color: white;
        background: rgba(0,0,0,0.5);
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
    }

    /* Buttons */
    .btn-primary {
        background-color: #4F46E5;
        border-color: #4F46E5;
        color: #ffffff;
        padding: 0.625rem 1.25rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #4338CA;
        border-color: #4338CA;
    }

    .btn-success {
        background-color: #10B981;
        border-color: #10B981;
    }

    .btn-success:hover {
        background-color: #059669;
        border-color: #059669;
    }

    .btn-danger {
        background-color: #EF4444;
        border-color: #EF4444;
    }

    .btn-danger:hover {
        background-color: #DC2626;
        border-color: #DC2626;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Photobooth Gallery</h1>
        <a href="{{ route('admin.photobooth') }}" class="btn btn-primary">
            <i class="bi bi-camera me-2"></i>
            Take New Photos
        </a>
    </div>

    <div class="settings-wrapper">
        <div class="row g-4">
            @foreach($photos as $photo)
            <div class="col-md-4">
                <div class="photo-card">
                    <img src="{{ Storage::url($photo->path) }}" alt="Photobooth">
                    <div class="photo-date">
                        <i class="bi bi-calendar3"></i>
                        {{ $photo->created_at->format('d M Y H:i') }}
                    </div>
                    <div class="photo-actions">
                        <a href="{{ route('admin.photo.download', $photo) }}" 
                           class="btn btn-sm btn-success">
                            <i class="bi bi-download"></i> Download
                        </a>
                        <button class="btn btn-sm btn-danger delete-photo" 
                                data-id="{{ $photo->id }}">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <p class="mt-3">Are you sure you want to delete this photo?</p>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                    <p class="mt-3">Photo deleted successfully!</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jsPage')
<script>
$(document).ready(function() {
    let photoToDelete = null;

    $('.delete-photo').on('click', function() {
        photoToDelete = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function() {
        if (!photoToDelete) return;

        const photoCard = $(`.delete-photo[data-id="${photoToDelete}"]`).closest('.col-md-4');
        
        $.ajax({
            url: `/admin/photo/${photoToDelete}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#deleteModal').modal('hide');
                    photoCard.fadeOut(300, function() {
                        $(this).remove();
                        $('#successModal').modal('show');
                    });
                }
            },
            error: function() {
                alert('Failed to delete photo');
            }
        });
    });

    // Reset photoToDelete when modal is closed
    $('#deleteModal').on('hidden.bs.modal', function() {
        photoToDelete = null;
    });
});
</script>
@endsection 