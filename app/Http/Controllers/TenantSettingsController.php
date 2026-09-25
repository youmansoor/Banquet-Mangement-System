<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\LawnType;
use App\Models\Service;
use App\Models\TenantBank;
use App\Models\TenantSetting;
use App\Models\TenantFinanceMaster;
use App\Models\TenantTermCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TenantSettingsController extends Controller
{
    /**
     * Show tenant settings page.
     */
    public function index()
    {
        $user = Auth::user();

        if (! $user || ! $user->isTenantUser()) {
            abort(403);
        }

        $tenantId = $user->tenant_id;

        /*
        |--------------------------------------------------------------------------
        | SETTINGS
        |--------------------------------------------------------------------------
        */

        $settings = TenantSetting::firstOrCreate(
            [
                'tenant_id' => $tenantId,
            ],
            [
                'currency' => 'PKR',
                'tax_enabled' => false,
                'tax_percentage' => 0,
                'booking_advance_percentage' => 0,
                'installment_options' => [],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | LAWN TYPES
        |--------------------------------------------------------------------------
        */

        $lawnTypes = LawnType::where(
            'tenant_id',
            $tenantId
        )
            ->orderBy('lawn_type')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SERVICES
        |--------------------------------------------------------------------------
        */

        $services = Service::where(
            'tenant_id',
            $tenantId
        )
            ->orderBy('service_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TENANT BANKS
        |--------------------------------------------------------------------------
        */

        $banks = TenantBank::where(
            'tenant_id',
            $tenantId
        )
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TENANT TERMS & CONDITIONS
        |--------------------------------------------------------------------------
        */

        $tenantTermsConditions = TenantTermCondition::where(
            'tenant_id',
            $tenantId
        )
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | FINANCE MASTER
        |--------------------------------------------------------------------------
        */

        $masters = TenantFinanceMaster::where(
            'tenant_id',
            $tenantId
        )
            ->where(
                'is_active',
                true
            )
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('master_type');

        /*
        |--------------------------------------------------------------------------
        | TOTAL TAX PERCENTAGE
        |--------------------------------------------------------------------------
        */

        $totalTaxPercentage = 0;

        if ($settings->tax_enabled) {

            $taxes = $settings->taxes;

            /*
            |--------------------------------------------------------------------------
            | NEW TAX JSON STRUCTURE
            |--------------------------------------------------------------------------
            */

            if (
                is_array($taxes)
                && ! empty($taxes)
            ) {

                $totalTaxPercentage =
                    collect($taxes)->sum(
                        function ($tax) {

                            return (float) (
                                is_array($tax)
                                    ? ($tax['percentage'] ?? 0)
                                    : 0
                            );
                        }
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | OLD TAX FALLBACK
            |--------------------------------------------------------------------------
            */

            elseif (
                is_numeric(
                    $settings->tax_percentage
                )
            ) {

                $totalTaxPercentage =
                    (float) $settings->tax_percentage;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT SETTINGS
        |--------------------------------------------------------------------------
        */

        $taxEnabled = (bool) (
            $settings->tax_enabled ?? false
        );

        $taxPercent = $totalTaxPercentage;

        $paymentMethods = [];

        $configuredPaymentMethods =
            $settings->payment_methods
            ?? $settings->payment_method
            ?? [];

        if (
            is_array(
                $configuredPaymentMethods
            )
        ) {

            $paymentMethods =
                $configuredPaymentMethods;

        } elseif (
            ! empty(
                $configuredPaymentMethods
            )
        ) {

            $paymentMethods =
                json_decode(
                    (string) $configuredPaymentMethods,
                    true
                ) ?? [];
        }

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'tenant.settings.index',
            compact(
                'settings',
                'lawnTypes',
                'services',
                'banks',
                'masters',
                'tenantTermsConditions',
                'totalTaxPercentage',
                'taxEnabled',
                'taxPercent',
                'paymentMethods'
            )
        );
    }

    /**
     * Update tenant settings.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (! $user || ! $user->isTenantUser()) {
            abort(403);
        }

        $tenantId = $user->tenant_id;

        /*
        |--------------------------------------------------------------------------
        | TERMS & CONDITIONS
        |--------------------------------------------------------------------------
        |
        | terms_form = 1
        |
        */

        if (
            $request->boolean(
                'terms_form'
            )
        ) {

            $validated = $request->validate([

                'terms_conditions' => [
                    'nullable',
                    'array',
                ],

                'terms_conditions.*.id' => [
                    'nullable',
                    'integer',
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
            ]);

            DB::transaction(
                function () use (
                    $validated,
                    $tenantId
                ) {

                    $submittedIds = [];

                    foreach (
                        $validated['terms_conditions']
                        ?? []
                        as $termData
                    ) {

                        $heading = trim(
                            (string) (
                                $termData['heading']
                                ?? ''
                            )
                        );

                        $description = trim(
                            (string) (
                                $termData['description']
                                ?? ''
                            )
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | EMPTY ROW SKIP
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $heading === ''
                            ||
                            $description === ''
                        ) {
                            continue;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | UPDATE EXISTING TERM
                        |--------------------------------------------------------------------------
                        */

                        if (
                            ! empty(
                                $termData['id']
                            )
                        ) {

                            $term = TenantTermCondition::where(
                                'tenant_id',
                                $tenantId
                            )
                                ->findOrFail(
                                    (int) $termData['id']
                                );

                            $term->update([
                                'heading' =>
                                    $heading,

                                'description' =>
                                    $description,
                            ]);

                        }

                        /*
                        |--------------------------------------------------------------------------
                        | CREATE NEW TERM
                        |--------------------------------------------------------------------------
                        */

                        else {

                            $term =
                                TenantTermCondition::create([

                                    'tenant_id' =>
                                        $tenantId,

                                    'heading' =>
                                        $heading,

                                    'description' =>
                                        $description,
                                ]);
                        }

                        $submittedIds[] =
                            $term->id;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | DELETE REMOVED TERMS
                    |--------------------------------------------------------------------------
                    */

                    $query =
                        TenantTermCondition::where(
                            'tenant_id',
                            $tenantId
                        );

                    if (
                        ! empty(
                            $submittedIds
                        )
                    ) {

                        $query
                            ->whereNotIn(
                                'id',
                                $submittedIds
                            )
                            ->delete();

                    } else {

                        $query->delete();
                    }
                }
            );

            return redirect()
                ->route(
                    'tenant.settings'
                )
                ->with(
                    'success',
                    'Terms & Conditions updated successfully.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | FINANCE MASTER
        |--------------------------------------------------------------------------
        |
        | master_form = 1
        |
        | Transaction Types are now HARD-CODED.
        |
        | Payment Categories are stored in database and linked through:
        |
        | parent_key:
        | customer_payment
        | vendor_payment
        | banquet_expense
        |
        */

        if (
            $request->boolean(
                'master_form'
            )
        ) {

            $validated = $request->validate([

                /*
                |--------------------------------------------------------------------------
                | PAYMENT CATEGORIES
                |--------------------------------------------------------------------------
                */

                'payment_categories' => [
                    'nullable',
                    'array',
                ],

                'payment_categories.*.id' => [
                    'nullable',
                    'integer',
                ],

                'payment_categories.*.name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'payment_categories.*.parent_key' => [
                    'required',
                    'string',
                    Rule::in([
                        'customer_payment',
                        'vendor_payment',
                        'banquet_expense',
                    ]),
                ],

                /*
                |--------------------------------------------------------------------------
                | BENEFICIARIES
                |--------------------------------------------------------------------------
                */

                'beneficiaries' => [
                    'nullable',
                    'array',
                ],

                'beneficiaries.*.id' => [
                    'nullable',
                    'integer',
                ],

                'beneficiaries.*.name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                /*
                |--------------------------------------------------------------------------
                | VENDORS
                |--------------------------------------------------------------------------
                |
                | Kept here for compatibility with the existing settings form.
                | If vendors are now managed through the separate vendors table,
                | this section can be removed from the settings form/controller.
                |
                */

                'vendors' => [
                    'nullable',
                    'array',
                ],

                'vendors.*.id' => [
                    'nullable',
                    'integer',
                ],

                'vendors.*.name' => [
                    'required',
                    'string',
                    'max:255',
                ],
            ]);

            DB::transaction(
                function () use (
                    $validated,
                    $tenantId
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | PAYMENT CATEGORIES
                    |--------------------------------------------------------------------------
                    */

                    $this->syncFinanceMasters(
                        $tenantId,
                        'payment_category',
                        $validated[
                            'payment_categories'
                        ] ?? []
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | BENEFICIARIES
                    |--------------------------------------------------------------------------
                    */

                    $this->syncFinanceMasters(
                        $tenantId,
                        'beneficiary',
                        $validated[
                            'beneficiaries'
                        ] ?? []
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | VENDORS
                    |--------------------------------------------------------------------------
                    |
                    | Existing compatibility logic.
                    |
                    */

                    $this->syncFinanceMasters(
                        $tenantId,
                        'vendor',
                        $validated[
                            'vendors'
                        ] ?? []
                    );
                }
            );

            return redirect()
                ->route(
                    'tenant.settings'
                )
                ->with(
                    'success',
                    'Finance Master settings updated successfully.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | NORMAL SETTINGS
        |--------------------------------------------------------------------------
        */

        $taxEnabled =
            $request->boolean(
                'tax_enabled'
            );

        /*
        |--------------------------------------------------------------------------
        | VALIDATION RULES
        |--------------------------------------------------------------------------
        */

        $validationRules = [

            /*
            |--------------------------------------------------------------------------
            | BUSINESS
            |--------------------------------------------------------------------------
            */

            'business_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'business_phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'business_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'business_address' => [
                'nullable',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | TAX
            |--------------------------------------------------------------------------
            */

            'tax_enabled' => [
                'nullable',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | CURRENCY
            |--------------------------------------------------------------------------
            */

            'currency' => [
                'required_unless:banks_form,1',
                'string',
                'max:10',
            ],

            /*
            |--------------------------------------------------------------------------
            | PAYMENT
            |--------------------------------------------------------------------------
            */

            'payment_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'booking_advance_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | INSTALLMENT
            |--------------------------------------------------------------------------
            */

            'installment_options' => [
                'nullable',
                'array',
            ],

            'installment_options.*' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | PAYMENT RULES
            |--------------------------------------------------------------------------
            */

            'payment_rules' => [
                'nullable',
                'array',
            ],

            'payment_rules.*.name' => [
                'required',
                'string',
                'max:100',
            ],

            'payment_rules.*.description' => [
                'nullable',
                'string',
                'max:255',
            ],

            'payment_rules.*.amount_type' => [
                'required',
                'in:fixed,percentage',
            ],

            'payment_rules.*.amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | TENANT BANKS
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
        ];

        /*
        |--------------------------------------------------------------------------
        | TAX VALIDATION
        |--------------------------------------------------------------------------
        */

        if ($taxEnabled) {

            $validationRules['taxes'] = [
                'required',
                'array',
                'min:1',
            ];

            $validationRules['taxes.*.name'] = [
                'required',
                'string',
                'max:100',
            ];

            $validationRules['taxes.*.percentage'] = [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ];

        } else {

            $validationRules['taxes'] = [
                'nullable',
                'array',
            ];

            $validationRules['taxes.*.name'] = [
                'nullable',
                'string',
                'max:100',
            ];

            $validationRules['taxes.*.percentage'] = [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATE
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate(
                $validationRules
            );

        /*
        |--------------------------------------------------------------------------
        | TAX ENABLED
        |--------------------------------------------------------------------------
        */

        $validated['tax_enabled'] =
            $taxEnabled;

        /*
        |--------------------------------------------------------------------------
        | TAX DISABLED
        |--------------------------------------------------------------------------
        */

        if (
            ! $validated['tax_enabled']
        ) {

            $validated['taxes'] = [];

            $validated['tax_percentage'] = 0;

            $validated['tax_name'] = null;

        } else {

            $taxes =
                $validated['taxes']
                ?? [];

            /*
            |--------------------------------------------------------------------------
            | CLEAN TAXES
            |--------------------------------------------------------------------------
            */

            $taxes =
                array_values(
                    array_filter(
                        $taxes,
                        function ($tax) {

                            return
                                ! empty(
                                    $tax['name']
                                )
                                && isset(
                                    $tax['percentage']
                                );
                        }
                    )
                );

            /*
            |--------------------------------------------------------------------------
            | TOTAL TAX
            |--------------------------------------------------------------------------
            */

            $totalTaxPercentage =
                collect($taxes)->sum(
                    function ($tax) {

                        return (float) (
                            $tax['percentage']
                            ?? 0
                        );
                    }
                );

            $validated['taxes'] =
                $taxes;

            $validated['tax_percentage'] =
                $totalTaxPercentage;

            $validated['tax_name'] =
                ! empty($taxes)
                    ? $taxes[0]['name']
                    : null;
        }

        /*
        |--------------------------------------------------------------------------
        | INSTALLMENT OPTIONS
        |--------------------------------------------------------------------------
        */

        $installmentOptions =
            $validated[
                'installment_options'
            ] ?? [];

        if (
            ! is_array(
                $installmentOptions
            )
        ) {

            $installmentOptions = [];
        }

        $installmentOptions =
            array_map(
                function ($option) {

                    return trim(
                        (string) $option
                    );
                },
                $installmentOptions
            );

        $installmentOptions =
            array_values(
                array_filter(
                    $installmentOptions,
                    function ($option) {

                        return $option !== '';
                    }
                )
            );

        $installmentOptions =
            array_values(
                array_unique(
                    $installmentOptions
                )
            );

        $validated[
            'installment_options'
        ] = $installmentOptions;

        /*
        |--------------------------------------------------------------------------
        | PAYMENT RULES
        |--------------------------------------------------------------------------
        */

        $paymentRules = [];

        foreach (
            $validated['payment_rules']
            ?? [] as $rule
        ) {

            $paymentRules[] = [

                'name' =>
                    trim(
                        (string) (
                            $rule['name']
                            ?? ''
                        )
                    ),

                'description' =>
                    $rule['description']
                    ?? null,

                'amount_type' =>
                    $rule['amount_type'],

                'amount' =>
                    (float) $rule['amount'],
            ];
        }

        $validated['payment_rules'] =
            $paymentRules;

        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $validated,
                $tenantId,
                $request
            ) {

                /*
                |--------------------------------------------------------------------------
                | TENANT SETTINGS
                |--------------------------------------------------------------------------
                */

                $settingsData =
                    $validated;

                unset(
                    $settingsData[
                        'banks_form'
                    ],
                    $settingsData[
                        'banks'
                    ]
                );

                TenantSetting::updateOrCreate(
                    [
                        'tenant_id' =>
                            $tenantId,
                    ],
                    $settingsData
                );

                /*
                |--------------------------------------------------------------------------
                | TENANT BANKS
                |--------------------------------------------------------------------------
                */

                if (
                    $request->boolean(
                        'banks_form'
                    )
                ) {

                    $submittedBanks =
                        $validated['banks']
                        ?? [];

                    $submittedBankIds = [];

                    foreach (
                        $submittedBanks
                        as $bankData
                    ) {

                        $bankId =
                            ! empty(
                                $bankData['id']
                            )
                                ? (int) $bankData['id']
                                : null;

                        $bankName =
                            trim(
                                (string) (
                                    $bankData['name']
                                    ?? ''
                                )
                            );

                        $openingBalance =
                            (float) (
                                $bankData[
                                    'opening_balance'
                                ] ?? 0
                            );

                        /*
                        |--------------------------------------------------------------------------
                        | EXISTING BANK
                        |--------------------------------------------------------------------------
                        */

                        if ($bankId) {

                            $bank =
                                TenantBank::where(
                                    'tenant_id',
                                    $tenantId
                                )
                                ->findOrFail(
                                    $bankId
                                );

                            $bank->update([

                                'bank_name' =>
                                    $bankName,

                                'opening_balance' =>
                                    $openingBalance,
                            ]);

                            $submittedBankIds[] =
                                $bank->id;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | NEW BANK
                        |--------------------------------------------------------------------------
                        */

                        else {

                            $bank =
                                TenantBank::create([

                                    'tenant_id' =>
                                        $tenantId,

                                    'bank_name' =>
                                        $bankName,

                                    'opening_balance' =>
                                        $openingBalance,
                                ]);

                            $submittedBankIds[] =
                                $bank->id;
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | DELETE REMOVED BANKS
                    |--------------------------------------------------------------------------
                    */

                    $tenantBanksQuery =
                        TenantBank::where(
                            'tenant_id',
                            $tenantId
                        );

                    if (
                        ! empty(
                            $submittedBankIds
                        )
                    ) {

                        $tenantBanksQuery
                            ->whereNotIn(
                                'id',
                                $submittedBankIds
                            )
                            ->delete();

                    } else {

                        $tenantBanksQuery->delete();
                    }
                }
            }
        );

        return redirect()
            ->route(
                'tenant.settings'
            )
            ->with(
                'success',
                'Settings updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | FINANCE MASTER SYNC
    |--------------------------------------------------------------------------
    */

    private function syncFinanceMasters(
    int $tenantId,
    string $masterType,
    array $rows
): void {
    $submittedIds = [];

    foreach ($rows as $index => $row) {

        $name = trim((string) ($row['name'] ?? ''));

        if ($name === '') {
            continue;
        }

        $masterId = !empty($row['id'])
            ? (int) $row['id']
            : null;


        /*
        |--------------------------------------------------------------------------
        | PAYMENT CATEGORY
        |--------------------------------------------------------------------------
        */

        $parentKey = null;

        if ($masterType === 'payment_category') {

            $parentKey = trim(
                (string) ($row['parent_key'] ?? '')
            );


            /*
            |--------------------------------------------------------------------------
            | TRANSACTION TYPE REQUIRED
            |--------------------------------------------------------------------------
            */

            if ($parentKey === '') {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    "payment_categories.$index.parent_key" =>
                        'Please select a Transaction Type.',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | ALLOWED TRANSACTION TYPES
            |--------------------------------------------------------------------------
            */

            if (!in_array($parentKey, [
                'customer_payment',
                'vendor_payment',
                'banquet_expense',
            ], true)) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    "payment_categories.$index.parent_key" =>
                        'Invalid Transaction Type selected.',
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE EXISTING RECORD
        |--------------------------------------------------------------------------
        */

        if ($masterId) {

            $master = TenantFinanceMaster::where('tenant_id', $tenantId)
                ->where('master_type', $masterType)
                ->findOrFail($masterId);


            $master->update([

                /*
                | Old database relationship is no longer used.
                */
                'parent_id' => null,

                /*
                | IMPORTANT:
                | Banquet Expense = banquet_expense
                */
                'parent_key' => $parentKey,

                'name' => $name,

                'sort_order' => $index,

                'is_active' => true,

            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | CREATE NEW RECORD
        |--------------------------------------------------------------------------
        */

        else {

            $master = TenantFinanceMaster::create([

                'tenant_id' => $tenantId,

                'master_type' => $masterType,

                /*
                | Old parent relationship.
                */
                'parent_id' => null,

                /*
                | IMPORTANT:
                | This stores the selected Transaction Type.
                */
                'parent_key' => $parentKey,

                'name' => $name,

                'sort_order' => $index,

                'is_active' => true,

            ]);
        }


        $submittedIds[] = $master->id;
    }


    /*
    |--------------------------------------------------------------------------
    | DEACTIVATE REMOVED RECORDS
    |--------------------------------------------------------------------------
    */

    $query = TenantFinanceMaster::where('tenant_id', $tenantId)
        ->where('master_type', $masterType);


    if (!empty($submittedIds)) {

        $query->whereNotIn('id', $submittedIds);
    }


    $query->update([
        'is_active' => false,
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | PAYMENT HISTORY
    |--------------------------------------------------------------------------
    */

    public function paymentHistory($id)
    {
        $tenantId =
            Auth::user()->tenant_id;

        $booking =
            Booking::with([
                'customer',
                'payments',
                'lawnType',
                'bookingServices.service',
            ])
            ->where(
                'tenant_id',
                $tenantId
            )
            ->findOrFail(
                $id
            );

        $settings =
            TenantSetting::where(
                'tenant_id',
                $tenantId
            )->first();

        $installmentOptions =
            $settings?->installment_options
            ?? [];

        if (
            ! is_array(
                $installmentOptions
            )
        ) {

            $installmentOptions =
                json_decode(
                    (string) $installmentOptions,
                    true
                ) ?? [];
        }

        $installmentOptions =
            array_values(
                array_unique(
                    array_filter(
                        array_map(
                            fn ($option) =>
                                trim(
                                    (string) $option
                                ),
                            $installmentOptions
                        ),
                        fn ($option) =>
                            $option !== ''
                    )
                )
            );

        return view(
            'bookings.payment_history',
            compact(
                'booking',
                'settings',
                'installmentOptions'
            )
        );
    }
}