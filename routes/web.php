<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User Management
    Route::resource('users', UserController::class, ['as' => 'admin']);
    Route::patch('/users/{user}/toggle-verification', [UserController::class, 'toggleVerification'])->name('admin.users.toggleVerification');
    Route::post('/users/{user}/impersonate', [UserController::class, 'impersonate'])->name('admin.users.impersonate');
    Route::post('/stop-impersonating', [UserController::class, 'stopImpersonating'])->name('admin.users.stopImpersonating');
    
    // Subscription Plans
    Route::resource('subscription-plans', SubscriptionPlanController::class);
    
    // Subscriptions Management
    Route::get('/subscriptions', [AdminController::class, 'subscriptions'])->name('admin.subscriptions');
});

// User routes
Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
});

// Subscription routes
Route::middleware('auth')->group(function () {
    Route::get('/subscription/plans', [SubscriptionController::class, 'showPlans'])->name('subscription.plans');
    Route::post('/subscription/initiate-upgrade', [SubscriptionController::class, 'initiatePlanUpgrade'])->name('subscription.initiate-upgrade');
    
    // Payment routes
    Route::match(['get', 'post'], '/payment/initialize', [PaymentController::class, 'initializePayment'])->name('payment.initialize');
    Route::get('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');
    
    // Transaction routes
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{payment}/receipt', [TransactionController::class, 'showReceipt'])->name('transactions.receipt');
    Route::get('/transactions/{payment}/download', [TransactionController::class, 'downloadReceipt'])->name('transactions.download');
});

// Redirect after login based on role
Route::middleware('auth')->get('/dashboard', function () {
    return redirect()->route('user.dashboard');
})->name('dashboard');

require __DIR__.'/auth.php';
