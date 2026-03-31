<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * Handle a registration request.
     * Collects name, phone_number, gender, optional email, and password.
     */
    public function __invoke(Request $request)
    {
        $request->merge([
            'phone_number' => preg_replace('/\D+/', '', (string) $request->input('phone_number')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'digits:10', 'unique:users,phone_number'],
            'gender' => ['required', 'in:male,female,other'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Log::debug('Registration submission received.', [
            'phone_number' => $validated['phone_number'],
            'email_present' => filled($validated['email'] ?? null),
            'ip' => $request->ip(),
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'phone_number' => $validated['phone_number'],
                'gender' => $validated['gender'],
                'email' => $validated['email'] ?? null,
                'password' => $validated['password'],
                'public_id' => User::generatePublicId($validated['gender']),
                'verification_step' => 'unverified',
            ]);
        } catch (\Throwable $e) {
            Log::error('Registration failed.', [
                'phone_number' => $validated['phone_number'],
                'email' => $validated['email'] ?? null,
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }

        Log::info('Registration created.', [
            'user_id' => $user->id,
            'public_id' => $user->public_id,
            'phone_number' => $user->phone_number,
            'gender' => $user->gender,
            'verification_step' => $user->verification_step,
            'ip' => $request->ip(),
        ]);

        return redirect('/registration-submitted');
    }
}
