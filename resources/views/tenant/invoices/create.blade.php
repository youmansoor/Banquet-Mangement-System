<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Create Invoice</title>

    <link rel="stylesheet"
          href="{{ asset('assets/css/style.css') }}">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>

        .invoice-card {
            margin-bottom: 20px;
        }

        .invoice-section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .required {
            color: #f44336;
        }

        .item-total {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .summary-box {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .summary-row.total-row {
            border-top: 2px solid #ddd;
            margin-top: 10px;
            padding-top: 15px;
        }

        .grand-total {
            color: #28a745;
            font-size: 24px;
            font-weight: 700;
        }

        .table th {
            vertical-align: middle;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .remove-item {
            min-width: 40px;
        }

        .alert ul {
            margin-bottom: 0;
        }

        @media(max-width: 767px) {

            .table-responsive {
                overflow-x: auto;
            }

            .summary-box {
                margin-top: 20px;
            }

        }

    </style>

</head>


<body>

@include('tenant.nav')


<div class="page-wrapper">

    <div class="container-fluid">

        <!-- ==============================================
             PAGE HEADER
        =============================================== -->

        <div class="row page-titles">

            <div class="col-md-6 align-self-center">

                <h4 class="text-themecolor">
                    Create Invoice
                </h4>

            </div>

            <div class="col-md-6 align-self-center text-end">

                <a href="{{ route('invoices.index') }}"
                   class="btn btn-secondary">

                    <i class="fa fa-arrow-left"></i>

                    Back to Invoices

                </a>

            </div>

        </div>


        <!-- ==============================================
             VALIDATION ERRORS
        =============================================== -->

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Please fix the following errors:
                </strong>

                <ul class="mt-2">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <!-- ==============================================
             SUCCESS MESSAGE
        =============================================== -->

        @if(session('success'))

            <div class="alert alert-success">

                <i class="fa fa-check-circle"></i>

                {{ session('success') }}

            </div>

        @endif


        <!-- ==============================================
             CREATE INVOICE FORM
        =============================================== -->

        <form method="POST"
              action="{{ route('invoices.store') }}"
              id="invoiceForm">

            @csrf


            <!-- ==========================================
                 INVOICE INFORMATION
            ========================================== -->

            <div class="card invoice-card">

                <div class="card-body">

                    <h4 class="invoice-section-title">

                        <i class="fa fa-file-text-o text-info"></i>

                        Invoice Information

                    </h4>


                    <div class="row">


                        <!-- CUSTOMER -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Customer

                                <span class="required">*</span>

                            </label>

                            <select name="customer_id"
                                    id="customer_id"
                                    class="form-control @error('customer_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Customer
                                </option>

                                @foreach($customers as $customer)

                                    <option value="{{ $customer->id }}"
                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>

                                        {{ $customer->name }}

                                        @if($customer->phone)

                                            - {{ $customer->phone }}

                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('customer_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- BOOKING -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Booking

                            </label>

                            <select name="booking_id"
                                    id="booking_id"
                                    class="form-control @error('booking_id') is-invalid @enderror">

                                <option value="">
                                    Select Booking
                                </option>

                                @foreach($bookings as $booking)

                                    <option value="{{ $booking->id }}"
                                        {{ old('booking_id') == $booking->id ? 'selected' : '' }}>

                                        Booking #{{ $booking->id }}

                                        -

                                        {{ $booking->customer->name ?? 'N/A' }}

                                        -

                                        {{ $booking->booking_date }}

                                    </option>

                                @endforeach

                            </select>

                            @error('booking_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- INVOICE DATE -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Invoice Date

                                <span class="required">*</span>

                            </label>

                            <input type="date"
                                   name="invoice_date"
                                   class="form-control @error('invoice_date') is-invalid @enderror"
                                   value="{{ old('invoice_date', now()->format('Y-m-d')) }}"
                                   required>

                            @error('invoice_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <!-- DUE DATE -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Due Date

                            </label>

                            <input type="date"
                                   name="due_date"
                                   class="form-control @error('due_date') is-invalid @enderror"
                                   value="{{ old('due_date') }}">

                            @error('due_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                    </div>

                </div>

            </div>


            <!-- ==============================================
                 INVOICE ITEMS
            =============================================== -->

            <div class="card invoice-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h4 class="invoice-section-title mb-0">

                            <i class="fa fa-shopping-cart text-info"></i>

                            Invoice Items

                        </h4>


                        <button type="button"
                                class="btn btn-success"
                                id="addItem">

                            <i class="fa fa-plus"></i>

                            Add Item

                        </button>

                    </div>


                    <div class="table-responsive">

                        <table class="table table-bordered table-hover"
                               id="itemsTable">

                            <thead class="bg-light">

                            <tr>

                                <th>
                                    Description
                                </th>

                                <th width="150">
                                    Quantity
                                </th>

                                <th width="180">
                                    Unit Cost
                                </th>

                                <th width="180">
                                    Total
                                </th>

                                <th width="70"
                                    class="text-center">

                                    Action

                                </th>

                            </tr>

                            </thead>


                            <tbody id="itemsBody">


                            @php

                                $oldItems = old('items', [
                                    [
                                        'description' => '',
                                        'quantity' => 1,
                                        'unit_cost' => 0
                                    ]
                                ]);

                            @endphp


                            @foreach($oldItems as $index => $item)

                                <tr class="item-row">

                                    <!-- DESCRIPTION -->

                                    <td>

                                        <input type="text"
                                               name="items[{{ $index }}][description]"
                                               class="form-control"
                                               placeholder="Enter item description"
                                               value="{{ $item['description'] ?? '' }}"
                                               required>

                                    </td>


                                    <!-- QUANTITY -->

                                    <td>

                                        <input type="number"
                                               name="items[{{ $index }}][quantity]"
                                               class="form-control quantity"
                                               value="{{ $item['quantity'] ?? 1 }}"
                                               min="1"
                                               step="1"
                                               required>

                                    </td>


                                    <!-- UNIT COST -->

                                    <td>

                                        <input type="number"
                                               name="items[{{ $index }}][unit_cost]"
                                               class="form-control unit-cost"
                                               value="{{ $item['unit_cost'] ?? 0 }}"
                                               min="0"
                                               step="0.01"
                                               required>

                                    </td>


                                    <!-- TOTAL -->

                                    <td>

                                        <input type="text"
                                               class="form-control item-total"
                                               value="0.00"
                                               readonly>

                                    </td>


                                    <!-- REMOVE -->

                                    <td class="text-center">

                                        <button type="button"
                                                class="btn btn-danger remove-item"
                                                title="Remove Item">

                                            <i class="fa fa-trash"></i>

                                        </button>

                                    </td>

                                </tr>

                            @endforeach


                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            <!-- ==============================================
                 NOTES + TOTALS
            =============================================== -->

            <div class="card invoice-card">

                <div class="card-body">

                    <div class="row">


                        <!-- NOTES -->

                        <div class="col-md-7">

                            <h4 class="invoice-section-title">

                                <i class="fa fa-sticky-note-o text-info"></i>

                                Notes

                            </h4>

                            <textarea name="notes"
                                      class="form-control"
                                      rows="7"
                                      placeholder="Enter invoice notes...">{{ old('notes') }}</textarea>

                        </div>


                        <!-- SUMMARY -->

                        <div class="col-md-5">

                            <h4 class="invoice-section-title">

                                <i class="fa fa-calculator text-info"></i>

                                Invoice Summary

                            </h4>


                            <div class="summary-box">


                                <!-- SUBTOTAL -->

                                <div class="summary-row">

                                    <span>
                                        Sub Total
                                    </span>

                                    <strong>
                                        Rs. <span id="subtotal">0.00</span>
                                    </strong>

                                </div>


                                <!-- DISCOUNT -->

                                <div class="mb-3">

                                    <label class="form-label">
                                        Discount
                                    </label>

                                    <input type="number"
                                           name="discount"
                                           id="discount"
                                           class="form-control"
                                           value="{{ old('discount', 0) }}"
                                           min="0"
                                           step="0.01">

                                </div>


                                <!-- TAX -->

                                <div class="mb-3">

                                    <label class="form-label">
                                        Tax
                                    </label>

                                    <input type="number"
                                           name="tax"
                                           id="tax"
                                           class="form-control"
                                           value="{{ old('tax', 0) }}"
                                           min="0"
                                           step="0.01">

                                </div>


                                <!-- ADDITIONAL -->

                                <div class="mb-3">

                                    <label class="form-label">

                                        Additional Charges

                                    </label>

                                    <input type="number"
                                           name="additional_charges"
                                           id="additional_charges"
                                           class="form-control"
                                           value="{{ old('additional_charges', 0) }}"
                                           min="0"
                                           step="0.01">

                                </div>


                                <!-- TOTAL -->

                                <div class="summary-row total-row">

                                    <span class="fw-bold">

                                        Grand Total

                                    </span>

                                    <span class="grand-total">

                                        Rs.

                                        <span id="grandTotal">
                                            0.00
                                        </span>

                                    </span>

                                </div>


                            </div>

                        </div>

                    </div>


                    <!-- ======================================
                         FORM BUTTONS
                    ======================================= -->

                    <div class="text-end mt-4">

                        <a href="{{ route('invoices.index') }}"
                           class="btn btn-secondary me-2">

                            <i class="fa fa-times"></i>

                            Cancel

                        </a>


                        <button type="submit"
                                class="btn btn-success text-white"
                                id="submitInvoice">

                            <i class="fa fa-save"></i>

                            Create Invoice

                        </button>

                    </div>

                </div>

            </div>


        </form>

    </div>

</div>


@include('tenant.footer')


<!-- ==============================================
     JAVASCRIPT
=============================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    let itemIndex =
        document.querySelectorAll('.item-row').length;


    /*
    |--------------------------------------------------------------------------
    | CALCULATE TOTALS
    |--------------------------------------------------------------------------
    */

    function calculateTotals()
    {

        let subtotal = 0;


        document
            .querySelectorAll('.item-row')
            .forEach(function(row) {

                const quantityInput =
                    row.querySelector('.quantity');

                const unitCostInput =
                    row.querySelector('.unit-cost');

                const totalInput =
                    row.querySelector('.item-total');


                let quantity =
                    parseFloat(quantityInput.value) || 0;


                let unitCost =
                    parseFloat(unitCostInput.value) || 0;


                let total =
                    quantity * unitCost;


                totalInput.value =
                    total.toFixed(2);


                subtotal += total;

            });


        /*
        |--------------------------------------------------------------------------
        | DISCOUNT
        |--------------------------------------------------------------------------
        */

        let discount =
            parseFloat(
                document.getElementById('discount').value
            ) || 0;


        /*
        |--------------------------------------------------------------------------
        | TAX
        |--------------------------------------------------------------------------
        */

        let tax =
            parseFloat(
                document.getElementById('tax').value
            ) || 0;


        /*
        |--------------------------------------------------------------------------
        | ADDITIONAL CHARGES
        |--------------------------------------------------------------------------
        */

        let additional =
            parseFloat(
                document.getElementById('additional_charges').value
            ) || 0;


        /*
        |--------------------------------------------------------------------------
        | GRAND TOTAL
        |--------------------------------------------------------------------------
        */

        let grandTotal =
            subtotal
            - discount
            + tax
            + additional;


        /*
        |--------------------------------------------------------------------------
        | PREVENT NEGATIVE TOTAL
        |--------------------------------------------------------------------------
        */

        if (grandTotal < 0) {

            grandTotal = 0;

        }


        document.getElementById('subtotal').innerText =
            subtotal.toFixed(2);


        document.getElementById('grandTotal').innerText =
            grandTotal.toFixed(2);

    }


    /*
    |--------------------------------------------------------------------------
    | ADD NEW ITEM
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('addItem')
        .addEventListener('click', function () {


            const tbody =
                document.getElementById('itemsBody');


            const row =
                document.createElement('tr');


            row.className =
                'item-row';


            row.innerHTML = `

                <td>

                    <input type="text"
                           name="items[${itemIndex}][description]"
                           class="form-control"
                           placeholder="Enter item description"
                           required>

                </td>


                <td>

                    <input type="number"
                           name="items[${itemIndex}][quantity]"
                           class="form-control quantity"
                           value="1"
                           min="1"
                           step="1"
                           required>

                </td>


                <td>

                    <input type="number"
                           name="items[${itemIndex}][unit_cost]"
                           class="form-control unit-cost"
                           value="0"
                           min="0"
                           step="0.01"
                           required>

                </td>


                <td>

                    <input type="text"
                           class="form-control item-total"
                           value="0.00"
                           readonly>

                </td>


                <td class="text-center">

                    <button type="button"
                            class="btn btn-danger remove-item"
                            title="Remove Item">

                        <i class="fa fa-trash"></i>

                    </button>

                </td>

            `;


            tbody.appendChild(row);


            itemIndex++;


            calculateTotals();

        });


    /*
    |--------------------------------------------------------------------------
    | REMOVE ITEM
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function (e) {


        const removeButton =
            e.target.closest('.remove-item');


        if (!removeButton) {

            return;

        }


        const rows =
            document.querySelectorAll('.item-row');


        /*
        |--------------------------------------------------------------------------
        | ALWAYS KEEP AT LEAST ONE ROW
        |--------------------------------------------------------------------------
        */

        if (rows.length <= 1) {

            alert('At least one invoice item is required.');

            return;

        }


        const row =
            removeButton.closest('.item-row');


        row.remove();


        calculateTotals();

    });


    /*
    |--------------------------------------------------------------------------
    | LIVE CALCULATION
    |--------------------------------------------------------------------------
    */

    document.addEventListener('input', function (e) {


        if (

            e.target.classList.contains('quantity') ||

            e.target.classList.contains('unit-cost') ||

            e.target.id === 'discount' ||

            e.target.id === 'tax' ||

            e.target.id === 'additional_charges'

        ) {

            calculateTotals();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | PREVENT DISCOUNT GREATER THAN SUBTOTAL
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('discount')
        .addEventListener('input', function () {

            let subtotal = 0;


            document
                .querySelectorAll('.item-row')
                .forEach(function(row) {

                    const quantity =
                        parseFloat(
                            row.querySelector('.quantity').value
                        ) || 0;


                    const unitCost =
                        parseFloat(
                            row.querySelector('.unit-cost').value
                        ) || 0;


                    subtotal +=
                        quantity * unitCost;

                });


            let discount =
                parseFloat(this.value) || 0;


            if (discount > subtotal) {

                this.value =
                    subtotal.toFixed(2);

            }


            calculateTotals();

        });


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT PROTECTION
    |--------------------------------------------------------------------------
    */

    document
        .getElementById('invoiceForm')
        .addEventListener('submit', function () {

            const submitButton =
                document.getElementById('submitInvoice');


            submitButton.disabled =
                true;


            submitButton.innerHTML = `

                <i class="fa fa-spinner fa-spin"></i>

                Creating Invoice...

            `;

        });


    /*
    |--------------------------------------------------------------------------
    | INITIAL CALCULATION
    |--------------------------------------------------------------------------
    */

    calculateTotals();

});

</script>


</body>

</html>
