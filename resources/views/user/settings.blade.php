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

    /* Container Fluid - Updated padding */
    .container-fluid {
        padding: 0;
        width: 100%;
    }

    /* Settings Wrapper - Updated margins and padding */
    .settings-wrapper {
        background: transparent;
        width: 100%;
        margin: 0;
        padding: 1.5rem;
    }

    /* Page Header - Updated margins and padding */
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

    /* Settings Card - Updated margins */
    .settings-card {
        background: #ffffff;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 1rem;
        border: 1px solid #edf2f7;
    }

    .settings-section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #edf2f7;
    }

    /* Profile Image Section */
    .profile-image-section {
        display: flex;
        align-items: flex-start;
        gap: 2rem;
    }

    .profile-image-section > div:first-child {
        flex-shrink: 0;
    }

    .profile-image-section > div:last-child {
        flex-grow: 1;
        max-width: 600px;
    }

    .profile-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #edf2f7;
    }

    .profile-image-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #9ca3af;
        border: 3px solid #edf2f7;
    }

    .image-upload-info {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.5rem;
    }

    /* Form Controls */
    .form-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        color: #111827;
        background-color: #fff;
        transition: border-color 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #4F46E5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* Button Styles */
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

    /* Password Section */
    .password-requirements {
        margin-top: 0.75rem;
        padding: 0.75rem;
        background-color: #f8fafc;
        border-radius: 8px;
        font-size: 0.875rem;
    }

    .password-requirements ul {
        margin: 0;
        padding-left: 1.25rem;
        color: #64748b;
    }

    /* Input Groups */
    .input-group {
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        overflow: hidden;
    }

    .input-group .form-control {
        border: none;
    }

    .input-group .toggle-password {
        border: none;
        background-color: transparent;
        color: #6B7280;
        padding: 0.625rem 0.875rem;
    }

    .input-group .toggle-password:hover {
        color: #4F46E5;
        background-color: #F9FAFB;
    }

    /* Responsive Design - Updated padding */
    @media (max-width: 768px) {
        .settings-wrapper {
            padding: 1rem;
        }

        .settings-card {
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .profile-image-section {
            flex-direction: column;
            gap: 1.5rem;
        }

        .profile-image-section > div:last-child {
            width: 100%;
            max-width: none;
        }
    }

    /* Toast Container */
    .toast-container {
        z-index: 1056;
    }
</style>
@endsection

@section('content')

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Profile Settings</h1>
        </div>

        <!-- Settings Content Wrapper -->
        <div class="settings-wrapper">
            <!-- Profile Information -->
            <div class="settings-card">
                <h2 class="settings-section-title">Profile Information</h2>
                <form id="profileForm">
                    @csrf
                    <div class="profile-image-section">
                        <div>
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile" class="profile-image" id="profileImagePreview">
                            @else
                                <div class="profile-image-placeholder" id="profileImagePlaceholder">
                                    <i class="bi bi-person"></i>
                                </div>
                            @endif
                            <input type="file" class="d-none" id="profileImage" name="profile_image" accept="image/*">
                            <div class="image-upload-info">
                                <p class="mb-1">Click on the image to change</p>
                                <small>Requirements:</small>
                                <ul class="ps-3 mb-0">
                                    <li>Maximum file size: 2MB</li>
                                    <li>Allowed formats: JPG, PNG</li>
                                    <li>Recommended size: 300x300 pixels</li>
                                </ul>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="{{ Auth::user()->email }}" readonly>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="settings-card">
                <h2 class="settings-section-title">Change Password</h2>
                <form id="passwordForm">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="password-requirements">
                        <small class="d-block mb-2">Password requirements:</small>
                        <ul>
                            <li>Minimum 8 characters</li>
                            <li>Must be different from current password</li>
                        </ul>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>


<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="toast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@section('jsPage')
<script>
$(document).ready(function() {
    // Profile Image Preview
    function readURL(input) {
        if (input.files && input.files[0]) {
            // Validasi ukuran file (max 2MB)
            if (input.files[0].size > 2 * 1024 * 1024) {
                showToast('File size must be less than 2MB', 'error');
                input.value = '';
                return;
            }

            var reader = new FileReader();
            
            reader.onload = function(e) {
                if ($('#profileImagePreview').length) {
                    $('#profileImagePreview').attr('src', e.target.result);
                } else {
                    $('#profileImagePlaceholder').replaceWith(
                        `<img src="${e.target.result}" alt="Profile" class="profile-image" id="profileImagePreview">`
                    );
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Trigger file input when clicking on profile image
    $('.profile-image, .profile-image-placeholder').click(function() {
        $('#profileImage').click();
    });

    $('#profileImage').change(function() {
        readURL(this);
    });

    // Profile Update Form
    $('#profileForm').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: "{{ route('user.settings.updateProfile') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showToast(response.message, 'success');
                // Refresh halaman setelah berhasil update
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(xhr, status, error) {
                console.error('Error details:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = Object.values(errors)[0][0];
                    showToast(errorMessage, 'error');
                } else {
                    let errorMessage = 'An error occurred while updating profile.';
                    try {
                        let response = JSON.parse(xhr.responseText);
                        errorMessage = response.message || errorMessage;
                    } catch(e) {}
                    showToast(errorMessage, 'error');
                }
            }
        });
    });

    // Password Update Form
    $('#passwordForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('user.settings.updatePassword') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                showToast(response.message, 'success');
                $('#passwordForm')[0].reset();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = Object.values(errors)[0][0];
                    showToast(errorMessage, 'error');
                } else {
                    showToast('An error occurred while updating password.', 'error');
                }
            }
        });
    });

    // Toast function
    function showToast(message, type = 'success') {
        const toast = $('#toast');
        toast.removeClass('bg-success bg-danger');
        
        if (type === 'success') {
            toast.addClass('bg-success');
        } else {
            toast.addClass('bg-danger');
        }
        
        $('.toast-body').text(message);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    }

    // Password Toggle Functionality
    $('.toggle-password').click(function() {
        const passwordInput = $(this).parent().find('input');
        const icon = $(this).find('i');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });
});
</script>
@endsection 