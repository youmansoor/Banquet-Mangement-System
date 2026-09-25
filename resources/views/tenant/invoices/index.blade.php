<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Invoices</title>

    <style>

        /* =========================================================
           INVOICE TABLE
           ========================================================= */

        .invoice-table {
            min-width: 1350px;
        }

        .invoice-table th {
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
            vertical-align: middle;
        }

        .invoice-table td {
            vertical-align: middle;
            font-size: 13px;
        }


        /* =========================================================
           INVOICE NUMBER
           ========================================================= */

        .invoice-number {
            font-weight: 600;
            color: #0d6efd;
            white-space: nowrap;
        }


        /* =========================================================
           CUSTOMER
           ========================================================= */

        .customer-name {
            font-weight: 600;
            color: #343a40;
        }

        .customer-phone {
            font-size: 12px;
        }


        /* =========================================================
           AMOUNTS
           ========================================================= */

        .amount-cell {
            white-space: nowrap;
            font-weight: 600;
        }

        .total-amount {
            color: #0d6efd;
        }

        .paid-amount {
            color: #198754;
        }

        .remaining-amount {
            color: #dc3545;
        }


        /* =========================================================
           FILTER CARD
           ========================================================= */

        .filter-card {
            border: 0;
            border-radius: 8px;
        }

        .filter-card .form-label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }


        /* =========================================================
           ACTION BUTTONS
           ========================================================= */

        .action-buttons {
            white-space: nowrap;
        }

        .action-buttons .btn {
            margin-right: 3px;
        }


        /* =========================================================
           STATUS
           ========================================================= */

        .status-badge {
            font-size: 11px;
            padding: 5px 8px;
            white-space: nowrap;
        }


        /* =========================================================
           EMPTY STATE
           ========================================================= */

        .empty-state {
            padding: 50px 20px;
        }


        /* =========================================================
           CARD
           ========================================================= */

        .invoice-card {
            border: 0;
            border-radius: 8px;
        }


        /* =========================================================
           RESPONSIVE
           ========================================================= */

        @media (max-width: 767px) {

            .invoice-table {
                min-width: 1000px;
            }

        }

    </style>

</head>


<body>

@include('tenant.nav')


<div class="page-wrapper">

    <div class="container-fluid">


        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="row page-titles">

            <div class="col-md-6">

                <h4 class="text-themecolor">

                    <i class="ti-file me-2"></i>

                    Invoices

                </h4>

                <p class="text-muted mb-0">

                    Manage all customer invoices

                </p>

            </div>


            <div class="col-md-6 text-end">

                <a
                    href="{{ route('invoices.create') }}"
                    class="btn btn-info text-white"
                >

                    <i class="fa fa-plus me-1"></i>

                    Create Invoice

                </a>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >

                <i class="fa fa-check-circle me-1"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ERROR MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('error'))

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >

                <i class="fa fa-exclamation-circle me-1"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- VALIDATION ERRORS --}}
        {{-- ========================================================= --}}

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Please fix the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ========================================================= --}}
{{-- SEARCH / FILTER CARD --}}
{{-- ========================================================= --}}

<div class="card shadow-sm filter-card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('invoices.index') }}"
        >

            <div class="row g-3">

                {{-- INVOICE NUMBER --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Invoice Number
                    </label>

                    <input
                        type="text"
                        name="invoice_number"
                        class="form-control"
                        value="{{ request('invoice_number') }}"
                        placeholder="e.g. INV-0001"
                    >

                </div>


                {{-- CUSTOMER --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Customer
                    </label>

                    <input
                        type="text"
                        name="customer"
                        class="form-control"
                        value="{{ request('customer') }}"
                        placeholder="Customer name"
                    >

                </div>


                {{-- PHONE --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Phone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="{{ request('phone') }}"
                        placeholder="Customer phone"
                    >

                </div>


                {{-- BOOKING --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Booking ID
                    </label>

                    <input
                        type="text"
                        name="booking_id"
                        class="form-control"
                        value="{{ request('booking_id') }}"
                        placeholder="Booking ID"
                    >

                </div>


                {{-- FROM DATE --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Invoice From
                    </label>

                    <input
                        type="date"
                        name="date_from"
                        class="form-control"
                        value="{{ request('date_from') }}"
                    >

                </div>


                {{-- TO DATE --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Invoice To
                    </label>

                    <input
                        type="date"
                        name="date_to"
                        class="form-control"
                        value="{{ request('date_to') }}"
                    >

                </div>


                {{-- STATUS --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-control form-select"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="paid"
                            {{ request('status') === 'paid' ? 'selected' : '' }}
                        >
                            Paid
                        </option>

                        <option
                            value="partially_paid"
                            {{ request('status') === 'partially_paid' ? 'selected' : '' }}
                        >
                            Partially Paid
                        </option>

                        <option
                            value="sent"
                            {{ request('status') === 'sent' ? 'selected' : '' }}
                        >
                            Sent
                        </option>

                        <option
                            value="overdue"
                            {{ request('status') === 'overdue' ? 'selected' : '' }}
                        >
                            Overdue
                        </option>

                        <option
                            value="cancelled"
                            {{ request('status') === 'cancelled' ? 'selected' : '' }}
                        >
                            Cancelled
                        </option>

                    </select>

                </div>


                {{-- PAYMENT STATE --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Payment State
                    </label>

                    <select
                        name="payment_state"
                        class="form-control form-select"
                    >

                        <option value="">
                            All Payments
                        </option>

                        <option
                            value="paid"
                            {{ request('payment_state') === 'paid' ? 'selected' : '' }}
                        >
                            Fully Paid
                        </option>

                        <option
                            value="partial"
                            {{ request('payment_state') === 'partial' ? 'selected' : '' }}
                        >
                            Partially Paid
                        </option>

                        <option
                            value="unpaid"
                            {{ request('payment_state') === 'unpaid' ? 'selected' : '' }}
                        >
                            Unpaid
                        </option>

                    </select>

                </div>


                {{-- MIN TOTAL --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Minimum Total
                    </label>

                    <input
                        type="number"
                        name="min_total"
                        class="form-control"
                        value="{{ request('min_total') }}"
                        min="0"
                        step="0.01"
                        placeholder="e.g. 5000"
                    >

                </div>


                {{-- MAX TOTAL --}}
                <div class="col-md-3">

                    <label class="form-label">
                        Maximum Total
                    </label>

                    <input
                        type="number"
                        name="max_total"
                        class="form-control"
                        value="{{ request('max_total') }}"
                        min="0"
                        step="0.01"
                        placeholder="e.g. 100000"
                    >

                </div>


                {{-- GENERAL SEARCH --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Quick Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        value="{{ request('search') }}"
                        placeholder="Search invoice, customer, phone, booking..."
                    >

                </div>


                {{-- BUTTONS --}}
                <div class="col-md-6 d-flex align-items-end gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="fa fa-search me-1"></i>

                        Search

                    </button>


                    <a
                        href="{{ route('invoices.index') }}"
                        class="btn btn-secondary"
                        title="Reset Filters"
                    >

                        <i class="fa fa-refresh me-1"></i>

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>



        {{-- ========================================================= --}}
        {{-- INVOICE TABLE --}}
        {{-- ========================================================= --}}

        <div class="card invoice-card">

            <div class="card-body">


                {{-- CARD HEADER --}}
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h4 class="card-title mb-1">

                            Invoice List

                        </h4>

                        <h6 class="card-subtitle">

                            Manage all customer invoices

                        </h6>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TABLE --}}
                {{-- ================================================= --}}

                <div class="table-responsive">

                    <table
                        class="table color-table primary-table align-middle mb-0 invoice-table"
                    >

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Invoice
                                </th>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Booking
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Total
                                </th>

                                <th>
                                    Pay Amount
                                </th>

                                <th>
                                    Paid
                                </th>

                                <th>
                                    Remaining
                                </th>

                                <th>
                                    Status
                                </th>

                                <th width="180">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                        @forelse($invoices as $invoice)


                            <tr>


                                {{-- ================================================= --}}
                                {{-- NUMBER --}}
                                {{-- ================================================= --}}

                                <td>

                                    {{ $loop->iteration }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- INVOICE --}}
                                {{-- ================================================= --}}

                                <td>

                                    <span class="invoice-number">

                                        {{ $invoice->invoice_number }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- CUSTOMER --}}
                                {{-- ================================================= --}}

                                <td>

                                    <span class="customer-name">

                                        {{ $invoice->customer->name ?? 'N/A' }}

                                    </span>


                                    @if(!empty($invoice->customer->phone_1))

                                        <br>

                                        <small class="text-muted customer-phone">

                                            <i class="fa fa-phone me-1"></i>

                                            {{ $invoice->customer->phone_1 }}

                                        </small>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- BOOKING --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if($invoice->booking_id)

                                        <span class="text-primary">

                                            #{{ $invoice->booking_id }}

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            N/A

                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- DATE --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if($invoice->invoice_date)

                                        {{ $invoice->invoice_date->format('d M Y') }}

                                    @else

                                        N/A

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- TOTAL --}}
                                {{-- ================================================= --}}

                                <td class="amount-cell total-amount">

                                    Rs.

                                    {{ number_format(
                                        (float) ($invoice->grand_total ?? 0),
                                        2
                                    ) }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- PAY AMOUNT --}}
                                {{-- ================================================= --}}

                                <td class="amount-cell" style="color: #6f42c1;">

                                    Rs.

                                    {{ number_format(
                                        (float) ($invoice->pay_amount ?? 0),
                                        2
                                    ) }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- PAID --}}
                                {{-- ================================================= --}}

                                <td class="amount-cell paid-amount">

                                    Rs.

                                    {{ number_format(
                                        (float) ($invoice->paid_amount ?? 0),
                                        2
                                    ) }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- REMAINING --}}
                                {{-- ================================================= --}}

                                <td class="amount-cell remaining-amount">

                                    Rs.

                                    {{ number_format(
                                        (float) ($invoice->remaining_amount ?? 0),
                                        2
                                    ) }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- STATUS --}}
                                {{-- ================================================= --}}

                                <td>

                                    @php

                                        $statusClass = match($invoice->status) {

                                            'paid'
                                                => 'bg-success',

                                            'partially_paid'
                                                => 'bg-warning text-dark',

                                            'cancelled'
                                                => 'bg-danger',

                                            'overdue'
                                                => 'bg-danger',

                                            'sent'
                                                => 'bg-info',

                                            default
                                                => 'bg-secondary',

                                        };

                                    @endphp


                                    <span
                                        class="badge {{ $statusClass }} status-badge"
                                    >

                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $invoice->status ?? 'N/A'
                                            )
                                        ) }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- ACTION --}}
                                {{-- ================================================= --}}

                                <td>

                                    <div class="action-buttons d-flex gap-1">


                                        {{-- VIEW --}}

                                        <a
                                            href="{{ route(
                                                'invoices.show',
                                                $invoice->id
                                            ) }}"
                                            class="btn btn-primary"
                                            title="View Invoice"
                                        >

                                            <span class="btn-label">

                                                <i class="fa fa-eye"></i>

                                            </span>

                                            View

                                        </a>


                                        {{-- EDIT --}}

                                        <a
                                            href="{{ route(
                                                'invoices.edit',
                                                $invoice->id
                                            ) }}"
                                            class="btn btn-warning"
                                            title="Edit Invoice"
                                        >

                                            <span class="btn-label">

                                                <i class="fa fa-edit"></i>

                                            </span>

                                            Edit

                                        </a>


                                        {{-- DELETE --}}

                                        <form
                                            action="{{ route(
                                                'invoices.destroy',
                                                $invoice->id
                                            ) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this invoice?');"
                                        >

                                            @csrf

                                            @method('DELETE')


                                            <button
                                                type="submit"
                                                class="btn btn-danger"
                                                title="Delete Invoice"
                                            >

                                                <span class="btn-label">

                                                    <i class="fa fa-trash"></i>

                                                </span>

                                                Delete

                                            </button>

                                        </form>


                                    </div>

                                </td>


                            </tr>


                        @empty


                            {{-- ================================================= --}}
                            {{-- EMPTY STATE --}}
                            {{-- ================================================= --}}

                            <tr>

                                <td
                                    colspan="11"
                                    class="text-center empty-state"
                                >

                                    <i
                                        class="fa fa-file-text-o fa-3x text-muted mb-3"
                                    ></i>


                                    <h5 class="text-muted">

                                        No invoices found

                                    </h5>


                                    <p class="text-muted mb-0">

                                        There are no invoices matching your search.

                                    </p>

                                </td>

                            </tr>


                        @endforelse


                        </tbody>

                    </table>

                </div>


                {{-- ================================================= --}}
                {{-- PAGINATION --}}
                {{-- ================================================= --}}

                @if($invoices->hasPages())

                    <div class="mt-3">

                        {{ $invoices->appends(request()->query())->links() }}

                    </div>

                @endif


            </div>

        </div>


    </div>

</div>


@include('tenant.footer')


</body>

</html>