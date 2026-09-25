<!DOCTYPE html> <html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Booking</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head> <body>
@include('tenant.nav')

<div class="container-fluid py-4">
{{-- ========================================================= --}}
{{-- PAGE HEADER --}}
{{-- ========================================================= --}}

<div class="row">
    <div class="col-12">

        <div class="card">

            <div class="card-body">

                <div class="d-md-flex align-items-center">

                    <div>
                        <h4 class="card-title">
                            <i class="fas fa-calendar-edit me-2"></i>
                            Edit Booking
                        </h4>

                        <h6 class="card-subtitle">
                            Update booking information
                        </h6>
                    </div>

                    <div class="ms-auto mt-3 mt-md-0">

                        <a href="{{ route('bookings.index') }}"
                           class="btn btn-secondary waves-effect waves-light">

                            <span class="btn-label">
                                <i class="fas fa-arrow-left"></i>
                            </span>

                            Back to Bookings

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

{{-- ========================================================= --}}
{{-- ERRORS --}}
{{-- ========================================================= --}}

@if ($errors->any())

    <div class="alert alert-danger alert-dismissible fade show">

        <strong>
            <i class="fas fa-exclamation-triangle me-1"></i>
            Please fix the following errors:
        </strong>

        <ul class="mb-0 mt-2">

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

@endif

{{-- ========================================================= --}}
{{-- EDIT BOOKING FORM --}}
{{-- ========================================================= --}}

<div class="row">

    <div class="col-12">

        <div class="card">

            <div class="card-body">

                <h4 class="card-title">
                    <i class="fas fa-edit me-2"></i>
                    Booking Information
                </h4>

                <h6 class="card-subtitle">
                    Update customer, event, payment and booking details
                </h6>

                <form action="{{ route('bookings.update', $booking->id) }}"
                      method="POST"
                      id="editBookingForm"
                      class="mt-4">

                    @csrf
                    @method('PUT')

                    {{-- ================================================= --}}
                    {{-- CUSTOMER / LAWN --}}
                    {{-- ================================================= --}}

                    <div class="row">

                        {{-- Customer --}}
                        <div class="col-md-6">

                            <div class="form-floating mb-3">

                                <select name="customer_id"
                                        id="customer_id"
                                        class="form-select"
                                        required>

                                    <option value="">Select Customer</option>

                                    @foreach($customers as $customer)

                                        <option value="{{ $customer->id }}"
                                            {{ old('customer_id', $booking->customer_id) == $customer->id ? 'selected' : '' }}>

                                            {{ $customer->name }}
                                            - {{ $customer->phone_1 }}

                                        </option>

                                    @endforeach

                                </select>

                                <label for="customer_id">
                                    Customer <span class="text-danger">*</span>
                                </label>

                            </div>

                        </div>

                        {{-- Lawn Type --}}
                        <div class="col-md-6">

                            <div class="form-floating mb-3">

                                <select name="lawn_type"
                                        id="lawn_type"
                                        class="form-select"
                                        required>

                                    <option value="">Select Lawn Type</option>

                                    @foreach($lawnTypes as $lawnType)

                                        <option value="{{ $lawnType->id }}"
                                            {{ old('lawn_type', $booking->lawn_type) == $lawnType->id ? 'selected' : '' }}>

                                            {{ $lawnType->lawn_type }}

                                        </option>

                                    @endforeach

                                </select>

                                <label for="lawn_type">
                                    Lawn Type <span class="text-danger">*</span>
                                </label>

                            </div>

                        </div>

                        {{-- ================================================= --}}
                        {{-- EVENT / DATE --}}
                        {{-- ================================================= --}}

                        {{-- Event Type --}}
                        <div class="col-md-6">

                            <div class="form-floating mb-3">

                                <select name="event_type"
                                        id="event_type"
                                        class="form-select"
                                        required>

                                    <option value="">Select Event Type</option>

                                    <option value="barat"
                                        {{ old('event_type', $booking->event_type) == 'barat' ? 'selected' : '' }}>
                                        Barat
                                    </option>

                                    <option value="valeema"
                                        {{ old('event_type', $booking->event_type) == 'valeema' ? 'selected' : '' }}>
                                        Valeema
                                    </option>

                                    <option value="mehendi"
                                        {{ old('event_type', $booking->event_type) == 'mehendi' ? 'selected' : '' }}>
                                        Mehendi
                                    </option>

                                    <option value="normal_dawat"
                                        {{ old('event_type', $booking->event_type) == 'normal_dawat' ? 'selected' : '' }}>
                                        Normal Dawat
                                    </option>

                                    <option value="birthday"
                                        {{ old('event_type', $booking->event_type) == 'birthday' ? 'selected' : '' }}>
                                        Birthday
                                    </option>

                                    <option value="haqeeqa"
                                        {{ old('event_type', $booking->event_type) == 'haqeeqa' ? 'selected' : '' }}>
                                        Haqeeqa
                                    </option>

                                    <option value="milad"
                                        {{ old('event_type', $booking->event_type) == 'milad' ? 'selected' : '' }}>
                                        Milad
                                    </option>

                                    <option value="roza_kushai"
                                        {{ old('event_type', $booking->event_type) == 'roza_kushai' ? 'selected' : '' }}>
                                        Roza Kushai
                                    </option>

                                    <option value="nama_e_taraweh"
                                        {{ old('event_type', $booking->event_type) == 'nama_e_taraweh' ? 'selected' : '' }}>
                                        Nama-e-Taraweh
                                    </option>

                                </select>

                                <label for="event_type">
                                    Event Type <span class="text-danger">*</span>
                                </label>

                            </div>

                            @error('event_type')

                                <div class="text-danger small mb-3">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                        {{-- Booking Date --}}
                        <div class="col-md-6">

                            <div class="form-floating mb-3">

                                <input type="date"
                                       name="booking_date"
                                       id="booking_date"
                                       class="form-control"
                                       value="{{ old('booking_date', \Carbon\Carbon::parse($booking->booking_date)->format('Y-m-d')) }}"
                                       required>

                                <label for="booking_date">
                                    Booking Date <span class="text-danger">*</span>
                                </label>

                            </div>

                        </div>

                        {{-- Booking Time --}}
                        <div class="col-md-6">

                            <div class="form-floating mb-3">

                                <select name="booking_time"
                                        id="booking_time"
                                        class="form-select"
                                        required>

                                    <option value="day"
                                        {{ old('booking_time', $booking->booking_time) == 'day' ? 'selected' : '' }}>
                                        Day
                                    </option>

                                    <option value="night"
                                        {{ old('booking_time', $booking->booking_time) == 'night' ? 'selected' : '' }}>
                                        Night
                                    </option>

                                </select>

                                <label for="booking_time">
                                    Booking Time <span class="text-danger">*</span>
                                </label>

                            </div>

                        </div>

                        {{-- Guests --}}
                        <div class="col-md-6">

                            <div class="form-floating mb-3">

                                <input type="number"
                                       name="number_of_guests"
                                       id="number_of_guests"
                                       class="form-control"
                                       min="1"
                                       placeholder="Number of Guests"
                                       value="{{ old('number_of_guests', $booking->number_of_guests ?? $booking->guests ?? '') }}"
                                       required>

                                <label for="number_of_guests">
                                    Number of Guests <span class="text-danger">*</span>
                                </label>

                            </div>

                        </div>

                        {{-- ================================================= --}}
                        {{-- AMOUNTS --}}
                        {{-- ================================================= --}}

                        {{-- Booking Amount --}}
                        <div class="col-md-6">

                            <div class="form-floating mb-3">

                                <input type="number"
                                       name="booking_amount"
                                       id="booking_amount"
                                       class="form-control"
                                       min="0"
                                       step="0.01"
                                       placeholder="Banquet Booking Amount"
                                       value="{{ old('booking_amount', $booking->booking_amount ?? 0) }}"
                                       required>

                                <label for="booking_amount">
                                    Banquet Booking Amount <span class="text-danger">*</span>
                                </label>

                            </div>

                        </div>

                        {{-- Total Amount --}}
                        <div class="col-md-6">

                            <div class="form-floating mb-3">

                                <input type="number"
                                       id="total_amount"
                                       name="total_amount"
                                       class="form-control"
                                       min="0"
                                       step="0.01"
                                       placeholder="Total Amount"
                                       value="{{ old('total_amount', $booking->total_amount ?? 0) }}"
                                       readonly>

                                <label for="total_amount">
                                    Total Amount
                                </label>

                            </div>

                        </div>

                        {{-- Tax Amount --}}
                        <div class="col-md-6">

                            <div class="form-floating mb-3">

                                <input type="number"
                                       id="tax_amount"
                                       name="tax_amount"
                                       class="form-control"
                                       min="0"
                                       step="0.01"
                                       placeholder="Tax Amount"
                                       value="{{ old('tax_amount', $booking->tax_amount ?? 0) }}"
                                       readonly>

                                <label for="tax_amount">
                                    Tax Amount
                                </label>

                            </div>

                        </div>

                        {{-- Grand Total --}}
                        <div class="col-md-6">

                            <div class="form-floating mb-3">

                                <input type="number"
                                       id="grand_total"
                                       name="grand_total"
                                       class="form-control fw-bold"
                                       min="0"
                                       step="0.01"
                                       placeholder="Grand Total"
                                       value="{{ old('grand_total', $booking->grand_total ?? 0) }}"
                                       readonly>

                                <label for="grand_total">
                                    Grand Total
                                </label>

                            </div>

                        </div>

                        {{-- Advance Amount --}}
                        <div class="col-md-6">

                            <div class="form-floating mb-3">

                                <input type="number"
                                       id="advance_amount"
                                       name="advance_amount"
                                       class="form-control"
                                       min="0"
                                       step="0.01"
                                       placeholder="Advance Amount"
                                       value="{{ old('advance_amount', $booking->advance_amount ?? 0) }}">

                                <label for="advance_amount">
                                    Advance Amount
                                </label>

                            </div>

                        </div>

                        {{-- Remaining Amount --}}
                        <div class="col-md-6">

                            <div class="form-floating mb-3">

                                <input type="number"
                                       id="remaining_amount"
                                       name="remaining_amount"
                                       class="form-control fw-bold text-danger"
                                       min="0"
                                       step="0.01"
                                       placeholder="Remaining Amount"
                                       value="{{ old('remaining_amount', $booking->remaining_amount ?? 0) }}"
                                       readonly>

                                <label for="remaining_amount">
                                    Remaining Amount
                                </label>

                            </div>

                        </div>

                        {{-- ================================================= --}}
                        {{-- PAYMENT --}}
                        {{-- ================================================= --}}

                        {{-- Payment Method --}}
                        <div class="col-md-6">

                            <div class="form-floating mb-3">

                                <select name="payment_method"
                                        id="payment_method"
                                        class="form-select">

                                    <option value="">Select Payment Method</option>

                                    <option value="cash"
                                        {{ old('payment_method', $booking->payment_method) == 'cash' ? 'selected' : '' }}>
                                        Cash
                                    </option>

                                    <option value="bank_transfer"
                                        {{ old('payment_method', $booking->payment_method) == 'bank_transfer' ? 'selected' : '' }}>
                                        Bank Transfer
                                    </option>

                                    <option value="card"
                                        {{ old('payment_method', $booking->payment_method) == 'card' ? 'selected' : '' }}>
                                        Card
                                    </option>

                                    <option value="online"
                                        {{ old('payment_method', $booking->payment_method) == 'online' ? 'selected' : '' }}>
                                        Online Payment
                                    </option>

                                    <option value="cheque"
                                        {{ old('payment_method', $booking->payment_method) == 'cheque' ? 'selected' : '' }}>
                                        Cheque
                                    </option>

                                </select>

                                <label for="payment_method">
                                    Payment Method
                                </label>

                            </div>

                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">

                            <div class="form-floating mb-3">

                                <select name="status"
                                        id="status"
                                        class="form-select">

                                    <option value="pending"
                                        {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>

                                    <option value="confirmed"
                                        {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>
                                        Confirmed
                                    </option>

                                    <option value="cancelled"
                                        {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>

                                </select>

                                <label for="status">
                                    Status
                                </label>

                            </div>

                        </div>

                        {{-- ================================================= --}}
                        {{-- NOTES --}}
                        {{-- ================================================= --}}

                        <div class="col-12">

                            <div class="form-floating mb-3">

                                <textarea name="text"
                                          id="notes"
                                          class="form-control"
                                          placeholder="Notes"
                                          style="height: 120px">{{ old('notes', $booking->notes ?? '') }}</textarea>

                                <label for="notes">
                                    Notes
                                </label>

                            </div>

                        </div>

                        {{-- ================================================= --}}
                        {{-- BUTTONS --}}
                        {{-- ================================================= --}}

                        <div class="col-12">

                            <div class="d-md-flex align-items-center mt-2">

                                <div>

                                    <button type="submit"
                                            class="btn btn-primary text-white waves-effect waves-light">

                                        <span class="btn-label">
                                            <i class="fas fa-save"></i>
                                        </span>

                                        Update Booking

                                    </button>

                                    <a href="{{ route('bookings.show', $booking->id) }}"
                                       class="btn btn-info text-white waves-effect waves-light">

                                        <span class="btn-label">
                                            <i class="fas fa-eye"></i>
                                        </span>

                                        View

                                    </a>

                                    <a href="{{ route('bookings.index') }}"
                                       class="btn btn-secondary waves-effect waves-light">

                                        <span class="btn-label">
                                            <i class="fas fa-times"></i>
                                        </span>

                                        Cancel

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</div>
@include('tenant.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> <script>
document.addEventListener('DOMContentLoaded', function () {

const bookingAmountInput =
    document.getElementById('booking_amount');

const advanceAmountInput =
    document.getElementById('advance_amount');

const totalAmountInput =
    document.getElementById('total_amount');

const taxAmountInput =
    document.getElementById('tax_amount');

const grandTotalInput =
    document.getElementById('grand_total');

const remainingAmountInput =
    document.getElementById('remaining_amount');

const taxPercentage =
    {{ $settings->tax_percentage ?? 0 }};

function calculateTotals() {

    const bookingAmount =
        parseFloat(bookingAmountInput?.value || 0);

    const advanceAmount =
        parseFloat(advanceAmountInput?.value || 0);

    const subtotal = bookingAmount;

    const taxAmount =
        (subtotal * taxPercentage) / 100;

    const grandTotal =
        subtotal + taxAmount;

    const remaining =
        Math.max(0, grandTotal - advanceAmount);

    if (totalAmountInput) {
        totalAmountInput.value =
            subtotal.toFixed(2);
    }

    if (taxAmountInput) {
        taxAmountInput.value =
            taxAmount.toFixed(2);
    }

    if (grandTotalInput) {
        grandTotalInput.value =
            grandTotal.toFixed(2);
    }

    if (remainingAmountInput) {
        remainingAmountInput.value =
            remaining.toFixed(2);
    }
}

bookingAmountInput?.addEventListener(
    'input',
    calculateTotals
);

advanceAmountInput?.addEventListener(
    'input',
    calculateTotals
);

calculateTotals();

});

</script> </body> </html>