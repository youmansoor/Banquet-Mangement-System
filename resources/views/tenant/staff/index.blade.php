<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Staff Management</title>

    <style>

        /* =========================================================
           STAFF TABLE
           ========================================================= */

        .staff-table {
            min-width: 1000px;
        }

        .staff-table th {
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
            vertical-align: middle;
        }

        .staff-table td {
            vertical-align: middle;
            font-size: 13px;
        }


        /* =========================================================
           STAFF NAME
           ========================================================= */

        .staff-name {
            font-weight: 600;
            color: #343a40;
        }

        .staff-phone {
            font-size: 12px;
        }


        /* =========================================================
           ROLE
           ========================================================= */

        .role-badge {
            font-size: 11px;
            padding: 6px 9px;
            white-space: nowrap;
        }


        /* =========================================================
           STATUS
           ========================================================= */

        .status-badge {
            font-size: 11px;
            padding: 6px 9px;
            white-space: nowrap;
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
        }


        /* =========================================================
           ACTION BUTTONS
           ========================================================= */

        .action-buttons {
            white-space: nowrap;
        }

        .action-buttons .btn {
            margin-right: 3px;
        }


        /* =========================================================
           EMPTY STATE
           ========================================================= */

        .empty-state {
            padding: 50px 20px;
        }


        /* =========================================================
           STAFF CARD
           ========================================================= */

        .staff-card {
            border: 0;
            border-radius: 8px;
        }


        /* =========================================================
           RESPONSIVE
           ========================================================= */

        @media (max-width: 767px) {

            .staff-table {
                min-width: 900px;
            }

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

                    <i class="ti-id-badge me-2"></i>

                    Staff Management

                </h4>

                <p class="text-muted mb-0">

                    Manage banquet employees, accounts and assigned roles

                </p>

            </div>


            <div class="col-md-6 text-end">

                @can('staff.create')

                    <a
                        href="{{ route('tenant.staff.create') }}"
                        class="btn btn-info text-white"
                    >

                        <i class="fa fa-plus me-1"></i>

                        Add Staff

                    </a>

                @endcan

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
            action="{{ route('tenant.staff.index') }}"
        >

            <div class="row g-3 align-items-end">


                {{-- SEARCH --}}
                <div class="col-lg-5 col-md-6">

                    <label class="form-label">
                        Search Staff
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        value="{{ request('search') }}"
                        placeholder="Name, email, phone or role..."
                    >

                </div>


                {{-- ROLE --}}
                <div class="col-lg-3 col-md-6">

                    <label class="form-label">
                        Role
                    </label>

                    <select
                        name="role"
                        class="form-control form-select"
                    >

                        <option value="">
                            All Roles
                        </option>

                        @foreach($roles as $role)

                            <option
                                value="{{ $role->id }}"
                                {{ (string) request('role') === (string) $role->id ? 'selected' : '' }}
                            >

                                {{ ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $role->name
                                    )
                                ) }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- STATUS --}}
                <div class="col-lg-2 col-md-6">

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


                {{-- BUTTONS --}}
                <div class="col-lg-2 col-md-6 d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary flex-fill"
                    >

                        <i class="fa fa-search me-1"></i>

                        Search

                    </button>


                    <a
                        href="{{ route('tenant.staff.index') }}"
                        class="btn btn-secondary"
                        title="Reset Filters"
                    >

                        <i class="fa fa-refresh"></i>

                    </a>

                </div>


            </div>

        </form>

    </div>

</div>



        {{-- ========================================================= --}}
        {{-- STAFF TABLE --}}
        {{-- ========================================================= --}}

        <div class="card staff-card">

            <div class="card-body">


                {{-- CARD HEADER --}}
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h4 class="card-title mb-1">

                            Staff List

                        </h4>

                        <h6 class="card-subtitle">

                            Manage all banquet staff members

                        </h6>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TABLE --}}
                {{-- ================================================= --}}

                <div class="table-responsive">

                    <table
                        class="table color-table primary-table align-middle mb-0 staff-table"
                    >

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Staff
                                </th>

                                <th>
                                    Email
                                </th>

                                <th>
                                    Role
                                </th>

                                <th>
                                    Status
                                </th>

                                <th width="180">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                        @forelse($staff as $member)


                            <tr>


                                {{-- ================================================= --}}
                                {{-- NUMBER --}}
                                {{-- ================================================= --}}

                                <td>

                                    {{ $staff->firstItem() + $loop->index }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- STAFF --}}
                                {{-- ================================================= --}}

                                <td>

                                    <span class="staff-name">

                                        {{ $member->name }}

                                    </span>


                                    @if(!empty($member->phone))

                                        <br>

                                        <small class="text-muted staff-phone">

                                            <i class="fa fa-phone me-1"></i>

                                            {{ $member->phone }}

                                        </small>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- EMAIL --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if($member->email)

                                        <span>

                                            {{ $member->email }}

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            N/A

                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- ROLE --}}
                                {{-- ================================================= --}}

                                <td>

                                    @php

                                        $roleObj = $member->roles->first();

                                        $roleName = $roleObj
                                            ? $roleObj->name
                                            : ($member->role ?? 'No Role');

                                    @endphp


                                    <span
                                        class="badge bg-light-primary text-primary role-badge"
                                    >

                                        <i class="ti-lock me-1"></i>

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $roleName
                                            )
                                        ) }}

                                    </span>

                                </td>


                                {{-- ================================================= --}}
                                {{-- STATUS --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if($member->status)

                                        <span
                                            class="badge bg-success text-white status-badge"
                                        >

                                            <i class="fa fa-check-circle me-1"></i>

                                            Active

                                        </span>

                                    @else

                                        <span
                                            class="badge bg-danger text-white status-badge"
                                        >

                                            <i class="fa fa-times-circle me-1"></i>

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- ACTION --}}
                                {{-- ================================================= --}}

                                <td>

                                    <div class="action-buttons d-flex gap-1">


                                        {{-- EDIT --}}

                                        @can('staff.update')

                                            <a
                                                href="{{ route(
                                                    'tenant.staff.edit',
                                                    $member
                                                ) }}"
                                                class="btn btn-warning"
                                                title="Edit Staff"
                                            >

                                                <span class="btn-label">

                                                    <i class="fa fa-edit"></i>

                                                </span>

                                                Edit

                                            </a>

                                        @endcan


                                        {{-- DELETE --}}

                                        @if(
                                            $member->id !== auth()->id()
                                            &&
                                            $member->role !== 'tenant_owner'
                                        )

                                            @can('staff.delete')

                                                <form
                                                    action="{{ route(
                                                        'tenant.staff.destroy',
                                                        $member
                                                    ) }}"
                                                    method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this staff member?');"
                                                >

                                                    @csrf

                                                    @method('DELETE')


                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger"
                                                        title="Delete Staff"
                                                    >

                                                        <span class="btn-label">

                                                            <i class="fa fa-trash"></i>

                                                        </span>

                                                        Delete

                                                    </button>

                                                </form>

                                            @endcan

                                        @endif


                                    </div>

                                </td>


                            </tr>


                        @empty


                            {{-- ================================================= --}}
                            {{-- EMPTY STATE --}}
                            {{-- ================================================= --}}

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center empty-state"
                                >

                                    <i
                                        class="ti-id-badge fa-3x text-muted mb-3"
                                    ></i>


                                    <h5 class="text-muted">

                                        No staff members found

                                    </h5>


                                    <p class="text-muted mb-0">

                                        There are no staff members matching your search.

                                    </p>

                                </td>

                            </tr>


                        @endforelse


                        </tbody>

                    </table>

                </div>


                {{-- ================================================= --}}
                {{-- PAGINATION --}}
                {{-- ================================================= --}}

                @if($staff->hasPages())

                    <div class="mt-3">

                        {{ $staff->appends(request()->query())->links() }}

                    </div>

                @endif


            </div>

        </div>


    </div>

</div>


@include('tenant.footer')


</body>

</html>
