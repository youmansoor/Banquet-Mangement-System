<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Booking Reminders</title>

    <style>

        .reminder-table {
            min-width: 1250px;
        }

        .reminder-table th {
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
            vertical-align: middle;
        }

        .reminder-table td {
            vertical-align: middle;
            font-size: 13px;
        }

        .customer-name {
            font-weight: 600;
            color: #343a40;
        }

        .customer-email {
            font-size: 12px;
        }

        .event-name {
            font-weight: 600;
            color: #343a40;
        }

        .date-cell {
            white-space: nowrap;
            font-weight: 600;
        }

        .time-cell {
            white-space: nowrap;
        }

        .guest-cell {
            white-space: nowrap;
        }

        .days-remaining {
            white-space: nowrap;
            font-weight: 600;
        }

        .days-normal {
            color: #198754;
        }

        .days-warning {
            color: #fd7e14;
        }

        .days-danger {
            color: #dc3545;
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

        .summary-card {
            border: 0;
            border-radius: 8px;
        }

        .summary-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 20px;
        }

        .reminder-card {
            border: 0;
            border-radius: 8px;
        }

        .reminder-badge {
            font-size: 11px;
            padding: 5px 8px;
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

                    <i class="ti-bell me-2"></i>

                    Booking Reminders

                </h4>

                <p class="text-muted mb-0">

                    Send reminder emails for upcoming bookings

                </p>

            </div>


            <div class="col-md-6 text-end">

                <div class="d-flex justify-content-end align-items-center">

                    <ol class="breadcrumb mb-0">

                        <li class="breadcrumb-item">

                            <a href="{{ url('/tenant/dashboard') }}">
                                Home
                            </a>

                        </li>

                        <li class="breadcrumb-item">

                            <a href="{{ url('/bookings') }}">
                                Bookings
                            </a>

                        </li>

                        <li class="breadcrumb-item active">

                            Reminders

                        </li>

                    </ol>

                </div>

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
        {{-- SEARCH / FILTER CARD --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm filter-card mb-4">

            <div class="card-body">

                <div class="row g-3 align-items-end">


                    {{-- SEARCH --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            id="reminderSearch"
                            class="form-control"
                            placeholder="Customer, email, event or lawn..."
                        >

                    </div>


                    {{-- DATE --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Booking Date
                        </label>

                        <input
                            type="date"
                            id="reminderDate"
                            class="form-control"
                        >

                    </div>


                    {{-- RESET --}}
                    <div class="col-md-2">

                        <button
                            type="button"
                            id="resetReminderFilters"
                            class="btn btn-secondary w-100"
                        >

                            <i class="fa fa-refresh me-1"></i>

                            Reset

                        </button>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- UPCOMING BOOKINGS --}}
        {{-- ========================================================= --}}

        <div class="card">

            <div class="card-body">


                {{-- CARD HEADER --}}
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h4 class="card-title mb-1">

                            Upcoming Bookings

                        </h4>

                        <h6 class="card-subtitle">

                            Bookings scheduled for the next 7 days

                        </h6>

                    </div>


                    <span class="badge bg-primary reminder-badge">

                        <i class="fa fa-calendar me-1"></i>

                        Next 7 Days

                    </span>

                </div>


                {{-- ================================================= --}}
                {{-- TABLE --}}
                {{-- ================================================= --}}

                <div class="table-responsive">

                    <table
                        class="table color-table primary-table align-middle mb-0 reminder-table"
                        id="reminderTable"
                    >

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Event
                                </th>

                                <th>
                                    Lawn
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Time
                                </th>

                                <th>
                                    Guests
                                </th>

                                <th>
                                    Days Remaining
                                </th>

                                <th width="170">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                        @forelse($upcomingBookings as $booking)

                            @php

                                $bookingDate = $booking->booking_date
                                    ? \Carbon\Carbon::parse($booking->booking_date)
                                    : null;

                                $daysRemaining = $bookingDate
                                    ? now()->startOfDay()->diffInDays(
                                        $bookingDate->startOfDay(),
                                        false
                                    )
                                    : null;

                            @endphp


                            <tr class="reminder-row">


                                {{-- # --}}
                                <td>

                                    {{ $loop->iteration }}

                                </td>


                                {{-- CUSTOMER --}}
                                <td>

                                    <span class="customer-name reminder-searchable">

                                        {{ $booking->customer->name ?? 'Unknown' }}

                                    </span>


                                    @if(!empty($booking->customer->email))

                                        <br>

                                        <small class="text-muted customer-email reminder-searchable">

                                            <i class="fa fa-envelope-o me-1"></i>

                                            {{ $booking->customer->email }}

                                        </small>

                                    @else

                                        <br>

                                        <small class="text-muted">

                                            Email not provided

                                        </small>

                                    @endif

                                </td>


                                {{-- EVENT --}}
                                <td>

                                    <span class="event-name reminder-searchable">

                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $booking->event_type ?? 'N/A'
                                            )
                                        ) }}

                                    </span>

                                </td>


                                {{-- LAWN --}}
                                <td>

                                    <span class="reminder-searchable">

                                        {{ $booking->lawnType->lawn_type
                                            ?? $booking->lawn_type
                                            ?? 'Not specified'
                                        }}

                                    </span>

                                </td>


                                {{-- DATE --}}
                                <td class="date-cell">

                                    @if($bookingDate)

                                        <strong>

                                            {{ $bookingDate->format('d M Y') }}

                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            {{ $bookingDate->format('l') }}

                                        </small>

                                    @else

                                        N/A

                                    @endif

                                </td>


                                {{-- TIME --}}
                                <td class="time-cell">

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
                                <td class="guest-cell">

                                    <i class="fa fa-users text-muted me-1"></i>

                                    {{ number_format(
                                        (int) ($booking->number_of_guests ?? 0)
                                    ) }}

                                </td>


                                {{-- DAYS REMAINING --}}
                                <td class="days-remaining">

                                    @if($daysRemaining === 0)

                                        <span class="badge bg-danger">

                                            Today

                                        </span>

                                    @elseif($daysRemaining === 1)

                                        <span class="badge bg-warning text-dark">

                                            Tomorrow

                                        </span>

                                    @elseif($daysRemaining > 1)

                                        <span class="days-normal">

                                            {{ $daysRemaining }} days

                                        </span>

                                    @else

                                        <span class="days-danger">

                                            Today

                                        </span>

                                    @endif

                                </td>


                                {{-- ACTION --}}
                                <td>

                                    <div class="action-buttons">


                                        <form
                                            action="{{ route(
                                                'bookings.remind',
                                                $booking
                                            ) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to send reminder email to this customer?');"
                                        >

                                            @csrf


                                            <button
                                                type="submit"
                                                class="btn btn-primary"
                                                title="Send Reminder"
                                            >

                                                <span class="btn-label">

                                                    <i class="fa fa-envelope"></i>

                                                </span>

                                                Send Reminder

                                            </button>

                                        </form>

                                    </div>

                                </td>


                            </tr>


                        @empty


                            {{-- EMPTY STATE --}}

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center empty-state"
                                >

                                    <i
                                        class="fa fa-calendar-times-o fa-3x text-muted mb-3"
                                    ></i>


                                    <h5 class="text-muted">

                                        No upcoming bookings

                                    </h5>


                                    <p class="text-muted mb-0">

                                        There are no bookings scheduled
                                        for the next 7 days.

                                    </p>

                                </td>

                            </tr>


                        @endforelse


                        </tbody>

                    </table>

                </div>


                {{-- NO SEARCH RESULT --}}
                <div
                    id="noSearchResults"
                    class="text-center py-5 d-none"
                >

                    <i class="fa fa-search fa-3x text-muted mb-3"></i>

                    <h5 class="text-muted">
                        No bookings found
                    </h5>

                    <p class="text-muted mb-0">
                        Try changing your search or date filter.
                    </p>

                </div>


            </div>

        </div>


    </div>

</div>


{{-- ============================================================= --}}
{{-- SEARCH SCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('reminderSearch');

    const dateInput =
        document.getElementById('reminderDate');

    const resetButton =
        document.getElementById('resetReminderFilters');

    const rows =
        document.querySelectorAll('.reminder-row');

    const noResults =
        document.getElementById('noSearchResults');


    function filterBookings()
    {

        const search =
            searchInput.value
                .toLowerCase()
                .trim();

        const selectedDate =
            dateInput.value;

        let visibleRows = 0;


        rows.forEach(function (row) {

            const rowText =
                row.innerText.toLowerCase();


            let matchesSearch =
                !search ||
                rowText.includes(search);


            let matchesDate = true;


            if (selectedDate) {

                const dateCell =
                    row.querySelector('.date-cell');

                if (dateCell) {

                    const dateText =
                        dateCell.innerText.trim();


                    const parts =
                        dateText.split(' ');


                    if (parts.length >= 3) {

                        const day =
                            parts[0];

                        const month =
                            parts[1];

                        const year =
                            parts[2];


                        const monthMap = {
                            Jan: '01',
                            Feb: '02',
                            Mar: '03',
                            Apr: '04',
                            May: '05',
                            Jun: '06',
                            Jul: '07',
                            Aug: '08',
                            Sep: '09',
                            Oct: '10',
                            Nov: '11',
                            Dec: '12'
                        };


                        const monthNumber =
                            monthMap[month];


                        const formattedDate =
                            year +
                            '-' +
                            monthNumber +
                            '-' +
                            day;


                        matchesDate =
                            formattedDate === selectedDate;

                    }

                }

            }


            if (
                matchesSearch &&
                matchesDate
            ) {

                row.style.display = '';

                visibleRows++;

            } else {

                row.style.display = 'none';

            }

        });


        if (
            visibleRows === 0 &&
            rows.length > 0
        ) {

            noResults.classList.remove('d-none');

        } else {

            noResults.classList.add('d-none');

        }

    }


    searchInput.addEventListener(
        'input',
        filterBookings
    );


    dateInput.addEventListener(
        'change',
        filterBookings
    );


    resetButton.addEventListener(
        'click',
        function () {

            searchInput.value = '';

            dateInput.value = '';

            rows.forEach(function (row) {

                row.style.display = '';

            });

            noResults.classList.add('d-none');

        }
    );

});

</script>


@include('tenant.footer')

</body>

</html>
