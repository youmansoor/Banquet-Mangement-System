
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Invoice - {{ $invoice->invoice_number }}
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
   EVENT INFORMATION
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
    white-space: nowrap;
}

@media (max-width: 991px) {

    .event-info-row {
        display: flex;
        flex-wrap: wrap;
    }

    .event-info-item {
        width: 50%;
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
        width: 100%;
        border-right: none;
    }

}

    </style>

</head>


<body>

@include('tenant.nav')


<div class="page-wrapper">

    <div class="container-fluid">


        {{-- PAGE HEADER --}}

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

                            <a href="{{ route('invoices.index') }}">
                                Invoices
                            </a>

                        </li>

                        <li class="breadcrumb-item active">

                            {{ $invoice->invoice_number }}

                        </li>

                    </ol>


                    <a href="{{ route('invoices.edit', $invoice) }}"
                       class="btn btn-warning text-white m-l-15">

                        <i class="fa fa-edit"></i>

                        Edit

                    </a>


                    <button type="button"
                            class="btn btn-info text-white m-l-15"
                            onclick="window.print();">

                        <i class="fa fa-print"></i>

                        Print

                    </button>

                </div>

            </div>

        </div>


        {{-- INVOICE --}}

        <div class="row">

            <div class="col-md-12">

                <div class="card card-body printableArea">


                    {{-- INVOICE TITLE --}}

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
                                        #{{ $invoice->invoice_number }}
                                    </b>

                                </h3>

                            </div>

                        </div>

                    </div>


                    <hr>


                    {{-- TENANT + CUSTOMER --}}

                    <div class="row">


                        {{-- TENANT --}}

                        <div class="col-md-6">

                            <address>

                                <h3>

                                    &nbsp;

                                    <b class="text-danger">

                                        {{ $invoice->tenant->name
                                            ?? 'Banquet Management' }}

                                    </b>

                                </h3>


                                <p class="text-muted m-l-5">

                                    @if(!empty($invoice->tenant->address))

                                        {{ $invoice->tenant->address }}

                                        <br>

                                    @endif


                                    @if(!empty($invoice->tenant->phone))

                                        Phone:
                                        {{ $invoice->tenant->phone }}

                                        <br>

                                    @endif


                                    @if(!empty($invoice->tenant->email))

                                        Email:
                                        {{ $invoice->tenant->email }}

                                    @endif

                                </p>

                            </address>

                        </div>


                        {{-- CUSTOMER --}}

                        <div class="col-md-6 text-end">

                            <address>

                                <h3>
                                    To,
                                </h3>


                                <h4 class="font-bold">

                                    {{ $invoice->customer->name
                                        ?? 'N/A' }}

                                </h4>


                                <p class="text-muted">

                                    @if(!empty($invoice->customer->address))

                                        {{ $invoice->customer->address }}

                                        <br>

                                    @endif


                                    @if(!empty($invoice->customer->phone))

                                        Phone:
                                        {{ $invoice->customer->phone }}

                                        <br>

                                    @endif


                                    @if(!empty($invoice->customer->email))

                                        Email:
                                        {{ $invoice->customer->email }}

                                    @endif

                                </p>


                                <p class="m-t-30">

                                    <b>
                                        Invoice Date:
                                    </b>

                                    <i class="fa fa-calendar"></i>

                                    {{ $invoice->invoice_date
                                        ? $invoice->invoice_date->format('d M Y')
                                        : '-' }}

                                </p>


                                @if($invoice->due_date)

                                    <p>

                                        <b>
                                            Due Date:
                                        </b>

                                        <i class="fa fa-calendar"></i>

                                        {{ $invoice->due_date->format('d M Y') }}

                                    </p>

                                @endif


                                {{-- STATUS --}}

                                <p>

                                    <b>
                                        Status:
                                    </b>

                                    @php

                                        $statusClass = match($invoice->status) {

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


                                    <span class="invoice-status {{ $statusClass }}">

                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $invoice->status
                                            )
                                        ) }}

                                    </span>

                                </p>

                            </address>

                        </div>

                    </div>


                    {{-- =========================================================
     EVENT INFORMATION
========================================================= --}}

@if($invoice->booking)

    <div class="event-info-box">

        {{-- TITLE --}}
        <div class="event-info-title">

            Event Information

        </div>


        {{-- INFORMATION ROW --}}
        <div class="event-info-row">


            {{-- BOOKING DATE --}}
            <div
                class="event-info-item"
                style="width: 12%;"
            >

                <span class="event-info-label">
                    Booking Date
                </span>

                <span class="event-info-value">

                    {{ $invoice->booking->booking_date
                        ? \Carbon\Carbon::parse(
                            $invoice->booking->booking_date
                        )->format('d-M-Y')
                        : '-'
                    }}

                </span>

            </div>


            {{-- GUESTS --}}
            <div
                class="event-info-item"
                style="width: 12%;"
            >

                <span class="event-info-label">
                    Number Of Guest
                </span>

                <span class="event-info-value">

                    {{ $invoice->booking->number_of_guests ?? '-' }}

                </span>

            </div>


            {{-- EVENT NAME --}}
            <div
                class="event-info-item"
                style="width: 13%;"
            >

                <span class="event-info-label">
                    Event Name
                </span>

                <span class="event-info-value">

                    {{ $invoice->booking->event_type ?? '-' }}

                </span>

            </div>


            {{-- LAWN TYPE --}}
            <div
                class="event-info-item"
                style="width: 12%;"
            >

                <span class="event-info-label">
                    Lawn-type
                </span>

                <span class="event-info-value">

                    {{ $invoice->booking->lawn_type ?? '-' }}

                </span>

            </div>


            {{-- SPECIAL INSTRUCTION --}}
            <div
                class="event-info-item"
                style="width: 25%;"
            >

                <span class="event-info-label">
                    Any Special Instruction
                </span>

                <span class="event-info-value">

                    {{ $invoice->booking->notes ?? '-' }}

                </span>

            </div>


            {{-- TIME --}}
            <div
                class="event-info-item"
                style="width: 16%;"
            >

                <span class="event-info-label">
                    Time / Arrival Time
                </span>

                <span class="event-info-value">

                    {{ ucfirst(
                        $invoice->booking->booking_time ?? '-'
                    ) }}

                </span>

            </div>


            {{-- BOOKING ID --}}
            <div
                class="event-info-item"
                style="width: 10%;"
            >

                <span class="event-info-label">
                    Booking ID
                </span>

                <span class="event-info-value">

                    #{{ $invoice->booking->id }}

                </span>

            </div>


        </div>

    </div>

@endif


                    {{-- ITEMS --}}

                    <div class="col-md-12">

                        <div class="table-responsive m-t-40"
                             style="clear: both;">

                            <table class="table table-hover">

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

                                @forelse($invoice->items as $item)

                                    <tr>

                                        <td class="text-center">

                                            {{ $loop->iteration }}

                                        </td>


                                        <td>

                                            {{ $item->description }}

                                        </td>


                                        <td class="text-end">

                                            {{ $item->quantity }}

                                        </td>


                                        <td class="text-end">

                                            Rs.
                                            {{ number_format(
                                                $item->unit_cost,
                                                2
                                            ) }}

                                        </td>


                                        <td class="text-end">

                                            Rs.
                                            {{ number_format(
                                                $item->total,
                                                2
                                            ) }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5"
                                            class="text-center">

                                            No invoice items found.

                                        </td>

                                    </tr>

                                @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>


                    {{-- TOTALS --}}

                    <div class="col-md-12">

                        <div class="invoice-total-box m-t-30 text-end">


                            <p>

                                Sub - Total:

                                <strong>

                                    Rs.
                                    {{ number_format(
                                        $invoice->subtotal,
                                        2
                                    ) }}

                                </strong>

                            </p>


                            @if($invoice->discount > 0)

                                <p class="text-danger">

                                    Discount:

                                    <strong>

                                        - Rs.
                                        {{ number_format(
                                            $invoice->discount,
                                            2
                                        ) }}

                                    </strong>

                                </p>

                            @endif


                            @if($invoice->tax > 0)

                                <p>

                                    Tax:

                                    <strong>

                                        Rs.
                                        {{ number_format(
                                            $invoice->tax,
                                            2
                                        ) }}

                                    </strong>

                                </p>

                            @endif


                            @if($invoice->additional_charges > 0)

                                <p>

                                    Additional Charges:

                                    <strong>

                                        Rs.
                                        {{ number_format(
                                            $invoice->additional_charges,
                                            2
                                        ) }}

                                    </strong>

                                </p>

                            @endif


                            <hr>


                            {{-- GRAND TOTAL --}}
                            <p class="invoice-grand-total">

                                <b>
                                    Grand Total:
                                </b>

                                Rs.
                                {{ number_format(
                                    (float) ($invoice->grand_total ?? 0),
                                    2
                                ) }}

                            </p>


                            {{-- PAY AMOUNT --}}
                            <p class="text-primary">

                                <b>
                                    Pay Amount:
                                </b>

                                Rs.
                                {{ number_format(
                                    (float) ($invoice->pay_amount ?? 0),
                                    2
                                ) }}

                            </p>


                            {{-- PAID --}}
                            <p class="text-success">

                                <b>
                                    Paid:
                                </b>

                                Rs.
                                {{ number_format(
                                    (float) ($invoice->paid_amount ?? 0),
                                    2
                                ) }}

                            </p>


                            {{-- REMAINING --}}
                            <p class="text-danger">

                                <b>
                                    Remaining:
                                </b>

                                Rs.
                                {{ number_format(
                                    (float) ($invoice->remaining_amount ?? 0),
                                    2
                                ) }}

                            </p>

                        </div>


                        <div class="clearfix"></div>


                        <hr>


                        {{-- NOTES --}}

                        @if($invoice->notes)

                            <div class="row">

                                <div class="col-md-12">

                                    <h5>
                                        Notes
                                    </h5>

                                    <p class="text-muted">

                                        {{ $invoice->notes }}

                                    </p>

                                </div>

                            </div>

                        @endif


                        {{-- ACTION BUTTONS --}}

                        <div class="text-end no-print">

                            <a href="{{ route('invoices.index') }}"
                               class="btn btn-secondary">

                                <i class="fa fa-arrow-left"></i>

                                Back

                            </a>


                            <a href="{{ route('invoices.edit', $invoice) }}"
                               class="btn btn-warning text-white">

                                <i class="fa fa-edit"></i>

                                Edit

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


@include('tenant.footer')

</body>

</html>