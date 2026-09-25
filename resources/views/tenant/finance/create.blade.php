<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Finance Management</title>

    <link rel="icon"
          type="image/png"
          href="{{ asset('assets/images/favicon.png') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/dist/css/style.min.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/dist/css/pages/dashboard1.css') }}">
</head>

<body>

@include('tenant.nav')

<div class="container-fluid mt-4">

    {{-- ================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================= --}}

    <div class="row page-titles">

        <div class="col-md-8">

            <h4 class="text-themecolor">

                <i class="fa fa-money me-2"></i>

                Finance Management

            </h4>

            <p class="text-muted mb-0">

                Complete financial management for your banquet.

            </p>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- SUCCESS --}}
    {{-- ================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">

            <i class="fa fa-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- ================================================= --}}
    {{-- ERRORS --}}
    {{-- ================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ================================================= --}}
    {{-- MAIN CARD --}}
    {{-- ================================================= --}}

    <div class="card">

        <div class="card-body">

            <h4 class="card-title mb-1">
                Finance Transaction
            </h4>

            <h6 class="card-subtitle mb-4">
                Enter financial transaction details.
            </h6>


            <form method="POST"
                  action="{{ route('tenant.finance.store') }}"
                  id="financeForm">

                @csrf


                {{-- ================================================= --}}
                {{-- TRANSACTION TYPE + CATEGORY --}}
                {{-- ================================================= --}}

                <div class="row">

                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | HARD-CODED TRANSACTION TYPES
                        |--------------------------------------------------------------------------
                        |
                        | These are NOT stored in tenant_finance_masters.
                        |
                        */

                        $hardcodedTransactionTypes = [

                            [
                                'key' => 'customer_payment',
                                'name' => 'Banquet Payment (Customer)',
                                'direction' => 'cr',
                            ],

                            [
                                'key' => 'vendor_payment',
                                'name' => 'Vendor Payment',
                                'direction' => 'dr',
                            ],

                            [
                                'key' => 'banquet_expense',
                                'name' => 'Banquet Expense',
                                'direction' => 'dr',
                            ],

                        ];

                    @endphp


                    {{-- TRANSACTION TYPE --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Select Transaction Type

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select name="transaction_type"
                                id="transaction_type"
                                class="form-control"
                                required>

                            <option value="">
                                Select Transaction Type
                            </option>


                            @foreach($hardcodedTransactionTypes as $type)

                                <option value="{{ $type['key'] }}"
                                        data-direction="{{ $type['direction'] }}"
                                        {{ old('transaction_type') === $type['key'] ? 'selected' : '' }}>

                                    {{ $type['name'] }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- PAYMENT CATEGORY --}}

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Payment Category / Reason

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <select name="payment_category_id"
                                id="payment_category_id"
                                class="form-control"
                                required
                                disabled>

                            <option value="">
                                Select Transaction Type First
                            </option>

                        </select>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- AUTOMATIC DIRECTION --}}
                {{-- ================================================= --}}

                <input type="hidden"
                       name="transaction_direction"
                       id="transaction_direction"
                       value="{{ old('transaction_direction') }}">


                {{-- ================================================= --}}
                {{-- CUSTOMER PAYMENT --}}
                {{-- ================================================= --}}

                <div id="customerPaymentSection"
                     class="type-section"
                     style="display:none;">

                    <div class="row">


                        {{-- CUSTOMER --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Customer
                            </label>


                            <select name="customer_id"
                                    id="customer_id"
                                    class="form-control">

                                <option value="">
                                    Select Customer
                                </option>


                                @foreach($customers as $customer)

                                    <option value="{{ $customer->id }}"
                                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}>

                                        {{ $customer->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- JOB NUMBER --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Job Number
                            </label>


                            <select id="booking_id_select"
                                    class="form-control"
                                    disabled>

                                <option value="">
                                    Select Customer First
                                </option>

                            </select>


                            <input type="hidden"
                                   name="booking_id"
                                   id="booking_id"
                                   value="{{ old('booking_id') }}">


                            <input type="hidden"
                                   name="job_number"
                                   id="job_number"
                                   value="{{ old('job_number') }}">

                        </div>


                        {{-- BILL NUMBER --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Bill Number
                            </label>


                            <select id="invoice_id_select"
                                    class="form-control"
                                    disabled>

                                <option value="">
                                    Select Job First
                                </option>

                            </select>


                            <input type="hidden"
                                   name="invoice_id"
                                   id="invoice_id"
                                   value="{{ old('invoice_id') }}">


                            <input type="hidden"
                                   name="bill_number"
                                   id="bill_number"
                                   value="{{ old('bill_number') }}">

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- INVOICE SUMMARY --}}
                    {{-- ================================================= --}}

                    <div class="row"
                         id="invoiceSummary"
                         style="display:none;">


                        {{-- INVOICE TOTAL --}}

                        <div class="col-md-3 mb-3">

                            <div class="card border h-100">

                                <div class="card-body py-3">

                                    <small class="text-muted">
                                        Invoice Total
                                    </small>

                                    <h5 class="mb-0"
                                        id="invoiceGrandTotal">
                                        0.00
                                    </h5>

                                </div>

                            </div>

                        </div>


                        {{-- PAY AMOUNT --}}

                        <div class="col-md-3 mb-3">

                            <div class="card border h-100">

                                <div class="card-body py-3">

                                    <small class="text-muted">
                                        Invoice Pay Amount
                                    </small>

                                    <h5 class="mb-0 text-primary"
                                        id="invoicePayAmount">
                                        0.00
                                    </h5>

                                </div>

                            </div>

                        </div>


                        {{-- ALREADY PAID --}}

                        <div class="col-md-3 mb-3">

                            <div class="card border h-100">

                                <div class="card-body py-3">

                                    <small class="text-muted">
                                        Already Paid
                                    </small>

                                    <h5 class="mb-0 text-success"
                                        id="invoicePaidAmount">
                                        0.00
                                    </h5>

                                </div>

                            </div>

                        </div>


                        {{-- REMAINING --}}

                        <div class="col-md-3 mb-3">

                            <div class="card border h-100">

                                <div class="card-body py-3">

                                    <small class="text-muted">
                                        Invoice Remaining
                                    </small>

                                    <h5 class="mb-0 text-danger"
                                        id="invoiceRemainingAmount">
                                        0.00
                                    </h5>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- CURRENT PAYMENT SUMMARY --}}
                    {{-- ================================================= --}}

                    <div class="row"
                         id="currentPaymentSummary"
                         style="display:none;">


                        <div class="col-md-6 mb-3">

                            <div class="card border">

                                <div class="card-body py-3">

                                    <small class="text-muted">
                                        Current Payment
                                    </small>

                                    <h5 class="mb-0 text-success"
                                        id="currentPaymentAmountDisplay">

                                        0.00

                                    </h5>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <div class="card border">

                                <div class="card-body py-3">

                                    <small class="text-muted">
                                        Remaining After Current Payment
                                    </small>

                                    <h5 class="mb-0 text-danger"
                                        id="remainingAfterCurrentPayment">

                                        0.00

                                    </h5>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- VENDOR PAYMENT --}}
                {{-- ================================================= --}}

                <div id="vendorPaymentSection"
                     class="type-section"
                     style="display:none;">

                    <div class="row">


                        {{-- VENDOR --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Vendor Name
                            </label>


                            <select name="vendor_id"
                                    id="vendor_id"
                                    class="form-control">

                                <option value="">
                                    Select Vendor
                                </option>


                                @foreach($vendors as $vendor)

                                    <option value="{{ $vendor->id }}"
                                            {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>

                                        {{ $vendor->vendor_name }}

                                        @if($vendor->contact_person)

                                            — {{ $vendor->contact_person }}

                                        @endif

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- VENDOR INVOICE --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Vendor Invoice
                            </label>


                            <input type="text"
                                   name="vendor_invoice"
                                   id="vendor_invoice"
                                   class="form-control"
                                   value="{{ old('vendor_invoice') }}"
                                   placeholder="Enter vendor invoice number">

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- BANQUET EXPENSE --}}
                {{-- ================================================= --}}

                <div id="banquetExpenseSection"
                     class="type-section"
                     style="display:none;">

                    <div class="row">


                        {{-- BENEFICIARY --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Beneficiary
                            </label>


                            <select name="beneficiary_id"
                                    id="beneficiary_id"
                                    class="form-control">

                                <option value="">
                                    Select Beneficiary
                                </option>


                                @foreach(($masters['beneficiary'] ?? collect())->where('is_active', true) as $beneficiary)

                                    <option value="{{ $beneficiary->id }}"
                                            {{ old('beneficiary_id') == $beneficiary->id ? 'selected' : '' }}>

                                        {{ $beneficiary->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- ITEM DESCRIPTION --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Item Name / Description
                            </label>


                            <input type="text"
                                   name="item_description"
                                   id="item_description"
                                   class="form-control"
                                   value="{{ old('item_description') }}"
                                   placeholder="Enter expense item or description">

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- COMMON FINANCE SECTION --}}
                {{-- ================================================= --}}

                <div id="commonFinanceSection"
                     style="display:none;">


                    <div class="row">


                        {{-- BANK --}}

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Bank
                            </label>


                            <select name="tenant_bank_id"
                                    id="tenant_bank_id"
                                    class="form-control">

                                <option value="">
                                    Select Tenant Bank
                                </option>


                                @foreach($banks as $bank)

                                    <option value="{{ $bank->id }}"
                                            {{ old('tenant_bank_id') == $bank->id ? 'selected' : '' }}>

                                        {{ $bank->bank_name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- CHEQUE NUMBER --}}

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Cheque Number / TID / PO#
                            </label>


                            <input type="text"
                                   name="cheque_number"
                                   id="cheque_number"
                                   class="form-control"
                                   value="{{ old('cheque_number') }}"
                                   placeholder="Cheque / TID / PO#">

                        </div>


                        {{-- CHEQUE DATE --}}

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Cheque Date
                            </label>


                            <input type="date"
                                   name="cheque_date"
                                   id="cheque_date"
                                   class="form-control"
                                   value="{{ old('cheque_date') }}">

                        </div>


                        {{-- TRANSACTION FLOW --}}

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Transaction Flow
                            </label>


                            <input type="text"
                                   id="transactionFlowDisplay"
                                   class="form-control"
                                   value=""
                                   readonly>

                        </div>

                    </div>


                    <div class="row">


                        {{-- AMOUNT --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Amount

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input type="number"
                                   name="amount"
                                   id="amount"
                                   class="form-control"
                                   value="{{ old('amount') }}"
                                   min="0.01"
                                   step="0.01"
                                   placeholder="0.00"
                                   required>


                            <small id="amountHelp"
                                   class="text-muted">

                                For customer payment, invoice Pay Amount will be loaded automatically.

                            </small>


                            <div id="amountError"
                                 class="text-danger mt-1"
                                 style="display:none;">

                                Payment amount cannot be greater than invoice remaining amount.

                            </div>

                        </div>


                        {{-- TRANSACTION DATE --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Transaction Date

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input type="date"
                                   name="transaction_date"
                                   id="transaction_date"
                                   class="form-control"
                                   value="{{ old('transaction_date', date('Y-m-d')) }}"
                                   required>

                        </div>


                        {{-- REMARKS --}}

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Remarks
                            </label>


                            <input type="text"
                                   name="remarks"
                                   id="remarks"
                                   class="form-control"
                                   value="{{ old('remarks') }}"
                                   placeholder="Enter remarks">

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- BUTTONS --}}
                {{-- ================================================= --}}

                <div class="row mt-3">

                    <div class="col-md-12 text-end">

                        <button type="reset"
                                class="btn btn-secondary me-2"
                                id="resetFinanceForm">

                            <i class="fa fa-refresh me-1"></i>

                            Reset

                        </button>


                        <button type="submit"
                                class="btn btn-primary"
                                id="saveFinanceTransaction">

                            <i class="fa fa-save me-1"></i>

                            Save Finance Transaction

                        </button>

                    </div>

                </div>


            </form>

        </div>

    </div>

</div>


@include('tenant.footer')


{{-- ================================================= --}}
{{-- FINANCE CATEGORIES --}}
{{-- ================================================= --}}

@php

    $financeCategories =

        ($masters['payment_category'] ?? collect())

            ->where('is_active', true)

            ->map(function ($category) {

                return [

                    'id' =>
                        $category->id,

                    'name' =>
                        $category->name,

                    'parent_key' =>
                        $category->parent_key,

                ];

            })

            ->values()

            ->all();


    $oldCustomerId =
        old('customer_id');


    $oldBookingId =
        old('booking_id');


    $oldInvoiceId =
        old('invoice_id');


    $oldPaymentCategoryId =
        old('payment_category_id');


    $oldAmount =
        old('amount');

@endphp


<script>

document.addEventListener('DOMContentLoaded', function () {


    /* ============================================================
       ELEMENTS
    ============================================================ */


    const transactionType =
        document.getElementById('transaction_type');


    const paymentCategory =
        document.getElementById('payment_category_id');


    const direction =
        document.getElementById('transaction_direction');


    const transactionFlowDisplay =
        document.getElementById('transactionFlowDisplay');


    const customerSection =
        document.getElementById('customerPaymentSection');


    const vendorSection =
        document.getElementById('vendorPaymentSection');


    const expenseSection =
        document.getElementById('banquetExpenseSection');


    const commonSection =
        document.getElementById('commonFinanceSection');


    const customer =
        document.getElementById('customer_id');


    const booking =
        document.getElementById('booking_id_select');


    const bookingIdInput =
        document.getElementById('booking_id');


    const invoice =
        document.getElementById('invoice_id_select');


    const invoiceIdInput =
        document.getElementById('invoice_id');


    const jobNumber =
        document.getElementById('job_number');


    const billNumber =
        document.getElementById('bill_number');


    const amount =
        document.getElementById('amount');


    const amountHelp =
        document.getElementById('amountHelp');


    const amountError =
        document.getElementById('amountError');


    const invoiceSummary =
        document.getElementById('invoiceSummary');


    const invoiceGrandTotal =
        document.getElementById('invoiceGrandTotal');


    const invoicePayAmount =
        document.getElementById('invoicePayAmount');


    const invoicePaidAmount =
        document.getElementById('invoicePaidAmount');


    const invoiceRemainingAmount =
        document.getElementById('invoiceRemainingAmount');


    const currentPaymentSummary =
        document.getElementById('currentPaymentSummary');


    const currentPaymentAmountDisplay =
        document.getElementById('currentPaymentAmountDisplay');


    const remainingAfterCurrentPayment =
        document.getElementById('remainingAfterCurrentPayment');


    const financeForm =
        document.getElementById('financeForm');


    /* ============================================================
       PHP DATA
    ============================================================ */


    const categories =
        @js($financeCategories);


    const oldCustomerId =
        @js($oldCustomerId);


    const oldBookingId =
        @js($oldBookingId);


    const oldInvoiceId =
        @js($oldInvoiceId);


    const oldPaymentCategoryId =
        @js($oldPaymentCategoryId);


    const oldAmount =
        @js($oldAmount);


    let currentInvoiceRemaining = null;

    let currentInvoicePayAmount = 0;


    /* ============================================================
       MONEY FORMAT
    ============================================================ */


    function formatMoney(value) {

        return Number(value ?? 0).toLocaleString(
            'en-US',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );

    }


    /* ============================================================
       ENABLE / DISABLE SECTION
    ============================================================ */


    function setSectionEnabled(
        section,
        enabled
    ) {

        if (!section) {
            return;
        }


        section.style.display =
            enabled
                ? ''
                : 'none';


        section
            .querySelectorAll(
                'input, select, textarea'
            )
            .forEach(function (field) {

                field.disabled =
                    !enabled;

            });

    }


    /* ============================================================
       CLEAR SECTION
    ============================================================ */


    function clearSection(section) {

        if (!section) {
            return;
        }


        section
            .querySelectorAll(
                'input, select, textarea'
            )
            .forEach(function (field) {


                if (
                    field.type === 'hidden'
                ) {

                    field.value = '';

                }


                else if (
                    field.tagName === 'SELECT'
                ) {

                    field.selectedIndex = 0;

                }


                else if (
                    field.type !== 'date'
                ) {

                    field.value = '';

                }

            });

    }


    /* ============================================================
       RESET INVOICE SUMMARY
    ============================================================ */


    function resetInvoiceSummary() {

        currentInvoiceRemaining =
            null;


        currentInvoicePayAmount =
            0;


        if (invoiceSummary) {

            invoiceSummary.style.display =
                'none';

        }


        if (currentPaymentSummary) {

            currentPaymentSummary.style.display =
                'none';

        }


        if (invoiceGrandTotal) {

            invoiceGrandTotal.textContent =
                '0.00';

        }


        if (invoicePayAmount) {

            invoicePayAmount.textContent =
                '0.00';

        }


        if (invoicePaidAmount) {

            invoicePaidAmount.textContent =
                '0.00';

        }


        if (invoiceRemainingAmount) {

            invoiceRemainingAmount.textContent =
                '0.00';

        }


        if (currentPaymentAmountDisplay) {

            currentPaymentAmountDisplay.textContent =
                '0.00';

        }


        if (remainingAfterCurrentPayment) {

            remainingAfterCurrentPayment.textContent =
                '0.00';

        }


        if (amountError) {

            amountError.style.display =
                'none';

        }

    }


    /* ============================================================
       CURRENT PAYMENT SUMMARY
    ============================================================ */


    function updateCurrentPaymentSummary() {

        if (
            currentInvoiceRemaining === null
        ) {

            if (currentPaymentSummary) {

                currentPaymentSummary.style.display =
                    'none';

            }

            return;

        }


        const currentPayment =
            Number(
                amount?.value || 0
            );


        const remaining =
            Number(
                currentInvoiceRemaining || 0
            );


        const remainingAfter =
            Math.max(
                remaining -
                currentPayment,
                0
            );


        if (currentPaymentSummary) {

            currentPaymentSummary.style.display =
                '';

        }


        if (currentPaymentAmountDisplay) {

            currentPaymentAmountDisplay.textContent =
                formatMoney(
                    currentPayment
                );

        }


        if (remainingAfterCurrentPayment) {

            remainingAfterCurrentPayment.textContent =
                formatMoney(
                    remainingAfter
                );

        }


        if (
            currentPayment >
            remaining
        ) {

            if (amountError) {

                amountError.style.display =
                    '';

            }

        }
        else {

            if (amountError) {

                amountError.style.display =
                    'none';

            }

        }

    }


    /* ============================================================
       SHOW INVOICE SUMMARY
    ============================================================ */


    function showInvoiceSummary(bill) {

        const grandTotal =
            Number(
                bill.grand_total ?? 0
            );


        const payAmount =
            Number(
                bill.pay_amount ?? 0
            );


        const paidAmount =
            Number(
                bill.paid_amount ?? 0
            );


        const remainingAmount =
            Number(
                bill.remaining_amount ?? 0
            );


        currentInvoiceRemaining =
            remainingAmount;


        currentInvoicePayAmount =
            payAmount;


        if (invoiceSummary) {

            invoiceSummary.style.display =
                '';

        }


        if (invoiceGrandTotal) {

            invoiceGrandTotal.textContent =
                formatMoney(
                    grandTotal
                );

        }


        if (invoicePayAmount) {

            invoicePayAmount.textContent =
                formatMoney(
                    payAmount
                );

        }


        if (invoicePaidAmount) {

            invoicePaidAmount.textContent =
                formatMoney(
                    paidAmount
                );

        }


        if (invoiceRemainingAmount) {

            invoiceRemainingAmount.textContent =
                formatMoney(
                    remainingAmount
                );

        }


        /*
        |--------------------------------------------------------------------------
        | DEFAULT AMOUNT
        |--------------------------------------------------------------------------
        */

        if (amount) {

            if (
                oldAmount === null ||
                oldAmount === ''
            ) {

                amount.value =
                    payAmount.toFixed(2);

            }

        }


        updateCurrentPaymentSummary();

    }


    /* ============================================================
       SELECTED TRANSACTION TYPE
    ============================================================ */


    function getSelectedType() {

        const option =
            transactionType?.options[
                transactionType.selectedIndex
            ];


        if (
            !option ||
            !option.value
        ) {

            return {

                key: '',
                name: '',
                direction: ''

            };

        }


        return {

            key:
                option.value,

            name:
                option.textContent.trim(),

            direction:
                option.dataset.direction || ''

        };

    }


    /* ============================================================
       LOAD PAYMENT CATEGORIES
    ============================================================ */


    function loadPaymentCategories(
        typeKey
    ) {

        if (!paymentCategory) {

            return;

        }


        paymentCategory.innerHTML =
            '<option value="">Select Payment Category</option>';


        paymentCategory.disabled =
            true;


        if (!typeKey) {

            return;

        }


        const filtered =
            categories.filter(
                function (category) {

                    return String(
                        category.parent_key || ''
                    ) === String(
                        typeKey
                    );

                }
            );


        if (!filtered.length) {

            paymentCategory.innerHTML =
                '<option value="">No categories configured for this transaction type</option>';

            return;

        }


        filtered.forEach(
            function (category) {

                const option =
                    document.createElement(
                        'option'
                    );


                option.value =
                    category.id;


                option.textContent =
                    category.name;


                if (

                    oldPaymentCategoryId !== null &&

                    oldPaymentCategoryId !== '' &&

                    String(
                        oldPaymentCategoryId
                    ) === String(
                        category.id
                    )

                ) {

                    option.selected =
                        true;

                }


                paymentCategory.appendChild(
                    option
                );

            }
        );


        paymentCategory.disabled =
            false;

    }


    /* ============================================================
       UPDATE TRANSACTION TYPE UI
    ============================================================ */


    function updateTransactionTypeUI() {

        const selected =
            getSelectedType();


        setSectionEnabled(
            customerSection,
            false
        );


        setSectionEnabled(
            vendorSection,
            false
        );


        setSectionEnabled(
            expenseSection,
            false
        );


        setSectionEnabled(
            commonSection,
            false
        );


        resetInvoiceSummary();


        if (!selected.key) {

            if (direction) {

                direction.value =
                    '';

            }


            if (transactionFlowDisplay) {

                transactionFlowDisplay.value =
                    '';

            }


            if (paymentCategory) {

                paymentCategory.innerHTML =
                    '<option value="">Select Transaction Type First</option>';

                paymentCategory.disabled =
                    true;

            }


            return;

        }


        /* ========================================================
           DIRECTION
        ======================================================== */

        if (direction) {

            direction.value =
                selected.direction;

        }


        if (transactionFlowDisplay) {

            transactionFlowDisplay.value =
                selected.direction === 'cr'

                    ? 'Credit (CR) — Money Received'

                    : 'Debit (DR) — Money Paid';

        }


        /* ========================================================
           COMMON SECTION
        ======================================================== */

        setSectionEnabled(
            commonSection,
            true
        );


        /* ========================================================
           CUSTOMER PAYMENT
        ======================================================== */

        if (
            selected.key ===
            'customer_payment'
        ) {

            setSectionEnabled(
                customerSection,
                true
            );


            if (amountHelp) {

                amountHelp.textContent =
                    'Select a bill. Invoice Pay Amount will be loaded automatically.';

            }

        }


        /* ========================================================
           VENDOR PAYMENT
        ======================================================== */

        else if (
            selected.key ===
            'vendor_payment'
        ) {

            setSectionEnabled(
                vendorSection,
                true
            );


            if (amountHelp) {

                amountHelp.textContent =
                    'Enter the amount paid to the vendor.';

            }

        }


        /* ========================================================
           BANQUET EXPENSE
        ======================================================== */

        else if (
            selected.key ===
            'banquet_expense'
        ) {

            setSectionEnabled(
                expenseSection,
                true
            );


            if (amountHelp) {

                amountHelp.textContent =
                    'Enter the banquet expense amount.';

            }

        }


        /* ========================================================
           PAYMENT CATEGORY
        ======================================================== */

        loadPaymentCategories(
            selected.key
        );

    }


    /* ============================================================
       LOAD CUSTOMER JOBS
    ============================================================ */


    async function loadCustomerJobs(
        customerId,
        restoreBooking = null
    ) {

        if (
            !customerId ||
            !booking
        ) {

            return;

        }


        booking.innerHTML =
            '<option value="">Loading Jobs...</option>';


        invoice.innerHTML =
            '<option value="">Select Job First</option>';


        booking.disabled =
            true;


        invoice.disabled =
            true;


        bookingIdInput.value =
            '';


        invoiceIdInput.value =
            '';


        jobNumber.value =
            '';


        billNumber.value =
            '';


        resetInvoiceSummary();


        try {

            const response =
                await fetch(
                    `/tenant/finance/customer/${customerId}/jobs`,
                    {
                        headers: {

                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'

                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Unable to load jobs.'
                );

            }


            const data =
                await response.json();


            const jobs =
                Array.isArray(data)

                    ? data

                    : (
                        Array.isArray(
                            data.jobs
                        )

                            ? data.jobs

                            : []
                    );


            booking.innerHTML =
                '<option value="">Select Job</option>';


            jobs.forEach(
                function (job) {

                    const option =
                        document.createElement(
                            'option'
                        );


                    option.value =
                        job.id;


                    option.textContent =
                        `${job.job_number ?? ''} | ${job.event_type ?? ''} | ${job.booking_date ?? ''}`;


                    option.dataset.jobNumber =
                        job.job_number ?? '';


                    if (
                        restoreBooking &&
                        String(job.id) ===
                        String(restoreBooking)
                    ) {

                        option.selected =
                            true;

                    }


                    booking.appendChild(
                        option
                    );

                }
            );


            booking.disabled =
                jobs.length === 0;


            if (!jobs.length) {

                booking.innerHTML =
                    '<option value="">No Jobs Found</option>';

            }


            else if (restoreBooking) {

                booking.dispatchEvent(
                    new Event('change')
                );

            }

        }
        catch (error) {

            console.error(error);


            booking.innerHTML =
                '<option value="">Failed to load jobs</option>';

        }

    }


    /* ============================================================
       LOAD BOOKING BILLS
    ============================================================ */


    async function loadBookingBills(
        bookingId,
        restoreInvoice = null
    ) {

        if (
            !bookingId ||
            !invoice
        ) {

            return;

        }


        invoice.innerHTML =
            '<option value="">Loading Bills...</option>';


        invoice.disabled =
            true;


        billNumber.value =
            '';


        resetInvoiceSummary();


        try {

            const response =
                await fetch(
                    `/tenant/finance/booking/${bookingId}/bills`,
                    {
                        headers: {

                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'

                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Unable to load bills.'
                );

            }


            const data =
                await response.json();


            const bills =
                Array.isArray(data)

                    ? data

                    : (
                        Array.isArray(
                            data.bills
                        )

                            ? data.bills

                            : []
                    );


            invoice.innerHTML =
                '<option value="">Select Bill</option>';


            bills.forEach(
                function (bill) {

                    const option =
                        document.createElement(
                            'option'
                        );


                    option.value =
                        bill.id;


                    option.textContent =
                        `${bill.invoice_number ?? ''} | Total: ${formatMoney(bill.grand_total ?? 0)} | Pay Amount: ${formatMoney(bill.pay_amount ?? 0)} | Paid: ${formatMoney(bill.paid_amount ?? 0)} | Remaining: ${formatMoney(bill.remaining_amount ?? 0)}`;


                    option.dataset.billNumber =
                        bill.invoice_number ?? '';


                    option.dataset.grandTotal =
                        bill.grand_total ?? 0;


                    option.dataset.payAmount =
                        bill.pay_amount ?? 0;


                    option.dataset.paidAmount =
                        bill.paid_amount ?? 0;


                    option.dataset.remainingAmount =
                        bill.remaining_amount ?? 0;


                    if (
                        restoreInvoice &&
                        String(bill.id) ===
                        String(restoreInvoice)
                    ) {

                        option.selected =
                            true;

                    }


                    invoice.appendChild(
                        option
                    );

                }
            );


            invoice.disabled =
                bills.length === 0;


            if (!bills.length) {

                invoice.innerHTML =
                    '<option value="">No Bills Found</option>';

            }


            else if (restoreInvoice) {

                invoice.dispatchEvent(
                    new Event('change')
                );

            }

        }
        catch (error) {

            console.error(error);


            invoice.innerHTML =
                '<option value="">Failed to load bills</option>';

        }

    }


    /* ============================================================
       TRANSACTION TYPE CHANGE
    ============================================================ */


    if (transactionType) {

        transactionType.addEventListener(
            'change',
            function () {

                const selected =
                    getSelectedType();


                if (
                    selected.key !==
                    'customer_payment'
                ) {

                    clearSection(
                        customerSection
                    );


                    if (booking) {

                        booking.disabled =
                            true;

                    }


                    if (invoice) {

                        invoice.disabled =
                            true;

                    }

                }


                if (
                    selected.key !==
                    'vendor_payment'
                ) {

                    clearSection(
                        vendorSection
                    );

                }


                if (
                    selected.key !==
                    'banquet_expense'
                ) {

                    clearSection(
                        expenseSection
                    );

                }


                updateTransactionTypeUI();

            }
        );

    }


    /* ============================================================
       CUSTOMER CHANGE
    ============================================================ */


    if (customer) {

        customer.addEventListener(
            'change',
            function () {

                loadCustomerJobs(
                    this.value
                );

            }
        );

    }


    /* ============================================================
       JOB CHANGE
    ============================================================ */


    if (booking) {

        booking.addEventListener(
            'change',
            function () {

                const selected =
                    this.options[
                        this.selectedIndex
                    ];


                bookingIdInput.value =
                    this.value || '';


                jobNumber.value =
                    selected?.dataset?.jobNumber || '';


                loadBookingBills(
                    this.value,
                    null
                );

            }
        );

    }


    /* ============================================================
       INVOICE CHANGE
    ============================================================ */


    if (invoice) {

        invoice.addEventListener(
            'change',
            function () {

                const selected =
                    this.options[
                        this.selectedIndex
                    ];


                invoiceIdInput.value =
                    this.value || '';


                billNumber.value =
                    selected?.dataset?.billNumber || '';


                if (this.value) {

                    showInvoiceSummary({

                        grand_total:
                            selected?.dataset?.grandTotal ?? 0,

                        pay_amount:
                            selected?.dataset?.payAmount ?? 0,

                        paid_amount:
                            selected?.dataset?.paidAmount ?? 0,

                        remaining_amount:
                            selected?.dataset?.remainingAmount ?? 0

                    });

                }
                else {

                    resetInvoiceSummary();

                }

            }
        );

    }


    /* ============================================================
       AMOUNT INPUT
    ============================================================ */


    if (amount) {

        amount.addEventListener(
            'input',
            function () {

                updateCurrentPaymentSummary();

            }
        );

    }


    /* ============================================================
       FORM SUBMIT
    ============================================================ */


    if (financeForm) {

        financeForm.addEventListener(
            'submit',
            function (event) {

                const selected =
                    getSelectedType();


                if (!selected.key) {

                    event.preventDefault();

                    transactionType?.focus();

                    return;

                }


                if (direction) {

                    direction.value =
                        selected.direction;

                }


                /* ==================================================
                   CUSTOMER PAYMENT VALIDATION
                ================================================== */

                if (
                    selected.key ===
                    'customer_payment'
                ) {

                    const enteredAmount =
                        Number(
                            amount?.value || 0
                        );


                    if (!customer?.value) {

                        event.preventDefault();

                        alert(
                            'Please select a customer.'
                        );

                        customer?.focus();

                        return;

                    }


                    if (!bookingIdInput.value) {

                        event.preventDefault();

                        alert(
                            'Please select a Job Number.'
                        );

                        booking?.focus();

                        return;

                    }


                    if (!invoiceIdInput.value) {

                        event.preventDefault();

                        alert(
                            'Please select a Bill / Invoice.'
                        );

                        invoice?.focus();

                        return;

                    }


                    if (enteredAmount <= 0) {

                        event.preventDefault();

                        alert(
                            'Please enter a valid payment amount.'
                        );

                        amount?.focus();

                        return;

                    }


                    if (
                        currentInvoiceRemaining !== null &&
                        enteredAmount >
                            Number(
                                currentInvoiceRemaining
                            )
                    ) {

                        event.preventDefault();


                        if (amountError) {

                            amountError.style.display =
                                '';

                        }


                        amount?.focus();

                        return;

                    }

                }

            }
        );

    }


    /* ============================================================
       RESET
    ============================================================ */


    const resetButton =
        document.getElementById(
            'resetFinanceForm'
        );


    if (resetButton) {

        resetButton.addEventListener(
            'click',
            function () {

                setTimeout(
                    function () {


                        if (transactionType) {

                            transactionType.value =
                                '';

                        }


                        clearSection(
                            customerSection
                        );


                        clearSection(
                            vendorSection
                        );


                        clearSection(
                            expenseSection
                        );


                        setSectionEnabled(
                            customerSection,
                            false
                        );


                        setSectionEnabled(
                            vendorSection,
                            false
                        );


                        setSectionEnabled(
                            expenseSection,
                            false
                        );


                        setSectionEnabled(
                            commonSection,
                            false
                        );


                        if (direction) {

                            direction.value =
                                '';

                        }


                        if (transactionFlowDisplay) {

                            transactionFlowDisplay.value =
                                '';

                        }


                        if (paymentCategory) {

                            paymentCategory.innerHTML =
                                '<option value="">Select Transaction Type First</option>';


                            paymentCategory.disabled =
                                true;

                        }


                        if (booking) {

                            booking.innerHTML =
                                '<option value="">Select Customer First</option>';


                            booking.disabled =
                                true;

                        }


                        if (invoice) {

                            invoice.innerHTML =
                                '<option value="">Select Job First</option>';


                            invoice.disabled =
                                true;

                        }


                        if (bookingIdInput) {

                            bookingIdInput.value =
                                '';

                        }


                        if (invoiceIdInput) {

                            invoiceIdInput.value =
                                '';

                        }


                        if (jobNumber) {

                            jobNumber.value =
                                '';

                        }


                        if (billNumber) {

                            billNumber.value =
                                '';

                        }


                        resetInvoiceSummary();

                    },
                    0
                );

            }
        );

    }


    /* ============================================================
       INITIALIZE
    ============================================================ */

    updateTransactionTypeUI();


    /*
    |--------------------------------------------------------------------------
    | RESTORE PAYMENT CATEGORY
    |--------------------------------------------------------------------------
    |
    | Important when validation fails and old() values exist.
    |
    */

    const initialType =
        getSelectedType();


    if (initialType.key) {

        loadPaymentCategories(
            initialType.key
        );

    }


    /* ============================================================
       RESTORE CUSTOMER / JOB / BILL
    ============================================================ */

    if (
        getSelectedType().key ===
            'customer_payment' &&
        oldCustomerId
    ) {

        loadCustomerJobs(
            oldCustomerId,
            oldBookingId
        );

    }

});

</script>

</body>
</html>