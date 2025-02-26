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
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(79, 70, 229, 0.1);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-primary:disabled {
        background: #9CA3AF;
        cursor: wait;
    }

    .btn-primary .spinner-border {
        width: 1.2rem;
        height: 1.2rem;
        margin-right: 0.5rem;
    }

    .btn-primary.success {
        background-color: #10B981;
        border-color: #10B981;
    }

    .btn-primary.error {
        background-color: #EF4444;
        border-color: #EF4444;
    }

    .btn-control {
        padding: 0.625rem 1.25rem;
        border-color: #E5E7EB;
        color: #6B7280;
        background-color: #ffffff;
        border-radius: 8px;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .btn-control:hover:not(:disabled) {
        background-color: #F9FAFB;
        border-color: #E5E7EB;
        color: #4F46E5;
    }

    .btn-control:disabled {
        background-color: #F3F4F6;
        border-color: #E5E7EB;
        color: #9CA3AF;
        cursor: not-allowed;
    }

    /* Photo Count Selector */
    .photo-count-selector {
        display: flex;
        gap: 15px;
        margin-bottom: 1.5rem;
        background: #ffffff;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid #edf2f7;
    }

    .count-option {
        padding: 0.625rem 1.25rem;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
        color: #6B7280;
        position: relative;
    }

    .count-option.active {
        background-color: #4F46E5;
        border-color: #4F46E5;
        color: #ffffff;
    }

    .count-option:hover:not(.active):not([style*="opacity"]) {
        background-color: #F9FAFB;
        border-color: #E5E7EB;
        color: #4F46E5;
    }

    .coming-soon-badge {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #dc3545;
        color: white;
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 12px;
        white-space: nowrap;
    }

    /* Template and Preview Section */
    .template-preview-section {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid #edf2f7;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1rem;
    }

    .template-selector {
        display: none;
        margin-bottom: 1.5rem;
    }

    .template-options {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding-bottom: 1rem;
    }

    .template-option {
        flex: 0 0 auto;
        width: 80px;
        height: 200px;
        cursor: pointer;
        border: 3px solid transparent;
        border-radius: 8px;
        transition: all 0.2s ease;
        overflow: hidden;
    }

    .template-option.active {
        border-color: #4F46E5;
    }

    .template-option img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .preview-container {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }

    .preview-wrapper {
        position: relative;
        width: 100%;
        padding-bottom: 250%;
        overflow: hidden;
    }

    .photo-placeholder {
        position: absolute;
        background-size: cover;
        background-position: center;
        z-index: 1;
    }

    .preview-template {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        z-index: 2;
    }

    /* Live Preview */
    .live-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }

    .live-photo {
        width: 100px;
        height: 100px;
        background-size: cover;
        background-position: center;
        border: 2px solid #4F46E5;
        border-radius: 8px;
        position: relative;
    }

    .live-photo.empty {
        border: 2px dashed #ccc;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6B7280;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 16px;
        border: none;
    }

    .modal-header {
        border-bottom: 1px solid #edf2f7;
        padding: 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #edf2f7;
        padding: 1.5rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .template-option {
            width: 60px;
            height: 150px;
        }

        .preview-container {
            max-width: 300px;
        }
    }

    /* Camera Container Styles */
    .camera-container {
        position: relative;
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        overflow: hidden;
        border-radius: 8px;
    }

    #video {
        width: 100%;
        height: auto;
        transform: scaleX(-1); /* Mirror effect */
    }

    /* Tutorial Styles */
    .tutorial-steps {
        padding: 15px;
        background: #f8fafc;
        border-radius: 8px;
    }

    .step {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .step-number {
        background: #4F46E5;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 500;
        flex-shrink: 0;
    }

    .step-text {
        color: #4B5563;
        font-size: 15px;
    }

    @media (max-width: 768px) {
        .step {
            margin-bottom: 12px;
        }
        
        .step-text {
            font-size: 14px;
        }
    }

    /* Main Layout Styles */
    .gallery-container {
        padding: 20px;
    }

    .gallery-header {
        margin-bottom: 2rem;
    }

    /* Toast Notifications */
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

    /* Loading Screen */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-content {
        text-align: center;
    }

    .loading-spinner {
        width: 3rem;
        height: 3rem;
        color: #4F46E5;
    }

    .loading-text {
        margin-top: 1rem;
        color: #4B5563;
        font-size: 0.875rem;
    }

    /* Preview Actions */
    .preview-actions {
        margin-top: 2rem;
        text-align: center;
    }

    .preview-actions .btn {
        min-width: 180px;
        height: 48px;
        font-size: 1rem;
    }

    /* Page Header Button */
    .page-header .btn {
        min-width: 160px;
        height: 44px;
        font-size: 0.95rem;
        background-color: #4F46E5;
        border-color: #4F46E5;
    }

    .page-header .btn:hover {
        background-color: #4338CA;
        border-color: #4338CA;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="settings-wrapper">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title">Photobooth</h1>
            <a href="{{ route('user.photobooth.gallery') }}" class="btn btn-primary">
                <i class="bi bi-images me-2"></i>View Gallery
            </a>
        </div>

        <!-- Tutorial Section -->
        <div class="filter-section mb-4">
            <h5 class="section-title">
                <i class="bi bi-info-circle me-2"></i>Cara Pakai Photobooth
            </h5>
            <div class="tutorial-steps">
                <div class="step mb-3">
                    <span class="step-number">1</span>
                    <span class="step-text">Pilih jumlah foto yang kamu mau (untuk saat ini tersedia 3 foto)</span>
                </div>
                <div class="step mb-3">
                    <span class="step-number">2</span>
                    <span class="step-text">Klik tombol "Start Camera" untuk nyalain kamera</span>
                </div>
                <div class="step mb-3">
                    <span class="step-number">3</span>
                    <span class="step-text">Atur posisi kamu di depan kamera. Tenang, tampilannya seperti cermin kok!</span>
                </div>
                <div class="step mb-3">
                    <span class="step-number">4</span>
                    <span class="step-text">Klik tombol "Take Photo" untuk ambil foto. Lakukan sampai 3 kali ya</span>
                </div>
                <div class="step mb-3">
                    <span class="step-number">5</span>
                    <span class="step-text">Pilih template border yang kamu suka</span>
                </div>
                <div class="step mb-3">
                    <span class="step-number">6</span>
                    <span class="step-text">Kalau sudah oke, klik "Save Photos" untuk simpan hasilnya</span>
                </div>
                <div class="step">
                    <span class="step-number">7</span>
                    <span class="step-text">Kamu bisa lihat dan download hasilnya di Gallery</span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="filter-section mb-4">
            <!-- Photo Count Selector -->
            <div class="photo-count-selector mb-4">
                <div class="count-option" data-count="2" style="opacity: 0.5; cursor: not-allowed;">
                    <span>2 Photos</span>
                    <div class="coming-soon-badge">Coming Soon</div>
                </div>
                <div class="count-option" data-count="3">
                    <span>3 Photos</span>
                </div>
                <div class="count-option" data-count="4" style="opacity: 0.5; cursor: not-allowed;">
                    <span>4 Photos</span>
                    <div class="coming-soon-badge">Coming Soon</div>
                </div>
            </div>

            <!-- Camera Section -->
            <div class="camera-container mb-3">
                <video id="video" autoplay playsinline></video>
            </div>
            
            <!-- Control Section -->
            <div class="control-section mb-3">
                <button id="startCamera" class="btn btn-control">
                    <i class="bi bi-camera"></i> Start Camera
                </button>
                <button id="takePhoto" class="btn btn-control" disabled>
                    <i class="bi bi-camera-fill"></i> Take Photo
                </button>
            </div>

            <!-- Live Preview -->
            <div class="live-preview" id="livePreview"></div>
        </div>

        <!-- Template and Preview Section -->
        <div class="filter-section">
            <!-- Template Selector -->
            <div class="template-selector mb-3">
                <h5 class="section-title mb-3">Select Template</h5>
                <div class="template-options">
                    <div class="template-option" data-template="template1">
                        <img src="{{ asset('images/photobooth/templates/1.png') }}" alt="Border Style 1">
                    </div>
                    <div class="template-option" data-template="template2">
                        <img src="{{ asset('images/photobooth/templates/2.png') }}" alt="Border Style 2">
                    </div>
                    <div class="template-option" data-template="template3">
                        <img src="{{ asset('images/photobooth/templates/3.png') }}" alt="Border Style 3">
                    </div>
                    <div class="template-option" data-template="template4">
                        <img src="{{ asset('images/photobooth/templates/4.png') }}" alt="Border Style 4">
                    </div>
                    <div class="template-option" data-template="template5">
                        <img src="{{ asset('images/photobooth/templates/5.png') }}" alt="Border Style 5">
                    </div>
                </div>
            </div>

            <!-- Preview Container -->
            <div class="preview-container">
                <!-- Template dan foto akan ditampilkan di sini -->
            </div>

            <!-- Preview Actions -->
            <div class="preview-actions">
                <button id="savePhotos" class="btn btn-primary" disabled>
                    <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                    <i class="bi bi-save me-2"></i>
                    <span class="btn-text">Save Photos</span>
                </button>
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
                    <p class="mt-3">Photos saved successfully!</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('user.photobooth.gallery') }}" class="btn btn-primary">View in Gallery</a>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Camera Access Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="bi bi-camera-video-off text-danger" style="font-size: 3rem;"></i>
                </div>
                <div id="errorMessage" class="alert alert-warning">
                    <!-- Error message will be inserted here -->
                </div>
                <div class="mt-3">
                    <h6>Troubleshooting Steps:</h6>
                    <ol class="ps-3">
                        <li>Make sure your camera is properly connected</li>
                        <li>Allow camera access in your browser settings</li>
                        <li>Close other applications that might be using the camera</li>
                        <li>Try refreshing the page</li>
                    </ol>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="location.reload()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Refresh Page
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Screen -->
<div class="loading-overlay">
    <div class="loading-content">
        <div class="spinner-border loading-spinner" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="loading-text">Processing your photos...</div>
    </div>
</div>
@endsection

@section('jsPage')
<script>
$(document).ready(function() {
    let video;
    let stream;
    const photos = [];
    let selectedPhotoCount = 0;
    let selectedTemplate = null;

    // Initialize elements
    function initializeElements() {
        video = document.getElementById('video');
        $('#takePhoto').prop('disabled', true);
        $('#savePhotos').prop('disabled', true);
        $('.template-selector').hide();
        // Set default to 3 photos
        $('.count-option[data-count="3"]').addClass('active');
        selectedPhotoCount = 3;
        updateLivePreview();
    }

    // Photo count selector
    $('.count-option').click(function() {
        const count = $(this).data('count');
        if (count === 2 || count === 4) {
            $('#errorMessage').text('This photo count option is coming soon!');
            $('#errorModal').modal('show');
            return;
        }

        $('.count-option').removeClass('active');
        $(this).addClass('active');
        selectedPhotoCount = parseInt(count);
        photos.length = 0;
        updateLivePreview();
        $('#takePhoto').prop('disabled', !stream);
        $('#savePhotos').prop('disabled', true);
        $('.template-selector').hide();
    });

    // Template selector
    $('.template-option').click(function() {
        $('.template-option').removeClass('active');
        $(this).addClass('active');
        selectedTemplate = $(this).data('template');
        updatePreview();
    });

    // Start camera
    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: 1280,
                    height: 720
                }, 
                audio: false 
            });
            video.srcObject = stream;
            $('#startCamera').prop('disabled', true);
            $('#takePhoto').prop('disabled', !selectedPhotoCount);
        } catch (err) {
            $('#errorMessage').text('Camera access required: ' + err.message);
            $('#errorModal').modal('show');
        }
    }

    // Take photo with mirror effect
    function takePhoto() {
        if (photos.length >= selectedPhotoCount) {
            $('#errorMessage').text(`Maximum ${selectedPhotoCount} photos allowed`);
            $('#errorModal').modal('show');
            return;
        }

        const canvas = document.createElement('canvas');
        canvas.width = 1280;
        canvas.height = 720;
        const context = canvas.getContext('2d');
        
        // Mirror the photo
        context.scale(-1, 1);
        context.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
        // Reset transform
        context.setTransform(1, 0, 0, 1, 0, 0);
        
        photos.push(canvas.toDataURL('image/png'));
        
        updateLivePreview();

        if (photos.length === selectedPhotoCount) {
            $('#takePhoto').prop('disabled', true);
            $('.template-selector').fadeIn();
        }
    }

    // Update preview
    function updatePreview() {
        const container = $('.preview-container');
        container.empty();

        if (photos.length === selectedPhotoCount && selectedTemplate) {
            const previewWrapper = $('<div>')
                .addClass('preview-wrapper');
            container.append(previewWrapper);

            // Add photos first
            const positions = getPhotoPositions(selectedTemplate);
            photos.forEach((photo, index) => {
                const pos = positions[index];
                const photoDiv = $('<div>')
                    .addClass('photo-placeholder')
                    .css({
                        left: pos.x + '%',
                        top: pos.y + '%',
                        width: pos.w + '%',
                        height: pos.h + '%',
                        backgroundImage: `url(${photo})`,
                        backgroundSize: 'cover',
                        backgroundPosition: 'center'
                    });
                previewWrapper.append(photoDiv);
            });

            // Add template on top
            const template = $('.template-option.active img').clone();
            template.addClass('preview-template');
            previewWrapper.append(template);

            $('#savePhotos').prop('disabled', false);
        }
    }

    // Update posisi foto untuk setiap template
    function getPhotoPositions(template) {
        switch(template) {
            case 'template1':
                return [
                    { x: 0, y: 0, w: 100, h: 33.33 },
                    { x: 0, y: 33.33, w: 100, h: 33.33 },
                    { x: 0, y: 66.66, w: 100, h: 33.33 }
                ];
            case 'template2':
                return [
                    { x: 0, y: 0, w: 100, h: 33.33 },
                    { x: 0, y: 33.33, w: 100, h: 33.33 },
                    { x: 0, y: 66.66, w: 100, h: 33.33 }
                ];
            case 'template3':
                return [
                    { x: 0, y: 0, w: 100, h: 33.33 },
                    { x: 0, y: 33.33, w: 100, h: 33.33 },
                    { x: 0, y: 66.66, w: 100, h: 33.33 }
                ];
            case 'template4':
                return [
                    { x: 0, y: 0, w: 100, h: 33.33 },
                    { x: 0, y: 33.33, w: 100, h: 33.33 },
                    { x: 0, y: 66.66, w: 100, h: 33.33 }
                ];
            case 'template5':
                return [
                    { x: 0, y: 0, w: 100, h: 33.33 },
                    { x: 0, y: 33.33, w: 100, h: 33.33 },
                    { x: 0, y: 66.66, w: 100, h: 33.33 }
                ];
            default:
                return [];
        }
    }

    // Save photos with loading state
    function savePhotos() {
        const $btn = $('#savePhotos');
        const $spinner = $btn.find('.spinner-border');
        const $btnText = $btn.find('.btn-text');
        const originalText = $btnText.text();
        
        // Show loading overlay
        $('.loading-overlay').css('display', 'flex').hide().fadeIn(300);
        
        // Disable button and show spinner
        $btn.prop('disabled', true);
        $spinner.removeClass('d-none');
        $btnText.text('Saving...');

        // Create final canvas
        const finalCanvas = document.createElement('canvas');
        const ctx = finalCanvas.getContext('2d');
        
        const templateImg = $('.template-option.active img')[0];
        finalCanvas.width = templateImg.naturalWidth;
        finalCanvas.height = templateImg.naturalHeight;
        
        const positions = getPhotoPositions(selectedTemplate);
        let loadedPhotos = 0;
        let loadedTemplate = false;

        function checkAllLoaded() {
            if (loadedPhotos === photos.length && loadedTemplate) {
                $.ajax({
                    url: '{{ route("user.photobooth.save") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        image: finalCanvas.toDataURL('image/png')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Hide loading overlay
                            $('.loading-overlay').fadeOut(300);
                            
                            // Show success state
                            $btn.addClass('success');
                            $btnText.text('Saved!');
                            
                            // Show success modal
                            $('#successModal').modal('show');
                            
                            // Reset state
                            photos.length = 0;
                            selectedTemplate = null;
                            $('.template-option').removeClass('active');
                            $('.template-selector').hide();
                            updatePreview();
                            
                            // Reset button after delay
                            setTimeout(() => {
                                $btn.removeClass('success');
                                $btn.prop('disabled', true);
                                $spinner.addClass('d-none');
                                $btnText.text(originalText);
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        // Hide loading overlay
                        $('.loading-overlay').fadeOut(300);
                        
                        // Show error state
                        $btn.addClass('error');
                        $btnText.text('Error!');
                        
                        // Show error modal
                        $('#errorMessage').text('Failed to save photos: ' + (xhr.responseText || 'Unknown error'));
                        $('#errorModal').modal('show');
                        
                        // Reset button after delay
                        setTimeout(() => {
                            $btn.removeClass('error');
                            $btn.prop('disabled', false);
                            $spinner.addClass('d-none');
                            $btnText.text(originalText);
                        }, 2000);
                    }
                });
            }
        }

        // Draw photos first with full coverage
        photos.forEach((photoData, index) => {
            const img = new Image();
            img.onload = function() {
                const pos = positions[index];
                const x = (pos.x / 100) * finalCanvas.width;
                const y = (pos.y / 100) * finalCanvas.height;
                const w = (pos.w / 100) * finalCanvas.width;
                const h = (pos.h / 100) * finalCanvas.height;

                // Calculate scaling to cover the entire area
                const scale = Math.max(w / img.width, h / img.height);
                const scaledWidth = img.width * scale;
                const scaledHeight = img.height * scale;
                
                // Center the image
                const offsetX = x + (w - scaledWidth) / 2;
                const offsetY = y + (h - scaledHeight) / 2;

                ctx.drawImage(img, offsetX, offsetY, scaledWidth, scaledHeight);
                loadedPhotos++;
                checkAllLoaded();
            };
            img.src = photoData;
        });

        // Draw template on top
        const templateImage = new Image();
        templateImage.onload = function() {
            ctx.drawImage(templateImage, 0, 0, finalCanvas.width, finalCanvas.height);
            loadedTemplate = true;
            checkAllLoaded();
        };
        templateImage.src = templateImg.src;
    }

    function updateLivePreview() {
        const container = $('#livePreview');
        container.empty();

        // Create slots for all photos
        for (let i = 0; i < selectedPhotoCount; i++) {
            const photoDiv = $('<div>')
                .addClass('live-photo ' + (photos[i] ? '' : 'empty'))
                .css(photos[i] ? {
                    backgroundImage: `url(${photos[i]})`
                } : {});

            if (!photos[i]) {
                photoDiv.text((i + 1));
            }

            if (photos[i]) {
                $('<div>')
                    .addClass('photo-number')
                    .text(i + 1)
                    .appendTo(photoDiv);
            }

            container.append(photoDiv);
        }
    }

    // Initialize
    initializeElements();

    // Event listeners
    $('#startCamera').on('click', startCamera);
    $('#takePhoto').on('click', takePhoto);
    $('#savePhotos').on('click', savePhotos);

    // Cleanup
    window.addEventListener('beforeunload', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });

    // Update safe zone dimensions based on template
    function updateSafeZone() {
        const video = document.getElementById('video');
        const safeZone = $('.safe-zone');
        const videoWidth = video.offsetWidth;
        const videoHeight = video.offsetHeight;
        
        // Get template dimensions
        const template = $('.template-option.active img');
        if (template.length) {
            const templateRatio = template[0].naturalHeight / template[0].naturalWidth;
            
            // Calculate safe zone dimensions based on template ratio
            let zoneWidth, zoneHeight;
            
            if (templateRatio > 1) { // Portrait template
                zoneHeight = videoHeight * 0.8;
                zoneWidth = zoneHeight / templateRatio;
            } else { // Landscape or square template
                zoneWidth = videoWidth * 0.8;
                zoneHeight = zoneWidth * templateRatio;
            }
            
            // Center the safe zone
            const left = (videoWidth - zoneWidth) / 2;
            const top = (videoHeight - zoneHeight) / 2;
            
            safeZone.css({
                width: zoneWidth + 'px',
                height: zoneHeight + 'px',
                left: left + 'px',
                top: top + 'px'
            });
        }
    }

    // Update safe zone when template changes
    $('.template-option').click(function() {
        if (stream) {
            setTimeout(updateSafeZone, 100);
        }
    });

    // Update safe zone when camera starts
    $('#startCamera').click(function() {
        startCamera().then(() => {
            setTimeout(updateSafeZone, 500); // Wait for video to load
        });
    });

    // Update safe zone on window resize
    $(window).resize(function() {
        if (stream) {
            updateSafeZone();
        }
    });
});
</script>
@endsection 