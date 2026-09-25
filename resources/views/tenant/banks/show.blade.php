<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Details</title>
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style.min.css') }}">
</head>
<body>

@include('tenant.nav')

<div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="row page-titles">
        <div class="col-md-8">
            <h4 class="text-themecolor">
                <i class="fa fa-bank me-2"></i>
                Bank Details
            </h4>
            <p class="text-muted mb-0">View bank account information</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ url('/tenant/settings') }}#bankSettings" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i>
                Back
            </a>
        </div>
    </div>

    {{-- Bank Info --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $bank->bank_name }}</h4>
            <div class="table-responsive">
                <table class="table color-table primary-table">
                    <tbody>
                        <tr>
                            <th>Bank Name</th>
                            <td><strong>{{ $bank->bank_name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Opening Balance</th>
                            <td>Rs. {{ number_format($bank->opening_balance, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Current Balance</th>
                            <td>Rs. {{ number_format($bank->current_balance, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $bank->created_at->format('d M Y h:i A') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@include('tenant.footer')

</body>
</html>