<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tenant Payment Invoices</title>

    <style>

        /* =========================================================
           INVOICE TABLE
        ========================================================= */

        .invoice-table th {
            white-space: nowrap;
            vertical-align: middle;
        }

        .invoice-table td {
            vertical-align: middle;
        }

        .invoice-table .invoice-number {
            font-weight: 600;
        }

        .invoice-table .tenant-name {
            font-weight: 600;
        }

        .invoice-table .owner-name {
            font-size: 12px;
        }

        .invoice-table .amount {
            white-space: nowrap;
        }

        .invoice-table .action-buttons {
            white-space: nowrap;
        }

        /* =========================================================
           FILTER CARD
        ========================================================= */

        .filter-label {
            font-weight: 500;
            margin-bottom: 6px;
        }

        /* =========================================================
           SUMMARY CARDS
        ========================================================= */

        .summary-icon {
            font-size: 32px;
            opacity: .8;
        }

    </style>

</head>


<body>

@include('admin.nav')


<div class="page-wrapper">

    {{-- ========================================================= --}}
    {{-- PAGE CONTENT --}}
    {{-- ========================================================= --}}

    <div class="container-fluid">

        {{-- ===================================================== --}}
        {{-- PAGE HEADER --}}
        {{-- ===================================================== --}}

        <div class="row page-titles">

            <div class="col-md-8 col-12 align-self-center">

                <h3 class="text-themecolor mb-1">

                    <i class="ti-receipt me-2"></i>

                    Tenant Payment Invoices

                </h3>

                <ol class="breadcrumb">

                    <li class="breadcrumb-item">

                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>

                    </li>

                    <li class="breadcrumb-item active">
                        Invoices
                    </li>

                </ol>

            </div>


            <div class="col-md-4 col-12 text-end">

                <a
                    href="{{ route('admin.tenant-payments.create') }}"
                    class="btn btn-success text-white"
                >

                    <i class="fa fa-plus me-1"></i>

                    Create Invoice

                </a>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ===================================================== --}}

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


        {{-- ===================================================== --}}
        {{-- FILTER CARD --}}
        {{-- ===================================================== --}}

        <div class="card">

            <div class="card-body">

                <div class="row g-3">

                    {{-- SEARCH --}}
                    <div class="col-md-4">

                        <label class="form-label filter-label">
                            Search
                        </label>

                        <input
                            type="text"
                            id="invoiceSearch"
                            class="form-control"
                            placeholder="Tenant name, owner..."
                        >

                    </div>


                    {{-- PAYMENT STATUS --}}
                    <div class="col-md-3">

                        <label class="form-label filter-label">
                            Payment Status
                        </label>

                        <select
                            id="statusFilter"
                            class="form-select"
                        >

                            <option value="">
                                All
                            </option>

                            <option value="paid">
                                Paid
                            </option>

                            <option value="partial">
                                Partial
                            </option>

                            <option value="unpaid">
                                Unpaid
                            </option>

                        </select>

                    </div>


                    {{-- DATE FROM --}}
                    <div class="col-md-2">

                        <label class="form-label filter-label">
                            From
                        </label>

                        <input
                            type="date"
                            id="dateFrom"
                            class="form-control"
                        >

                    </div>


                    {{-- DATE TO --}}
                    <div class="col-md-2">

                        <label class="form-label filter-label">
                            To
                        </label>

                        <input
                            type="date"
                            id="dateTo"
                            class="form-control"
                        >

                    </div>


                    {{-- RESET --}}
                    <div class="col-md-1 d-flex align-items-end">

                        <button
                            type="button"
                            id="resetFilters"
                            class="btn btn-secondary w-100"
                            title="Reset Filters"
                        >

                            <i class="fa fa-refresh"></i>

                        </button>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- SUMMARY CARDS --}}
        {{-- ===================================================== --}}

        @php

            $totalCount = $payments->count();

            $totalSubscription =
                $payments->sum(
                    fn($payment) =>
                        (float) $payment->subscription_amount
                );

            $totalPaid =
                $payments
                    ->where(
                        'payment_direction',
                        'in'
                    )
                    ->sum(
                        fn($payment) =>
                            (float) $payment->payment_amount
                    );

            $totalRemaining =
                $payments->last()?->remaining_amount ?? 0;

        @endphp


        <div class="row mt-4">

            {{-- ALL --}}
            <div class="col-md-3">

                <div class="card bg-primary text-white">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <h6 class="mb-1">
                                    Total Invoices
                                </h6>

                                <h3
                                    id="totalInvoiceCount"
                                    class="mb-0"
                                >
                                    {{ $totalCount }}
                                </h3>

                            </div>

                            <div>

                                <i
                                    class="ti-files summary-icon"
                                ></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- SUBSCRIPTION --}}
            <div class="col-md-3">

                <div class="card bg-info text-white">

                    <div class="card-body">

                        <h6 class="mb-1">
                            Subscription Amount
                        </h6>

                        <h3 class="mb-0">

                            PKR
                            {{ number_format(
                                $totalSubscription,
                                2
                            ) }}

                        </h3>

                    </div>

                </div>

            </div>


            {{-- RECEIVED --}}
            <div class="col-md-3">

                <div class="card bg-success text-white">

                    <div class="card-body">

                        <h6 class="mb-1">
                            Total Received
                        </h6>

                        <h3 class="mb-0">

                            PKR
                            {{ number_format(
                                $totalPaid,
                                2
                            ) }}

                        </h3>

                    </div>

                </div>

            </div>


            {{-- REMAINING --}}
            <div class="col-md-3">

                <div class="card bg-warning">

                    <div class="card-body">

                        <h6 class="mb-1">
                            Current Remaining
                        </h6>

                        <h3 class="mb-0">

                            PKR
                            {{ number_format(
                                (float) $totalRemaining,
                                2
                            ) }}

                        </h3>

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INVOICE TABLE --}}
        {{-- ===================================================== --}}

        <div class="card mt-2">

            <div class="card-body">

                <div
                    class="d-flex justify-content-between align-items-center mb-3"
                >

                    <div>

                        <h4 class="card-title mb-1">
                            Invoice Records
                        </h4>

                        <p class="text-muted mb-0">
                            Tenant subscription payment history.
                        </p>

                    </div>

                    <span
                        class="badge bg-primary"
                        id="visibleCount"
                    >
                        {{ $payments->count() }}
                    </span>

                </div>


                <div class="table-responsive">

                    {{-- =================================================
                         PRIMARY TABLE UI
                    ================================================= --}}

                    <table
                        class="table color-table primary-table invoice-table"
                        id="invoiceTable"
                    >

                        <thead>

                            <tr>

                                <th width="60">
                                    #
                                </th>

                                <th>
                                    Tenant
                                </th>

                                <th>
                                    Payment Date
                                </th>

                                <th>
                                    Subscription
                                </th>

                                <th>
                                    Payment
                                </th>

                                <th>
                                    Remaining
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Direction
                                </th>

                                <th>
                                    Status
                                </th>

                                <th width="100">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($payments as $index => $payment)

                                @php

                                    $subscriptionAmount =
                                        (float) $payment->subscription_amount;

                                    $paymentAmount =
                                        (float) $payment->payment_amount;

                                    $remaining =
                                        (float) $payment->remaining_amount;

                                    if (
                                        $payment->payment_direction === 'out'
                                    ) {

                                        $status = 'partial';

                                    } elseif ($remaining <= 0) {

                                        $status = 'paid';

                                    } elseif ($paymentAmount > 0) {

                                        $status = 'partial';

                                    } else {

                                        $status = 'unpaid';

                                    }

                                @endphp


                                <tr
                                    class="invoice-row"

                                    data-search="{{
                                        strtolower(
                                            ($payment->tenant->business_name ?? '')
                                            .' '.
                                            ($payment->tenant->owner_name ?? '')
                                        )
                                    }}"

                                    data-status="{{ $status }}"

                                    data-date="{{
                                        optional(
                                            $payment->payment_date
                                        )->format('Y-m-d')
                                    }}"
                                >

                                    {{-- NUMBER --}}
                                    <td class="invoice-number">

                                        {{ $index + 1 }}

                                    </td>


                                    {{-- TENANT --}}
                                    <td>

                                        <div class="tenant-name">

                                            {{
                                                $payment->tenant->business_name
                                                ?? $payment->tenant->owner_name
                                                ?? 'Unknown Tenant'
                                            }}

                                        </div>

                                        @if($payment->tenant?->owner_name)

                                            <div class="small text-muted owner-name">

                                                Owner:
                                                {{ $payment->tenant->owner_name }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- DATE --}}
                                    <td>

                                        {{
                                            optional(
                                                $payment->payment_date
                                            )->format('d-m-Y')
                                            ?? '-'
                                        }}

                                    </td>


                                    {{-- SUBSCRIPTION --}}
                                    <td class="amount">

                                        PKR
                                        {{
                                            number_format(
                                                $subscriptionAmount,
                                                2
                                            )
                                        }}

                                    </td>


                                    {{-- PAYMENT --}}
                                    <td class="amount">

                                        <span
                                            class="
                                                fw-semibold
                                                {{
                                                    $payment->payment_direction === 'in'
                                                        ? 'text-success'
                                                        : 'text-danger'
                                                }}
                                            "
                                        >

                                            {{
                                                $payment->payment_direction === 'in'
                                                    ? '+'
                                                    : '-'
                                            }}

                                            PKR
                                            {{
                                                number_format(
                                                    $paymentAmount,
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>


                                    {{-- REMAINING --}}
                                    <td class="amount">

                                        <span class="fw-semibold">

                                            PKR
                                            {{
                                                number_format(
                                                    $remaining,
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>


                                    {{-- PAYMENT TYPE --}}
                                    <td>

                                        {{
                                            ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $payment->payment_type
                                                )
                                            )
                                        }}

                                    </td>


                                    {{-- DIRECTION --}}
                                    <td>

                                        @if(
                                            $payment->payment_direction === 'in'
                                        )

                                            <span class="badge bg-success">
                                                IN
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                OUT
                                            </span>

                                        @endif

                                    </td>


                                    {{-- STATUS --}}
                                    <td>

                                        @if($status === 'paid')

                                            <span class="badge bg-success">
                                                Paid
                                            </span>

                                        @elseif($status === 'partial')

                                            <span class="badge bg-warning text-dark">
                                                Partial
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Unpaid
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTION --}}
                                    <td class="action-buttons">

                                        <a
                                            href="{{ route(
                                                'admin.invoices.print',
                                                $payment
                                            ) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-primary"
                                            title="Print Invoice"
                                        >

                                            <i class="fa fa-print"></i>

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr id="emptyInvoiceRow">

                                    <td
                                        colspan="10"
                                        class="text-center py-5"
                                    >

                                        <div class="text-muted">

                                            <i
                                                class="ti-receipt"
                                                style="font-size:40px;"
                                            ></i>

                                            <h5 class="mt-3">
                                                No payment invoices found.
                                            </h5>

                                            <a
                                                href="{{ route(
                                                    'admin.tenant-payments.create'
                                                ) }}"
                                                class="btn btn-primary"
                                            >

                                                Create First Invoice

                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================== --}}
{{-- FILTER JAVASCRIPT --}}
{{-- =============================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const searchInput =
            document.getElementById(
                'invoiceSearch'
            );

        const statusFilter =
            document.getElementById(
                'statusFilter'
            );

        const dateFrom =
            document.getElementById(
                'dateFrom'
            );

        const dateTo =
            document.getElementById(
                'dateTo'
            );

        const resetButton =
            document.getElementById(
                'resetFilters'
            );

        const rows =
            Array.from(
                document.querySelectorAll(
                    '#invoiceTable .invoice-row'
                )
            );

        const visibleCount =
            document.getElementById(
                'visibleCount'
            );

        const totalInvoiceCount =
            document.getElementById(
                'totalInvoiceCount'
            );


        function applyFilters() {

            const search =
                (
                    searchInput?.value || ''
                )
                    .trim()
                    .toLowerCase();


            const status =
                statusFilter?.value || '';


            const from =
                dateFrom?.value || '';


            const to =
                dateTo?.value || '';


            let count = 0;


            rows.forEach(
                function (row) {

                    const rowSearch =
                        row.dataset.search || '';


                    const rowStatus =
                        row.dataset.status || '';


                    const rowDate =
                        row.dataset.date || '';


                    const matchesSearch =
                        !search ||
                        rowSearch.includes(search);


                    const matchesStatus =
                        !status ||
                        rowStatus === status;


                    const matchesFrom =
                        !from ||
                        rowDate >= from;


                    const matchesTo =
                        !to ||
                        rowDate <= to;


                    const visible =
                        matchesSearch &&
                        matchesStatus &&
                        matchesFrom &&
                        matchesTo;


                    row.style.display =
                        visible
                            ? ''
                            : 'none';


                    if (visible) {

                        count++;

                    }

                }
            );


            if (visibleCount) {

                visibleCount.textContent =
                    count;

            }


            if (totalInvoiceCount) {

                totalInvoiceCount.textContent =
                    count;

            }

        }


        searchInput?.addEventListener(
            'input',
            applyFilters
        );


        statusFilter?.addEventListener(
            'change',
            applyFilters
        );


        dateFrom?.addEventListener(
            'change',
            applyFilters
        );


        dateTo?.addEventListener(
            'change',
            applyFilters
        );


        resetButton?.addEventListener(
            'click',
            function () {

                if (searchInput) {

                    searchInput.value = '';

                }


                if (statusFilter) {

                    statusFilter.value = '';

                }


                if (dateFrom) {

                    dateFrom.value = '';

                }


                if (dateTo) {

                    dateTo.value = '';

                }


                applyFilters();

            }
        );

    }
);

</script>


@include('admin.footer')

</body>

</html>