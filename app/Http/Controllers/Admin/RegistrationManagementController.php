<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Registration;
use App\Models\RegistrationReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrationManagementController extends Controller
{
    /**
     * Display the Registration Management page.
     */
    public function index(Request $request)
    {
        // Get available registration statuses from the database
        $statuses = Registration::whereNotNull('status')
            ->distinct()
            ->orderBy('status')
            ->pluck('status')
            ->toArray();

        // Default status tab
        $status = $request->query('status', $statuses[0] ?? null);

        // Validate selected status
        if ($status !== null && !in_array($status, $statuses, true)) {
            $status = $statuses[0] ?? null;
        }

        // Initialize Registration query
        $query = Registration::query();

        // Filter by registration status
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

        return view('admin.user-management.registration-management', compact(
            'registrations',
            'statuses',
            'status'
        ));
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
    public function approveRegistration($registrationId)
    {
        DB::transaction(function () use ($registrationId) {

            $registration = Registration::where(
                'registration_id',
                $registrationId
            )->lockForUpdate()->firstOrFail();

            // Validate registration status
            $status = strtolower(trim($registration->status));

            if (!in_array($status, ['submitted', 'returned'])) {
                abort(422, 'This registration cannot be reviewed.');
            }

            // Update registration
            $registration->status = 'Approved';
            $registration->reviewed_by = Auth::id();
            $registration->reviewed_at = now();
            $registration->remarks = null;

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
                'remarks' => null,
                'reviewed_at' => now(),
            ]);

        });

        return redirect()->back()->with(
            'success',
            'Registration successfully approved and user account activated.'
        );
    }

    /**
     * Return Registration with Remarks
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

        return redirect()->back()->with(
            'success',
            'Registration successfully returned with remarks.'
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