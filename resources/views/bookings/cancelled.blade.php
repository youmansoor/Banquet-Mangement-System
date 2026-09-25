<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Cancelled Bookings</title>

</head>

<body>

@include('tenant.nav')


<div class="page-wrapper">

    <div class="container-fluid">


        {{-- PAGE HEADER --}}

        <div class="row page-titles">

            <div class="col-md-6">

                <h4 class="text-danger">

                    <i class="fa fa-times-circle me-2"></i>

                    Cancelled Bookings

                </h4>

                <p class="text-muted mb-0">

                    View all cancelled banquet bookings

                </p>

            </div>

        </div>


        {{-- SUCCESS --}}

        @if(session('success'))

            <div class="alert alert-success">

                <i class="fa fa-check-circle me-1"></i>

                {{ session('success') }}

            </div>

        @endif


        {{-- ERROR --}}

        @if(session('error'))

            <div class="alert alert-danger">

                <i class="fa fa-exclamation-circle me-1"></i>

                {{ session('error') }}

            </div>

        @endif

{{-- ========================================================= --}}
{{-- SEARCH / FILTER CARD --}}
{{-- ========================================================= --}}

<div class="card shadow-sm filter-card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('bookings.cancelled') }}"
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
                            'bank_transfer' => 'Bank Transfer',
                            'card' => 'Card',
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
                        href="{{ route('bookings.cancelled') }}"
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

        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table color-table primary-table">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Customer</th>

                                <th>Lawn</th>

                                <th>Event</th>

                                <th>Booking Date</th>

                                <th>Time</th>

                                <th>Guests</th>

                                <th>Grand Total</th>

                                <th>Advance</th>

                                <th>Remaining</th>

                                <th>Cancelled At</th>

                                <th>Reason</th>

                            </tr>

                        </thead>


                        <tbody>

                        @forelse($cancelledBookings as $booking)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $booking->customer->name ?? 'N/A' }}
                                    </strong>

                                    @if($booking->customer?->phone_1)

                                        <br>

                                        <small class="text-muted">

                                            {{ $booking->customer->phone_1 }}

                                        </small>

                                    @endif

                                </td>


                                <td>

                                    {{ $booking->lawnType->lawn_type
                                        ?? 'N/A'
                                    }}

                                </td>


                                <td>

                                    {{ $booking->event_type }}

                                </td>


                                <td>

                                    {{ $booking->booking_date
                                        ? $booking->booking_date->format('d M Y')
                                        : 'N/A'
                                    }}

                                </td>


                                <td>

                                    @if($booking->booking_time === 'day')

                                        <span class="badge bg-info text-dark">
                                            Day
                                        </span>

                                    @else

                                        <span class="badge bg-dark">
                                            Night
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    {{ number_format(
                                        $booking->number_of_guests
                                    ) }}

                                </td>


                                <td class="fw-bold">

                                    Rs.
                                    {{ number_format(
                                        (float) $booking->grand_total,
                                        2
                                    ) }}

                                </td>


                                <td>

                                    Rs.
                                    {{ number_format(
                                        (float) $booking->advance_amount,
                                        2
                                    ) }}

                                </td>


                                <td class="text-danger">

                                    Rs.
                                    {{ number_format(
                                        (float) $booking->remaining_amount,
                                        2
                                    ) }}

                                </td>


                                <td>

                                    {{ $booking->cancelled_at
                                        ? $booking->cancelled_at->format('d M Y h:i A')
                                        : 'N/A'
                                    }}

                                </td>


                                <td>

                                    {{ $booking->cancellation_reason
                                        ?? 'No reason provided'
                                    }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="12"
                                    class="text-center py-5"
                                >

                                    <i
                                        class="fa fa-calendar-times-o fa-3x text-muted mb-3"
                                    ></i>

                                    <h5 class="text-muted">

                                        No Cancelled Bookings

                                    </h5>

                                    <p class="text-muted mb-0">

                                        There are no cancelled bookings yet.

                                    </p>

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