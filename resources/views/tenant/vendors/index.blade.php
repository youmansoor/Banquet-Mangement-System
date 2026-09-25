<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Vendors</title>
</head>

<body>

    @include('tenant.nav')

    <div class="page-wrapper">

        <div class="container-fluid">

            {{-- ====================================================== --}}
            {{-- PAGE TITLE --}}
            {{-- ====================================================== --}}

            <div class="row page-titles">

                <div class="col-md-6 align-self-center">

                    <h4 class="text-themecolor">

                        <i class="fa fa-truck me-2"></i>

                        Vendors

                    </h4>

                </div>

                <div class="col-md-6 align-self-center text-end">

                    <a
                        href="{{ route('tenant.vendors.create') }}"
                        class="btn btn-primary"
                    >

                        <i class="fa fa-plus me-1"></i>

                        Add Vendor

                    </a>

                </div>

            </div>


            {{-- ====================================================== --}}
            {{-- SUCCESS MESSAGE --}}
            {{-- ====================================================== --}}

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    <i class="fa fa-check-circle me-1"></i>

                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

                </div>

            @endif


            {{-- ====================================================== --}}
            {{-- ERROR MESSAGE --}}
            {{-- ====================================================== --}}

            @if(session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    <i class="fa fa-exclamation-circle me-1"></i>

                    {{ session('error') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

                </div>

            @endif


            {{-- ====================================================== --}}
            {{-- VALIDATION ERRORS --}}
            {{-- ====================================================== --}}

            @if($errors->any())

                <div class="alert alert-danger">

                    <strong>Please fix the following errors:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ====================================================== --}}
            {{-- SEARCH & FILTER --}}
            {{-- ====================================================== --}}

            <div class="card">

                <div class="card-header bg-primary">

                    <h4 class="m-b-0 text-white">

                        <i class="fa fa-search me-2"></i>

                        Search & Filter Vendors

                    </h4>

                </div>


                <div class="card-body">

                    <form
                        method="GET"
                        action="{{ route('tenant.vendors.index') }}"
                    >

                        <div class="row">

                            {{-- Search --}}

                            <div class="col-md-5 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="text"
                                        name="search"
                                        id="search"
                                        class="form-control"
                                        value="{{ request('search') }}"
                                        placeholder="Search Vendor"
                                    >

                                    <label for="search">

                                        Vendor Name / Contact / Phone / NTN

                                    </label>

                                </div>

                            </div>


                            {{-- Vendor Type --}}

                            <div class="col-md-3 mb-3">

                                <div class="form-floating">

                                    <select
                                        name="vendor_type"
                                        id="vendor_type"
                                        class="form-control"
                                    >

                                        <option value="">
                                            All Vendor Types
                                        </option>

                                        <option
                                            value="food"
                                            {{ request('vendor_type') == 'food' ? 'selected' : '' }}
                                        >
                                            Food Supplier
                                        </option>

                                        <option
                                            value="beverages"
                                            {{ request('vendor_type') == 'beverages' ? 'selected' : '' }}
                                        >
                                            Beverages Supplier
                                        </option>

                                        <option
                                            value="decoration"
                                            {{ request('vendor_type') == 'decoration' ? 'selected' : '' }}
                                        >
                                            Decoration Supplier
                                        </option>

                                        <option
                                            value="furniture"
                                            {{ request('vendor_type') == 'furniture' ? 'selected' : '' }}
                                        >
                                            Furniture Supplier
                                        </option>

                                        <option
                                            value="sound"
                                            {{ request('vendor_type') == 'sound' ? 'selected' : '' }}
                                        >
                                            Sound / Lighting Supplier
                                        </option>

                                        <option
                                            value="equipment"
                                            {{ request('vendor_type') == 'equipment' ? 'selected' : '' }}
                                        >
                                            Equipment Supplier
                                        </option>

                                        <option
                                            value="stationery"
                                            {{ request('vendor_type') == 'stationery' ? 'selected' : '' }}
                                        >
                                            Stationery Supplier
                                        </option>

                                        <option
                                            value="other"
                                            {{ request('vendor_type') == 'other' ? 'selected' : '' }}
                                        >
                                            Other
                                        </option>

                                    </select>

                                    <label for="vendor_type">
                                        Vendor Type
                                    </label>

                                </div>

                            </div>


                            {{-- Status --}}

                            <div class="col-md-2 mb-3">

                                <div class="form-floating">

                                    <select
                                        name="status"
                                        id="status"
                                        class="form-control"
                                    >

                                        <option value="">
                                            All Status
                                        </option>

                                        <option
                                            value="1"
                                            {{ request('status') === '1' ? 'selected' : '' }}
                                        >
                                            Active
                                        </option>

                                        <option
                                            value="0"
                                            {{ request('status') === '0' ? 'selected' : '' }}
                                        >
                                            Inactive
                                        </option>

                                    </select>

                                    <label for="status">
                                        Status
                                    </label>

                                </div>

                            </div>


                            {{-- Buttons --}}

                            <div class="col-md-2 mb-3 d-flex align-items-center gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >

                                    <i class="fa fa-search"></i>

                                    Search

                                </button>

                                <a
                                    href="{{ route('tenant.vendors.index') }}"
                                    class="btn btn-secondary"
                                >

                                    <i class="fa fa-refresh"></i>

                                </a>

                            </div>

                        </div>

                    </form>

                </div>

            </div>


            {{-- ====================================================== --}}
            {{-- VENDOR LIST --}}
            {{-- ====================================================== --}}

            <div class="card">

                <div class="card-header bg-info">

                    <div class="d-flex justify-content-between align-items-center">

                        <h4 class="m-b-0 text-white">

                            <i class="fa fa-truck me-2"></i>

                            Vendor List

                        </h4>

                        <span class="badge bg-light text-dark">

                            Total Vendors:
                            {{ $vendors->count() }}

                        </span>

                    </div>

                </div>


                <div class="card-body">

                    @if($vendors->count() > 0)

                        <div class="table-responsive">

                            <table class="table color-table primary-table">

                                <thead>

                                    <tr>

                                        <th width="60">
                                            #
                                        </th>

                                        <th>
                                            Vendor
                                        </th>

                                        <th>
                                            Contact Person
                                        </th>

                                        <th>
                                            Contact
                                        </th>

                                        <th>
                                            Vendor Type
                                        </th>

                                        <th>
                                            City
                                        </th>

                                        <th class="text-end">
                                            Opening Balance
                                        </th>

                                        <th class="text-center">
                                            Status
                                        </th>

                                        <th width="180" class="text-center">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($vendors as $vendor)

                                        <tr>

                                            {{-- ID --}}

                                            <td>

                                                {{ $vendor->id }}

                                            </td>


                                            {{-- Vendor --}}

                                            <td>

                                                <div class="d-flex align-items-center">

                                                    @if($vendor->logo)

                                                        <img
                                                            src="{{ asset('storage/' . $vendor->logo) }}"
                                                            alt="{{ $vendor->vendor_name }}"
                                                            class="rounded-circle me-2"
                                                            width="40"
                                                            height="40"
                                                            style="object-fit: cover;"
                                                        >

                                                    @else

                                                        <div
                                                            class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                            style="width:40px;height:40px;"
                                                        >

                                                            <i class="fa fa-truck"></i>

                                                        </div>

                                                    @endif


                                                    <div>

                                                        <strong>

                                                            {{ $vendor->vendor_name }}

                                                        </strong>

                                                        @if($vendor->email)

                                                            <br>

                                                            <small class="text-muted">

                                                                {{ $vendor->email }}

                                                            </small>

                                                        @endif

                                                    </div>

                                                </div>

                                            </td>


                                            {{-- Contact Person --}}

                                            <td>

                                                {{ $vendor->contact_person ?: '—' }}

                                            </td>


                                            {{-- Contact --}}

                                            <td>

                                                @if($vendor->phone)

                                                    <i class="fa fa-phone me-1"></i>

                                                    {{ $vendor->phone }}

                                                @else

                                                    —

                                                @endif

                                            </td>


                                            {{-- Vendor Type --}}

                                            <td>

                                                @php

                                                    $vendorTypes = [

                                                        'food' => 'Food Supplier',

                                                        'beverages' => 'Beverages Supplier',

                                                        'decoration' => 'Decoration Supplier',

                                                        'furniture' => 'Furniture Supplier',

                                                        'sound' => 'Sound / Lighting',

                                                        'equipment' => 'Equipment Supplier',

                                                        'stationery' => 'Stationery Supplier',

                                                        'other' => 'Other',

                                                    ];

                                                @endphp


                                                @if($vendor->vendor_type)

                                                    <span class="badge bg-info">

                                                        {{ $vendorTypes[$vendor->vendor_type] ?? ucfirst($vendor->vendor_type) }}

                                                    </span>

                                                @else

                                                    —

                                                @endif

                                            </td>


                                            {{-- City --}}

                                            <td>

                                                {{ $vendor->city ?: '—' }}

                                            </td>


                                            {{-- Opening Balance --}}

                                            <td class="text-end">

                                                <strong>

                                                    Rs.
                                                    {{ number_format((float) $vendor->opening_balance, 2) }}

                                                </strong>

                                            </td>


                                            {{-- Status --}}

                                            <td class="text-center">

                                                @if($vendor->status)

                                                    <span class="badge bg-success">

                                                        <i class="fa fa-check-circle me-1"></i>

                                                        Active

                                                    </span>

                                                @else

                                                    <span class="badge bg-danger">

                                                        <i class="fa fa-times-circle me-1"></i>

                                                        Inactive

                                                    </span>

                                                @endif

                                            </td>


                                            {{-- Actions --}}

                                            <td class="text-center">

                                                <div class="btn-group" role="group">

                                                    {{-- View --}}

                                                    <a
                                                        href="{{ route('tenant.vendors.show', $vendor) }}"
                                                        class="btn btn-sm btn-info"
                                                        title="View Vendor"
                                                    >

                                                        <i class="fa fa-eye"></i>

                                                    </a>


                                                    {{-- Edit --}}

                                                    <a
                                                        href="{{ route('tenant.vendors.edit', $vendor) }}"
                                                        class="btn btn-sm btn-warning"
                                                        title="Edit Vendor"
                                                    >

                                                        <i class="fa fa-edit"></i>

                                                    </a>


                                                    {{-- Toggle Status --}}

                                                    <form
                                                        action="{{ route('tenant.vendors.toggle-status', $vendor) }}"
                                                        method="POST"
                                                        class="d-inline"
                                                    >

                                                        @csrf

                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm {{ $vendor->status ? 'btn-secondary' : 'btn-success' }}"
                                                            title="{{ $vendor->status ? 'Deactivate' : 'Activate' }}"
                                                            onclick="return confirm('Are you sure you want to change vendor status?')"
                                                        >

                                                            <i class="fa {{ $vendor->status ? 'fa-ban' : 'fa-check' }}"></i>

                                                        </button>

                                                    </form>


                                                    {{-- Delete --}}

                                                    <form
                                                        action="{{ route('tenant.vendors.destroy', $vendor) }}"
                                                        method="POST"
                                                        class="d-inline"
                                                    >

                                                        @csrf

                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Delete Vendor"
                                                            onclick="return confirm('Are you sure you want to delete this vendor?')"
                                                        >

                                                            <i class="fa fa-trash"></i>

                                                        </button>

                                                    </form>

                                                </div>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        {{-- ====================================================== --}}
                        {{-- EMPTY STATE --}}
                        {{-- ====================================================== --}}

                        <div class="text-center py-5">

                            <div class="mb-3">

                                <i
                                    class="fa fa-truck text-muted"
                                    style="font-size:60px;"
                                ></i>

                            </div>

                            <h4>
                                No Vendors Found
                            </h4>

                            <p class="text-muted">

                                Abhi tak koi vendor add nahi kiya gaya.

                            </p>

                            <a
                                href="{{ route('tenant.vendors.create') }}"
                                class="btn btn-primary"
                            >

                                <i class="fa fa-plus me-1"></i>

                                Add First Vendor

                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    @include('tenant.footer')


    {{-- ====================================================== --}}
    {{-- AUTO HIDE ALERTS --}}
    {{-- ====================================================== --}}

    <script>

        setTimeout(function () {

            const alerts = document.querySelectorAll('.alert');

            alerts.forEach(function (alert) {

                if (typeof bootstrap !== 'undefined') {

                    const bsAlert = new bootstrap.Alert(alert);

                    bsAlert.close();

                }

            });

        }, 5000);

    </script>

</body>

</html>