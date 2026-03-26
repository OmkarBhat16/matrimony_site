<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt using phone_number + password.
     */
    public function __invoke(Request $request)
    {
        $request->merge([
            'phone_number' => preg_replace('/\D+/', '', (string) $request->input('phone_number')),
        ]);

        $credentials = $request->validate([
            "phone_number" => ["required", "digits:10"],
            "password" => ["required"],
        ]);

        $candidateUser = User::where('phone_number', $credentials['phone_number'])->first();

        Log::debug('Login attempt received.', [
            'phone_number' => $credentials['phone_number'],
            'user_exists' => (bool) $candidateUser,
            'user_id' => $candidateUser?->id,
            'verification_step' => $candidateUser?->verification_step,
            'remember' => $request->boolean('remember'),
            'ip' => $request->ip(),
        ]);

        if (Auth::attempt($credentials, $request->boolean("remember"))) {
            $request->session()->regenerate();

            $user = auth()->user();
            $destination = '/matrimony';

            if ($user->isApproved()) {
                $destination = '/matrimony';
            } elseif ($user->needsOnboarding()) {
                $destination = '/onboarding/create';
            } elseif ($user->isPendingReview()) {
                $destination = '/pending-review';
            }

            Log::info('Login successful.', [
                'user_id' => $user->id,
                'phone_number' => $user->phone_number,
                'verification_step' => $user->verification_step,
                'redirect_to' => $destination,
                'ip' => $request->ip(),
            ]);

            return redirect()->intended($destination);
        }

        Log::info('Login rejected.', [
            'phone_number' => $credentials['phone_number'],
            'user_exists' => (bool) $candidateUser,
            'user_id' => $candidateUser?->id,
            'verification_step' => $candidateUser?->verification_step,
            'ip' => $request->ip(),
        ]);

        throw ValidationException::withMessages([
            "phone_number" => "The provided credentials do not match our records.",
        ]);
    }
}
