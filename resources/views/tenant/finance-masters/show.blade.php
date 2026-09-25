<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($master->master_type) }} Details</title>
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style.min.css') }}">
</head>
<body>

@include('tenant.nav')

<div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="row page-titles">
        <div class="col-md-8">
            <h4 class="text-themecolor">
                <i class="fa fa-eye me-2"></i>
                {{ ucfirst($master->master_type) }} Details
            </h4>
            <p class="text-muted mb-0">View {{ $master->master_type }} information</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ url('/tenant/settings') }}#masterSettings" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i>
                Back
            </a>
        </div>
    </div>

    {{-- Info --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $master->name }}</h4>
            <div class="table-responsive">
                <table class="table color-table primary-table">
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <td><strong>{{ $master->name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>{{ ucfirst($master->master_type) }}</td>
                        </tr>
                        @if($master->parent_key)
                            <tr>
                                <th>Transaction Type</th>
                                <td>{{ $master->parent_key }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($master->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $master->created_at->format('d M Y h:i A') }}</td>
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