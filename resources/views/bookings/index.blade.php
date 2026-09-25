<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Bookings</title>

    <style>

        .booking-table {
            min-width: 1500px;
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

        .customer-name {
            font-weight: 600;
            color: #343a40;
        }

        .customer-phone {
            font-size: 12px;
        }

        .filter-card {
            border: 0;
            border-radius: 8px;
        }

        .filter-card .form-label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .action-buttons {
            white-space: nowrap;
        }

        .action-buttons .btn {
            margin-right: 3px;
        }

        .empty-state {
            padding: 50px 20px;
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

                    <i class="ti-calendar me-2"></i>

                    Bookings

                </h4>

                <p class="text-muted mb-0">
                    Manage all banquet bookings
                </p>

            </div>

            {{--

            <div class="col-md-6 text-end">

                <a
                    href="{{ route('bookings.create') }}"
                    class="btn btn-info text-white"
                >

                    <i class="fa fa-plus me-1"></i>

                    New Booking

                </a>

            </div>

            --}}

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
                    action="{{ route('bookings.index') }}"
                >

                    <div class="row g-3">

                        {{-- SEARCH --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Search
                            </label>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Customer, phone, event or lawn..."
                            >

                        </div>


                        {{-- BOOKING DATE --}}
                        <div class="col-md-2">

                            <label class="form-label">
                                Booking Date
                            </label>

                            <input
                                type="date"
                                name="booking_date"
                                class="form-control"
                                value="{{ request('booking_date') }}"
                            >

                        </div>


                        {{-- PAYMENT METHOD --}}
                        <div class="col-md-2">

                            <label class="form-label">
                                Payment Method
                            </label>

                            <select
                                name="payment_method"
                                class="form-control form-select"
                            >

                                <option value="">
                                    All Methods
                                </option>

                                @foreach([
                                    'cash' => 'Cash',
                                    'card' => 'Card',
                                    'bank' => 'Bank',
                                    'online' => 'Online',
                                    'cheque' => 'Cheque'
                                ] as $value => $label)

                                    <option
                                        value="{{ $value }}"
                                        {{ request('payment_method') === $value ? 'selected' : '' }}
                                    >
                                        {{ $label }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- STATUS --}}
                        <div class="col-md-2">

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
                                    value="confirmed"
                                    {{ request('status') === 'confirmed' ? 'selected' : '' }}
                                >
                                    Confirmed
                                </option>

                                <option
                                    value="pending"
                                    {{ request('status') === 'pending' ? 'selected' : '' }}
                                >
                                    Pending
                                </option>

                                <option
                                    value="cancelled"
                                    {{ request('status') === 'cancelled' ? 'selected' : '' }}
                                >
                                    Cancelled
                                </option>

                            </select>

                        </div>


                        {{-- BUTTONS --}}
                        <div class="col-md-2 d-flex align-items-end gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary flex-fill"
                            >

                                <i class="fa fa-search me-1"></i>

                                Search

                            </button>


                            <a
                                href="{{ route('bookings.index') }}"
                                class="btn btn-secondary"
                                title="Reset"
                            >

                                <i class="fa fa-refresh"></i>

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- BOOKINGS TABLE --}}
        {{-- ========================================================= --}}

        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table color-table primary-table align-middle mb-0 booking-table">

                        <thead>

                            <tr>

                                <th>#</th>
                                
                                <th>Invoice #</th>

                                <th>Customer</th>

                                <th>Lawn</th>

                                <th>Event</th>

                                <th>Date</th>

                                <th>Time</th>

                                {{-- <th>Guests</th> --}}

                                <th>Booking Amount</th>

                                {{-- <th>Tax</th> --}}

                                {{-- <th>Grand Total</th> --}}

                                <th>Advance Paid</th>

                                <th>Remaining Amount</th>

                                {{-- <th>Payment Method</th> --}}

                                {{-- <th>Status</th> --}}

                                <th width="180">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @forelse($bookings as $booking)

                            <tr>

                                {{-- ================================================= --}}
                                {{-- NUMBER --}}
                                {{-- ================================================= --}}

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $booking->invoice?->invoice_number ?? 'N/A' }}
                                </td>

                                {{-- ================================================= --}}
                                {{-- CUSTOMER --}}
                                {{-- ================================================= --}}

                                <td>

                                    <strong class="customer-name">

                                        {{ $booking->customer->name ?? 'N/A' }}

                                    </strong>

                                    @if(!empty($booking->customer->phone_1))

                                        <br>

                                        <small class="text-muted customer-phone">

                                            {{ $booking->customer->phone_1 }}

                                        </small>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- LAWN --}}
                                {{-- ================================================= --}}

                                <td>

                                    {{ $booking->lawnType->lawn_type
                                        ?? $booking->lawn_type
                                        ?? 'N/A'
                                    }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- EVENT --}}
                                {{-- ================================================= --}}

                                <td>

                                    {{ $booking->event_type ?? 'N/A' }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- DATE --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if($booking->booking_date)

                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}

                                    @else

                                        N/A

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- TIME --}}
                                {{-- ================================================= --}}

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

                                        {{ ucfirst($booking->booking_time ?? 'N/A') }}

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- GUESTS --}}
                                {{-- ================================================= --}}

                                {{--
                                <td>

                                    {{ number_format(
                                        (int) ($booking->number_of_guests ?? 0)
                                    ) }}

                                </td>
                                --}}


                                {{-- ================================================= --}}
                                {{-- BOOKING AMOUNT --}}
                                {{-- ================================================= --}}

                                <td class="amount-cell booking-amount">

                                    Rs.

                                    {{ number_format(
                                        (float) ($booking->booking_amount ?? 0)
                                    ) }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- TAX --}}
                                {{-- ================================================= --}}

                                {{--
                                <td class="amount-cell tax-amount">

                                    Rs.

                                    {{ number_format(
                                        (float) ($booking->tax_amount ?? 0),
                                        2
                                    ) }}

                                </td>
                                --}}


                                {{-- ================================================= --}}
                                {{-- GRAND TOTAL --}}
                                {{-- ================================================= --}}

                                {{--
                                <td class="amount-cell grand-total">

                                    <strong>

                                        Rs.

                                        {{ number_format(
                                            (float) (
                                                $booking->grand_total
                                                ?? $booking->total_amount
                                                ?? 0
                                            )
                                        ) }}

                                    </strong>

                                </td>
                                --}}


                                {{-- ================================================= --}}
                                {{-- ADVANCE --}}
                                {{-- ================================================= --}}

                                <td class="amount-cell advance-amount">

                                    Rs.

                                    {{ number_format(
                                        (float) ($booking->advance_amount ?? 0)
                                    ) }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- REMAINING --}}
                                {{-- ================================================= --}}

                                <td class="amount-cell remaining-amount">

                                    @php

                                        $grandTotal = (float) (
                                            $booking->grand_total
                                            ?? $booking->total_amount
                                            ?? $booking->booking_amount
                                            ?? 0
                                        );

                                        $advanceAmount = (float) (
                                            $booking->advance_amount
                                            ?? 0
                                        );

                                        $remainingAmount =
                                            $booking->remaining_amount !== null
                                            ? (float) $booking->remaining_amount
                                            : max(
                                                0,
                                                $grandTotal - $advanceAmount
                                            );

                                    @endphp

                                    Rs.

                                    {{ number_format($remainingAmount) }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- PAYMENT METHOD --}}
                                {{-- ================================================= --}}

                                {{--
                                <td>

                                    @if($booking->payment_method)

                                        {{ ucfirst($booking->payment_method) }}

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>
                                --}}


                                {{-- ================================================= --}}
                                {{-- STATUS --}}
                                {{-- ================================================= --}}

                                {{--
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
                                            {{ ucfirst($booking->status ?? 'N/A') }}
                                        </span>

                                    @endif

                                </td>
                                --}}


                                {{-- ================================================= --}}
                                {{-- ACTIONS --}}
                                {{-- ================================================= --}}

                                <td>

                                    <div class="action-buttons d-flex gap-1">

                                        {{-- PAYMENT --}}
                                        <a
                                            href="{{ route('bookings.payments.history', $booking->id) }}"
                                            class="btn btn-primary text-white"
                                            title="Payment History"
                                        >

                                            <span class="btn-label">

                                                <i class="ti-wallet"></i>

                                            </span>

                                            Payment

                                        </a>


                                        {{-- VIEW --}}
                                        {{--
                                        <a
                                            href="{{ route('bookings.show', $booking->id) }}"
                                            class="btn btn-primary"
                                            title="View Booking"
                                        >

                                            <span class="btn-label">

                                                <i class="fa fa-eye"></i>

                                            </span>

                                            View

                                        </a>
                                        --}}


                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('bookings.edit', $booking->id) }}"
                                            class="btn btn-warning"
                                            title="Edit Booking"
                                        >

                                            <span class="btn-label">

                                                <i class="fa fa-edit"></i>

                                            </span>

                                            Edit

                                        </a>


                                        {{-- ================================================= --}}
                                        {{-- CANCEL --}}
                                        {{-- ================================================= --}}

                                        @if($booking->status !== 'cancelled')

                                            <button
                                                type="button"
                                                class="btn btn-danger"
                                                title="Cancel Booking"
                                                data-bs-toggle="modal"
                                                data-bs-target="#cancelBookingModal{{ $booking->id }}"
                                            >

                                                <span class="btn-label">

                                                    <i class="fa fa-times"></i>

                                                </span>

                                                Cancel

                                            </button>

                                        @endif

                                    </div>

                                </td>

                            </tr>


                            {{-- ========================================================= --}}
                            {{-- CANCEL BOOKING MODAL --}}
                            {{-- ========================================================= --}}

                            @if($booking->status !== 'cancelled')

                                <div
                                    class="modal fade"
                                    id="cancelBookingModal{{ $booking->id }}"
                                    tabindex="-1"
                                    aria-labelledby="cancelBookingModalLabel{{ $booking->id }}"
                                    aria-hidden="true"
                                >

                                    <div class="modal-dialog modal-dialog-centered">

                                        <div class="modal-content">


                                            {{-- MODAL HEADER --}}
                                            <div class="modal-header bg-danger text-white">

                                                <h5
                                                    class="modal-title"
                                                    id="cancelBookingModalLabel{{ $booking->id }}"
                                                >

                                                    <i class="fa fa-times-circle me-1"></i>

                                                    Cancel Booking

                                                </h5>


                                                <button
                                                    type="button"
                                                    class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"
                                                ></button>

                                            </div>


                                            {{-- MODAL FORM --}}
                                            <form
                                                method="POST"
                                                action="{{ route('bookings.cancel', $booking->id) }}"
                                            >

                                                @csrf


                                                <div class="modal-body">


                                                    {{-- WARNING --}}
                                                    <div class="alert alert-warning">

                                                        <i class="fa fa-exclamation-triangle me-1"></i>

                                                        Are you sure you want to cancel this booking?

                                                    </div>


                                                    {{-- CUSTOMER --}}
                                                    <div class="mb-3">

                                                        <strong>
                                                            Customer:
                                                        </strong>

                                                        {{ $booking->customer->name ?? 'N/A' }}

                                                    </div>


                                                    {{-- EVENT --}}
                                                    <div class="mb-3">

                                                        <strong>
                                                            Event:
                                                        </strong>

                                                        {{ $booking->event_type ?? 'N/A' }}

                                                    </div>


                                                    {{-- LAWN --}}
                                                    <div class="mb-3">

                                                        <strong>
                                                            Lawn:
                                                        </strong>

                                                        {{ $booking->lawnType->lawn_type
                                                            ?? $booking->lawn_type
                                                            ?? 'N/A'
                                                        }}

                                                    </div>


                                                    {{-- DATE --}}
                                                    <div class="mb-3">

                                                        <strong>
                                                            Date:
                                                        </strong>

                                                        @if($booking->booking_date)

                                                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}

                                                        @else

                                                            N/A

                                                        @endif

                                                    </div>


                                                    {{-- TIME --}}
                                                    <div class="mb-3">

                                                        <strong>
                                                            Time:
                                                        </strong>

                                                        @if($booking->booking_time === 'day')

                                                            Day

                                                        @elseif($booking->booking_time === 'night')

                                                            Night

                                                        @else

                                                            {{ ucfirst($booking->booking_time ?? 'N/A') }}

                                                        @endif

                                                    </div>


                                                    {{-- CANCELLATION REASON --}}
                                                    <div class="mb-3">

                                                        <label
                                                            for="cancellation_reason_{{ $booking->id }}"
                                                            class="form-label"
                                                        >

                                                            Cancellation Reason

                                                        </label>


                                                        <textarea
                                                            id="cancellation_reason_{{ $booking->id }}"
                                                            name="cancellation_reason"
                                                            class="form-control"
                                                            rows="3"
                                                            placeholder="Enter cancellation reason..."
                                                        ></textarea>

                                                    </div>

                                                </div>


                                                {{-- MODAL FOOTER --}}
                                                <div class="modal-footer">

                                                    <button
                                                        type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal"
                                                    >

                                                        <i class="ti-close me-1"></i>

                                                        Close

                                                    </button>


                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger"
                                                    >

                                                        <i class="fa fa-times me-1"></i>

                                                        Yes, Cancel Booking

                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            @endif


                        @empty

                            {{-- ========================================================= --}}
                            {{-- EMPTY STATE --}}
                            {{-- ========================================================= --}}

                            <tr>

                                <td
                                    colspan="15"
                                    class="text-center py-5"
                                >

                                    <div class="empty-state">

                                        <i
                                            class="fa fa-calendar fa-3x text-muted mb-3"
                                        ></i>


                                        <h5 class="text-muted">

                                            No bookings found

                                        </h5>


                                        <p class="text-muted mb-0">

                                            There are no bookings available yet.

                                        </p>

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


@include('tenant.footer')


</body>

</html>
