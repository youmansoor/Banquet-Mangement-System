<?php

namespace App\Http\Middleware;

use App\Models\TermCondition;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantAcceptedTerms
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $user = $request->user();

        // Login nahi hai
        if (! $user) {
            return $next($request);
        }

        // Tenant user nahi hai
        if (! $user->isTenantUser()) {
            return $next($request);
        }

        // Terms page aur accept request ko allow karo
        if (
            $request->routeIs('tenant.terms') ||
            $request->routeIs('tenant.terms.accept') ||
            $request->routeIs('logout')
        ) {
            return $next($request);
        }

        // Latest Terms & Conditions
        $latestTerm = TermCondition::latest('updated_at')->first();

        // Agar koi Terms hi nahi hain
        if (! $latestTerm) {
            return $next($request);
        }

        // Tenant ne Terms accept nahi kiye
        if (is_null($user->terms_accepted_at)) {
            return redirect()->route('tenant.terms');
        }

        // Terms update ho chuke hain
        if (
            $latestTerm->updated_at->greaterThan(
                $user->terms_accepted_at
            )
        ) {
            return redirect()->route('tenant.terms');
        }

        return $next($request);
    }
}