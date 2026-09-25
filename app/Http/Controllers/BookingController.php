<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\BookingPaymentReceipt;
use App\Models\BookingService;
use App\Models\CancelledBooking;
use App\Models\Customer;
use App\Models\FreeService;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\LawnType;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\TenantSetting;
use App\Models\User;
use App\Notifications\TenantActivityNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $tenantId =
            (int) auth()->user()->tenant_id;

        /*
        |--------------------------------------------------------------------------
        | BOOKINGS
        |--------------------------------------------------------------------------
        */

        $query = Booking::with([
            'customer',
            'lawnType',
            'bookingServices.paidService',
            'bookingServices.freeService',
            'payments',
            'invoice',
        ])
            ->where(
                'tenant_id',
                $tenantId
            )
            ->where(
                'status',
                '!=',
                'cancelled'
            );

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search =
                trim($request->search);

            $query->where(
                function ($q) use ($search) {

                    $q->where(
                        'event_type',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'booking_time',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'payment_method',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'status',
                            'like',
                            "%{$search}%"
                        );

                    $q->orWhereHas(
                        'customer',
                        function ($customer) use ($search) {

                            $customer
                                ->where(
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
                                )
                                ->orWhere(
                                    'nic_number',
                                    'like',
                                    "%{$search}%"
                                );

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

                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATE FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('booking_date')) {

            $query->whereDate(
                'booking_date',
                $request->booking_date
            );

        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_method')) {

            $query->where(
                'payment_method',
                $request->payment_method
            );

        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );

        }

        /*
        |--------------------------------------------------------------------------
        | GET BOOKINGS
        |--------------------------------------------------------------------------
        */

        $bookings =
            $query
                ->latest('booking_date')
                ->latest('id')
                ->get();

        /*
        |--------------------------------------------------------------------------
        | LAWN TYPES
        |--------------------------------------------------------------------------
        */

        $lawnTypes =
            LawnType::where(
                'tenant_id',
                $tenantId
            )
                ->orderBy(
                    'lawn_type'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | CUSTOMERS
        |--------------------------------------------------------------------------
        */

        $customers =
            Customer::where(
                'tenant_id',
                $tenantId
            )
                ->orderBy(
                    'name'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | PAID SERVICES
        |--------------------------------------------------------------------------
        */

        $services =
            Service::where(
                'tenant_id',
                $tenantId
            )
                ->orderBy(
                    'service_name'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | FREE SERVICES
        |--------------------------------------------------------------------------
        */

        $freeServices =
            FreeService::where(
                'tenant_id',
                $tenantId
            )
                ->where(
                    'status',
                    true
                )
                ->orderBy(
                    'service_name'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | TENANT SETTINGS
        |--------------------------------------------------------------------------
        */

        $settings =
            TenantSetting::where(
                'tenant_id',
                $tenantId
            )->first();

        /*
        |--------------------------------------------------------------------------
        | TAX
        |--------------------------------------------------------------------------
        */

        $taxEnabled =
            (bool) (
                $settings->tax_enabled
                ?? false
            );

        $taxPercent =
            $this->getTaxPercentage(
                $settings
            );

        /*
        |--------------------------------------------------------------------------
        | PAYMENT METHODS
        |--------------------------------------------------------------------------
        */

        $paymentMethods = [];

        if ($settings) {

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

        }

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'bookings.index',
            compact(
                'bookings',
                'lawnTypes',
                'customers',
                'services',
                'freeServices',
                'settings',
                'taxEnabled',
                'taxPercent',
                'paymentMethods'
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
        $tenantId =
            (int) auth()->user()->tenant_id;

        $lawnTypes =
            LawnType::where(
                'tenant_id',
                $tenantId
            )
                ->orderBy(
                    'lawn_type'
                )
                ->get();

        $customers =
            Customer::where(
                'tenant_id',
                $tenantId
            )
                ->orderBy(
                    'name'
                )
                ->get();

        $services =
            Service::where(
                'tenant_id',
                $tenantId
            )
                ->orderBy(
                    'service_name'
                )
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
                ->orderBy(
                    'service_name'
                )
                ->get();

        $settings =
            TenantSetting::where(
                'tenant_id',
                $tenantId
            )->first();

        $taxEnabled =
            (bool) (
                $settings->tax_enabled
                ?? false
            );

        $taxPercent =
            $this->getTaxPercentage(
                $settings
            );

        $paymentMethods = [];

        if ($settings) {

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

        }

        return view(
            'bookings.create',
            compact(
                'lawnTypes',
                'customers',
                'services',
                'freeServices',
                'settings',
                'taxEnabled',
                'taxPercent',
                'paymentMethods'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ) {
        $tenantId =
            (int) auth()->user()->tenant_id;

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate([

                /*
                |--------------------------------------------------------------------------
                | EVENT & LAWN
                |--------------------------------------------------------------------------
                */

                'lawn_type' => [

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

                'booking_time' => [

                    'required',

                    Rule::in([
                        'day',
                        'night',
                    ]),

            ],

                'booking_date' => [

                    'required',
                    'date',
                    'after_or_equal:today',

            ],

                'event_type' => [

                    'required',
                    'string',
                    'max:255',

            ],

                'number_of_guests' => [

                    'required',
                    'integer',
                    'min:1',

            ],

                /*
            |--------------------------------------------------------------------------
            | CUSTOMER
            |--------------------------------------------------------------------------
            */

                'customer_name' => [

                    'required',
                    'string',
                    'max:255',

            ],

                'customer_number' => [

                    'required',
                    'string',
                    'max:20',

            ],

                'customer_nic_number' => [

                    'required',
                    'string',
                    'max:20',

            ],

                'customer_email' => [

                    'required',
                    'email',
                    'max:100',

            ],

                'customer_address' => [

                    'required',
                    'string',
                    'max:1000',

            ],

                /*
            |--------------------------------------------------------------------------
            | BOOKING AMOUNT
            |--------------------------------------------------------------------------
            */

                'booking_amount' => [

                    'required',
                    'numeric',
                    'min:0',

            ],

                /*
            |--------------------------------------------------------------------------
            | DISCOUNT
            |--------------------------------------------------------------------------
            */

                'discount' => [

                    'nullable',
                    'numeric',
                    'min:0',

            ],

                /*
            |--------------------------------------------------------------------------
            | PAID SERVICES
            |--------------------------------------------------------------------------
            */

                'paid_services' => [

                    'nullable',
                    'array',

            ],

                'paid_services.*.service_id' => [

                    'required',

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

                'paid_services.*.quantity' => [

                    'required',
                    'integer',
                    'min:1',

            ],

                /*
            |--------------------------------------------------------------------------
            | FREE SERVICES
            |--------------------------------------------------------------------------
            */

                'free_services' => [

                    'nullable',
                    'array',

            ],

                'free_services.*.service_id' => [

                    'required',

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

                'free_services.*.quantity' => [

                    'required',
                    'integer',
                    'min:1',

            ],

                /*
            |--------------------------------------------------------------------------
            | BACKWARD COMPATIBILITY
            |--------------------------------------------------------------------------
            */

                'services' => [

                    'nullable',
                    'array',

            ],

                'services.*.service_id' => [

                    'required',

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

                'services.*.quantity' => [

                    'required',
                    'integer',
                    'min:1',

            ],

                /*
            |--------------------------------------------------------------------------
            | PAY AMOUNT
            |--------------------------------------------------------------------------
            |
            | This is NOT received payment.
            |
            | It is the amount customer is required to pay according
            | to this invoice.
            |
            */

                'advance_amount' => [

                    'nullable',
                    'numeric',
                    'min:0',

            ],

                /*
            |--------------------------------------------------------------------------
            | PAYMENT METHOD
            |--------------------------------------------------------------------------
            |
            | Optional planned payment method.
            | It does NOT create booking_payment.
            |
            */

                'payment_method' => [

                    'nullable',

                    Rule::in([

                        'cash',
                        'bank_transfer',
                        'card',
                        'online',
                        'cheque',

                    ]),

            ],

                /*
            |--------------------------------------------------------------------------
            | PAYMENT DATE
            |--------------------------------------------------------------------------
            |
            | This is only invoice/payment reference information.
            | It does NOT mean payment is received.
            |
            */

                'paid_at' => [

                    'nullable',
                    'date',

            ],

                /*
            |--------------------------------------------------------------------------
            | TRANSACTION REFERENCE
            |--------------------------------------------------------------------------
            */

                'transaction_reference' => [

                    'nullable',
                    'string',
                    'max:255',

            ],

                /*
            |--------------------------------------------------------------------------
            | PAYMENT NOTES
            |--------------------------------------------------------------------------
            */

                'payment_notes' => [

                    'nullable',
                    'string',
                    'max:1000',

            ],

                /*
            |--------------------------------------------------------------------------
            | BOOKING NOTES
            |--------------------------------------------------------------------------
            */

                'notes' => [

                    'nullable',
                    'string',
                    'max:2000',

            ],

            ]);

        /*
        |--------------------------------------------------------------------------
        | AVAILABILITY
        |--------------------------------------------------------------------------
        */

        $alreadyBooked =
            Booking::where(
                'tenant_id',
                $tenantId
            )
                ->where(
                    'lawn_type',
                    $validated['lawn_type']
                )
                ->whereDate(
                    'booking_date',
                    $validated['booking_date']
                )
                ->where(
                    'booking_time',
                    $validated['booking_time']
                )
                ->exists();

        if ($alreadyBooked) {

            return back()
                ->withInput()
                ->withErrors([

                    'booking_date' => 'This lawn is already booked for the selected date and time.',

                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | TENANT SETTINGS
        |--------------------------------------------------------------------------
        */

        $settings =
            TenantSetting::where(
                'tenant_id',
                $tenantId
            )->first();

        /*
        |--------------------------------------------------------------------------
        | TAX
        |--------------------------------------------------------------------------
        */

        $taxEnabled =
            (bool) (
                $settings->tax_enabled
                ?? false
            );

        $taxPercent =
            $this->getTaxPercentage(
                $settings
            );

        /*
        |--------------------------------------------------------------------------
        | BOOKING AMOUNT
        |--------------------------------------------------------------------------
        */

        $bookingAmount =
            (float) (
                $validated['booking_amount']
            );

        /*
        |--------------------------------------------------------------------------
        | SERVICES
        |--------------------------------------------------------------------------
        */

        $serviceTotal = 0;

        $serviceRows = [];

        /*
        |--------------------------------------------------------------------------
        | PAID SERVICES
        |--------------------------------------------------------------------------
        */

        foreach (
            $validated['paid_services'] ?? [] as $serviceData
        ) {

            $service =
                Service::where(
                    'tenant_id',
                    $tenantId
                )
                    ->findOrFail(
                        $serviceData['service_id']
                    );

            $quantity =
                (int) (
                    $serviceData['quantity']
                );

            $price =
                (float) (
                    $service->amount
                );

            $lineTotal =
                $price * $quantity;

            $serviceTotal +=
                $lineTotal;

            $serviceRows[] = [

                'service_type' => 'paid',

                'paid_service_id' => $service->id,

                'free_service_id' => null,

                'quantity' => $quantity,

                'price' => $price,

                'total' => $lineTotal,

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | FREE SERVICES
        |--------------------------------------------------------------------------
        */

        foreach (
            $validated['free_services'] ?? [] as $serviceData
        ) {

            $service =
                FreeService::where(
                    'tenant_id',
                    $tenantId
                )
                    ->where(
                        'status',
                        true
                    )
                    ->findOrFail(
                        $serviceData['service_id']
                    );

            $quantity =
                (int) (
                    $serviceData['quantity']
                );

            $serviceRows[] = [

                'service_type' => 'free',

                'paid_service_id' => null,

                'free_service_id' => $service->id,

                'quantity' => $quantity,

                'price' => 0,

                'total' => 0,

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | OLD SERVICES FORMAT
        |--------------------------------------------------------------------------
        */

        if (
            empty(
                $validated['paid_services']
            )
            &&
            empty(
                $validated['free_services']
            )
        ) {

            foreach (
                $validated['services'] ?? [] as $serviceData
            ) {

                $service =
                    Service::where(
                        'tenant_id',
                        $tenantId
                    )
                        ->findOrFail(
                            $serviceData['service_id']
                        );

                $quantity =
                    (int) (
                        $serviceData['quantity']
                    );

                $price =
                    (float) (
                        $service->amount
                    );

                $lineTotal =
                    $price * $quantity;

                $serviceTotal +=
                    $lineTotal;

                $serviceRows[] = [

                    'service_type' => 'paid',

                    'paid_service_id' => $service->id,

                    'free_service_id' => null,

                    'quantity' => $quantity,

                    'price' => $price,

                    'total' => $lineTotal,

                ];

            }

        }

        /*
        |--------------------------------------------------------------------------
        | SUBTOTAL
        |--------------------------------------------------------------------------
        */

        $subtotal =
            $bookingAmount
            + $serviceTotal;

        /*
        |--------------------------------------------------------------------------
        | DISCOUNT
        |--------------------------------------------------------------------------
        */

        $discount =
            (float) (
                $validated['discount']
                ?? 0
            );

        if (
            $discount >
            $subtotal
        ) {

            return back()
                ->withInput()
                ->withErrors([

                    'discount' => 'Discount cannot be greater than subtotal.',

                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | TAXABLE AMOUNT
        |--------------------------------------------------------------------------
        */

        $taxableAmount =
            max(

                $subtotal
                - $discount,

                0

            );

        /*
        |--------------------------------------------------------------------------
        | TAX
        |--------------------------------------------------------------------------
        */

        $taxAmount =
            (
                $taxableAmount
                * $taxPercent
            ) / 100;

        /*
        |--------------------------------------------------------------------------
        | GRAND TOTAL
        |--------------------------------------------------------------------------
        */

        $grandTotal =
            $taxableAmount
            + $taxAmount;

        /*
        |--------------------------------------------------------------------------
        | PAY AMOUNT
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | This amount means:
        |
        | "Customer ko invoice ke mutabiq itna pay karna hai."
        |
        | It is NOT actual received payment.
        |
        */

        $payAmount =
            (float) (
                $validated['advance_amount']
                ?? 0
            );

        /*
        |--------------------------------------------------------------------------
        | PAY AMOUNT CANNOT EXCEED GRAND TOTAL
        |--------------------------------------------------------------------------
        */

        if (
            $payAmount >
            $grandTotal
        ) {

            return back()
                ->withInput()
                ->withErrors([

                    'advance_amount' => 'Pay amount cannot be greater than grand total.',

                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | ACTUAL PAID AMOUNT
        |--------------------------------------------------------------------------
        |
        | Booking create par koi actual payment receive nahi hui.
        |
        */

        $paidAmount = 0;

        /*
        |--------------------------------------------------------------------------
        | ACTUAL REMAINING BALANCE
        |--------------------------------------------------------------------------
        |
        | Pay Amount received nahi hui, isliye full grand total outstanding hai.
        |
        */

        $remainingAmount =
            $grandTotal;

        /*
        |--------------------------------------------------------------------------
        | INVOICE STATUS
        |--------------------------------------------------------------------------
        */

        $invoiceStatus =
            'unpaid';

        /*
        |--------------------------------------------------------------------------
        | SAVE BOOKING + INVOICE
        |--------------------------------------------------------------------------
        */

        $booking =
            DB::transaction(

                function () use (

                    $validated,

                    $tenantId,

                    $bookingAmount,

                    $subtotal,

                    $discount,

                    $taxAmount,

                    $grandTotal,

                    $payAmount,

                    $paidAmount,

                    $remainingAmount,

                    $invoiceStatus,

                    $serviceRows

                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | CUSTOMER
                    |--------------------------------------------------------------------------
                    */

                    $customer =
                        Customer::updateOrCreate(

                            [

                                'tenant_id' => $tenantId,

                                'nic_number' => $validated[
                                        'customer_nic_number'
                                    ],

                            ],

                            [

                                'name' => $validated[
                                        'customer_name'
                                    ],

                                'phone_1' => $validated[
                                        'customer_number'
                                    ],

                                'email' => $validated[
                                        'customer_email'
                                    ],

                                'address' => $validated[
                                        'customer_address'
                                    ],

                            ]

                        );

                    /*
                    |--------------------------------------------------------------------------
                    | BOOKING
                    |--------------------------------------------------------------------------
                    */

                    $booking =
                        Booking::create([

                            'tenant_id' => $tenantId,

                            'customer_id' => $customer->id,

                            'lawn_type' => $validated[
                                    'lawn_type'
                                ],

                            'event_type' => $validated[
                                    'event_type'
                                ],

                            'booking_date' => $validated[
                                    'booking_date'
                                ],

                            'booking_time' => $validated[
                                    'booking_time'
                                ],

                            'number_of_guests' => $validated[
                                    'number_of_guests'
                                ],

                            'booking_amount' => $bookingAmount,

                            'total_amount' => $subtotal,

                            'tax_amount' => $taxAmount,

                            'discount' => $discount,

                            'grand_total' => $grandTotal,

                            /*
                            |--------------------------------------------------------------------------
                            | ACTUAL PAYMENT = ZERO
                            |--------------------------------------------------------------------------
                            */

                            'advance_amount' => 0,

                            'remaining_amount' => $grandTotal,

                            /*
                            |--------------------------------------------------------------------------
                            | NO ACTUAL PAYMENT METHOD
                            |--------------------------------------------------------------------------
                            */

                            'payment_method' => null,

                            'status' => 'pending',

                            'notes' => $validated[
                                    'notes'
                                ] ?? null,

                        ]);

                    /*
                    |--------------------------------------------------------------------------
                    | BOOKING SERVICES
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $serviceRows as $serviceRow
                    ) {

                        BookingService::create([

                            'booking_id' => $booking->id,

                            'service_type' => $serviceRow[
                                    'service_type'
                                ],

                            'paid_service_id' => $serviceRow[
                                    'paid_service_id'
                                ] ?? null,

                            'free_service_id' => $serviceRow[
                                    'free_service_id'
                                ] ?? null,

                            'quantity' => $serviceRow[
                                    'quantity'
                                ],

                            'price' => $serviceRow[
                                    'price'
                                ],

                            'total' => $serviceRow[
                                    'total'
                                ],

                        ]);

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | MAIN BOOKING INVOICE
                    |--------------------------------------------------------------------------
                    */

                    $invoice =
                        Invoice::create([

                            'tenant_id' => $tenantId,

                            'customer_id' => $customer->id,

                            'booking_id' => $booking->id,

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

                            /*
                        |--------------------------------------------------------------------------
                        | PAY AMOUNT
                        |--------------------------------------------------------------------------
                        |
                        | Customer ko invoice ke mutabiq kitna pay karna hai.
                        |
                        */

                            'pay_amount' => $payAmount,

                            /*
                        |--------------------------------------------------------------------------
                        | ACTUAL PAID
                        |--------------------------------------------------------------------------
                        */

                            'paid_amount' => $paidAmount,

                            /*
                        |--------------------------------------------------------------------------
                        | ACTUAL OUTSTANDING
                        |--------------------------------------------------------------------------
                        */

                            'remaining_amount' => $remainingAmount,

                            'status' => $invoiceStatus,

                            'notes' => null,

                        ]);

                    /*
                    |--------------------------------------------------------------------------
                    | INVOICE ITEM - BOOKING
                    |--------------------------------------------------------------------------
                    */

                    InvoiceItem::create([

                        'invoice_id' => $invoice->id,

                        'description' => 'Lawn Booking - '
                            .$validated[
                                'event_type'
                            ],

                        'quantity' => 1,

                        'unit_cost' => $bookingAmount,

                        'total' => $bookingAmount,

                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | INVOICE ITEMS - SERVICES
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $serviceRows as $serviceRow
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | GET SERVICE
                        |--------------------------------------------------------------------------
                        */

                        if (
                            (
                                $serviceRow[
                                    'service_type'
                                ]
                                ?? 'paid'
                            ) === 'free'
                        ) {

                            $service =
                                FreeService::where(
                                    'tenant_id',
                                    $tenantId
                                )
                                    ->find(
                                        $serviceRow[
                                            'free_service_id'
                                        ]
                                    );

                        } else {

                            $service =
                                Service::where(
                                    'tenant_id',
                                    $tenantId
                                )
                                    ->find(
                                        $serviceRow[
                                            'paid_service_id'
                                        ]
                                    );

                        }

                        if (! $service) {

                            continue;

                        }

                        /*
                        |--------------------------------------------------------------------------
                        | INVOICE SERVICE ITEM
                        |--------------------------------------------------------------------------
                        */

                        InvoiceItem::create([

                            'invoice_id' => $invoice->id,

                            'description' => $service->service_name,

                            'quantity' => $serviceRow[
                                    'quantity'
                                ],

                            'unit_cost' => $serviceRow[
                                    'price'
                                ],

                            'total' => $serviceRow[
                                    'total'
                                ],

                        ]);

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT:
                    |--------------------------------------------------------------------------
                    |
                    | BookingPayment::create()
                    | BookingPaymentReceipt::create()
                    |
                    | YAHAN NAHI HOGA.
                    |
                    | Pay Amount sirf Invoice mein save hogi.
                    |
                    */

                    return $booking;

                }

            );

        /*
        |--------------------------------------------------------------------------
        | LOAD RELATIONS FOR NOTIFICATION
        |--------------------------------------------------------------------------
        */

        $booking->load([

            'customer',

            'tenant',

            'lawnType',

        ]);

        /*
        |--------------------------------------------------------------------------
        | NOTIFY SUPER ADMINS
        |--------------------------------------------------------------------------
        */

        $this->notifySuperAdmins(

            'booking_created',

            [

                'booking_id' => $booking->id,

                'tenant_id' => $tenantId,

                'tenant_name' => $booking->tenant?->owner_name
                    ?? 'Unknown Tenant',

                'customer_name' => $booking->customer?->name
                    ?? 'Unknown Customer',

                'event_type' => $booking->event_type,

                'booking_date' => $booking->booking_date?->format(
                    'Y-m-d'
                ),

                'booking_time' => ucfirst(
                    $booking->booking_time
                ),

                'grand_total' => $booking->grand_total,

                /*
            |--------------------------------------------------------------------------
            | ACTUAL PAYMENT
            |--------------------------------------------------------------------------
            */

                'advance_amount' => 0,

                'remaining_amount' => $booking->grand_total,

                /*
            |--------------------------------------------------------------------------
            | PAY AMOUNT
            |--------------------------------------------------------------------------
            */

                'pay_amount' => $payAmount,

            ]

        );

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'bookings.index'
            )
            ->with(
                'success',
                'Booking created successfully. Invoice generated with Pay Amount.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Booking $booking
    ) {
        $this->checkTenant(
            $booking
        );

        $booking->load([

            'customer',

            'lawnType',

            'bookingServices.paidService',

            'bookingServices.freeService',

            'payments.receipt',

            'invoice.items',

        ]);

        return view(

            'bookings.show',

            compact(
                'booking'
            )

        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Booking $booking
    ) {
        $this->checkTenant(
            $booking
        );

        $tenantId =
            (int) auth()->user()->tenant_id;

        $booking->load([

            'customer',

            'bookingServices.paidService',

            'bookingServices.freeService',

            'payments',

        ]);

        $lawnTypes =
            LawnType::where(
                'tenant_id',
                $tenantId
            )
                ->orderBy(
                    'lawn_type'
                )
                ->get();

        $customers =
            Customer::where(
                'tenant_id',
                $tenantId
            )
                ->orderBy(
                    'name'
                )
                ->get();

        $services =
            Service::where(
                'tenant_id',
                $tenantId
            )
                ->orderBy(
                    'service_name'
                )
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
                ->orderBy(
                    'service_name'
                )
                ->get();

        $settings =
            TenantSetting::where(
                'tenant_id',
                $tenantId
            )->first();

        $taxEnabled =
            (bool) (
                $settings->tax_enabled
                ?? false
            );

        $taxPercent =
            $this->getTaxPercentage(
                $settings
            );

        $paymentMethods = [];

        if ($settings) {

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
                        (string)
                        $configuredPaymentMethods,
                        true
                    ) ?? [];

            }

        }

        return view(

            'bookings.edit',

            compact(

                'booking',

                'lawnTypes',

                'customers',

                'services',

                'freeServices',

                'settings',

                'taxEnabled',

                'taxPercent',

                'paymentMethods'

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
        Booking $booking
    ) {
        $this->checkTenant(
            $booking
        );

        $tenantId =
            (int) auth()->user()->tenant_id;

        $validated =
            $request->validate([

                'customer_id' => [

                    'required',

                    Rule::exists(
                        'customers',
                        'id'
                    )->where(
                        fn ($query) => $query->where(
                            'tenant_id',
                            $tenantId
                        )
                    ),

                ],

                'lawn_type' => [

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

                'booking_time' => [

                    'required',

                    Rule::in([
                        'day',
                        'night',
                    ]),

                ],

                'booking_date' => [

                    'required',
                    'date',
                    'after_or_equal:today',

                ],

                'number_of_guests' => [

                    'required',
                    'integer',
                    'min:1',

                ],

                'booking_amount' => [

                    'required',
                    'numeric',
                    'min:0',

                ],

                'discount' => [

                    'nullable',
                    'numeric',
                    'min:0',

                ],

                /*
                |--------------------------------------------------------------------------
                | PAID SERVICES
                |--------------------------------------------------------------------------
                */

                'paid_services' => [

                    'nullable',
                    'array',

                ],

                'paid_services.*.service_id' => [

                    'required',

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

                'paid_services.*.quantity' => [

                    'required',
                    'integer',
                    'min:1',

                ],

                /*
                |--------------------------------------------------------------------------
                | FREE SERVICES
                |--------------------------------------------------------------------------
                */

                'free_services' => [

                    'nullable',
                    'array',

                ],

                'free_services.*.service_id' => [

                    'required',

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

                'free_services.*.quantity' => [

                    'required',
                    'integer',
                    'min:1',

                ],

                /*
                |--------------------------------------------------------------------------
                | OLD SERVICES
                |--------------------------------------------------------------------------
                */

                'services' => [

                    'nullable',
                    'array',

                ],

                'services.*.service_id' => [

                    'required',

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

                'services.*.quantity' => [

                    'required',
                    'integer',
                    'min:1',

                ],

                /*
                |--------------------------------------------------------------------------
                | PAYMENT
                |--------------------------------------------------------------------------
                */

                'payment_method' => [

                    'nullable',

                    Rule::in([
                        'cash',
                        'bank_transfer',
                        'card',
                        'online',
                        'cheque',
                    ]),

                ],

                'advance_amount' => [

                    'nullable',
                    'numeric',
                    'min:0',

                ],

                'status' => [

                    'nullable',

                    Rule::in([
                        'pending',
                        'confirmed',
                        'cancelled',
                    ]),

                ],

                'notes' => [

                    'nullable',
                    'string',
                    'max:2000',

                ],

            ]);

        /*
        |--------------------------------------------------------------------------
        | DUPLICATE SLOT
        |--------------------------------------------------------------------------
        */

        $duplicate =
            Booking::where(
                'tenant_id',
                $tenantId
            )
                ->where(
                    'lawn_type',
                    $validated['lawn_type']
                )
                ->whereDate(
                    'booking_date',
                    $validated['booking_date']
                )
                ->where(
                    'booking_time',
                    $validated['booking_time']
                )
                ->where(
                    'id',
                    '!=',
                    $booking->id
                )
                ->exists();

        if ($duplicate) {

            return back()
                ->withInput()
                ->withErrors([
                    'booking_date' => 'This lawn is already booked for the selected date and time.',
                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | SETTINGS / TAX
        |--------------------------------------------------------------------------
        */

        $settings =
            TenantSetting::where(
                'tenant_id',
                $tenantId
            )->first();

        $taxEnabled =
            (bool) (
                $settings->tax_enabled
                ?? false
            );

        $taxPercent =
            $this->getTaxPercentage(
                $settings
            );

        /*
        |--------------------------------------------------------------------------
        | BOOKING AMOUNT
        |--------------------------------------------------------------------------
        */

        $bookingAmount =
            (float) (
                $validated['booking_amount']
            );

        /*
        |--------------------------------------------------------------------------
        | SERVICES
        |--------------------------------------------------------------------------
        */

        $serviceRows = [];

        $serviceTotal = 0;

        /*
        |--------------------------------------------------------------------------
        | PAID SERVICES
        |--------------------------------------------------------------------------
        */

        foreach (
            $validated['paid_services'] ?? [] as $serviceData
        ) {

            $service =
                Service::where(
                    'tenant_id',
                    $tenantId
                )
                    ->findOrFail(
                        $serviceData['service_id']
                    );

            $quantity =
                (int) (
                    $serviceData['quantity']
                );

            $price =
                (float) (
                    $service->amount
                );

            $lineTotal =
                $price * $quantity;

            $serviceTotal +=
                $lineTotal;

            $serviceRows[] = [

                'service_type' => 'paid',

                'paid_service_id' => $service->id,

                'free_service_id' => null,

                'quantity' => $quantity,

                'price' => $price,

                'total' => $lineTotal,

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | FREE SERVICES
        |--------------------------------------------------------------------------
        */

        foreach (
            $validated['free_services'] ?? [] as $serviceData
        ) {

            $service =
                FreeService::where(
                    'tenant_id',
                    $tenantId
                )
                    ->where(
                        'status',
                        true
                    )
                    ->findOrFail(
                        $serviceData['service_id']
                    );

            $quantity =
                (int) (
                    $serviceData['quantity']
                );

            $serviceRows[] = [

                'service_type' => 'free',

                'paid_service_id' => null,

                'free_service_id' => $service->id,

                'quantity' => $quantity,

                'price' => 0,

                'total' => 0,

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | OLD SERVICES FORMAT
        |--------------------------------------------------------------------------
        */

        if (
            empty(
                $validated['paid_services']
            )
            &&
            empty(
                $validated['free_services']
            )
        ) {

            foreach (
                $validated['services'] ?? [] as $serviceData
            ) {

                $service =
                    Service::where(
                        'tenant_id',
                        $tenantId
                    )
                        ->findOrFail(
                            $serviceData['service_id']
                        );

                $quantity =
                    (int) (
                        $serviceData['quantity']
                    );

                $price =
                    (float) (
                        $service->amount
                    );

                $lineTotal =
                    $price * $quantity;

                $serviceTotal +=
                    $lineTotal;

                $serviceRows[] = [

                    'service_type' => 'paid',

                    'paid_service_id' => $service->id,

                    'free_service_id' => null,

                    'quantity' => $quantity,

                    'price' => $price,

                    'total' => $lineTotal,

                ];

            }

        }

        /*
        |--------------------------------------------------------------------------
        | SUBTOTAL
        |--------------------------------------------------------------------------
        */

        $subtotal =
            $bookingAmount
            + $serviceTotal;

        /*
        |--------------------------------------------------------------------------
        | DISCOUNT
        |--------------------------------------------------------------------------
        */

        $discount =
            (float) (
                $validated['discount']
                ?? 0
            );

        if (
            $discount >
            $subtotal
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'discount' => 'Discount cannot be greater than subtotal.',
                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | TAX
        |--------------------------------------------------------------------------
        */

        $taxableAmount =
            max(
                $subtotal
                - $discount,
                0
            );

        $taxAmount =
            (
                $taxableAmount
                * $taxPercent
            ) / 100;

        /*
        |--------------------------------------------------------------------------
        | GRAND TOTAL
        |--------------------------------------------------------------------------
        */

        $grandTotal =
            $taxableAmount
            + $taxAmount;

        /*
        |--------------------------------------------------------------------------
        | EXISTING ACTUAL PAYMENTS
        |--------------------------------------------------------------------------
        |
        | Existing recorded installments are authoritative.
        | Booking edit must not erase them.
        |--------------------------------------------------------------------------
        */

        $existingPaymentsTotal =
            (float) $booking
                ->payments()
                ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | ADVANCE / PAID TOTAL
        |--------------------------------------------------------------------------
        */

        if (
            $existingPaymentsTotal > 0
        ) {

            $advanceAmount =
                $existingPaymentsTotal;

        } else {

            $advanceAmount =
                (float) (
                    $validated['advance_amount']
                    ?? 0
                );

        }

        /*
        |--------------------------------------------------------------------------
        | PAID CANNOT EXCEED NEW GRAND TOTAL
        |--------------------------------------------------------------------------
        */

        if (
            $advanceAmount >
            $grandTotal
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'booking_amount' => 'The new grand total cannot be less than the amount already paid.',
                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | REMAINING
        |--------------------------------------------------------------------------
        */

        $remainingAmount =
            max(
                $grandTotal
                - $advanceAmount,
                0
            );

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $booking,
                $validated,
                $bookingAmount,
                $subtotal,
                $discount,
                $taxAmount,
                $grandTotal,
                $advanceAmount,
                $remainingAmount,
                $serviceRows,
                $tenantId
            ) {

                /*
                |--------------------------------------------------------------------------
                | LOCK BOOKING
                |--------------------------------------------------------------------------
                */

                $lockedBooking =
                    Booking::where(
                        'tenant_id',
                        $tenantId
                    )
                        ->where(
                            'id',
                            $booking->id
                        )
                        ->lockForUpdate()
                        ->firstOrFail();

                /*
                |--------------------------------------------------------------------------
                | BOOKING
                |--------------------------------------------------------------------------
                */

                $lockedBooking->update([

                    'customer_id' => $validated[
                            'customer_id'
                        ],

                    'lawn_type' => $validated[
                            'lawn_type'
                        ],

                    'event_type' => $validated[
                            'event_type'
                        ],

                    'booking_time' => $validated[
                            'booking_time'
                        ],

                    'booking_date' => $validated[
                            'booking_date'
                        ],

                    'number_of_guests' => $validated[
                            'number_of_guests'
                        ],

                    'booking_amount' => $bookingAmount,

                    'total_amount' => $subtotal,

                    'tax_amount' => $taxAmount,

                    'discount' => $discount,

                    'grand_total' => $grandTotal,

                    'advance_amount' => $advanceAmount,

                    'remaining_amount' => $remainingAmount,

                    'payment_method' => $validated[
                            'payment_method'
                        ]
                        ?? $lockedBooking->payment_method,

                    'status' => $validated[
                            'status'
                        ]
                        ?? $lockedBooking->status,

                    'notes' => $validated[
                            'notes'
                        ]
                        ?? null,

                ]);

                /*
                |--------------------------------------------------------------------------
                | SERVICES
                |--------------------------------------------------------------------------
                */

                $lockedBooking
                    ->bookingServices()
                    ->delete();

                foreach (
                    $serviceRows as $serviceRow
                ) {

                    BookingService::create([

                        'booking_id' => $lockedBooking->id,

                        'service_type' => $serviceRow[
                                'service_type'
                            ],

                        'paid_service_id' => $serviceRow[
                                'paid_service_id'
                            ] ?? null,

                        'free_service_id' => $serviceRow[
                                'free_service_id'
                            ] ?? null,

                        'quantity' => $serviceRow[
                                'quantity'
                            ],

                        'price' => $serviceRow[
                                'price'
                            ],

                        'total' => $serviceRow[
                                'total'
                            ],

                    ]);

                }

                /*
                |--------------------------------------------------------------------------
                | MAIN BOOKING INVOICE
                |--------------------------------------------------------------------------
                */

                $invoice =
                    Invoice::where(
                        'tenant_id',
                        $tenantId
                    )
                        ->where(
                            'booking_id',
                            $lockedBooking->id
                        )
                        ->where(
                            'status',
                            '!=',
                            'cancelled'
                        )
                        ->latest(
                            'id'
                        )
                        ->lockForUpdate()
                        ->first();

                /*
                |--------------------------------------------------------------------------
                | INVOICE STATUS
                |--------------------------------------------------------------------------
                */

                $invoiceStatus =
                    $advanceAmount <= 0

                        ? 'unpaid'

                        : (
                            $remainingAmount <= 0

                                ? 'paid'

                                : 'partial'
                        );

                /*
                |--------------------------------------------------------------------------
                | CREATE INVOICE IF MISSING
                |--------------------------------------------------------------------------
                */

                if (! $invoice) {

                    $invoice =
                        Invoice::create([

                            'tenant_id' => $tenantId,

                            'customer_id' => $lockedBooking->customer_id,

                            'booking_id' => $lockedBooking->id,

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

                            'status' => $invoiceStatus,

                            'notes' => null,

                        ]);

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE INVOICE
                    |--------------------------------------------------------------------------
                    |
                    | IMPORTANT:
                    | Existing payments are preserved.
                    |--------------------------------------------------------------------------
                    */

                    $invoice->update([

                        'customer_id' => $lockedBooking->customer_id,

                        'subtotal' => $subtotal,

                        'discount' => $discount,

                        'tax' => $taxAmount,

                        'additional_charges' => $invoice->additional_charges
                            ?? 0,

                        'grand_total' => $grandTotal,

                        'paid_amount' => $advanceAmount,

                        'remaining_amount' => $remainingAmount,

                        'status' => $invoiceStatus,

                    ]);

                    InvoiceItem::where(
                        'invoice_id',
                        $invoice->id
                    )->delete();

                }

                /*
                |--------------------------------------------------------------------------
                | INVOICE BOOKING ITEM
                |--------------------------------------------------------------------------
                */

                InvoiceItem::create([

                    'invoice_id' => $invoice->id,

                    'description' => 'Lawn Booking - '
                        .$validated[
                            'event_type'
                        ],

                    'quantity' => 1,

                    'unit_cost' => $bookingAmount,

                    'total' => $bookingAmount,

                ]);

                /*
                |--------------------------------------------------------------------------
                | INVOICE SERVICE ITEMS
                |--------------------------------------------------------------------------
                */

                foreach (
                    $serviceRows as $serviceRow
                ) {

                    $service =
                        (
                            $serviceRow[
                                'service_type'
                            ]
                            ?? 'paid'
                        ) === 'free'

                        ? FreeService::where(
                            'tenant_id',
                            $tenantId
                        )
                            ->find(
                                $serviceRow[
                                    'free_service_id'
                                ]
                            )

                        : Service::where(
                            'tenant_id',
                            $tenantId
                        )
                            ->find(
                                $serviceRow[
                                    'paid_service_id'
                                ]
                            );

                    if (! $service) {
                        continue;
                    }

                    InvoiceItem::create([

                        'invoice_id' => $invoice->id,

                        'description' => $service->service_name,

                        'quantity' => $serviceRow[
                                'quantity'
                            ],

                        'unit_cost' => $serviceRow[
                                'price'
                            ],

                        'total' => $serviceRow[
                                'total'
                            ],

                    ]);

                }

            }
        );

        return redirect()
            ->route(
                'bookings.index'
            )
            ->with(
                'success',
                'Booking updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Booking $booking
    ) {
        $this->checkTenant(
            $booking
        );

        $booking->delete();

        return redirect()
            ->route(
                'bookings.index'
            )
            ->with(
                'success',
                'Booking deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CALENDAR
    |--------------------------------------------------------------------------
    */

    public function calendar()
    {
        return view(
            'bookings.calendar'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CALENDAR EVENTS
    |--------------------------------------------------------------------------
    */

    public function calendarEvents(
        Request $request
    ) {
        $tenantId =
            (int) auth()->user()->tenant_id;

        $bookings =
            Booking::with([

                'customer',

                'lawnType',

                'bookingServices.paidService',

                'bookingServices.freeService',

                'payments',

            ])
                ->where(
                    'tenant_id',
                    $tenantId
                )
                ->where(
                    'status',
                    '!=',
                    'cancelled'
                )
                ->latest(
                    'booking_date'
                )
                ->latest(
                    'id'
                )
                ->get();

        $events =
            $bookings->map(
                function ($booking) {

                    $servicesTotal =
                        $booking
                            ->bookingServices
                            ->sum(
                                fn ($item) => (float) $item->price
                                    *
                                    (int) $item->quantity
                            );

                    return [

                        'id' => $booking->id,

                        'title' => $booking->customer->name
                            .' - '
                            .$booking->event_type,

                        'start' => $booking->booking_date
                            ->format('Y-m-d'),

                        'allDay' => true,

                        'customer_name' => $booking->customer->name,

                        'customer_number' => $booking->customer->phone_1,

                        'customer_nic_number' => $booking->customer->nic_number,

                        'customer_address' => $booking->customer->address,

                        'event_type' => $booking->event_type,

                        'booking_time' => ucfirst(
                            $booking->booking_time
                        ),

                        'guests' => $booking->number_of_guests,

                        'status' => ucfirst(
                            $booking->status
                        ),

                        'booking_price' => $booking->booking_amount,

                        'services_total' => $servicesTotal,

                        'booking_amount' => $booking->booking_amount,

                        'tax_amount' => $booking->tax_amount,

                        'discount' => $booking->discount,

                        'grand_total' => $booking->grand_total,

                        'total_amount' => $booking->total_amount,

                        'advance_amount' => $booking->advance_amount,

                        'remaining_amount' => $booking->remaining_amount,

                        'payment_method' => $booking->payment_method,

                    ];

                }
            );

        return response()->json(
            $events
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK AVAILABILITY
    |--------------------------------------------------------------------------
    */

    public function checkAvailability(
        Request $request
    ) {
        $request->validate([

            'booking_date' => [
                'required',
                'date',
            ],

            'lawn_type' => [
                'required',
            ],

            'booking_time' => [
                'required',

                'in:day,night',
            ],

        ]);

        $tenantId =
            (int) auth()->user()->tenant_id;

        $exists =
            Booking::where(
                'tenant_id',
                $tenantId
            )
                ->whereDate(
                    'booking_date',
                    $request->booking_date
                )
                ->where(
                    'lawn_type',
                    $request->lawn_type
                )
                ->where(
                    'booking_time',
                    $request->booking_time
                )
                ->exists();

        return response()->json([

            'available' => ! $exists,

            'message' => $exists

                    ? 'This lawn is already booked for the selected date and time.'

                    : 'This lawn is available for booking.',

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT HISTORY
    |--------------------------------------------------------------------------
    */

    public function paymentHistory(
        $id
    ) {
        $tenantId =
            (int) auth()->user()->tenant_id;

        $booking =
            Booking::with([

                'customer',

                'payments.receipt',

                'lawnType',

                'bookingServices.paidService',

                'bookingServices.freeService',

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
                    (string)
                    $installmentOptions,
                    true
                ) ?? [];

        }

        $installmentOptions =
            array_values(

                array_unique(

                    array_filter(

                        array_map(

                            fn ($option) => trim(
                                (string)
                                $option
                            ),

                            $installmentOptions

                        ),

                        fn ($option) => $option !== ''

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

    /*
    |--------------------------------------------------------------------------
    | STORE INSTALLMENT
    |--------------------------------------------------------------------------
    */

    public function storeInstallment(
        Request $request,
        $bookingId
    ) {
        $tenantId =
            (int) auth()->user()->tenant_id;

        /*
        |--------------------------------------------------------------------------
        | INSTALLMENT OPTIONS
        |--------------------------------------------------------------------------
        */

        $installmentOptions =
            $this->getTenantInstallmentOptions(
                $tenantId
            );

        if (
            empty(
                $installmentOptions
            )
        ) {

            return back()
                ->withInput()
                ->withErrors([

                    'installment_type' => 'No installment options are configured for this tenant. Please add them in Tenant Settings.',

                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate([

                'amount' => [

                    'required',
                    'numeric',
                    'min:0.01',

                ],

                'installment_type' => [

                    'required',
                    'string',
                    'max:100',

                    Rule::in(
                        $installmentOptions
                    ),

                ],

                'payment_method' => [

                    'required',

                    Rule::in([

                        'cash',
                        'bank_transfer',
                        'card',
                        'online',
                        'cheque',

                    ]),

                ],

                'paid_at' => [

                    'required',
                    'date',

                ],

                'transaction_reference' => [

                    'nullable',
                    'string',
                    'max:255',

                ],

                'notes' => [

                    'nullable',
                    'string',
                    'max:1000',

                ],

            ]);

        /*
        |--------------------------------------------------------------------------
        | AMOUNT
        |--------------------------------------------------------------------------
        */

        $amount =
            (float) $validated[
                'amount'
            ];

        /*
        |--------------------------------------------------------------------------
        | ALL PROCESS ATOMICALLY
        |--------------------------------------------------------------------------
        */

        $receipt =
            DB::transaction(

                function () use (

                    $tenantId,

                    $bookingId,

                    $validated,

                    $amount

                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | LOCK BOOKING
                    |--------------------------------------------------------------------------
                    */

                    $booking =
                        Booking::where(
                            'tenant_id',
                            $tenantId
                        )
                            ->where(
                                'id',
                                $bookingId
                            )
                            ->lockForUpdate()
                            ->firstOrFail();

                    /*
                    |--------------------------------------------------------------------------
                    | CURRENT BOOKING VALUES
                    |--------------------------------------------------------------------------
                    */

                    $bookingGrandTotal =
                        (float)
                        $booking->grand_total;

                    $paidBefore =
                        (float)
                        $booking->advance_amount;

                    $remainingBefore =
                        (float)
                        $booking->remaining_amount;

                    /*
                    |--------------------------------------------------------------------------
                    | BOOKING PAYMENT LIMIT
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $amount >
                        $remainingBefore
                    ) {

                        throw ValidationException::withMessages([
                            'amount' => 'Payment cannot be greater than booking remaining amount of '
                                .
                                number_format(
                                    $remainingBefore,
                                    2
                                )
                                .
                                '.',

                        ]);

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | MAIN BOOKING INVOICE
                    |--------------------------------------------------------------------------
                    */

                    $invoice =
                        Invoice::where(
                            'tenant_id',
                            $tenantId
                        )
                            ->where(
                                'booking_id',
                                $booking->id
                            )
                            ->where(
                                'status',
                                '!=',
                                'cancelled'
                            )
                            ->latest(
                                'id'
                            )
                            ->lockForUpdate()
                            ->first();

                    /*
                    |--------------------------------------------------------------------------
                    | INVOICE LIMIT
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $invoice
                    ) {

                        $invoiceRemaining =
                            max(

                                (float)
                                $invoice->remaining_amount,

                                0

                            );

                        if (
                            $amount >
                            $invoiceRemaining
                        ) {

                            throw ValidationException::withMessages([
                                'amount' => 'Payment cannot be greater than invoice remaining amount of '
                                    .
                                    number_format(
                                        $invoiceRemaining,
                                        2
                                    )
                                    .
                                    '.',

                            ]);

                        }

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | NEW BOOKING BALANCE
                    |--------------------------------------------------------------------------
                    */

                    $newTotalPaid =
                        $paidBefore
                        + $amount;

                    $newRemaining =
                        max(

                            $bookingGrandTotal
                            - $newTotalPaid,

                            0

                        );

                    /*
                    |--------------------------------------------------------------------------
                    | PAYMENT STATUS
                    |--------------------------------------------------------------------------
                    */

                    $paymentStatus =
                        $newRemaining <= 0

                            ? 'paid'

                            : 'partial';

                    /*
                    |--------------------------------------------------------------------------
                    | CREATE BOOKING PAYMENT
                    |--------------------------------------------------------------------------
                    */

                    $payment =
                        BookingPayment::create([

                            'tenant_id' => $tenantId,

                            'booking_id' => $booking->id,

                            'amount' => $amount,

                            'installment_type' => $validated[
                                    'installment_type'
                                ],

                            'payment_method' => $validated[
                                    'payment_method'
                                ],

                            'payment_status' => $paymentStatus,

                            'transaction_reference' => $validated[
                                    'transaction_reference'
                                ] ?? null,

                            'notes' => $validated[
                                    'notes'
                                ] ?? null,

                            'paid_at' => $validated[
                                    'paid_at'
                                ],

                        ]);

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE BOOKING
                    |--------------------------------------------------------------------------
                    */

                    $booking->update([

                        'advance_amount' => $newTotalPaid,

                        'remaining_amount' => $newRemaining,

                        'payment_method' => $validated[
                                'payment_method'
                            ],

                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE MAIN BOOKING INVOICE
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $invoice
                    ) {

                        $invoicePaidBefore =
                            (float)
                            $invoice->paid_amount;

                        $invoiceGrandTotal =
                            (float)
                            $invoice->grand_total;

                        $newInvoicePaid =
                            $invoicePaidBefore
                            + $amount;

                        $newInvoiceRemaining =
                            max(

                                $invoiceGrandTotal
                                - $newInvoicePaid,

                                0

                            );

                        /*
                        |--------------------------------------------------------------------------
                        | INVOICE STATUS
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $newInvoiceRemaining <= 0
                        ) {

                            $invoiceStatus =
                                'paid';

                        } elseif (
                            $newInvoicePaid > 0
                        ) {

                            $invoiceStatus =
                                'partial';

                        } else {

                            $invoiceStatus =
                                'unpaid';

                        }

                        $invoice->update([

                            'paid_amount' => $newInvoicePaid,

                            'remaining_amount' => $newInvoiceRemaining,

                            'status' => $invoiceStatus,

                        ]);

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | COMPLETE PAYMENT HISTORY SNAPSHOT
                    |--------------------------------------------------------------------------
                    */

                    $paymentHistory =
                        BookingPayment::where(
                            'tenant_id',
                            $tenantId
                        )
                            ->where(
                                'booking_id',
                                $booking->id
                            )
                            ->orderBy(
                                'paid_at'
                            )
                            ->orderBy(
                                'id'
                            )
                            ->get()
                            ->map(

                                function (
                                    $historyPayment
                                ) {

                                    return [

                                        'payment_id' => $historyPayment->id,

                                        'date' => $historyPayment->paid_at

                                                ? Carbon::parse(
                                                    $historyPayment->paid_at
                                                )->format(
                                                    'd-m-Y'
                                                )

                                                : null,

                                        'installment_type' => $historyPayment
                                            ->installment_type
                                            ?: 'Advance',

                                        'payment_method' => $historyPayment
                                            ->payment_method,

                                        'transaction_reference' => $historyPayment
                                            ->transaction_reference,

                                        'amount' => (float)
                                            $historyPayment->amount,

                                    ];

                                }

                            )
                            ->values()
                            ->all();

                    /*
                    |--------------------------------------------------------------------------
                    | CREATE NEW PAYMENT RECEIPT
                    |--------------------------------------------------------------------------
                    */

                    $receipt =
                        BookingPaymentReceipt::create([

                            'tenant_id' => $tenantId,

                            'booking_id' => $booking->id,

                            'customer_id' => $booking->customer_id,

                            'booking_payment_id' => $payment->id,

                            'receipt_number' => 'TEMP-'.
                                uniqid(),

                            'receipt_date' => $validated[
                                    'paid_at'
                                ],

                            'booking_total' => $bookingGrandTotal,

                            'paid_before' => $paidBefore,

                            'current_payment' => $amount,

                            'total_paid_after' => $newTotalPaid,

                            'remaining_after' => $newRemaining,

                            'payment_method' => $payment->payment_method,

                            'installment_type' => $payment->installment_type,

                            'transaction_reference' => $payment->transaction_reference,

                            'payment_history' => $paymentHistory,

                            'notes' => $payment->notes,

                        ]);

                    /*
                    |--------------------------------------------------------------------------
                    | FINAL PAYMENT RECEIPT NUMBER
                    |--------------------------------------------------------------------------
                    */

                    $receipt->update([

                        'receipt_number' => 'PR-'.
                            str_pad(

                                $receipt->id,

                                6,

                                '0',

                                STR_PAD_LEFT

                            ),

                    ]);

                    return $receipt;

                }

            );

        /*
        |--------------------------------------------------------------------------
        | REFRESH BOOKING
        |--------------------------------------------------------------------------
        */

        $booking =
            Booking::with([

                'customer',

                'tenant',

                'lawnType',

            ])
                ->where(
                    'tenant_id',
                    $tenantId
                )
                ->findOrFail(
                    $bookingId
                );

        $booking->refresh();

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATION
        |--------------------------------------------------------------------------
        */

        $this->notifySuperAdmins(

            'payment_received',

            [

                'booking_id' => $booking->id,

                'tenant_id' => $tenantId,

                'tenant_name' => $booking->tenant?->owner_name
                    ?? 'Unknown Tenant',

                'customer_name' => $booking->customer?->name
                    ?? 'Unknown Customer',

                'event_type' => $booking->event_type,

                'booking_date' => $booking->booking_date?->format(
                    'Y-m-d'
                ),

                'payment_amount' => $amount,

                'payment_method' => ucfirst(
                    str_replace(
                        '_',
                        ' ',
                        $validated[
                            'payment_method'
                        ]
                    )
                ),

                'paid_at' => $validated[
                        'paid_at'
                    ],

                'remaining_amount' => $booking->remaining_amount,

                'transaction_reference' => $validated[
                        'transaction_reference'
                    ] ?? null,

            ]

        );

        /*
        |--------------------------------------------------------------------------
        | REDIRECT TO RECEIPT
        |--------------------------------------------------------------------------
        */

        return redirect()->route(

            'bookings.payment.receipt.print',

            $receipt->id

        );
    }

    /*
    |--------------------------------------------------------------------------
    | PRINT PAYMENT RECEIPT
    |--------------------------------------------------------------------------
    */

    public function printPaymentReceipt(
        $receiptId
    ) {
        $tenantId =
            (int) auth()->user()->tenant_id;

        $receipt =
            BookingPaymentReceipt::with([

                'booking.customer',

                'booking.lawnType',

                'customer',

                'bookingPayment',

            ])
                ->where(
                    'tenant_id',
                    $tenantId
                )
                ->findOrFail(
                    $receiptId
                );

        $settings =
            TenantSetting::where(
                'tenant_id',
                $tenantId
            )->first();

        return view(

            'bookings.payment_receipt',

            compact(

                'receipt',

                'settings'

            )

        );
    }

    /*
    |--------------------------------------------------------------------------
    | CANCEL
    |--------------------------------------------------------------------------
    */

    public function cancel(
        Booking $booking
    ) {
        $this->checkTenant(
            $booking
        );

        $booking->load([

            'customer',

            'tenant',

            'lawnType',

        ]);

        $notificationData = [

            'booking_id' => $booking->id,

            'tenant_id' => $booking->tenant_id,

            'tenant_name' => $booking->tenant?->owner_name
                ?? 'Unknown Tenant',

            'customer_name' => $booking->customer?->name
                ?? 'Unknown Customer',

            'event_type' => $booking->event_type,

            'booking_date' => $booking->booking_date?->format(
                'Y-m-d'
            ),

            'booking_time' => ucfirst(
                $booking->booking_time
            ),

            'grand_total' => $booking->grand_total,

            'advance_amount' => $booking->advance_amount,

            'remaining_amount' => $booking->remaining_amount,

            'cancelled_at' => now()->format(
                'Y-m-d H:i:s'
            ),

        ];

        DB::transaction(

            function () use (
                $booking
            ) {

                CancelledBooking::create([

                    'tenant_id' => $booking->tenant_id,

                    'booking_id' => $booking->id,

                    'customer_id' => $booking->customer_id,

                    'lawn_type' => $booking->lawn_type,

                    'event_type' => $booking->event_type,

                    'booking_date' => $booking->booking_date,

                    'booking_time' => $booking->booking_time,

                    'number_of_guests' => $booking->number_of_guests,

                    'booking_amount' => $booking->booking_amount,

                    'total_amount' => $booking->total_amount,

                    'tax_amount' => $booking->tax_amount,

                    'discount' => $booking->discount,

                    'grand_total' => $booking->grand_total,

                    'advance_amount' => $booking->advance_amount,

                    'remaining_amount' => $booking->remaining_amount,

                    'payment_method' => $booking->payment_method,

                    'status' => 'cancelled',

                    'notes' => $booking->notes,

                ]);

                $booking->delete();

            }

        );

        $this->notifySuperAdmins(

            'booking_cancelled',

            $notificationData

        );

        return redirect()
            ->route(
                'bookings.index'
            )
            ->with(
                'success',
                'Booking cancelled successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CANCELLED BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function cancelledBookings(
        Request $request
    ) {
        $tenantId =
            (int) auth()->user()->tenant_id;

        $query =
            CancelledBooking::with([

                'customer',

                'lawnType',

            ])
                ->where(
                    'tenant_id',
                    $tenantId
                );

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'search'
            )
        ) {

            $search =
                trim(
                    $request->search
                );

            $query->where(

                function (
                    $q
                ) use (
                    $search
                ) {

                    $q->where(
                        'event_type',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'booking_time',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'payment_method',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'status',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'cancellation_reason',
                            'like',
                            "%{$search}%"
                        );

                    if (
                        is_numeric(
                            $search
                        )
                    ) {

                        $q->orWhere(
                            'booking_id',
                            $search
                        );

                        $q->orWhere(
                            'number_of_guests',
                            $search
                        );

                        $q->orWhere(
                            'grand_total',
                            'like',
                            "%{$search}%"
                        );

                        $q->orWhere(
                            'advance_amount',
                            'like',
                            "%{$search}%"
                        );

                        $q->orWhere(
                            'remaining_amount',
                            'like',
                            "%{$search}%"
                        );

                    }

                    $q->orWhereHas(

                        'customer',

                        function (
                            $customer
                        ) use (
                            $search
                        ) {

                            $customer

                                ->where(
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
                                )

                                ->orWhere(
                                    'nic_number',
                                    'like',
                                    "%{$search}%"
                                )

                                ->orWhere(
                                    'address',
                                    'like',
                                    "%{$search}%"
                                );

                        }

                    );

                    $q->orWhereHas(

                        'lawnType',

                        function (
                            $lawn
                        ) use (
                            $search
                        ) {

                            $lawn->where(
                                'lawn_type',
                                'like',
                                "%{$search}%"
                            );

                        }

                    );

                }

            );

        }

        /*
        |--------------------------------------------------------------------------
        | DATE FILTERS
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'booking_date'
            )
        ) {

            $query->whereDate(
                'booking_date',
                $request->booking_date
            );

        }

        if (
            $request->filled(
                'cancelled_date'
            )
        ) {

            $query->whereDate(
                'cancelled_at',
                $request->cancelled_date
            );

        }

        if (
            $request->filled(
                'booking_time'
            )
        ) {

            $query->where(
                'booking_time',
                $request->booking_time
            );

        }

        if (
            $request->filled(
                'payment_method'
            )
        ) {

            $query->where(
                'payment_method',
                $request->payment_method
            );

        }

        if (
            $request->filled(
                'status'
            )
        ) {

            $query->where(
                'status',
                $request->status
            );

        }

        if (
            $request->filled(
                'lawn_type'
            )
        ) {

            $query->where(
                'lawn_type',
                $request->lawn_type
            );

        }

        /*
        |--------------------------------------------------------------------------
        | RESULTS
        |--------------------------------------------------------------------------
        */

        $cancelledBookings =
            $query
                ->latest(
                    'cancelled_at'
                )
                ->latest(
                    'id'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | LAWN TYPES
        |--------------------------------------------------------------------------
        */

        $lawnTypes =
            LawnType::where(
                'tenant_id',
                $tenantId
            )
                ->orderBy(
                    'lawn_type'
                )
                ->get();

        return view(

            'bookings.cancelled',

            compact(

                'cancelledBookings',

                'lawnTypes'

            )

        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function superAdminBookings(
        Request $request
    ) {
        $query =
            Booking::with([

                'tenant',

                'customer',

                'lawnType',

                'bookingServices.paidService',

                'bookingServices.freeService',

            ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'search'
            )
        ) {

            $search =
                trim(
                    $request->search
                );

            $query->where(

                function (
                    $q
                ) use (
                    $search
                ) {

                    $q->where(
                        'event_type',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'booking_time',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'payment_method',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'status',
                            'like',
                            "%{$search}%"
                        );

                    /*
                    | CUSTOMER
                    */

                    $q->orWhereHas(

                        'customer',

                        function (
                            $customer
                        ) use (
                            $search
                        ) {

                            $customer

                                ->where(
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
                                )

                                ->orWhere(
                                    'nic_number',
                                    'like',
                                    "%{$search}%"
                                );

                        }

                    );

                    /*
                    | TENANT
                    */

                    $q->orWhereHas(

                        'tenant',

                        function (
                            $tenant
                        ) use (
                            $search
                        ) {

                            $tenant

                                ->where(
                                    'owner_name',
                                    'like',
                                    "%{$search}%"
                                )

                                ->orWhere(
                                    'business_name',
                                    'like',
                                    "%{$search}%"
                                );

                        }

                    );

                    /*
                    | LAWN
                    */

                    $q->orWhereHas(

                        'lawnType',

                        function (
                            $lawn
                        ) use (
                            $search
                        ) {

                            $lawn->where(
                                'lawn_type',
                                'like',
                                "%{$search}%"
                            );

                        }

                    );

                }

            );

        }

        /*
        |--------------------------------------------------------------------------
        | TENANT FILTER
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'tenant_id'
            )
        ) {

            $query->where(
                'tenant_id',
                $request->tenant_id
            );

        }

        /*
        |--------------------------------------------------------------------------
        | DATE FILTER
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'booking_date'
            )
        ) {

            $query->whereDate(
                'booking_date',
                $request->booking_date
            );

        }

        /*
        |--------------------------------------------------------------------------
        | TIME FILTER
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'booking_time'
            )
        ) {

            $query->where(
                'booking_time',
                $request->booking_time
            );

        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT METHOD
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'payment_method'
            )
        ) {

            $query->where(
                'payment_method',
                $request->payment_method
            );

        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'status'
            )
        ) {

            $query->where(
                'status',
                $request->status
            );

        }

        /*
        |--------------------------------------------------------------------------
        | LAWN
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'lawn_type'
            )
        ) {

            $query->where(
                'lawn_type',
                $request->lawn_type
            );

        }

        /*
        |--------------------------------------------------------------------------
        | BOOKINGS
        |--------------------------------------------------------------------------
        */

        $bookings =
            $query
                ->latest(
                    'booking_date'
                )
                ->latest(
                    'id'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | TENANT SUMMARY
        |--------------------------------------------------------------------------
        */

        $tenantBookings =
            $bookings
                ->groupBy(
                    'tenant_id'
                )
                ->map(

                    function (
                        $tenantBookings
                    ) {

                        $tenant =
                            $tenantBookings
                                ->first()
                                ->tenant;

                        return [

                            'tenant_id' => $tenant?->id,

                            'tenant_name' => $tenant?->owner_name
                                ?? 'N/A',

                            'banquet_name' => $tenant?->business_name
                                ?? 'N/A',

                            'total_bookings' => $tenantBookings->count(),

                        ];

                    }

                )
                ->values();

        /*
        |--------------------------------------------------------------------------
        | TENANTS
        |--------------------------------------------------------------------------
        */

        $tenants =
            Tenant::orderBy(
                'owner_name'
            )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | LAWN TYPES
        |--------------------------------------------------------------------------
        */

        $lawnTypes =
            LawnType::query()
                ->when(

                    $request->filled(
                        'tenant_id'
                    ),

                    function (
                        $q
                    ) use (
                        $request
                    ) {

                        $q->where(
                            'tenant_id',
                            $request->tenant_id
                        );

                    }

                )
                ->orderBy(
                    'lawn_type'
                )
                ->get();

        return view(

            'super_admin.bookings.index',

            compact(

                'bookings',

                'tenants',

                'lawnTypes',

                'tenantBookings'

            )

        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN TENANT BOOKINGS
    |--------------------------------------------------------------------------
    */

    public function superAdminTenantBookings(
        Tenant $tenant
    ) {
        $bookings = Booking::with([

            'tenant',

            'customer',

            'lawnType',

            'bookingServices.paidService',

            'bookingServices.freeService',

            'payments',

            'invoice.items',

        ])
            ->where(
                'tenant_id',
                $tenant->id
            )
            ->latest(
                'booking_date'
            )
            ->latest(
                'id'
            )
            ->get();

        return view(
            'super_admin.bookings.tenant',
            compact(
                'tenant',
                'bookings'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INSTALLMENT OPTIONS
    |--------------------------------------------------------------------------
    */

    private function getTenantInstallmentOptions(
        int $tenantId
    ): array {

        $settings =
            TenantSetting::where(
                'tenant_id',
                $tenantId
            )->first();

        $options =
            $settings?->installment_options
            ?? [];

        if (
            ! is_array(
                $options
            )
        ) {

            $options =
                json_decode(
                    (string) $options,
                    true
                ) ?? [];

        }

        return array_values(

            array_unique(

                array_filter(

                    array_map(

                        fn ($option) => trim(
                            (string) $option
                        ),

                        $options

                    ),

                    fn ($option) => $option !== ''

                )

            )

        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK TENANT
    |--------------------------------------------------------------------------
    */

    private function checkTenant(
        Booking $booking
    ): void {

        if (

            (int) $booking->tenant_id
            !==
            (int) auth()->user()->tenant_id

        ) {

            abort(403);

        }
    }

    /*
    |--------------------------------------------------------------------------
    | TAX PERCENTAGE
    |--------------------------------------------------------------------------
    */

    private function getTaxPercentage(
        ?TenantSetting $settings
    ): float {

        if (
            ! $settings
            ||
            ! $settings->tax_enabled
        ) {

            return 0;

        }

        /*
        |--------------------------------------------------------------------------
        | NEW TAX JSON
        |--------------------------------------------------------------------------
        */

        $taxes =
            $settings->taxes
            ?? null;

        if (
            is_array(
                $taxes
            )
            &&
            ! empty(
                $taxes
            )
        ) {

            return (float) collect(
                $taxes
            )
                ->sum(

                    function (
                        $tax
                    ) {

                        return is_array(
                            $tax
                        )

                            ? (float) (
                                $tax['percentage']
                                ?? 0
                            )

                            : 0;

                    }

                );

        }

        /*
        |--------------------------------------------------------------------------
        | OLD TAX PERCENTAGE
        |--------------------------------------------------------------------------
        */

        if (
            is_numeric(
                $settings->tax_percentage
            )
        ) {

            return (float)
                $settings->tax_percentage;

        }

        /*
        |--------------------------------------------------------------------------
        | OLD TAX_PERCENT
        |--------------------------------------------------------------------------
        */

        if (
            is_numeric(
                $settings->tax_percent
            )
        ) {

            return (float)
                $settings->tax_percent;

        }

        return 0;
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE INVOICE NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateInvoiceNumber(
        $tenantId
    ) {

        do {

            $number =
                'INV-'
                .now()->format(
                    'Ymd'
                )
                .'-'
                .strtoupper(
                    substr(
                        uniqid(),
                        -6
                    )
                );

        } while (

            Invoice::where(
                'tenant_id',
                $tenantId
            )
                ->where(
                    'invoice_number',
                    $number
                )
                ->exists()

        );

        return $number;
    }

    /*
    |--------------------------------------------------------------------------
    | NOTIFY SUPER ADMINS
    |--------------------------------------------------------------------------
    */

    private function notifySuperAdmins(
        string $type,
        array $data
    ): void {

        User::where(
            'role',
            'super_admin'
        )
            ->get()
            ->each(

                function (
                    $superAdmin
                ) use (
                    $type,
                    $data
                ) {

                    $superAdmin->notify(

                        new TenantActivityNotification(

                            $type,

                            $data

                        )

                    );

                }

            );
    }

    /*
    |--------------------------------------------------------------------------
    | PRINT BOOKING CONTRACT
    |--------------------------------------------------------------------------
    */

    public function print(
        Booking $booking
    ) {
        $this->checkTenant(
            $booking
        );

        $booking->load([
            'customer',
            'lawnType',
            'bookingServices.paidService',
            'bookingServices.freeService',
            'payments.receipt',
            'invoice.items',
        ]);

        $tenant = Tenant::find(
            (int) $booking->tenant_id
        );

        return view(
            'bookings.print',
            compact(
                'booking',
                'tenant'
            )
        );
    }
}
