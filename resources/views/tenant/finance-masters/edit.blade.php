<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ ucfirst($master->master_type) }}</title>
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style.min.css') }}">
</head>
<body>

@include('tenant.nav')

<div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="row page-titles">
        <div class="col-md-8">
            <h4 class="text-themecolor">
                <i class="fa fa-edit me-2"></i>
                Edit {{ ucfirst($master->master_type) }}
            </h4>
            <p class="text-muted mb-0">Update {{ $master->master_type }} information</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ url('/tenant/settings') }}#masterSettings" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i>
                Back
            </a>
        </div>
    </div>

    {{-- Validation Errors --}}
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
            <h4 class="card-title">Edit {{ ucfirst($master->master_type) }} Details</h4>
            <form method="POST" action="{{ route('tenant.finance-masters.update', $master) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $master->name) }}" placeholder="Enter name" required>
                    </div>

                    @if($master->master_type === 'payment_category')
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Transaction Type <span class="text-danger">*</span></label>
                            <select name="parent_key" class="form-control" required>
                                <option value="">Select Transaction Type</option>
                                <option value="customer_payment" {{ old('parent_key', $master->parent_key) == 'customer_payment' ? 'selected' : '' }}>Customer Payment</option>
                                <option value="vendor_payment" {{ old('parent_key', $master->parent_key) == 'vendor_payment' ? 'selected' : '' }}>Vendor Payment</option>
                                <option value="banquet_expense" {{ old('parent_key', $master->parent_key) == 'banquet_expense' ? 'selected' : '' }}>Banquet Expense</option>
                            </select>
                        </div>
                    @endif

                    <div class="col-12 text-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i>
                            Update {{ ucfirst($master->master_type) }}
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