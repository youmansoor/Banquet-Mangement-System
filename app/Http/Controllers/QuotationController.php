<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\FreeService;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\LawnType;
use App\Models\Quotation;
use App\Models\Service;
use App\Models\TenantSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class QuotationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Quotation::with([
            'customer',
            'lawnType',
            'tenant',
            'invoice',
        ]);

        if ($user->role !== 'super_admin') {
            $query->where(
                'tenant_id',
                $user->tenant_id
            );
        }

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'quotation_number',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'event_type',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'booking_time',
                        'like',
                        "%{$search}%"
                    );

                $q->orWhereHas(
                    'customer',
                    function ($customer) use ($search) {

                        $customer->where(function ($c) use ($search) {

                            $c->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                                ->orWhere(
                                    'phone_1',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'phone_2',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    "%{$search}%"
                                );

                        });
                    }
                );

                $q->orWhereHas(
                    'lawnType',
                    function ($lawn) use ($search) {

                        $lawn->where(
                            'lawn_type',
                            'like',
                            "%{$search}%"
                        );
                    }
                );

                if ($user->role === 'super_admin') {

                    $q->orWhereHas(
                        'tenant',
                        function ($tenant) use ($search) {

                            $tenant->where(
                                'business_name',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
                }
            });
        }

        if ($request->filled('event_date')) {

            $query->whereDate(
                'event_date',
                $request->event_date
            );
        }

        if ($request->filled('valid_until')) {

            $query->whereDate(
                'valid_until',
                $request->valid_until
            );
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('event_type')) {

            $query->where(
                'event_type',
                $request->event_type
            );
        }

        $eventTypesQuery = Quotation::query()
            ->whereNotNull('event_type')
            ->where('event_type', '!=', '');

        if ($user->role !== 'super_admin') {

            $eventTypesQuery->where(
                'tenant_id',
                $user->tenant_id
            );
        }

        $eventTypes = $eventTypesQuery
            ->distinct()
            ->orderBy('event_type')
            ->pluck('event_type');

        $quotations = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'quotations.index',
            compact(
                'quotations',
                'eventTypes'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $user = auth()->user();

        if (! $user->tenant_id) {
            abort(
                403,
                'Tenant is not assigned to this user.'
            );
        }

        $tenantId = $user->tenant_id;

        $customers = Customer::where(
            'tenant_id',
            $tenantId
        )
            ->orderBy('name')
            ->get();

        $lawnTypes = LawnType::where(
            'tenant_id',
            $tenantId
        )
            ->orderBy('lawn_type')
            ->get();

        $services = Service::where(
            'tenant_id',
            $tenantId
        )
            ->orderBy('service_name')
            ->get();

        $freeServices = FreeService::where(
            'tenant_id',
            $tenantId
        )
            ->where('status', true)
            ->orderBy('service_name')
            ->get();

        $settings = TenantSetting::where(
            'tenant_id',
            $tenantId
        )->first();

        return view(
            'quotations.create',
            compact(
                'customers',
                'lawnTypes',
                'services',
                'freeServices',
                'settings'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = auth()->user();

        if (! $user->tenant_id) {
            abort(
                403,
                'Tenant is not assigned to this user.'
            );
        }

        $tenantId = $user->tenant_id;

        $validated = $request->validate([

            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'lawn_type_id' => [
                'required',
                Rule::exists(
                    'lawn_types',
                    'id'
                )->where(
                    fn ($query) => $query->where(
                        'tenant_id',
                        $tenantId
                    )
                ),
            ],

            'event_type' => [
                'required',
                'string',
                'max:255',
            ],

            'event_date' => [
                'required',
                'date',
            ],

            'booking_time' => [
                'required',
                Rule::in([
                    'day',
                    'night',
                ]),
            ],

            'number_of_guests' => [
                'required',
                'integer',
                'min:1',
            ],

            'package_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'valid_until' => [
                'nullable',
                'date',
            ],

            'menu_details' => [
                'nullable',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | PAID SERVICES
            |--------------------------------------------------------------------------
            */

            'service_ids' => [
                'nullable',
                'array',
            ],

            'service_ids.*' => [
                'nullable',
                'integer',
                'distinct',
                Rule::exists(
                    'services',
                    'id'
                )->where(
                    fn ($query) => $query->where(
                        'tenant_id',
                        $tenantId
                    )
                ),
            ],

            'service_quantities' => [
                'nullable',
                'array',
            ],

            'service_quantities.*' => [
                'nullable',
                'numeric',
                'min:1',
            ],

            /*
            |--------------------------------------------------------------------------
            | FREE SERVICES
            |--------------------------------------------------------------------------
            */

            'free_service_ids' => [
                'nullable',
                'array',
            ],

            'free_service_ids.*' => [
                'nullable',
                'integer',
                'distinct',
                Rule::exists(
                    'free_services',
                    'id'
                )->where(
                    fn ($query) => $query
                        ->where(
                            'tenant_id',
                            $tenantId
                        )
                        ->where(
                            'status',
                            true
                        )
                ),
            ],

            'free_service_quantities' => [
                'nullable',
                'array',
            ],

            'free_service_quantities.*' => [
                'nullable',
                'numeric',
                'min:1',
            ],

            /*
            |--------------------------------------------------------------------------
            | PRICING
            |--------------------------------------------------------------------------
            */

            'banquet_booking_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'bookingamount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'draft',
                    'sent',
                    'accepted',
                    'rejected',
                    'expired',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER
        |--------------------------------------------------------------------------
        */

        $customer = Customer::firstOrCreate(
            [
                'tenant_id' => $tenantId,
                'name' => trim(
                    $validated['customer_name']
                ),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | PAID SERVICES
        |--------------------------------------------------------------------------
        */

        $servicesTotal = 0;

        $servicesData = [];

        $serviceIds =
            $validated['service_ids'] ?? [];

        $serviceQuantities =
            $validated['service_quantities'] ?? [];

        foreach (
            $serviceIds as $index => $serviceId
        ) {

            if (empty($serviceId)) {
                continue;
            }

            $service = Service::where(
                'tenant_id',
                $tenantId
            )
                ->where(
                    'id',
                    $serviceId
                )
                ->first();

            if (! $service) {
                continue;
            }

            $quantity = (float) (
                $serviceQuantities[$index] ?? 1
            );

            if ($quantity < 1) {
                $quantity = 1;
            }

            $unitPrice =
                (float) $service->amount;

            $lineTotal =
                $unitPrice * $quantity;

            $servicesTotal += $lineTotal;

            $servicesData[] = [

                'service_type' => 'paid',

                'id' => $service->id,

                'name' => $service->service_name,

                'amount' => $unitPrice,

                'quantity' => $quantity,

                'total' => round(
                    $lineTotal,
                    2
                ),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | FREE SERVICES
        |--------------------------------------------------------------------------
        */

        $freeServiceIds =
            $validated['free_service_ids'] ?? [];

        $freeServiceQuantities =
            $validated['free_service_quantities'] ?? [];

        foreach (
            $freeServiceIds as $index => $freeServiceId
        ) {

            if (empty($freeServiceId)) {
                continue;
            }

            $freeService =
                FreeService::where(
                    'tenant_id',
                    $tenantId
                )
                    ->where(
                        'status',
                        true
                    )
                    ->where(
                        'id',
                        $freeServiceId
                    )
                    ->first();

            if (! $freeService) {
                continue;
            }

            $quantity = (float) (
                $freeServiceQuantities[$index] ?? 1
            );

            if ($quantity < 1) {
                $quantity = 1;
            }

            $servicesData[] = [

                'service_type' => 'free',

                'id' => $freeService->id,

                'name' => $freeService->service_name,

                'amount' => 0,

                'quantity' => $quantity,

                'total' => 0,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | PRICING
        |--------------------------------------------------------------------------
        */

        $banquetAmount =
            (float) (
                $validated[
                    'banquet_booking_amount'
                ]
            );

        $subtotal =
            $banquetAmount +
            $servicesTotal;

        $discount =
            (float) (
                $validated['discount'] ?? 0
            );

        if ($discount < 0) {
            $discount = 0;
        }

        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        $afterDiscount =
            $subtotal - $discount;

        /*
        |--------------------------------------------------------------------------
        | TAX
        |--------------------------------------------------------------------------
        */

        $settings = TenantSetting::where(
            'tenant_id',
            $tenantId
        )->first();

        $taxPercentage = 0;

        if (
            $settings &&
            $settings->tax_enabled
        ) {

            $taxPercentage =
                (float) (
                    $settings->tax_percentage ?? 0
                );
        }

        $tax =
            $afterDiscount *
            ($taxPercentage / 100);

        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        $totalAmount =
            $afterDiscount +
            $tax;

        if ($totalAmount < 0) {
            $totalAmount = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | ADVANCE
        |--------------------------------------------------------------------------
        */

        $bookingAmount =
            (float) (
                $validated['bookingamount'] ?? 0
            );

        if ($bookingAmount < 0) {
            $bookingAmount = 0;
        }

        if ($bookingAmount > $totalAmount) {
            $bookingAmount = $totalAmount;
        }

        $remainingAmount =
            $totalAmount -
            $bookingAmount;

        /*
        |--------------------------------------------------------------------------
        | CREATE QUOTATION
        |--------------------------------------------------------------------------
        */

        $quotationNumber =
            'QT-'
            .now()->format('Ymd')
            .'-'
            .strtoupper(
                Str::random(6)
            );

        $quotation =
            Quotation::create([

                'tenant_id' => $tenantId,

                'customer_id' => $customer->id,

                'lawn_type_id' => $validated['lawn_type_id'],

                'quotation_number' => $quotationNumber,

                'event_type' => $validated['event_type'],

                'event_date' => $validated['event_date'],

                'booking_time' => $validated['booking_time'],

                'number_of_guests' => $validated['number_of_guests'],

                'package_name' => $validated['package_name']
                    ?? null,

                'menu_details' => $validated['menu_details']
                    ?? null,

                'services' => $servicesData,

                'banquet_booking_amount' => $banquetAmount,

                'subtotal' => $subtotal,

                'discount' => $discount,

                'tax' => $tax,

                'totalamount' => $totalAmount,

                'bookingamount' => $bookingAmount,

                'remainingamount' => $remainingAmount,

                'valid_until' => $validated['valid_until']
                    ?? null,

                'status' => $validated['status']
                    ?? 'draft',

                'notes' => $validated['notes']
                    ?? null,

                'invoice_id' => null,
            ]);

        return redirect()
            ->route(
                'quotations.show',
                $quotation
            )
            ->with(
                'success',
                'Quotation created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Quotation $quotation
    ) {
        $this->checkTenant(
            $quotation
        );

        $quotation->load([
            'customer',
            'lawnType',
            'tenant',
            'invoice',
        ]);

        return view(
            'quotations.show',
            compact('quotation')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Quotation $quotation
    ) {
        $this->checkTenant(
            $quotation
        );

        $user = auth()->user();

        if (! $user->tenant_id) {
            abort(
                403,
                'Tenant is not assigned to this user.'
            );
        }

        $tenantId =
            $user->tenant_id;

        $customers =
            Customer::where(
                'tenant_id',
                $tenantId
            )
                ->orderBy('name')
                ->get();

        $lawnTypes =
            LawnType::where(
                'tenant_id',
                $tenantId
            )
                ->orderBy('lawn_type')
                ->get();

        $services =
            Service::where(
                'tenant_id',
                $tenantId
            )
                ->orderBy('service_name')
                ->get();

        $freeServices =
            FreeService::where(
                'tenant_id',
                $tenantId
            )
                ->where(
                    'status',
                    true
                )
                ->orderBy('service_name')
                ->get();

        $settings =
            TenantSetting::where(
                'tenant_id',
                $tenantId
            )->first();

        return view(
            'quotations.edit',
            compact(
                'quotation',
                'customers',
                'lawnTypes',
                'services',
                'freeServices',
                'settings'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Quotation $quotation
    ) {
        $this->checkTenant(
            $quotation
        );

        $user = auth()->user();

        if (! $user->tenant_id) {
            abort(
                403,
                'Tenant is not assigned to this user.'
            );
        }

        $tenantId =
            $user->tenant_id;

        $validated =
            $request->validate([

                'customer_name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'lawn_type_id' => [
                    'required',
                    Rule::exists(
                        'lawn_types',
                        'id'
                    )->where(
                        fn ($query) => $query->where(
                            'tenant_id',
                            $tenantId
                        )
                    ),
                ],

                'event_type' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'event_date' => [
                    'required',
                    'date',
                ],

                'booking_time' => [
                    'required',
                    Rule::in([
                        'day',
                        'night',
                    ]),
                ],

                'number_of_guests' => [
                    'required',
                    'integer',
                    'min:1',
                ],

                'package_name' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'valid_until' => [
                    'nullable',
                    'date',
                ],

                'menu_details' => [
                    'nullable',
                    'string',
                ],

                /*
                |--------------------------------------------------------------------------
                | PAID SERVICES
                |--------------------------------------------------------------------------
                */

                'service_ids' => [
                    'nullable',
                    'array',
                ],

                'service_ids.*' => [
                    'nullable',
                    'integer',
                    'distinct',
                    Rule::exists(
                        'services',
                        'id'
                    )->where(
                        fn ($query) => $query->where(
                            'tenant_id',
                            $tenantId
                        )
                    ),
                ],

                'service_quantities' => [
                    'nullable',
                    'array',
                ],

                'service_quantities.*' => [
                    'nullable',
                    'numeric',
                    'min:1',
                ],

                /*
                |--------------------------------------------------------------------------
                | FREE SERVICES
                |--------------------------------------------------------------------------
                */

                'free_service_ids' => [
                    'nullable',
                    'array',
                ],

                'free_service_ids.*' => [
                    'nullable',
                    'integer',
                    'distinct',
                    Rule::exists(
                        'free_services',
                        'id'
                    )->where(
                        fn ($query) => $query
                            ->where(
                                'tenant_id',
                                $tenantId
                            )
                            ->where(
                                'status',
                                true
                            )
                    ),
                ],

                'free_service_quantities' => [
                    'nullable',
                    'array',
                ],

                'free_service_quantities.*' => [
                    'nullable',
                    'numeric',
                    'min:1',
                ],

                'banquet_booking_amount' => [
                    'required',
                    'numeric',
                    'min:0',
                ],

                'discount' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'bookingamount' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'status' => [
                    'required',
                    Rule::in([
                        'draft',
                        'sent',
                        'accepted',
                        'rejected',
                        'expired',
                    ]),
                ],

                'notes' => [
                    'nullable',
                    'string',
                ],
            ]);

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER
        |--------------------------------------------------------------------------
        */

        $customer =
            Customer::firstOrCreate(
                [
                    'tenant_id' => $tenantId,

                    'name' => trim(
                        $validated[
                            'customer_name'
                        ]
                    ),
                ]
            );

        /*
        |--------------------------------------------------------------------------
        | PAID SERVICES
        |--------------------------------------------------------------------------
        */

        $servicesData = [];

        $servicesTotal = 0;

        $serviceIds =
            $validated['service_ids'] ?? [];

        $serviceQuantities =
            $validated[
                'service_quantities'
            ] ?? [];

        foreach (
            $serviceIds as $index => $serviceId
        ) {

            if (empty($serviceId)) {
                continue;
            }

            $service =
                Service::where(
                    'tenant_id',
                    $tenantId
                )
                    ->where(
                        'id',
                        $serviceId
                    )
                    ->first();

            if (! $service) {
                continue;
            }

            $quantity =
                (float) (
                    $serviceQuantities[
                        $index
                    ] ?? 1
                );

            if ($quantity < 1) {
                $quantity = 1;
            }

            $amount =
                (float) $service->amount;

            $lineTotal =
                $amount * $quantity;

            $servicesTotal +=
                $lineTotal;

            $servicesData[] = [

                'service_type' => 'paid',

                'id' => $service->id,

                'name' => $service->service_name,

                'amount' => $amount,

                'quantity' => $quantity,

                'total' => round(
                    $lineTotal,
                    2
                ),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | FREE SERVICES
        |--------------------------------------------------------------------------
        */

        $freeServiceIds =
            $validated[
                'free_service_ids'
            ] ?? [];

        $freeServiceQuantities =
            $validated[
                'free_service_quantities'
            ] ?? [];

        foreach (
            $freeServiceIds as $index => $freeServiceId
        ) {

            if (empty($freeServiceId)) {
                continue;
            }

            $freeService =
                FreeService::where(
                    'tenant_id',
                    $tenantId
                )
                    ->where(
                        'status',
                        true
                    )
                    ->where(
                        'id',
                        $freeServiceId
                    )
                    ->first();

            if (! $freeService) {
                continue;
            }

            $quantity =
                (float) (
                    $freeServiceQuantities[
                        $index
                    ] ?? 1
                );

            if ($quantity < 1) {
                $quantity = 1;
            }

            $servicesData[] = [

                'service_type' => 'free',

                'id' => $freeService->id,

                'name' => $freeService->service_name,

                'amount' => 0,

                'quantity' => $quantity,

                'total' => 0,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | PRICING
        |--------------------------------------------------------------------------
        */

        $banquetAmount =
            (float) (
                $validated[
                    'banquet_booking_amount'
                ] ?? 0
            );

        $subtotal =
            $banquetAmount +
            $servicesTotal;

        $discount =
            (float) (
                $validated['discount'] ?? 0
            );

        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        $afterDiscount =
            max(
                $subtotal -
                $discount,
                0
            );

        /*
        |--------------------------------------------------------------------------
        | TAX
        |--------------------------------------------------------------------------
        */

        $settings =
            TenantSetting::where(
                'tenant_id',
                $tenantId
            )->first();

        $taxPercentage = 0;

        if (
            $settings &&
            $settings->tax_enabled
        ) {

            $taxPercentage =
                (float) (
                    $settings->tax_percentage
                    ?? 0
                );
        }

        $tax =
            $afterDiscount *
            (
                $taxPercentage /
                100
            );

        $totalAmount =
            $afterDiscount +
            $tax;

        /*
        |--------------------------------------------------------------------------
        | ADVANCE / REMAINING
        |--------------------------------------------------------------------------
        */

        $bookingAmount =
            (float) (
                $validated[
                    'bookingamount'
                ] ?? 0
            );

        if ($bookingAmount < 0) {
            $bookingAmount = 0;
        }

        if ($bookingAmount > $totalAmount) {
            $bookingAmount = $totalAmount;
        }

        $remainingAmount =
            max(
                $totalAmount -
                $bookingAmount,
                0
            );

        /*
        |--------------------------------------------------------------------------
        | UPDATE QUOTATION
        |--------------------------------------------------------------------------
        */

        $quotation->update([

            'customer_id' => $customer->id,

            'lawn_type_id' => $validated['lawn_type_id'],

            'event_type' => $validated['event_type'],

            'event_date' => $validated['event_date'],

            'booking_time' => $validated['booking_time'],

            'number_of_guests' => $validated['number_of_guests'],

            'package_name' => $validated['package_name']
                ?? null,

            'menu_details' => $validated['menu_details']
                ?? null,

            'services' => $servicesData,

            'banquet_booking_amount' => $banquetAmount,

            'subtotal' => $subtotal,

            'discount' => $discount,

            'tax' => $tax,

            'totalamount' => $totalAmount,

            'bookingamount' => $bookingAmount,

            'remainingamount' => $remainingAmount,

            'valid_until' => $validated['valid_until']
                ?? null,

            'status' => $validated['status'],

            'notes' => $validated['notes']
                ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE LINKED INVOICE
        |--------------------------------------------------------------------------
        */

        $invoice =
            Invoice::where(
                'tenant_id',
                $tenantId
            )
                ->where(
                    'quotation_id',
                    $quotation->id
                )
                ->first();

        if ($invoice) {

            DB::transaction(
                function () use (
                    $invoice,
                    $quotation,
                    $servicesData,
                    $banquetAmount,
                    $subtotal,
                    $discount,
                    $tax,
                    $totalAmount,
                    $remainingAmount
                ) {

                    $invoice->update([

                        'customer_id' => $quotation->customer_id,

                        'quotation_id' => $quotation->id,

                        'subtotal' => $subtotal,

                        'discount' => $discount,

                        'tax' => $tax,

                        'grand_total' => $totalAmount,

                        'remaining_amount' => $remainingAmount,
                    ]);

                    $invoice->items()
                        ->delete();

                    InvoiceItem::create([

                        'invoice_id' => $invoice->id,

                        'description' => 'Banquet Booking',

                        'quantity' => 1,

                        'unit_cost' => $banquetAmount,

                        'total' => $banquetAmount,
                    ]);

                    foreach (
                        $servicesData as $service
                    ) {

                        InvoiceItem::create([

                            'invoice_id' => $invoice->id,

                            'description' => $service['name'],

                            'quantity' => $service['quantity'],

                            'unit_cost' => $service['amount'],

                            'total' => $service['total'],
                        ]);
                    }

                    $quotation->update([
                        'invoice_id' => $invoice->id,
                    ]);
                }
            );
        }

        return redirect()
            ->route(
                'quotations.show',
                $quotation
            )
            ->with(
                'success',
                'Quotation updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE INVOICE
    |--------------------------------------------------------------------------
    */

    public function generateInvoice(
        Quotation $quotation
    ) {
        $this->checkTenant(
            $quotation
        );

        $tenantId =
            auth()->user()->tenant_id;

        /*
        |--------------------------------------------------------------------------
        | Existing Invoice
        |--------------------------------------------------------------------------
        */

        $existingInvoice =
            Invoice::where(
                'tenant_id',
                $tenantId
            )
                ->where(
                    'quotation_id',
                    $quotation->id
                )
                ->first();

        if ($existingInvoice) {

            return redirect()
                ->route(
                    'invoices.show',
                    $existingInvoice
                )
                ->with(
                    'success',
                    'Invoice already exists for this quotation.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Services
        |--------------------------------------------------------------------------
        */

        $services =
            $quotation->services;

        if (! is_array($services)) {
            $services = [];
        }

        /*
        |--------------------------------------------------------------------------
        | Create Invoice
        |--------------------------------------------------------------------------
        */

        $invoice =
            DB::transaction(
                function () use (
                    $quotation,
                    $tenantId,
                    $services
                ) {

                    $invoiceNumber =
                        'INV-'
                        .now()->format('Ymd')
                        .'-'
                        .strtoupper(
                            Str::random(6)
                        );

                    $invoice =
                        Invoice::create([

                            'tenant_id' => $tenantId,

                            'customer_id' => $quotation->customer_id,

                            'booking_id' => null,

                            'quotation_id' => $quotation->id,

                            'invoice_number' => $invoiceNumber,

                            'invoice_date' => now()->toDateString(),

                            'due_date' => null,

                            'subtotal' => $quotation->subtotal,

                            'discount' => $quotation->discount,

                            'tax' => $quotation->tax,

                            'additional_charges' => 0,

                            'grand_total' => $quotation->totalamount,

                            'paid_amount' => $quotation->bookingamount,

                            'remaining_amount' => $quotation->remainingamount,

                            'notes' => $quotation->notes,
                        ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Booking Item
                    |--------------------------------------------------------------------------
                    */

                    InvoiceItem::create([

                        'invoice_id' => $invoice->id,

                        'description' => 'Banquet Booking',

                        'quantity' => 1,

                        'unit_cost' => $quotation->banquet_booking_amount,

                        'total' => $quotation->banquet_booking_amount,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Service Items
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $services as $service
                    ) {

                        InvoiceItem::create([

                            'invoice_id' => $invoice->id,

                            'description' => $service['name']
                                ?? 'Service',

                            'quantity' => $service['quantity']
                                ?? 1,

                            'unit_cost' => $service['amount']
                                ?? 0,

                            'total' => $service['total']
                                ?? 0,
                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Link Quotation
                    |--------------------------------------------------------------------------
                    */

                    $quotation->update([

                        'invoice_id' => $invoice->id,
                    ]);

                    return $invoice;
                }
            );

        return redirect()
            ->route(
                'invoices.show',
                $invoice
            )
            ->with(
                'success',
                'Invoice generated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Quotation $quotation
    ) {
        $this->checkTenant(
            $quotation
        );

        $quotation->delete();

        return redirect()
            ->route(
                'quotations.index'
            )
            ->with(
                'success',
                'Quotation deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | PRINT
    |--------------------------------------------------------------------------
    */

    public function print(
        Quotation $quotation
    ) {
        $this->checkTenant(
            $quotation
        );

        $quotation->load([
            'customer',
            'lawnType',
            'tenant',
            'invoice',
        ]);

        return view(
            'quotations.print',
            compact('quotation')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CONVERT TO BOOKING
    |--------------------------------------------------------------------------
    */

    public function convertToBooking(Quotation $quotation)
    {
        /*
        |--------------------------------------------------------------------------
        | TENANT SECURITY
        |--------------------------------------------------------------------------
        */

        $this->checkTenant($quotation);

        /*
        |--------------------------------------------------------------------------
        | ALREADY CONVERTED
        |--------------------------------------------------------------------------
        */

        if ($quotation->status === 'accepted') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'This quotation has already been converted or accepted.'
                );
        }

        $tenantId = auth()->user()->tenant_id;

        /*
        |--------------------------------------------------------------------------
        | CONVERT QUOTATION -> BOOKING
        |--------------------------------------------------------------------------
        |
        | Same calculation values used by quotation are carried into booking.
        |
        */

        $bookingAmount = (float) (
            $quotation->bookingamount
            ?? $quotation->banquet_booking_amount
            ?? 0
        );

        $subtotal = (float) (
            $quotation->subtotal
            ?? $quotation->total_amount
            ?? 0
        );

        $discount = (float) (
            $quotation->discount
            ?? 0
        );

        $taxAmount = (float) (
            $quotation->tax
            ?? 0
        );

        $grandTotal = (float) (
            $quotation->totalamount
            ?? $quotation->total_amount
            ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | ADVANCE
        |--------------------------------------------------------------------------
        |
        | Quotation booking amount is treated as advance exactly as
        | the current conversion flow does.
        |
        */

        $advanceAmount = $bookingAmount;

        $remainingAmount = max(
            $grandTotal - $advanceAmount,
            0
        );

        /*
        |--------------------------------------------------------------------------
        | PAYMENT STATUS
        |--------------------------------------------------------------------------
        */

        $paymentStatus = match (true) {
            $advanceAmount <= 0 => 'pending',
            $remainingAmount <= 0 => 'paid',
            default => 'partial',
        };

        /*
        |--------------------------------------------------------------------------
        | SERVICES FROM QUOTATION
        |--------------------------------------------------------------------------
        |
        | Quotation stores services as JSON.
        |
        */

        $quotationServices = $quotation->services;

        if (is_string($quotationServices)) {
            $quotationServices = json_decode(
                $quotationServices,
                true
            ) ?? [];
        }

        if (! is_array($quotationServices)) {
            $quotationServices = [];
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE EVERYTHING IN ONE TRANSACTION
        |--------------------------------------------------------------------------
        */

        $booking = DB::transaction(
            function () use (
                $quotation,
                $tenantId,
                $bookingAmount,
                $subtotal,
                $discount,
                $taxAmount,
                $grandTotal,
                $advanceAmount,
                $remainingAmount,
                $paymentStatus,
                $quotationServices
            ) {

                /*
                |--------------------------------------------------------------------------
                | 1. BOOKING
                |--------------------------------------------------------------------------
                */

                $booking = Booking::create([
                    'tenant_id' => $tenantId,

                    'customer_id' => $quotation->customer_id,

                    'lawn_type' => $quotation->lawn_type_id,

                    'event_type' => $quotation->event_type,

                    'booking_date' => $quotation->event_date,

                    'booking_time' => $quotation->booking_time,

                    'number_of_guests' => $quotation->number_of_guests,

                    'booking_amount' => $bookingAmount,

                    'total_amount' => $subtotal,

                    'tax_amount' => $taxAmount,

                    'discount' => $discount,

                    'grand_total' => $grandTotal,

                    'advance_amount' => $advanceAmount,

                    'remaining_amount' => $remainingAmount,

                    'payment_method' => null,

                    'status' => 'confirmed',

                    'notes' => $quotation->notes,
                ]);

                /*
                |--------------------------------------------------------------------------
                | 2. BOOKING SERVICES
                |--------------------------------------------------------------------------
                */

                foreach ($quotationServices as $serviceData) {

                    $serviceType = $serviceData['service_type']
                        ?? 'paid';

                    $quantity = (int) (
                        $serviceData['quantity']
                        ?? $serviceData['qty']
                        ?? 1
                    );

                    $price = (float) (
                        $serviceData['amount']
                        ?? $serviceData['price']
                        ?? 0
                    );

                    $total = (float) (
                        $serviceData['total']
                        ?? ($price * $quantity)
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | PAID SERVICE
                    |--------------------------------------------------------------------------
                    */

                    if ($serviceType === 'paid') {

                        $paidServiceId =
                            $serviceData['id']
                            ?? $serviceData['service_id']
                            ?? $serviceData['paid_service_id']
                            ?? null;

                        if (! $paidServiceId) {
                            continue;
                        }

                        $service = Service::where(
                            'tenant_id',
                            $tenantId
                        )->find(
                            $paidServiceId
                        );

                        if (! $service) {
                            continue;
                        }

                        BookingService::create([
                            'booking_id' => $booking->id,

                            'service_type' => 'paid',

                            'paid_service_id' => $service->id,

                            'free_service_id' => null,

                            'quantity' => $quantity,

                            'price' => $price,

                            'total' => $total,
                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | FREE SERVICE
                    |--------------------------------------------------------------------------
                    */

                    elseif ($serviceType === 'free') {

                        $freeServiceId =
                            $serviceData['id']
                            ?? $serviceData['service_id']
                            ?? $serviceData['free_service_id']
                            ?? null;

                        if (! $freeServiceId) {
                            continue;
                        }

                        $service = FreeService::where(
                            'tenant_id',
                            $tenantId
                        )
                            ->where('status', true)
                            ->find(
                                $freeServiceId
                            );

                        if (! $service) {
                            continue;
                        }

                        BookingService::create([
                            'booking_id' => $booking->id,

                            'service_type' => 'free',

                            'paid_service_id' => null,

                            'free_service_id' => $service->id,

                            'quantity' => $quantity,

                            'price' => 0,

                            'total' => 0,
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | 3. INVOICE
                |--------------------------------------------------------------------------
                |
                | Same structure as normal BookingController::store()
                |
                */

                $invoice = Invoice::create([
                    'tenant_id' => $tenantId,

                    'customer_id' => $quotation->customer_id,

                    'booking_id' => $booking->id,

                    'quotation_id' => $quotation->id,

                    'invoice_number' => $this->generateInvoiceNumber(
                        $tenantId
                    ),

                    'invoice_date' => now()->toDateString(),

                    'due_date' => null,

                    'subtotal' => $subtotal,

                    'discount' => $discount,

                    'tax' => $taxAmount,

                    'additional_charges' => 0,

                    'grand_total' => $grandTotal,

                    'paid_amount' => $advanceAmount,

                    'remaining_amount' => $remainingAmount,

                    'status' => $paymentStatus === 'paid'
                        ? 'paid'
                        : (
                            $paymentStatus === 'partial'
                                ? 'partial'
                                : 'unpaid'
                        ),

                    'notes' => $quotation->notes,
                ]);

                /*
                |--------------------------------------------------------------------------
                | 4. INVOICE - BOOKING ITEM
                |--------------------------------------------------------------------------
                */

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,

                    'description' => 'Lawn Booking - '
                        .$quotation->event_type,

                    'quantity' => 1,

                    'unit_cost' => $bookingAmount,

                    'total' => $bookingAmount,
                ]);

                /*
                |--------------------------------------------------------------------------
                | 5. INVOICE - SERVICES
                |--------------------------------------------------------------------------
                */

                foreach ($quotationServices as $serviceData) {

                    $serviceType = $serviceData['service_type']
                        ?? 'paid';

                    $quantity = (int) (
                        $serviceData['quantity']
                        ?? $serviceData['qty']
                        ?? 1
                    );

                    $price = (float) (
                        $serviceData['amount']
                        ?? $serviceData['price']
                        ?? 0
                    );

                    $total = (float) (
                        $serviceData['total']
                        ?? ($price * $quantity)
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | PAID SERVICE
                    |--------------------------------------------------------------------------
                    */

                    if ($serviceType === 'paid') {

                        $paidServiceId =
                            $serviceData['id']
                            ?? $serviceData['service_id']
                            ?? $serviceData['paid_service_id']
                            ?? null;

                        if (! $paidServiceId) {
                            continue;
                        }

                        $service = Service::where(
                            'tenant_id',
                            $tenantId
                        )->find(
                            $paidServiceId
                        );

                        if (! $service) {
                            continue;
                        }

                        InvoiceItem::create([
                            'invoice_id' => $invoice->id,

                            'description' => $serviceData['name']
                                ?? $service->service_name,

                            'quantity' => $quantity,

                            'unit_cost' => $price,

                            'total' => $total,
                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | FREE SERVICE
                    |--------------------------------------------------------------------------
                    */

                    elseif ($serviceType === 'free') {

                        $freeServiceId =
                            $serviceData['id']
                            ?? $serviceData['service_id']
                            ?? $serviceData['free_service_id']
                            ?? null;

                        if (! $freeServiceId) {
                            continue;
                        }

                        $service = FreeService::where(
                            'tenant_id',
                            $tenantId
                        )->find(
                            $freeServiceId
                        );

                        if (! $service) {
                            continue;
                        }

                        InvoiceItem::create([
                            'invoice_id' => $invoice->id,

                            'description' => $serviceData['name']
                                ?? $service->service_name,

                            'quantity' => $quantity,

                            'unit_cost' => 0,

                            'total' => 0,
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | 6. FIRST PAYMENT
                |--------------------------------------------------------------------------
                |
                | Normal booking form mein advance > 0 hone par
                | BookingPayment create hoti hai.
                |
                */

                if ($advanceAmount > 0) {

                    BookingPayment::create([
                        'tenant_id' => $tenantId,

                        'booking_id' => $booking->id,

                        'amount' => $advanceAmount,

                        'payment_method' => 'cash',

                        'payment_status' => $paymentStatus,

                        'transaction_reference' => null,

                        'notes' => 'Advance transferred from quotation.',

                        'paid_at' => now(),
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | 7. QUOTATION
                |--------------------------------------------------------------------------
                */

                $quotation->update([
                    'status' => 'accepted',

                    'invoice_id' => $invoice->id,
                ]);

                return $booking;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'bookings.show',
                $booking
            )
            ->with(
                'success',
                'Quotation successfully converted to booking and invoice generated.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TENANT SECURITY
    |--------------------------------------------------------------------------
    */

    private function checkTenant(
        Quotation $quotation
    ): void {

        $user = auth()->user();

        if (
            $user->role !== 'super_admin'
            &&
            $quotation->tenant_id !==
            $user->tenant_id
        ) {
            abort(403);
        }
    }
}
