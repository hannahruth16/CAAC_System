
@extends('layouts.app')

@section('title', 'Registration Management')

@section('content')

<div class="container py-5">

    {{-- Header --}}
    <div class="mb-4">
        <h2 class="fw-bold text-primary">
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

                <table class="table table-hover align-middle">

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
                                    {{ $registration->full_name }}
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

                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#registrationModal"
                                            data-registration-url="{{ route('admin.users.registration', $registration->user_id) }}">

                                        <i class="bi bi-eye"></i> View Details

                                    </button>

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


{{-- ==========================================
     REGISTRATION DETAILS MODAL
========================================== --}}

<!-- {{-- REGISTRATION DETAILS MODAL --}} -->

<div class="modal fade" id="registrationModal" tabindex="-1"
     aria-labelledby="registrationModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <div>
                    <h5 class="modal-title fw-bold text-primary"
                        id="registrationModalLabel">
                        Applicant Registration Information
                    </h5>

                    <small class="text-muted" id="modalApplicantName">
                        Applicant Details
                    </small>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            
            <div class="modal-body">

                <!-- REGISTRATION DETAILS VIEW -->
                <div id="registrationDetails">

                    <div class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                        <p class="text-muted mt-2">
                            Loading registration information...
                        </p>
                    </div>

                </div>

                <!-- RETURN REGISTRATION VIEW -->
                <div id="returnRegistrationPanel" style="display: none;">

                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Please provide the reason why this registration
                        is being returned.
                    </div>

                    <form method="POST"
                        id="returnRegistrationForm"
                        action="">

                        @csrf
                        @method('PATCH')

                        <div class="mb-3">

                            <label for="return_remarks" class="form-label fw-semibold">
                                Remarks <span class="text-danger">*</span>
                            </label>

                            <textarea name="remarks"
                                    id="return_remarks"
                                    class="form-control"
                                    rows="5"
                                    minlength="5"
                                    maxlength="1000"
                                    placeholder="Enter the necessary corrections or missing requirements..."
                                    required></textarea>

                            <small class="text-muted">
                                Minimum of 5 characters. Maximum of 1,000 characters.
                            </small>

                        </div>

                        <div class="modal-footer">

                            <button type="button"
                                    class="btn btn-secondary"
                                    id="cancelReturnBtn">
                                Cancel
                            </button>

                            <button type="submit"
                                    class="btn btn-warning">
                                <i class="bi bi-arrow-return-left me-1"></i>
                                Confirm Return
                            </button>

                        </div>

                    </form>

                </div>

                <!-- APPROVAL CONFIRMATION VIEW -->
                <div id="approveConfirmationPanel" style="display: none;">

                    <div class="text-center py-4">

                        <i class="bi bi-person-check text-success"
                        style="font-size: 4rem;"></i>

                        <h5 class="fw-bold mt-3">
                            Approve this registration?
                        </h5>

                        <p class="text-muted">
                            Are you sure you want to approve this registration?
                            This action will also activate the user's account.
                        </p>

                    </div>

                    <!-- OPTIONAL APPROVAL REMARKS -->
                    <div class="mb-4">

                        <label for="approvalRemarks" class="form-label fw-semibold">
                            Remarks <span class="text-muted fw-normal">(Optional)</span>
                        </label>

                        <textarea
                            class="form-control"
                            id="approvalRemarks"
                            name="remarks"
                            rows="3"
                            maxlength="1000"
                            placeholder="Enter remarks (optional)..."></textarea>

                        <small class="text-muted">
                            Maximum of 1000 characters.
                        </small>

                    </div>

                    <div class="d-flex justify-content-end gap-2">

                        <button type="button"
                                class="btn btn-secondary"
                                id="cancelApproveBtn">
                            Cancel
                        </button>

                        <button type="button"
                                class="btn btn-success"
                                id="confirmApproveBtn">
                            <i class="bi bi-check-lg me-1"></i>
                            Yes, Approve
                        </button>

                    </div>

                </div>

                <!-- REJECT REGISTRATION VIEW -->
                <div id="rejectRegistrationPanel" style="display: none;">

                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-octagon me-2"></i>
                        Please provide the reason why this registration
                        is being rejected.
                    </div>

                    <form method="POST"
                        id="rejectRegistrationForm"
                        action="">

                        @csrf
                        @method('PATCH')

                        <div class="mb-3">

                            <label for="reject_remarks" class="form-label fw-semibold">
                                Rejection Remarks <span class="text-danger">*</span>
                            </label>

                            <textarea name="remarks"
                                    id="reject_remarks"
                                    class="form-control"
                                    rows="5"
                                    minlength="5"
                                    maxlength="1000"
                                    placeholder="Enter the reason for rejecting this registration..."
                                    required></textarea>

                            <small class="text-muted">
                                Minimum of 5 characters. Maximum of 1,000 characters.
                            </small>

                        </div>

                        <div class="modal-footer">

                            <button type="button"
                                    class="btn btn-secondary"
                                    id="cancelRejectBtn">
                                Cancel
                            </button>

                            <button type="submit"
                                    class="btn btn-danger">
                                <i class="bi bi-x-circle me-1"></i>
                                Confirm Rejection
                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <div class="modal-footer" id="registrationModalFooter">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                        id="closeRegistrationBtn">
                    Close
                </button>

                <button type="button"
                        class="btn btn-warning"
                        id="returnRegistrationBtn">
                    <i class="bi bi-arrow-return-left me-1"></i>
                    Return Registration
                </button>

                <button type="button"
                        class="btn btn-success"
                        id="approveRegistrationBtn">
                    <i class="bi bi-check-circle me-1"></i>
                    Approve Registration
                </button>

                <button type="button"
        class="btn btn-danger"
        id="rejectRegistrationBtn">
    <i class="bi bi-x-circle me-1"></i>
    Reject
</button>

            </div>

        </div>

    </div>

</div>

{{-- ==========================================
     MODAL JAVASCRIPT
========================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    // ==================================
    // REGISTRATION DETAILS MODAL
    // ==================================


    const registrationModal = document.getElementById('registrationModal');
    const registrationDetails = document.getElementById('registrationDetails');
    const applicantName = document.getElementById('modalApplicantName');

    // Safely escape database values before displaying them.
    function escapeHTML(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Display N/A when a field is empty.
    function displayValue(value) {
        if (value === null || value === undefined || value === '') {
            return 'N/A';
        }

        return escapeHTML(value);
    }

    // Read-only information field.
    function field(label, value) {
        return `
            <div class="col-md-4 mb-3">
                <label class="form-label text-muted small mb-1">
                    ${escapeHTML(label)}
                </label>

                <div class="form-control bg-light">
                    ${displayValue(value)}
                </div>
            </div>
        `;
    }

    // Section heading.
    function section(title, description = '') {
        return `
            <div class="mb-3 mt-2">
                <h6 class="fw-bold text-primary mb-1">
                    ${escapeHTML(title)}
                </h6>

                ${description ? `
                    <small class="text-muted">
                        ${escapeHTML(description)}
                    </small>
                ` : ''}
            </div>
        `;
    }

    // Responsive table.
    function table(headers, rows, emptyMessage) {

        if (!rows.length) {
            return `
                <div class="alert alert-light border text-center text-muted">
                    ${escapeHTML(emptyMessage)}
                </div>
            `;
        }

        return `
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            ${headers.map(header => `
                                <th>${escapeHTML(header)}</th>
                            `).join('')}
                        </tr>
                    </thead>

                    <tbody>
                        ${rows.map(row => `
                            <tr>
                                ${row.map(value => `
                                    <td>${displayValue(value)}</td>
                                `).join('')}
                            </tr>
                        `).join('')}
                    </tbody>

                </table>
            </div>
        `;
    }

    // Render all registration tabs.
    function renderRegistration(data) {

        const user = data.user;
        const r = data.registration;

        applicantName.textContent = r.full_name || 'Applicant Details';

        const addresses = r.addresses || [];
        const dependents = r.dependents || [];
        const recommender = r.recommender;
        const documents = r.documents || [];

        registrationDetails.innerHTML = `

            {{-- NAVIGATION TABS --}}

            <ul class="nav nav-tabs registration-view-tabs mb-4"
                id="registrationViewTabs">

                <li class="nav-item">
                    <button class="nav-link active"
                            data-bs-toggle="tab"
                            data-bs-target="#viewPersonal"
                            type="button">
                        Personal Information
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#viewAddress"
                            type="button">
                        Address & Contact
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#viewBackground"
                            type="button">
                        Background
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#viewDependents"
                            type="button">
                        Dependents
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#viewRecommender"
                            type="button">
                        Recommender
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#viewAccount"
                            type="button">
                        Account Registration
                    </button>
                </li>

            </ul>

            {{-- TAB CONTENT --}}

            <div class="tab-content">

                {{-- PERSONAL INFORMATION --}}

                <div class="tab-pane fade show active"
                     id="viewPersonal">

                    ${section('Personal Information', 'Basic information of the applicant.')}

                    <div class="row">

                        ${field('Registration Number', r.registration_no)}
                        ${field('Family Name', r.family_name)}
                        ${field('First Name', r.first_name)}
                        ${field('Middle Name', r.middle_name)}
                        ${field('Suffix', r.suffix)}
                        ${field('Gender', r.gender)}
                        ${field('Height (cm)', r.height)}
                        ${field('Age', r.age)}
                        ${field('Birth Date', r.birth_date)}
                        ${field('Place of Birth', r.birth_place)}
                        ${field('Marital Status', r.marital_status)}
                        ${field('Citizenship', r.citizenship)}
                        ${field('Religion', r.religion)}

                    </div>

                </div>


                {{-- ADDRESS AND CONTACT --}}

                <div class="tab-pane fade"
                     id="viewAddress">

                    ${section('Residential Address', 'Registered residential address information.')}

                    ${addresses.length ? addresses.map((address, index) => `

                        <div class="border rounded p-3 mb-3">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <h6 class="fw-bold mb-0">
                                    Address ${index + 1}
                                </h6>

                            </div>

                            <div class="row">

                                ${field('Address Type', address.address_type)}
                                ${field('House Number', address.house_no)}
                                ${field('Street', address.street)}
                                ${field('Barangay', address.barangay)}
                                ${field('Municipality', address.municipality)}
                                ${field('City', address.city)}
                                ${field('Province', address.province)}
                                ${field('Region', address.region)}
                                ${field('ZIP Code', address.zip_code)}
                                ${field('Additional Address', address.address_line)}

                            </div>

                        </div>

                    `).join('') : `
                        <div class="alert alert-light border text-center text-muted">
                            No address records found.
                        </div>
                    `}

                    <hr class="my-4">

                    ${section('Contact Information')}

                    <div class="row">

                        ${field('Mobile Number', r.mobile_no)}
                        ${field('Landline', r.landline)}
                        ${field('Email Address', r.email)}

                    </div>

                </div>


                {{-- BACKGROUND --}}

                <div class="tab-pane fade"
                     id="viewBackground">

                    ${section('Background Information', 'Identification and educational information.')}

                    <div class="row">

                        ${field('TIN Number', r.tin_no)}
                        ${field('SSS / GSIS Number', r.sss_gsis_no)}
                        ${field('Year Graduated', r.year_graduated)}
                        ${field('Graduation Institution', r.graduation_school)}

                    </div>

                </div>


                {{-- DEPENDENTS --}}

                <div class="tab-pane fade"
                     id="viewDependents">

                    ${section('Dependents Information', 'List of registered dependents.')}

                    ${table(
                        ['No.', 'Full Name', 'Relationship', 'Birth Date', 'Remarks'],
                        dependents.map((dependent, index) => [
                            index + 1,
                            dependent.full_name,
                            dependent.relationship,
                            dependent.date_of_birth,
                            dependent.remarks
                        ]),
                        'No dependent records found.'
                    )}

                </div>


                {{-- RECOMMENDER --}}

                <div class="tab-pane fade"
                     id="viewRecommender">

                    ${section('Recommender Information', 'Regular CAAC member recommending the applicant.')}

                    ${recommender ? `

                        <div class="row">

                            ${field('Last Name', recommender.last_name)}
                            ${field('First Name', recommender.first_name)}
                            ${field('Middle Name', recommender.middle_name)}
                            ${field('Year Graduated From PMS', recommender.year_graduated)}

                        </div>

                    ` : `
                        <div class="alert alert-light border text-center text-muted">
                            No recommender information found.
                        </div>
                    `}

                </div>


                {{-- ACCOUNT REGISTRATION --}}

                <div class="tab-pane fade"
                     id="viewAccount">

                    ${section('Account Information', 'Applicant account and registration details.')}

                    <div class="row">

                        ${field('Username', user.username)}
                        ${field('Account Email', user.email)}
                        ${field('Account Status', user.account_status)}
                        ${field('Registration Number', r.registration_no)}
                        ${field('Registration Status', r.status)}
                        ${field('Submitted At', r.submitted_at)}
                        ${field('Reviewed At', r.reviewed_at)}
                        ${field('Reviewed By', r.reviewer)}
                        ${field('Registration Email', r.email)}

                    </div>

                    ${section('Registration Remarks')}

                    <div class="form-control bg-light"
                         style="min-height:80px; white-space:pre-wrap;">${displayValue(r.remarks)}
                    </div>

                    <hr class="my-4">

                    ${section('Uploaded Documents')}

                    ${table(
                        ['No.', 'Document Type', 'Document Name', 'Status'],
                        documents.map((document, index) => [
                            index + 1,
                            document.document_type,
                            document.document_name,
                            document.status
                        ]),
                        'No uploaded documents found.'
                    )}

                </div>

            </div>

        `;
    }

    // Load selected applicant's registration details.
    if (registrationModal) {

        registrationModal.addEventListener('show.bs.modal', async function (event) {

            const button = event.relatedTarget;
            const url = button.getAttribute('data-registration-url');

            // Reset registration ID
            registrationModal.dataset.registrationId = '';

            applicantName.textContent = 'Loading applicant information...';

            registrationDetails.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted mt-3">
                        Loading registration information...
                    </p>
                </div>
            `;

            try {

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(
                        data.message || 'Unable to retrieve registration information.'
                    );
                }

                // REGISTRATION ID
                registrationModal.dataset.registrationId =
                    data.registration?.registration_id;

                console.log(
                    'Registration ID:',
                    registrationModal.dataset.registrationId
                );

                // BUTTON VISIBILITY
                const status = (
                    data.registration?.status || ''
                ).trim().toLowerCase();

                registrationModal.dataset.registrationStatus = status;

                const roleId = {{ Auth::user()->role_id }};

                if (roleId == 4) {

                    // BOARD APPROVER
                    const canBoardApprove = status === 'for board approval';

                    approveRegistrationBtn.style.display =
                        canBoardApprove ? 'inline-block' : 'none';

                    rejectRegistrationBtn.style.display =
                        canBoardApprove ? 'inline-block' : 'none';

                    // Board Approver cannot return registrations
                    returnRegistrationBtn.style.display = 'none';

                } else {

                    // INITIAL APPROVER
                    const canReview = ['submitted', 'resubmitted'].includes(status);

                    approveRegistrationBtn.style.display =
                        canReview ? 'inline-block' : 'none';

                    rejectRegistrationBtn.style.display =
                        canReview ? 'inline-block' : 'none';

                    returnRegistrationBtn.style.display =
                        canReview ? 'inline-block' : 'none';
                }

                // RENDER DETAILS
                renderRegistration(data);

            } catch (error) {

                applicantName.textContent = 'Unable to load applicant';

                registrationDetails.innerHTML = `
                    <div class="alert alert-danger">
                        <strong>Error!</strong>
                        ${escapeHTML(error.message)}
                    </div>
                `;

            }

        });

    }
    
    // APPROVAL, RETURN, AND REJECTION BUTTONS
    const approveConfirmationPanel =
        document.getElementById('approveConfirmationPanel');
    const returnRegistrationPanel =
        document.getElementById('returnRegistrationPanel');
    const registrationModalFooter =
        document.getElementById('registrationModalFooter');

    //Approval 
    const cancelApproveBtn =
        document.getElementById('cancelApproveBtn');
    const cancelReturnBtn =
        document.getElementById('cancelReturnBtn');
    const confirmApproveBtn =
        document.getElementById('confirmApproveBtn');
    const approveRegistrationBtn =
        document.getElementById('approveRegistrationBtn');

    //Return
    const returnRegistrationBtn =
        document.getElementById('returnRegistrationBtn');
    const closeRegistrationBtn =
        document.getElementById('closeRegistrationBtn');
    const returnRegistrationForm =
        document.getElementById('returnRegistrationForm');
    const returnRemarks =
        document.getElementById('return_remarks');

    //Rejection
    const rejectRegistrationPanel =
    document.getElementById('rejectRegistrationPanel');
    const rejectRegistrationBtn =
        document.getElementById('rejectRegistrationBtn');
    const rejectRegistrationForm =
        document.getElementById('rejectRegistrationForm');
    const rejectRemarks =
        document.getElementById('reject_remarks');
    const cancelRejectBtn =
        document.getElementById('cancelRejectBtn');

    // SHOW DETAILS VIEW
    function showRegistrationDetails() {

        registrationDetails.style.display = 'block';

        returnRegistrationPanel.style.display = 'none';

        approveConfirmationPanel.style.display = 'none';

        registrationModalFooter.style.display = 'flex';

        returnRegistrationBtn.style.display = 'inline-block';

        approveRegistrationBtn.style.display = 'inline-block';

    }

    // APPROVAL CONFIRMATION VIEW
    if (approveRegistrationBtn) {

        approveRegistrationBtn.addEventListener('click', function () {

            const registrationId =
                registrationModal.dataset.registrationId;

            if (!registrationId) {
                console.error('Registration ID not found.');
                return;
            }

            registrationDetails.style.display = 'none';

            returnRegistrationPanel.style.display = 'none';

            approveConfirmationPanel.style.display = 'block';

            registrationModalFooter.style.display = 'none';

        });

    }

    // CANCEL APPROVAL
    if (cancelApproveBtn) {

        cancelApproveBtn.addEventListener('click', function () {

            showRegistrationDetails();

        });

    }

    
    // CONFIRM APPROVAL
    if (confirmApproveBtn) {

        confirmApproveBtn.addEventListener('click', function () {

            const registrationId =
                registrationModal.dataset.registrationId;

            if (!registrationId) {
                console.error('Registration ID not found.');
                return;
            }

            // Get optional remarks
            const remarks =
                document.getElementById('approvalRemarks').value.trim();

            const form = document.createElement('form');

            form.method = 'POST';

            form.action =
                "{{ route('admin.registrations.approve', ':id') }}"
                    .replace(':id', registrationId);

            // CSRF TOKEN
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = "{{ csrf_token() }}";

            // PATCH METHOD
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'PATCH';

            // OPTIONAL REMARKS
            const remarksInput = document.createElement('input');
            remarksInput.type = 'hidden';
            remarksInput.name = 'remarks';
            remarksInput.value = remarks;

            form.appendChild(csrf);
            form.appendChild(method);
            form.appendChild(remarksInput);

            document.body.appendChild(form);

            form.submit();

        });

    }


    // RETURN REGISTRATION

    if (
        returnRegistrationBtn &&
        returnRegistrationForm &&
        returnRegistrationPanel &&
        returnRemarks
    ) {

        returnRegistrationBtn.addEventListener('click', function () {

            const registrationId =
                registrationModal.dataset.registrationId;

            if (!registrationId) {
                console.error('Registration ID not found.');
                return;
            }

            // Set Laravel form action
            returnRegistrationForm.action =
                "{{ route('admin.registrations.return', ':id') }}"
                    .replace(':id', registrationId);

            // Clear previous remarks
            returnRemarks.value = '';

            // Hide other views
            registrationDetails.style.display = 'none';
            approveConfirmationPanel.style.display = 'none';

            // Display Return Registration panel
            returnRegistrationPanel.style.display = 'block';

            // Hide main footer
            registrationModalFooter.style.display = 'none';

            // Focus textarea
            requestAnimationFrame(function () {
                returnRemarks.focus();
            });

        });

    }

    // CANCEL RETURN REGISTRATION
    if (cancelReturnBtn) {

        cancelReturnBtn.addEventListener('click', function () {

            returnRegistrationForm.reset();

            showRegistrationDetails();

        });

    }

    // REJECT REGISTRATION
    if (
        rejectRegistrationBtn &&
        rejectRegistrationForm &&
        rejectRegistrationPanel &&
        rejectRemarks
    ) {

        rejectRegistrationBtn.addEventListener('click', function () {

            const registrationId =
                registrationModal.dataset.registrationId;

            if (!registrationId) {
                console.error('Registration ID not found.');
                return;
            }

            // Set Laravel form action
            rejectRegistrationForm.action =
                "{{ route('admin.registrations.reject', ':id') }}"
                    .replace(':id', registrationId);

            // Clear previous remarks
            rejectRemarks.value = '';

            // Hide other views
            registrationDetails.style.display = 'none';
            returnRegistrationPanel.style.display = 'none';
            approveConfirmationPanel.style.display = 'none';

            // Display Reject Registration panel
            rejectRegistrationPanel.style.display = 'block';

            // Hide main footer
            registrationModalFooter.style.display = 'none';

            // Focus textarea
            requestAnimationFrame(function () {
                rejectRemarks.focus();
            });

        });

    }


    // CANCEL REJECT REGISTRATION

    if (cancelRejectBtn) {

        cancelRejectBtn.addEventListener('click', function () {

            rejectRegistrationForm.reset();

            showRegistrationDetails();

        });

    }

    // RESET WHEN MODAL CLOSES
    registrationModal.addEventListener('hidden.bs.modal', function () {

        showRegistrationDetails();

        returnRemarks.value = '';

        returnRegistrationForm.action = '';

    });

});

</script>

@endsection