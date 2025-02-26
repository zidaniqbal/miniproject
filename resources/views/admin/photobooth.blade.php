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

    /* Control Section */
    .control-section {
        background: #ffffff;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 1.5rem;
        border: 1px solid #edf2f7;
    }

    .btn-control {
        background-color: #4F46E5;
        border-color: #4F46E5;
        color: #ffffff;
        padding: 0.625rem 1.25rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s ease;
        margin: 0 5px;
        min-width: 120px;
    }

    .btn-control:hover {
        background-color: #4338CA;
        border-color: #4338CA;
    }

    .btn-control:disabled {
        background-color: #9CA3AF;
        border-color: #9CA3AF;
    }

    .layout-select {
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        color: #111827;
        width: 120px !important;
        margin-left: 10px;
    }

    /* Camera and Preview Section */
    .camera-container {
        position: relative;
        background: #000;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    }

    #video {
        width: 100%;
        border-radius: 16px;
    }

    .preview-container {
        background: #ffffff;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid #edf2f7;
    }

    #photoStrip {
        border-radius: 8px;
        width: 100%;
    }

    .btn-primary {
        background-color: #4F46E5;
        border-color: #4F46E5;
        color: #ffffff;
        padding: 0.625rem 1.25rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        background-color: #4338CA;
        border-color: #4338CA;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
    }

    .btn-primary .bi {
        font-size: 1.1em;
    }

    /* Photo Strip Container */
    .photo-strip-container {
        width: 600px;
        height: 800px;
        background: #fff;
        padding: 20px;
        position: relative;
    }

    /* Checkered Background */
    .checkered-bg {
        background-image: linear-gradient(45deg, #002B5B 25%, transparent 25%),
            linear-gradient(-45deg, #002B5B 25%, transparent 25%),
            linear-gradient(45deg, transparent 75%, #002B5B 75%),
            linear-gradient(-45deg, transparent 75%, #002B5B 75%);
        background-size: 40px 40px;
        background-position: 0 0, 0 20px, 20px -20px, -20px 0px;
        padding: 20px;
        border-radius: 8px;
    }

    /* Photo Frame */
    .photo-frame {
        background: white;
        padding: 15px;
        margin-bottom: 20px;
        position: relative;
        border-radius: 4px;
    }

    .photo-frame::after {
        content: '';
        position: absolute;
        bottom: 10px;
        right: 15px;
        width: 24px;
        height: 24px;
        background: #FFA41B;
        clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
    }

    .photo-frame:nth-child(2n)::after {
        left: 15px;
        right: auto;
    }

    /* Photo Placeholder */
    .photo-placeholder {
        width: 100%;
        height: 200px;
        background: #000;
        margin-bottom: 10px;
        border-radius: 4px;
        overflow: hidden;
    }

    /* Dots Decoration */
    .dots {
        display: flex;
        gap: 8px;
        margin-top: 10px;
    }

    .dot {
        width: 8px;
        height: 8px;
        background: #FFA41B;
        border-radius: 50%;
    }

    /* Title Text */
    .strip-title {
        text-align: center;
        margin-top: 20px;
        font-family: 'Arial Black', sans-serif;
    }

    .title-main {
        color: #002B5B;
        font-size: 24px;
        font-weight: 900;
        margin: 0;
    }

    .title-sub {
        color: #FFA41B;
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }

    /* Video Preview */
    #video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Canvas */
    #photoStrip {
        width: 100%;
        height: auto;
        display: block;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Photobooth</h1>
                <a href="{{ route('admin.photobooth.gallery') }}" class="btn btn-primary">
                    <i class="bi bi-images me-2"></i>View Gallery
                </a>
            </div>

            <div class="control-section text-center mb-4">
                <button id="startCamera" class="btn btn-control">
                    <i class="bi bi-camera"></i> Start Camera
                </button>
                <button id="takePhoto" class="btn btn-control" disabled>
                    <i class="bi bi-camera-fill"></i> Take Photo
                </button>
                <button id="savePhotos" class="btn btn-control" disabled>
                    <i class="bi bi-save"></i> Save Photos
                </button>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="camera-container">
                        <video id="video" autoplay playsinline></video>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="checkered-bg">
                        <div class="photo-strip-container">
                            <div class="photo-frame">
                                <div class="photo-placeholder" id="photo1"></div>
                                <div class="dots">
                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                </div>
                            </div>
                            <div class="photo-frame">
                                <div class="photo-placeholder" id="photo2"></div>
                                <div class="dots">
                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                </div>
                            </div>
                            <div class="strip-title">
                                <p class="title-main">MOMENTS</p>
                                <p class="title-sub">WITH FRIENDS</p>
                            </div>
                        </div>
                    </div>
                </div>
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
                <a href="{{ route('admin.photobooth.gallery') }}" class="btn btn-primary">View in Gallery</a>
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
@endsection

@section('jsPage')
<script>
$(document).ready(function() {
    let video;
    let stream;
    let photos = [];
    const maxPhotos = 2;

    function initializeElements() {
        video = document.getElementById('video');
    }

    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                } 
            });
            video.srcObject = stream;
            await video.play();
            $('#takePhoto').prop('disabled', false);
        } catch (err) {
            console.error('Error:', err);
            $('#errorMessage').text('Error accessing camera. Please check permissions.');
            $('#errorModal').modal('show');
        }
    }

    function takePhoto() {
        if (photos.length >= maxPhotos) {
            $('#errorMessage').text('Maximum 2 photos allowed');
            $('#errorModal').modal('show');
            return;
        }

        const canvas = document.createElement('canvas');
        // Gunakan rasio 4:3 untuk foto
        canvas.width = 1280;
        canvas.height = 960;
        const context = canvas.getContext('2d');
        
        // Capture dari video dengan rasio yang benar
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Tambahkan ke array photos
        photos.push(canvas.toDataURL('image/png'));
        
        // Update preview
        const photoElement = document.getElementById(`photo${photos.length}`);
        if (photoElement) {
            photoElement.style.backgroundImage = `url(${photos[photos.length-1]})`;
            photoElement.style.backgroundSize = 'cover';
            photoElement.style.backgroundPosition = 'center';
        }

        if (photos.length === maxPhotos) {
            $('#savePhotos').prop('disabled', false);
        }
    }

    function savePhotos() {
        // Buat canvas final
        const finalCanvas = document.createElement('canvas');
        const ctx = finalCanvas.getContext('2d');
        
        // Set ukuran untuk strip foto
        finalCanvas.width = 600;
        finalCanvas.height = 800;

        // Draw background
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, finalCanvas.width, finalCanvas.height);

        // Draw checkered background
        drawCheckeredBackground(ctx, finalCanvas.width, finalCanvas.height);

        // Buat promise array untuk memastikan semua foto telah di-load
        const promises = photos.map((photoData, index) => {
            return new Promise((resolve) => {
                const img = new Image();
                img.onload = function() {
                    // Posisi untuk setiap foto
                    const margin = 30;
                    const photoWidth = finalCanvas.width - (margin * 2);
                    const photoHeight = 220;
                    const y = margin + (index * (photoHeight + margin + 20));

                    // Draw white frame
                    ctx.fillStyle = 'white';
                    ctx.fillRect(margin - 10, y - 10, photoWidth + 20, photoHeight + 40);

                    // Draw photo
                    ctx.drawImage(img, margin, y, photoWidth, photoHeight);

                    // Draw dots
                    const dotsY = y + photoHeight + 10;
                    drawDots(ctx, margin, dotsY);

                    // Draw star
                    if (index % 2 === 0) {
                        drawStar(ctx, margin, y, '#FFA41B');
                    } else {
                        drawStar(ctx, finalCanvas.width - margin - 20, y, '#FFA41B');
                    }

                    resolve();
                };
                img.src = photoData;
            });
        });

        // Setelah semua foto di-load dan di-draw
        Promise.all(promises).then(() => {
            // Draw title
            ctx.font = 'bold 32px Arial';
            ctx.fillStyle = '#002B5B';
            ctx.textAlign = 'center';
            ctx.fillText('MOMENTS', finalCanvas.width/2, finalCanvas.height - 80);
            
            ctx.font = 'bold 24px Arial';
            ctx.fillStyle = '#FFA41B';
            ctx.fillText('WITH FRIENDS', finalCanvas.width/2, finalCanvas.height - 40);

            // Save the final image
            $.ajax({
                url: '{{ route("admin.photobooth.save") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    image: finalCanvas.toDataURL('image/png')
                },
                success: function(response) {
                    if (response.success) {
                        $('#successModal').modal('show');
                        // Reset
                        photos = [];
                        $('.photo-placeholder').css('backgroundImage', '');
                        $('#savePhotos').prop('disabled', true);
                    }
                },
                error: function() {
                    $('#errorMessage').text('Failed to save photos');
                    $('#errorModal').modal('show');
                }
            });
        });
    }

    function drawCheckeredBackground(ctx, width, height) {
        const squareSize = 40;
        ctx.fillStyle = '#002B5B';
        
        for(let x = 0; x < width; x += squareSize) {
            for(let y = 0; y < height; y += squareSize) {
                if((x + y) % (squareSize * 2) === 0) {
                    ctx.fillRect(x, y, squareSize, squareSize);
                }
            }
        }
    }

    function drawDots(ctx, x, y) {
        const dotRadius = 4;
        const dotSpacing = 12;
        ctx.fillStyle = '#FFA41B';
        
        for(let i = 0; i < 3; i++) {
            ctx.beginPath();
            ctx.arc(x + (i * dotSpacing), y, dotRadius, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    function drawStar(ctx, x, y, color) {
        ctx.fillStyle = color;
        ctx.beginPath();
        ctx.moveTo(x + 10, y);
        for(let i = 0; i < 5; i++) {
            ctx.lineTo(x + 10 + Math.cos((i * 4 * Math.PI / 5) - Math.PI/2) * 10,
                      y + Math.sin((i * 4 * Math.PI / 5) - Math.PI/2) * 10);
        }
        ctx.closePath();
        ctx.fill();
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
});
</script>
@endsection 