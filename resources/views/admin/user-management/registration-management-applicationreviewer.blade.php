@extends('layouts.app')

@section('title', 'Applicant Registration Details')

@section('content')

@php
    $status = strtolower(trim($registration->status ?? ''));

    $statusColors = [
        'submitted' => 'bg-warning text-dark',
        'resubmitted' => 'bg-warning text-dark',
        'returned' => 'bg-info text-dark',
        'for board approval' => 'bg-primary',
        'approved' => 'bg-success',
        'rejected' => 'bg-danger',
    ];

    $statusBadge = $statusColors[$status] ?? 'bg-secondary';
@endphp

<div class="container-fluid py-4">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <div>
            <h2 class="heading">
                Applicant Registration Details
            </h2>

            <p class="text-muted mb-0">
                Review and manage applicant registration information.
            </p>
        </div>

        <a href="{{ route('admin.users.registration-management') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Registration Management

        </a>

    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please check the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- APPLICANT SUMMARY --}}
    <div class="card border-0 shadow-sm mb-4 heading-card">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h4 class="fw-bold mb-1">
                        {{ ucwords(strtolower(collect([
                            $registration->first_name,
                            $registration->middle_name,
                            $registration->family_name,
                            $registration->suffix
                        ])->filter()->implode(' '))) ?: 'Applicant Name' }}
                    </h4>

                    <p class="text-muted mb-1">
                        <i class="bi bi-person-badge me-1"></i>
                        Registration No.:
                        <strong>
                            {{ $registration->registration_no ?? 'N/A' }}
                        </strong>
                    </p>

                    <p class="text-muted mb-0">
                        <i class="bi bi-envelope me-1"></i>
                        {{ $registration->email ?? 'N/A' }}
                    </p>

                </div>

                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    <span class="badge {{ $statusBadge }} px-3 py-2 fs-6">
                        {{ ucfirst($registration->status ?? 'Unknown') }}
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- MAIN CONTENT --}}
    <div class="row g-4">

        {{-- ==========================================
             LEFT SIDE - REGISTRATION INFORMATION
        ========================================== --}}

        <div class="col-lg-8">

            {{-- 1. PERSONAL INFORMATION --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold text-primary mb-0">
                        <span class="badge bg-primary me-2">1</span>
                        Personal Information
                    </h5>

                </div>

                <div class="card-body p-4">

                    <p class="text-muted small mb-4">
                        Basic personal information of the applicant.
                    </p>

                    <div class="row">

                        @foreach([
                            'Family Name' => $registration->family_name,
                            'First Name' => $registration->first_name,
                            'Middle Name' => $registration->middle_name,
                            'Suffix' => $registration->suffix,
                            'Gender' => $registration->gender,
                            'Height (cm)' => $registration->height,
                            'Age' => $registration->age,
                            'Birth Date' => $registration->birth_date,
                            'Place of Birth' => $registration->birth_place,
                            'Marital Status' => $registration->marital_status,
                            'Citizenship' => $registration->citizenship,
                            'Religion' => $registration->religion,
                        ] as $label => $value)

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">
                                    {{ $label }}
                                </label>

                                <div class="col-md-8 fw-semibold">
                                    {{ is_string($value) ? ucwords(strtolower($value)) : ($value ?? 'N/A') }}
                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>


            {{-- 2. ADDRESS AND CONTACT --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold text-primary mb-0">
                        <span class="badge bg-primary me-2">2</span>
                        Address & Contact Information
                    </h5>

                </div>

                <div class="card-body p-4">

                    <h6 class="fw-bold mb-3">
                        Residential Address
                    </h6>

                    @forelse($registration->addresses as $index => $address)

                        <div class="border rounded p-3 mb-3">

                            <h6 class="fw-semibold text-primary mb-3">
                                Address {{ $index + 1 }}

                                @if($address->is_primary)
                                    <span class="badge bg-success ms-2">
                                        Primary Address
                                    </span>
                                @endif
                            </h6>

                            <div class="row">

                                @foreach([
                                    'Address Type' => $address->address_type,
                                    'House Number' => $address->house_no,
                                    'Street' => $address->street,
                                    'Barangay' => $address->barangay,
                                    'Municipality' => $address->municipality,
                                    'City' => $address->city,
                                    'Province' => $address->province,
                                    'Region' => $address->region,
                                    'ZIP Code' => $address->zip_code,
                                    'Additional Address' => $address->address_line,
                                ] as $label => $value)

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label text-muted small mb-1">
                                            {{ $label }}
                                        </label>

                                        <div class="form-control bg-light">
                                            {{ $value ?: 'N/A' }}
                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @empty

                        <div class="alert alert-light border text-center text-muted">
                            No address records found.
                        </div>

                    @endforelse

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3">
                        Contact Information
                    </h6>

                    <div class="row">

                        @foreach([
                            'Mobile Number' => $registration->mobile_no,
                            'Landline' => $registration->landline,
                            'Email Address' => $registration->email,
                        ] as $label => $value)

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">
                                    {{ $label }}
                                </label>

                                <div class="form-control bg-light">
                                    {{ $value ?: 'N/A' }}
                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>


            {{-- 3. BACKGROUND INFORMATION --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold text-primary mb-0">
                        <span class="badge bg-primary me-2">3</span>
                        Background Information
                    </h5>

                </div>

                <div class="card-body p-4">

                    <p class="text-muted small mb-4">
                        Identification and educational background of the applicant.
                    </p>

                    <div class="row">

                        @foreach([
                            'TIN Number' => $registration->tin_no,
                            'SSS / GSIS Number' => $registration->sss_gsis_no,
                            'Year Graduated' => $registration->year_graduated,
                            'Graduation Institution' => $registration->graduation_school,
                        ] as $label => $value)

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">
                                    {{ $label }}
                                </label>

                                <div class="form-control bg-light">
                                    {{ $value ?: 'N/A' }}
                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>


            {{-- 4. DEPENDENTS --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold text-primary mb-0">
                        <span class="badge bg-primary me-2">4</span>
                        Dependents Information
                    </h5>

                </div>

                <div class="card-body p-4">

                    <p class="text-muted small mb-3">
                        List of registered dependents of the applicant.
                    </p>

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>No.</th>
                                    <th>Full Name</th>
                                    <th>Relationship</th>
                                    <th>Birth Date</th>
                                    <th>Remarks</th>
                                </tr>

                            </thead>

                            <tbody>

                                @forelse($registration->dependents as $dependent)

                                    <tr>

                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            {{ ucfirst($dependent->full_name ?? 'N/A') }}
                                        </td>

                                        <td>
                                            {{ ucfirst($dependent->relationship ?? 'N/A') }}
                                        </td>

                                        <td>
                                            {{ $dependent->date_of_birth ?? 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $dependent->remarks ?: 'N/A' }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="5"
                                            class="text-center text-muted py-4">
                                            No dependent records found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- 5. RECOMMENDER --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold text-primary mb-0">
                        <span class="badge bg-primary me-2">5</span>
                        Recommender Information
                    </h5>

                </div>

                <div class="card-body p-4">

                    <p class="text-muted small mb-4">
                        Information about the regular CAAC member recommending the applicant.
                    </p>

                    @if($registration->recommender)

                        <div class="row">

                            @foreach([
                                'Last Name' => $registration->recommender->last_name,
                                'First Name' => $registration->recommender->first_name,
                                'Middle Name' => $registration->recommender->middle_name,
                                'Year Graduated From PMS' => $registration->recommender->year_graduated,
                            ] as $label => $value)

                                <div class="col-md-6 mb-3">

                                    <label class="form-label text-muted small mb-1">
                                        {{ $label }}
                                    </label>

                                    <div class="form-control bg-light">
                                        {{ is_string($value) ? ucwords(strtolower($value)) : ($value ?? 'N/A') }}
                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="alert alert-light border text-center text-muted">
                            No recommender information found.
                        </div>

                    @endif

                </div>

            </div>


            {{-- 6. ACCOUNT REGISTRATION --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold text-primary mb-0">
                        <span class="badge bg-primary me-2">6</span>
                        Account Registration
                    </h5>

                </div>

                <div class="card-body p-4">

                    <p class="text-muted small mb-4">
                        Applicant account and registration review information.
                    </p>

                    <div class="row">

                        @foreach([
                            'Username' => $user->username,
                            'Account Email' => $user->email,
                            'Account Status' => $user->account_status,
                            'Registration Number' => $registration->registration_no,
                            'Registration Status' => $registration->status,
                            'Submitted At' => $registration->submitted_at,
                            'Reviewed At' => $registration->reviewed_at,
                            'Reviewed By' => $registration->reviewer?->username,
                            'Registration Email' => $registration->email,
                        ] as $label => $value)

                            <div class="col-md-6 mb-3">

                                <label class="form-label text-muted small mb-1">
                                    {{ $label }}
                                </label>

                                <div class="form-control bg-light">
                                    {{ $value ?: 'N/A' }}
                                </div>

                            </div>

                        @endforeach

                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3">
                        Registration Remarks
                    </h6>

                    <div class="form-control bg-light"
                         style="min-height: 100px; white-space: pre-wrap;">{{ $registration->remarks ?: 'No remarks available.' }}</div>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3">
                        Uploaded Documents
                    </h6>

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>No.</th>
                                    <th>Document Type</th>
                                    <th>Document Name</th>
                                    <th>Status</th>
                                </tr>

                            </thead>

                            <tbody>

                                @forelse($registration->documents as $document)

                                    <tr>

                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            {{ $document->document_type ?? 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $document->document_name ?? 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $document->status ?? 'N/A' }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="4"
                                            class="text-center text-muted py-4">
                                            No uploaded documents found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- ==========================================
             RIGHT SIDE - STATUS AND ACTIONS
        ========================================== --}}

        <div class="col-lg-4">

            <div class="position-sticky" style="top: 90px;">

                {{-- APPLICATION STATUS --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-clipboard-check text-primary me-2"></i>
                            Application Status
                        </h5>

                    </div>

                    <div class="card-body p-4">

                        <label class="text-muted small mb-2">
                            Current Status
                        </label>

                        <div class="mb-4">

                            <span class="badge {{ $statusBadge }} px-3 py-2 fs-6">
                                {{ ucfirst($registration->status ?? 'Unknown') }}
                            </span>

                        </div>

                        <div class="mb-3">

                            <label class="text-muted small">
                                Registration Number
                            </label>

                            <div class="fw-semibold">
                                {{ $registration->registration_no ?? 'N/A' }}
                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="text-muted small">
                                Date Submitted
                            </label>

                            <div class="fw-semibold">
                                {{ $registration->submitted_at?->format('M d, Y h:i A') ?? 'N/A' }}
                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="text-muted small">
                                Last Reviewed
                            </label>

                            <div class="fw-semibold">
                                {{ $registration->reviewed_at?->format('M d, Y h:i A') ?? 'N/A' }}
                            </div>

                        </div>

                        <div>

                            <label class="text-muted small">
                                Reviewed By
                            </label>

                            <div class="fw-semibold">
                                {{ ucfirst($registration->reviewer?->username ?? 'N/A') }}
                            </div>

                        </div>

                    </div>

                </div>
                
                {{-- REGISTRATION ACTIONS --}}
                @if(!in_array($status, ['approved', 'rejected', 'for board approval']))

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-gear text-primary me-2"></i>
                            Registration Actions
                        </h5>
                    </div>

                    <div class="card-body p-4">

                        <p class="text-muted small mb-4">
                            Select an action to process this applicant's registration.
                        </p>

                        {{-- APPROVE BUTTON --}}
                        <button type="button"
                                class="btn btn-success w-100 mb-3"
                                data-bs-toggle="modal"
                                data-bs-target="#approveModal">
                            <i class="bi bi-check-circle me-1"></i>
                            Approve Registration
                        </button>

                        {{-- RETURN BUTTON --}}
                        <button type="button"
                                class="btn btn-warning w-100 mb-3"
                                data-bs-toggle="modal"
                                data-bs-target="#returnModal">
                            <i class="bi bi-arrow-return-left me-1"></i>
                            Return Registration
                        </button>

                        {{-- REJECT BUTTON --}}
                        <button type="button"
                                class="btn btn-danger w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle me-1"></i>
                            Reject Registration
                        </button>

                    </div>
                </div>
                @endif

                {{-- BACK BUTTON --}}
                <a href="{{ route('admin.users.registration-management') }}"
                   class="btn btn-outline-secondary w-100">

                    <i class="bi bi-arrow-left me-1"></i>
                    Back to Registration Management

                </a>

            </div>
            
            {{-- ==========================================
                APPROVE MODAL
            ========================================== --}}
            <div class="modal fade" id="approveModal" tabindex="-1"
                aria-labelledby="approveModalLabel" aria-hidden="true">

                <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content border-0 shadow">

                        <form method="POST"
                            action="{{ route('admin.registrations.approve', $registration->registration_id) }}">

                            @csrf
                            @method('PATCH')

                            <div class="modal-header bg-success text-white">

                                <h5 class="modal-title fw-bold" id="approveModalLabel">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Confirm Registration Approval
                                </h5>

                                <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>

                            </div>

                            <div class="modal-body p-4">

                                <div class="text-center mb-3">
                                    <i class="bi bi-check-circle-fill text-success"
                                    style="font-size: 3.5rem;"></i>
                                </div>

                                <h6 class="text-center fw-bold">
                                    Are you sure you want to approve this registration?
                                </h6>

                                <p class="text-muted text-center small">
                                    This action will also activate the applicant's account.
                                </p>

                                <label class="form-label fw-semibold mt-3">
                                    Approval Remarks
                                    <span class="text-muted fw-normal">(Optional)</span>
                                </label>

                                <textarea name="remarks"
                                        class="form-control"
                                        rows="3"
                                        maxlength="1000"
                                        placeholder="Enter approval remarks (optional)..."></textarea>

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">
                                    Cancel
                                </button>

                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Yes, Approve
                                </button>

                            </div>

                        </form>

                    </div>
                </div>
            </div>


            {{-- ==========================================
                RETURN MODAL
            ========================================== --}}
            <div class="modal fade" id="returnModal" tabindex="-1"
                aria-labelledby="returnModalLabel" aria-hidden="true">

                <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content border-0 shadow">

                        <form method="POST"
                            action="{{ route('admin.registrations.return', $registration->registration_id) }}">

                            @csrf
                            @method('PATCH')

                            <div class="modal-header bg-warning">

                                <h5 class="modal-title fw-bold" id="returnModalLabel">
                                    <i class="bi bi-arrow-return-left me-2"></i>
                                    Confirm Return Registration
                                </h5>

                                <button type="button" class="btn-close"
                                        data-bs-dismiss="modal"></button>

                            </div>

                            <div class="modal-body p-4">

                                <div class="text-center mb-3">
                                    <i class="bi bi-exclamation-triangle-fill text-warning"
                                    style="font-size: 3.5rem;"></i>
                                </div>

                                <h6 class="text-center fw-bold">
                                    Are you sure you want to return this registration?
                                </h6>

                                <p class="text-muted text-center small">
                                    The applicant will be required to address the corrections
                                    or missing requirements.
                                </p>

                                <label class="form-label fw-semibold mt-3">
                                    Return Remarks <span class="text-danger">*</span>
                                </label>

                                <textarea name="remarks"
                                        class="form-control"
                                        rows="3"
                                        minlength="5"
                                        maxlength="1000"
                                        placeholder="Enter the necessary corrections or missing requirements..."
                                        required></textarea>

                                <small class="text-muted">
                                    Minimum 5 characters. Maximum 1,000 characters.
                                </small>

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">
                                    Cancel
                                </button>

                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-arrow-return-left me-1"></i>
                                    Yes, Return
                                </button>

                            </div>

                        </form>

                    </div>
                </div>
            </div>


            {{-- ==========================================
                REJECT MODAL
            ========================================== --}}
            <div class="modal fade" id="rejectModal" tabindex="-1"
                aria-labelledby="rejectModalLabel" aria-hidden="true">

                <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content border-0 shadow">

                        <form method="POST"
                            action="{{ route('admin.registrations.reject', $registration->registration_id) }}">

                            @csrf
                            @method('PATCH')

                            <div class="modal-header bg-danger text-white">

                                <h5 class="modal-title fw-bold" id="rejectModalLabel">
                                    <i class="bi bi-x-circle me-2"></i>
                                    Confirm Registration Rejection
                                </h5>

                                <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>

                            </div>

                            <div class="modal-body p-4">

                                <div class="text-center mb-3">
                                    <i class="bi bi-x-circle-fill text-danger"
                                    style="font-size: 3.5rem;"></i>
                                </div>

                                <h6 class="text-center fw-bold">
                                    Are you sure you want to reject this registration?
                                </h6>

                                <p class="text-danger text-center small">
                                    Warning: This action may be final.
                                </p>

                                <label class="form-label fw-semibold mt-3">
                                    Rejection Remarks <span class="text-danger">*</span>
                                </label>

                                <textarea name="remarks"
                                        class="form-control"
                                        rows="3"
                                        minlength="5"
                                        maxlength="1000"
                                        placeholder="Enter the reason for rejecting this registration..."
                                        required></textarea>

                                <small class="text-muted">
                                    Minimum 5 characters. Maximum 1,000 characters.
                                </small>

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">
                                    Cancel
                                </button>

                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Yes, Reject
                                </button>

                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection