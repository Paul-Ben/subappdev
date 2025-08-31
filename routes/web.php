<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User Management
    Route::resource('users', UserController::class, ['as' => 'admin']);
    Route::patch('/users/{user}/toggle-verification', [UserController::class, 'toggleVerification'])->name('admin.users.toggleVerification');
    Route::post('/users/{user}/impersonate', [UserController::class, 'impersonate'])->name('admin.users.impersonate');
    Route::post('/stop-impersonating', [UserController::class, 'stopImpersonating'])->name('admin.users.stopImpersonating');
    
    // Subscription Plans
    Route::resource('subscription-plans', SubscriptionPlanController::class);
});

// User routes
Route::middleware(['auth', 'role:user,admin'])->prefix('user')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
});

// Redirect after login based on role
Route::middleware('auth')->get('/dashboard', function () {
    $user = Auth::user();
    if ($user && $user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->name('dashboard');

require __DIR__.'/auth.php';
