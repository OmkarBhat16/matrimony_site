<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminFeaturedProfileController;
use App\Http\Controllers\AdminContentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MatrimonyController;
use App\Models\AboutPageContent;
use App\Models\FeaturedProfile;
use App\Models\HomePageContent;
use App\Http\Controllers\UserProfileController;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $homePageContent = HomePageContent::query()->firstOrCreate([], [
        'content' => HomePageContent::defaults(),
    ]);

    $featuredProfiles = FeaturedProfile::query()
        ->with(['userProfile.user'])
        ->latest()
        ->limit(4)
        ->get()
        ->pluck('userProfile')
        ->filter(fn ($profile) => $profile?->user?->verification_step === 'approved')
        ->values();

    return view('welcome', [
        'featuredProfiles' => $featuredProfiles,
        'homePageContent' => $homePageContent->normalizedContent(),
    ]);
});

// PUBLIC PAGES
Route::get('/about', function () {
    $aboutPageContent = AboutPageContent::query()->firstOrCreate([], [
        'content' => AboutPageContent::defaults(),
    ]);

    return view('root.about', [
        'aboutPageContent' => $aboutPageContent->normalizedContent(),
    ]);
})->name('root.about');
Route::get('/matrimony', [MatrimonyController::class, 'index'])->name(
    'root.matrimony',
);
Route::get('/profile-images/{userProfile}/{slot}', [
    UserProfileController::class,
    'showImage',
])
    ->whereNumber('slot')
    ->name('profile.images.show');

Route::get('/profile-images/{userProfile}/kundli', [
    UserProfileController::class,
    'showKundliImage',
])
    ->name('profile.kundli.show');

Route::get('/profile-images/{userProfile}/kundli/pending', [
    UserProfileController::class,
    'showPendingKundliImage',
])
    ->middleware(['auth', 'admin'])
    ->name('profile.kundli.pending.show');

Route::get('/profile-images/{userProfile}/{slot}/pending', [
    UserProfileController::class,
    'showPendingImage',
])
    ->middleware(['auth', 'admin'])
    ->whereNumber('slot')
    ->name('profile.images.pending.show');

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
    Route::get('/account', [UserProfileController::class, 'account'])->name(
        'account',
    );

    Route::get('/account/edit', [UserProfileController::class, 'edit'])->name(
        'account.edit',
    );

    Route::post('/account/edit', [
        UserProfileController::class,
        'update',
    ])->name('account.update');

    Route::post('/account/edit/password', [
        UserProfileController::class,
        'updatePassword',
    ])->name('account.password.update');

    Route::post('/account/password', [
        UserProfileController::class,
        'updatePassword',
    ])->name('account.password.update.legacy');

    Route::post('/account/images/upload', [
        UserProfileController::class,
        'uploadImages',
    ])->name('account.images.upload');

    Route::post('/account/images/primary', [
        UserProfileController::class,
        'setPrimaryImage',
    ])->name('account.images.primary');

    Route::post('/account/kundli/upload', [
        UserProfileController::class,
        'uploadKundli',
    ])->name('account.kundli.upload');

    Route::get('/profile/{user:public_id}', [
        UserProfileController::class,
        'show',
    ])->name('profile.show');
});

Route::redirect('/profile', '/account');
Route::redirect('/profile/edit', '/account/edit');

// ADMIN PAGES
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', function () {
        $user = auth()->user();

        if ($user->canAccessContentManagement() && ! $user->canAccessProfileManagementPanel()) {
            return redirect()->route('admin.content-management');
        }

        if (! $user->canAccessProfileManagementPanel()) {
            abort(403, 'Unauthorized');
        }

        return view('admin.admin');
    })->name('admin');

    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/users', [AdminUserController::class, 'index'])->name(
            'admin.users',
        );

        Route::get('/admin/featured-profiles', [
            AdminFeaturedProfileController::class,
            'index',
        ])->name('admin.featured-profiles');

        Route::post('/admin/featured-profiles', [
            AdminFeaturedProfileController::class,
            'store',
        ])->name('admin.featured-profiles.store');

        Route::delete('/admin/featured-profiles/{featuredProfile}', [
            AdminFeaturedProfileController::class,
            'destroy',
        ])->name('admin.featured-profiles.destroy');

        Route::post('/admin/users/{user}/create-account', [
            AdminUserController::class,
            'createAccount',
        ])->name('admin.users.create-account');

        Route::post('/admin/users/{user}/reset-password', [
            AdminUserController::class,
            'resetPassword',
        ])->name('admin.users.reset-password');

        Route::delete('/admin/users/{user}', [
            AdminUserController::class,
            'destroy',
        ])->name('admin.users.destroy');

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

    Route::middleware(['superadmin'])->group(function () {
        Route::get('/admin/deleted-accounts', [
            AdminUserController::class,
            'deletedAccounts',
        ])->name('admin.deleted-accounts');

        Route::post('/admin/deleted-accounts/{userId}/restore', [
            AdminUserController::class,
            'restoreDeletedAccount',
        ])->name('admin.deleted-accounts.restore');

        Route::delete('/admin/deleted-accounts/{userId}', [
            AdminUserController::class,
            'forceDeleteDeletedAccount',
        ])->name('admin.deleted-accounts.force-delete');
    });

    Route::middleware(['content'])->group(function () {
        Route::get('/admin/content-management', [
            AdminContentController::class,
            'index',
        ])->name('admin.content-management');

        Route::post('/admin/content-management', [
            AdminContentController::class,
            'update',
        ])->name('admin.content-management.update');
    });
});
