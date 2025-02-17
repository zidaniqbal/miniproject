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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Bootstrap CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .sidebar .avatar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar .avatar i {
            font-size: 1.5rem;
        }
    </style>
</head>
<body class="{{ Auth::check() && Auth::user()->role == 2 ? 'admin-theme' : 'user-theme' }}">
    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

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
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <i class="bi bi-person-circle text-white"></i>
                            @endif
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
                            <a href="{{ route('admin.users') }}" data-title="User Management">
                                <i class="bi bi-people"></i>
                                <span>User Management</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings') }}" data-title="Settings">
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
                            <a href="{{ route('user.settings') }}" data-title="Settings">
                                <i class="bi bi-gear"></i>
                                <span>Settings</span>
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
    @yield('cssPage')
    @yield('jsPage')
</body>
</html>
