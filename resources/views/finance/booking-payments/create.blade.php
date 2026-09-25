<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Booking Payment</title>
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style.min.css') }}">
</head>
<body>

@include('tenant.nav')

<div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="row page-titles">
        <div class="col-md-8">
            <h4 class="text-themecolor">
                <i class="fa fa-money me-2"></i>
                Create Booking Payment
            </h4>
            <p class="text-muted mb-0">
                Record a payment from customer for their booking.
            </p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa fa-check-circle me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Booking Payment Details</h4>
            <h6 class="card-subtitle">Fill in the payment information below</h6>

            <form method="POST" action="{{ route('booking.payments.store') }}">
                @csrf

                <div class="row">

                    {{-- Customer --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Customer
                            <span class="text-danger">*</span>
                        </label>
                        <select name="customer_id" id="customer_id" class="form-control" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Booking --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Booking
                            <span class="text-danger">*</span>
                        </label>
                        <select name="booking_id" id="booking_id" class="form-control" required disabled>
                            <option value="">Select Customer First</option>
                        </select>
                    </div>

                    {{-- Invoice --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Invoice / Bill
                            <span class="text-danger">*</span>
                        </label>
                        <select name="invoice_id" id="invoice_id" class="form-control" required disabled>
                            <option value="">Select Booking First</option>
                        </select>
                    </div>

                    {{-- Payment Category --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Payment Category / Reason
                            <span class="text-danger">*</span>
                        </label>
                        <select name="payment_category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($masters as $master)
                                <option value="{{ $master->id }}">{{ $master->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Bank --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bank Account</label>
                        <select name="tenant_bank_id" class="form-control">
                            <option value="">Select Bank (Optional)</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->bank_name }} - {{ $bank->account_number }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Amount --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Amount (Rs.)
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required>
                    </div>

                    {{-- Transaction Date --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Transaction Date
                            <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="transaction_date" class="form-control" required>
                    </div>

                    {{-- Cheque Number --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Cheque Number</label>
                        <input type="text" name="cheque_number" class="form-control">
                    </div>

                    {{-- Cheque Date --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Cheque Date</label>
                        <input type="date" name="cheque_date" class="form-control">
                    </div>

                    {{-- Remarks --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3"></textarea>
                    </div>

                    {{-- Submit --}}
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i>
                            Save Booking Payment
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</div>

@include('tenant.footer')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerSelect = document.getElementById('customer_id');
    const bookingSelect = document.getElementById('booking_id');
    const invoiceSelect = document.getElementById('invoice_id');

    customerSelect.addEventListener('change', function() {
        const customerId = this.value;

        if (customerId) {
            bookingSelect.disabled = false;
            bookingSelect.innerHTML = '<option value="">Loading...</option>';

            fetch(`/tenant/finance/customer/${customerId}/jobs`)
                .then(response => response.json())
                .then(data => {
                    bookingSelect.innerHTML = '<option value="">Select Booking</option>';
                    if (data.success && data.jobs) {
                        data.jobs.forEach(booking => {
                            const option = document.createElement('option');
                            option.value = booking.id;
                            option.textContent = booking.job_number + ' - ' + booking.booking_date;
                            bookingSelect.appendChild(option);
                        });
                    } else {
                        bookingSelect.innerHTML = '<option value="">No bookings found</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    bookingSelect.innerHTML = '<option value="">Error loading bookings</option>';
                });
        } else {
            bookingSelect.disabled = true;
            bookingSelect.innerHTML = '<option value="">Select Customer First</option>';
            invoiceSelect.disabled = true;
            invoiceSelect.innerHTML = '<option value="">Select Booking First</option>';
        }
    });

    bookingSelect.addEventListener('change', function() {
        const bookingId = this.value;

        if (bookingId) {
            invoiceSelect.disabled = false;
            invoiceSelect.innerHTML = '<option value="">Loading...</option>';

            fetch(`/tenant/finance/booking/${bookingId}/bills`)
                .then(response => response.json())
                .then(data => {
                    invoiceSelect.innerHTML = '<option value="">Select Invoice</option>';
                    if (data.success && data.bills) {
                        data.bills.forEach(invoice => {
                            const option = document.createElement('option');
                            option.value = invoice.id;
                            option.textContent = invoice.invoice_number + ' - Rs. ' + invoice.grand_total;
                            invoiceSelect.appendChild(option);
                        });
                    } else {
                        invoiceSelect.innerHTML = '<option value="">No invoices found</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    invoiceSelect.innerHTML = '<option value="">Error loading invoices</option>';
                });
        } else {
            invoiceSelect.disabled = true;
            invoiceSelect.innerHTML = '<option value="">Select Booking First</option>';
        }
    });
});
</script>

</body>
</html>