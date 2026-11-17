<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Dependent;
use App\Models\Recommender;
use App\Models\Registration;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Mail\ApplicationReceivedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class RegistrationController extends Controller
{
    /**
     * Display the applicant registration form.
     */
    public function create(): View
    {
        return view('registration');
    }

    /**
     * Save the account and all registration details, then submit the application.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Account registration
            'account_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Personal information
            'family_name' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', 'string', 'max:30'],
            'height' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'birth_date' => ['required', 'date', 'before:today'],
            'birth_place' => ['required', 'string', 'max:255'],
            'marital_status' => ['required', 'string', 'max:50'],
            'citizenship' => ['required', 'string', 'max:100'],
            'religion' => ['nullable', 'string', 'max:100'],

            // Residential address
            'house_no' => ['nullable', 'string', 'max:50'],
            'street' => ['nullable', 'string', 'max:150'],
            'barangay' => ['required', 'string', 'max:150'],
            'municipality' => ['required', 'string', 'max:150'],
            'city' => ['required', 'string', 'max:150'],
            'province' => ['required', 'string', 'max:150'],
            'region' => ['nullable', 'string', 'max:150'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'address_line' => ['nullable', 'string', 'max:255'],

            // Contact details
            'mobile_no' => ['required', 'string', 'max:30'],
            'landline' => ['nullable', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],

            // Background
            'tin_no' => ['nullable', 'string', 'max:50'],
            'sss_gsis_no' => ['nullable', 'string', 'max:50'],
            'year_graduated' => ['required', 'integer', 'min:1900', 'max:' . now()->year],
            'graduation_school' => ['required', 'string', 'max:255'],

            // Dependents. Blank dependent rows are ignored below.
            'dependents' => ['nullable', 'array'],
            'dependents.*.full_name' => ['nullable', 'string', 'max:200'],
            'dependents.*.relationship' => ['nullable', 'string', 'max:100'],
            'dependents.*.birth_date' => ['nullable', 'date', 'before_or_equal:today'],

            // Recommender
            'recommender_last_name' => ['required', 'string', 'max:100'],
            'recommender_first_name' => ['required', 'string', 'max:100'],
            'recommender_middle_name' => ['nullable', 'string', 'max:100'],
            'recommender_year_graduated' => ['nullable', 'integer', 'min:1900', 'max:' . now()->year],

            // Declaration
            'declaration' => ['accepted'],
        ]);

        // Remove unused/blank dependent rows before inserting.
        $dependents = collect($validated['dependents'] ?? [])
            ->filter(fn ($dependent) => filled($dependent['full_name'] ?? null))
            ->values();

        // The Applicant role must already exist in the roles table.
        $applicantRole = Role::where('role_name', 'Applicant')
            ->where('is_active', true)
            ->first();

        if (!$applicantRole) {
            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors([
                    'account_email' => 'The Applicant role is not configured. Please contact the system administrator.',
                ]);
        }

        $registration = DB::transaction(function () use ($validated, $dependents, $applicantRole) {
            // Create login account. The User model's password_hash hashed cast
            // hashes the plain password before it is stored.
            $user = User::create([
                'role_id' => $applicantRole->role_id,
                'username' => $validated['account_email'],
                'password' => $validated['password'],
                'email' => $validated['account_email'],
                'account_status' => 'Pending',
            ]);

            // Create the applicant's registration record.
            $registration = Registration::create([
                'user_id' => $user->user_id,
                'registration_no' => $this->generateRegistrationNumber(),
                'family_name' => $validated['family_name'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'suffix' => $validated['suffix'] ?? null,
                'gender' => $validated['gender'],
                'height' => $validated['height'] ?? null,
                'birth_date' => $validated['birth_date'],
                'birth_place' => $validated['birth_place'],
                'marital_status' => $validated['marital_status'],
                'citizenship' => $validated['citizenship'],
                'religion' => $validated['religion'] ?? null,
                'mobile_no' => $validated['mobile_no'],
                'landline' => $validated['landline'] ?? null,
                'tin_no' => $validated['tin_no'] ?? null,
                'sss_gsis_no' => $validated['sss_gsis_no'] ?? null,
                'email' => $validated['email'],
                'year_graduated' => $validated['year_graduated'],
                'graduation_school' => $validated['graduation_school'],
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            // Save residential address.
            Address::create([
                'registration_id' => $registration->registration_id,
                'address_type' => 'Residential',
                'house_no' => $validated['house_no'] ?? null,
                'street' => $validated['street'] ?? null,
                'barangay' => $validated['barangay'],
                'municipality' => $validated['municipality'],
                'city' => $validated['city'],
                'province' => $validated['province'],
                'region' => $validated['region'] ?? null,
                'zip_code' => $validated['zip_code'] ?? null,
                'address_line' => $validated['address_line'] ?? null,
                'is_primary' => true,
            ]);

            // Save each non-empty dependent.
            foreach ($dependents as $dependent) {
                Dependent::create([
                    'registration_id' => $registration->registration_id,
                    'full_name' => $dependent['full_name'],
                    'relationship' => $dependent['relationship'] ?? null,
                    'date_of_birth' => $dependent['birth_date'] ?? null,
                ]);
            }

            // Save the regular CAAC member who recommended the applicant.
            Recommender::create([
                'registration_id' => $registration->registration_id,
                'last_name' => $validated['recommender_last_name'],
                'first_name' => $validated['recommender_first_name'],
                'middle_name' => $validated['recommender_middle_name'] ?? null,
                'recommender_year_graduated' => $validated['recommender_year_graduated'] ?? null,
            ]);

            return $registration;
        });

        // Retrieve the saved recommender.
        $recommender = Recommender::where(
            'registration_id',
            $registration->registration_id
        )->first();

        // Build the recommender's full name.
        $recommenderName = $recommender
            ? trim(
                $recommender->first_name . ' ' .
                ($recommender->middle_name ?? '') . ' ' .
                $recommender->last_name
            )
            : 'Not provided';

        // Build the applicant's full name.
        $applicantName = trim(
            $registration->first_name . ' ' .
            ($registration->middle_name ?? '') . ' ' .
            $registration->family_name . ' ' .
            ($registration->suffix ?? '')
        );

        // Send confirmation email to applicant.
        try {

            Mail::to($registration->email)->send(
                new ApplicationReceivedMail(
                    $registration,
                    $recommenderName
                )
            );

        } catch (\Throwable $e) {

            Log::error('CAAC Application Received Email Failed', [
                'registration_id' => $registration->registration_id,
                'email' => $registration->email,
                'error' => $e->getMessage(),
            ]);

        }

        return redirect()
            ->back()
            ->with('success', 'Your registration has been submitted successfully. Your registration number is ' . $registration->registration_no . '.')
            ->with('registration_no', $registration->registration_no);

    }

    /**
     * Generate a human-readable registration number.
     * Example: CAAC-2026-A1B2C3D4
     */
    private function generateRegistrationNumber(): string
    {
        do {
            $registrationNumber = 'CAAC-' . now()->format('Y') . '-' . Str::upper(Str::random(8));
        } while (Registration::where('registration_no', $registrationNumber)->exists());

        return $registrationNumber;
    }

    /**
     * Display the applicant's returned registration for correction.
     */
    public function showResubmit($registrationId): View
    {
        // Retrieve the registration.
        $registration = Registration::where('registration_id', $registrationId)
            ->firstOrFail();

        // Verify that the registration is returned.
        $status = strtolower(trim($registration->status));

        if ($status === 'resubmitted') {
            return view('resubmission-already-submitted');
        }

        if ($status !== 'returned') {
            abort(403, 'This registration is not available for resubmission.');
        }

        // Retrieve the applicant using the registration's user_id.
        $user = User::where('user_id', $registration->user_id)
            ->firstOrFail();

        // Allow pending accounts to access returned registrations.
        if (!in_array(strtolower(trim($user->account_status)), ['pending', 'active'])) {
            abort(403, 'Your account is not authorized to access this registration.');
        }

        // Retrieve residential address.
        $address = Address::where('registration_id', $registration->registration_id)
            ->where('address_type', 'Residential')
            ->first();

        // Retrieve dependents.
        $dependents = Dependent::where('registration_id', $registration->registration_id)
            ->get();

        // Retrieve recommender.
        $recommender = Recommender::where('registration_id', $registration->registration_id)
            ->first();

        // Generate signed URL for form submission.
        $resubmitAction = URL::signedRoute(
            'registration.resubmit',
            [
                'registrationId' => $registration->registration_id
            ]
        );

        return view('resubmit-registration', compact(
            'registration',
            'user',
            'address',
            'dependents',
            'recommender',
            'resubmitAction'
        ));
    }
    
    /**
     * Update and resubmit the applicant's returned registration.
     */
    public function resubmit(Request $request, $registrationId): RedirectResponse
    {
        // Validate the submitted information.
        $validated = $request->validate([

            // Personal Information
            'family_name' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', 'string', 'max:30'],
            'height' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'birth_date' => ['required', 'date', 'before:today'],
            'birth_place' => ['required', 'string', 'max:255'],
            'marital_status' => ['required', 'string', 'max:50'],
            'citizenship' => ['required', 'string', 'max:100'],
            'religion' => ['nullable', 'string', 'max:100'],

            // Residential Address
            'house_no' => ['nullable', 'string', 'max:50'],
            'street' => ['nullable', 'string', 'max:150'],
            'barangay' => ['required', 'string', 'max:150'],
            'municipality' => ['required', 'string', 'max:150'],
            'city' => ['required', 'string', 'max:150'],
            'province' => ['required', 'string', 'max:150'],
            'region' => ['nullable', 'string', 'max:150'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'address_line' => ['nullable', 'string', 'max:255'],

            // Contact Details
            'mobile_no' => ['required', 'string', 'max:30'],
            'landline' => ['nullable', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],

            // Background Information
            'tin_no' => ['nullable', 'string', 'max:50'],
            'sss_gsis_no' => ['nullable', 'string', 'max:50'],
            'year_graduated' => ['required', 'integer', 'min:1900', 'max:' . now()->year],
            'graduation_school' => ['required', 'string', 'max:255'],

            // Dependents
            'dependents' => ['nullable', 'array'],
            'dependents.*.full_name' => ['nullable', 'string', 'max:200'],
            'dependents.*.relationship' => ['nullable', 'string', 'max:100'],
            'dependents.*.birth_date' => ['nullable', 'date', 'before_or_equal:today'],

            // Recommender
            'recommender_last_name' => ['required', 'string', 'max:100'],
            'recommender_first_name' => ['required', 'string', 'max:100'],
            'recommender_middle_name' => ['nullable', 'string', 'max:100'],
            'recommender_year_graduated' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . now()->year
            ],

            // Declaration
            'declaration' => ['accepted'],

        ]);

        // Remove empty dependent rows.
        $dependents = collect($validated['dependents'] ?? [])
            ->filter(fn ($dependent) => filled($dependent['full_name'] ?? null))
            ->values();

        // Update existing records inside one database transaction.
        DB::transaction(function () use ($registrationId, $validated, $dependents) {

            // Verify ownership and lock the registration record.
            $registration = Registration::where('registration_id', $registrationId)
                ->lockForUpdate()
                ->firstOrFail();

            // Make sure the application is still returned.
            if (strtolower(trim($registration->status)) !== 'returned') {
                abort(403, 'This registration cannot be resubmitted.');
            }

            // Update Personal Information and Background.
            $registration->family_name = $validated['family_name'];
            $registration->first_name = $validated['first_name'];
            $registration->middle_name = $validated['middle_name'] ?? null;
            $registration->suffix = $validated['suffix'] ?? null;
            $registration->gender = $validated['gender'];
            $registration->height = $validated['height'] ?? null;
            $registration->birth_date = $validated['birth_date'];
            $registration->birth_place = $validated['birth_place'];
            $registration->marital_status = $validated['marital_status'];
            $registration->citizenship = $validated['citizenship'];
            $registration->religion = $validated['religion'] ?? null;

            // Update Contact Information.
            $registration->mobile_no = $validated['mobile_no'];
            $registration->landline = $validated['landline'] ?? null;
            $registration->email = $validated['email'];

            // Update Background Information.
            $registration->tin_no = $validated['tin_no'] ?? null;
            $registration->sss_gsis_no = $validated['sss_gsis_no'] ?? null;
            $registration->year_graduated = $validated['year_graduated'];
            $registration->graduation_school = $validated['graduation_school'];

            // Update registration status.
            $registration->status = 'resubmitted';
            $registration->reviewed_by = null;
            $registration->reviewed_at = null;

            // Preserve the original registration number and return remarks.
            $registration->save();


            // UPDATE RESIDENTIAL ADDRESS.
            Address::updateOrCreate(
                [
                    'registration_id' => $registration->registration_id,
                    'address_type' => 'Residential',
                ],
                [
                    'house_no' => $validated['house_no'] ?? null,
                    'street' => $validated['street'] ?? null,
                    'barangay' => $validated['barangay'],
                    'municipality' => $validated['municipality'],
                    'city' => $validated['city'],
                    'province' => $validated['province'],
                    'region' => $validated['region'] ?? null,
                    'zip_code' => $validated['zip_code'] ?? null,
                    'address_line' => $validated['address_line'] ?? null,
                    'is_primary' => true,
                ]
            );


            // UPDATE DEPENDENTS.
            // Remove previous dependent records and save the corrected list.
            Dependent::where('registration_id', $registration->registration_id)
                ->delete();

            foreach ($dependents as $dependent) {

                Dependent::create([
                    'registration_id' => $registration->registration_id,
                    'full_name' => $dependent['full_name'],
                    'relationship' => $dependent['relationship'] ?? null,
                    'date_of_birth' => $dependent['birth_date'] ?? null,
                ]);

            }


            // UPDATE RECOMMENDER.
            Recommender::updateOrCreate(
                [
                    'registration_id' => $registration->registration_id,
                ],
                [
                    'last_name' => $validated['recommender_last_name'],
                    'first_name' => $validated['recommender_first_name'],
                    'middle_name' => $validated['recommender_middle_name'] ?? null,
                    'recommender_year_graduated' =>
                        $validated['recommender_year_graduated'] ?? null,
                ]
            );

        });

        return redirect()
            ->route('registration.resubmit.success')
            ->with('success', 'Your corrected registration has been successfully resubmitted.');
    }

}