<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"></noscript>

    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="{{ Auth::check() && Auth::user()->role == 2 ? 'admin-theme' : 'user-theme' }}">
    @auth
        @if(Auth::user()->role >= 1)
            <!-- Mobile Toggle Button -->
            <button class="mobile-toggle d-md-none" id="mobileToggle">
                <i class="bi bi-list" style="font-size: 1.5rem;"></i>
            </button>

            <!-- Sidebar -->
            <div class="sidebar {{ Auth::user()->role == 2 ? 'admin-sidebar' : 'user-sidebar' }}" id="sidebar">
                <div class="sidebar-header">
                    <div class="profile-info">
                        <div class="avatar">
                            <i class="bi bi-person-circle text-white"></i>
                        </div>
                        <div class="profile-details">
                            <h6>{{ Auth::user()->name }}</h6>
                            <small>{{ Auth::user()->role == 2 ? 'Administrator' : 'User' }}</small>
                        </div>
                    </div>
                    <button class="sidebar-toggle d-none d-md-block" id="sidebarToggle">
                        <i class="bi bi-chevron-left" style="font-size: 1.2rem;"></i>
                    </button>
                </div>
                
                <ul class="sidebar-menu">
                    @if(Auth::user()->role == 2)
                        <!-- Admin Menu -->
                        <li>
                            <a href="{{ route('admin.dashboard') }}" data-title="Dashboard">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-title="User Management">
                                <i class="bi bi-people"></i>
                                <span>User Management</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-title="Settings">
                                <i class="bi bi-gear"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                    @else
                        <!-- User Menu -->
                        <li>
                            <a href="{{ route('user.dashboard') }}" data-title="Dashboard">
                                <i class="bi bi-grid"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-title="Profile">
                                <i class="bi bi-person"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" data-title="Notifications">
                                <i class="bi bi-bell"></i>
                                <span>Notifications</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           data-title="Logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Sidebar Overlay -->
            <div class="sidebar-overlay" id="sidebarOverlay"></div>
        @endif
    @endauth

    <!-- Main Content -->
    <div class="{{ Auth::check() && Auth::user()->role >= 1 ? 'main-content' : '' }}">
        @yield('content')
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileToggle = document.getElementById('mobileToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Desktop toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    this.querySelector('i').classList.toggle('bi-chevron-left');
                    this.querySelector('i').classList.toggle('bi-chevron-right');
                    if (!window.matchMedia('(max-width: 768px)').matches) {
                        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                    }
                });
            }

            // Mobile toggle
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });
            }

            // Overlay click
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }

            // Check localStorage on desktop
            if (!window.matchMedia('(max-width: 768px)').matches) {
                const sidebarState = localStorage.getItem('sidebarCollapsed');
                if (sidebarState === 'true') {
                    sidebar.classList.add('collapsed');
                    if (sidebarToggle) {
                        sidebarToggle.querySelector('i').classList.replace('bi-chevron-left', 'bi-chevron-right');
                    }
                }
            }

            // Handle resize
            window.addEventListener('resize', function() {
                if (window.matchMedia('(max-width: 768px)').matches) {
                    sidebar.classList.remove('collapsed');
                    if (sidebarToggle) {
                        sidebarToggle.querySelector('i').classList.replace('bi-chevron-right', 'bi-chevron-left');
                    }
                } else {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    const sidebarState = localStorage.getItem('sidebarCollapsed');
                    if (sidebarState === 'true') {
                        sidebar.classList.add('collapsed');
                        if (sidebarToggle) {
                            sidebarToggle.querySelector('i').classList.replace('bi-chevron-left', 'bi-chevron-right');
                        }
                    }
                }
            });
        });
    </script>
    @stack('scripts')
    <script>
    $(document).ready(function() {
        // Global AJAX setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global error handler
        $(document).ajaxError(function(event, jqXHR, settings, thrownError) {
            alert('An error occurred: ' + (jqXHR.responseJSON?.message || 'Unknown error'));
        });
    });
    </script>
    @yield('jsPage')
</body>
</html>
