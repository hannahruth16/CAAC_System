<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Auth\LoginController;

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

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

// Registration routes
Route::get('/register', [RegistrationController::class, 'create'])
    ->name('registration');

Route::post('/register', [RegistrationController::class, 'store'])
    ->name('registration.store');