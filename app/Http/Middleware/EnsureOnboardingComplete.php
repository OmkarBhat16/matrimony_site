<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    /**
     * Redirect users who haven't completed onboarding or are pending review.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Admins and superadmins bypass this middleware
        if (in_array($user->role, ['admin', 'superadmin'])) {
            return $next($request);
        }

        // Allow logout and onboarding routes always
        $path = $request->path();
        $allowedPrefixes = ['logout', 'onboarding', 'pending-review'];
        foreach ($allowedPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return $next($request);
            }
        }

        // Step 1 complete → force to onboarding
        if ($user->needsOnboarding()) {
            return redirect('/onboarding/create');
        }

        // Step 2 pending → force to pending-review page
        if ($user->isPendingReview()) {
            return redirect('/pending-review');
        }

        // Unverified users shouldn't be logged in, log them out
        if ($user->verification_step === 'unverified') {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login');
        }

        return $next($request);
    }
}
