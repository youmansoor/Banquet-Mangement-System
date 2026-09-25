<?php

namespace App\Http\Controllers;

use App\Models\TenantTermCondition;
use Illuminate\Support\Facades\Auth;

class TenantViewTermConditionsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | VIEW ACCEPTED TERMS & CONDITIONS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'User is not authenticated.');
        }

        $acceptedAt = $user->terms_accepted_at;

        /*
        |--------------------------------------------------------------------------
        | GET TERMS ACCEPTED BY TENANT
        |--------------------------------------------------------------------------
        |
        | Sirf woh terms show hongi jo tenant ki acceptance ke waqt
        | already available thi.
        |
        */

        if ($acceptedAt) {
            $termsConditions = TenantTermCondition::where(
                'updated_at',
                '<=',
                $acceptedAt
            )
                ->orderBy('id')
                ->get();
        } else {
            $termsConditions = collect();
        }

        return view('tenant.accepted-terms-conditions.index', [
            'termsConditions' => $termsConditions,
            'acceptedAt' => $acceptedAt,
        ]);
    }
}