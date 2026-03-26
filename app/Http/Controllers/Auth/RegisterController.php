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
     * Only collects name + phone_number (+ optional email). No password.
     */
    public function __invoke(Request $request)
    {
        $request->merge([
            'phone_number' => preg_replace('/\D+/', '', (string) $request->input('phone_number')),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'digits:10', 'unique:users,phone_number'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
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
                'email' => $validated['email'] ?? null,
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
            'phone_number' => $user->phone_number,
            'verification_step' => $user->verification_step,
            'ip' => $request->ip(),
        ]);

        return redirect('/registration-submitted');
    }
}
