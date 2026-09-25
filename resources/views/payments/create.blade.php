
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        http-equiv="X-UA-Compatible"
        content="IE=edge"
    >

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="VenueFlow - Subscription Invoice Payment"
    >

    <title>Subscription Invoice Payment | VenueFlow</title>

    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="{{ asset('assets/images/favicon.png') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/dist/css/style.min.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/dist/css/pages/dashboard1.css') }}"
    >

    <style>

        .payment-page-card {
            border-radius: 4px;
        }

        .readonly-field {
            background-color: #f8f9fa !important;
        }

        .payment-summary-card {
            border: 1px solid #edf1f5;
            border-radius: 4px;
            height: 100%;
        }

        .payment-summary-card .card-body {
            padding: 20px;
        }

        .payment-summary-label {
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .payment-summary-value {
            font-size: 22px;
            font-weight: 600;
        }

        .tab-content {
            min-height: 350px;
        }

        .invoice-details-box {
            border: 1px solid #edf1f5;
            border-radius: 4px;
            background: #f8f9fa;
            padding: 20px;
        }

        .invoice-detail-label {
            color: #6c757d;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .invoice-detail-value {
            font-size: 15px;
            font-weight: 600;
        }

        .invoice-select option[hidden] {
            display: none;
        }

    </style>

</head>

<body>

@include('admin.nav')

<div class="container-fluid mt-4">

    {{-- PAGE HEADER --}}

    <div class="row page-titles">

        <div class="col-md-8">

            <h4 class="text-themecolor">

                <i class="ti-wallet me-2"></i>

                Subscription Invoice Payment

            </h4>

            <p class="text-muted mb-0">

                Select tenant invoice and record payment

            </p>

        </div>

        <div class="col-md-4 text-end">

            <a
                href="{{ route('admin.subscription-payments.index') }}"
                class="btn btn-secondary"
            >

                <i class="fa fa-arrow-left me-1"></i>

                Back

            </a>

        </div>

    </div>


    {{-- SUCCESS MESSAGE --}}

    @if(session('success'))

        <div class="alert alert-success">

            <i class="fa fa-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- ERROR MESSAGE --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- MAIN CARD --}}

    <div class="row">

        <div class="col-12">

            <div class="card payment-page-card">

                <div class="card-body p-b-0">

                    <h4 class="card-title">

                        Record Subscription Payment

                    </h4>

                    <h6 class="card-subtitle">

                        Select tenant, invoice, bank and payment details

                    </h6>

                </div>


                {{-- FORM --}}

                <form
                    action="{{ route('admin.subscription-payments.store') }}"
                    method="POST"
                    id="subscriptionPaymentForm"
                >

                    @csrf


                    {{-- TABS --}}

                    <ul
                        class="nav nav-tabs customtab"
                        role="tablist"
                    >

                        <li class="nav-item">

                            <a
                                class="nav-link active"
                                data-bs-toggle="tab"
                                href="#invoiceInformation"
                                role="tab"
                            >

                                <span class="hidden-sm-up">

                                    <i class="ti-file"></i>

                                </span>

                                <span class="hidden-xs-down">

                                    Invoice Information

                                </span>

                            </a>

                        </li>

                        <li class="nav-item">

                            <a
                                class="nav-link"
                                data-bs-toggle="tab"
                                href="#paymentForm"
                                role="tab"
                            >

                                <span class="hidden-sm-up">

                                    <i class="ti-wallet"></i>

                                </span>

                                <span class="hidden-xs-down">

                                    Payment Form

                                </span>

                            </a>

                        </li>

                    </ul>


                    <div class="tab-content">


                        {{-- ================================================= --}}
                        {{-- INVOICE INFORMATION TAB --}}
                        {{-- ================================================= --}}

                        <div
                            class="tab-pane active"
                            id="invoiceInformation"
                            role="tabpanel"
                        >

                            <div class="p-20">

                                <div class="row">


                                    {{-- TENANT --}}

                                    <div class="col-md-6 mb-3">

                                        <div class="form-floating">

                                            <select
                                                name="tenant_id"
                                                id="tenant_id"
                                                class="form-select @error('tenant_id') is-invalid @enderror"
                                                required
                                            >

                                                <option value="">

                                                    -- Select Tenant --

                                                </option>

                                                @forelse($tenants as $tenant)

                                                    <option
                                                        value="{{ $tenant->id }}"
                                                        data-subscription-id="{{ optional($tenant->activeSubscription)->id }}"
                                                        data-created-at="{{ optional($tenant->created_at)->format('Y-m-d') }}"
                                                        data-owner-name="{{ $tenant->owner_name }}"
                                                        data-email="{{ $tenant->email }}"
                                                        data-phone="{{ $tenant->phone }}"
                                                        data-address="{{ $tenant->address }}"
                                                        {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}
                                                    >

                                                        {{ $tenant->business_name }}

                                                    </option>

                                                @empty

                                                    <option
                                                        value=""
                                                        disabled
                                                    >

                                                        No Active Tenants Found

                                                    </option>

                                                @endforelse

                                            </select>

                                            <label for="tenant_id">

                                                Tenant Name

                                                <span class="text-danger">*</span>

                                            </label>

                                            @error('tenant_id')

                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>

                                            @enderror

                                        </div>

                                    </div>


                                    {{-- INVOICE --}}

                                    <div class="col-md-6 mb-3">

                                        <div class="form-floating">

                                            <select
                                                name="invoice_id"
                                                id="invoice_id"
                                                class="form-select invoice-select @error('invoice_id') is-invalid @enderror"
                                                required
                                            >

                                                <option value="">

                                                    -- Select Tenant First --

                                                </option>

                                                @forelse($invoices as $invoice)

                                                    <option
                                                        value="{{ $invoice->id }}"
                                                        data-tenant-id="{{ $invoice->tenant_id }}"
                                                        data-subscription-id="{{ $invoice->subscription_id ?? '' }}"
                                                        data-total="{{ $invoice->grand_total }}"
                                                        data-paid="{{ $invoice->paid_amount }}"
                                                        data-remaining="{{ $invoice->remaining_amount }}"
                                                        data-invoice-number="{{ $invoice->invoice_number }}"
                                                        data-invoice-date="{{ optional($invoice->invoice_date)->format('d M Y') }}"
                                                        data-due-date="{{ optional($invoice->due_date)->format('d M Y') }}"
                                                        {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}
                                                    >

                                                        {{ $invoice->invoice_number }}

                                                        |

                                                        Rs.
                                                        {{ number_format($invoice->grand_total, 2) }}

                                                        |

                                                        Remaining:

                                                        Rs.
                                                        {{ number_format($invoice->remaining_amount, 2) }}

                                                    </option>

                                                @empty

                                                    <option
                                                        value=""
                                                        disabled
                                                    >

                                                        No Unpaid or Partial Invoices Found

                                                    </option>

                                                @endforelse

                                            </select>

                                            <label for="invoice_id">

                                                Invoice

                                                <span class="text-danger">*</span>

                                            </label>

                                            @error('invoice_id')

                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>

                                            @enderror

                                        </div>

                                    </div>


                                    {{-- OWNER NAME --}}

                                    <div class="col-md-6 mb-3">

                                        <div class="form-floating">

                                            <input
                                                type="text"
                                                id="owner_name"
                                                class="form-control readonly-field"
                                                readonly
                                            >

                                            <label for="owner_name">

                                                Owner Name

                                            </label>

                                        </div>

                                    </div>


                                    {{-- EMAIL --}}

                                    <div class="col-md-6 mb-3">

                                        <div class="form-floating">

                                            <input
                                                type="email"
                                                id="tenant_email"
                                                class="form-control readonly-field"
                                                readonly
                                            >

                                            <label for="tenant_email">

                                                Email Address

                                            </label>

                                        </div>

                                    </div>


                                    {{-- PHONE --}}

                                    <div class="col-md-6 mb-3">

                                        <div class="form-floating">

                                            <input
                                                type="text"
                                                id="tenant_phone"
                                                class="form-control readonly-field"
                                                readonly
                                            >

                                            <label for="tenant_phone">

                                                Phone

                                            </label>

                                        </div>

                                    </div>


                                    {{-- ACCOUNT CREATION DATE --}}

                                    <div class="col-md-6 mb-3">

                                        <div class="form-floating">

                                            <input
                                                type="date"
                                                id="account_creation_date"
                                                class="form-control readonly-field"
                                                readonly
                                            >

                                            <label for="account_creation_date">

                                                Account Creation Date

                                            </label>

                                        </div>

                                    </div>


                                    {{-- SUBSCRIPTION PLAN DETAILS --}}

                                    <div class="col-md-12 mt-2">

                                        <div class="invoice-details-box">

                                            <h6 class="mb-3">
                                                <i class="ti ti-credit-card me-2"></i>
                                                Subscription Plan Details
                                            </h6>

                                            <div class="row">

                                                {{-- SUBSCRIPTION AMOUNT --}}

                                                <div class="col-md-3 mb-3">

                                                    <div class="invoice-detail-label">

                                                        Subscription Amount

                                                    </div>

                                                    <div
                                                        class="invoice-detail-value"
                                                        id="subscription_amount_display"
                                                    >

                                                        Rs. 0.00

                                                    </div>

                                                </div>


                                                {{-- SUBSCRIPTION START DATE --}}

                                                <div class="col-md-3 mb-3">

                                                    <div class="invoice-detail-label">

                                                        Start Date

                                                    </div>

                                                    <div
                                                        class="invoice-detail-value"
                                                        id="subscription_start_date_display"
                                                    >

                                                        —

                                                    </div>

                                                </div>


                                                {{-- SUBSCRIPTION END DATE --}}

                                                <div class="col-md-3 mb-3">

                                                    <div class="invoice-detail-label">

                                                        End Date

                                                    </div>

                                                    <div
                                                        class="invoice-detail-value"
                                                        id="subscription_end_date_display"
                                                    >

                                                        —

                                                    </div>

                                                </div>


                                                {{-- SUBSCRIPTION STATUS --}}

                                                <div class="col-md-3 mb-3">

                                                    <div class="invoice-detail-label">

                                                        Status

                                                    </div>

                                                    <div
                                                        class="invoice-detail-value"
                                                        id="subscription_status_display"
                                                    >

                                                        —

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- ADDRESS --}}

                                    <div class="col-md-12 mb-3">

                                        <div class="form-floating">

                                            <textarea
                                                id="tenant_address"
                                                class="form-control readonly-field"
                                                style="height: 100px"
                                                readonly
                                            ></textarea>

                                            <label for="tenant_address">

                                                Address

                                            </label>

                                        </div>

                                    </div>


                                    {{-- INVOICE DETAILS --}}

                                    <div class="col-md-12 mt-2">

                                        <div class="invoice-details-box">

                                            <div class="row">


                                                {{-- INVOICE NUMBER --}}

                                                <div class="col-md-3 mb-3">

                                                    <div class="invoice-detail-label">

                                                        Invoice Number

                                                    </div>

                                                    <div
                                                        class="invoice-detail-value"
                                                        id="invoice_number_display"
                                                    >

                                                        —

                                                    </div>

                                                </div>


                                                {{-- INVOICE DATE --}}

                                                <div class="col-md-3 mb-3">

                                                    <div class="invoice-detail-label">

                                                        Invoice Date

                                                    </div>

                                                    <div
                                                        class="invoice-detail-value"
                                                        id="invoice_date_display"
                                                    >

                                                        —

                                                    </div>

                                                </div>


                                                {{-- DUE DATE --}}

                                                <div class="col-md-3 mb-3">

                                                    <div class="invoice-detail-label">

                                                        Due Date

                                                    </div>

                                                    <div
                                                        class="invoice-detail-value"
                                                        id="invoice_due_date_display"
                                                    >

                                                        —

                                                    </div>

                                                </div>


                                                {{-- INVOICE TOTAL --}}

                                                <div class="col-md-3 mb-3">

                                                    <div class="invoice-detail-label">

                                                        Invoice Total

                                                    </div>

                                                    <div
                                                        class="invoice-detail-value"
                                                        id="invoice_total_display"
                                                    >

                                                        Rs. 0.00

                                                    </div>

                                                </div>

                                            </div>


                                            <div class="row">


                                                {{-- PAID --}}

                                                <div class="col-md-6">

                                                    <div class="invoice-detail-label">

                                                        Already Paid

                                                    </div>

                                                    <div
                                                        class="invoice-detail-value text-success"
                                                        id="invoice_paid_display"
                                                    >

                                                        Rs. 0.00

                                                    </div>

                                                </div>


                                                {{-- REMAINING --}}

                                                <div class="col-md-6">

                                                    <div class="invoice-detail-label">

                                                        Remaining Amount

                                                    </div>

                                                    <div
                                                        class="invoice-detail-value text-danger"
                                                        id="invoice_remaining_display"
                                                    >

                                                        Rs. 0.00

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- PAYMENT FORM TAB --}}
                        {{-- ================================================= --}}

                        <div
                            class="tab-pane"
                            id="paymentForm"
                            role="tabpanel"
                        >

                            <div class="p-20">


                                {{-- HIDDEN SUBSCRIPTION ID --}}

                                <input
                                    type="hidden"
                                    name="subscription_id"
                                    id="subscription_id"
                                    value="{{ old('subscription_id') }}"
                                >


                                <div class="row">


                                    {{-- PAYMENT DATE --}}

                                    <div class="col-md-6 mb-3">

                                        <div class="form-floating">

                                            <input
                                                type="date"
                                                name="paid_at"
                                                id="paid_at"
                                                class="form-control @error('paid_at') is-invalid @enderror"
                                                value="{{ old('paid_at', date('Y-m-d')) }}"
                                                required
                                            >

                                            <label for="paid_at">

                                                Payment Date

                                                <span class="text-danger">*</span>

                                            </label>

                                            @error('paid_at')

                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>

                                            @enderror

                                        </div>

                                    </div>


                                    {{-- PAYMENT METHOD --}}

                                    <div class="col-md-6 mb-3">

                                        <div class="form-floating">

                                            <select
                                                name="payment_method"
                                                id="payment_method"
                                                class="form-select @error('payment_method') is-invalid @enderror"
                                                required
                                            >

                                                <option value="">

                                                    -- Select Payment Method --

                                                </option>

                                                <option
                                                    value="cash"
                                                    {{ old('payment_method') == 'cash' ? 'selected' : '' }}
                                                >

                                                    Cash

                                                </option>

                                                <option
                                                    value="bank_transfer"
                                                    {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}
                                                >

                                                    Bank Transfer

                                                </option>

                                                <option
                                                    value="cheque"
                                                    {{ old('payment_method') == 'cheque' ? 'selected' : '' }}
                                                >

                                                    Cheque

                                                </option>

                                                <option
                                                    value="online"
                                                    {{ old('payment_method') == 'online' ? 'selected' : '' }}
                                                >

                                                    Online Payment

                                                </option>

                                            </select>

                                            <label for="payment_method">

                                                Payment Method

                                                <span class="text-danger">*</span>

                                            </label>

                                            @error('payment_method')

                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>

                                            @enderror

                                        </div>

                                    </div>


                                    {{-- BANK --}}

                                    <div class="col-md-6 mb-3">

                                        <div class="form-floating">

                                            <select
                                                name="bank_id"
                                                id="bank_id"
                                                class="form-select @error('bank_id') is-invalid @enderror"
                                            >

                                                <option value="">

                                                    -- Select Bank --

                                                </option>

                                                @forelse(($banks ?? collect()) as $bank)

                                                    <option
                                                        value="{{ $bank->id }}"
                                                        {{ old('bank_id') == $bank->id ? 'selected' : '' }}
                                                    >

                                                        {{ $bank->name ?? $bank->bank_name ?? 'Bank #' . $bank->id }}

                                                    </option>

                                                @empty

                                                    <option
                                                        value=""
                                                        disabled
                                                    >

                                                        No Banks Available

                                                    </option>

                                                @endforelse

                                            </select>

                                            <label for="bank_id">

                                                Bank

                                            </label>

                                            @error('bank_id')

                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>

                                            @enderror

                                        </div>

                                    </div>


                                    {{-- PAYMENT AMOUNT --}}

                                    <div class="col-md-6 mb-3">

                                        <div class="form-floating">

                                            <input
                                                type="number"
                                                name="amount"
                                                id="payment_amount"
                                                class="form-control @error('amount') is-invalid @enderror"
                                                value="{{ old('amount') }}"
                                                min="0.01"
                                                step="0.01"
                                                placeholder="Payment Amount"
                                                required
                                            >

                                            <label for="payment_amount">

                                                Payment Amount

                                                <span class="text-danger">*</span>

                                            </label>

                                            @error('amount')

                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>

                                            @enderror

                                        </div>

                                    </div>


                                    {{-- REMAINING AFTER PAYMENT --}}

                                    <div class="col-md-6 mb-3">

                                        <div class="form-floating">

                                            <input
                                                type="text"
                                                id="remaining_amount"
                                                class="form-control fw-bold text-danger readonly-field"
                                                value="0.00"
                                                readonly
                                            >

                                            <label for="remaining_amount">

                                                Remaining After Payment

                                            </label>

                                        </div>

                                    </div>


                                    {{-- PAYMENT STATUS --}}

                                    <div class="col-md-6 mb-3">

                                        <div class="form-floating">

                                            <select
                                                name="status"
                                                id="status"
                                                class="form-select @error('status') is-invalid @enderror"
                                                required
                                            >

                                                <option
                                                    value="pending"
                                                    {{ old('status') == 'pending' ? 'selected' : '' }}
                                                >

                                                    Pending

                                                </option>

                                                <option
                                                    value="paid"
                                                    {{ old('status', 'paid') == 'paid' ? 'selected' : '' }}
                                                >

                                                    Paid

                                                </option>

                                                <option
                                                    value="failed"
                                                    {{ old('status') == 'failed' ? 'selected' : '' }}
                                                >

                                                    Failed

                                                </option>

                                                <option
                                                    value="refunded"
                                                    {{ old('status') == 'refunded' ? 'selected' : '' }}
                                                >

                                                    Refunded

                                                </option>

                                            </select>

                                            <label for="status">

                                                Payment Status

                                                <span class="text-danger">*</span>

                                            </label>

                                            @error('status')

                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>

                                            @enderror

                                        </div>

                                    </div>


                                    {{-- TRANSACTION REFERENCE --}}

                                    <div class="col-md-6 mb-3">

                                        <div class="form-floating">

                                            <input
                                                type="text"
                                                name="transaction_reference"
                                                id="transaction_reference"
                                                class="form-control @error('transaction_reference') is-invalid @enderror"
                                                value="{{ old('transaction_reference') }}"
                                                maxlength="255"
                                                placeholder="Transaction Reference"
                                            >

                                            <label for="transaction_reference">

                                                Transaction / Reference No.

                                            </label>

                                            @error('transaction_reference')

                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>

                                            @enderror

                                        </div>

                                    </div>


                                    {{-- NOTES --}}

                                    <div class="col-md-12 mb-3">

                                        <div class="form-floating">

                                            <textarea
                                                name="notes"
                                                id="notes"
                                                class="form-control @error('notes') is-invalid @enderror"
                                                style="height: 110px"
                                                placeholder="Payment Notes"
                                            >{{ old('notes') }}</textarea>

                                            <label for="notes">

                                                Payment Notes

                                            </label>

                                            @error('notes')

                                                <div class="invalid-feedback">

                                                    {{ $message }}

                                                </div>

                                            @enderror

                                        </div>

                                    </div>

                                </div>


                                {{-- ================================================= --}}
                                {{-- PAYMENT SUMMARY --}}
                                {{-- ================================================= --}}

                                <div class="row mt-3">


                                    {{-- TOTAL --}}

                                    <div class="col-md-4 mb-3">

                                        <div class="card payment-summary-card bg-light">

                                            <div class="card-body">

                                                <div class="payment-summary-label">

                                                    Invoice Total

                                                </div>

                                                <div
                                                    class="payment-summary-value"
                                                    id="summary_total"
                                                >

                                                    Rs. 0.00

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- CURRENT PAYMENT --}}

                                    <div class="col-md-4 mb-3">

                                        <div class="card payment-summary-card bg-light">

                                            <div class="card-body">

                                                <div class="payment-summary-label">

                                                    Current Payment

                                                </div>

                                                <div
                                                    class="payment-summary-value text-success"
                                                    id="summary_paid"
                                                >

                                                    Rs. 0.00

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- REMAINING --}}

                                    <div class="col-md-4 mb-3">

                                        <div class="card payment-summary-card bg-light">

                                            <div class="card-body">

                                                <div class="payment-summary-label">

                                                    Remaining After Payment

                                                </div>

                                                <div
                                                    class="payment-summary-value text-danger"
                                                    id="summary_remaining"
                                                >

                                                    Rs. 0.00

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- BUTTONS --}}

                                <div class="d-flex mt-3">

                                    <a
                                        href="{{ route('admin.subscription-payments.index') }}"
                                        class="btn btn-secondary"
                                    >

                                        <i class="fa fa-arrow-left me-1"></i>

                                        Back

                                    </a>

                                    <button
                                        type="submit"
                                        class="btn btn-primary text-white ms-auto"
                                    >

                                        <i class="fa fa-save me-1"></i>

                                        Record Payment

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@include('admin.footer')


<script src="{{ asset('assets/node_modules/jquery/dist/jquery.min.js') }}"></script>

<script src="{{ asset('assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | DOM ELEMENTS
    |--------------------------------------------------------------------------
    */

    const tenantSelect =
        document.getElementById('tenant_id');

    const invoiceSelect =
        document.getElementById('invoice_id');

    const subscriptionIdInput =
        document.getElementById('subscription_id');

    const ownerName =
        document.getElementById('owner_name');

    const tenantEmail =
        document.getElementById('tenant_email');

    const tenantPhone =
        document.getElementById('tenant_phone');

    const tenantAddress =
        document.getElementById('tenant_address');

    const accountCreationDate =
        document.getElementById('account_creation_date');

    const paymentAmount =
        document.getElementById('payment_amount');

    const remainingAmount =
        document.getElementById('remaining_amount');

    const invoiceNumberDisplay =
        document.getElementById('invoice_number_display');

    const invoiceDateDisplay =
        document.getElementById('invoice_date_display');

    const invoiceDueDateDisplay =
        document.getElementById('invoice_due_date_display');

    const invoiceTotalDisplay =
        document.getElementById('invoice_total_display');

    const invoicePaidDisplay =
        document.getElementById('invoice_paid_display');

    const invoiceRemainingDisplay =
        document.getElementById('invoice_remaining_display');

    const summaryTotal =
        document.getElementById('summary_total');

    const summaryPaid =
        document.getElementById('summary_paid');

    const summaryRemaining =
        document.getElementById('summary_remaining');

    const subscriptionAmountDisplay =
        document.getElementById('subscription_amount_display');

    const subscriptionStartDateDisplay =
        document.getElementById('subscription_start_date_display');

    const subscriptionEndDateDisplay =
        document.getElementById('subscription_end_date_display');

    const subscriptionStatusDisplay =
        document.getElementById('subscription_status_display');


    /*
    |--------------------------------------------------------------------------
    | SAFETY CHECK
    |--------------------------------------------------------------------------
    */

    if (
        !tenantSelect ||
        !invoiceSelect ||
        !paymentAmount
    ) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | CURRENCY FORMATTER
    |--------------------------------------------------------------------------
    */

    function formatCurrency(amount) {

        return 'Rs. ' + Number(amount || 0).toLocaleString('en-PK', {

            minimumFractionDigits: 2,

            maximumFractionDigits: 2

        });

    }


    /*
    |--------------------------------------------------------------------------
    | RESET TENANT DATA
    |--------------------------------------------------------------------------
    */

    function resetTenantData() {

        subscriptionIdInput.value = '';

        ownerName.value = '';

        tenantEmail.value = '';

        tenantPhone.value = '';

        tenantAddress.value = '';

        accountCreationDate.value = '';

    }


    /*
    |--------------------------------------------------------------------------
    | RESET SUBSCRIPTION DATA
    |--------------------------------------------------------------------------
    */

    function resetSubscriptionData() {

        subscriptionAmountDisplay.textContent =
            'Rs. 0.00';

        subscriptionStartDateDisplay.textContent =
            '—';

        subscriptionEndDateDisplay.textContent =
            '—';

        subscriptionStatusDisplay.textContent =
            '—';

    }


    /*
    |--------------------------------------------------------------------------
    | RESET INVOICE DATA
    |--------------------------------------------------------------------------
    */

    function resetInvoiceData() {

        invoiceNumberDisplay.textContent = '—';

        invoiceDateDisplay.textContent = '—';

        invoiceDueDateDisplay.textContent = '—';

        invoiceTotalDisplay.textContent =
            formatCurrency(0);

        invoicePaidDisplay.textContent =
            formatCurrency(0);

        invoiceRemainingDisplay.textContent =
            formatCurrency(0);

        paymentAmount.value = '';

        paymentAmount.removeAttribute('max');

        remainingAmount.value = '0.00';

        summaryTotal.textContent =
            formatCurrency(0);

        summaryPaid.textContent =
            formatCurrency(0);

        summaryRemaining.textContent =
            formatCurrency(0);

    }


    /*
    |--------------------------------------------------------------------------
    | GET SELECTED INVOICE
    |--------------------------------------------------------------------------
    */

    function getSelectedInvoice() {

        const selectedIndex =
            invoiceSelect.selectedIndex;

        const option =
            invoiceSelect.options[selectedIndex];

        if (
            !option ||
            !option.value ||
            option.hidden
        ) {

            return null;

        }

        return option;

    }


    /*
    |--------------------------------------------------------------------------
    | FILTER INVOICES BY TENANT
    |--------------------------------------------------------------------------
    */

    function filterInvoices() {

        const tenantId =
            String(tenantSelect.value || '');

        invoiceSelect.value = '';

        let invoiceFound = false;


        Array.from(invoiceSelect.options).forEach(function (option, index) {


            /*
            | Keep Placeholder Visible
            */

            if (index === 0) {

                option.hidden = false;

                return;

            }


            /*
            | Hide Empty/Disabled Option
            */

            if (!option.value) {

                option.hidden = true;

                return;

            }


            const invoiceTenantId =
                String(option.dataset.tenantId || '');

            const invoiceSubscriptionId =
                String(option.dataset.subscriptionId || '');


            if (
                tenantId &&
                invoiceTenantId === tenantId &&
                invoiceSubscriptionId
            ) {

                option.hidden = false;

                invoiceFound = true;

            } else {

                option.hidden = true;

            }

        });


        if (!tenantId) {

            invoiceSelect.options[0].textContent =
                '-- Select Tenant First --';

        } else if (!invoiceFound) {

            invoiceSelect.options[0].textContent =
                '-- No Subscription Invoice Found --';

        } else {

            invoiceSelect.options[0].textContent =
                '-- Select Invoice --';

        }


        resetInvoiceData();

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD TENANT DATA
    |--------------------------------------------------------------------------
    */

    function loadTenantData() {

        const selectedOption =
            tenantSelect.options[tenantSelect.selectedIndex];


        resetTenantData();

        resetInvoiceData();

        resetSubscriptionData();


        if (
            !selectedOption ||
            !selectedOption.value
        ) {

            filterInvoices();

            return;

        }


        subscriptionIdInput.value =
            selectedOption.dataset.subscriptionId || '';


        ownerName.value =
            selectedOption.dataset.ownerName || '';


        tenantEmail.value =
            selectedOption.dataset.email || '';


        tenantPhone.value =
            selectedOption.dataset.phone || '';


        tenantAddress.value =
            selectedOption.dataset.address || '';


        accountCreationDate.value =
            selectedOption.dataset.createdAt || '';


        /*
        |--------------------------------------------------------------------------
        | FETCH TENANT SUBSCRIPTION AND LATEST INVOICE
        |--------------------------------------------------------------------------
        */

        const tenantId = selectedOption.value;

        fetch(`/admin/subscription-payments/get-tenant-details?tenant_id=${tenantId}`)
            .then(response => response.json())
            .then(data => {
                if (data.subscription) {
                    subscriptionAmountDisplay.textContent =
                        formatCurrency(data.subscription.amount);

                    subscriptionStartDateDisplay.textContent =
                        data.subscription.starts_at || '—';

                    subscriptionEndDateDisplay.textContent =
                        data.subscription.ends_at || '—';

                    subscriptionStatusDisplay.textContent =
                        data.subscription.status.charAt(0).toUpperCase() +
                        data.subscription.status.slice(1);

                    subscriptionIdInput.value = data.subscription.id;
                }

                if (data.latest_invoice) {
                    const invoiceOption = Array.from(invoiceSelect.options).find(
                        option => option.value == data.latest_invoice.id
                    );

                    if (invoiceOption) {
                        invoiceSelect.value = data.latest_invoice.id;
                        loadInvoiceData();
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching tenant details:', error);
            });


        filterInvoices();

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD INVOICE DATA
    |--------------------------------------------------------------------------
    */

    function loadInvoiceData() {

        const option =
            getSelectedInvoice();


        if (!option) {

            resetInvoiceData();

            return;

        }


        const total =
            parseFloat(option.dataset.total || 0);


        const paid =
            parseFloat(option.dataset.paid || 0);


        const remaining =
            parseFloat(option.dataset.remaining || 0);


        const invoiceSubscriptionId =
            option.dataset.subscriptionId || '';


        /*
        | Update Subscription ID If Available
        */

        if (invoiceSubscriptionId) {

            subscriptionIdInput.value =
                invoiceSubscriptionId;

        }


        /*
        | Invoice Details
        */

        invoiceNumberDisplay.textContent =
            option.dataset.invoiceNumber || '—';


        invoiceDateDisplay.textContent =
            option.dataset.invoiceDate || '—';


        invoiceDueDateDisplay.textContent =
            option.dataset.dueDate || '—';


        invoiceTotalDisplay.textContent =
            formatCurrency(total);


        invoicePaidDisplay.textContent =
            formatCurrency(paid);


        invoiceRemainingDisplay.textContent =
            formatCurrency(remaining);


        /*
        | Default Payment Amount
        */

        paymentAmount.value =
            remaining.toFixed(2);


        paymentAmount.setAttribute(
            'max',
            remaining.toFixed(2)
        );


        calculateRemaining();

    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE REMAINING
    |--------------------------------------------------------------------------
    */

    function calculateRemaining() {

        const option =
            getSelectedInvoice();


        if (!option) {

            remainingAmount.value = '0.00';

            summaryTotal.textContent =
                formatCurrency(0);

            summaryPaid.textContent =
                formatCurrency(0);

            summaryRemaining.textContent =
                formatCurrency(0);

            return;

        }


        const invoiceTotal =
            parseFloat(option.dataset.total || 0);


        const invoiceRemaining =
            parseFloat(option.dataset.remaining || 0);


        let currentPayment =
            parseFloat(paymentAmount.value || 0);


        if (
            isNaN(currentPayment) ||
            currentPayment < 0
        ) {

            currentPayment = 0;

            paymentAmount.value = '0.00';

        }


        /*
        | Prevent Payment Above Invoice Remaining
        */

        if (currentPayment > invoiceRemaining) {

            currentPayment = invoiceRemaining;

            paymentAmount.value =
                invoiceRemaining.toFixed(2);

        }


        const afterPayment =
            Math.max(
                invoiceRemaining - currentPayment,
                0
            );


        remainingAmount.value =
            afterPayment.toFixed(2);


        summaryTotal.textContent =
            formatCurrency(invoiceTotal);


        summaryPaid.textContent =
            formatCurrency(currentPayment);


        summaryRemaining.textContent =
            formatCurrency(afterPayment);

    }


    /*
    |--------------------------------------------------------------------------
    | FORM VALIDATION
    |--------------------------------------------------------------------------
    */

    const paymentForm =
        document.getElementById('subscriptionPaymentForm');


    if (paymentForm) {

        paymentForm.addEventListener('submit', function (event) {

            const selectedInvoice =
                getSelectedInvoice();


            if (!selectedInvoice) {

                event.preventDefault();

                alert('Please select a valid invoice.');

                invoiceSelect.focus();

                return;

            }


            const invoiceRemaining =
                parseFloat(
                    selectedInvoice.dataset.remaining || 0
                );


            const enteredAmount =
                parseFloat(paymentAmount.value || 0);


            if (
                isNaN(enteredAmount) ||
                enteredAmount <= 0
            ) {

                event.preventDefault();

                alert('Please enter a valid payment amount.');

                paymentAmount.focus();

                return;

            }


            if (enteredAmount > invoiceRemaining) {

                event.preventDefault();

                alert(
                    'Payment amount cannot be greater than invoice remaining amount.'
                );

                paymentAmount.focus();

                return;

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | EVENT LISTENERS
    |--------------------------------------------------------------------------
    */

    tenantSelect.addEventListener('change', function () {

        loadTenantData();

    });


    invoiceSelect.addEventListener('change', function () {

        loadInvoiceData();

    });


    paymentAmount.addEventListener('input', function () {

        calculateRemaining();

    });


    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    loadTenantData();


    /*
    |--------------------------------------------------------------------------
    | RESTORE OLD INVOICE
    |--------------------------------------------------------------------------
    */

    @if(old('invoice_id'))

        invoiceSelect.value =
            "{{ old('invoice_id') }}";

        loadInvoiceData();

    @endif


    /*
    |--------------------------------------------------------------------------
    | RESTORE OLD TENANT
    |--------------------------------------------------------------------------
    */

    @if(old('tenant_id'))

        tenantSelect.value =
            "{{ old('tenant_id') }}";

        loadTenantData();

        @if(old('invoice_id'))

            invoiceSelect.value =
                "{{ old('invoice_id') }}";

            loadInvoiceData();

        @endif

    @endif

});

</script>

</body>

</html>