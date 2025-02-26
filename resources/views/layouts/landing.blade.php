<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"></noscript>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('cssPage')

    <style>
    .navbar {
        transition: all 0.3s ease;
        background-color: transparent;
        padding: 1rem 0;
    }

    .navbar-scrolled {
        background-color: #002B5B !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 0.5rem 0;
    }

    /* Style untuk link navbar */
    .navbar-nav .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        padding: 0.5rem 1rem !important;
        transition: all 0.3s ease;
        position: relative;
    }

    .navbar-nav .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: #FFA41B;
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .navbar-nav .nav-link:hover::after {
        width: 100%;
    }

    .navbar-nav .nav-link.active {
        color: #FFA41B !important;
    }

    .navbar-nav .nav-link.active::after {
        width: 100%;
    }

    /* Pastikan tombol tetap terlihat */
    .btn-login,
    .btn-register {
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        transition: all 0.3s ease;
    }

    .btn-login {
        background-color: transparent;
        border: 2px solid #FFA41B;
        color: #FFA41B;
    }

    .btn-login:hover {
        background-color: #FFA41B;
        color: white;
    }

    .btn-register {
        background-color: #FFA41B;
        border: 2px solid #FFA41B;
        color: white;
    }

    .btn-register:hover {
        background-color: #FF8C00;
        border-color: #FF8C00;
    }

    /* Logo text protection */
    .navbar-brand {
        position: relative;
        z-index: 1;
    }

    .navbar-brand img {
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('home') }}">Homepage</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('landing.news') ? 'active' : '' }}" 
                           href="{{ route('landing.news') }}">News</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('about') ? 'active' : '' }}" href="{{ url('/about') }}">About</a>
                    </li>
                </ul>
                <div class="d-flex">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-login me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-register">Register</a>
                    @else
                        <a href="{{ auth()->user()->role == 1 ? route('user.dashboard') : route('admin.dashboard') }}" 
                           class="btn btn-primary">Dashboard</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    @yield('content')

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    @yield('jsPage')

    <script>
    $(document).ready(function() {
        // Fungsi untuk mengubah navbar saat scroll
        function toggleNavbarBg() {
            if ($(window).scrollTop() > 50) {
                $('.navbar').addClass('navbar-scrolled');
            } else {
                $('.navbar').removeClass('navbar-scrolled');
            }
        }

        // Jalankan saat halaman dimuat
        toggleNavbarBg();

        // Jalankan saat scroll
        $(window).scroll(toggleNavbarBg);
    });
    </script>
</body>
</html> 