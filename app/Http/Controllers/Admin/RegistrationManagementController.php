<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Registration;
use App\Models\RegistrationReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\ApplicationReturnedMail;
use App\Mail\RegistrationForBoardApproval;
use App\Mail\RegistrationApproved;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class RegistrationManagementController extends Controller
{
    /**
     * Display the Registration Management page.
     */
    public function index(Request $request)
    {
        $roleId = Auth::user()->role_id;

        // Available registration statuses based on role
        if ($roleId == 4) {
            $statuses = [
                'Approved',
                'For Board Approval',
            ];
        } else {
            $statuses = Registration::whereNotNull('status')
                ->distinct()
                ->orderBy('status')
                ->pluck('status')
                ->toArray();
        }

        // Default status tab
        $status = $request->query('status', $statuses[0] ?? null);

        // Validate selected status
        if ($status !== null && !in_array($status, $statuses, true)) {
            $status = $statuses[0] ?? null;
        }

        // Initialize Registration query
        $query = Registration::query();

        // IMPORTANT: Restrict role_id 4
        if ($roleId == 4) {
            $query->whereIn('status', [
                'Approved',
                'For Board Approval',
            ]);
        }

        // Filter by selected registration status
        if ($status !== null) {
            $query->where('status', $status);
        }

        // Search registration records
        if ($request->filled('search')) {

            $search = $request->input('search');

            $query->where(function ($q) use ($search) {

                $q->where('registration_no', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('family_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('mobile_no', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");

            });
        }

        // Pagination
        $registrations = $query
            ->orderByDesc('registration_id')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.user-management.registration-management',
            compact('registrations', 'statuses', 'status')
        );
    }

    /**
     * Retrieve registration information for the View Details modal.
     */
    public function registrationDetails($userId)
    {
        $user = User::with([
            'registration.addresses',
            'registration.dependents',
            'registration.recommender',
            'registration.documents',
            'registration.reviews',
            'registration.reviewer',
        ])->where('user_id', $userId)->firstOrFail();

        if (!$user->registration) {
            return response()->json([
                'message' => 'No registration record found for this user.'
            ], 404);
        }

        $registration = $user->registration;

        // Restrict Board Approver to Approved and For Board Approval
        if (Auth::user()->role_id == 4) {

            $allowedStatuses = [
                'approved',
                'for board approval',
            ];

            if (!in_array(
                strtolower(trim($registration->status)),
                $allowedStatuses
            )) {
                abort(403, 'This registration is not available.');
            }
        }

        return response()->json([
            'user' => [
                'user_id' => $user->user_id,
                'username' => $user->username,
                'email' => $user->email,
                'account_status' => $user->account_status,
            ],

            'registration' => [
                'registration_id' => $registration->registration_id,
                'registration_no' => $registration->registration_no,

                // Personal Information
                'family_name' => $registration->family_name,
                'first_name' => $registration->first_name,
                'middle_name' => $registration->middle_name,
                'suffix' => $registration->suffix,
                'full_name' => $registration->full_name,
                'gender' => $registration->gender,
                'height' => $registration->height,
                'age' => $registration->age,
                'birth_date' => $registration->birth_date?->format('F d, Y'),
                'birth_place' => $registration->birth_place,
                'marital_status' => $registration->marital_status,
                'citizenship' => $registration->citizenship,
                'religion' => $registration->religion,

                // Contact Information
                'mobile_no' => $registration->mobile_no,
                'landline' => $registration->landline,
                'email' => $registration->email,

                // Background
                'tin_no' => $registration->tin_no,
                'sss_gsis_no' => $registration->sss_gsis_no,
                'year_graduated' => $registration->year_graduated,
                'graduation_school' => $registration->graduation_school,

                // Registration status
                'status' => $registration->status,
                'submitted_at' => $registration->submitted_at?->format('F d, Y h:i A'),
                'reviewed_at' => $registration->reviewed_at?->format('F d, Y h:i A'),
                'remarks' => $registration->remarks,
                'reviewer' => $registration->reviewer?->username,

                // Address records
                'addresses' => $registration->addresses->map(function ($address) {
                    return [
                        'address_type' => $address->address_type,
                        'address_line' => $address->address_line,
                        'house_no' => $address->house_no,
                        'street' => $address->street,
                        'barangay' => $address->barangay,
                        'municipality' => $address->municipality,
                        'city' => $address->city,
                        'province' => $address->province,
                        'region' => $address->region,
                        'zip_code' => $address->zip_code,
                        'is_primary' => $address->is_primary,
                    ];
                })->values(),

                // Dependents
                'dependents' => $registration->dependents->map(function ($dependent) {
                    return [
                        'full_name' => $dependent->full_name,
                        'relationship' => $dependent->relationship,
                        'date_of_birth' => $dependent->date_of_birth?->format('F d, Y'),
                        'remarks' => $dependent->remarks,
                    ];
                })->values(),

                // Recommender
                'recommender' => $registration->recommender ? [
                    'last_name' => $registration->recommender->last_name,
                    'first_name' => $registration->recommender->first_name,
                    'middle_name' => $registration->recommender->middle_name,
                    'year_graduated' => $registration->recommender->year_graduated,
                ] : null,

                // Uploaded documents
                'documents' => $registration->documents->map(function ($document) {
                    return [
                        'document_type' => $document->document_type ?? null,
                        'document_name' => $document->document_name ?? null,
                        'status' => $document->status ?? null,
                    ];
                })->values(),
            ],
        ]);
    }

    /**
     * Approve Registration
     */
    public function approveRegistration(Request $request, $registrationId)
    {
        // Validate optional remarks
        $validated = $request->validate([
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($registrationId, $validated) {

            $registration = Registration::where(
                'registration_id',
                $registrationId
            )->lockForUpdate()->firstOrFail();

            // Validate registration status
           $roleId = Auth::user()->role_id;
            $status = strtolower(trim($registration->status));

            // Role 3: Initial Approver
            if ($roleId == 3) {

                if (!in_array($status, ['submitted', 'returned', 'resubmitted'])) {
                    abort(422, 'This registration cannot be reviewed.');
                }

            // Role 4: Board Approver
            } elseif ($roleId == 4) {

                if ($status !== 'for board approval') {
                    abort(422, 'This registration is not available for Board Approval.');
                }

            } else {
                abort(403, 'You are not authorized to approve registrations.');
            }

            $remarks = !empty(trim($validated['remarks'] ?? ''))
                ? trim($validated['remarks'])
                : null;

            // Update registration status
            // Update registration status based on approver's role_id
            if (Auth::user()->role_id == 3) {
                $registration->status = 'For Board Approval';
            } elseif (Auth::user()->role_id == 4) {
                $registration->status = 'Approved';
            }

            $registration->reviewed_by = Auth::id();
            $registration->reviewed_at = now();
            $registration->remarks = $remarks;

            $registration->save();

            // AUTOMATICALLY ACTIVATE USER ACCOUNT
            $user = User::where(
                'user_id',
                $registration->user_id
            )->lockForUpdate()->firstOrFail();

            $user->account_status = 'active';
            $user->save();

            // SAVE REVIEW HISTORY
            RegistrationReview::create([
                'registration_id' => $registration->registration_id,
                'reviewer_id' => Auth::id(),
                'action' => 'approved',
                'remarks' => $remarks,
                'reviewed_at' => now(),
            ]);

        });

        // RETRIEVE UPDATED REGISTRATION
        $registration = Registration::with('user')
            ->where('registration_id', $registrationId)
            ->firstOrFail();

        // BUILD APPLICANT'S FULL NAME
        $applicantName = trim(
            $registration->first_name . ' ' .
            ($registration->middle_name ?? '') . ' ' .
            $registration->family_name . ' ' .
            ($registration->suffix ?? '')
        );

        // SEND EMAIL NOTIFICATION
        if (Auth::user()->role_id == 3) {

        // Notify applicant that registration was endorsed
        Mail::to($registration->user->email)
            ->send(new RegistrationForBoardApproval(
                $registration,
                $applicantName
            ));

            } elseif (Auth::user()->role_id == 4) {

                // Notify applicant that registration was approved by the Board
                Mail::to($registration->user->email)
                    ->send(new RegistrationApproved(
                        $registration,
                        $applicantName
                    ));

            }

        return redirect()->back()->with(
            'success',
            'Registration successfully endorsed for Board Approval. Email notification sent.'
        );
    }

    /**
     * Return Registration with Remarks and Send Email
     */
    public function returnRegistration(Request $request, $registrationId)
    {
        $validated = $request->validate([
            'remarks' => [
                'required',
                'string',
                'min:5',
                'max:1000',
            ],
        ]);

        // Update registration and save review history.
        DB::transaction(function () use ($registrationId, $validated) {

            $registration = Registration::where(
                'registration_id',
                $registrationId
            )->lockForUpdate()->firstOrFail();

            $status = strtolower(trim($registration->status));

            if (!in_array($status, ['submitted', 'resubmitted'])) {
                abort(422, 'This registration cannot be reviewed.');
            }

            // Update registration status.
            $registration->status = 'returned';
            $registration->reviewed_by = Auth::id();
            $registration->reviewed_at = now();
            $registration->remarks = $validated['remarks'];

            $registration->save();

            // Save review history.
            RegistrationReview::create([
                'registration_id' => $registration->registration_id,
                'reviewer_id' => Auth::id(),
                'action' => 'returned',
                'remarks' => $validated['remarks'],
                'reviewed_at' => now(),
            ]);

        });

        // Retrieve updated registration.
        $registration = Registration::findOrFail($registrationId);

        // Build applicant's full name.
        $applicantName = trim(
            $registration->first_name . ' ' .
            ($registration->middle_name ?? '') . ' ' .
            $registration->family_name . ' ' .
            ($registration->suffix ?? '')
        );

        $resubmitUrl = URL::signedRoute(
            'registration.resubmit.form',
            [
                'registrationId' => $registration->registration_id
            ]
        );
        
        // Send returned application email.
        try {

            Mail::to($registration->email)->send(
                new ApplicationReturnedMail(
                    $registration,
                    $applicantName,
                    $resubmitUrl
                )
            );
            

        } catch (\Throwable $e) {

            Log::error('CAAC Application Returned Email Failed', [
                'registration_id' => $registration->registration_id,
                'email' => $registration->email,
                'error' => $e->getMessage(),
            ]);

        }

        return redirect()->back()->with(
            'success',
            'Registration successfully returned with remarks. Applicant notification email has been processed.'
        );
    }

    /**
     * Reject Registration with Remarks
     */
    public function rejectRegistration(Request $request, $registrationId)
    {
        // Validate rejection remarks
        $validated = $request->validate([

            'remarks' => [
                'required',
                'string',
                'min:5',
                'max:1000',
            ],

        ]);

        DB::transaction(function () use ($registrationId, $validated) {

            $registration = Registration::where(
                'registration_id',
                $registrationId
            )->lockForUpdate()->firstOrFail();

            // Validate registration status
            $status = strtolower(trim($registration->status));

            if (!in_array($status, ['submitted', 'resubmitted'])) {
                abort(422, 'This registration cannot be rejected.');
            }

            // Update registration
            $registration->status = 'rejected';
            $registration->reviewed_by = Auth::id();
            $registration->reviewed_at = now();
            $registration->remarks = $validated['remarks'];

            $registration->save();

             // AUTOMATICALLY ACTIVATE USER ACCOUNT
            $user = User::where(
                'user_id',
                $registration->user_id
            )->lockForUpdate()->firstOrFail();

            $user->account_status = 'rejected';
            $user->save();

            // Save review history
            RegistrationReview::create([
                'registration_id' => $registration->registration_id,
                'reviewer_id' => Auth::id(),
                'action' => 'rejected',
                'remarks' => $validated['remarks'],
                'reviewed_at' => now(),
            ]);

        });

        return redirect()->back()->with(
            'success',
            'Registration successfully rejected.'
        );
    }

}