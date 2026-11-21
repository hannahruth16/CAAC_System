
@extends('layouts.app')

@section('title', 'Registration Management')

@section('content')

<div class="container py-5">

    {{-- Header --}}
    <div class="mb-4">
        <h2 class="heading">
            Registration Management
        </h2>

        <p class="text-muted">
            Manage and review applicant registration records.
        </p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Main Card --}}
    <div class="card border-0 shadow-sm">

        {{-- Status Tabs --}}
        <div class="card-header bg-white pt-3">
            <ul class="nav nav-tabs card-header-tabs">
                @foreach($statuses as $tab)
                    <li class="nav-item">
                        <a href="{{ route('admin.users.registration-management', ['status' => $tab]) }}"
                        class="nav-link {{ $status === $tab ? 'active fw-bold' : '' }}">
                            {{ ucfirst($tab) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Search --}}
        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.users.registration-management') }}"
                  class="row g-2 mb-4">

                <input type="hidden"
                       name="status"
                       value="{{ $status }}">

                <div class="col-md-8">

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Search registration number, full name, mobile number, or email...">

                </div>

                <div class="col-md-2">

                    <button type="submit"
                            class="btn btn-primary w-100">
                        Search
                    </button>

                </div>

                <div class="col-md-2">

                    <a href="{{ route('admin.users.registration-management', ['status' => $status]) }}"
                       class="btn btn-outline-secondary w-100">
                        Reset
                    </a>

                </div>

            </form>

            {{-- Registration Table --}}
            <div class="table-responsive">

                <table class="table table-hover align-middle registration-table">

                    <thead class="table-light">

                        <tr>
                            <th>No.</th>
                            <th>Registration No.</th>
                            <th>Full Name</th>
                            <th>Mobile Number</th>
                            <th>Email</th>
                            <th>Date Submitted</th>
                            <th>Registration Status</th>
                            <th class="text-center">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($registrations as $registration)

                            <tr>

                                <td>
                                    {{ $registrations->firstItem() + $loop->index }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $registration->registration_no ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ ucfirst($registration->full_name) }}
                                </td>

                                <td>
                                    {{ $registration->mobile_no ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $registration->email ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $registration->submitted_at?->format('M d, Y') ?? 'N/A' }}
                                </td>

                                <td>

                                    @php
                                        $badgeColors = [
                                            'for board approval' => 'bg-success',
                                            'approved' => 'bg-success',
                                            'submitted' => 'bg-warning text-dark',
                                            'resubmitted' => 'bg-warning text-dark',
                                            'returned' => 'bg-info text-dark',
                                            'rejected' => 'bg-danger',
                                        ];
                                    @endphp

                                    <span class="badge {{ $badgeColors[strtolower($registration->status)] ?? 'bg-secondary' }}">
                                        {{ ucfirst($registration->status) }}
                                    </span>

                                </td>

                                <td class="text-center">
                                    <a href="{{ route('admin.users.registration', $registration->user_id) }}"
                                        class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-eye"></i> View Details

                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="10"
                                    class="text-center text-muted py-4">
                                    No registration records found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $registrations->links() }}
            </div>

        </div>

    </div>

</div>

@endsection