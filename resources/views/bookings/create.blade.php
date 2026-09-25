<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

@include('tenant.nav')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">New Booking</h2>
            <p class="text-muted mb-0">Create a new banquet booking</p>
        </div>

        <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
            Back to Bookings
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    {{-- Customer --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Customer <span class="text-danger">*</span>
                        </label>

                        <select name="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>

                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                    - {{ $customer->phone_1 }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Lawn Type --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Lawn Type <span class="text-danger">*</span>
                        </label>

                        <select name="lawn_type_id" class="form-select" required>
                            <option value="">Select Lawn Type</option>

                            @foreach($lawnTypes as $lawnType)
                                <option value="{{ $lawnType->id }}"
                                    {{ old('lawn_type_id') == $lawnType->id ? 'selected' : '' }}>
                                    {{ $lawnType->lawn_type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Event Type --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Event Type <span class="text-danger">*</span>
                        </label>

                        <select name="event_type" class="form-select" required>
                            <option value="">Select Event Type</option>

                            <option value="Wedding"
                                {{ old('event_type') == 'Wedding' ? 'selected' : '' }}>
                                Wedding
                            </option>

                            <option value="Mehndi"
                                {{ old('event_type') == 'Mehndi' ? 'selected' : '' }}>
                                Mehndi
                            </option>

                            <option value="Barat"
                                {{ old('event_type') == 'Barat' ? 'selected' : '' }}>
                                Barat
                            </option>

                            <option value="Walima"
                                {{ old('event_type') == 'Walima' ? 'selected' : '' }}>
                                Walima
                            </option>

                            <option value="Birthday"
                                {{ old('event_type') == 'Birthday' ? 'selected' : '' }}>
                                Birthday
                            </option>

                            <option value="Corporate"
                                {{ old('event_type') == 'Corporate' ? 'selected' : '' }}>
                                Corporate
                            </option>

                            <option value="Other"
                                {{ old('event_type') == 'Other' ? 'selected' : '' }}>
                                Other
                            </option>
                        </select>
                    </div>

                    {{-- Booking Date --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Booking Date <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            name="booking_date"
                            class="form-control"
                            value="{{ old('booking_date') }}"
                            required
                        >
                    </div>

                    {{-- Booking Time --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Booking Time <span class="text-danger">*</span>
                        </label>

                        <select name="booking_time" class="form-select" required>
                            <option value="">Select Time</option>

                            <option value="day"
                                {{ old('booking_time') == 'day' ? 'selected' : '' }}>
                                Day
                            </option>

                            <option value="night"
                                {{ old('booking_time') == 'night' ? 'selected' : '' }}>
                                Night
                            </option>
                        </select>
                    </div>

                    {{-- Number of Guests --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Number of Guests <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="number_of_guests"
                            class="form-control"
                            min="1"
                            value="{{ old('number_of_guests') }}"
                            required
                        >
                    </div>

                    {{-- Total Amount --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Total Amount
                        </label>

                        <input
                            type="number"
                            name="total_amount"
                            class="form-control"
                            min="0"
                            step="0.01"
                            value="{{ old('total_amount', 0) }}"
                        >
                    </div>

                    {{-- Advance Amount --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Advance Amount
                        </label>

                        <input
                            type="number"
                            name="advance_amount"
                            class="form-control"
                            min="0"
                            step="0.01"
                            value="{{ old('advance_amount', 0) }}"
                        >
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status" class="form-select">
                            <option value="pending"
                                {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="confirmed"
                                {{ old('status') == 'confirmed' ? 'selected' : '' }}>
                                Confirmed
                            </option>

                            <option value="cancelled"
                                {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>
                    </div>

                    {{-- Notes --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Notes
                        </label>

                        <textarea
                            name="notes"
                            class="form-control"
                            rows="4"
                            placeholder="Additional booking details..."
                        >{{ old('notes') }}</textarea>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            Create Booking
                        </button>

                        <a href="{{ route('bookings.index') }}"
                           class="btn btn-secondary px-4">
                            Cancel
                        </a>
                    </div>

                </div>

            </form>

        </div>
    </div>

</div>

@include('tenant.footer')

</body>
</html>