<?php

namespace App\Http\Controllers;

use App\Models\AdminFooterSetting;
use App\Models\AdminPagePin;
use App\Models\Bank;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Tenant;
use App\Models\TenantPagePin;
use App\Models\TermCondition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminDashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ADMIN / TENANT DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN DASHBOARD
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Super Admin')) {

            $totalTenants = Tenant::count();

            $activeTenants = Tenant::where(
                'status',
                true
            )->count();

            $inactiveTenants = Tenant::where(
                'status',
                false
            )->count();

            $totalUsers = User::count();

            return view(
                'admin.dashboard',
                compact(
                    'totalTenants',
                    'activeTenants',
                    'inactiveTenants',
                    'totalUsers'
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TENANT DASHBOARD
        |--------------------------------------------------------------------------
        */

        $tenant = $user->tenant;

        if (! $tenant) {
            abort(404, 'Tenant not found.');
        }

        $totalCustomers = Customer::where(
            'tenant_id',
            $tenant->id
        )->count();

        return view(
            'tenant.dashboard',
            compact(
                'tenant',
                'totalCustomers'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN PROFILE / SETTINGS
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        $user = Auth::user();

        if (! $user->isSuperAdmin()) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN PAGE PIN SETTINGS
        |--------------------------------------------------------------------------
        */

        $pages = collect(config('admin_pages'))
            ->map(function ($page, $key) use ($user) {

                $pin = AdminPagePin::where(
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
        | ALL TENANTS
        |--------------------------------------------------------------------------
        */

        $tenants = Tenant::all();

        /*
        |--------------------------------------------------------------------------
        | BANKS
        |--------------------------------------------------------------------------
        */

        $banks = Bank::orderBy('id')->get();

        /*
        |--------------------------------------------------------------------------
        | TERMS & CONDITIONS
        |--------------------------------------------------------------------------
        */

        $termsConditions = TermCondition::orderBy(
            'id'
        )->get();

        /*
        |--------------------------------------------------------------------------
        | FOOTER SETTINGS
        |--------------------------------------------------------------------------
        */

        $footer = AdminFooterSetting::first();

        /*
        |--------------------------------------------------------------------------
        | PROFILE VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.profile',
            compact(
                'user',
                'pages',
                'tenants',
                'banks',
                'termsConditions',
                'footer'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN TENANT PAGE PIN MANAGEMENT
    |--------------------------------------------------------------------------
    */

    /**
     * Show page PIN settings for a selected tenant.
     */
    public function tenantPagePins(Tenant $tenant)
    {
        $pages = collect(config('tenant_pages'))
            ->map(function ($page, $key) use ($tenant) {

                $pin = TenantPagePin::where(
                    'tenant_id',
                    $tenant->id
                )
                    ->where(
                        'page_key',
                        $key
                    )
                    ->first();

                return [
                    'key' => $key,

                    'name' => $page['name'],

                    'url' => $page['url'] ?? null,

                    'enabled' => (bool) (
                        $pin?->enabled ?? false
                    ),

                    'has_pin' => ! empty(
                        $pin?->pin_hash
                    ),
                ];
            })
            ->values();

        return view(
            'admin.profile.tenant-page-pins',
            compact(
                'tenant',
                'pages'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ENABLE / DISABLE TENANT PAGE PIN
    |--------------------------------------------------------------------------
    */

    public function updateTenantPagePinStatus(
        Request $request,
        Tenant $tenant
    ) {
        $validated = $request->validate([
            'page_key' => [
                'required',
                'string',
                'in:'.implode(
                    ',',
                    array_keys(
                        config('tenant_pages')
                    )
                ),
            ],

            'enabled' => [
                'required',
                'boolean',
            ],
        ]);

        $pageKey = $validated['page_key'];

        $page = config(
            'tenant_pages.'.$pageKey
        );

        /*
        |--------------------------------------------------------------------------
        | FIND EXISTING PIN
        |--------------------------------------------------------------------------
        */

        $pagePin = TenantPagePin::where(
            'tenant_id',
            $tenant->id
        )
            ->where(
                'page_key',
                $pageKey
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | ENABLE SECURITY ONLY IF PIN EXISTS
        |--------------------------------------------------------------------------
        */

        if (
            $validated['enabled'] &&
            (
                ! $pagePin ||
                empty($pagePin->pin_hash)
            )
        ) {

            $message =
                'Please set a PIN for "'.
                ($page['name'] ?? $pageKey).
                '" before enabling 2-PIN security.';

            if (
                $request->expectsJson() ||
                $request->ajax()
            ) {

                return response()->json([
                    'success' => false,

                    'message' => $message,

                    'errors' => [
                        'page_key' => [
                            $message,
                        ],
                    ],
                ], 422);
            }

            return back()
                ->withErrors([
                    'page_key' => $message,
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE STATUS
        |--------------------------------------------------------------------------
        */

        TenantPagePin::updateOrCreate(
            [
                'tenant_id' => $tenant->id,

                'page_key' => $pageKey,
            ],
            [
                'page_name' => $page['name'] ?? $pageKey,

                'enabled' => $validated['enabled'],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        if (
            $request->expectsJson() ||
            $request->ajax()
        ) {

            return response()->json([
                'success' => true,

                'message' => $validated['enabled']
                        ? '2-PIN security enabled successfully.'
                        : '2-PIN security disabled successfully.',
            ]);
        }

        return back()->with(
            'success',
            '2-PIN security setting updated successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE TENANT PAGE PINS
    |--------------------------------------------------------------------------
    */

    public function updateTenantPagePins(
        Request $request
    ) {
        $validated = $request->validate([
            'tenant_id' => [
                'required',
                'integer',
                'exists:tenants,id',
            ],

            'pins' => [
                'nullable',
                'array',
            ],
        ]);

        $tenantId = $validated['tenant_id'];

        $pages = config('tenant_pages');

        foreach ($pages as $key => $page) {

            $pin = $request->input(
                "pins.$key"
            );

            /*
            |--------------------------------------------------------------------------
            | EMPTY PIN = KEEP EXISTING PIN
            |--------------------------------------------------------------------------
            */

            if (blank($pin)) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | PIN MUST BE 4-6 DIGITS
            |--------------------------------------------------------------------------
            */

            if (
                ! preg_match(
                    '/^\d{4,6}$/',
                    $pin
                )
            ) {

                return back()
                    ->withErrors([
                        "pins.$key" => $page['name'].
                            ' PIN must contain 4 to 6 digits.',
                    ])
                    ->withInput();
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE HASHED PIN
            |--------------------------------------------------------------------------
            */

            TenantPagePin::updateOrCreate(
                [
                    'tenant_id' => $tenantId,

                    'page_key' => $key,
                ],
                [
                    'page_name' => $page['name'],

                    'pin_hash' => Hash::make($pin),
                ]
            );
        }

        if ($request->ajax()) {

            return response()->json([
                'success' => true,

                'message' => 'Tenant page PINs updated successfully.',
            ]);
        }

        return back()->with(
            'success',
            'Tenant page PINs updated successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE SUPER ADMIN PROFILE
    |--------------------------------------------------------------------------
    */

    public function updateProfile(
        Request $request
    ) {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN CHECK
        |--------------------------------------------------------------------------
        */

        if (! $user->isSuperAdmin()) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | PROFILE
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,'.$user->id,
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            /*
            |--------------------------------------------------------------------------
            | PASSWORD
            |--------------------------------------------------------------------------
            */

            'current_password' => [
                'nullable',
                'required_with:password',
                'string',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            /*
            |--------------------------------------------------------------------------
            | BANKS
            |--------------------------------------------------------------------------
            */

            'banks_form' => [
                'nullable',
                'boolean',
            ],

            'banks' => [
                'nullable',
                'array',
            ],

            'banks.*.id' => [
                'nullable',
                'integer',
                'exists:banks,id',
            ],

            'banks.*.name' => [
                'required',
                'string',
                'max:255',
            ],

            'banks.*.opening_balance' => [
                'required',
                'numeric',
                'min:0',
                'max:999999999999.99',
            ],

            /*
            |--------------------------------------------------------------------------
            | TERMS & CONDITIONS
            |--------------------------------------------------------------------------
            */

            'terms_conditions' => [
                'nullable',
                'array',
            ],

            'terms_conditions.*.id' => [
                'nullable',
                'integer',

                Rule::exists(
                    (new TermCondition)->getTable(),
                    'id'
                ),
            ],

            'terms_conditions.*.heading' => [
                'required',
                'string',
                'max:255',
            ],

            'terms_conditions.*.description' => [
                'required',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | FOOTER SETTINGS
            |--------------------------------------------------------------------------
            */

            'footer_enabled' => [
                'nullable',
                'boolean',
            ],

            'footer_text' => [
                'nullable',
                'string',
                'max:255',
            ],

            'footer_brand_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'footer_link_text' => [
                'nullable',
                'string',
                'max:255',
            ],

            'footer_link_url' => [
                'nullable',
                'url',
                'max:500',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | PASSWORD CHECK
        |--------------------------------------------------------------------------
        */

        if ($request->filled('password')) {

            if (
                ! $request->filled(
                    'current_password'
                )
            ) {

                return back()
                    ->withErrors([
                        'current_password' => 'Current password is required when changing password.',
                    ])
                    ->withInput();
            }

            if (
                ! Hash::check(
                    $request->current_password,
                    $user->password
                )
            ) {

                return back()
                    ->withErrors([
                        'current_password' => 'Current password is incorrect.',
                    ])
                    ->withInput();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE EVERYTHING IN ONE TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $request,
            $validated,
            $user
        ) {

            /*
            |--------------------------------------------------------------------------
            | UPDATE PASSWORD
            |--------------------------------------------------------------------------
            */

            if ($request->filled('password')) {

                $user->password = Hash::make(
                    $request->password
                );
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE PROFILE
            |--------------------------------------------------------------------------
            */

            $user->name =
                $validated['name'];

            $user->email =
                $validated['email'];

            $user->phone =
                $validated['phone'] ?? null;

            $user->save();

            /*
            |--------------------------------------------------------------------------
            | SAVE BANKS
            |--------------------------------------------------------------------------
            */

            if (
                $request->boolean(
                    'banks_form'
                )
            ) {

                $submittedBanks =
                    $request->input(
                        'banks',
                        []
                    );

                /*
                |--------------------------------------------------------------------------
                | EXISTING BANK IDS
                |--------------------------------------------------------------------------
                */

                $existingBankIds =
                    Bank::query()
                        ->pluck('id')
                        ->map(
                            fn ($id) => (int) $id
                        )
                        ->toArray();

                /*
                |--------------------------------------------------------------------------
                | TRACK SUBMITTED BANK IDS
                |--------------------------------------------------------------------------
                */

                $submittedBankIds = [];

                /*
                |--------------------------------------------------------------------------
                | CREATE / UPDATE BANKS
                |--------------------------------------------------------------------------
                */

                foreach (
                    $submittedBanks as $bankData
                ) {

                    $bankId =
                        ! empty(
                            $bankData['id']
                        )
                            ? (int)
                                $bankData['id']
                            : null;

                    /*
                    |--------------------------------------------------------------------------
                    | EXISTING BANK
                    |--------------------------------------------------------------------------
                    */

                    if ($bankId) {

                        $bank =
                            Bank::find(
                                $bankId
                            );

                        if (! $bank) {
                            continue;
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | NEW BANK
                    |--------------------------------------------------------------------------
                    */

                    else {

                        $bank =
                            new Bank;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | SAVE BANK
                    |--------------------------------------------------------------------------
                    */

                    $bank->bank_name =
                        trim(
                            $bankData['name']
                        );

                    $bank->opening_balance =
                        (float)
                        $bankData[
                            'opening_balance'
                        ];

                    $bank->save();

                    /*
                    |--------------------------------------------------------------------------
                    | TRACK BANK
                    |--------------------------------------------------------------------------
                    */

                    $submittedBankIds[] =
                        (int) $bank->id;
                }

                /*
                |--------------------------------------------------------------------------
                | DELETE REMOVED BANKS
                |--------------------------------------------------------------------------
                */

                $banksToDelete =
                    array_diff(
                        $existingBankIds,
                        $submittedBankIds
                    );

                if (
                    ! empty(
                        $banksToDelete
                    )
                ) {

                    Bank::whereIn(
                        'id',
                        $banksToDelete
                    )->delete();
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE TERMS & CONDITIONS
            |--------------------------------------------------------------------------
            */

            $submittedTerms =
                $request->input(
                    'terms_conditions',
                    []
                );

            /*
            |--------------------------------------------------------------------------
            | TRACK SAVED TERM IDS
            |--------------------------------------------------------------------------
            */

            $submittedTermIds = [];

            /*
            |--------------------------------------------------------------------------
            | CREATE / UPDATE TERMS
            |--------------------------------------------------------------------------
            */

            foreach (
                $submittedTerms as $termData
            ) {

                $heading =
                    trim(
                        $termData['heading'] ?? ''
                    );

                $description =
                    trim(
                        $termData['description'] ?? ''
                    );

                /*
                |--------------------------------------------------------------------------
                | SKIP EMPTY ROW
                |--------------------------------------------------------------------------
                */

                if (
                    $heading === '' ||
                    $description === ''
                ) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | EXISTING TERM
                |--------------------------------------------------------------------------
                */

                if (
                    ! empty(
                        $termData['id']
                    )
                ) {

                    $term =
                        TermCondition::find(
                            (int)
                            $termData['id']
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | SAFETY CHECK
                    |--------------------------------------------------------------------------
                    */

                    if (! $term) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE TERM
                    |--------------------------------------------------------------------------
                    */

                    $term->update([
                        'heading' => $heading,

                        'description' => $description,
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | NEW TERM
                |--------------------------------------------------------------------------
                */

                else {

                    $term =
                        TermCondition::create([
                            'heading' => $heading,

                            'description' => $description,
                        ]);
                }

                /*
                |--------------------------------------------------------------------------
                | TRACK TERM ID
                |--------------------------------------------------------------------------
                */

                $submittedTermIds[] =
                    (int) $term->id;
            }

            /*
            |--------------------------------------------------------------------------
            | DELETE REMOVED TERMS
            |--------------------------------------------------------------------------
            */

            if (
                ! empty(
                    $submittedTermIds
                )
            ) {

                TermCondition::whereNotIn(
                    'id',
                    $submittedTermIds
                )->delete();

            } else {

                TermCondition::query()->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE FOOTER SETTINGS
            |--------------------------------------------------------------------------
            */

            AdminFooterSetting::updateOrCreate(
                [
                    'id' => 1,
                ],
                [
                    'enabled' => $request->boolean(
                        'footer_enabled'
                    ),

                    'footer_text' => $request->input(
                        'footer_text'
                    ),

                    'brand_name' => $request->input(
                        'footer_brand_name'
                    ),

                    'link_text' => $request->input(
                        'footer_link_text'
                    ),

                    'link_url' => $request->input(
                        'footer_link_url'
                    ),
                ]
            );
        });

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.profile'
            )
            ->with(
                'success',
                'Your profile, bank settings, Terms & Conditions and footer settings have been updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function bookings()
    {
        $bookings = Booking::with([
            'customer',
            'lawnType',
            'tenant',
        ])
            ->latest('booking_date')
            ->latest()
            ->get();

        return view(
            'super_admin.bookings.index',
            compact('bookings')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN PAGE PIN SETTINGS
    |--------------------------------------------------------------------------
    */

    public function pagePins()
    {
        $user = Auth::user();

        $pages = collect(config('admin_pages'))
            ->map(function ($page, $key) use ($user) {

                $pin = AdminPagePin::where(
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

        return view(
            'admin.profile.page-pins',
            compact('pages')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE ADMIN PAGE PINS
    |--------------------------------------------------------------------------
    */

    public function updatePagePins(
        Request $request
    ) {
        $user = Auth::user();

        $pages = config('admin_pages');

        foreach (
            $pages as $key => $page
        ) {

            $pin =
                $request->input(
                    "pins.$key"
                );

            /*
            |--------------------------------------------------------------------------
            | EMPTY PIN = KEEP EXISTING PIN
            |--------------------------------------------------------------------------
            */

            if (blank($pin)) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | PIN MUST BE 4-6 DIGITS
            |--------------------------------------------------------------------------
            */

            if (
                ! preg_match(
                    '/^\d{4,6}$/',
                    $pin
                )
            ) {

                return back()
                    ->withErrors([
                        "pins.$key" => $page['name'].
                            ' PIN must contain 4 to 6 digits.',
                    ])
                    ->withInput();
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE HASHED PIN
            |--------------------------------------------------------------------------
            */

            AdminPagePin::updateOrCreate(
                [
                    'user_id' => $user->id,

                    'page_key' => $key,
                ],
                [
                    'page_name' => $page['name'],

                    'pin_hash' => Hash::make($pin),
                ]
            );
        }

        if ($request->ajax()) {

            return response()->json([
                'success' => true,

                'message' => 'Page PINs updated successfully.',
            ]);
        }

        return redirect()
            ->route(
                'admin.profile.page-pins'
            )
            ->with(
                'success',
                'Page PINs updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | ENABLE / DISABLE ADMIN PAGE PIN
    |--------------------------------------------------------------------------
    */

    public function updatePagePinStatus(
        Request $request
    ) {
        $validated = $request->validate([
            'page_key' => [
                'required',
                'string',
                'in:'.implode(
                    ',',
                    array_keys(
                        config('admin_pages')
                    )
                ),
            ],

            'enabled' => [
                'required',
                'boolean',
            ],
        ]);

        $user = Auth::user();

        $pageKey =
            $validated['page_key'];

        $page =
            config(
                'admin_pages.'.
                $pageKey
            );

        /*
        |--------------------------------------------------------------------------
        | FIND EXISTING PIN
        |--------------------------------------------------------------------------
        */

        $pagePin =
            AdminPagePin::where(
                'user_id',
                $user->id
            )
                ->where(
                    'page_key',
                    $pageKey
                )
                ->first();

        /*
        |--------------------------------------------------------------------------
        | DON'T ALLOW ENABLE WITHOUT PIN
        |--------------------------------------------------------------------------
        */

        if (
            $validated['enabled'] &&
            (
                ! $pagePin ||
                empty(
                    $pagePin->pin_hash
                )
            )
        ) {

            return back()
                ->withErrors([
                    'page_key' => 'Please set a PIN for "'.
                        $page['name'].
                        '" before enabling 2-PIN security.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS
        |--------------------------------------------------------------------------
        */

        AdminPagePin::updateOrCreate(
            [
                'user_id' => $user->id,

                'page_key' => $pageKey,
            ],
            [
                'page_name' => $page['name'],

                'enabled' => $validated['enabled'],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | IF DISABLED, REMOVE OLD VERIFICATION
        |--------------------------------------------------------------------------
        */

        if (
            ! $validated['enabled']
        ) {

            $request->session()->forget(
                'admin_page_pin_verified.'.
                $pageKey
            );
        }

        if ($request->ajax()) {

            return response()->json([
                'success' => true,

                'message' => '2-PIN security setting updated successfully.',
            ]);
        }

        return back()->with(
            'success',
            '2-PIN security setting updated successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFY ADMIN PAGE PIN
    |--------------------------------------------------------------------------
    */

    public function verifyPagePin(
        Request $request
    ) {
        $validated = $request->validate([
            'page_key' => [
                'required',
                'string',
                'in:'.implode(
                    ',',
                    array_keys(
                        config('admin_pages')
                    )
                ),
            ],

            'pin' => [
                'required',
                'string',
                'digits_between:4,6',
            ],
        ]);

        $user = Auth::user();

        $pagePin =
            AdminPagePin::where(
                'user_id',
                $user->id
            )
                ->where(
                    'page_key',
                    $validated['page_key']
                )
                ->where(
                    'enabled',
                    true
                )
                ->first();

        /*
        |--------------------------------------------------------------------------
        | PIN SECURITY NOT ENABLED
        |--------------------------------------------------------------------------
        */

        if (! $pagePin) {

            if (
                $request->expectsJson()
            ) {

                return response()->json([
                    'success' => false,

                    'message' => 'PIN security is not enabled for this page.',
                ], 422);
            }

            return back()
                ->withErrors([
                    'pin' => 'PIN security is not enabled for this page.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | NO PIN CONFIGURED
        |--------------------------------------------------------------------------
        */

        if (
            empty(
                $pagePin->pin_hash
            )
        ) {

            if (
                $request->expectsJson()
            ) {

                return response()->json([
                    'success' => false,

                    'message' => 'No PIN has been configured for this page.',
                ], 422);
            }

            return back()
                ->withErrors([
                    'pin' => 'No PIN has been configured for this page.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | WRONG PIN
        |--------------------------------------------------------------------------
        */

        if (
            ! Hash::check(
                (string)
                $validated['pin'],
                $pagePin->pin_hash
            )
        ) {

            if (
                $request->expectsJson()
            ) {

                return response()->json([
                    'success' => false,

                    'message' => 'Wrong PIN. Please try again.',
                ], 422);
            }

            return back()
                ->withErrors([
                    'pin' => 'Wrong PIN. Please try again.',
                ])
                ->withInput([
                    'page_key' => $validated['page_key'],
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PIN CORRECT
        |--------------------------------------------------------------------------
        */

        $request->session()->put(
            'admin_page_pin_verified.'.
            $validated['page_key'],
            true
        );

        /*
        |--------------------------------------------------------------------------
        | AJAX SUCCESS
        |--------------------------------------------------------------------------
        */

        if (
            $request->expectsJson()
        ) {

            return response()->json([
                'success' => true,

                'message' => 'PIN verified successfully.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | NORMAL REQUEST FALLBACK
        |--------------------------------------------------------------------------
        */

        $url =
            $request->session()->pull(
                'admin_page_pin_url'
            );

        return redirect()->to(
            $url ?: route(
                'admin.dashboard'
            )
        );
    }
}
