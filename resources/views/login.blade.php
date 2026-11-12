<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CAAC Login</title>

    @vite(['resources/js/app.js'])
</head>

<body class="bg-light">

    <div class="container min-vh-100 d-flex justify-content-center align-items-center">

        <div class="card border-0 shadow-sm" style="width: 100%; max-width: 450px;">

            <div class="card-body p-5">

                <div class="text-center mb-4">

                    <h3 class="fw-bold text-primary">
                        CAAC System
                    </h3>

                    <p class="text-muted">
                        Welcome! Please log in to your account.
                    </p>

                </div>

                <hr>

                {{-- Display validation errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Login Form --}}
                <form action="{{ route('login.submit') }}" method="POST">

                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">

                        <label for="email" class="form-label fw-semibold">
                            Email Address
                        </label>

                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email"
                            required
                            autofocus
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Password --}}
                    <div class="mb-4">

                        <label for="password" class="form-label fw-semibold">
                            Password
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                        >

                    </div>

                    {{-- Remember Me --}}
                    <div class="mb-3 form-check">

                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="remember"
                            name="remember"
                        >

                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>

                    </div>

                    {{-- Login Button --}}
                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        Login
                    </button>

                    <br><br>

                    {{-- Registration Link --}}

                    <a href="{{ route('registration') }}" class="btn btn-primary w-100 fw-bold">
                        Register
                    </a>

                </form>

                <hr>

                <p class="text-center text-muted mb-0">
                    CAAC System &copy; {{ date('Y') }}
                </p>

            </div>

        </div>

    </div>

</body>
</html>
