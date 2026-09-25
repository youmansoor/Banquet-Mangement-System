<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\TenantBank;
use App\Models\TenantFinanceMaster;
use App\Models\Vendor;
use App\Models\TenantFinanceTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TenantFinanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $tenantId =
            (int) Auth::user()->tenant_id;


        /*
        |--------------------------------------------------------------------------
        | FINANCE MASTERS
        |--------------------------------------------------------------------------
        */

        $masters =
            TenantFinanceMaster::where(
                'tenant_id',
                $tenantId
            )
            ->where(
                'is_active',
                true
            )
            ->orderBy(
                'sort_order'
            )
            ->orderBy(
                'name'
            )
            ->get()
            ->groupBy(
                'master_type'
            );


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
        | TENANT BANKS
        |--------------------------------------------------------------------------
        */

        $banks =
            TenantBank::where(
                'tenant_id',
                $tenantId
            )
            ->orderBy(
                'bank_name'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | CURRENCY
        |--------------------------------------------------------------------------
        */

        $currency =
            'PKR';


        /*
        |--------------------------------------------------------------------------
        | TENANT VENDORS
        |--------------------------------------------------------------------------
        */

        $vendors =
            Vendor::where(
                'tenant_id',
                $tenantId
            )
            ->where(
                'status',
                true
            )
            ->orderBy(
                'vendor_name'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'tenant.finance.create',
            compact(
                'masters',
                'customers',
                'banks',
                'currency',
                'vendors'
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
            (int) Auth::user()->tenant_id;


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate([

                /*
                |--------------------------------------------------------------------------
                | TRANSACTION TYPE
                |--------------------------------------------------------------------------
                */

                'transaction_type' => [
                    'required',
                    Rule::in([
                        'customer_payment',
                        'vendor_payment',
                        'banquet_expense',
                    ]),
                ],


                /*
                |--------------------------------------------------------------------------
                | PAYMENT CATEGORY
                |--------------------------------------------------------------------------
                */

                'payment_category_id' => [
                    'required',
                    'integer',

                    Rule::exists(
                        'tenant_finance_masters',
                        'id'
                    )->where(
                        function ($query) use ($tenantId) {

                            $query->where(
                                'tenant_id',
                                $tenantId
                            )
                            ->where(
                                'master_type',
                                'payment_category'
                            )
                            ->where(
                                'is_active',
                                true
                            );

                        }
                    ),
                ],


                /*
                |--------------------------------------------------------------------------
                | CUSTOMER
                |--------------------------------------------------------------------------
                */

                'customer_id' => [
                    'nullable',
                    'integer',

                    Rule::exists(
                        'customers',
                        'id'
                    )->where(
                        function ($query) use ($tenantId) {

                            $query->where(
                                'tenant_id',
                                $tenantId
                            );

                        }
                    ),
                ],


                /*
                |--------------------------------------------------------------------------
                | BOOKING
                |--------------------------------------------------------------------------
                */

                'booking_id' => [
                    'nullable',
                    'integer',

                    Rule::exists(
                        'bookings',
                        'id'
                    )->where(
                        function ($query) use ($tenantId) {

                            $query->where(
                                'tenant_id',
                                $tenantId
                            );

                        }
                    ),
                ],


                /*
                |--------------------------------------------------------------------------
                | INVOICE
                |--------------------------------------------------------------------------
                */

                'invoice_id' => [
                    'nullable',
                    'integer',

                    Rule::exists(
                        'invoices',
                        'id'
                    )->where(
                        function ($query) use ($tenantId) {

                            $query->where(
                                'tenant_id',
                                $tenantId
                            );

                        }
                    ),
                ],


                /*
                |--------------------------------------------------------------------------
                | BENEFICIARY
                |--------------------------------------------------------------------------
                */

                'beneficiary_id' => [
                    'nullable',
                    'integer',

                    Rule::exists(
                        'tenant_finance_masters',
                        'id'
                    )->where(
                        function ($query) use ($tenantId) {

                            $query->where(
                                'tenant_id',
                                $tenantId
                            )
                            ->where(
                                'master_type',
                                'beneficiary'
                            )
                            ->where(
                                'is_active',
                                true
                            );

                        }
                    ),
                ],


                /*
                |--------------------------------------------------------------------------
                | VENDOR
                |--------------------------------------------------------------------------
                */

                'vendor_id' => [
                    'nullable',
                    'integer',

                    Rule::exists(
                        'vendors',
                        'id'
                    )->where(
                        function ($query) use ($tenantId) {

                            $query->where(
                                'tenant_id',
                                $tenantId
                            )
                            ->where(
                                'status',
                                true
                            );

                        }
                    ),
                ],


                /*
                |--------------------------------------------------------------------------
                | TENANT BANK
                |--------------------------------------------------------------------------
                */

                'tenant_bank_id' => [
                    'nullable',
                    'integer',

                    Rule::exists(
                        'tenant_banks',
                        'id'
                    )->where(
                        function ($query) use ($tenantId) {

                            $query->where(
                                'tenant_id',
                                $tenantId
                            );

                        }
                    ),
                ],


                /*
                |--------------------------------------------------------------------------
                | SNAPSHOT VALUES
                |--------------------------------------------------------------------------
                */

                'job_number' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'bill_number' => [
                    'nullable',
                    'string',
                    'max:100',
                ],


                /*
                |--------------------------------------------------------------------------
                | DESCRIPTION
                |--------------------------------------------------------------------------
                */

                'item_description' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],


                /*
                |--------------------------------------------------------------------------
                | VENDOR INVOICE
                |--------------------------------------------------------------------------
                */

                'vendor_invoice' => [
                    'nullable',
                    'string',
                    'max:255',
                ],


                /*
                |--------------------------------------------------------------------------
                | CHEQUE / TID / PO
                |--------------------------------------------------------------------------
                */

                'cheque_number' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'cheque_date' => [
                    'nullable',
                    'date',
                ],


                /*
                |--------------------------------------------------------------------------
                | AMOUNT
                |--------------------------------------------------------------------------
                */

                'amount' => [
                    'required',
                    'numeric',
                    'min:0.01',
                    'max:999999999999.99',
                ],


                /*
                |--------------------------------------------------------------------------
                | DATE
                |--------------------------------------------------------------------------
                */

                'transaction_date' => [
                    'required',
                    'date',
                ],


                /*
                |--------------------------------------------------------------------------
                | REMARKS
                |--------------------------------------------------------------------------
                */

                'remarks' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],

            ]);


        /*
        |--------------------------------------------------------------------------
        | HARD-CODED TRANSACTION TYPE
        |--------------------------------------------------------------------------
        */

        $transactionTypeKey =
            $validated['transaction_type'];

        $directionMap = [
            'customer_payment' => 'cr',
            'vendor_payment' => 'dr',
            'banquet_expense' => 'dr',
        ];

        $direction =
            $directionMap[$transactionTypeKey];


        /*
        |--------------------------------------------------------------------------
        | VERIFY PAYMENT CATEGORY
        |--------------------------------------------------------------------------
        */

        $paymentCategory =
            TenantFinanceMaster::where(
                'tenant_id',
                $tenantId
            )
            ->where(
                'id',
                $validated['payment_category_id']
            )
            ->where(
                'master_type',
                'payment_category'
            )
            ->where(
                'is_active',
                true
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | CATEGORY MUST BELONG TO HARD-CODED TYPE
        |--------------------------------------------------------------------------
        */

        if (
            $paymentCategory->parent_key !==
            $transactionTypeKey
        ) {

            return back()
                ->withErrors([
                    'payment_category_id' =>
                        'Selected payment category does not belong to the selected transaction type.',
                ])
                ->withInput();

        }


        /*
        |--------------------------------------------------------------------------
        | AMOUNT
        |--------------------------------------------------------------------------
        */

        $amount =
            (float) $validated['amount'];


        /*
        |--------------------------------------------------------------------------
        | BOOKING PAYMENT
        |
        | CR = Customer / Booking Payment
        |--------------------------------------------------------------------------
        */

        if (
            $direction === 'cr'
        ) {

            /*
            |--------------------------------------------------------------------------
            | CUSTOMER REQUIRED
            |--------------------------------------------------------------------------
            */

            if (
                empty(
                    $validated['customer_id']
                )
            ) {

                return back()
                    ->withErrors([
                        'customer_id' =>
                            'Customer is required for booking payment.',
                    ])
                    ->withInput();

            }


            /*
            |--------------------------------------------------------------------------
            | BOOKING REQUIRED
            |--------------------------------------------------------------------------
            */

            if (
                empty(
                    $validated['booking_id']
                )
            ) {

                return back()
                    ->withErrors([
                        'booking_id' =>
                            'Job / Booking is required for booking payment.',
                    ])
                    ->withInput();

            }


            /*
            |--------------------------------------------------------------------------
            | INVOICE REQUIRED
            |--------------------------------------------------------------------------
            */

            if (
                empty(
                    $validated['invoice_id']
                )
            ) {

                return back()
                    ->withErrors([
                        'invoice_id' =>
                            'Bill / Invoice is required for booking payment.',
                    ])
                    ->withInput();

            }


            /*
            |--------------------------------------------------------------------------
            | CUSTOMER
            |--------------------------------------------------------------------------
            */

            $customer =
                Customer::where(
                    'tenant_id',
                    $tenantId
                )
                ->findOrFail(
                    $validated['customer_id']
                );


            /*
            |--------------------------------------------------------------------------
            | BOOKING
            |--------------------------------------------------------------------------
            */

            $booking =
                Booking::where(
                    'tenant_id',
                    $tenantId
                )
                ->findOrFail(
                    $validated['booking_id']
                );


            /*
            |--------------------------------------------------------------------------
            | BOOKING CUSTOMER MUST MATCH
            |--------------------------------------------------------------------------
            */

            if (
                (int) $booking->customer_id !==
                (int) $customer->id
            ) {

                return back()
                    ->withErrors([
                        'booking_id' =>
                            'Selected job does not belong to the selected customer.',
                    ])
                    ->withInput();

            }


            /*
            |--------------------------------------------------------------------------
            | INVOICE
            |--------------------------------------------------------------------------
            */

            $invoice =
                Invoice::where(
                    'tenant_id',
                    $tenantId
                )
                ->findOrFail(
                    $validated['invoice_id']
                );


            /*
            |--------------------------------------------------------------------------
            | INVOICE MUST BELONG TO BOOKING
            |--------------------------------------------------------------------------
            */

            if (
                (int) $invoice->booking_id !==
                (int) $booking->id
            ) {

                return back()
                    ->withErrors([
                        'invoice_id' =>
                            'Selected bill does not belong to the selected job.',
                    ])
                    ->withInput();

            }


            /*
            |--------------------------------------------------------------------------
            | INVOICE CUSTOMER MUST MATCH
            |--------------------------------------------------------------------------
            */

            if (
                (int) $invoice->customer_id !==
                (int) $customer->id
            ) {

                return back()
                    ->withErrors([
                        'invoice_id' =>
                            'Selected bill does not belong to the selected customer.',
                    ])
                    ->withInput();

            }


            /*
            |--------------------------------------------------------------------------
            | TRANSACTION REFERENCE
            |--------------------------------------------------------------------------
            */

            $transactionReference =
                trim(
                    $validated[
                        'cheque_number'
                    ] ?? ''
                ) ?: null;


            /*
            |--------------------------------------------------------------------------
            | PAYMENT METHOD
            |
            | cheque number => cheque
            | bank selected  => bank
            | otherwise      => cash
            |--------------------------------------------------------------------------
            */

            if (
                !empty(
                    $transactionReference
                )
            ) {

                $paymentMethod =
                    'cheque';

            } elseif (
                !empty(
                    $validated[
                        'tenant_bank_id'
                    ]
                )
            ) {

                $paymentMethod =
                    'bank';

            } else {

                $paymentMethod =
                    'cash';

            }


            /*
            |--------------------------------------------------------------------------
            | SAVE EVERYTHING ATOMICALLY
            |--------------------------------------------------------------------------
            */

            $transaction =
                DB::transaction(
                    function () use (
                        $tenantId,
                        $validated,
                        $amount,
                        $customer,
                        $booking,
                        $invoice,
                        $transactionReference,
                        $paymentMethod,
                        $transactionTypeKey
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | LOCK INVOICE
                        |--------------------------------------------------------------------------
                        */

                        $lockedInvoice =
                            Invoice::where(
                                'tenant_id',
                                $tenantId
                            )
                            ->where(
                                'id',
                                $invoice->id
                            )
                            ->lockForUpdate()
                            ->firstOrFail();


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
                        | CURRENT INVOICE VALUES
                        |--------------------------------------------------------------------------
                        */

                        $invoiceGrandTotal =
                            (float)
                            $lockedInvoice->grand_total;


                        $invoicePaidAmount =
                            (float)
                            $lockedInvoice->paid_amount;


                        $invoiceRemainingAmount =
                            (float)
                            $lockedInvoice->remaining_amount;


                        /*
                        |--------------------------------------------------------------------------
                        | CALCULATE INVOICE REMAINING
                        |
                        | Stored remaining is preferred.
                        | Also protect against negative values.
                        |--------------------------------------------------------------------------
                        */

                        $invoiceRemainingAmount =
                            max(
                                $invoiceRemainingAmount,
                                0
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | INVOICE PAYMENT LIMIT
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $amount >
                            $invoiceRemainingAmount
                        ) {

                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'amount' =>
                                    'Payment amount cannot be greater than invoice remaining amount of '
                                    . number_format(
                                        $invoiceRemainingAmount,
                                        2
                                    )
                                    . '.',
                            ]);

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | BOOKING CURRENT VALUES
                        |--------------------------------------------------------------------------
                        */

                        $bookingGrandTotal =
                            (float)
                            $lockedBooking->grand_total;


                        $bookingAdvance =
                            (float)
                            $lockedBooking->advance_amount;


                        $bookingRemaining =
                            (float)
                            $lockedBooking->remaining_amount;


                        /*
                        |--------------------------------------------------------------------------
                        | BOOKING PAYMENT LIMIT
                        |--------------------------------------------------------------------------
                        */

                        $bookingRemaining =
                            max(
                                $bookingRemaining,
                                0
                            );


                        if (
                            $amount >
                            $bookingRemaining
                        ) {

                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'amount' =>
                                    'Payment amount cannot be greater than booking remaining amount of '
                                    . number_format(
                                        $bookingRemaining,
                                        2
                                    )
                                    . '.',
                            ]);

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | NEW INVOICE VALUES
                        |--------------------------------------------------------------------------
                        */

                        $newInvoicePaid =
                            $invoicePaidAmount
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


                        /*
                        |--------------------------------------------------------------------------
                        | UPDATE INVOICE
                        |--------------------------------------------------------------------------
                        */

                        $lockedInvoice->update([

                            'paid_amount' =>
                                $newInvoicePaid,

                            'remaining_amount' =>
                                $newInvoiceRemaining,

                            'status' =>
                                $invoiceStatus,

                        ]);


                        /*
                        |--------------------------------------------------------------------------
                        | NEW BOOKING VALUES
                        |--------------------------------------------------------------------------
                        */

                        $newBookingAdvance =
                            $bookingAdvance
                            + $amount;


                        $newBookingRemaining =
                            max(
                                $bookingGrandTotal
                                - $newBookingAdvance,
                                0
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | UPDATE BOOKING BALANCE
                        |--------------------------------------------------------------------------
                        */

                        $lockedBooking->update([

                            'advance_amount' =>
                                $newBookingAdvance,

                            'remaining_amount' =>
                                $newBookingRemaining,

                        ]);


                        /*
                        |--------------------------------------------------------------------------
                        | BOOKING PAYMENT STATUS
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $newBookingRemaining <= 0
                        ) {

                            $bookingPaymentStatus =
                                'paid';

                        } else {

                            $bookingPaymentStatus =
                                'partial';

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | CREATE BOOKING PAYMENT
                        |--------------------------------------------------------------------------
                        */

                        BookingPayment::create([

                            'tenant_id' =>
                                $tenantId,

                            'booking_id' =>
                                $lockedBooking->id,

                            'amount' =>
                                $amount,

                            'payment_method' =>
                                $paymentMethod,

                            'payment_status' =>
                                $bookingPaymentStatus,

                            'transaction_reference' =>
                                $transactionReference,

                            'installment_type' =>
                                'finance_payment',

                            'notes' =>
                                trim(
                                    $validated[
                                        'remarks'
                                    ] ?? ''
                                ) ?: null,

                            'paid_at' =>
                                $validated[
                                    'transaction_date'
                                ] . ' 00:00:00',

                        ]);


                        /*
                        |--------------------------------------------------------------------------
                        | JOB NUMBER
                        |--------------------------------------------------------------------------
                        */

                        $jobNumber =
                            'JOB-' .
                            str_pad(
                                $lockedBooking->id,
                                6,
                                '0',
                                STR_PAD_LEFT
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | BILL NUMBER
                        |--------------------------------------------------------------------------
                        */

                        $billNumber =
                            $lockedInvoice->invoice_number;


                        /*
                        |--------------------------------------------------------------------------
                        | CREATE FINANCE LEDGER ENTRY
                        |--------------------------------------------------------------------------
                        */

                        return TenantFinanceTransaction::create([

                            'tenant_id' =>
                                $tenantId,

                            'transaction_type' =>
                                $transactionTypeKey,

                            'payment_category_id' =>
                                $validated[
                                    'payment_category_id'
                                ],

                            'beneficiary_id' =>
                                null,

                            'vendor_id' =>
                                null,

                            'customer_id' =>
                                $customer->id,

                            'booking_id' =>
                                $lockedBooking->id,

                            'invoice_id' =>
                                $lockedInvoice->id,

                            'job_number' =>
                                $jobNumber,

                            'bill_number' =>
                                $billNumber,

                            'item_description' =>
                                trim(
                                    $validated[
                                        'item_description'
                                    ] ?? ''
                                ) ?: 'Booking Payment',

                            'vendor_invoice' =>
                                null,

                            'tenant_bank_id' =>
                                $validated[
                                    'tenant_bank_id'
                                ] ?? null,

                            'cheque_number' =>
                                trim(
                                    $validated[
                                        'cheque_number'
                                    ] ?? ''
                                ) ?: null,

                            'cheque_date' =>
                                $validated[
                                    'cheque_date'
                                ] ?? null,

                            'transaction_direction' =>
                                'cr',

                            'amount' =>
                                $amount,

                            'transaction_date' =>
                                $validated[
                                    'transaction_date'
                                ],

                            'remarks' =>
                                trim(
                                    $validated[
                                        'remarks'
                                    ] ?? ''
                                ) ?: null,

                        ]);

                    }
                );


            return redirect()
                ->route(
                    'tenant.finance.create'
                )
                ->with(
                    'success',
                    'Booking payment recorded successfully. Invoice and booking balances updated.'
                );

        }



        /*
        |--------------------------------------------------------------------------
        | VENDOR PAYMENT / BANQUET EXPENSE
        |--------------------------------------------------------------------------
        |
        | Both are DR transactions.
        | Vendor Payment must have a real tenant vendor.
        | Banquet Expense can optionally use a vendor/beneficiary.
        |--------------------------------------------------------------------------
        */

        if (
            $transactionTypeKey === 'vendor_payment' &&
            empty($validated['vendor_id'])
        ) {

            return back()
                ->withErrors([
                    'vendor_id' =>
                        'Vendor is required for vendor payment.',
                ])
                ->withInput();

        }


        /*
        |--------------------------------------------------------------------------
        | EXPENSE
        |
        | DR = Expense
        |--------------------------------------------------------------------------
        */

        if (
            $direction === 'dr'
        ) {

            /*
            |--------------------------------------------------------------------------
            | EXPENSE FIELDS
            |
            | Remove booking-side values even if someone manipulates the request.
            |--------------------------------------------------------------------------
            */

            $validated[
                'customer_id'
            ] = null;


            $validated[
                'booking_id'
            ] = null;


            $validated[
                'invoice_id'
            ] = null;


            $validated[
                'job_number'
            ] = null;


            $validated[
                'bill_number'
            ] = null;


            /*
            |--------------------------------------------------------------------------
            | CREATE EXPENSE LEDGER ENTRY
            |--------------------------------------------------------------------------
            */

            TenantFinanceTransaction::create([

                'tenant_id' =>
                    $tenantId,

                'transaction_type' =>
                    $transactionTypeKey,

                'payment_category_id' =>
                    $validated[
                        'payment_category_id'
                    ],

                'beneficiary_id' =>
                    $validated[
                        'beneficiary_id'
                    ] ?? null,

                'vendor_id' =>
                    $validated[
                        'vendor_id'
                    ] ?? null,

                'customer_id' =>
                    null,

                'booking_id' =>
                    null,

                'invoice_id' =>
                    null,

                'job_number' =>
                    null,

                'bill_number' =>
                    null,

                'item_description' =>
                    trim(
                        $validated[
                            'item_description'
                        ] ?? ''
                    ) ?: null,

                'vendor_invoice' =>
                    trim(
                        $validated[
                            'vendor_invoice'
                        ] ?? ''
                    ) ?: null,

                'tenant_bank_id' =>
                    $validated[
                        'tenant_bank_id'
                    ] ?? null,

                'cheque_number' =>
                    trim(
                        $validated[
                            'cheque_number'
                        ] ?? ''
                    ) ?: null,

                'cheque_date' =>
                    $validated[
                        'cheque_date'
                    ] ?? null,

                'transaction_direction' =>
                    'dr',

                'amount' =>
                    $amount,

                'transaction_date' =>
                    $validated[
                        'transaction_date'
                    ],

                'remarks' =>
                    trim(
                        $validated[
                            'remarks'
                        ] ?? ''
                    ) ?: null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | REDIRECT
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'tenant.finance.create'
                )
                ->with(
                    'success',
                    'Expense transaction recorded successfully.'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        return back()
            ->withErrors([
                'transaction_type' =>
                    'Invalid transaction type.',
            ])
            ->withInput();
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER JOBS
    |--------------------------------------------------------------------------
    */

    public function customerJobs(
        Customer $customer
    ) {

        $tenantId =
            (int) Auth::user()->tenant_id;


        /*
        |--------------------------------------------------------------------------
        | TENANT SECURITY
        |--------------------------------------------------------------------------
        */

        if (
            (int) $customer->tenant_id !==
            $tenantId
        ) {

            abort(403);

        }


        /*
        |--------------------------------------------------------------------------
        | JOBS
        |--------------------------------------------------------------------------
        */

        $jobs =
            Booking::where(
                'tenant_id',
                $tenantId
            )
            ->where(
                'customer_id',
                $customer->id
            )
            ->where(
                'status',
                '!=',
                'cancelled'
            )
            ->orderByDesc(
                'booking_date'
            )
            ->orderByDesc(
                'id'
            )
            ->get()
            ->map(
                function ($booking) {

                    return [

                        'id' =>
                            $booking->id,

                        'job_number' =>
                            'JOB-' .
                            str_pad(
                                $booking->id,
                                6,
                                '0',
                                STR_PAD_LEFT
                            ),

                        'event_type' =>
                            $booking->event_type,

                        'booking_date' =>
                            $booking->booking_date
                                ? $booking->booking_date
                                    ->format('d-m-Y')
                                : null,

                    ];

                }
            )
            ->values();


        return response()->json([

            'success' =>
                true,

            'jobs' =>
                $jobs,

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | BOOKING BILLS
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| BOOKING BILLS
|--------------------------------------------------------------------------
*/

public function bookingBills(
    Booking $booking
) {

    $tenantId =
        (int) Auth::user()->tenant_id;


    /*
    |--------------------------------------------------------------------------
    | TENANT SECURITY
    |--------------------------------------------------------------------------
    */

    if (
        (int) $booking->tenant_id !==
        $tenantId
    ) {

        abort(403);

    }


    /*
    |--------------------------------------------------------------------------
    | ONLY ACTIVE PAYABLE INVOICES
    |--------------------------------------------------------------------------
    */

    $bills =
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
        ->where(
            'remaining_amount',
            '>',
            0
        )
        ->orderByDesc(
            'invoice_date'
        )
        ->orderByDesc(
            'id'
        )
        ->get()
        ->map(
            function ($invoice) {

                return [

                    /*
                    |--------------------------------------------------------------------------
                    | INVOICE ID
                    |--------------------------------------------------------------------------
                    */

                    'id' =>
                        $invoice->id,


                    /*
                    |--------------------------------------------------------------------------
                    | INVOICE NUMBER
                    |--------------------------------------------------------------------------
                    */

                    'invoice_number' =>
                        $invoice->invoice_number,


                    /*
                    |--------------------------------------------------------------------------
                    | GRAND TOTAL
                    |--------------------------------------------------------------------------
                    */

                    'grand_total' =>
                        (float)
                        $invoice->grand_total,


                    /*
                    |--------------------------------------------------------------------------
                    | PAY AMOUNT
                    |--------------------------------------------------------------------------
                    |
                    | THIS IS THE IMPORTANT FIX.
                    |
                    | This is the amount saved in:
                    |
                    | invoices.pay_amount
                    |
                    |--------------------------------------------------------------------------
                    */

                    'pay_amount' =>
                        (float)
                        ($invoice->pay_amount ?? 0),


                    /*
                    |--------------------------------------------------------------------------
                    | ACTUAL PAID AMOUNT
                    |--------------------------------------------------------------------------
                    */

                    'paid_amount' =>
                        (float)
                        ($invoice->paid_amount ?? 0),


                    /*
                    |--------------------------------------------------------------------------
                    | REMAINING AMOUNT
                    |--------------------------------------------------------------------------
                    */

                    'remaining_amount' =>
                        (float)
                        ($invoice->remaining_amount ?? 0),


                    /*
                    |--------------------------------------------------------------------------
                    | STATUS
                    |--------------------------------------------------------------------------
                    */

                    'status' =>
                        $invoice->status,

                ];

            }
        )
        ->values();


    /*
    |--------------------------------------------------------------------------
    | JSON RESPONSE
    |--------------------------------------------------------------------------
    */

    return response()->json([

        'success' =>
            true,

        'bills' =>
            $bills,

    ]);
}
}