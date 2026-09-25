<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Booking Details</title>

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    >

    <style>

        .profile-card-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }

        .profile-info-item {
            margin-bottom: 22px;
        }

        .profile-info-item:last-child {
            margin-bottom: 0;
        }

        .profile-info-item small {
            display: block;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .profile-info-item h6 {
            margin-bottom: 0;
            font-weight: 600;
            word-break: break-word;
        }

        .booking-info-item {
            padding: 15px 10px;
            border-bottom: 1px solid #edf1f5;
        }

        .booking-info-item:last-child {
            border-bottom: 0;
        }

        .booking-info-item strong {
            display: block;
            margin-bottom: 5px;
        }

        .summary-card {
            height: 100%;
            border: 1px solid #edf1f5;
        }

        .summary-card h6 {
            font-size: 13px;
        }

        .summary-card h4 {
            font-weight: 700;
        }

        .booking-table th {
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
        }

        .booking-table td {
            vertical-align: middle;
            font-size: 13px;
        }

        .amount-value {
            white-space: nowrap;
        }

        .service-table th {
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
        }

        .service-table td {
            vertical-align: middle;
            font-size: 13px;
        }

        .note-box {
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            border-radius: 4px;
        }

        .btn-label {
            margin-right: 6px;
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

            <div class="col-md-8">

                <h4 class="text-themecolor">

                    <i class="fas fa-calendar-check me-2"></i>

                    Booking Details

                </h4>

                <p class="text-muted mb-0">

                    Booking #{{ $booking->id }}

                </p>

            </div>

            <div class="col-md-4 text-end">

                <a
                    href="{{ route('bookings.edit', $booking->id) }}"
                    class="btn btn-warning text-white waves-effect waves-light"
                >

                    <span class="btn-label">
                        <i class="fas fa-edit"></i>
                    </span>

                    Edit

                </a>

                <button
                    type="button"
                    class="btn btn-primary text-white waves-effect waves-light"
                >

                    <span class="btn-label">
                        <i class="far fa-envelope"></i>
                    </span>

                    Mail

                </button>

                <a
                    href="{{ route('bookings.index') }}"
                    class="btn btn-secondary waves-effect waves-light"
                >

                    <span class="btn-label">
                        <i class="fas fa-arrow-left"></i>
                    </span>

                    Back

                </a>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SUCCESS --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >

                <i class="fas fa-check-circle me-2"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ERROR --}}
        {{-- ========================================================= --}}

        @if(session('error'))

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >

                <i class="fas fa-exclamation-circle me-2"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- MAIN PROFILE STYLE LAYOUT --}}
        {{-- ========================================================= --}}

        <div class="row">

            {{-- ===================================================== --}}
            {{-- LEFT COLUMN --}}
            {{-- ===================================================== --}}

            <div class="col-lg-4 col-xlg-3 col-md-5">

                {{-- ================================================= --}}
                {{-- CUSTOMER PROFILE --}}
                {{-- ================================================= --}}

                <div class="card">

                    <div class="card-body">

                        <center class="m-t-30">

                            {{-- Customer Image --}}

                            <div class="mb-3">

                                @if(!empty($booking->customer->image))

                                    <img
                                        src="{{ asset('storage/' . $booking->customer->image) }}"
                                        class="profile-card-image"
                                        alt="Customer"
                                    >

                                @else

                                    <div
                                        class="bg-primary text-white d-inline-flex align-items-center justify-content-center"
                                        style="
                                            width:150px;
                                            height:150px;
                                            border-radius:50%;
                                            font-size:55px;
                                        "
                                    >

                                        <i class="fas fa-user"></i>

                                    </div>

                                @endif

                            </div>


                            {{-- Customer Name --}}

                            <h4 class="card-title m-t-10">

                                {{ $booking->customer->name ?? 'N/A' }}

                            </h4>

                            <h6 class="card-subtitle">

                                Customer

                            </h6>


                            {{-- Booking ID / Guests --}}

                            <div
                                class="row text-center justify-content-md-center mt-3"
                            >

                                <div class="col-6">

                                    <span class="link">

                                        <i class="fas fa-calendar-check"></i>

                                        <font class="font-medium">

                                            #{{ $booking->id }}

                                        </font>

                                    </span>

                                </div>

                                <div class="col-6">

                                    <span class="link">

                                        <i class="fas fa-users"></i>

                                        <font class="font-medium">

                                            {{
                                                $booking->number_of_guests
                                                ?? $booking->guests
                                                ?? 0
                                            }}

                                        </font>

                                    </span>

                                </div>

                            </div>

                        </center>

                    </div>


                    <div>
                        <hr>
                    </div>


                    {{-- ================================================= --}}
                    {{-- CUSTOMER DETAILS --}}
                    {{-- ================================================= --}}

                    <div class="card-body">

                        {{-- Email --}}

                        <div class="profile-info-item">

                            <small>

                                <i class="fas fa-envelope me-1"></i>

                                Email Address

                            </small>

                            <h6>

                                {{ $booking->customer->email ?? 'N/A' }}

                            </h6>

                        </div>


                        {{-- Phone --}}

                        <div class="profile-info-item">

                            <small>

                                <i class="fas fa-phone me-1"></i>

                                Phone

                            </small>

                            <h6>

                                {{ $booking->customer->phone_1 ?? 'N/A' }}

                            </h6>

                        </div>


                        {{-- CNIC --}}

                        <div class="profile-info-item">

                            <small>

                                <i class="fas fa-id-card me-1"></i>

                                CNIC

                            </small>

                            <h6>

                                {{ $booking->customer->nic_number ?? 'N/A' }}

                            </h6>

                        </div>


                        {{-- Address --}}

                        <div class="profile-info-item">

                            <small>

                                <i class="fas fa-map-marker-alt me-1"></i>

                                Address

                            </small>

                            <h6>

                                {{ $booking->customer->address ?? 'N/A' }}

                            </h6>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- QUICK BOOKING SUMMARY --}}
                {{-- ================================================= --}}

                <div class="card">

                    <div class="card-body">

                        <h4 class="card-title">

                            <i class="fas fa-info-circle me-2"></i>

                            Booking Summary

                        </h4>

                        <h6 class="card-subtitle">

                            Quick booking overview

                        </h6>


                        <div class="booking-info-item">

                            <strong>

                                <i class="fas fa-building me-1"></i>

                                Lawn Type

                            </strong>

                            <span class="text-muted">

                                {{ $booking->lawnType->lawn_type ?? 'N/A' }}

                            </span>

                        </div>


                        <div class="booking-info-item">

                            <strong>

                                <i class="fas fa-glass-cheers me-1"></i>

                                Event Type

                            </strong>

                            <span class="text-muted">

                                {{
                                    ucfirst(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $booking->event_type ?? 'N/A'
                                        )
                                    )
                                }}

                            </span>

                        </div>


                        <div class="booking-info-item">

                            <strong>

                                <i class="fas fa-calendar me-1"></i>

                                Booking Date

                            </strong>

                            <span class="text-muted">

                                @if($booking->booking_date)

                                    {{
                                        \Carbon\Carbon::parse(
                                            $booking->booking_date
                                        )->format('d M Y')
                                    }}

                                @else

                                    N/A

                                @endif

                            </span>

                        </div>


                        <div class="booking-info-item">

                            <strong>

                                <i class="fas fa-clock me-1"></i>

                                Booking Time

                            </strong>

                            <span class="badge bg-info text-dark">

                                <i class="fas fa-sun me-1"></i>

                                {{
                                    ucfirst(
                                        $booking->booking_time ?? 'N/A'
                                    )
                                }}

                            </span>

                        </div>


                        <div class="booking-info-item">

                            <strong>

                                <i class="fas fa-flag me-1"></i>

                                Status

                            </strong>

                            @if(($booking->status ?? '') === 'confirmed')

                                <span class="badge bg-success">

                                    <i class="fas fa-check-circle me-1"></i>

                                    Confirmed

                                </span>

                            @elseif(($booking->status ?? '') === 'cancelled')

                                <span class="badge bg-danger">

                                    <i class="fas fa-times-circle me-1"></i>

                                    Cancelled

                                </span>

                            @else

                                <span class="badge bg-warning text-dark">

                                    <i class="fas fa-hourglass-half me-1"></i>

                                    {{
                                        ucfirst(
                                            $booking->status ?? 'Pending'
                                        )
                                    }}

                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- RIGHT COLUMN --}}
            {{-- ===================================================== --}}

            <div class="col-lg-8">


                {{-- ================================================= --}}
                {{-- PAYMENT SUMMARY CARDS --}}
                {{-- MOVED ABOVE TABS --}}
                {{-- ================================================= --}}

                <div class="row mb-3">

                    {{-- Grand Total --}}

                    <div class="col-md-4">

                        <div
                            class="card summary-card bg-light border"
                        >

                            <div class="card-body">

                                <h6 class="text-muted mb-1">

                                    <i class="fas fa-file-invoice-dollar me-1"></i>

                                    Grand Total

                                </h6>

                                <h4 class="mb-0 text-primary">

                                    Rs.

                                    {{
                                        number_format(
                                            (float) (
                                                $booking->grand_total ?? 0
                                            ),
                                            2
                                        )
                                    }}

                                </h4>

                            </div>

                        </div>

                    </div>


                    {{-- Paid --}}

                    <div class="col-md-4">

                        <div
                            class="card summary-card bg-light border"
                        >

                            <div class="card-body">

                                <h6 class="text-muted mb-1">

                                    <i class="fas fa-check-circle me-1"></i>

                                    Paid Amount

                                </h6>

                                <h4 class="mb-0 text-success">

                                    Rs.

                                    {{
                                        number_format(
                                            (float) (
                                                $booking->advance_amount ?? 0
                                            ),
                                            2
                                        )
                                    }}

                                </h4>

                            </div>

                        </div>

                    </div>


                    {{-- Remaining --}}

                    <div class="col-md-4">

                        <div
                            class="card summary-card bg-light border"
                        >

                            <div class="card-body">

                                <h6 class="text-muted mb-1">

                                    <i class="fas fa-exclamation-circle me-1"></i>

                                    Remaining Amount

                                </h6>

                                <h4 class="mb-0 text-danger">

                                    Rs.

                                    {{
                                        number_format(
                                            (float) (
                                                $booking->remaining_amount ?? 0
                                            ),
                                            2
                                        )
                                    }}

                                </h4>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TABS --}}
                {{-- ================================================= --}}

                <div class="card">

                    <ul
                        class="nav nav-tabs profile-tab"
                        role="tablist"
                    >

                        {{-- BOOKING INFORMATION --}}

                        <li class="nav-item">

                            <a
                                class="nav-link active"
                                data-bs-toggle="tab"
                                href="#bookingInfo"
                                role="tab"
                            >

                                <i class="fas fa-calendar-check me-1"></i>

                                Booking Information

                            </a>

                        </li>


                        {{-- PAYMENT INFORMATION --}}

                        <li class="nav-item">

                            <a
                                class="nav-link"
                                data-bs-toggle="tab"
                                href="#paymentInfo"
                                role="tab"
                            >

                                <i class="fas fa-wallet me-1"></i>

                                Payment Information

                            </a>

                        </li>


                        {{-- SERVICES --}}

                        <li class="nav-item">

                            <a
                                class="nav-link"
                                data-bs-toggle="tab"
                                href="#servicesInfo"
                                role="tab"
                            >

                                <i class="fas fa-cubes me-1"></i>

                                Services

                                @if(
                                    $booking->services &&
                                    $booking->services->count() > 0
                                )

                                    <span class="badge bg-primary ms-1">

                                        {{ $booking->services->count() }}

                                    </span>

                                @endif

                            </a>

                        </li>


                        {{-- PAYMENT LEDGER --}}

                        <li class="nav-item">

                            <a
                                class="nav-link"
                                data-bs-toggle="tab"
                                href="#paymentLedger"
                                role="tab"
                            >

                                <i class="fas fa-list-alt me-1"></i>

                                Payment Ledger

                            </a>

                        </li>

                    </ul>


                    {{-- ================================================= --}}
                    {{-- TAB CONTENT --}}
                    {{-- ================================================= --}}

                    <div class="tab-content">


                        {{-- ================================================= --}}
                        {{-- BOOKING INFORMATION --}}
                        {{-- ================================================= --}}

                        <div
                            class="tab-pane active"
                            id="bookingInfo"
                            role="tabpanel"
                        >

                            <div class="card-body">

                                <h4 class="card-title">

                                    <i class="fas fa-calendar-check me-2"></i>

                                    Booking Details

                                </h4>

                                <h6 class="card-subtitle">

                                    Complete information about this booking

                                </h6>


                                <div class="row mt-3">

                                    {{-- Lawn Type --}}

                                    <div class="col-md-6">

                                        <div class="booking-info-item">

                                            <strong>

                                                <i class="fas fa-building me-1"></i>

                                                Lawn Type

                                            </strong>

                                            <span class="text-muted">

                                                {{
                                                    $booking->lawnType->lawn_type
                                                    ?? 'N/A'
                                                }}

                                            </span>

                                        </div>

                                    </div>


                                    {{-- Event Type --}}

                                    <div class="col-md-6">

                                        <div class="booking-info-item">

                                            <strong>

                                                <i class="fas fa-glass-cheers me-1"></i>

                                                Event Type

                                            </strong>

                                            <span class="text-muted">

                                                {{
                                                    ucfirst(
                                                        str_replace(
                                                            '_',
                                                            ' ',
                                                            $booking->event_type
                                                            ?? 'N/A'
                                                        )
                                                    )
                                                }}

                                            </span>

                                        </div>

                                    </div>


                                    {{-- Date --}}

                                    <div class="col-md-6">

                                        <div class="booking-info-item">

                                            <strong>

                                                <i class="fas fa-calendar me-1"></i>

                                                Booking Date

                                            </strong>

                                            <span class="text-muted">

                                                @if($booking->booking_date)

                                                    {{
                                                        \Carbon\Carbon::parse(
                                                            $booking->booking_date
                                                        )->format(
                                                            'd M Y (l)'
                                                        )
                                                    }}

                                                @else

                                                    N/A

                                                @endif

                                            </span>

                                        </div>

                                    </div>


                                    {{-- Time --}}

                                    <div class="col-md-6">

                                        <div class="booking-info-item">

                                            <strong>

                                                <i class="fas fa-clock me-1"></i>

                                                Booking Time

                                            </strong>

                                            <span class="badge bg-info text-dark">

                                                <i class="fas fa-sun me-1"></i>

                                                {{
                                                    ucfirst(
                                                        $booking->booking_time
                                                        ?? 'N/A'
                                                    )
                                                }}

                                            </span>

                                        </div>

                                    </div>


                                    {{-- Guests --}}

                                    <div class="col-md-6">

                                        <div class="booking-info-item">

                                            <strong>

                                                <i class="fas fa-users me-1"></i>

                                                Number of Guests

                                            </strong>

                                            <span class="text-muted">

                                                {{
                                                    $booking->number_of_guests
                                                    ?? $booking->guests
                                                    ?? 0
                                                }}

                                            </span>

                                        </div>

                                    </div>


                                    {{-- Status --}}

                                    <div class="col-md-6">

                                        <div class="booking-info-item">

                                            <strong>

                                                <i class="fas fa-flag me-1"></i>

                                                Status

                                            </strong>

                                            @if(
                                                ($booking->status ?? '') ===
                                                'confirmed'
                                            )

                                                <span class="badge bg-success">

                                                    <i class="fas fa-check-circle me-1"></i>

                                                    Confirmed

                                                </span>

                                            @elseif(
                                                ($booking->status ?? '') ===
                                                'cancelled'
                                            )

                                                <span class="badge bg-danger">

                                                    <i class="fas fa-times-circle me-1"></i>

                                                    Cancelled

                                                </span>

                                            @else

                                                <span class="badge bg-warning text-dark">

                                                    <i class="fas fa-hourglass-half me-1"></i>

                                                    {{
                                                        ucfirst(
                                                            $booking->status
                                                            ?? 'Pending'
                                                        )
                                                    }}

                                                </span>

                                            @endif

                                        </div>

                                    </div>


                                    {{-- Payment Method --}}

                                    <div class="col-md-6">

                                        <div class="booking-info-item">

                                            <strong>

                                                <i class="fas fa-wallet me-1"></i>

                                                Payment Method

                                            </strong>

                                            <span class="text-muted">

                                                {{
                                                    ucfirst(
                                                        str_replace(
                                                            '_',
                                                            ' ',
                                                            $booking->payment_method
                                                            ?? 'N/A'
                                                        )
                                                    )
                                                }}

                                            </span>

                                        </div>

                                    </div>


                                    {{-- Booking ID --}}

                                    <div class="col-md-6">

                                        <div class="booking-info-item">

                                            <strong>

                                                <i class="fas fa-hashtag me-1"></i>

                                                Booking ID

                                            </strong>

                                            <span class="text-muted">

                                                #{{ $booking->id }}

                                            </span>

                                        </div>

                                    </div>

                                </div>


                                {{-- Notes --}}

                                @if(!empty($booking->notes))

                                    <hr>

                                    <h5 class="font-medium mt-3">

                                        <i class="fas fa-sticky-note me-1"></i>

                                        Notes

                                    </h5>

                                    <div class="note-box mt-3">

                                        {{ $booking->notes }}

                                    </div>

                                @endif

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- PAYMENT INFORMATION --}}
                        {{-- ================================================= --}}

                        <div
                            class="tab-pane"
                            id="paymentInfo"
                            role="tabpanel"
                        >

                            <div class="card-body">

                                <h4 class="card-title">

                                    <i class="fas fa-money-bill-wave me-2"></i>

                                    Amount Breakdown

                                </h4>

                                <h6 class="card-subtitle">

                                    Complete booking payment information

                                </h6>


                                <div class="table-responsive mt-3">

                                    <table
                                        class="table color-table primary-table booking-table"
                                    >

                                        <thead>

                                            <tr>

                                                <th>
                                                    Particular
                                                </th>

                                                <th class="text-end">
                                                    Amount
                                                </th>

                                                <th class="text-center">
                                                    Status
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody>


                                            {{-- Booking Amount --}}

                                            <tr>

                                                <td>

                                                    <i class="fas fa-home me-2"></i>

                                                    Banquet Booking Amount

                                                </td>

                                                <td class="text-end amount-value">

                                                    Rs.

                                                    {{
                                                        number_format(
                                                            (float) (
                                                                $booking->booking_amount
                                                                ?? 0
                                                            ),
                                                            2
                                                        )
                                                    }}

                                                </td>

                                                <td class="text-center">

                                                    <span class="badge bg-primary">

                                                        Booking

                                                    </span>

                                                </td>

                                            </tr>


                                            {{-- Total Amount --}}

                                            <tr>

                                                <td>

                                                    <i class="fas fa-calculator me-2"></i>

                                                    Total Amount (+ Services)

                                                </td>

                                                <td class="text-end amount-value">

                                                    Rs.

                                                    {{
                                                        number_format(
                                                            (float) (
                                                                $booking->total_amount
                                                                ?? 0
                                                            ),
                                                            2
                                                        )
                                                    }}

                                                </td>

                                                <td class="text-center">

                                                    <span class="badge bg-info text-dark">

                                                        Total

                                                    </span>

                                                </td>

                                            </tr>


                                            {{-- Tax --}}

                                            <tr>

                                                <td>

                                                    <i class="fas fa-percent me-2"></i>

                                                    Tax Amount

                                                </td>

                                                <td class="text-end text-warning amount-value">

                                                    Rs.

                                                    {{
                                                        number_format(
                                                            (float) (
                                                                $booking->tax_amount
                                                                ?? 0
                                                            ),
                                                            2
                                                        )
                                                    }}

                                                </td>

                                                <td class="text-center">

                                                    <span class="badge bg-warning text-dark">

                                                        Tax

                                                    </span>

                                                </td>

                                            </tr>


                                            {{-- Grand Total --}}

                                            <tr>

                                                <td>

                                                    <strong>

                                                        <i class="fas fa-file-invoice-dollar me-2"></i>

                                                        Grand Total

                                                    </strong>

                                                </td>

                                                <td class="text-end amount-value">

                                                    <strong class="text-success">

                                                        Rs.

                                                        {{
                                                            number_format(
                                                                (float) (
                                                                    $booking->grand_total
                                                                    ?? 0
                                                                ),
                                                                2
                                                            )
                                                        }}

                                                    </strong>

                                                </td>

                                                <td class="text-center">

                                                    <span class="badge bg-success">

                                                        Grand Total

                                                    </span>

                                                </td>

                                            </tr>


                                            {{-- Advance --}}

                                            <tr>

                                                <td>

                                                    <i class="fas fa-check me-2"></i>

                                                    Advance Amount (Paid)

                                                </td>

                                                <td class="text-end text-success amount-value">

                                                    Rs.

                                                    {{
                                                        number_format(
                                                            (float) (
                                                                $booking->advance_amount
                                                                ?? 0
                                                            ),
                                                            2
                                                        )
                                                    }}

                                                </td>

                                                <td class="text-center">

                                                    <span class="badge bg-success">

                                                        Paid

                                                    </span>

                                                </td>

                                            </tr>


                                            {{-- Remaining --}}

                                            <tr>

                                                <td>

                                                    <i class="fas fa-exclamation me-2"></i>

                                                    Remaining Amount

                                                </td>

                                                <td class="text-end text-danger amount-value">

                                                    Rs.

                                                    {{
                                                        number_format(
                                                            (float) (
                                                                $booking->remaining_amount
                                                                ?? 0
                                                            ),
                                                            2
                                                        )
                                                    }}

                                                </td>

                                                <td class="text-center">

                                                    @if(
                                                        ($booking->remaining_amount
                                                        ?? 0) <= 0
                                                    )

                                                        <span class="badge bg-success">

                                                            Cleared

                                                        </span>

                                                    @else

                                                        <span class="badge bg-danger">

                                                            Due

                                                        </span>

                                                    @endif

                                                </td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>


                                {{-- Payment Status --}}

                                <hr class="my-4">


                                <div class="row">

                                    <div class="col-md-6">

                                        <small class="text-muted d-block mb-2">

                                            Payment Status

                                        </small>


                                        @if(
                                            ($booking->remaining_amount ?? 0)
                                            <= 0
                                        )

                                            <span class="badge bg-success">

                                                <i class="fas fa-check-circle me-1"></i>

                                                Paid

                                            </span>

                                        @elseif(
                                            ($booking->advance_amount ?? 0)
                                            > 0
                                        )

                                            <span class="badge bg-warning text-dark">

                                                <i class="fas fa-hourglass-half me-1"></i>

                                                Partial

                                            </span>

                                        @else

                                            <span class="badge bg-danger">

                                                <i class="fas fa-times me-1"></i>

                                                Pending

                                            </span>

                                        @endif

                                    </div>


                                    <div class="col-md-6">

                                        <small class="text-muted d-block mb-2">

                                            Payment Method

                                        </small>

                                        <h5 class="mb-0">

                                            <i class="fas fa-wallet me-2"></i>

                                            {{
                                                ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $booking->payment_method
                                                        ?? 'N/A'
                                                    )
                                                )
                                            }}

                                        </h5>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- SERVICES --}}
                        {{-- ================================================= --}}

                        <div
                            class="tab-pane"
                            id="servicesInfo"
                            role="tabpanel"
                        >

                            <div class="card-body">

                                <h4 class="card-title">

                                    <i class="fas fa-cubes me-2"></i>

                                    Services

                                </h4>

                                <h6 class="card-subtitle">

                                    Additional services included with this booking

                                </h6>


                                @if(
                                    $booking->services &&
                                    $booking->services->count() > 0
                                )

                                    <div class="table-responsive mt-3">

                                        <table
                                            class="table color-table primary-table service-table"
                                        >

                                            <thead>

                                                <tr>

                                                    <th>
                                                        #
                                                    </th>

                                                    <th>
                                                        Service Name
                                                    </th>

                                                    <th class="text-center">
                                                        Qty
                                                    </th>

                                                    <th class="text-end">
                                                        Price
                                                    </th>

                                                    <th class="text-end">
                                                        Total
                                                    </th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach(
                                                    $booking->services
                                                    as $index => $service
                                                )

                                                    <tr>

                                                        <td>

                                                            {{ $index + 1 }}

                                                        </td>

                                                        <td>

                                                            <i class="fas fa-cube me-1"></i>

                                                            {{
                                                                $service->service
                                                                ->service_name
                                                                ?? 'N/A'
                                                            }}

                                                        </td>

                                                        <td class="text-center">

                                                            {{
                                                                $service->quantity
                                                                ?? 0
                                                            }}

                                                        </td>

                                                        <td class="text-end">

                                                            Rs.

                                                            {{
                                                                number_format(
                                                                    (float) (
                                                                        $service->price
                                                                        ?? 0
                                                                    ),
                                                                    2
                                                                )
                                                            }}

                                                        </td>

                                                        <td class="text-end">

                                                            <strong>

                                                                Rs.

                                                                {{
                                                                    number_format(
                                                                        (
                                                                            $service->price
                                                                            ?? 0
                                                                        ) *
                                                                        (
                                                                            $service->quantity
                                                                            ?? 0
                                                                        ),
                                                                        2
                                                                    )
                                                                }}

                                                            </strong>

                                                        </td>

                                                    </tr>

                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                @else

                                    <div class="text-center py-5">

                                        <i
                                            class="fas fa-cubes fa-3x text-muted mb-3"
                                        ></i>

                                        <h5 class="text-muted">

                                            No Services Found

                                        </h5>

                                        <p class="text-muted mb-0">

                                            No additional services have been
                                            added to this booking.

                                        </p>

                                    </div>

                                @endif

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- PAYMENT LEDGER --}}
                        {{-- ================================================= --}}

                        <div
                            class="tab-pane"
                            id="paymentLedger"
                            role="tabpanel"
                        >

                            <div class="card-body">

                                <h4 class="card-title">

                                    <i class="fas fa-list-alt me-2"></i>

                                    Payment Ledger / History

                                </h4>

                                <h6 class="card-subtitle">

                                    Complete payment transaction history

                                </h6>


                                @if(
                                    isset($booking->payments) &&
                                    $booking->payments->count() > 0
                                )

                                    <div class="table-responsive mt-3">

                                        <table
                                            class="table color-table primary-table booking-table"
                                        >

                                            <thead>

                                                <tr>

                                                    <th>
                                                        #
                                                    </th>

                                                    <th>
                                                        Date
                                                    </th>

                                                    <th>
                                                        Method
                                                    </th>

                                                    <th>
                                                        Reference
                                                    </th>

                                                    <th class="text-end">
                                                        Amount
                                                    </th>

                                                </tr>

                                            </thead>

                                            <tbody>

                                                @foreach(
                                                    $booking->payments
                                                    as $payment
                                                )

                                                    <tr>

                                                        <td>

                                                            {{ $loop->iteration }}

                                                        </td>


                                                        <td>

                                                            @if($payment->paid_at)

                                                                {{
                                                                    \Carbon\Carbon::parse(
                                                                        $payment->paid_at
                                                                    )->format(
                                                                        'd-m-Y'
                                                                    )
                                                                }}

                                                            @else

                                                                N/A

                                                            @endif

                                                        </td>


                                                        <td>

                                                            @php

                                                                $methodColors = [
                                                                    'cash' => 'success',
                                                                    'bank_transfer' => 'info',
                                                                    'cheque' => 'warning',
                                                                ];

                                                                $methodColor =
                                                                    $methodColors[
                                                                        $payment->payment_method
                                                                    ] ?? 'secondary';

                                                            @endphp


                                                            <span
                                                                class="badge bg-{{ $methodColor }}"
                                                            >

                                                                {{
                                                                    ucfirst(
                                                                        str_replace(
                                                                            '_',
                                                                            ' ',
                                                                            $payment->payment_method
                                                                            ?? 'N/A'
                                                                        )
                                                                    )
                                                                }}

                                                            </span>

                                                        </td>


                                                        <td>

                                                            @if(
                                                                $payment->transaction_reference
                                                            )

                                                                {{
                                                                    $payment->transaction_reference
                                                                }}

                                                            @else

                                                                <span class="text-muted">

                                                                    N/A

                                                                </span>

                                                            @endif

                                                        </td>


                                                        <td
                                                            class="text-end text-success"
                                                        >

                                                            <strong>

                                                                Rs.

                                                                {{
                                                                    number_format(
                                                                        (float)
                                                                        $payment->amount,
                                                                        2
                                                                    )
                                                                }}

                                                            </strong>

                                                        </td>

                                                    </tr>

                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                @else

                                    <div class="text-center py-5">

                                        <i
                                            class="fas fa-credit-card fa-3x text-muted mb-3"
                                        ></i>

                                        <h5 class="text-muted">

                                            No Payments Found

                                        </h5>

                                        <p class="text-muted mb-0">

                                            No payment transactions have been
                                            recorded for this booking yet.

                                        </p>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</div>

@include('tenant.footer')


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>
