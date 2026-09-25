<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>All Bookings</title>

    <style>

        .booking-table {
            min-width: 1600px;
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

        .table-responsive {
            overflow-x: auto;
        }

    </style>

</head>


<body>

@include('admin.nav')


<div class="page-wrapper">

    <div class="container-fluid">


        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="row page-titles">

            <div class="col-md-6">

                <h4 class="text-themecolor">

                    <i class="ti-calendar me-2"></i>

                    All Bookings

                </h4>

                <p class="text-muted mb-0">
                    Manage and monitor all tenant bookings
                </p>

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
        {{-- SEARCH / FILTER CARD --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm filter-card mb-4">

            <div class="card-body">

                <h4 class="card-title mb-3">

                    <i class="fa fa-filter me-1"></i>

                    Search & Filter Bookings

                </h4>


                <form
                    method="GET"
                    action="{{ route('admin.bookings.index') }}"
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
                                placeholder="Customer, phone, tenant, lawn or event..."
                            >

                        </div>


                        {{-- TENANT --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Tenant
                            </label>

                            <select
                                name="tenant_id"
                                class="form-control form-select"
                            >

                                <option value="">
                                    All Tenants
                                </option>

                                @foreach($tenants as $tenant)

                                    <option
                                        value="{{ $tenant->id }}"
                                        {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}
                                    >

                                        {{ $tenant->owner_name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- LAWN --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Lawn
                            </label>

                            <select
                                name="lawn_type"
                                class="form-control form-select"
                            >

                                <option value="">
                                    All Lawns
                                </option>

                                @foreach($lawnTypes as $lawn)

                                    <option
                                        value="{{ $lawn->id }}"
                                        {{ request('lawn_type') == $lawn->id ? 'selected' : '' }}
                                    >

                                        {{ $lawn->lawn_type }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- DATE --}}
                        <div class="col-md-4">

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


                        {{-- TIME --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Booking Time
                            </label>

                            <select
                                name="booking_time"
                                class="form-control form-select"
                            >

                                <option value="">
                                    All Times
                                </option>

                                <option
                                    value="day"
                                    {{ request('booking_time') === 'day' ? 'selected' : '' }}
                                >
                                    Day
                                </option>

                                <option
                                    value="night"
                                    {{ request('booking_time') === 'night' ? 'selected' : '' }}
                                >
                                    Night
                                </option>

                            </select>

                        </div>


                        {{-- PAYMENT METHOD --}}
                        <div class="col-md-4">

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

                                <option
                                    value="cash"
                                    {{ request('payment_method') === 'cash' ? 'selected' : '' }}
                                >
                                    Cash
                                </option>

                                <option
                                    value="bank_transfer"
                                    {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}
                                >
                                    Bank Transfer
                                </option>

                                <option
                                    value="card"
                                    {{ request('payment_method') === 'card' ? 'selected' : '' }}
                                >
                                    Card
                                </option>

                                <option
                                    value="online"
                                    {{ request('payment_method') === 'online' ? 'selected' : '' }}
                                >
                                    Online
                                </option>

                                <option
                                    value="cheque"
                                    {{ request('payment_method') === 'cheque' ? 'selected' : '' }}
                                >
                                    Cheque
                                </option>

                            </select>

                        </div>


                        {{-- STATUS --}}
                        <div class="col-md-4">

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
                                    value="pending"
                                    {{ request('status') === 'pending' ? 'selected' : '' }}
                                >
                                    Pending
                                </option>

                                <option
                                    value="confirmed"
                                    {{ request('status') === 'confirmed' ? 'selected' : '' }}
                                >
                                    Confirmed
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
                        <div class="col-md-6 d-flex align-items-end gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="fa fa-search me-1"></i>

                                Search

                            </button>


                            <a
                                href="{{ route('admin.bookings.index') }}"
                                class="btn btn-secondary"
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
        {{-- BOOKINGS TABLE --}}
        {{-- ========================================================= --}}

        {{-- ========================================================= --}}
{{-- TENANT BOOKINGS SUMMARY --}}
{{-- ========================================================= --}}

<div class="card">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h4 class="card-title mb-0">

                    <i class="ti-calendar me-1"></i>

                    Tenant Bookings

                </h4>

                <small class="text-muted">

                    Tenant-wise booking summary

                </small>

            </div>


            <span class="badge bg-primary">

                {{ $tenantBookings->count() }}

                Tenants

            </span>

        </div>


        <div class="table-responsive">

            <table class="table color-table primary-table align-middle mb-0">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Tenant Name</th>

                        <th>Banquet Name</th>

                        <th>Total Bookings</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                @forelse($tenantBookings as $tenantBooking)

                    <tr>

                        {{-- # --}}

                        <td>

                            {{ $loop->iteration }}

                        </td>


                        {{-- TENANT --}}

                        <td>

                            <strong class="customer-name">

                                {{ $tenantBooking['tenant_name'] }}

                            </strong>

                        </td>


                        {{-- BANQUET --}}

                        <td>

                            {{ $tenantBooking['banquet_name'] }}

                        </td>


                        {{-- TOTAL BOOKINGS --}}

                        <td>

                            <span class="badge bg-primary">

                                {{ $tenantBooking['total_bookings'] }}

                                Bookings

                            </span>

                        </td>


                        {{-- ACTION --}}

                        <td>

                            @if($tenantBooking['tenant_id'])

                                <a
                                    href="{{ route(
                                        'admin.bookings.tenant',
                                        $tenantBooking['tenant_id']
                                    ) }}"
                                    class="btn btn-sm btn-primary"
                                >

                                    <i class="fa fa-eye me-1"></i>

                                    View Bookings

                                </a>

                            @else

                                <span class="text-muted">
                                    N/A
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="text-center"
                        >

                            <div class="empty-state">

                                <i
                                    class="fa fa-calendar-times fa-3x text-muted mb-3"
                                ></i>

                                <h5 class="text-muted">

                                    No bookings found

                                </h5>

                                <p class="text-muted mb-0">

                                    Try changing your search
                                    or filter criteria.

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


@include('admin.footer')


</body>

</html>
