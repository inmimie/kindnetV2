<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Applicant;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return response()
        ->view('welcome')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
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
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
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

    // Notifications
    Route::get('/notifications', [Applicant\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [Applicant\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{id}/read', [Applicant\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [Applicant\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

require __DIR__.'/auth.php';

Route::get('/debug-img', function () {
    $file = public_path('storage/charity_types/9IWHQUQMWBR1wIsUOjJqS5W7ptzKiSoNIsFLfB2t.jpg');
    $out = "File Path: " . $file . "<br>";
    $out .= "Exists: " . (file_exists($file) ? 'YES' : 'NO') . "<br>";
    if (file_exists($file)) {
        $out .= "Readable: " . (is_readable($file) ? 'YES' : 'NO') . "<br>";
        $out .= "Permissions: " . substr(sprintf('%o', fileperms($file)), -4) . "<br>";
        $out .= "Owner UID: " . fileowner($file) . "<br>";
    }
    
    // Check if the symlink itself is broken
    $symlink = public_path('storage');
    $out .= "Symlink Path: " . $symlink . "<br>";
    $out .= "Symlink Exists: " . (file_exists($symlink) ? 'YES' : 'NO') . "<br>";
    $out .= "Is Link: " . (is_link($symlink) ? 'YES' : 'NO') . "<br>";
    if (is_link($symlink)) {
        $out .= "Link Target: " . readlink($symlink) . "<br>";
    }
    return $out;
});
