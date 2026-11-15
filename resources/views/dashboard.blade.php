
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container py-5">

    <div class="mb-4">
        <h2 class="fw-bold text-primary">
            Dashboard
        </h2>

        <p class="text-muted">
            Welcome to the CAAC Management System.
            Manage your account and access your system information here.
        </p>
    </div>

    {{-- Dashboard Cards --}}
    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Username</h6>
                    <h4 class="fw-bold">
                        {{ Auth::user()->username }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Account Status</h6>
                    <h4>
                        <span class="badge bg-success">
                            {{ Auth::user()->account_status }}
                        </span>
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted">Role ID</h6>
                    <h4 class="fw-bold">
                        {{ Auth::user()->role_id }}
                    </h4>
                </div>
            </div>
        </div>

    </div>

    {{-- Account Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0 text-primary">
                Account Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">User ID</div>
                <div class="col-md-8">
                    {{ Auth::user()->user_id }}
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Username</div>
                <div class="col-md-8">
                    {{ Auth::user()->username }}
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Email Address</div>
                <div class="col-md-8">
                    {{ Auth::user()->email }}
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Role ID</div>
                <div class="col-md-8">
                    {{ Auth::user()->role_id }}
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Account Status</div>
                <div class="col-md-8">
                    {{ Auth::user()->account_status }}
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4 fw-semibold">Registered Date</div>
                <div class="col-md-8">
                    {{ Auth::user()->created_at?->format('F d, Y') }}
                </div>
            </div>

        </div>
    </div>

    {{-- Quick Access --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <h5 class="fw-bold text-primary mb-3">
                Quick Access
            </h5>

            <div class="row g-3">

                <div class="col-md-4">
                    <a href="#" class="btn btn-outline-primary w-100">
                        My Profile
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="#" class="btn btn-outline-primary w-100">
                        Account Settings
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="#" class="btn btn-outline-primary w-100">
                        Help and Support
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection
