
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
                    <h3 class="fw-bold text-primary">Applicant Registration</h3>
                    <p class="text-muted">
                        Enter your legal and personal details as shown on your identification documents.
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

                        <form form id="registrationForm" action="{{ route('registration.store') }}" method="POST">
                            @csrf

                            <!-- Tab Navigation -->
                            <!-- Non-clickable registration step indicators -->
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
                                <li class="nav-item">
                                    <span class="nav-link">Account Registration</span>
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
                                                class="form-control" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">First Name *</label>
                                            <input type="text" name="first_name"
                                                class="form-control" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" name="middle_name"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Suffix</label>
                                            <select name="suffix" class="form-select">
                                                <option value="">Select</option>
                                                <option>Jr.</option>
                                                <option>Sr.</option>
                                                <option>II</option>
                                                <option>III</option>
                                                <option>IV</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Gender *</label>
                                            <select name="gender" class="form-select" required>
                                                <option value="">Select Gender</option>
                                                <option>Male</option>
                                                <option>Female</option>
                                                <option>Other</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Height (cm)</label>
                                            <input type="number" name="height"
                                                class="form-control" step="0.01">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Age</label>
                                            <input type="text" name="age"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Birth Date *</label>
                                            <input type="date" name="birth_date"
                                                class="form-control" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Place of Birth *</label>
                                            <input type="text" name="birth_place"
                                                class="form-control" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Marital Status *</label>
                                            <select name="marital_status" class="form-select" required>
                                                <option value="">Select</option>
                                                <option>Single</option>
                                                <option>Married</option>
                                                <option>Widowed</option>
                                                <option>Separated</option>
                                                <option>Annulled</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Citizenship *</label>
                                            <input type="text" name="citizenship"
                                                class="form-control" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Religion</label>
                                            <input type="text" name="religion"
                                                class="form-control">
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
                                            <input type="text" name="House No"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Street</label>
                                            <input type="text" name="Street"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Barangay *</label>
                                            <input type="text" name="barangay"
                                                class="form-control" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Town or Municipality *</label>
                                            <input type="text" name="municipality"
                                                class="form-control" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">City *</label>
                                            <input type="text" name="city"
                                                class="form-control" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Province *</label>
                                            <input type="text" name="province"
                                                class="form-control" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Region</label>
                                            <input type="text" name="region"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">ZIP Code</label>
                                            <input type="text" name="zip_code"
                                                class="form-control">
                                        </div>

                                    </div>

                                    <hr class="my-4">

                                    <h6 class="text-primary mb-3">Contact Details</h6>

                                    <div class="row g-3">

                                        <div class="col-md-4">
                                            <label class="form-label">Mobile Number *</label>
                                            <input type="tel" name="mobile_no"
                                                class="form-control" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Landline</label>
                                            <input type="tel" name="landline"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Email Address *</label>
                                            <input type="email" name="email"
                                                class="form-control" required>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">Additional Address</label>
                                            <input type="text" name="address_line"
                                                class="form-control">
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
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">SSS / GSIS Number</label>
                                            <input type="text" name="sss_gsis_no"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Year Graduated From PMS/PNPA/Others *</label>
                                            <input type="number" name="year_graduated"
                                                class="form-control" required>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">Institution (PMS/PNPA/Others) *</label>
                                            <input type="text" name="graduation_school"
                                                class="form-control" required>
                                        </div>

                                    </div>

                                </div>

                                <!-- DEPENDENTS -->

                                <div class="tab-pane fade" id="dependents">

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="fw-bold mb-1">Dependents Information</h5>
                                            <p class="text-muted small mb-0">Add each child or dependent, relationship, and date of birth.</p>
                                        </div>

                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="addDependent()">
                                            + Add Dependent
                                        </button>
                                    </div>

                                    <div id="dependentContainer">

                                        <div class="dependent-item border rounded p-3 mb-3">

                                            <div class="d-flex justify-content-between mb-3">
                                                <h6 class="fw-bold">Dependent 1</h6>
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
                                                        name="dependents[0][full_name]"
                                                        class="form-control">
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Relationship</label>
                                                    <select name="dependents[0][relationship]"
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
                                                        name="dependents[0][birth_date]"
                                                        class="form-control">
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <!-- RECOMMENDER -->

                                <div class="tab-pane fade" id="recommender">

                                    <h5 class="fw-bold mb-3">Recommender Information</h5>
                                    <p class="text-muted small">Details of the regular CAAC member recommending the applicant.</p>

                                    <div class="row g-3">

                                        <div class="alert alert-info small">
                                            Regular CAAC Member Recommending the Applicant.
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Last Name *</label>
                                            <input type="text" name="recommender_last_name"
                                                class="form-control" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">First Name *</label>
                                            <input type="text" name="recommender_first_name"
                                                class="form-control" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" name="recommender_middle_name"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">Year Graduated From PMS</label>
                                            <input type="number" name="recommender_year_graduated"
                                                class="form-control">
                                        </div>

                                    </div>

                                    <hr class="my-4">

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            id="declaration" name="declaration" value="1" required>
                                        <label class="form-check-label" for="declaration">
                                            I hereby certify that the information provided in this registration form is true and correct to the best of my knowledge.
                                        </label>
                                    </div>

                                </div>

                                <!-- ACCOUNT REGISTRATION -->

                                <div class="tab-pane fade" id="account">

                                    <h5 class="fw-bold mb-3">Account Registration</h5>
                                    <p class="text-muted small">
                                        Create your login credentials. The email address will be used to access your CAAC System account.
                                    </p>

                                    <div class="row g-3">

                                        <div class="col-md-12">
                                            <label for="accountEmail" class="form-label">Email Address *</label>
                                            <input type="email" id="accountEmail" name="account_email"
                                                class="form-control" autocomplete="email" required>
                                            <div class="form-text">
                                                Please provide a valid and active email address.
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="password" class="form-label">Password *</label>
                                            <input type="password" id="password" name="password"
                                                class="form-control" minlength="8"
                                                autocomplete="new-password" required>
                                            <div class="form-text">
                                                Password must contain at least 8 characters.
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="passwordConfirmation" class="form-label">Confirm Password *</label>
                                            <input type="password" id="passwordConfirmation"
                                                name="password_confirmation" class="form-control"
                                                minlength="8" autocomplete="new-password" required>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- Form Navigation -->
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
                                    Submit Registration
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

        let currentStep = 0;

        function showStep(index) {
            currentStep = index;

            // Update the non-clickable step indicators.
            tabIndicators.forEach((indicator, i) => {
                indicator.classList.toggle('active', i === index);
                indicator.classList.toggle('completed', i < index);

                if (i === index) {
                    indicator.setAttribute('aria-current', 'step');
                } else {
                    indicator.removeAttribute('aria-current');
                }
            });

            // Show only the selected form section.
            tabPanes.forEach((pane, i) => {
                pane.classList.toggle('show', i === index);
                pane.classList.toggle('active', i === index);
            });

            // Show the appropriate navigation buttons.
            prevBtn.disabled = index === 0;
            nextBtn.classList.toggle('d-none', index === tabPanes.length - 1);
            submitBtn.classList.toggle('d-none', index !== tabPanes.length - 1);

            // Bring the form heading into view after changing steps.
            document.getElementById('registrationTabs').scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }

        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('passwordConfirmation');

        function validatePasswordConfirmation() {
            if (password.value !== passwordConfirmation.value) {
                passwordConfirmation.setCustomValidity('The passwords do not match.');
            } else {
                passwordConfirmation.setCustomValidity('');
            }
        }

        password.addEventListener('input', validatePasswordConfirmation);
        passwordConfirmation.addEventListener('input', validatePasswordConfirmation);

        function validateCurrentStep() {
            validatePasswordConfirmation();

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

        // Save & Continue: validate this section, then move forward.
        nextBtn.addEventListener('click', function () {
            if (!validateCurrentStep()) {
                return;
            }

            if (currentStep < tabPanes.length - 1) {
                showStep(currentStep + 1);
            }
        });

        // Previous: return to the preceding section.
        prevBtn.addEventListener('click', function () {
            if (currentStep > 0) {
                showStep(currentStep - 1);
            }
        });

        // Validate the final section before allowing form submission.
        document.getElementById('registrationForm').addEventListener('submit', function (event) {
            if (!validateCurrentStep()) {
                event.preventDefault();
            }
        });

        showStep(0);
    });

    // Add Dependent
    let dependentCount = 1;

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

    // Remove Dependent
    function removeDependent(button) {
        button.closest('.dependent-item').remove();
    }
    </script>

</body>
</html>