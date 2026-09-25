<?php

namespace App\Http\Controllers;

use App\Models\TenantPagePin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenantProfileController extends Controller
{
    /**
     * Show tenant profile and tenant page PIN settings.
     */
    public function edit()
    {
        $user = auth()->user();

        $tenant = $user->tenant;

        if (! $tenant) {
            abort(404, 'Tenant not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | TENANT PAGE PIN SETTINGS
        |--------------------------------------------------------------------------
        */

        $pages = collect(config('tenant_pages'))
            ->map(function ($page, $key) use ($user) {

                $pin = TenantPagePin::where(
                    'tenant_id',
                    $user->tenant_id
                )
                    ->where(
                        'user_id',
                        $user->id
                    )
                    ->where(
                        'page_key',
                        $key
                    )
                    ->first();

                return [
                    'key' => $key,
                    'name' => $page['name'],
                    'enabled' => (bool) (
                        $pin?->enabled ?? false
                    ),
                    'has_pin' => ! empty(
                        $pin?->pin_hash
                    ),
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | PROFILE VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'tenant.profile',
            compact(
                'user',
                'tenant',
                'pages'
            )
        );
    }

    /**
     * Update tenant profile.
     */
    public function update(Request $request)
    {
        $tenant = auth()->user()->tenant;

        if (! $tenant) {
            abort(404, 'Tenant not found.');
        }

        $validated = $request->validate([

            'business_name' => [
                'required',
                'string',
                'max:255',
            ],

            'owner_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | LOGO UPLOAD
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            // Delete old logo
            if ($tenant->logo) {
                Storage::disk('public')->delete(
                    $tenant->logo
                );
            }

            // Store new logo
            $validated['logo'] = $request
                ->file('logo')
                ->store(
                    'tenants/logos',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE TENANT
        |--------------------------------------------------------------------------
        */

        $tenant->update($validated);

        return redirect()
            ->route('tenant.profile.edit')
            ->with(
                'success',
                'Business profile updated successfully.'
            );
    }
}
