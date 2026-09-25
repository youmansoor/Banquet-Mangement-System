<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Masters</title>
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style.min.css') }}">
</head>
<body>

@include('tenant.nav')

<div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="row page-titles">
        <div class="col-md-8">
            <h4 class="text-themecolor">
                <i class="fa fa-cogs me-2"></i>
                Finance Masters
            </h4>
            <p class="text-muted mb-0">Manage payment categories, beneficiaries, and vendors</p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa fa-check-circle me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabs --}}
    <div class="card">
        <div class="card-body p-b-0">
            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#paymentCategories" role="tab">
                        <i class="fa fa-list me-1"></i>
                        Payment Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#beneficiaries" role="tab">
                        <i class="fa fa-user me-1"></i>
                        Beneficiaries
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#vendors" role="tab">
                        <i class="fa fa-building me-1"></i>
                        Vendors
                    </a>
                </li>
            </ul>
        </div>

        <div class="tab-content">
            {{-- Payment Categories --}}
            <div class="tab-pane active" id="paymentCategories" role="tabpanel">
                <div class="p-20">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title">Payment Categories</h5>
                        <a href="{{ route('tenant.finance-masters.create', 'payment_category') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-1"></i>
                            Add Category
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table color-table primary-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Transaction Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($masters['payment_category'] ?? collect() as $master)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $master->name }}</strong></td>
                                        <td>{{ $master->parent_key }}</td>
                                        <td>
                                            @if($master->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('tenant.finance-masters.edit', $master) }}" class="btn btn-warning waves-effect waves-light text-white me-1">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('tenant.finance-masters.destroy', $master) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="return confirm('Are you sure?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No payment categories found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Beneficiaries --}}
            <div class="tab-pane" id="beneficiaries" role="tabpanel">
                <div class="p-20">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title">Beneficiaries</h5>
                        <a href="{{ route('tenant.finance-masters.create', 'beneficiary') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-1"></i>
                            Add Beneficiary
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table color-table primary-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($masters['beneficiary'] ?? collect() as $master)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $master->name }}</strong></td>
                                        <td>
                                            @if($master->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('tenant.finance-masters.edit', $master) }}" class="btn btn-warning waves-effect waves-light text-white me-1">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('tenant.finance-masters.destroy', $master) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="return confirm('Are you sure?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">No beneficiaries found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Vendors --}}
            <div class="tab-pane" id="vendors" role="tabpanel">
                <div class="p-20">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title">Vendors</h5>
                        <a href="{{ route('tenant.finance-masters.create', 'vendor') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-1"></i>
                            Add Vendor
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table color-table primary-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($masters['vendor'] ?? collect() as $master)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $master->name }}</strong></td>
                                        <td>
                                            @if($master->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('tenant.finance-masters.edit', $master) }}" class="btn btn-warning waves-effect waves-light text-white me-1">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('tenant.finance-masters.destroy', $master) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="return confirm('Are you sure?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">No vendors found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@include('tenant.footer')

</body>
</html>