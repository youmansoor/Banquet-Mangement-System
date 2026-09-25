<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Expense #{{ $expenseModel->id }}</title>
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style.min.css') }}">
</head>
<body>

@include('tenant.nav')

<div class="page-wrapper">
    <div class="container-fluid mt-4">

        {{-- Header --}}
        <div class="row page-titles">
            <div class="col-md-8">
                <h4 class="text-themecolor">
                    <i class="fa fa-edit me-2"></i>
                    Edit Expense
                </h4>
                <p class="text-muted mb-0">
                    Update expense record #{{ $expenseModel->id }}
                </p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('expenses.show', $expenseModel->id) }}" class="btn btn-light border me-2">
                    <i class="fa fa-arrow-left me-1"></i> View
                </a>
                <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                    <i class="fa fa-list me-1"></i> All Expenses
                </a>
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
                <h4 class="card-title">Expense Details</h4>
                <h6 class="card-subtitle mb-4">Update the expense information below</h6>

                <form method="POST" action="{{ route('expenses.update', $expenseModel->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        {{-- Payment Category --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Payment Category / Reason
                                <span class="text-danger">*</span>
                            </label>
                            <select name="payment_category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach($masters as $master)
                                    <option
                                        value="{{ $master->id }}"
                                        {{ old('payment_category_id', $expenseModel->payment_category_id) == $master->id ? 'selected' : '' }}
                                    >
                                        {{ $master->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Vendor --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vendor</label>
                            <select name="vendor_id" class="form-control">
                                <option value="">Select Vendor (Optional)</option>
                                @foreach($vendors as $vendor)
                                    <option
                                        value="{{ $vendor->id }}"
                                        {{ old('vendor_id', $expenseModel->vendor_id) == $vendor->id ? 'selected' : '' }}
                                    >
                                        {{ $vendor->vendor_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Beneficiary --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Beneficiary</label>
                            <select name="beneficiary_id" class="form-control">
                                <option value="">Select Beneficiary (Optional)</option>
                                @foreach($beneficiaries as $beneficiary)
                                    <option
                                        value="{{ $beneficiary->id }}"
                                        {{ old('beneficiary_id', $expenseModel->beneficiary_id) == $beneficiary->id ? 'selected' : '' }}
                                    >
                                        {{ $beneficiary->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Bank --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bank Account</label>
                            <select name="tenant_bank_id" class="form-control">
                                <option value="">Select Bank (Optional)</option>
                                @foreach($banks as $bank)
                                    <option
                                        value="{{ $bank->id }}"
                                        {{ old('tenant_bank_id', $expenseModel->tenant_bank_id) == $bank->id ? 'selected' : '' }}
                                    >
                                        {{ $bank->bank_name }} - {{ $bank->account_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Amount --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Amount (Rs.)
                                <span class="text-danger">*</span>
                            </label>
                            <input
                                type="number"
                                name="amount"
                                class="form-control"
                                step="0.01"
                                min="0.01"
                                required
                                value="{{ old('amount', $expenseModel->amount) }}"
                            >
                        </div>

                        {{-- Transaction Date --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Transaction Date
                                <span class="text-danger">*</span>
                            </label>
                            <input
                                type="date"
                                name="transaction_date"
                                class="form-control"
                                required
                                value="{{ old('transaction_date', $expenseModel->transaction_date ? \Carbon\Carbon::parse($expenseModel->transaction_date)->format('Y-m-d') : '') }}"
                            >
                        </div>

                        {{-- Vendor Invoice --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Vendor Invoice Number</label>
                            <input
                                type="text"
                                name="vendor_invoice"
                                class="form-control"
                                value="{{ old('vendor_invoice', $expenseModel->vendor_invoice) }}"
                            >
                        </div>

                        {{-- Cheque Number --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Cheque Number</label>
                            <input
                                type="text"
                                name="cheque_number"
                                class="form-control"
                                value="{{ old('cheque_number', $expenseModel->cheque_number) }}"
                            >
                        </div>

                        {{-- Cheque Date --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Cheque Date</label>
                            <input
                                type="date"
                                name="cheque_date"
                                class="form-control"
                                value="{{ old('cheque_date', $expenseModel->cheque_date ? \Carbon\Carbon::parse($expenseModel->cheque_date)->format('Y-m-d') : '') }}"
                            >
                        </div>

                        {{-- Description --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Item Description</label>
                            <textarea name="item_description" class="form-control" rows="3">{{ old('item_description', $expenseModel->item_description) }}</textarea>
                        </div>

                        {{-- Remarks --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $expenseModel->remarks) }}</textarea>
                        </div>

                        {{-- Submit --}}
                        <div class="col-12">
                            <a href="{{ route('expenses.index') }}" class="btn btn-light border me-2">
                                <i class="fa fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-1"></i> Update Expense
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@include('tenant.footer')

</body>
</html>
