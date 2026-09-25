<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\TenantPagePin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TenantPagePinController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TENANT USER PAGE PIN MANAGEMENT
    |--------------------------------------------------------------------------
    */

    /**
     * Show tenant user's own page PIN settings.
     */
    public function index()
    {
        $user = Auth::user();

        abort_unless(
            $user && $user->tenant_id,
            403
        );

        $pages = collect(
            config('tenant_pages', [])
        )
            ->filter(
                fn ($page) => is_array($page)
            )
            ->map(function ($page, $key) use ($user) {

                $pin = TenantPagePin::where(
                    'tenant_id',
                    $user->tenant_id
                )
                    ->where(
                        'page_key',
                        $key
                    )
                    ->first();

                return [
                    'key' => $key,

                    'name' => $page['name'] ?? $key,

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
            'tenant.profile.page-pins',
            compact('pages')
        );
    }


    /**
     * Enable / disable 2-PIN security
     * for tenant user's own page.
     */
    public function updateStatus(Request $request)
    {
        $availablePages = array_keys(
            config('tenant_pages', [])
        );

        $validated = $request->validate([
            'page_key' => [
                'required',
                'string',
                'in:' . implode(',', $availablePages),
            ],

            'enabled' => [
                'required',
                'boolean',
            ],
        ]);

        $user = Auth::user();

        abort_unless(
            $user && $user->tenant_id,
            403
        );

        $page = config(
            'tenant_pages.' . $validated['page_key']
        );

        abort_unless(
            is_array($page),
            404,
            'Tenant page configuration not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | PIN REQUIRED BEFORE ENABLE
        |--------------------------------------------------------------------------
        */

        if (
            (bool) $validated['enabled'] === true
        ) {

            $existingPin = TenantPagePin::where(
                'tenant_id',
                $user->tenant_id
            )
                ->where(
                    'page_key',
                    $validated['page_key']
                )
                ->first();

            if (
                ! $existingPin ||
                empty($existingPin->pin_hash)
            ) {

                $message =
                    'Please set a PIN for "' .
                    ($page['name'] ?? $validated['page_key']) .
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
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SAVE STATUS
        |--------------------------------------------------------------------------
        */

        TenantPagePin::updateOrCreate(
            [
                'tenant_id' => $user->tenant_id,

                'page_key' => $validated['page_key'],
            ],
            [
                'page_name' =>
                    $page['name'] ??
                    $validated['page_key'],

                'enabled' =>
                    (bool) $validated['enabled'],
            ]
        );


        $message =
            $validated['enabled']
                ? '2-PIN security enabled successfully.'
                : '2-PIN security disabled successfully.';


        if (
            $request->expectsJson() ||
            $request->ajax()
        ) {

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }


        return back()->with(
            'success',
            $message
        );
    }


    /**
     * Update PINs for tenant user's own pages.
     */
    public function updatePins(Request $request)
    {
        $user = Auth::user();

        abort_unless(
            $user && $user->tenant_id,
            403
        );

        $pages = config(
            'tenant_pages',
            []
        );


        foreach ($pages as $key => $page) {

            if (! is_array($page)) {
                continue;
            }


            $pin = $request->input(
                "pins.$key"
            );


            /*
            |--------------------------------------------------------------------------
            | EMPTY PIN = KEEP EXISTING
            |--------------------------------------------------------------------------
            */

            if (
                $pin === null ||
                $pin === ''
            ) {
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
                    (string) $pin
                )
            ) {
                continue;
            }


            TenantPagePin::updateOrCreate(
                [
                    'tenant_id' =>
                        $user->tenant_id,

                    'page_key' =>
                        $key,
                ],
                [
                    'page_name' =>
                        $page['name'] ?? $key,

                    'pin_hash' =>
                        Hash::make($pin),
                ]
            );
        }


        return back()->with(
            'success',
            'Tenant page PINs updated successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFY TENANT PAGE PIN
    |--------------------------------------------------------------------------
    */

    /**
     * Verify tenant page PIN.
     *
     * Supports:
     *
     * 1. Original URL saved by middleware
     * 2. Optional redirect_url sent by AJAX
     * 3. Page URL from tenant_pages.php
     *
     * This prevents successful PIN verification
     * from blindly redirecting to the dashboard.
     */
    public function verify(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | AVAILABLE PAGES
        |--------------------------------------------------------------------------
        */

        $availablePages = array_keys(
            config('tenant_pages', [])
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'page_key' => [
                'required',
                'string',
                'in:' . implode(',', $availablePages),
            ],

            'pin' => [
                'required',
                'string',
                'digits_between:4,6',
            ],

            'redirect_url' => [
                'nullable',
                'string',
                'max:2048',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | AUTH USER
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        if (
            ! $user ||
            ! $user->tenant_id
        ) {

            return response()->json([
                'success' => false,
                'message' =>
                    'You are not associated with any business.',
            ], 403);
        }


        /*
        |--------------------------------------------------------------------------
        | PAGE KEY
        |--------------------------------------------------------------------------
        */

        $pageKey =
            $validated['page_key'];


        /*
        |--------------------------------------------------------------------------
        | FIND ENABLED PAGE PIN
        |--------------------------------------------------------------------------
        */

        $pagePin = TenantPagePin::where(
            'tenant_id',
            $user->tenant_id
        )
            ->where(
                'page_key',
                $pageKey
            )
            ->where(
                'enabled',
                true
            )
            ->first();


        /*
        |--------------------------------------------------------------------------
        | PIN NOT CONFIGURED
        |--------------------------------------------------------------------------
        */

        if (
            ! $pagePin ||
            empty($pagePin->pin_hash)
        ) {

            return response()->json([
                'success' => false,
                'message' =>
                    '2-PIN security is not configured for this page.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK PIN
        |--------------------------------------------------------------------------
        */

        if (
            ! Hash::check(
                $validated['pin'],
                $pagePin->pin_hash
            )
        ) {

            return response()->json([
                'success' => false,

                'message' =>
                    'Wrong PIN. Please try again.',

                'errors' => [
                    'pin' => [
                        'Wrong PIN. Please try again.',
                    ],
                ],
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | PIN VERIFIED
        |--------------------------------------------------------------------------
        */

        $request->session()->put(
            'tenant_page_pin_verified.' . $pageKey,
            true
        );


        /*
        |--------------------------------------------------------------------------
        | ORIGINAL URL FROM MIDDLEWARE
        |--------------------------------------------------------------------------
        */

        $sessionRedirect =
            $request->session()->pull(
                'tenant_page_pin_url'
            );


        /*
        |--------------------------------------------------------------------------
        | ORIGINAL PAGE KEY FROM MIDDLEWARE
        |--------------------------------------------------------------------------
        */

        $sessionPageKey =
            $request->session()->pull(
                'tenant_page_pin_page'
            );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT URL SENT BY FRONTEND
        |--------------------------------------------------------------------------
        */

        $redirectUrl =
            $validated['redirect_url'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | PRIORITY 1
        |--------------------------------------------------------------------------
        |
        | Use frontend target URL when it belongs to this
        | same application host.
        |
        */

        if (
            ! empty($redirectUrl) &&
            $this->isSafeLocalUrl($redirectUrl)
        ) {

            return response()->json([
                'success' => true,

                'message' =>
                    'PIN verified successfully.',

                'data' => [
                    'redirect' => $redirectUrl,
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | PRIORITY 2
        |--------------------------------------------------------------------------
        |
        | Use URL saved by TenantPagePinMiddleware.
        |
        */

        if (
            ! empty($sessionRedirect) &&
            $this->isSafeLocalUrl($sessionRedirect)
        ) {

            return response()->json([
                'success' => true,

                'message' =>
                    'PIN verified successfully.',

                'data' => [
                    'redirect' => $sessionRedirect,
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | PAGE CONFIGURATION
        |--------------------------------------------------------------------------
        */

        $pageConfig = config(
            'tenant_pages.' . $pageKey
        );


        /*
        |--------------------------------------------------------------------------
        | PRIORITY 3
        |--------------------------------------------------------------------------
        |
        | Use URL from tenant_pages.php
        |
        */

        if (
            is_array($pageConfig) &&
            ! empty($pageConfig['url'])
        ) {

            $configuredUrl =
                (string) $pageConfig['url'];


            /*
            |--------------------------------------------------------------
            | Accept only local application URL/path
            |--------------------------------------------------------------
            */

            if (
                str_starts_with(
                    $configuredUrl,
                    '/'
                ) &&
                ! str_starts_with(
                    $configuredUrl,
                    '//'
                )
            ) {

                $configuredRedirect =
                    url($configuredUrl);


                return response()->json([
                    'success' => true,

                    'message' =>
                        'PIN verified successfully.',

                    'data' => [
                        'redirect' =>
                            $configuredRedirect,
                    ],
                ]);
            }


            if (
                $this->isSafeLocalUrl(
                    $configuredUrl
                )
            ) {

                return response()->json([
                    'success' => true,

                    'message' =>
                        'PIN verified successfully.',

                    'data' => [
                        'redirect' =>
                            $configuredUrl,
                    ],
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | PAGE-SPECIFIC ROUTE FALLBACKS
        |--------------------------------------------------------------------------
        |
        | These make sure important tenant pages do not fall back
        | to dashboard merely because a session URL was unavailable.
        |
        */

        $routeFallbacks = [

            'bookings' =>
                'bookings.calendar',

            'customers' =>
                'customers.index',

            'lawn_types' =>
                'lawn_types.index',

            'services' =>
                'services.index',

            'quotations' =>
                'quotations.index',

            'invoices' =>
                'invoices.index',

            'payments' =>
                'payments.index',

            'profile' =>
                'tenant.profile.edit',

            'settings' =>
                'tenant.settings',

            'roles' =>
                'tenant.roles.index',

            'staff' =>
                'tenant.staff.index',
        ];


        /*
        |--------------------------------------------------------------------------
        | USE PAGE-SPECIFIC FALLBACK
        |--------------------------------------------------------------------------
        */

        $fallbackRoute =
            $routeFallbacks[$pageKey] ??
            null;


        if (
            $fallbackRoute &&
            \Illuminate\Support\Facades\Route::has(
                $fallbackRoute
            )
        ) {

            return response()->json([
                'success' => true,

                'message' =>
                    'PIN verified successfully.',

                'data' => [
                    'redirect' =>
                        route($fallbackRoute),
                ],
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | LAST RESORT
        |--------------------------------------------------------------------------
        |
        | Do NOT silently redirect to dashboard.
        |
        */

        return response()->json([
            'success' => false,

            'message' =>
                'PIN verified, but the destination page could not be determined.',

            'errors' => [
                'redirect' => [
                    'Unable to determine the requested page.',
                ],
            ],
        ], 422);
    }


    /*
    |--------------------------------------------------------------------------
    | SAFE LOCAL URL CHECK
    |--------------------------------------------------------------------------
    */

    /**
     * Check whether a URL belongs to this application.
     *
     * Prevents external redirects.
     */
    private function isSafeLocalUrl(
        ?string $url
    ): bool {

        if (
            ! $url ||
            ! filter_var(
                $url,
                FILTER_VALIDATE_URL
            )
        ) {
            return false;
        }


        $target = parse_url($url);

        $application = parse_url(
            config('app.url')
        );


        /*
        |--------------------------------------------------------------------------
        | HOST CHECK
        |--------------------------------------------------------------------------
        */

        if (
            isset($target['host']) &&
            isset($application['host']) &&
            strtolower(
                $target['host']
            ) !== strtolower(
                $application['host']
            )
        ) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | PORT CHECK
        |--------------------------------------------------------------------------
        */

        if (
            isset($target['port']) &&
            isset($application['port']) &&
            (int) $target['port'] !==
            (int) $application['port']
        ) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | SCHEME CHECK
        |--------------------------------------------------------------------------
        */

        if (
            isset($target['scheme']) &&
            isset($application['scheme']) &&
            strtolower(
                $target['scheme']
            ) !== strtolower(
                $application['scheme']
            )
        ) {

            return false;
        }


        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN - TENANT PAGE PIN MANAGEMENT
    |--------------------------------------------------------------------------
    */

    /**
     * Show page PIN settings for selected tenant.
     */
    public function adminIndex(Tenant $tenant)
    {
        $pages = collect(
            config('tenant_pages', [])
        )
            ->filter(
                fn ($page) => is_array($page)
            )
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

                    'name' =>
                        $page['name'] ?? $key,

                    'url' =>
                        $page['url'] ?? null,

                    'enabled' =>
                        (bool) (
                            $pin?->enabled ?? false
                        ),

                    'has_pin' =>
                        ! empty(
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


    /**
     * Super Admin:
     * Enable / disable page 2-PIN.
     */
    public function adminUpdateStatus(
        Request $request
    ) {

        $availablePages = array_keys(
            config('tenant_pages', [])
        );


        $validated = $request->validate([
            'tenant_id' => [
                'required',
                'integer',
                'exists:tenants,id',
            ],

            'page_key' => [
                'required',
                'string',
                'in:' . implode(',', $availablePages),
            ],

            'enabled' => [
                'required',
                'boolean',
            ],
        ]);


        $pageKey =
            $validated['page_key'];


        $page = config(
            'tenant_pages.' . $pageKey
        );


        if (! is_array($page)) {

            return back()
                ->withErrors([
                    'page_key' =>
                        "Page configuration for [{$pageKey}] was not found in config/tenant_pages.php.",
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | PIN REQUIRED BEFORE ENABLE
        |--------------------------------------------------------------------------
        */

        if (
            (bool) $validated['enabled'] === true
        ) {

            $existingPin =
                TenantPagePin::where(
                    'tenant_id',
                    $validated['tenant_id']
                )
                    ->where(
                        'page_key',
                        $pageKey
                    )
                    ->first();


            if (
                ! $existingPin ||
                empty($existingPin->pin_hash)
            ) {

                return back()
                    ->withErrors([
                        'page_key' =>
                            'Please set a PIN for "' .
                            ($page['name'] ?? $pageKey) .
                            '" before enabling 2-PIN security.',
                    ])
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SAVE STATUS
        |--------------------------------------------------------------------------
        */

        TenantPagePin::updateOrCreate(
            [
                'tenant_id' =>
                    $validated['tenant_id'],

                'page_key' =>
                    $pageKey,
            ],
            [
                'page_name' =>
                    $page['name'] ?? $pageKey,

                'enabled' =>
                    (bool) $validated['enabled'],
            ]
        );


        return back()->with(
            'success',
            '2-PIN Tenant Security setting updated successfully.'
        );
    }


    /**
     * Super Admin:
     * Update PINs for selected tenant pages.
     */
    public function adminUpdatePins(
        Request $request
    ) {

        $validated = $request->validate([
            'tenant_id' => [
                'required',
                'integer',
                'exists:tenants,id',
            ],
        ]);


        $tenantId =
            $validated['tenant_id'];


        $pages =
            config('tenant_pages', []);


        foreach (
            $pages as $key => $page
        ) {

            if (! is_array($page)) {
                continue;
            }


            $pin =
                $request->input(
                    "pins.$key"
                );


            /*
            |--------------------------------------------------------------------------
            | EMPTY PIN = KEEP EXISTING
            |--------------------------------------------------------------------------
            */

            if (
                $pin === null ||
                $pin === ''
            ) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | ONLY 4-6 DIGITS
            |--------------------------------------------------------------------------
            */

            if (
                ! preg_match(
                    '/^\d{4,6}$/',
                    (string) $pin
                )
            ) {
                continue;
            }


            TenantPagePin::updateOrCreate(
                [
                    'tenant_id' =>
                        $tenantId,

                    'page_key' =>
                        $key,
                ],
                [
                    'page_name' =>
                        $page['name'] ?? $key,

                    'pin_hash' =>
                        Hash::make($pin),
                ]
            );
        }


        return back()->with(
            'success',
            'Tenant page PINs updated successfully.'
        );
    }
}