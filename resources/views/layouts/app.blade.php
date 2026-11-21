
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
            <!-- @if (Auth::check() && in_array(Auth::user()->role_id, [6, 4, 3])) -->

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

            <!-- @endif -->

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