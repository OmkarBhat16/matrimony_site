<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt using phone_number + password.
     */
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            "phone_number" => ["required", "string"],
            "password" => ["required"],
        ]);

        if (Auth::attempt($credentials, $request->boolean("remember"))) {
            $request->session()->regenerate();

            $user = auth()->user();

            if ($user->isApproved()) {
                return redirect()->intended("/matrimony");
            } elseif ($user->needsOnboarding()) {
                return redirect("/onboarding/create");
            } elseif ($user->isPendingReview()) {
                return redirect("/pending-review");
            }

            // Shouldn't reach here, but fallback
            return redirect("/matrimony");
        }

        throw ValidationException::withMessages([
            "phone_number" => "The provided credentials do not match our records.",
        ]);
    }
}
