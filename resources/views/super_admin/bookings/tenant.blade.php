<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $tenant->owner_name ?? 'Tenant' }} - Bookings
    </title>

    <style>

        .booking-table {
            min-width: 1700px;
        }

        .booking-table th {
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
            vertical-align: middle;
        }

        .booking-table td {
            vertical-align: middle;
            font-size: 13px;
        }

        .amount-cell {
            white-space: nowrap;
            font-weight: 600;
        }

        .remaining-amount {
            color: #dc3545;
        }

        .advance-amount {
            color: #198754;
        }

        .grand-total {
            color: #0d6efd;
        }

        .booking-amount {
            color: #6f42c1;
        }

        .tax-amount {
            color: #fd7e14;
        }

        .customer-name {
            font-weight: 600;
            color: #343a40;
        }

        .customer-phone {
            font-size: 12px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .info-card {
            border: 0;
            border-radius: 8px;
        }

    </style>

</head>


<body>

@include('admin.nav')


<div class="page-wrapper">

    <div class="container-fluid">


        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="row page-titles">

            <div class="col-md-8">

                <h4 class="text-themecolor">

                    <i class="ti-calendar me-2"></i>

                    {{ $tenant->owner_name ?? 'N/A' }}

                </h4>

                <p class="text-muted mb-0">

                    {{ $tenant->banquet_name ?? 'N/A' }}

                    — All Bookings

                </p>

            </div>


            <div class="col-md-4 text-end">

                <a
                    href="{{ route('admin.bookings.index') }}"
                    class="btn btn-secondary"
                >

                    <i class="fa fa-arrow-left me-1"></i>

                    Back

                </a>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- TENANT INFO --}}
        {{-- ========================================================= --}}

        <div class="row mb-4">


            <div class="col-md-4">

                <div class="card shadow-sm info-card">

                    <div class="card-body">

                        <small class="text-muted">
                            Tenant Name
                        </small>

                        <h5 class="mb-0">

                            {{ $tenant->owner_name ?? 'N/A' }}

                        </h5>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card shadow-sm info-card">

                    <div class="card-body">

                        <small class="text-muted">
                            Banquet Name
                        </small>

                        <h5 class="mb-0">

                            {{ $tenant->business_name ?? 'N/A' }}

                        </h5>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card shadow-sm info-card">

                    <div class="card-body">

                        <small class="text-muted">
                            Total Bookings
                        </small>

                        <h5 class="mb-0 text-primary">

                            {{ $bookings->count() }}

                        </h5>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- BOOKINGS TABLE --}}
        {{-- ========================================================= --}}

        <div class="card">

            <div class="card-body">


                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h4 class="card-title mb-0">

                            <i class="ti-calendar me-1"></i>

                            Booking Details

                        </h4>

                        <small class="text-muted">

                            All bookings for
                            {{ $tenant->owner_name }}

                        </small>

                    </div>


                    <span class="badge bg-primary">

                        {{ $bookings->count() }}

                        Bookings

                    </span>

                </div>


                <div class="table-responsive">

                    <table
                        class="table color-table primary-table align-middle mb-0 booking-table"
                    >

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Customer</th>

                                <th>Phone</th>

                                <th>Lawn</th>

                                <th>Event</th>

                                <th>Date</th>

                                <th>Time</th>

                                <th>Guests</th>

                                <th>Booking Amount</th>

                                <th>Tax</th>

                                <th>Discount</th>

                                <th>Grand Total</th>

                                <th>Advance Paid</th>

                                <th>Remaining</th>

                                <th>Payment</th>

                                <th>Status</th>

                            </tr>

                        </thead>


                        <tbody>

                        @forelse($bookings as $booking)

                            @php

                                $bookingAmount = (float) (
                                    $booking->booking_amount ?? 0
                                );

                                $taxAmount = (float) (
                                    $booking->tax_amount ?? 0
                                );

                                $discount = (float) (
                                    $booking->discount ?? 0
                                );

                                $grandTotal = (float) (
                                    $booking->grand_total
                                    ?? $booking->total_amount
                                    ?? 0
                                );

                                $advanceAmount = (float) (
                                    $booking->advance_amount ?? 0
                                );

                                $remainingAmount =
                                    $booking->remaining_amount !== null
                                    ? (float) $booking->remaining_amount
                                    : max(
                                        0,
                                        $grandTotal - $advanceAmount
                                    );

                            @endphp


                            <tr>

                                {{-- # --}}

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                {{-- CUSTOMER --}}

                                <td>

                                    <span class="customer-name">

                                        {{ $booking->customer->name ?? 'N/A' }}

                                    </span>

                                </td>


                                {{-- PHONE --}}

                                <td>

                                    @if($booking->customer?->phone_1)

                                        {{ $booking->customer->phone_1 }}

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- LAWN --}}

                                <td>

                                    {{ $booking->lawnType->lawn_type ?? 'N/A' }}

                                </td>


                                {{-- EVENT --}}

                                <td>

                                    {{ $booking->event_type ?? 'N/A' }}

                                </td>


                                {{-- DATE --}}

                                <td>

                                    @if($booking->booking_date)

                                        {{ \Carbon\Carbon::parse(
                                            $booking->booking_date
                                        )->format('d M Y') }}

                                    @else

                                        N/A

                                    @endif

                                </td>


                                {{-- TIME --}}

                                <td>

                                    @if($booking->booking_time === 'day')

                                        <span class="badge bg-info text-dark">
                                            Day
                                        </span>

                                    @elseif($booking->booking_time === 'night')

                                        <span class="badge bg-dark">
                                            Night
                                        </span>

                                    @else

                                        {{ ucfirst(
                                            $booking->booking_time ?? 'N/A'
                                        ) }}

                                    @endif

                                </td>


                                {{-- GUESTS --}}

                                <td>

                                    {{ number_format(
                                        $booking->number_of_guests ?? 0
                                    ) }}

                                </td>


                                {{-- BOOKING AMOUNT --}}

                                <td class="amount-cell booking-amount">

                                    Rs.
                                    {{ number_format($bookingAmount) }}

                                </td>


                                {{-- TAX --}}

                                <td class="amount-cell tax-amount">

                                    Rs.
                                    {{ number_format($taxAmount) }}

                                </td>


                                {{-- DISCOUNT --}}

                                <td class="amount-cell">

                                    Rs.
                                    {{ number_format($discount) }}

                                </td>


                                {{-- GRAND TOTAL --}}

                                <td class="amount-cell grand-total">

                                    <strong>

                                        Rs.
                                        {{ number_format($grandTotal) }}

                                    </strong>

                                </td>


                                {{-- ADVANCE --}}

                                <td class="amount-cell advance-amount">

                                    Rs.
                                    {{ number_format($advanceAmount) }}

                                </td>


                                {{-- REMAINING --}}

                                <td class="amount-cell remaining-amount">

                                    Rs.
                                    {{ number_format($remainingAmount) }}

                                </td>


                                {{-- PAYMENT --}}

                                <td>

                                    @if($booking->payment_method)

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $booking->payment_method
                                            )
                                        ) }}

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if($booking->status === 'confirmed')

                                        <span class="badge bg-success">
                                            Confirmed
                                        </span>

                                    @elseif($booking->status === 'cancelled')

                                        <span class="badge bg-danger">
                                            Cancelled
                                        </span>

                                    @elseif($booking->status === 'pending')

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            {{ ucfirst(
                                                $booking->status ?? 'N/A'
                                            ) }}

                                        </span>

                                    @endif

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="16"
                                    class="text-center py-5"
                                >

                                    <i
                                        class="fa fa-calendar-times fa-3x text-muted mb-3"
                                    ></i>

                                    <h5 class="text-muted">

                                        No bookings found

                                    </h5>

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


@include('admin.footer')


</body>

</html>
