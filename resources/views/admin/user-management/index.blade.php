
@extends('layouts.app')

@section('title', 'User Management')

@section('content')

<div class="container py-5">

    {{-- Header --}}
    <div class="mb-4">
        <h2 class="fw-bold text-primary">
            User Management
        </h2>

        <p class="text-muted">
            Manage system accounts and review registrations.
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

        {{-- Tabs --}}
        <div class="card-header bg-white pt-3">

            <ul class="nav nav-tabs card-header-tabs">

                @foreach(['active', 'pending', 'returned', 'rejected'] as $tab)

                    <li class="nav-item">

                        <a href="{{ route('admin.users.index', ['status' => $tab]) }}"
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
                  action="{{ route('admin.users.index') }}"
                  class="row g-2 mb-4">

                <input type="hidden"
                       name="status"
                       value="{{ $status }}">

                <div class="col-md-8">

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Search username, email, or full name...">

                </div>

                <div class="col-md-2">

                    <button type="submit"
                            class="btn btn-primary w-100">
                        Search
                    </button>

                </div>

                <div class="col-md-2">

                    <a href="{{ route('admin.users.index', ['status' => $status]) }}"
                       class="btn btn-outline-secondary w-100">
                        Reset
                    </a>

                </div>

            </form>

            {{-- Active Users Table --}}
            @if($status === 'active')

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>No.</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Account Status</th>
                                <th class="text-center">Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($users as $user)

                                <tr>

                                    <td>
                                        {{ $users->firstItem() + $loop->index }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $user->username }}
                                    </td>

                                    <td>
                                        {{ $user->email }}
                                    </td>
                                     <td>
                                        {{ $user->role->role_name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $user->account_status }}
                                    </td>

                                    <td class="text-center">

                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewAccountModal"
                                                data-view-user-id="{{ $user->user_id }}"
                                                data-view-username="{{ $user->username }}"
                                                data-view-email="{{ $user->email }}"
                                                data-view-account-status="{{ $user->account_status }}"
                                                >
                                            View Details

                                        </button>

                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editAccountModal"
                                                data-user-id="{{ $user->user_id }}"
                                                data-username="{{ $user->username }}"
                                                data-email="{{ $user->email }}"
                                                data-account-status="{{ $user->account_status }}"
                                                >
                                            Edit Account

                                        </button>
                                        

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4"
                                        class="text-center text-muted py-4">
                                        No active users found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            {{-- Pending, Returned, Rejected Tables --}}
            @else

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>No.</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Mobile Number</th>
                                <th>Account Status</th>
                                <th class="text-center">Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($users as $user)

                                <tr>

                                    <td>
                                        {{ $users->firstItem() + $loop->index }}
                                    </td>

                                    <td>

                                        {{ $user->registration?->full_name ?? 'N/A' }}

                                    </td>
                                     <td>

                                        {{ $user->registration?->email ?? 'N/A' }}

                                    </td>

                                    <td>
                                        {{ $user->registration?->mobile_no ?? 'N/A' }}
                                    </td>

                                    <td>

                                        @php
                                            $badgeColors = [
                                                'pending' => 'bg-warning text-dark',
                                                'returned' => 'bg-info text-dark',
                                                'rejected' => 'bg-danger',
                                            ];
                                        @endphp

                                        <span class="badge {{ $badgeColors[$status] ?? 'bg-secondary' }}">
                                            {{ ucfirst($status) }}
                                        </span>

                                    </td>

                                    <td class="text-center">

                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#registrationModal"
                                                data-registration-url="{{ route('admin.users.registration', $user->user_id) }}">
                                            <i class="bi bi-eye"></i> View Details
                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5"
                                        class="text-center text-muted py-4">
                                        No registrations found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            @endif

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $users->links() }}
            </div>

        </div>

    </div>

</div>





{{-- ==========================================
     VIEW ACCOUNT MODAL
========================================== --}}

<div class="modal fade" id="viewAccountModal" tabindex="-1"
     aria-labelledby="viewAccountModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title fw-bold"
                    id="viewAccountModalLabel">
                    View Account Details
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <input type="hidden" id="view_user_id">

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Username
                    </label>

                    <div id="view_username" class="form-control bg-light"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Email Address
                    </label>

                    <div id="view_email" class="form-control bg-light"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Account Status
                    </label>

                    <div id="view_account_status" class="form-control bg-light"></div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Close
                </button>

            </div>

        </div>

    </div>

</div>


{{-- ==========================================
     EDIT ACCOUNT MODAL
========================================== --}}

<div class="modal fade" id="editAccountModal" tabindex="-1"
     aria-labelledby="editAccountModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" id="editAccountForm" action="">

                @csrf
                @method('PATCH')

                <div class="modal-header">

                    <h5 class="modal-title fw-bold"
                        id="editAccountModalLabel">
                        Edit Account
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <input type="hidden"
                           name="user_id"
                           id="edit_user_id">

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Username
                        </label>

                        <input type="text"
                               name="username"
                               id="edit_username"
                               class="form-control"
                               required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Email Address
                        </label>

                        <input type="email"
                               name="email"
                               id="edit_email"
                               class="form-control"
                               required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Save Changes
                    </button>

                </div>

            </form>

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

                <div id="registrationDetails">

                    <div class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                        <p class="text-muted mt-2">
                            Loading registration information...
                        </p>
                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Close
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
    // VIEW ACCOUNT MODAL
    // ==================================

    const viewModal = document.getElementById('viewAccountModal');

    if (viewModal) {

        viewModal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            document.getElementById('view_user_id').value =
                button.getAttribute('data-view-user-id') || '';

            document.getElementById('view_username').textContent =
                button.getAttribute('data-view-username') || '';

            document.getElementById('view_email').textContent =
                button.getAttribute('data-view-email') || '';

            document.getElementById('view_account_status').textContent =
                button.getAttribute('data-view-account-status') || '';

        });

    }


    // ==================================
    // EDIT ACCOUNT MODAL
    // ==================================

    const editModal = document.getElementById('editAccountModal');

    if (editModal) {

        editModal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            const userId = button.getAttribute('data-user-id');
            const username = button.getAttribute('data-username');
            const email = button.getAttribute('data-email');

            document.getElementById('edit_user_id').value = userId;

            document.getElementById('edit_username').value = username;

            document.getElementById('edit_email').value = email;

            document.getElementById('editAccountForm').action =
                `/admin/users/${encodeURIComponent(userId)}`;

        });

    }


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

                                ${address.is_primary ? `
                                    <span class="badge bg-success">
                                        Primary Address
                                    </span>
                                ` : ''}

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
                         style="min-height:80px; white-space:pre-wrap;">${displayValue(r.remarks)}</div>

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

            applicantName.textContent = 'Loading applicant information...';

            registrationDetails.innerHTML = `
                <div class="text-center py-5">

                    <div class="spinner-border text-primary"
                         role="status">
                    </div>

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

});

</script>

@endsection
