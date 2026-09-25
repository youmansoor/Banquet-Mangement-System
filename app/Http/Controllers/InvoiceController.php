<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $invoices = Invoice::with([
            'customer',
            'booking',
        ])
            ->where('tenant_id', $tenantId)

            /*
            |--------------------------------------------------------------------------
            | QUICK SEARCH
            |--------------------------------------------------------------------------
            | Invoice number
            | Customer name
            | Customer phone
            | Booking ID
            |--------------------------------------------------------------------------
            */

            ->when($request->filled('search'), function ($query) use ($request) {

                $search = trim($request->search);

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'invoice_number',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'booking_id',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhereHas('customer', function ($customer) use ($search) {

                            $customer
                                ->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'phone',
                                    'like',
                                    "%{$search}%"
                                );

                        });

                });

            })

            /*
            |--------------------------------------------------------------------------
            | INVOICE NUMBER
            |--------------------------------------------------------------------------
            */

            ->when($request->filled('invoice_number'), function ($query) use ($request) {

                $query->where(
                    'invoice_number',
                    'like',
                    '%'.trim($request->invoice_number).'%'
                );

            })

            /*
            |--------------------------------------------------------------------------
            | CUSTOMER NAME
            |--------------------------------------------------------------------------
            */

            ->when($request->filled('customer'), function ($query) use ($request) {

                $customer = trim($request->customer);

                $query->whereHas('customer', function ($q) use ($customer) {

                    $q->where(
                        'name',
                        'like',
                        "%{$customer}%"
                    );

                });

            })

            /*
            |--------------------------------------------------------------------------
            | CUSTOMER PHONE
            |--------------------------------------------------------------------------
            */

            ->when($request->filled('phone'), function ($query) use ($request) {

                $phone = trim($request->phone);

                $query->whereHas('customer', function ($q) use ($phone) {

                    $q->where(
                        'phone',
                        'like',
                        "%{$phone}%"
                    );

                });

            })

            /*
            |--------------------------------------------------------------------------
            | BOOKING ID
            |--------------------------------------------------------------------------
            */

            ->when($request->filled('booking_id'), function ($query) use ($request) {

                $query->where(
                    'booking_id',
                    $request->booking_id
                );

            })

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            ->when($request->filled('status'), function ($query) use ($request) {

                $query->where(
                    'status',
                    $request->status
                );

            })

            /*
            |--------------------------------------------------------------------------
            | INVOICE DATE FROM
            |--------------------------------------------------------------------------
            */

            ->when($request->filled('date_from'), function ($query) use ($request) {

                $query->whereDate(
                    'invoice_date',
                    '>=',
                    $request->date_from
                );

            })

            /*
            |--------------------------------------------------------------------------
            | INVOICE DATE TO
            |--------------------------------------------------------------------------
            */

            ->when($request->filled('date_to'), function ($query) use ($request) {

                $query->whereDate(
                    'invoice_date',
                    '<=',
                    $request->date_to
                );

            })

            /*
            |--------------------------------------------------------------------------
            | MINIMUM TOTAL
            |--------------------------------------------------------------------------
            */

            ->when($request->filled('min_total'), function ($query) use ($request) {

                $query->where(
                    'grand_total',
                    '>=',
                    (float) $request->min_total
                );

            })

            /*
            |--------------------------------------------------------------------------
            | MAXIMUM TOTAL
            |--------------------------------------------------------------------------
            */

            ->when($request->filled('max_total'), function ($query) use ($request) {

                $query->where(
                    'grand_total',
                    '<=',
                    (float) $request->max_total
                );

            })

            /*
            |--------------------------------------------------------------------------
            | PAYMENT STATE
            |--------------------------------------------------------------------------
            */

            ->when(
                $request->payment_state === 'paid',
                function ($query) {

                    $query->where(function ($q) {

                        $q->where(
                            'remaining_amount',
                            '<=',
                            0
                        )
                            ->orWhereColumn(
                                'paid_amount',
                                '>=',
                                'grand_total'
                            );

                    });

                }
            )

            ->when(
                $request->payment_state === 'partial',
                function ($query) {

                    $query->where(
                        'paid_amount',
                        '>',
                        0
                    )
                        ->where(
                            'remaining_amount',
                            '>',
                            0
                        );

                }
            )

            ->when(
                $request->payment_state === 'unpaid',
                function ($query) {

                    $query->where(function ($q) {

                        $q->whereNull('paid_amount')
                            ->orWhere(
                                'paid_amount',
                                '<=',
                                0
                            );

                    });

                }
            )

            /*
            |--------------------------------------------------------------------------
            | ORDER + PAGINATION
            |--------------------------------------------------------------------------
            */

            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'tenant.invoices.index',
            compact('invoices')
        );
    }

    public function create()
    {
        $tenantId = auth()->user()->tenant_id;

        $customers = Customer::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get();

        $bookings = Booking::with('customer')
            ->where('tenant_id', $tenantId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->latest()
            ->get();

        return view('tenant.invoices.create', compact(
            'customers',
            'bookings'
        ));
    }

    public function store(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'booking_id' => 'nullable|exists:bookings,id',

            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',

            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0',

            'notes' => 'nullable|string',

            'items' => 'required|array|min:1',

            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Make sure customer belongs to current tenant
        |--------------------------------------------------------------------------
        */

        $customer = Customer::where('id', $validated['customer_id'])
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Booking tenant validation
        |--------------------------------------------------------------------------
        */

        $booking = null;

        if (! empty($validated['booking_id'])) {
            $booking = Booking::where('id', $validated['booking_id'])
                ->where('tenant_id', $tenantId)
                ->firstOrFail();
        }

        DB::transaction(function () use (
            $validated,
            $tenantId,
            $booking
        ) {

            $subtotal = 0;

            foreach ($validated['items'] as $item) {
                $subtotal +=
                    ((float) $item['quantity']) *
                    ((float) $item['unit_cost']);
            }

            $discount = (float) ($validated['discount'] ?? 0);
            $tax = (float) ($validated['tax'] ?? 0);
            $additionalCharges =
                (float) ($validated['additional_charges'] ?? 0);

            $grandTotal =
                $subtotal
                - $discount
                + $tax
                + $additionalCharges;

            $invoice = Invoice::create([
                'tenant_id' => $tenantId,

                'customer_id' => $validated['customer_id'],

                'booking_id' => $booking?->id,

                'invoice_number' => $this->generateInvoiceNumber(
                    $tenantId
                ),

                'invoice_date' => $validated['invoice_date'],

                'due_date' => $validated['due_date'] ?? null,

                'subtotal' => $subtotal,

                'discount' => $discount,

                'tax' => $tax,

                'additional_charges' => $additionalCharges,

                'grand_total' => $grandTotal,

                'paid_amount' => 0,

                'remaining_amount' => $grandTotal,

                'status' => 'draft',

                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {

                $quantity = (int) $item['quantity'];

                $unitCost = (float) $item['unit_cost'];

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,

                    'description' => $item['description'],

                    'quantity' => $quantity,

                    'unit_cost' => $unitCost,

                    'total' => $quantity * $unitCost,
                ]);
            }
        });

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $this->authorizeTenant($invoice);

        $invoice->load([
            'customer',
            'booking',
            'items',
            'tenant',
        ]);

        return view(
            'tenant.invoices.show',
            compact('invoice')
        );
    }

    public function edit(Invoice $invoice)
    {
        $this->authorizeTenant($invoice);

        $invoice->load('items');

        $tenantId = auth()->user()->tenant_id;

        $customers = Customer::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get();

        $bookings = Booking::with('customer')
            ->where('tenant_id', $tenantId)
            ->latest()
            ->get();

        return view(
            'tenant.invoices.edit',
            compact(
                'invoice',
                'customers',
                'bookings'
            )
        );
    }

    public function update(Request $request, Invoice $invoice)
    {
        $this->authorizeTenant($invoice);

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'booking_id' => 'nullable|exists:bookings,id',

            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',

            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'additional_charges' => 'nullable|numeric|min:0',

            'notes' => 'nullable|string',

            'items' => 'required|array|min:1',

            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        $tenantId = auth()->user()->tenant_id;

        $customer = Customer::where('id', $validated['customer_id'])
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        $booking = null;

        if (! empty($validated['booking_id'])) {
            $booking = Booking::where('id', $validated['booking_id'])
                ->where('tenant_id', $tenantId)
                ->firstOrFail();
        }

        DB::transaction(function () use (
            $validated,
            $invoice,
            $booking
        ) {

            $subtotal = 0;

            foreach ($validated['items'] as $item) {
                $subtotal +=
                    ((float) $item['quantity']) *
                    ((float) $item['unit_cost']);
            }

            $discount =
                (float) ($validated['discount'] ?? 0);

            $tax =
                (float) ($validated['tax'] ?? 0);

            $additionalCharges =
                (float) ($validated['additional_charges'] ?? 0);

            $grandTotal =
                $subtotal
                - $discount
                + $tax
                + $additionalCharges;

            $paidAmount = (float) $invoice->paid_amount;

            $remainingAmount =
                max(0, $grandTotal - $paidAmount);

            $invoice->update([
                'customer_id' => $validated['customer_id'],

                'booking_id' => $booking?->id,

                'invoice_date' => $validated['invoice_date'],

                'due_date' => $validated['due_date'] ?? null,

                'subtotal' => $subtotal,

                'discount' => $discount,

                'tax' => $tax,

                'additional_charges' => $additionalCharges,

                'grand_total' => $grandTotal,

                'remaining_amount' => $remainingAmount,

                'notes' => $validated['notes'] ?? null,
            ]);

            $invoice->items()->delete();

            foreach ($validated['items'] as $item) {

                $quantity = (int) $item['quantity'];

                $unitCost = (float) $item['unit_cost'];

                $invoice->items()->create([
                    'description' => $item['description'],

                    'quantity' => $quantity,

                    'unit_cost' => $unitCost,

                    'total' => $quantity * $unitCost,
                ]);
            }
        });

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorizeTenant($invoice);

        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    private function generateInvoiceNumber($tenantId)
    {
        do {
            $number =
                'INV-'.
                now()->format('Ymd').
                '-'.
                strtoupper(substr(uniqid(), -6));

        } while (
            Invoice::where('tenant_id', $tenantId)
                ->where('invoice_number', $number)
                ->exists()
        );

        return $number;
    }

    private function authorizeTenant(Invoice $invoice)
    {
        abort_if(
            $invoice->tenant_id !== auth()->user()->tenant_id,
            403
        );
    }
}
