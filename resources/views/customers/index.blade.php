<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Customers</title>

    <style>

        .customer-table {
            min-width: 1200px;
        }

        .customer-table th {
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
            vertical-align: middle;
        }

        .customer-table td {
            vertical-align: middle;
            font-size: 13px;
        }

        .customer-name {
            font-weight: 600;
            color: #343a40;
        }

        .customer-phone {
            font-size: 12px;
        }

        .customer-email {
            font-size: 13px;
        }

        .nic-number {
            white-space: nowrap;
            font-weight: 500;
        }

        .customer-address {
            min-width: 180px;
            max-width: 250px;
        }

        .cnic-image {
            width: 65px;
            height: 45px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #dee2e6;
            transition: 0.2s ease;
        }

        .cnic-image:hover {
            transform: scale(1.08);
        }

        .filter-card {
            border: 0;
            border-radius: 8px;
        }

        .filter-card .form-label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .action-buttons {
            white-space: nowrap;
        }

        .action-buttons .btn {
            margin-right: 3px;
        }

        .empty-state {
            padding: 50px 20px;
        }

    </style>

</head>

<body>

@include('tenant.nav')

<div class="page-wrapper">

    <div class="container-fluid">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="row page-titles">

            <div class="col-md-6">

                <h4 class="text-themecolor">

                    <i class="fa fa-users me-2"></i>

                    Customers

                </h4>

                <p class="text-muted mb-0">
                    Manage all customers
                </p>

            </div>


            <div class="col-md-6 text-end">

                <a
                    href="{{ route('customers.create') }}"
                    class="btn btn-info text-white"
                >

                    <i class="fa fa-plus me-1"></i>

                    Add New Customer

                </a>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >

                <i class="fa fa-check-circle me-1"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ERROR MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('error'))

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >

                <i class="fa fa-exclamation-circle me-1"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- VALIDATION ERRORS --}}
        {{-- ========================================================= --}}

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Please fix the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ========================================================= --}}
{{-- CUSTOMER SEARCH / FILTER CARD --}}
{{-- ========================================================= --}}

<div class="card shadow-sm filter-card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('customers.index') }}"
        >

            <div class="row g-3">

                {{-- NAME --}}
                <div class="col-md-4">

                    <label
                        for="name"
                        class="form-label"
                    >
                        Customer Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        value="{{ request('name') }}"
                        placeholder="Search by customer name..."
                    >

                </div>


                {{-- EMAIL --}}
                <div class="col-md-4">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Email
                    </label>

                    <input
                        type="text"
                        name="email"
                        id="email"
                        class="form-control"
                        value="{{ request('email') }}"
                        placeholder="Search by email..."
                    >

                </div>


                {{-- PHONE --}}
                <div class="col-md-4">

                    <label
                        for="phone"
                        class="form-label"
                    >
                        Phone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        id="phone"
                        class="form-control"
                        value="{{ request('phone') }}"
                        placeholder="Search by phone..."
                    >

                </div>


                {{-- NIC --}}
                <div class="col-md-4">

                    <label
                        for="nic_number"
                        class="form-label"
                    >
                        NIC Number
                    </label>

                    <input
                        type="text"
                        name="nic_number"
                        id="nic_number"
                        class="form-control"
                        value="{{ request('nic_number') }}"
                        placeholder="Search by NIC number..."
                    >

                </div>


                {{-- ADDRESS --}}
                <div class="col-md-4">

                    <label
                        for="address"
                        class="form-label"
                    >
                        Address
                    </label>

                    <input
                        type="text"
                        name="address"
                        id="address"
                        class="form-control"
                        value="{{ request('address') }}"
                        placeholder="Search by address..."
                    >

                </div>


                {{-- CNIC DOCUMENT --}}
                <div class="col-md-4">

                    <label
                        for="cnic"
                        class="form-label"
                    >
                        CNIC Documents
                    </label>

                    <select
                        name="cnic"
                        id="cnic"
                        class="form-control form-select"
                    >

                        <option value="">
                            All Customers
                        </option>

                        <option
                            value="both"
                            {{ request('cnic') === 'both' ? 'selected' : '' }}
                        >
                            Both CNIC Uploaded
                        </option>

                        <option
                            value="front"
                            {{ request('cnic') === 'front' ? 'selected' : '' }}
                        >
                            CNIC Front Uploaded
                        </option>

                        <option
                            value="back"
                            {{ request('cnic') === 'back' ? 'selected' : '' }}
                        >
                            CNIC Back Uploaded
                        </option>

                        <option
                            value="missing"
                            {{ request('cnic') === 'missing' ? 'selected' : '' }}
                        >
                            CNIC Missing
                        </option>

                    </select>

                </div>


                {{-- BUTTONS --}}
                <div class="col-md-12">

                    <div class="d-flex justify-content-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="fa fa-search me-1"></i>

                            Search

                        </button>


                        <a
                            href="{{ route('customers.index') }}"
                            class="btn btn-secondary"
                        >

                            <i class="fa fa-refresh me-1"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>



        {{-- ========================================================= --}}
        {{-- CUSTOMER TABLE --}}
        {{-- ========================================================= --}}

        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h4 class="card-title mb-1">
                            Customer List
                        </h4>

                        <h6 class="card-subtitle text-muted">
                            Manage all registered customers
                        </h6>

                    </div>

                </div>


                <div class="table-responsive">

                    <table class="table color-table primary-table customer-table align-middle mb-0">

                        <thead>

                            <tr>

                                <th>#ID</th>

                                <th>Customer</th>

                                <th>Email</th>

                                <th>Phone</th>

                                <th>NIC Number</th>

                                <th>Address</th>

                                <th>CNIC Front</th>

                                <th>CNIC Back</th>

                                <th width="190">Action</th>

                            </tr>

                        </thead>


                        <tbody>

                        @forelse($customers as $customer)

                            <tr>

                                {{-- ID --}}
                                <td>

                                    <strong>
                                        {{ $customer->id }}
                                    </strong>

                                </td>


                                {{-- CUSTOMER --}}
                                <td>

                                    <div class="customer-name">

                                        {{ $customer->name }}

                                    </div>

                                    @if(!empty($customer->phone_1))

                                        <small class="text-muted customer-phone">

                                            <i class="fa fa-phone me-1"></i>

                                            {{ $customer->phone_1 }}

                                        </small>

                                    @endif

                                </td>


                                {{-- EMAIL --}}
                                <td>

                                    @if($customer->email)

                                        <span class="customer-email">

                                            {{ $customer->email }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- PHONE --}}
                                <td>

                                    @if($customer->phone_1)

                                        {{ $customer->phone_1 }}

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- NIC --}}
                                <td>

                                    @if($customer->nic_number)

                                        <span class="nic-number">

                                            {{ $customer->nic_number }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- ADDRESS --}}
                                <td class="customer-address">

                                    @if($customer->address)

                                        {{ $customer->address }}

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- CNIC FRONT --}}
                                <td>

                                    @if($customer->cnic_front)

                                        <a
                                            href="{{ asset('storage/' . $customer->cnic_front) }}"
                                            target="_blank"
                                            title="View CNIC Front"
                                        >

                                            <img
                                                src="{{ asset('storage/' . $customer->cnic_front) }}"
                                                alt="CNIC Front"
                                                class="cnic-image"
                                            >

                                        </a>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- CNIC BACK --}}
                                <td>

                                    @if($customer->cnic_back)

                                        <a
                                            href="{{ asset('storage/' . $customer->cnic_back) }}"
                                            target="_blank"
                                            title="View CNIC Back"
                                        >

                                            <img
                                                src="{{ asset('storage/' . $customer->cnic_back) }}"
                                                alt="CNIC Back"
                                                class="cnic-image"
                                            >

                                        </a>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- ACTIONS --}}
                                <td>

                                    <div class="action-buttons d-flex gap-1">

                                        {{-- VIEW --}}
                                        <a
                                            href="{{ route('customers.show', $customer->id) }}"
                                            class="btn btn-sm btn-primary text-white"
                                            title="View Customer"
                                        >

                                            <span class="btn-label">
                                                <i class="fa fa-eye"></i>
                                            </span>

                                            View

                                        </a>


                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('customers.edit', $customer->id) }}"
                                            class="btn btn-sm btn-warning text-white"
                                            title="Edit Customer"
                                        >

                                            <span class="btn-label">
                                                <i class="fa fa-edit"></i>
                                            </span>

                                            Edit

                                        </a>


                                        {{-- DELETE --}}
                                        <form
                                            action="{{ route('customers.destroy', $customer->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this customer?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete Customer"
                                            >

                                                <span class="btn-label">
                                                    <i class="fa fa-trash"></i>
                                                </span>

                                                Delete

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center empty-state"
                                >

                                    <i
                                        class="fa fa-users fa-3x text-muted mb-3"
                                    ></i>

                                    <h5 class="text-muted">

                                        No customers found

                                    </h5>

                                    <p class="text-muted mb-0">

                                        There are no customers available yet.

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- ========================================================= --}}
                {{-- PAGINATION --}}
                {{-- ========================================================= --}}

                @if($customers->hasPages())

                    <div class="mt-4">

                        {{ $customers->appends(request()->query())->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>


@include('tenant.footer')

</body>

</html>
