<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MatrimonyController;
use App\Http\Controllers\UserProfileController;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredProfiles = UserProfile::query()
        ->whereHas('user', fn ($q) => $q->where('verification_step', 'approved'))
        ->inRandomOrder()
        ->limit(4)
        ->get();

    return view('welcome', ['featuredProfiles' => $featuredProfiles]);
});

// PUBLIC PAGES
Route::view('/about', 'root.about')->name('root.about');
Route::get('/matrimony', [MatrimonyController::class, 'index'])->name(
    'root.matrimony',
);

// AUTHENTICATION
Route::view('/login', 'auth.login')->name('login')->middleware('guest');

Route::post('/login', [LoginController::class, '__invoke'])
    ->name('login.submit')
    ->middleware('guest');

Route::view('/register', 'auth.register')
    ->name('register')
    ->middleware('guest');

Route::post('/register', [RegisterController::class, '__invoke'])
    ->name('register.submit')
    ->middleware('guest');

Route::view('/registration-submitted', 'auth.registered')
    ->name('registration.submitted')
    ->middleware('guest');

Route::view('/pending-review', 'auth.pending-review')
    ->name('pending.review')
    ->middleware('auth');

Route::post('/logout', [LogoutController::class, '__invoke'])
    ->name('logout')
    ->middleware('auth');

// ONBOARDING
Route::get('/onboarding/create', [UserProfileController::class, 'create'])
    ->middleware('auth')
    ->name('onboarding.create');

Route::post('/onboarding/store', [UserProfileController::class, 'store'])
    ->middleware('auth')
    ->name('onboarding.store');

// LOGGED IN USER PAGES (soft-blocked — require onboarding middleware)
Route::middleware(['auth', 'onboarding'])->group(function () {
    Route::get('/profile', [UserProfileController::class, 'myProfile'])->name(
        'profile',
    );

    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name(
        'profile.edit',
    );

    Route::post('/profile/edit', [
        UserProfileController::class,
        'update',
    ])->name('profile.update');

    Route::post('/profile/images/upload', [
        UserProfileController::class,
        'uploadImages',
    ])->name('profile.images.upload');

    Route::post('/profile/images/primary', [
        UserProfileController::class,
        'setPrimaryImage',
    ])->name('profile.images.primary');

    Route::get('/profile/{userProfile}', [
        UserProfileController::class,
        'show',
    ])->name('profile.show');
});

// ADMIN PAGES
Route::middleware(['auth'])->group(function () {
    Route::view('/admin', 'admin.admin')->name('admin');

    Route::get('/admin/users', [AdminUserController::class, 'index'])->name(
        'admin.users',
    );

    Route::post('/admin/users/{user}/create-account', [
        AdminUserController::class,
        'createAccount',
    ])->name('admin.users.create-account');

    Route::get('/admin/users/{user}/profile', [
        AdminUserController::class,
        'showProfile',
    ])->name('admin.users.profile');

    Route::post('/users/{user}/approve', [
        AdminUserController::class,
        'approve',
    ])->name('users.approve');

    Route::view('/admin/settings', 'admin.settings')->name('admin.settings');

    // Profile Edit Approval
    Route::get('/admin/pending-edits', [
        AdminUserController::class,
        'pendingEdits',
    ])->name('admin.pending-edits');

    Route::get('/admin/pending-edits/{edit}/review', [
        AdminUserController::class,
        'reviewEdit',
    ])->name('admin.pending-edits.review');

    Route::post('/admin/pending-edits/{edit}/approve', [
        AdminUserController::class,
        'approveEdit',
    ])->name('admin.pending-edits.approve');

    Route::post('/admin/pending-edits/{edit}/reject', [
        AdminUserController::class,
        'rejectEdit',
    ])->name('admin.pending-edits.reject');
});
