<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Applicant;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('applicant.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('charity-types', Admin\CharityTypeController::class);
    Route::resource('accounts', Admin\AccountController::class);
    Route::resource('applications', Admin\ApplicationController::class)->except(['create', 'store', 'destroy']);
    Route::resource('payments', Admin\PaymentController::class)->only(['create', 'store', 'index']);
});

// Applicant Routes
Route::middleware(['auth', 'verified', 'role:applicant'])->prefix('applicant')->name('applicant.')->group(function () {
    Route::get('/dashboard', [Applicant\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('applications', Applicant\ApplicationController::class);
});

require __DIR__.'/auth.php';
