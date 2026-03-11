<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MatrimonyController;
use App\Http\Controllers\UserProfileController;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    $featuredProfiles = UserProfile::query()
        ->whereHas("user", fn($q) => $q->where("approved", true))
        ->inRandomOrder()
        ->limit(4)
        ->get();

    return view("welcome", ["featuredProfiles" => $featuredProfiles]);
});

# PUBLIC PAGES
Route::view("/about", "root.about")->name("root.about");
Route::get("/matrimony", [MatrimonyController::class, "index"])->name(
    "root.matrimony",
);

# AUTHENTICATION
Route::view("/login", "auth.login")->name("login")->middleware("guest");

Route::post("/login", [LoginController::class, "__invoke"])
    ->name("login.submit")
    ->middleware("guest");

Route::view("/register", "auth.register")
    ->name("register")
    ->middleware("guest");

Route::post("/register", [RegisterController::class, "__invoke"])
    ->name("register.submit")
    ->middleware("guest");

Route::post("/logout", [LogoutController::class, "__invoke"])
    ->name("logout")
    ->middleware("auth");

#ONBOARDING
Route::get("/onboarding/create", [UserProfileController::class, "create"])
    ->middleware("auth")
    ->name("onboarding.create");

Route::post("/onboarding/store", [UserProfileController::class, "store"])
    ->middleware("auth")
    ->name("onboarding.store");

#LOGGED IN USER PAGES
Route::get("/profile", [UserProfileController::class, "myProfile"])
    ->middleware("auth")
    ->name("profile");

# Image routes MUST come before the wildcard /profile/{userProfile} route
Route::post("/profile/images/upload", [
    UserProfileController::class,
    "uploadImages",
])
    ->middleware("auth")
    ->name("profile.images.upload");

Route::post("/profile/images/primary", [
    UserProfileController::class,
    "setPrimaryImage",
])
    ->middleware("auth")
    ->name("profile.images.primary");

Route::get("/profile/{userProfile}", [UserProfileController::class, "show"])
    ->middleware("auth")
    ->name("profile.show");

#ADMIN PAGES
Route::view("/admin", "admin.admin")->middleware("auth")->name("admin");

// Route::view("/admin/users", "admin.users")->middleware('auth')->name('admin.users');

Route::get("/admin/users", [AdminUserController::class, "index"])
    ->name("admin.users")
    ->middleware("auth");

Route::post("/users/{user}/approve", [AdminUserController::class, "approve"])
    ->name("users.approve")
    ->middleware("auth");

Route::view("/admin/settings", "admin.settings")
    ->middleware("auth")
    ->name("admin.settings");
