<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Registration;

class UserManagementController extends Controller
{
    /**
     * Display the User Management page.
     */
    public function index(Request $request)
    {
        $statuses = [
            'active',
            'pending',
            'returned',
            'rejected',
        ];

        // Default tab
        $status = strtolower($request->query('status', 'active'));

        // Validate status
        if (!in_array($status, $statuses, true)) {
            $status = 'active';
        }

        // Initialize query
        $query = User::with('registration')
            ->where('account_status', $status);

        // Search
        if ($request->filled('search')) {

            $search = $request->input('search');

            $query->where(function ($q) use ($search) {

                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")

                    ->orWhereHas('registration', function ($r) use ($search) {

                        $r->where('first_name', 'like', "%{$search}%")
                            ->orWhere('family_name', 'like', "%{$search}%")
                            ->orWhere('mobile_no', 'like', "%{$search}%");

                    });

            });

        }

        // Pagination
        $users = $query
            ->orderBy('user_id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.user-management.index', compact(
            'users',
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
     * Update account information.
     */
    public function updateAccount(Request $request, $userId)
    {
        $user = User::where('user_id', $userId)->firstOrFail();

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $userId . ',user_id',
            'email' => 'required|email|max:255|unique:users,email,' . $userId . ',user_id',
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.users.index', ['status' => 'active'])
            ->with('success', 'Account information updated successfully.');
    }
}
