
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\RegistrationManagementController;

// Show login page
Route::get('/', [LoginController::class, 'showLoginForm'])
    ->name('login');

// Process login form
Route::post('/login', [LoginController::class, 'login'])
    ->name('login.submit');

// Registration Routes
Route::get('/register', [RegistrationController::class, 'create'])
    ->name('registration');

Route::post('/register', [RegistrationController::class, 'store'])
    ->name('registration.store');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// User and Registration Management Routes
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // User Management Page
        Route::get('/users', [UserManagementController::class, 'index'])
            ->name('users.index');

        // Edit Account
        Route::patch('/users/{user}', [UserManagementController::class, 'updateAccount'])
            ->name('users.update');

         // Registration Management Page
        Route::get('/users/registration-management', [RegistrationManagementController::class, 'index'])
            ->name('users.registration-management');

        // Registration Details Modal
        Route::get('/users/{user}/registration', [RegistrationManagementController::class, 'registrationDetails'])
            ->name('users.registration');

        Route::patch('/registrations/{registrationId}/approve',
            [RegistrationManagementController::class, 'approveRegistration']
            )->name('registrations.approve');

        Route::patch('/registrations/{registrationId}/return',
           [RegistrationManagementController::class, 'returnRegistration']
            )->name('registrations.return');

        Route::patch('/registrations/{registrationId}/reject',
            [RegistrationManagementController::class, 'rejectRegistration']
            )->name('registrations.reject');

});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');