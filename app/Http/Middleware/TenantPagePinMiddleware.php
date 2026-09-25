<?php

namespace App\Http\Middleware;

use App\Models\TenantPagePin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantPagePinMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $page
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | AUTHENTICATION
        |--------------------------------------------------------------------------
        */

        if (! auth()->check()) {

            return redirect()->route('login');

        }


        /*
        |--------------------------------------------------------------------------
        | CURRENT USER
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | TENANT CHECK
        |--------------------------------------------------------------------------
        */

        if (! $user->tenant_id) {

            abort(
                403,
                'You are not associated with any business.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FIND PAGE PIN
        |--------------------------------------------------------------------------
        */

        $pagePin = TenantPagePin::query()
            ->where(
                'tenant_id',
                $user->tenant_id
            )
            ->where(
                'page_key',
                $page
            )
            ->first();


        /*
        |--------------------------------------------------------------------------
        | PAGE NOT LOCKED
        |--------------------------------------------------------------------------
        */

        if (
            ! $pagePin ||
            ! (bool) $pagePin->enabled
        ) {

            return $next($request);

        }


        /*
        |--------------------------------------------------------------------------
        | PIN NOT CONFIGURED
        |--------------------------------------------------------------------------
        |
        | Agar page enabled hai lekin PIN set nahi hai,
        | request ko block nahi karenge.
        |
        */

        if (
            empty($pagePin->pin_hash)
        ) {

            return $next($request);

        }


        /*
        |--------------------------------------------------------------------------
        | ALREADY VERIFIED FOR CURRENT SESSION
        |--------------------------------------------------------------------------
        */

        $verified = $request->session()->get(
            'tenant_page_pin_verified.' . $page,
            false
        );


        if ($verified === true) {

            return $next($request);

        }


        /*
        |--------------------------------------------------------------------------
        | SAVE ORIGINAL TARGET URL
        |--------------------------------------------------------------------------
        |
        | Ye URL baad mein TenantPagePinController::verify()
        | use karega.
        |
        */

        $request->session()->put(
            'tenant_page_pin_url',
            $request->fullUrl()
        );


        /*
        |--------------------------------------------------------------------------
        | SAVE ORIGINAL PAGE KEY
        |--------------------------------------------------------------------------
        */

        $request->session()->put(
            'tenant_page_pin_page',
            $page
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT TO TENANT DASHBOARD
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Dashboard PIN protected nahi hona chahiye.
        |
        | Sidebar ka JavaScript normal click par is middleware
        | tak request pahunchne nahi dega; wo pehle PIN popup
        | show karega.
        |
        | Ye redirect mainly direct URL access / fallback ke
        | liye hai.
        |
        */

        return redirect()->route(
            'tenant.dashboard'
        );
    }
}