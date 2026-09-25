<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Vendor Payment</title>
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
                Create Vendor Payment
            </h4>
            <p class="text-muted mb-0">
                Record a payment to your vendor.
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
            <h4 class="card-title">Vendor Payment Details</h4>
            <h6 class="card-subtitle">Fill in the payment information below</h6>

            <form method="POST" action="{{ route('vendor.payments.store') }}">
                @csrf

                <div class="row">

                    {{-- Vendor --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Vendor
                            <span class="text-danger">*</span>
                        </label>
                        <select name="vendor_id" class="form-control" required>
                            <option value="">Select Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                            @endforeach
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

                    {{-- Beneficiary --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Beneficiary</label>
                        <select name="beneficiary_id" class="form-control">
                            <option value="">Select Beneficiary (Optional)</option>
                            @foreach($beneficiaries as $beneficiary)
                                <option value="{{ $beneficiary->id }}">{{ $beneficiary->name }}</option>
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

                    {{-- Vendor Invoice --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Vendor Invoice Number</label>
                        <input type="text" name="vendor_invoice" class="form-control">
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

                    {{-- Description --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Item Description</label>
                        <textarea name="item_description" class="form-control" rows="3"></textarea>
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
                            Save Vendor Payment
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</div>

@include('tenant.footer')

</body>
</html>