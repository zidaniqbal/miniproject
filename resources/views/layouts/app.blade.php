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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        /* Modal Styles - Global */
        .modal-content {
            border: none;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            padding: 1.5rem 1.5rem 1rem;
            border: none;
        }

        .modal-body {
            padding: 1rem 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem 1.5rem;
            border: none;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
        }

        .btn-close {
            background-size: 0.8em;
            opacity: 0.5;
        }

        /* Modal Button Styles */
        .modal .btn {
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.625rem 1rem;
            border-radius: 8px;
            transition: all 0.15s ease-in-out;
        }

        .modal .btn-light {
            background-color: #F3F4F6;
            border-color: #F3F4F6;
            color: #374151;
        }

        .modal .btn-light:hover {
            background-color: #E5E7EB;
            border-color: #E5E7EB;
        }

        .modal .btn-danger {
            background-color: #DC2626;
            border-color: #DC2626;
        }

        .modal .btn-danger:hover {
            background-color: #B91C1C;
            border-color: #B91C1C;
        }

        /* Modal Body Text */
        .modal-body p {
            color: #4B5563;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* Responsive Modal */
        @media (max-width: 768px) {
            .modal-dialog {
                margin: 1rem;
            }
        }

        /* Active Sidebar Link */
        .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .sidebar-menu a.active i {
            color: #fff;
        }

        /* Sidebar Divider */
        .sidebar-divider {
            padding: 1rem 1rem 0.5rem;
            margin-top: 0.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-divider span {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 0.05em;
        }

        /* Adjust spacing for menu items after divider */
        .sidebar-divider + li {
            margin-top: 0.5rem;
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
                            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-title="Dashboard">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.goals') }}" class="{{ request()->routeIs('admin.goals*') ? 'active' : '' }}" data-title="Goals">
                                <i class="bi bi-bullseye"></i>
                                <span>Goals</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.news') }}" class="{{ request()->routeIs('admin.news*') ? 'active' : '' }}" data-title="News">
                                <i class="bi bi-newspaper"></i>
                                <span>Berita</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.gallery') }}" class="{{ request()->routeIs('admin.gallery') ? 'active' : '' }}" data-title="Gallery">
                                <i class="bi bi-images"></i>
                                <span>Gallery</span>
                            </a>
                        </li>

                        <!-- Admin Tools Divider -->
                        <li class="sidebar-divider">
                            <span>Admin Tools</span>
                        </li>

                        <li>
                            <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}" data-title="User Management">
                                <i class="bi bi-people"></i>
                                <span>User Management</span>
                            </a>
                        </li>
                    @else
                        <!-- User Menu -->
                        <li>
                            <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}" data-title="Dashboard">
                                <i class="bi bi-grid"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.goals') }}" class="{{ request()->routeIs('user.goals*') ? 'active' : '' }}" data-title="Goals">
                                <i class="bi bi-bullseye"></i>
                                <span>Goals</span>
                            </a>
                        <li>
                            <a href="{{ route('user.news') }}" class="{{ request()->routeIs('user.news*') ? 'active' : '' }}" data-title="News">
                                <i class="bi bi-newspaper"></i>
                                <span>Berita</span>
                            </a>
                        </li>
                    </li>
                    <li>
                        <a href="{{ route('user.gallery') }}" class="{{ request()->routeIs('user.gallery') ? 'active' : '' }}" data-title="Gallery">
                            <i class="bi bi-images"></i>
                            <span>Gallery</span>
                        </a>
                    </li>   
                    @endif

                    <!-- Common Menu Items -->
                    <li class="sidebar-divider">
                        <span>Account</span>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}" data-title="Settings">
                            <i class="bi bi-gear"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" 
                           data-bs-toggle="modal" 
                           data-bs-target="#logoutModal"
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

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Apakah Anda yakin ingin keluar? Anda harus login kembali untuk mengakses sistem.</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger px-4" onclick="document.getElementById('logout-form').submit();">Logout</button>
                </div>
            </div>
        </div>
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
