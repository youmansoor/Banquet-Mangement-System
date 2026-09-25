<?php

namespace App\Http\Controllers;

use App\Models\TermCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantTermsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW TERMS & CONDITIONS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | ONLY TENANT USERS CAN ACCESS
        |--------------------------------------------------------------------------
        */

        if (! $user || ! $user->isTenantUser()) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | GET TERMS & CONDITIONS
        |--------------------------------------------------------------------------
        */

        $termsConditions = TermCondition::orderBy('id')->get();

        return view(
            'tenant.terms-conditions',
            compact('termsConditions')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACCEPT TERMS & CONDITIONS
    |--------------------------------------------------------------------------
    */

    public function accept(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | ONLY TENANT USERS CAN ACCEPT
        |--------------------------------------------------------------------------
        */

        if (! $user || ! $user->isTenantUser()) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE ACCEPTANCE
        |--------------------------------------------------------------------------
        */

        $user->terms_accepted_at = now();

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | REDIRECT TO DASHBOARD
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('tenant.dashboard')
            ->with(
                'success',
                'Terms & Conditions accepted successfully.'
            );
    }
}