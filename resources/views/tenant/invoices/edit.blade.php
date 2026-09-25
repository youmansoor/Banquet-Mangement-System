<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @include('tenant.nav');
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Edit Invoice</h3>
            <p class="text-muted mb-0">
                Invoice #{{ $invoice->invoice_number }}
            </p>
        </div>

        <a href="{{ route('invoices.show', $invoice) }}"
           class="btn btn-secondary">
            Back
        </a>
    </div>


    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('invoices.update', $invoice) }}"
          method="POST">

        @csrf
        @method('PUT')


        {{-- CUSTOMER / BOOKING --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <h5 class="mb-0">Invoice Information</h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Customer --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Customer
                        </label>

                        <select name="customer_id"
                                id="customer_id"
                                class="form-select"
                                required>

                            <option value="">
                                Select Customer
                            </option>

                            @foreach ($customers as $customer)

                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>

                                    {{ $customer->name }}

                                    @if($customer->phone_1)
                                        - {{ $customer->phone_1 }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Booking --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Booking
                        </label>

                        <select name="booking_id"
                                id="booking_id"
                                class="form-select">

                            <option value="">
                                No Booking
                            </option>

                            @foreach ($bookings as $booking)

                                <option value="{{ $booking->id }}"
                                    {{ old('booking_id', $invoice->booking_id) == $booking->id ? 'selected' : '' }}>

                                    #{{ $booking->id }}
                                    -
                                    {{ $booking->customer->name ?? 'Customer' }}
                                    -
                                    {{ $booking->booking_date?->format('d M Y') }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Invoice Date --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Invoice Date
                        </label>

                        <input type="date"
                               name="invoice_date"
                               class="form-control"
                               value="{{ old(
                                   'invoice_date',
                                   $invoice->invoice_date?->format('Y-m-d')
                               ) }}"
                               required>

                    </div>


                    {{-- Due Date --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Due Date
                        </label>

                        <input type="date"
                               name="due_date"
                               class="form-control"
                               value="{{ old(
                                   'due_date',
                                   $invoice->due_date?->format('Y-m-d')
                               ) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- ITEMS --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Invoice Items
                </h5>

                <button type="button"
                        class="btn btn-sm btn-primary"
                        id="addItem">

                    + Add Item

                </button>

            </div>


            <div class="card-body">

                <div id="itemsContainer">

                    @forelse ($invoice->items as $index => $item)

                        <div class="item-row border rounded p-3 mb-3">

                            <div class="row g-2 align-items-end">

                                {{-- Description --}}
                                <div class="col-md-5">

                                    <label class="form-label">
                                        Description
                                    </label>

                                    <input type="text"
                                           name="items[{{ $index }}][description]"
                                           class="form-control item-description"
                                           value="{{ old(
                                               "items.$index.description",
                                               $item->description
                                           ) }}"
                                           required>

                                </div>


                                {{-- Quantity --}}
                                <div class="col-md-2">

                                    <label class="form-label">
                                        Quantity
                                    </label>

                                    <input type="number"
                                           name="items[{{ $index }}][quantity]"
                                           class="form-control item-quantity"
                                           value="{{ old(
                                               "items.$index.quantity",
                                               $item->quantity
                                           ) }}"
                                           min="1"
                                           required>

                                </div>


                                {{-- Unit Cost --}}
                                <div class="col-md-3">

                                    <label class="form-label">
                                        Unit Cost
                                    </label>

                                    <input type="number"
                                           name="items[{{ $index }}][unit_cost]"
                                           class="form-control item-cost"
                                           value="{{ old(
                                               "items.$index.unit_cost",
                                               $item->unit_cost
                                           ) }}"
                                           min="0"
                                           step="0.01"
                                           required>

                                </div>


                                {{-- Total --}}
                                <div class="col-md-2">

                                    <button type="button"
                                            class="btn btn-danger w-100 removeItem">

                                        Remove

                                    </button>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="item-row border rounded p-3 mb-3">

                            <div class="row g-2 align-items-end">

                                <div class="col-md-5">

                                    <label class="form-label">
                                        Description
                                    </label>

                                    <input type="text"
                                           name="items[0][description]"
                                           class="form-control item-description"
                                           required>

                                </div>

                                <div class="col-md-2">

                                    <label class="form-label">
                                        Quantity
                                    </label>

                                    <input type="number"
                                           name="items[0][quantity]"
                                           class="form-control item-quantity"
                                           value="1"
                                           min="1"
                                           required>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Unit Cost
                                    </label>

                                    <input type="number"
                                           name="items[0][unit_cost]"
                                           class="form-control item-cost"
                                           value="0"
                                           min="0"
                                           step="0.01"
                                           required>

                                </div>

                                <div class="col-md-2">

                                    <button type="button"
                                            class="btn btn-danger w-100 removeItem">

                                        Remove

                                    </button>

                                </div>

                            </div>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- TOTALS --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Invoice Totals
                </h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Discount --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Discount
                        </label>

                        <input type="number"
                               name="discount"
                               id="discount"
                               class="form-control"
                               value="{{ old(
                                   'discount',
                                   $invoice->discount
                               ) }}"
                               min="0"
                               step="0.01">

                    </div>


                    {{-- Tax --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Tax
                        </label>

                        <input type="number"
                               name="tax"
                               id="tax"
                               class="form-control"
                               value="{{ old(
                                   'tax',
                                   $invoice->tax
                               ) }}"
                               min="0"
                               step="0.01">

                    </div>


                    {{-- Additional Charges --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Additional Charges
                        </label>

                        <input type="number"
                               name="additional_charges"
                               id="additional_charges"
                               class="form-control"
                               value="{{ old(
                                   'additional_charges',
                                   $invoice->additional_charges
                               ) }}"
                               min="0"
                               step="0.01">

                    </div>

                </div>


                <hr>


                <div class="row justify-content-end">

                    <div class="col-md-5">

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <strong id="subtotalDisplay">
                                {{ number_format($invoice->subtotal, 2) }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount</span>
                            <strong id="discountDisplay">
                                {{ number_format($invoice->discount, 2) }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <strong id="taxDisplay">
                                {{ number_format($invoice->tax, 2) }}
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Additional Charges</span>
                            <strong id="additionalDisplay">
                                {{ number_format($invoice->additional_charges, 2) }}
                            </strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between fs-5">
                            <span>
                                Grand Total
                            </span>

                            <strong id="grandTotalDisplay">
                                {{ number_format($invoice->grand_total, 2) }}
                            </strong>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- NOTES --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Notes
                </h5>
            </div>

            <div class="card-body">

                <textarea name="notes"
                          class="form-control"
                          rows="4"
                          placeholder="Invoice notes...">{{ old('notes', $invoice->notes) }}</textarea>

            </div>

        </div>


        {{-- BUTTONS --}}
        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route('invoices.show', $invoice) }}"
               class="btn btn-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-success">

                Update Invoice

            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const container =
        document.getElementById('itemsContainer');

    const addItemButton =
        document.getElementById('addItem');

    let itemIndex =
        {{ $invoice->items->count() }};


    /*
    |--------------------------------------------------------------------------
    | ADD ITEM
    |--------------------------------------------------------------------------
    */

    addItemButton.addEventListener('click', function () {

        const row = document.createElement('div');

        row.className =
            'item-row border rounded p-3 mb-3';

        row.innerHTML = `

            <div class="row g-2 align-items-end">

                <div class="col-md-5">

                    <label class="form-label">
                        Description
                    </label>

                    <input type="text"
                           name="items[${itemIndex}][description]"
                           class="form-control item-description"
                           required>

                </div>

                <div class="col-md-2">

                    <label class="form-label">
                        Quantity
                    </label>

                    <input type="number"
                           name="items[${itemIndex}][quantity]"
                           class="form-control item-quantity"
                           value="1"
                           min="1"
                           required>

                </div>

                <div class="col-md-3">

                    <label class="form-label">
                        Unit Cost
                    </label>

                    <input type="number"
                           name="items[${itemIndex}][unit_cost]"
                           class="form-control item-cost"
                           value="0"
                           min="0"
                           step="0.01"
                           required>

                </div>

                <div class="col-md-2">

                    <button type="button"
                            class="btn btn-danger w-100 removeItem">

                        Remove

                    </button>

                </div>

            </div>
        `;

        container.appendChild(row);

        itemIndex++;

    });


    /*
    |--------------------------------------------------------------------------
    | REMOVE ITEM
    |--------------------------------------------------------------------------
    */

    container.addEventListener('click', function (event) {

        if (!event.target.classList.contains('removeItem')) {
            return;
        }

        const rows =
            container.querySelectorAll('.item-row');

        if (rows.length <= 1) {

            alert('At least one invoice item is required.');

            return;
        }

        event.target
            .closest('.item-row')
            .remove();

        calculateTotals();

    });


    /*
    |--------------------------------------------------------------------------
    | CALCULATE TOTALS
    |--------------------------------------------------------------------------
    */

    function calculateTotals() {

        let subtotal = 0;

        document
            .querySelectorAll('.item-row')
            .forEach(function (row) {

                const quantity =
                    parseFloat(
                        row.querySelector('.item-quantity')?.value
                    ) || 0;

                const cost =
                    parseFloat(
                        row.querySelector('.item-cost')?.value
                    ) || 0;

                subtotal += quantity * cost;

            });


        const discount =
            parseFloat(
                document.getElementById('discount').value
            ) || 0;

        const tax =
            parseFloat(
                document.getElementById('tax').value
            ) || 0;

        const additional =
            parseFloat(
                document.getElementById('additional_charges').value
            ) || 0;


        const grandTotal =
            subtotal
            - discount
            + tax
            + additional;


        document.getElementById('subtotalDisplay')
            .textContent = formatMoney(subtotal);

        document.getElementById('discountDisplay')
            .textContent = formatMoney(discount);

        document.getElementById('taxDisplay')
            .textContent = formatMoney(tax);

        document.getElementById('additionalDisplay')
            .textContent = formatMoney(additional);

        document.getElementById('grandTotalDisplay')
            .textContent = formatMoney(grandTotal);

    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT MONEY
    |--------------------------------------------------------------------------
    */

    function formatMoney(amount) {

        return Number(amount)
            .toLocaleString(
                'en-PK',
                {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }
            );

    }


    /*
    |--------------------------------------------------------------------------
    | LIVE CALCULATION
    |--------------------------------------------------------------------------
    */

    container.addEventListener(
        'input',
        calculateTotals
    );

    document.getElementById('discount')
        .addEventListener('input', calculateTotals);

    document.getElementById('tax')
        .addEventListener('input', calculateTotals);

    document.getElementById('additional_charges')
        .addEventListener('input', calculateTotals);


    calculateTotals();

});

</script>
    @include('tenant.footer');
</body>
</html>