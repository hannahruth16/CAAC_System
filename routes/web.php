
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserManagementController;

// Show login page
Route::get('/', [LoginController::class, 'showLoginForm'])
    ->name('login');

// Process login form
Route::post('/login', [LoginController::class, 'login'])
    ->name('login.submit');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// User Management Routes
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

        // Registration Details Modal
        Route::get('/users/{user}/registration', [UserManagementController::class, 'registrationDetails'])
            ->name('users.registration');

});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

// Registration Routes
Route::get('/register', [RegistrationController::class, 'create'])
    ->name('registration');

Route::post('/register', [RegistrationController::class, 'store'])
    ->name('registration.store');
