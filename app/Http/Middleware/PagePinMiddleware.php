<?php

namespace App\Http\Middleware;

use App\Models\AdminPagePin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PagePinMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $page
    ): Response {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (! $user->isSuperAdmin()) {
            abort(403);
        }

        $pagePin = AdminPagePin::where('user_id', $user->id)
            ->where('page_key', $page)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | PAGE IS NOT LOCKED
        |--------------------------------------------------------------------------
        */
        if (! $pagePin || ! $pagePin->enabled) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | PIN IS ALREADY VERIFIED
        |--------------------------------------------------------------------------
        */
        if (
            $request->session()->get(
                'admin_page_pin_verified.' . $page,
                false
            )
        ) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | DIRECT URL ACCESS
        |--------------------------------------------------------------------------
        |
        | If user manually types the locked URL, redirect to profile.
        | Normal sidebar/menu clicks are handled by the popup JavaScript.
        |
        */
        $request->session()->put(
            'admin_page_pin_url',
            $request->fullUrl()
        );

        $request->session()->put(
            'admin_page_pin_page',
            $page
        );

        return redirect()->route('admin.profile');
    }
}