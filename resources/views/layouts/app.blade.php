
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'CAAC Dashboard')</title>

    @vite(['resources/js/app.js'])
    @vite(['resources/css/app.css'])

    @stack('styles')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body {
            overflow-x: hidden;
        }

        /* TOP NAVBAR */
        .top-navbar {
            height: 65px;
            background: #0d6efd;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1040;
            display: flex;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,.1);
        }

        .sidebar-toggle {
            border: none;
            background: transparent;
            color: white;
            font-size: 24px;
            margin-right: 15px;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 65px;
            left: 0;
            bottom: 0;
            width: 250px;
            background: #fff;
            border-right: 1px solid #dee2e6;
            transition: width .3s ease, transform .3s ease;
            z-index: 1030;
            overflow-y: auto;
        }

        .sidebar-menu {
            padding: 20px 12px;
        }

        .sidebar .menu-label {
            font-size: 14px;
            font-weight: 700;
            color: #8a94a6;
            text-transform: uppercase;
            padding: 10px 12px;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px 14px;
            color: #495057;
            border-radius: 7px;
            margin-bottom: 5px;
            white-space: nowrap;
            transition: .2s;
        }

        .sidebar .nav-link i {
            font-size: 19px;
            min-width: 22px;
            text-align: center;
        }
        
        .sidebar .nav-link span{
            font-size: 14px;
            
        }

        .sidebar .nav-link:hover {
            background: #eaf2ff;
            color: #0d6efd;
        }

        .sidebar .nav-link.active {
            background: #0d6efd;
            color: white;
            font-weight: 600;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 250px;
            margin-top: 65px;
            padding: 25px;
            min-height: calc(100vh - 65px);
            transition: margin-left .3s ease;
        }

        /* COLLAPSED SIDEBAR */
        body.sidebar-collapsed .sidebar {
            width: 75px;
        }

        body.sidebar-collapsed .sidebar .nav-link {
            justify-content: center;
            padding: 12px 10px;
        }

        body.sidebar-collapsed .sidebar .nav-link span,
        body.sidebar-collapsed .sidebar .menu-label {
            display: none;
        }

        body.sidebar-collapsed .main-content {
            margin-left: 75px;
        }

        /* MOBILE */
        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
                transform: translateX(-100%);
            }

            body.mobile-sidebar-open .sidebar {
                transform: translateX(0);
            }

            .main-content,
            body.sidebar-collapsed .main-content {
                margin-left: 0;
                padding: 18px;
            }

            body.sidebar-collapsed .sidebar {
                width: 250px;
            }

            body.sidebar-collapsed .sidebar .nav-link {
                justify-content: flex-start;
                padding: 12px 14px;
            }

            body.sidebar-collapsed .sidebar .nav-link span,
            body.sidebar-collapsed .sidebar .menu-label {
                display: initial;
            }

            body.mobile-sidebar-open .sidebar-overlay {
                display: block;
                position: fixed;
                inset: 65px 0 0;
                background: rgba(0,0,0,.4);
                z-index: 1020;
            }

            .user-welcome {
                display: none;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- TOP NAVBAR --}}
    <nav class="top-navbar">

        <button class="sidebar-toggle" id="sidebarToggle" type="button"
                aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>

        <h5 class="fw-bold mb-0">
            CAAC SYSTEM
        </h5>

        <div class="ms-auto d-flex align-items-center">

            <span class="user-welcome me-3">
                Welcome, {{ Auth::user()->username }}
            </span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>

        </div>

    </nav>

    {{-- SIDEBAR OVERLAY --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-menu">

            <div class="menu-label">
                Main Menu
            </div>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               title="Dashboard">

                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>

            </a>

            {{-- Administrator Menus --}}
            @if (Auth::check() && in_array(Auth::user()->role_id, [6, 4, 3]))

                <div class="menu-label mt-3">
                    Administration
                </div>

                {{-- User Management --}}
                <a href="{{ route('admin.users.index') }}"
                   class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}"
                   title="User Management">

                    <i class="bi bi-people"></i>
                    <span>User Management</span>

                </a>

                {{-- Registration Management --}}
                <a href="{{ route('admin.users.registration-management') }}"
                   class="nav-link {{ request()->routeIs('admin.users.registration-management') ? 'active' : '' }}"
                   title="Registration Management">

                    <i class="bi bi-person-lines-fill"></i>
                    <span>Registration Management</span>

                </a>

            @endif

        </div>

    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main-content">

        @yield('content')

        {{-- FOOTER --}}
        <footer class="text-center py-4 mt-4">
            <p class="text-muted small mb-0">
                CAAC Management System &copy; {{ date('Y') }}
            </p>
        </footer>

    </main>

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebarToggle.addEventListener('click', function () {

            if (window.innerWidth <= 768) {
                document.body.classList.toggle('mobile-sidebar-open');
            } else {
                document.body.classList.toggle('sidebar-collapsed');
            }

        });

        sidebarOverlay.addEventListener('click', function () {
            document.body.classList.remove('mobile-sidebar-open');
        });

        // Close mobile sidebar when a menu is selected
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function () {
                document.body.classList.remove('mobile-sidebar-open');
            });
        });

    </script>

</body>
</html>