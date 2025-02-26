@extends('layouts.landing')

@section('cssPage')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #002B5B 0%, #1a4d8c 100%);
        min-height: 100vh;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.5;
    }

    /* Feature Cards */
    .feature-card {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease;
        background: white;
        box-shadow: 0 5px 15px rgba(0, 43, 91, 0.1);
    }

    .feature-card:hover {
        transform: translateY(-5px);
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        background: #FFA41B;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        color: white;
        font-size: 1.5rem;
    }

    /* Statistics Section */
    .stats-section {
        background: #f8f9fa;
    }

    .stat-card {
        text-align: center;
        padding: 2rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 43, 91, 0.05);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #002B5B;
        margin-bottom: 0.5rem;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #FFA41B 0%, #FF8C00 100%);
    }

    .btn-light-outline {
        border: 2px solid white;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-light-outline:hover {
        background: white;
        color: #FFA41B;
    }

    /* Additional Sections */
    .section-title {
        color: #002B5B;
        margin-bottom: 1rem;
    }

    .section-subtitle {
        color: #6c757d;
        margin-bottom: 3rem;
    }

    /* Manga Section Preview */
    .manga-preview {
        background: #f8f9fa;
        padding: 4rem 0;
    }

    .manga-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 43, 91, 0.1);
    }

    /* Gallery Preview */
    .gallery-preview {
        background: white;
        padding: 4rem 0;
    }

    .gallery-item {
        border-radius: 15px;
        overflow: hidden;
        position: relative;
    }

    .gallery-item img {
        transition: transform 0.3s ease;
    }

    .gallery-item:hover img {
        transform: scale(1.05);
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section d-flex align-items-center">
    <div class="container mt-5 pt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center" data-aos="fade-up">
                <h1 class="display-4 fw-bold mb-4">Your All-in-One Digital Hub</h1>
                <p class="lead mb-5">Discover manga, track your goals, create memories in our photobooth, and stay updated with the latest news.</p>
                
                @guest
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 me-sm-3">
                            Get Started - It's Free
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-light-outline btn-lg px-4">
                            Login
                        </a>
                    </div>
                @else
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
                        <a href="{{ auth()->user()->role == 1 ? route('user.dashboard') : route('admin.dashboard') }}" 
                           class="btn btn-light btn-lg px-4">
                            Go to Dashboard
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title fw-bold">Explore Our Features</h2>
            <p class="section-subtitle lead">Everything you need in one place</p>
        </div>
        <div class="row g-4">
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card card h-100 p-4">
                    <div class="feature-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <h4>Manga Library</h4>
                    <p class="text-muted">Access a vast collection of manga with detailed information and reading capabilities</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card card h-100 p-4">
                    <div class="feature-icon">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h4>Goal Tracking</h4>
                    <p class="text-muted">Set and track your personal goals with our intuitive goal management system</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card card h-100 p-4">
                    <div class="feature-icon">
                        <i class="bi bi-camera"></i>
                    </div>
                    <h4>Photobooth</h4>
                    <p class="text-muted">Create and save memorable moments with our built-in photobooth feature</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card card h-100 p-4">
                    <div class="feature-icon">
                        <i class="bi bi-newspaper"></i>
                    </div>
                    <h4>News Feed</h4>
                    <p class="text-muted">Stay updated with the latest news across various categories</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Manga Preview Section -->
<section class="manga-preview">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title fw-bold">Explore Manga</h2>
            <p class="section-subtitle">Discover your next favorite manga series</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="manga-card">
                    <img src="https://via.placeholder.com/400x600" class="w-100" alt="Manga Preview">
                </div>
            </div>
            <!-- Add more manga previews as needed -->
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card">
                    <div class="stat-number">10K+</div>
                    <p class="text-muted mb-0">Manga Titles</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card">
                    <div class="stat-number">5K+</div>
                    <p class="text-muted mb-0">Active Users</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card">
                    <div class="stat-number">1M+</div>
                    <p class="text-muted mb-0">Photos Created</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5 text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center" data-aos="fade-up">
                <h2 class="fw-bold mb-4">Ready to Get Started?</h2>
                <p class="lead mb-4">Join our community and explore all the features we have to offer.</p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">Create Free Account</a>
                @else
                    <a href="{{ auth()->user()->role == 1 ? route('user.dashboard') : route('admin.dashboard') }}" 
                       class="btn btn-light btn-lg px-5">Go to Dashboard</a>
                @endguest
            </div>
        </div>
    </div>
</section>
@endsection

@section('jsPage')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });
</script>
@endsection 