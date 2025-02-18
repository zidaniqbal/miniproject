@extends('layouts.app')

@section('cssPage')
<style>
.gallery-container {
    padding: 20px;
}

.gallery-header {
    margin-bottom: 2rem;
}

.image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.image-card {
    position: relative;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.image-wrapper {
    position: relative;
    padding-top: 75%; /* 4:3 Aspect Ratio */
}

.image-wrapper img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.image-card:hover img {
    transform: scale(1.05);
}

.image-source {
    position: absolute;
    top: 1rem;
    left: 1rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    color: #fff;
    background: rgba(0, 0, 0, 0.5);
}

.loading-spinner {
    display: none;
    justify-content: center;
    padding: 2rem;
}

#loadMore {
    display: block;
    margin: 2rem auto;
    padding: 0.75rem 2rem;
    background: #4F46E5;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

#loadMore:hover {
    background: #4338CA;
    transform: translateY(-1px);
}

#loadMore:disabled {
    background: #9CA3AF;
    cursor: not-allowed;
}

.image-actions {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s;
}

.image-card:hover .image-actions {
    opacity: 1;
}

.action-btn {
    background: rgba(0, 0, 0, 0.7);
    color: white;
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.action-btn:hover {
    background: rgba(0, 0, 0, 0.9);
    transform: scale(1.1);
}

.preview-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.9);
    z-index: 1050;
    padding: 2rem;
    justify-content: center;
    align-items: center;
}

.preview-content {
    position: relative;
    max-width: 90vw;
    max-height: 90vh;
    margin: auto;
    background: transparent;
}

.preview-image {
    max-width: 100%;
    max-height: 85vh;
    object-fit: contain;
    display: block;
    margin: auto;
}

.preview-close {
    position: absolute;
    top: -2rem;
    right: -2rem;
    color: white;
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    z-index: 1051;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.download-options {
    position: absolute;
    bottom: 1rem;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    padding: 0.75rem;
    border-radius: 8px;
    display: flex;
    gap: 0.5rem;
    z-index: 1051;
}

.download-btn {
    position: relative;
    background: #4F46E5;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
    min-width: 100px;
}

.download-btn:disabled {
    background: #9CA3AF;
    cursor: wait;
}

.download-btn .spinner-border {
    width: 1rem;
    height: 1rem;
    margin-right: 0.5rem;
    display: none;
}

.download-btn.loading .spinner-border {
    display: inline-block;
}

.download-btn.success {
    background: #10B981;
}

.download-btn.error {
    background: #EF4444;
}

.toast-container {
    position: fixed;
    bottom: 1rem;
    right: 1rem;
    z-index: 1060;
}

.toast {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 300px;
    opacity: 0;
    transition: opacity 0.3s;
}

.toast.success {
    border-left: 4px solid #10B981;
}

.toast.error {
    border-left: 4px solid #EF4444;
}

.toast.show {
    opacity: 1;
}
</style>
@endsection

@section('content')
<div class="gallery-container">
    <div class="gallery-header">
        <h1 class="page-title">Gallery</h1>
    </div>

    <div class="image-grid" id="imageGrid"></div>
    
    <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <button id="loadMore">Load More Images</button>
</div>

<!-- Preview Modal -->
<div class="preview-modal" id="previewModal">
    <button class="preview-close">&times;</button>
    <div class="preview-content">
        <img src="" alt="Preview" class="preview-image" id="previewImage">
        <div class="download-options">
            <button class="download-btn" data-quality="preview">
                <span class="spinner-border spinner-border-sm" role="status"></span>
                <span class="btn-text">Low quality</span>
            </button>
            <button class="download-btn" data-quality="full">
                <span class="spinner-border spinner-border-sm" role="status"></span>
                <span class="btn-text">Medium quality</span>
            </button>
            <button class="download-btn" data-quality="download">
                <span class="spinner-border spinner-border-sm" role="status"></span>
                <span class="btn-text">High quality</span>
            </button>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>
@endsection

@section('jsPage')
<script>
let currentPage = 1;
let isLoading = false;
let currentImageData = null;

function loadImages(page = 1, append = false) {
    if (isLoading) return;
    isLoading = true;

    $('.loading-spinner').css('display', 'flex');
    $('#loadMore').prop('disabled', true);

    $.ajax({
        url: '{{ route("user.gallery.search") }}',
        method: 'GET',
        data: { page: page },
        success: function(response) {
            if (response.success) {
                if (!append) {
                    $('#imageGrid').empty();
                }
                renderImages(response.images, append);
                $('#loadMore').toggle(response.hasMore).prop('disabled', false);
            } else {
                showError(response.message);
            }
        },
        error: function(xhr) {
            showError('Failed to load images. Please try again.');
        },
        complete: function() {
            isLoading = false;
            $('.loading-spinner').hide();
        }
    });
}

function renderImages(images, append) {
    const grid = $('#imageGrid');
    
    images.forEach(image => {
        // Escape data untuk JSON
        const imageDataAttr = encodeURIComponent(JSON.stringify(image));
        
        const card = `
            <div class="image-card">
                <div class="image-wrapper">
                    <img src="${image.thumbnail}" alt="Image ${image.id}" loading="lazy">
                    <div class="image-actions">
                        <button class="action-btn preview-btn" 
                                data-image="${imageDataAttr}"
                                onclick="openPreview(this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <span class="image-source">${image.source}</span>
                </div>
            </div>
        `;
        grid.append(card);
    });
}

function showError(message) {
    $('#imageGrid').html(`
        <div class="alert alert-danger text-center w-100" role="alert">
            ${message}
        </div>
    `);
}

function openPreview(button) {
    try {
        // Decode dan parse data gambar
        const imageData = JSON.parse(decodeURIComponent($(button).data('image')));
        currentImageData = imageData;
        
        const modal = $('#previewModal');
        const previewImage = $('#previewImage');
        
        // Set preview image
        previewImage.attr('src', imageData.preview);
        
        // Show modal
        modal.css('display', 'flex').hide().fadeIn(300);
    } catch (error) {
        console.error('Error opening preview:', error);
        alert('Failed to open preview. Please try again.');
    }
}

function showToast(message, type = 'success') {
    const toastContainer = $('#toastContainer');
    const toast = $(`
        <div class="toast ${type}">
            <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-x-circle'}"></i>
            <span>${message}</span>
        </div>
    `);
    
    toastContainer.append(toast);
    
    // Show with animation
    setTimeout(() => toast.addClass('show'), 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.removeClass('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

async function downloadImage(button, url, filename) {
    const $btn = $(button);
    const originalText = $btn.find('.btn-text').text();
    
    try {
        // Disable all download buttons
        $('.download-btn').prop('disabled', true);
        $btn.addClass('loading');
        
        // Validate URL
        if (!url) {
            throw new Error('Invalid download URL');
        }

        // Start download
        const response = await fetch(url);
        
        // Check if response is ok
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Get the blob
        const blob = await response.blob();
        
        // Validate file size (max 50MB)
        if (blob.size > 50 * 1024 * 1024) {
            throw new Error('File size too large');
        }
        
        // Create download link
        const downloadUrl = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.download = filename;
        
        // Trigger download
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Cleanup
        window.URL.revokeObjectURL(downloadUrl);
        
        // Show success
        $btn.addClass('success');
        showToast('Download completed successfully');
        
    } catch (error) {
        console.error('Download failed:', error);
        $btn.addClass('error');
        showToast(error.message || 'Failed to download image', 'error');
        
    } finally {
        // Reset button state after 2 seconds
        setTimeout(() => {
            $btn.removeClass('loading success error');
            $btn.find('.btn-text').text(originalText);
            $('.download-btn').prop('disabled', false);
        }, 2000);
    }
}

$(document).ready(function() {
    // Initial load
    loadImages();

    // Load more handler
    $('#loadMore').click(function() {
        currentPage++;
        loadImages(currentPage, true);
    });

    // Close preview modal
    $('.preview-close').click(function() {
        $('#previewModal').fadeOut(300);
        // Clear current image data when closing
        currentImageData = null;
    });
    
    // Close modal on background click
    $('#previewModal').click(function(e) {
        if (e.target === this) {
            $(this).fadeOut(300);
            // Clear current image data when closing
            currentImageData = null;
        }
    });

    // Download buttons handler
    $('.download-btn').click(function() {
        if (!currentImageData) {
            showToast('No image data available', 'error');
            return;
        }
        
        const quality = $(this).data('quality');
        const url = currentImageData[quality];
        const filename = `image-${currentImageData.id}-${quality}.jpg`;
        
        downloadImage(this, url, filename);
    });

    // Prevent closing when clicking inside preview content
    $('.preview-content').click(function(e) {
        e.stopPropagation();
    });

    // Handle escape key to close modal
    $(document).keydown(function(e) {
        if (e.key === 'Escape') {
            $('#previewModal').fadeOut(300);
            currentImageData = null;
        }
    });
});
</script>
@endsection 