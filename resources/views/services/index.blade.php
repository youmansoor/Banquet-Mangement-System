<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Services</title>
</head>

<body>

@include('tenant.nav')

<div class="page-wrapper">

    <div class="container-fluid">

        {{-- Page Title --}}
        <div class="row page-titles">

            <div class="col-md-6">
                <h4 class="text-themecolor">
                    Services
                </h4>
            </div>

            <div class="col-md-6 text-end">

                <a href="{{ route('services.create') }}"
                   class="btn btn-primary text-white">

                    <i class="fa fa-plus"></i>
                    Add Service

                </a>

            </div>

        </div>


        {{-- Success Message --}}
        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        {{-- ========================================================= --}}
{{-- SEARCH / FILTER CARD --}}
{{-- ========================================================= --}}

<div class="card shadow-sm filter-card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('services.index') }}"
        >

            <div class="row g-3">

                {{-- SERVICE NAME --}}
                <div class="col-md-3">

                    <label
                        for="service_name"
                        class="form-label"
                    >
                        Service Name
                    </label>

                    <input
                        type="text"
                        name="service_name"
                        id="service_name"
                        class="form-control"
                        value="{{ request('service_name') }}"
                        placeholder="Search service name..."
                    >

                </div>

                {{-- SERVICE UNIT --}}
                <div class="col-md-3">

                    <label
                        for="service_unit"
                        class="form-label"
                    >
                        Service Unit
                    </label>

                    <input
                        type="text"
                        name="service_unit"
                        id="service_unit"
                        class="form-control"
                        value="{{ request('service_unit') }}"
                        placeholder="Search service unit..."
                    >

                </div>

                {{-- MINIMUM AMOUNT --}}
                <div class="col-md-2">

                    <label
                        for="min_amount"
                        class="form-label"
                    >
                        Minimum Amount
                    </label>

                    <input
                        type="number"
                        name="min_amount"
                        id="min_amount"
                        class="form-control"
                        value="{{ request('min_amount') }}"
                        placeholder="e.g. 5000"
                        min="0"
                        step="0.01"
                    >

                </div>


                {{-- MAXIMUM AMOUNT --}}
                <div class="col-md-2">

                    <label
                        for="max_amount"
                        class="form-label"
                    >
                        Maximum Amount
                    </label>

                    <input
                        type="number"
                        name="max_amount"
                        id="max_amount"
                        class="form-control"
                        value="{{ request('max_amount') }}"
                        placeholder="e.g. 50000"
                        min="0"
                        step="0.01"
                    >

                </div>

                {{-- VENDOR FILTER --}}
                <div class="col-md-2">

                    <label
                        for="vendor_id"
                        class="form-label"
                    >
                        Vendor
                    </label>

                    <select
                        name="vendor_id"
                        id="vendor_id"
                        class="form-control"
                    >

                        <option value="">
                            All Vendors
                        </option>

                        @foreach($vendors as $vendor)

                            <option
                                value="{{ $vendor->id }}"
                                {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}
                            >
                                {{ $vendor->vendor_name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- BUTTONS --}}
                <div class="col-md-2 d-flex align-items-end gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary flex-fill"
                    >

                        <i class="fa fa-search me-1"></i>

                        Search

                    </button>


                    <a
                        href="{{ route('services.index') }}"
                        class="btn btn-secondary"
                        title="Reset"
                    >

                        <i class="fa fa-refresh"></i>

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>



        {{-- Services Table --}}

<div class="card">
<div class="card-body">

    <h4 class="card-title">
        All Services
    </h4>

    <h6 class="card-subtitle">
        All available services
    </h6>

    <div class="table-responsive">

        <table class="table color-table primary-table">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Service Name</th>
                    <th>Service Unit</th>
                    <th>Amount</th>
                    <th>Vendor</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @forelse($services as $service)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            <strong>
                                {{ $service->service_name }}
                            </strong>
                        </td>

                        <td>
                            <strong>
                                {{ $service->service_unit }}
                            </strong>
                        </td>

                        <td>
                            Rs.
                            {{ number_format($service->amount, 2) }}
                        </td>

                        <td>
                            @if($service->vendor)

                                <a href="{{ route('tenant.vendors.show', $service->vendor) }}" class="text-decoration-none">

                                    {{ $service->vendor->vendor_name }}

                                </a>

                            @else

                                <span class="text-muted">
                                    No vendor
                                </span>

                            @endif
                        </td>

                        <td>
                            @if($service->payment_status === 'paid')

                                <span class="badge bg-success">
                                    Paid
                                </span>

                            @else

                                <span class="badge bg-warning">
                                    Unpaid
                                </span>

                            @endif
                        </td>

                        <td>
<a
    href="{{ route('services.show', $service) }}"
    class="btn btn-primary waves-effect waves-light me-1"
    title="View">

    <span class="btn-label">
        <i class="fa fa-eye"></i>
    </span>

    View
</a>

<a
    href="{{ route('services.edit', $service) }}"
    class="btn btn-warning waves-effect waves-light text-white me-1"
    title="Edit">

    <span class="btn-label">
        <i class="fa fa-edit"></i>
    </span>

    Edit
</a>

<form
    action="{{ route('services.destroy', $service) }}"
    method="POST"
    class="d-inline">

    @csrf
    @method('DELETE')

    <button
        type="submit"
        class="btn btn-danger waves-effect waves-light"
        title="Delete"
        onclick="return confirm('Are you sure you want to delete this service?')">

        <span class="btn-label">
            <i class="fa fa-trash"></i>
        </span>

        Delete

    </button>

</form>

</td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="text-center py-4">

                            No services found.

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

@include('tenant.footer')

</body>

</html>