<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Invoice - Payment #{{ $payment->id }}
    </title>

    <link rel="stylesheet"
          href="{{ asset('assets/css/style.css') }}">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>

        .invoice-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .invoice-total-box {
            max-width: 400px;
            margin-left: auto;
        }

        .invoice-total-box p {
            margin-bottom: 8px;
        }

        .invoice-grand-total {
            font-size: 22px;
            font-weight: 700;
        }

        .invoice-status {
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .status-partially {
            background: #fff3cd;
            color: #856404;
        }

        .status-pending {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }


        /* =========================================================
           PAYMENT INFORMATION
        ========================================================= */

        .event-info-box {
            background: #f8f8f8;
            padding: 18px 20px;
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .event-info-title {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 18px;
        }

        .event-info-row {
            display: flex;
            width: 100%;
        }

        .event-info-item {
            padding: 0 14px;
            border-right: 1px solid #e5e5e5;
            min-height: 42px;
        }

        .event-info-item:first-child {
            padding-left: 0;
        }

        .event-info-item:last-child {
            border-right: none;
        }

        .event-info-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #333;
            margin-bottom: 7px;
            white-space: nowrap;
        }

        .event-info-value {
            display: block;
            font-size: 11px;
            color: #666;
        }


        /* =========================================================
           TABLE
        ========================================================= */

        .invoice-table thead th {
            font-weight: 600;
        }

        .invoice-table td,
        .invoice-table th {
            vertical-align: middle;
        }


        /* =========================================================
           PRINT
        ========================================================= */

        @media print {

            body * {
                visibility: hidden;
            }

            .printableArea,
            .printableArea * {
                visibility: visible;
            }

            .printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 991px) {

            .event-info-row {
                display: flex;
                flex-wrap: wrap;
            }

            .event-info-item {
                width: 50% !important;
                border-right: 1px solid #e5e5e5;
                border-bottom: 1px solid #e5e5e5;
                padding: 12px;
            }

            .event-info-item:first-child {
                padding-left: 12px;
            }

        }

        @media (max-width: 575px) {

            .event-info-item {
                width: 100% !important;
                border-right: none;
            }

        }

    </style>

</head>


<body>

@include('admin.nav')
<div class="page-wrapper">

    <div class="container-fluid">


        {{-- =========================================================
             PAGE HEADER
        ========================================================= --}}

        <div class="row page-titles no-print">

            <div class="col-md-5 align-self-center">

                <h4 class="text-themecolor">
                    Invoice
                </h4>

            </div>


            <div class="col-md-7 align-self-center text-end">

                <div class="d-flex justify-content-end align-items-center">

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">

                            <a href="{{ route('admin.invoices.index') }}">
                                Invoices
                            </a>

                        </li>

                        <li class="breadcrumb-item active">

                            #{{ $payment->id }}

                        </li>

                    </ol>


                    <button type="button"
                            class="btn btn-info text-white m-l-15"
                            onclick="window.print();">

                        <i class="fa fa-print"></i>

                        Print

                    </button>

                </div>

            </div>

        </div>



        {{-- =========================================================
             INVOICE
        ========================================================= --}}

        <div class="row">

            <div class="col-md-12">

                <div class="card card-body printableArea">


                    {{-- =================================================
                         INVOICE HEADER
                    ================================================= --}}

                    <div class="invoice-header">

                        <div class="row">

                            <div class="col-md-6">

                                <h3>

                                    <b>INVOICE</b>

                                </h3>

                            </div>


                            <div class="col-md-6 text-end">

                                <h3>

                                    <b>

                                        #{{ $payment->id }}

                                    </b>

                                </h3>

                            </div>

                        </div>

                    </div>


                    <hr>



                    {{-- =================================================
                         TENANT + PAYMENT INFO
                    ================================================= --}}

                    <div class="row">


                        {{-- TENANT --}}

                        <div class="col-md-6">

                            <address>

                                <h3>

                                    &nbsp;

                                    <b class="text-danger">

                                        {{ $payment->tenant->business_name
                                            ?? $payment->tenant->name
                                            ?? 'Banquet Management' }}

                                    </b>

                                </h3>


                                <p class="text-muted m-l-5">

                                    @if(!empty($payment->tenant->address))

                                        {{ $payment->tenant->address }}

                                        <br>

                                    @endif


                                    @if(!empty($payment->tenant->phone))

                                        Phone:
                                        {{ $payment->tenant->phone }}

                                        <br>

                                    @endif


                                    @if(!empty($payment->tenant->email))

                                        Email:
                                        {{ $payment->tenant->email }}

                                    @endif

                                </p>

                            </address>

                        </div>



                        {{-- PAYMENT SIDE --}}

                        <div class="col-md-6 text-end">

                            <address>

                                <h3>
                                    To,
                                </h3>


                                <h4 class="font-bold">

                                    Banquet Management

                                </h4>


                                <p class="text-muted">

                                    Subscription Payment Invoice

                                    <br>

                                    Admin / Super Admin

                                </p>


                                <p class="m-t-30">

                                    <b>
                                        Invoice Date:
                                    </b>

                                    <i class="fa fa-calendar"></i>

                                    {{ $payment->payment_date
                                        ? \Carbon\Carbon::parse(
                                            $payment->payment_date
                                        )->format('d M Y')
                                        : '-' }}

                                </p>


                                @php

                                    $remainingAmount = (float) (
                                        $payment->remaining_amount ?? 0
                                    );

                                    $paymentAmount = (float) (
                                        $payment->payment_amount ?? 0
                                    );

                                    if (
                                        $payment->payment_direction === 'in'
                                        && $remainingAmount <= 0
                                    ) {

                                        $status = 'paid';

                                    } elseif (
                                        $payment->payment_direction === 'in'
                                        && $paymentAmount > 0
                                        && $remainingAmount > 0
                                    ) {

                                        $status = 'partially_paid';

                                    } elseif (
                                        $payment->payment_direction === 'out'
                                    ) {

                                        $status = 'pending';

                                    } else {

                                        $status = 'pending';

                                    }


                                    $statusClass = match($status) {

                                        'paid'
                                            => 'status-paid',

                                        'partially_paid'
                                            => 'status-partially',

                                        'cancelled'
                                            => 'status-cancelled',

                                        default
                                            => 'status-pending',

                                    };

                                @endphp


                                <p>

                                    <b>
                                        Status:
                                    </b>


                                    <span class="invoice-status {{ $statusClass }}">

                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $status
                                            )
                                        ) }}

                                    </span>

                                </p>

                            </address>

                        </div>

                    </div>



                    {{-- =================================================
                         PAYMENT INFORMATION
                    ================================================= --}}

                    <div class="event-info-box">

                        <div class="event-info-title">

                            Payment Information

                        </div>


                        <div class="event-info-row">


                            {{-- PAYMENT DATE --}}

                            <div
                                class="event-info-item"
                                style="width: 16%;"
                            >

                                <span class="event-info-label">
                                    Payment Date
                                </span>

                                <span class="event-info-value">

                                    {{ $payment->payment_date
                                        ? \Carbon\Carbon::parse(
                                            $payment->payment_date
                                        )->format('d-M-Y')
                                        : '-'
                                    }}

                                </span>

                            </div>



                            {{-- PAYMENT METHOD --}}

                            <div
                                class="event-info-item"
                                style="width: 16%;"
                            >

                                <span class="event-info-label">
                                    Payment Method
                                </span>

                                <span class="event-info-value">

                                    {{ ucfirst(
                                        $payment->payment_type ?? '-'
                                    ) }}

                                </span>

                            </div>



                            {{-- DIRECTION --}}

                            <div
                                class="event-info-item"
                                style="width: 16%;"
                            >

                                <span class="event-info-label">
                                    Direction
                                </span>

                                <span class="event-info-value">

                                    {{ ucfirst(
                                        $payment->payment_direction ?? '-'
                                    ) }}

                                </span>

                            </div>



                            {{-- SUBSCRIPTION --}}

                            <div
                                class="event-info-item"
                                style="width: 18%;"
                            >

                                <span class="event-info-label">
                                    Subscription
                                </span>

                                <span class="event-info-value">

                                    {{ $payment->tenant->activeSubscription->plan_name
                                        ?? $payment->tenant->activeSubscription->name
                                        ?? 'Subscription' }}

                                </span>

                            </div>



                            {{-- TRANSACTION REFERENCE --}}

                            <div
                                class="event-info-item"
                                style="width: 18%;"
                            >

                                <span class="event-info-label">
                                    Transaction Reference
                                </span>

                                <span class="event-info-value">

                                    {{ $payment->transaction_reference
                                        ?? '-' }}

                                </span>

                            </div>



                            {{-- PAYMENT ID --}}

                            <div
                                class="event-info-item"
                                style="width: 16%;"
                            >

                                <span class="event-info-label">
                                    Payment ID
                                </span>

                                <span class="event-info-value">

                                    #{{ $payment->id }}

                                </span>

                            </div>


                        </div>

                    </div>



                    {{-- =================================================
                         ITEMS
                    ================================================= --}}

                    <div class="col-md-12">

                        <div class="table-responsive m-t-40"
                             style="clear: both;">

                            <table class="table table-hover invoice-table">

                                <thead>

                                <tr>

                                    <th class="text-center">
                                        #
                                    </th>

                                    <th>
                                        Description
                                    </th>

                                    <th class="text-end">
                                        Quantity
                                    </th>

                                    <th class="text-end">
                                        Unit Cost
                                    </th>

                                    <th class="text-end">
                                        Total
                                    </th>

                                </tr>

                                </thead>


                                <tbody>

                                <tr>

                                    <td class="text-center">

                                        1

                                    </td>


                                    <td>

                                        Subscription Payment

                                        @if($payment->tenant?->activeSubscription)

                                            <br>

                                            <small class="text-muted">

                                                {{ $payment->tenant->activeSubscription->plan_name
                                                    ?? $payment->tenant->activeSubscription->name
                                                    ?? 'Active Subscription' }}

                                            </small>

                                        @endif

                                    </td>


                                    <td class="text-end">

                                        1

                                    </td>


                                    <td class="text-end">

                                        Rs.
                                        {{ number_format(
                                            $payment->payment_amount ?? 0,
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-end">

                                        Rs.
                                        {{ number_format(
                                            $payment->payment_amount ?? 0,
                                            2
                                        ) }}

                                    </td>

                                </tr>


                                </tbody>

                            </table>

                        </div>

                    </div>



                    {{-- =================================================
                         TOTALS
                    ================================================= --}}

                    <div class="col-md-12">

                        <div class="invoice-total-box m-t-30 text-end">


                            <p>

                                Subscription Amount:

                                <strong>

                                    Rs.
                                    {{ number_format(
                                        $payment->subscription_amount ?? 0,
                                        2
                                    ) }}

                                </strong>

                            </p>


                            <p>

                                Payment Amount:

                                <strong>

                                    Rs.
                                    {{ number_format(
                                        $payment->payment_amount ?? 0,
                                        2
                                    ) }}

                                </strong>

                            </p>


                            <hr>


                            <p class="invoice-grand-total">

                                <b>
                                    Total Paid:
                                </b>

                                Rs.
                                {{ number_format(
                                    $payment->payment_amount ?? 0,
                                    2
                                ) }}

                            </p>


                            @if(($payment->payment_direction ?? 'in') === 'in')

                                <p class="text-danger">

                                    <b>
                                        Remaining:
                                    </b>

                                    Rs.
                                    {{ number_format(
                                        $payment->remaining_amount ?? 0,
                                        2
                                    ) }}

                                </p>

                            @endif


                        </div>


                        <div class="clearfix"></div>


                        <hr>



                        {{-- =================================================
                             NOTES
                        ================================================= --}}

                        @if($payment->notes)

                            <div class="row">

                                <div class="col-md-12">

                                    <h5>
                                        Notes
                                    </h5>

                                    <p class="text-muted">

                                        {{ $payment->notes }}

                                    </p>

                                </div>

                            </div>

                        @endif



                        {{-- =================================================
                             ACTION BUTTONS
                        ================================================= --}}

                        <div class="text-end no-print">

                            <a href="{{ route('admin.invoices.index') }}"
                               class="btn btn-secondary">

                                <i class="fa fa-arrow-left"></i>

                                Back

                            </a>


                            <button class="btn btn-default btn-outline"
                                    type="button"
                                    onclick="window.print();">

                                <i class="fa fa-print"></i>

                                Print

                            </button>

                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@include('admin.footer')
</body>

</html>