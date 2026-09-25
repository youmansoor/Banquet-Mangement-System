<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tenants</title>


    <style>

        /* =========================================================
           TENANT TABLE
           ========================================================= */

        .tenant-table {
            min-width: 1450px;
        }

        .tenant-table th {
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
            vertical-align: middle;
        }

        .tenant-table td {
            vertical-align: middle;
            font-size: 13px;
        }


        /* =========================================================
           FILTER CARD
           ========================================================= */

        .filter-card {
            border: 0;
            border-radius: 8px;
        }

        .filter-card .form-label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #455a64;
        }


        /* =========================================================
           BUSINESS
           ========================================================= */

        .tenant-business {
            display: flex;
            align-items: center;
            min-width: 220px;
        }

        .tenant-logo {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
            border: 1px solid #eef1f5;
        }

        .tenant-logo-placeholder {
            width: 42px;
            height: 42px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            margin-right: 10px;

            background: #e8f4ff;
            color: #03a9f4;

            font-size: 17px;
        }

        .tenant-business-name {
            font-weight: 600;
            color: #343a40;
            white-space: nowrap;
        }


        /* =========================================================
           OWNER
           ========================================================= */

        .tenant-owner {
            font-weight: 600;
            color: #343a40;
            white-space: nowrap;
        }


        /* =========================================================
           CONTACT
           ========================================================= */

        .tenant-email,
        .tenant-phone {
            color: #67757c;
            white-space: nowrap;
        }


        /* =========================================================
           ID
           ========================================================= */

        .tenant-id {
            color: #6c757d;
            font-weight: 600;
            white-space: nowrap;
        }


        /* =========================================================
           STATUS BADGE
           ========================================================= */

        .tenant-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;

            padding: 5px 10px;

            border-radius: 20px;

            font-size: 11px;
            font-weight: 600;

            white-space: nowrap;
        }

        .tenant-status-active {
            background: #d1e7dd;
            color: #198754;
        }

        .tenant-status-inactive {
            background: #f8d7da;
            color: #dc3545;
        }


        /* =========================================================
           CREATED DATE
           ========================================================= */

        .tenant-date {
            color: #67757c;
            white-space: nowrap;
        }


        /* =========================================================
           STATUS TOGGLE
           ========================================================= */

        .tenant-toggle-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .tenant-toggle {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 25px;
        }

        .tenant-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .tenant-toggle-slider {
            position: absolute;
            cursor: pointer;

            top: 0;
            left: 0;
            right: 0;
            bottom: 0;

            background-color: #dc3545;

            transition: 0.25s;

            border-radius: 30px;
        }

        .tenant-toggle-slider::before {
            position: absolute;

            content: "";

            height: 19px;
            width: 19px;

            left: 3px;
            top: 3px;

            background-color: white;

            transition: 0.25s;

            border-radius: 50%;

            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
        }

        .tenant-toggle input:checked
        + .tenant-toggle-slider {
            background-color: #198754;
        }

        .tenant-toggle input:checked
        + .tenant-toggle-slider::before {
            transform: translateX(23px);
        }

        .toggle-status-text {
            font-size: 11px;
            font-weight: 600;
        }

        .toggle-status-active {
            color: #198754;
        }

        .toggle-status-inactive {
            color: #dc3545;
        }


        /* =========================================================
           ACTIONS
           ========================================================= */

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .action-buttons .btn {
            margin: 0;
        }

        .action-buttons .btn-label {
            margin-right: 2px;
        }


        /* =========================================================
           EMPTY STATE
           ========================================================= */

        .empty-state {
            padding: 50px 20px;
        }

        .empty-state i {
            font-size: 42px;
        }


        /* =========================================================
           RESPONSIVE
           ========================================================= */

        @media (max-width: 767px) {

            .tenant-table {
                min-width: 1450px;
            }

            .action-buttons .btn {
                padding: 6px 9px;
                font-size: 12px;
            }

        }

    </style>

</head>


<body>

@include('admin.nav')


<div class="page-wrapper">

    <div class="container-fluid">


        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="row page-titles">

            <div class="col-md-8">

                <h4 class="text-themecolor">

                    <i class="fa fa-building me-2"></i>

                    Tenants

                </h4>

                <p class="text-muted mb-0">

                    Manage all registered tenants

                </p>

            </div>


            <div class="col-md-4 text-end">

                <a
                    href="{{ route('admin.tenants.create') }}"
                    class="btn btn-info text-white"
                >

                    <i class="fa fa-plus me-1"></i>

                    Add New Tenant

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
        {{-- SEARCH / FILTER CARD --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm filter-card mb-4">

            <div class="card-body">

                <form
                    method="GET"
                    action="{{ route('admin.tenants.index') }}"
                >

                    <div class="row g-3">


                        {{-- SEARCH --}}

                        <div class="col-md-5">

                            <label class="form-label">

                                Search

                            </label>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Business, owner, email, phone or address..."
                            >

                        </div>


                        {{-- STATUS FILTER --}}

                        <div class="col-md-2">

                            <label class="form-label">

                                Status

                            </label>

                            <select
                                name="status"
                                class="form-control form-select"
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

                        </div>


                        {{-- CREATED DATE --}}

                        <div class="col-md-2">

                            <label class="form-label">

                                Created Date

                            </label>

                            <input
                                type="date"
                                name="created_date"
                                class="form-control"
                                value="{{ request('created_date') }}"
                            >

                        </div>


                        {{-- BUTTONS --}}

                        <div class="col-md-3 d-flex align-items-end gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary flex-fill"
                            >

                                <i class="fa fa-search me-1"></i>

                                Search

                            </button>


                            <a
                                href="{{ route('admin.tenants.index') }}"
                                class="btn btn-secondary"
                                title="Reset"
                            >

                                <i class="fa fa-refresh"></i>

                            </a>

                        </div>

                    </div>


                    <div class="mt-2">

                        <small class="text-muted">

                            <i class="fa fa-info-circle me-1"></i>

                            Search by Business Name, Owner, Email, Phone or Address.

                        </small>

                    </div>

                </form>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- TENANTS TABLE --}}
        {{-- ========================================================= --}}

        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table
                        class="table color-table primary-table align-middle mb-0 tenant-table"
                    >

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Business
                                </th>

                                <th>
                                    Owner
                                </th>

                                <th>
                                    Email
                                </th>

                                <th>
                                    Phone
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Created
                                </th>

                                <th>
                                    Toggle Status
                                </th>

                                <th style="min-width: 260px;">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @forelse($tenants as $tenant)

                            <tr>


                                {{-- ================================================= --}}
                                {{-- ID --}}
                                {{-- ================================================= --}}

                                <td>

                                    <span class="tenant-id">

                                        #{{ $tenant->id }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- BUSINESS --}}
                                {{-- ================================================= --}}

                                <td>

                                    <div class="tenant-business">

                                        @if($tenant->logo)

                                            <img
                                                src="{{ asset('storage/' . $tenant->logo) }}"
                                                class="tenant-logo"
                                                alt="{{ $tenant->business_name }}"
                                            >

                                        @else

                                            <div class="tenant-logo-placeholder">

                                                <i class="fa fa-building"></i>

                                            </div>

                                        @endif

                                    <span class="banquet_name">

                                        {{ $tenant->business_name ?? 'N/A' }}

                                    </span>

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- OWNER --}}
                                {{-- ================================================= --}}

                                <td>

                                    <span class="tenant-owner">

                                        {{ $tenant->owner_name ?? 'N/A' }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- EMAIL --}}
                                {{-- ================================================= --}}

                                <td>

                                    <span class="tenant-email">

                                        {{ $tenant->email ?? 'N/A' }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- PHONE --}}
                                {{-- ================================================= --}}

                                <td>

                                    <span class="tenant-phone">

                                        {{ $tenant->phone ?? 'N/A' }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- STATUS BADGE --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if($tenant->status)

                                        <span
                                            class="tenant-status tenant-status-active"
                                        >

                                            <i class="fa fa-check-circle"></i>

                                            Active

                                        </span>

                                    @else

                                        <span
                                            class="tenant-status tenant-status-inactive"
                                        >

                                            <i class="fa fa-times-circle"></i>

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- CREATED --}}
                                {{-- ================================================= --}}

                                <td>

                                    <span class="tenant-date">

                                        {{ $tenant->created_at
                                            ? $tenant->created_at->format('d M Y')
                                            : 'N/A'
                                        }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- TOGGLE STATUS --}}
                                {{-- ================================================= --}}

                                <td>

                                    <form
                                        action="{{ route(
                                            'admin.tenants.toggle-status',
                                            $tenant->id
                                        ) }}"
                                        method="POST"
                                        class="tenant-toggle-form"
                                    >

                                        @csrf

                                        @method('PATCH')


                                        <div class="tenant-toggle-wrapper">


                                            <label
                                                class="tenant-toggle"
                                                title="Change Tenant Status"
                                            >

                                                <input
                                                    type="checkbox"
                                                    class="tenant-status-toggle"
                                                    {{ $tenant->status ? 'checked' : '' }}
                                                    data-tenant-name="{{ $tenant->business_name }}"
                                                    onchange="this.form.submit()"
                                                >

                                                <span
                                                    class="tenant-toggle-slider"
                                                ></span>

                                            </label>


                                            <span
                                                class="toggle-status-text
                                                {{ $tenant->status
                                                    ? 'toggle-status-active'
                                                    : 'toggle-status-inactive'
                                                }}"
                                            >

                                                {{ $tenant->status
                                                    ? 'Active'
                                                    : 'Inactive'
                                                }}

                                            </span>

                                        </div>

                                    </form>

                                </td>


                                {{-- ================================================= --}}
                                {{-- ACTIONS --}}
                                {{-- ================================================= --}}

                                <td>

                                    <div class="action-buttons">


                                        {{-- VIEW --}}

                                        <a
                                            href="{{ route(
                                                'admin.tenants.show',
                                                $tenant->id
                                            ) }}"
                                            class="btn btn-primary"
                                            title="View Tenant"
                                        >

                                            <span class="btn-label">

                                                <i class="fa fa-eye"></i>

                                            </span>

                                            View

                                        </a>


                                        {{-- EDIT --}}

                                        <a
                                            href="{{ route(
                                                'admin.tenants.edit',
                                                $tenant->id
                                            ) }}"
                                            class="btn btn-warning"
                                            title="Edit Tenant"
                                        >

                                            <span class="btn-label">

                                                <i class="fa fa-edit"></i>

                                            </span>

                                            Edit

                                        </a>


                                        {{-- DELETE --}}

                                        <form
                                            action="{{ route(
                                                'admin.tenants.destroy',
                                                $tenant->id
                                            ) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm(
                                                'Are you sure you want to delete this tenant?'
                                            );"
                                        >

                                            @csrf

                                            @method('DELETE')


                                            <button
                                                type="submit"
                                                class="btn btn-danger"
                                                title="Delete Tenant"
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


                            {{-- ================================================= --}}
                            {{-- EMPTY STATE --}}
                            {{-- ================================================= --}}

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center empty-state"
                                >

                                    <i
                                        class="fa fa-building-o fa-3x text-muted mb-3"
                                    ></i>

                                    <h5 class="text-muted">

                                        No tenants found

                                    </h5>

                                    <p class="text-muted mb-0">

                                        There are no tenants matching your
                                        search or filters.

                                    </p>

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


@include('admin.footer')


</body>

</html>