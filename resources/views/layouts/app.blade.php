
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

</head>

<body class="bg-light">

    {{-- Navigation Bar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">

            <h1 class="navbar-brand fw-bold"
               href="{{ route('dashboard') }}">
                CAAC SYSTEM
            </h1>
            <a class="navbar-brand"
               href="{{ route('dashboard') }}">
                Dashboard
            </a>

            {{-- Administrator Only Menu (role_id = 6) --}}
            @if (Auth::check() && Auth::user()->role_id == 6)

                <a class="nav-link text-white ms-3"
                href="{{ route('admin.users.index') }}">
                    User Management
                </a>

                <a class="nav-link text-white ms-3"
                href="{{ route('admin.users.registration-management') }}">
                    Registration Management
                </a>

            @endif

            <div class="ms-auto d-flex align-items-center">

                <span class="text-white me-3">
                    Welcome, {{ Auth::user()->username }}
                </span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="btn btn-danger btn-sm">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </nav>

    {{-- Page Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer class="container text-center py-4">
        <p class="text-muted small mb-0">
            CAAC Management System &copy; {{ date('Y') }}
        </p>
    </footer>

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
