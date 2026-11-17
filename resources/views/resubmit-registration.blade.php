
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAAC Registration</title>

    @vite(['resources/js/app.js'])
    <style>
        .registration-tabs {
            display: flex;
            flex-   wrap: wrap;
            gap: .35rem;
            border-bottom: 1px solid #dee2e6;
        }

        .registration-tabs .nav-link {
            display: block;
            color: #6c757d;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: .375rem .375rem 0 0;
            cursor: default;
            pointer-events: none;
            white-space: normal;
        }

        .registration-tabs .nav-link.active {
            color: #0d6efd;
            background: #fff;
            border-color: #0d6efd #0d6efd #fff;
            font-weight: 600;
        }

        .registration-tabs .nav-link.completed {
            color: #198754;
            background: #f0fff6;
            border-color: #b7dfc8;
        }
    </style>

</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="row justify-content-center">
            <div class="col-lg-11">

                <!-- Page Header -->
                <div class="mb-4">
                    <h3 class="fw-bold text-primary">Resubmit Registration</h3>
                    <p class="text-muted">
                        Please review and correct the information in your registration based
                        on the remarks provided by the administrator.
                    </p>
                </div>

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Please check the following errors:</strong>

                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if ($registration->status === 'returned')
                                <div class="alert alert-warning border-warning mb-4">
                                    <h5 class="fw-bold mb-2">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Application Returned for Correction
                                    </h5>

                                    <p class="mb-2">
                                        Your registration has been returned by the administrator.
                                        Please review the remarks below and make the necessary corrections.
                                    </p>

                                    <div class="bg-white border rounded p-3">
                                        <strong>Reason for Return:</strong>

                                        <p class="mb-0 mt-2">
                                            {{ $registration->remarks ?? 'No remarks provided.' }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                        
                        <form id="registrationForm"
                            action="{{ $resubmitAction }}"
                            method="POST">

                            @csrf
                            @method('PUT')

                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs registration-tabs mb-4" id="registrationTabs">
                                <li class="nav-item">
                                    <span class="nav-link active" aria-current="step">Personal Information</span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">Address & Contact</span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">Background</span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">Dependents</span>
                                </li>
                                <li class="nav-item">
                                    <span class="nav-link">Recommender</span>
                                </li>
                            </ul>

                            <div class="tab-content">

                                <!-- PERSONAL INFORMATION -->
                                <div class="tab-pane fade show active" id="personal">

                                    <h5 class="fw-bold mb-3">Personal Information</h5>
                                    <p class="text-muted small">Basic information of the applicant.</p>

                                    <div class="row g-3">

                                        <div class="col-md-4">
                                            <label class="form-label">Family Name *</label>
                                            <input type="text" name="family_name"
                                                class="form-control"
                                                value="{{ old('family_name', $registration->family_name) }}"
                                                required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">First Name *</label>
                                            <input type="text" name="first_name"
                                                class="form-control"
                                                value="{{ old('first_name', $registration->first_name) }}"
                                                required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" name="middle_name"
                                                class="form-control"
                                                value="{{ old('middle_name', $registration->middle_name) }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Suffix</label>
                                            <select name="suffix" class="form-select">
                                                <option value="">Select</option>
                                                @foreach (['Jr.', 'Sr.', 'II', 'III', 'IV'] as $suffix)
                                                    <option value="{{ $suffix }}"
                                                        @selected(old('suffix', $registration->suffix) == $suffix)>
                                                        {{ $suffix }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Gender *</label>
                                            <select name="gender" class="form-select" required>
                                                <option value="">Select Gender</option>
                                                @foreach (['Male', 'Female', 'Other'] as $gender)
                                                    <option value="{{ $gender }}"
                                                        @selected(old('gender', $registration->gender) == $gender)>
                                                        {{ $gender }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Height (cm)</label>
                                            <input type="number" name="height"
                                                class="form-control" step="0.01"
                                                value="{{ old('height', $registration->height) }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Age</label>
                                            <input type="text" name="age"
                                                class="form-control"
                                                value="{{ old('age', $registration->age) }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Birth Date *</label>
                                            <input type="date" name="birth_date"
                                                class="form-control"
                                                value="{{ old('birth_date', $registration->birth_date ? \Illuminate\Support\Carbon::parse($registration->birth_date)->format('Y-m-d') : '') }}"
                                                required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Place of Birth *</label>
                                            <input type="text" name="birth_place"
                                                class="form-control"
                                                value="{{ old('birth_place', $registration->birth_place) }}"
                                                required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Marital Status *</label>
                                            <select name="marital_status" class="form-select" required>
                                                <option value="">Select</option>
                                                @foreach (['Single', 'Married', 'Widowed', 'Separated', 'Annulled'] as $status)
                                                    <option value="{{ $status }}"
                                                        @selected(old('marital_status', $registration->marital_status) == $status)>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Citizenship *</label>
                                            <input type="text" name="citizenship"
                                                class="form-control"
                                                value="{{ old('citizenship', $registration->citizenship) }}"
                                                required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Religion</label>
                                            <input type="text" name="religion"
                                                class="form-control"
                                                value="{{ old('religion', $registration->religion) }}">
                                        </div>

                                    </div>
                                </div>

                                <!-- ADDRESS AND CONTACT -->
                                <div class="tab-pane fade" id="address">

                                    <h5 class="fw-bold mb-3">Address & Contact Information</h5>
                                    <p class="text-muted small">Provide your full residential address and contact information.</p>

                                    <h6 class="text-primary mt-3 mb-3">Residential Address</h6>

                                    <div class="row g-3">

                                        <div class="col-md-4">
                                            <label class="form-label">House No.</label>
                                            <input type="text" name="house_no"
                                                class="form-control"
                                                value="{{ old('house_no', $address->{'House No'} ?? '') }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Street</label>
                                            <input type="text" name="street"
                                                class="form-control"
                                                value="{{ old('street', $address->Street ?? '') }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Barangay *</label>
                                            <input type="text" name="barangay"
                                                class="form-control"
                                                value="{{ old('barangay', $address->barangay ?? '') }}"
                                                required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Town or Municipality *</label>
                                            <input type="text" name="municipality"
                                                class="form-control"
                                                value="{{ old('municipality', $address->municipality ?? '') }}"
                                                required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">City *</label>
                                            <input type="text" name="city"
                                                class="form-control"
                                                value="{{ old('city', $address->city ?? '') }}"
                                                required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Province *</label>
                                            <input type="text" name="province"
                                                class="form-control"
                                                value="{{ old('province', $address->province ?? '') }}"
                                                required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Region</label>
                                            <input type="text" name="region"
                                                class="form-control"
                                                value="{{ old('region', $address->region ?? '') }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">ZIP Code</label>
                                            <input type="text" name="zip_code"
                                                class="form-control"
                                                value="{{ old('zip_code', $address->zip_code ?? '') }}">
                                        </div>

                                    </div>

                                    <hr class="my-4">

                                    <h6 class="text-primary mb-3">Contact Details</h6>

                                    <div class="row g-3">

                                        <div class="col-md-4">
                                            <label class="form-label">Mobile Number *</label>
                                            <input type="tel" name="mobile_no"
                                                class="form-control"
                                                value="{{ old('mobile_no', $registration->mobile_no) }}"
                                                required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Landline</label>
                                            <input type="tel" name="landline"
                                                class="form-control"
                                                value="{{ old('landline', $registration->landline) }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Email Address *</label>
                                            <input type="email" name="email"
                                                class="form-control"
                                                value="{{ old('email', $registration->email) }}"
                                                required>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">Additional Address</label>
                                            <input type="text" name="address_line"
                                                class="form-control"
                                                value="{{ old('address_line', $address->address_line ?? '') }}">
                                        </div>

                                    </div>
                                </div>

                                <!-- BACKGROUND -->
                                <div class="tab-pane fade" id="background">

                                    <h5 class="fw-bold mb-3">Background Information</h5>
                                    <p class="text-muted small">Provide identification numbers and education information requested.</p>

                                    <div class="row g-3">

                                        <div class="col-md-4">
                                            <label class="form-label">TIN Number</label>
                                            <input type="text" name="tin_no"
                                                class="form-control"
                                                value="{{ old('tin_no', $registration->tin_no) }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">SSS / GSIS Number</label>
                                            <input type="text" name="sss_gsis_no"
                                                class="form-control"
                                                value="{{ old('sss_gsis_no', $registration->sss_gsis_no) }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Year Graduated From PMS/PNPA/Others *</label>
                                            <input type="number" name="year_graduated"
                                                class="form-control"
                                                value="{{ old('year_graduated', $registration->year_graduated) }}"
                                                required>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">Institution (PMS/PNPA/Others) *</label>
                                            <input type="text" name="graduation_school"
                                                class="form-control"
                                                value="{{ old('graduation_school', $registration->graduation_school) }}"
                                                required>
                                        </div>

                                    </div>
                                </div>

                                <!-- DEPENDENTS -->
                                <div class="tab-pane fade" id="dependents">

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="fw-bold mb-1">Dependents Information</h5>
                                            <p class="text-muted small mb-0">
                                                Add each child or dependent, relationship, and date of birth.
                                            </p>
                                        </div>

                                        <button type="button"
                                            class="btn btn-outline-primary btn-sm"
                                            onclick="addDependent()">
                                            + Add Dependent
                                        </button>
                                    </div>

                                    <div id="dependentContainer">

                                        @php
                                            $dependentItems = old('dependents', $dependents->toArray());
                                        @endphp

                                        @forelse ($dependentItems as $index => $dependent)

                                            <div class="dependent-item border rounded p-3 mb-3">

                                                <div class="d-flex justify-content-between mb-3">
                                                    <h6 class="fw-bold">Dependent {{ $index + 1 }}</h6>

                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="removeDependent(this)">
                                                        Remove
                                                    </button>
                                                </div>

                                                <div class="row g-3">

                                                    <div class="col-md-4">
                                                        <label class="form-label">Full Name</label>
                                                        <input type="text"
                                                            name="dependents[{{ $index }}][full_name]"
                                                            class="form-control"
                                                            value="{{ $dependent['full_name'] ?? '' }}">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Relationship</label>
                                                        <select name="dependents[{{ $index }}][relationship]"
                                                            class="form-select">

                                                            <option value="">Select</option>

                                                            @foreach (['Spouse', 'Son', 'Daughter', 'Father', 'Mother', 'Other'] as $relationship)
                                                                <option value="{{ $relationship }}"
                                                                    @selected(($dependent['relationship'] ?? '') == $relationship)>
                                                                    {{ $relationship }}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">Birth Date</label>
                                                        <input type="date"
                                                            name="dependents[{{ $index }}][birth_date]"
                                                            class="form-control"
                                                            value="{{ !empty($dependent['birth_date']) ? \Illuminate\Support\Carbon::parse($dependent['birth_date'])->format('Y-m-d') : '' }}">
                                                    </div>

                                                </div>
                                            </div>

                                        @empty

                                            <p class="text-muted">No dependents added.</p>

                                        @endforelse

                                    </div>
                                </div>

                                <!-- RECOMMENDER -->
                                <div class="tab-pane fade" id="recommender">

                                    <h5 class="fw-bold mb-3">Recommender Information</h5>
                                    <p class="text-muted small">
                                        Details of the regular CAAC member recommending the applicant.
                                    </p>

                                    <div class="row g-3">

                                        <div class="alert alert-info small">
                                            Regular CAAC Member Recommending the Applicant.
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Last Name *</label>
                                            <input type="text" name="recommender_last_name"
                                                class="form-control"
                                                value="{{ old('recommender_last_name', $recommender->last_name ?? '') }}"
                                                required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">First Name *</label>
                                            <input type="text" name="recommender_first_name"
                                                class="form-control"
                                                value="{{ old('recommender_first_name', $recommender->first_name ?? '') }}"
                                                required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" name="recommender_middle_name"
                                                class="form-control"
                                                value="{{ old('recommender_middle_name', $recommender->middle_name ?? '') }}">
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">Year Graduated From PMS</label>
                                            <input type="number" name="recommender_year_graduated"
                                                class="form-control"
                                                value="{{ old('recommender_year_graduated', $recommender->recommender_year_graduated ?? '') }}">
                                        </div>

                                    </div>

                                    <hr class="my-4">

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            id="declaration" name="declaration" value="1"
                                            {{ old('declaration') ? 'checked' : '' }} required>

                                        <label class="form-check-label" for="declaration">
                                            I hereby certify that the information provided in this
                                            registration form is true and correct to the best of my knowledge.
                                        </label>
                                    </div>

                                </div>

                            </div>

                            <!-- FORM NAVIGATION -->
                            <hr class="my-4">

                            <div class="d-flex justify-content-between">

                                <button type="button" id="prevBtn"
                                    class="btn btn-outline-secondary" disabled>
                                    Previous
                                </button>

                                <button type="button" id="nextBtn"
                                    class="btn btn-primary">
                                    Save & Continue
                                </button>

                                <button type="submit" id="submitBtn"
                                    class="btn btn-success d-none">
                                    Resubmit Registration
                                </button>

                            </div>

                        </form>

                    </div>
                </div>

                <p class="text-center text-muted small mt-3">
                    CAAC System | Applicant Registration
                </p>

            </div>
        </div>
    </div>

    <!-- Registration Step Navigation -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const tabIndicators = Array.from(
                document.querySelectorAll('#registrationTabs .nav-link')
            );

            const tabPanes = Array.from(
                document.querySelectorAll('.tab-content .tab-pane')
            );

            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            const registrationForm = document.getElementById('registrationForm');

            let currentStep = 0;

            function showStep(index) {

                currentStep = index;

                // Update step indicators
                tabIndicators.forEach((indicator, i) => {

                    indicator.classList.toggle('active', i === index);
                    indicator.classList.toggle('completed', i < index);

                    if (i === index) {
                        indicator.setAttribute('aria-current', 'step');
                    } else {
                        indicator.removeAttribute('aria-current');
                    }

                });

                // Display active section
                tabPanes.forEach((pane, i) => {

                    pane.classList.toggle('show', i === index);
                    pane.classList.toggle('active', i === index);

                });

                // Update navigation buttons
                prevBtn.disabled = index === 0;

                nextBtn.classList.toggle(
                    'd-none',
                    index === tabPanes.length - 1
                );

                submitBtn.classList.toggle(
                    'd-none',
                    index !== tabPanes.length - 1
                );

                document.getElementById('registrationTabs').scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });

            }

            // Validate fields in the current step only
            function validateCurrentStep() {

                const activePane = tabPanes[currentStep];

                const fields = activePane.querySelectorAll(
                    'input:not([type="hidden"]), select, textarea'
                );

                for (const field of fields) {

                    if (!field.checkValidity()) {

                        field.reportValidity();
                        field.focus();

                        return false;

                    }

                }

                return true;

            }

            // Save & Continue
            nextBtn.addEventListener('click', function () {

                if (!validateCurrentStep()) {
                    return;
                }

                if (currentStep < tabPanes.length - 1) {

                    showStep(currentStep + 1);

                }

            });

            // Previous
            prevBtn.addEventListener('click', function () {

                if (currentStep > 0) {

                    showStep(currentStep - 1);

                }

            });

            // Final submission
            registrationForm.addEventListener('submit', function (event) {

                if (!validateCurrentStep()) {

                    event.preventDefault();

                }

            });

            // Initialize first step
            showStep(0);

        });


        // DEPENDENTS FUNCTIONALITY

        let dependentCount = document.querySelectorAll(
            '#dependentContainer .dependent-item'
        ).length;

        function addDependent() {

            const container = document.getElementById('dependentContainer');

            const html = `
                <div class="dependent-item border rounded p-3 mb-3">

                    <div class="d-flex justify-content-between mb-3">

                        <h6 class="fw-bold">Dependent ${dependentCount + 1}</h6>

                        <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            onclick="removeDependent(this)">
                            Remove
                        </button>

                    </div>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Full Name</label>
                            <input type="text"
                                name="dependents[${dependentCount}][full_name]"
                                class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Relationship</label>
                            <select name="dependents[${dependentCount}][relationship]"
                                class="form-select">

                                <option value="">Select</option>
                                <option>Spouse</option>
                                <option>Son</option>
                                <option>Daughter</option>
                                <option>Father</option>
                                <option>Mother</option>
                                <option>Other</option>

                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Birth Date</label>
                            <input type="date"
                                name="dependents[${dependentCount}][birth_date]"
                                class="form-control">
                        </div>

                    </div>

                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);

            dependentCount++;

        }

        function removeDependent(button) {

            button.closest('.dependent-item').remove();

        }
    </script>

</body>
</html>