<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Payment History</title>

    <style>

        .payment-summary-card {
            height: 100%;
        }

        .payment-summary-item {
            padding: 12px 0;
            border-bottom: 1px solid #edf1f5;
        }

        .payment-summary-item:last-child {
            border-bottom: 0;
        }

        .payment-summary-label {
            color: #6c757d;
            font-size: 13px;
            display: block;
            margin-bottom: 4px;
        }

        .payment-summary-value {
            font-size: 15px;
            font-weight: 600;
            color: #343a40;
        }

        .payment-form-card .form-floating {
            margin-bottom: 18px;
        }

        .payment-form-card .form-floating > label {
            color: #6c757d;
        }

        .payment-history-table th {
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
        }

        .payment-history-table td {
            vertical-align: middle;
            font-size: 13px;
        }

        .payment-amount {
            color: #198754;
            font-weight: 700;
            white-space: nowrap;
        }

        .empty-payment-state {
            padding: 60px 20px;
        }

        .btn-label {
            margin-right: 6px;
        }

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

                    <i class="ti-wallet me-2"></i>

                    Payment History

                </h4>

                <p class="text-muted mb-0">

                    Manage payments and installments for this booking

                </p>

            </div>

            <div class="col-md-4 text-end">

                <a
                    href="{{ route('bookings.index') }}"
                    class="btn btn-secondary waves-effect waves-light"
                >

                    <span class="btn-label">
                        <i class="fa fa-arrow-left"></i>
                    </span>

                    Back to Bookings

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
        {{-- ERROR --}}
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
        {{-- VALIDATION --}}
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
        {{-- MAIN CONTENT --}}
        {{-- ========================================================= --}}

        <div class="row">

            {{-- ===================================================== --}}
            {{-- LEFT COLUMN --}}
            {{-- ===================================================== --}}

            <div class="col-lg-4 col-xlg-3 col-md-5">

                {{-- ================================================= --}}
                {{-- CUSTOMER INFORMATION --}}
                {{-- ================================================= --}}

                <div class="card">

                    <div class="card-body">

                        <h4 class="card-title">
                            Customer Information
                        </h4>

                        <h6 class="card-subtitle mb-4">
                            Customer details
                        </h6>

                        <div class="profile-info-item">

                            <small>
                                Customer Name
                            </small>

                            <h6>
                                {{ $booking->customer->name ?? 'N/A' }}
                            </h6>

                        </div>

                        <div class="profile-info-item">

                            <small>
                                <i class="fas fa-envelope me-1"></i>
                                Email Address
                            </small>

                            <h6>
                                {{ $booking->customer->email ?? 'N/A' }}
                            </h6>

                        </div>

                        <div class="profile-info-item">

                            <small>
                                <i class="fas fa-phone me-1"></i>
                                Phone
                            </small>

                            <h6>
                                {{ $booking->customer->phone_1 ?? 'N/A' }}
                            </h6>

                        </div>

                        <div class="profile-info-item">

                            <small>
                                <i class="fas fa-id-card me-1"></i>
                                CNIC
                            </small>

                            <h6>
                                {{ $booking->customer->nic_number ?? 'N/A' }}
                            </h6>

                        </div>

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
                {{-- ADD PAYMENT --}}
                {{-- ================================================= --}}

                @if((float) ($booking->remaining_amount ?? 0) > 0)

                    <div class="card shadow-sm payment-form-card">

                        <div class="card-body">

                            <h4 class="card-title">
                                Add New Installment
                            </h4>

                            <h6 class="card-subtitle mb-4">
                                Record a new payment against this booking
                            </h6>

                            <form
                                action="{{ route('bookings.payments.store', $booking->id) }}"
                                method="POST"
                            >

                                @csrf

                                {{-- AMOUNT --}}

                                <div class="form-floating">

                                    <input
                                        type="number"
                                        name="amount"
                                        id="payment_amount"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        placeholder="Amount Paid"
                                        min="1"
                                        max="{{ $booking->remaining_amount }}"
                                        step="0.01"
                                        value="{{ old('amount') }}"
                                        required
                                    >

                                    <label for="payment_amount">
                                        Amount Paid
                                    </label>

                                    @error('amount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- INSTALLMENT --}}

                                <div class="form-floating">

                                    <select
                                        name="installment_type"
                                        id="installment_type"
                                        class="form-select @error('installment_type') is-invalid @enderror"
                                        required
                                    >

                                        <option value="">
                                            -- Select Installment --
                                        </option>

                                        @forelse($installmentOptions ?? [] as $option)

                                            <option
                                                value="{{ $option }}"
                                                {{ old('installment_type') == $option ? 'selected' : '' }}
                                            >
                                                {{ $option }}
                                            </option>

                                        @empty

                                            <option value="" disabled>
                                                No installment options configured
                                            </option>

                                        @endforelse

                                    </select>

                                    <label for="installment_type">
                                        Installment
                                    </label>

                                    @error('installment_type')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                                {{-- PAYMENT METHOD --}}

                                <div class="form-floating">

                                    <select
                                        name="payment_method"
                                        id="payment_method"
                                        class="form-select @error('payment_method') is-invalid @enderror"
                                        required
                                    >

                                        <option value="cash"
                                            {{ old('payment_method', 'cash') === 'cash' ? 'selected' : '' }}>
                                            Cash
                                        </option>

                                        <option value="bank_transfer"
                                            {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>
                                            Bank Transfer
                                        </option>

                                        <option value="card"
                                            {{ old('payment_method') === 'card' ? 'selected' : '' }}>
                                            Card
                                        </option>

                                        <option value="online"
                                            {{ old('payment_method') === 'online' ? 'selected' : '' }}>
                                            Online
                                        </option>

                                        <option value="cheque"
                                            {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>
                                            Cheque
                                        </option>

                                    </select>

                                    <label for="payment_method">
                                        Payment Method
                                    </label>

                                    @error('payment_method')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                                {{-- DATE --}}

                                <div class="form-floating">

                                    <input
                                        type="date"
                                        name="paid_at"
                                        id="paid_at"
                                        class="form-control @error('paid_at') is-invalid @enderror"
                                        value="{{ old('paid_at', date('Y-m-d')) }}"
                                        required
                                    >

                                    <label for="paid_at">
                                        Payment Date
                                    </label>

                                    @error('paid_at')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                                {{-- TRANSACTION REFERENCE --}}

                                <div class="form-floating">

                                    <input
                                        type="text"
                                        name="transaction_reference"
                                        id="transaction_reference"
                                        class="form-control"
                                        placeholder="Transaction Reference"
                                        value="{{ old('transaction_reference') }}"
                                    >

                                    <label for="transaction_reference">
                                        Transaction Reference
                                    </label>

                                </div>

                                {{-- NOTES --}}

                                <div class="form-floating">

                                    <textarea
                                        name="notes"
                                        id="notes"
                                        class="form-control"
                                        placeholder="Notes"
                                        style="height: 100px;"
                                    >{{ old('notes') }}</textarea>

                                    <label for="notes">
                                        Notes
                                    </label>

                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-primary waves-effect waves-light w-100"
                                >

                                    <span class="btn-label">
                                        <i class="fa fa-save"></i>
                                    </span>

                                    Save Payment

                                </button>

                            </form>

                        </div>

                    </div>

                @else

                    <div class="card shadow-sm">

                        <div class="card-body text-center">

                            <div class="mb-3">

                                <i
                                    class="fa fa-check-circle text-success"
                                    style="font-size:45px;"
                                ></i>

                            </div>

                            <h5 class="text-success">
                                Payment Completed
                            </h5>

                            <p class="text-muted mb-0">
                                This booking has no remaining balance.
                            </p>

                        </div>

                    </div>

                @endif

            </div>

            {{-- ===================================================== --}}
            {{-- RIGHT COLUMN --}}
            {{-- ===================================================== --}}

            <div class="col-lg-8">

                <div class="card">

                    {{-- TABS --}}

                    <ul
                        class="nav nav-tabs profile-tab"
                        role="tablist"
                    >

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

                        <li class="nav-item">

                            <a
                                class="nav-link"
                                data-bs-toggle="tab"
                                href="#paymentLedger"
                                role="tab"
                            >

                                <i class="fas fa-list-alt me-1"></i>

                                Payment Ledger / History

                            </a>

                        </li>

                    </ul>

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
                                    Booking Information
                                </h4>

                                <h6 class="card-subtitle">
                                    Complete booking information
                                </h6>

                                <div class="table-responsive mt-3">

                                    <table class="table color-table primary-table">

                                        <thead>

                                            <tr>

                                                <th>
                                                    Particular
                                                </th>

                                                <th>
                                                    Details
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            <tr>
                                                <td>
                                                    <strong>Booking ID</strong>
                                                </td>

                                                <td>
                                                    {{ $booking->id }}
                                                </td>
                                            </tr>

                                            <tr>

                                                <td>
                                                    <strong>Lawn Type</strong>
                                                </td>

                                                <td>
                                                    {{ $booking->lawnType->lawn_type ?? 'N/A' }}
                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    <strong>Event Type</strong>
                                                </td>

                                                <td>

                                                    {{ ucfirst(
                                                        str_replace(
                                                            '_',
                                                            ' ',
                                                            $booking->event_type ?? 'N/A'
                                                        )
                                                    ) }}

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    <strong>Booking Date</strong>
                                                </td>

                                                <td>

                                                    @if($booking->booking_date)

                                                        {{ \Carbon\Carbon::parse(
                                                            $booking->booking_date
                                                        )->format('d M Y (l)') }}

                                                    @else

                                                        N/A

                                                    @endif

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    <strong>Booking Time</strong>
                                                </td>

                                                <td>
                                                    {{ ucfirst(
                                                        $booking->booking_time ?? 'N/A'
                                                    ) }}
                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    <strong>Number of Guests</strong>
                                                </td>

                                                <td>
                                                    {{ $booking->number_of_guests ?? $booking->guests ?? 0 }}
                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    <strong>Status</strong>
                                                </td>

                                                <td>

                                                    @if(($booking->status ?? '') === 'confirmed')

                                                        <span class="badge bg-success">
                                                            Confirmed
                                                        </span>

                                                    @elseif(($booking->status ?? '') === 'cancelled')

                                                        <span class="badge bg-danger">
                                                            Cancelled
                                                        </span>

                                                    @else

                                                        <span class="badge bg-warning text-dark">

                                                            {{ ucfirst(
                                                                $booking->status ?? 'Pending'
                                                            ) }}

                                                        </span>

                                                    @endif

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    <strong>Payment Method</strong>
                                                </td>

                                                <td>

                                                    {{ ucfirst(
                                                        str_replace(
                                                            '_',
                                                            ' ',
                                                            $booking->payment_method ?? 'N/A'
                                                        )
                                                    ) }}

                                                </td>

                                            </tr>

                                            @if(!empty($booking->notes))

                                                <tr>

                                                    <td>
                                                        <strong>Notes</strong>
                                                    </td>

                                                    <td>
                                                        {{ $booking->notes }}
                                                    </td>

                                                </tr>

                                            @endif

                                        </tbody>

                                    </table>

                                </div>

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
                                    Payment Information
                                </h4>

                                <h6 class="card-subtitle">
                                    Booking payment breakdown
                                </h6>

                                <div class="table-responsive mt-3">

                                    <table class="table color-table primary-table">

                                        <thead>

                                            <tr>

                                                <th>
                                                    Particular
                                                </th>

                                                <th class="text-end">
                                                    Amount
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            <tr>

                                                <td>
                                                    Banquet Booking Amount
                                                </td>

                                                <td class="text-end">

                                                    PKR
                                                    {{ number_format(
                                                        (float) ($booking->booking_amount ?? 0)
                                                    ) }}

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    Services Amount
                                                </td>

                                                <td class="text-end">

                                                    PKR
                                                    {{ number_format(
                                                        (float) $booking->bookingServices->sum(function ($service) {
                                                            return (float) $service->price * (int) $service->quantity;
                                                        })
                                                    ) }}

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    Total Amount
                                                </td>

                                                <td class="text-end">

                                                    PKR
                                                    {{ number_format(
                                                        (float) ($booking->total_amount ?? 0)
                                                    ) }}

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    Tax Amount
                                                </td>

                                                <td class="text-end text-warning">

                                                    PKR
                                                    {{ number_format(
                                                        (float) ($booking->tax_amount ?? 0),
                                                        2
                                                    ) }}

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    <strong>
                                                        Grand Total
                                                    </strong>
                                                </td>

                                                <td class="text-end">

                                                    <strong class="text-primary">

                                                        PKR
                                                        {{ number_format(
                                                            (float) ($booking->grand_total ?? 0),
                                                            2
                                                        ) }}

                                                    </strong>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    Advance Paid
                                                </td>

                                                <td class="text-end text-success">

                                                    PKR
                                                    {{ number_format(
                                                        (float) ($booking->advance_amount ?? 0),
                                                        2
                                                    ) }}

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>
                                                    Remaining Amount
                                                </td>

                                                <td class="text-end text-danger">

                                                    PKR
                                                    {{ number_format(
                                                        (float) ($booking->remaining_amount ?? 0),
                                                        2
                                                    ) }}

                                                </td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

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
                                    Payment Ledger / History
                                </h4>

                                <h6 class="card-subtitle">
                                    Complete payment transaction history
                                </h6>

                                <div class="table-responsive mt-3">

                                    <table
                                        class="table color-table primary-table payment-history-table"
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
                                                    Payment Type
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

                                            @forelse($booking->payments as $payment)

                                                <tr>

                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>

                                                    <td>

                                                        @if($payment->paid_at)

                                                            {{ \Carbon\Carbon::parse(
                                                                $payment->paid_at
                                                            )->format('d-m-Y') }}

                                                        @else

                                                            N/A

                                                        @endif

                                                    </td>

                                                    {{-- METHOD --}}

                                                    <td>

                                                        @php

                                                            $methodColors = [
                                                                'cash' => 'success',
                                                                'bank_transfer' => 'info',
                                                                'card' => 'primary',
                                                                'online' => 'secondary',
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

                                                            {{ ucfirst(
                                                                str_replace(
                                                                    '_',
                                                                    ' ',
                                                                    $payment->payment_method ?? 'N/A'
                                                                )
                                                            ) }}

                                                        </span>

                                                    </td>

                                                    {{-- INSTALLMENT TYPE --}}

                                                    <td>

                                                        @if($payment->installment_type)

                                                            <span class="badge bg-info">

                                                                {{ $payment->installment_type }}

                                                            </span>

                                                        @else

                                                            <span class="badge bg-primary">

                                                                Advance

                                                            </span>

                                                        @endif

                                                    </td>

                                                    {{-- REFERENCE --}}

                                                    <td>

                                                        @if($payment->transaction_reference)

                                                            {{ $payment->transaction_reference }}

                                                        @else

                                                            <span class="text-muted">
                                                                N/A
                                                            </span>

                                                        @endif

                                                    </td>

                                                    {{-- AMOUNT --}}

                                                    <td class="text-end payment-amount">

                                                        PKR
                                                        {{ number_format(
                                                            (float) $payment->amount,
                                                            2
                                                        ) }}

                                                    </td>

                                                </tr>

                                            @empty

                                                <tr>

                                                    <td
                                                        colspan="6"
                                                        class="text-center"
                                                    >

                                                        <div class="empty-payment-state">

                                                            <i
                                                                class="fa fa-credit-card fa-3x text-muted mb-3"
                                                            ></i>

                                                            <h5 class="text-muted">
                                                                No Payments Found
                                                            </h5>

                                                            <p class="text-muted mb-0">
                                                                No payment transactions
                                                                have been recorded for
                                                                this booking yet.
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

                {{-- ================================================= --}}
                {{-- PAYMENT SUMMARY --}}
                {{-- ================================================= --}}

                <div class="row mt-3">

                    {{-- Grand Total --}}

                    <div class="col-md-4">

                        <div class="card summary-card bg-light border">

                            <div class="card-body">

                                <h6 class="text-muted mb-1">
                                    Grand Total
                                </h6>

                                <h4 class="mb-0">

                                    PKR

                                    {{ number_format(
                                        (float) ($booking->grand_total ?? 0),
                                        2
                                    ) }}

                                </h4>

                            </div>

                        </div>

                    </div>

                    {{-- Paid --}}

                    <div class="col-md-4">

                        <div class="card summary-card bg-light border">

                            <div class="card-body">

                                <h6 class="text-muted mb-1">
                                    Paid Amount
                                </h6>

                                <h4 class="mb-0 text-success">

                                    PKR

                                    {{ number_format(
                                        (float) ($booking->advance_amount ?? 0),
                                        2
                                    ) }}

                                </h4>

                            </div>

                        </div>

                    </div>

                    {{-- Remaining --}}

                    <div class="col-md-4">

                        <div class="card summary-card bg-light border">

                            <div class="card-body">

                                <h6 class="text-muted mb-1">
                                    Remaining Amount
                                </h6>

                                <h4 class="mb-0 text-danger">

                                    PKR

                                    {{ number_format(
                                        (float) ($booking->remaining_amount ?? 0),
                                        2
                                    ) }}

                                </h4>

                            </div>

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