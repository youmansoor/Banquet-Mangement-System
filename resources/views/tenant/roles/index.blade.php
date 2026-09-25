<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Roles & Permissions - Banquet Management</title>

    <link rel="stylesheet"
          href="{{ asset('assets/css/style.css') }}">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>

        .role-page-header {
            margin-bottom: 25px;
        }

        .role-page-title {
            font-size: 22px;
            font-weight: 600;
            color: #343a40;
        }

        .role-page-subtitle {
            font-size: 13px;
            color: #8898aa;
        }

        .stat-card {
            border: 0;
            border-radius: 8px;
            transition: all .2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 18px rgba(0,0,0,.08);
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .stat-number {
            font-size: 22px;
            font-weight: 700;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 12px;
            color: #8898aa;
        }

        .role-card {
            border: 0;
            border-radius: 8px;
            overflow: hidden;
        }

        .role-table thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .3px;
            font-weight: 600;
            color: #6c757d;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            white-space: nowrap;
        }

        .role-table tbody td {
            vertical-align: middle;
            padding-top: 14px;
            padding-bottom: 14px;
        }

        .role-name {
            font-weight: 600;
            color: #343a40;
        }

        .role-slug {
            font-size: 11px;
            color: #98a6ad;
        }

        .role-number {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f1f3f5;
            color: #6c757d;
            font-size: 12px;
            font-weight: 600;
        }

        .permission-list {
            max-height: 180px;
            overflow-y: auto;
            min-width: 220px;
        }

        .permission-list::-webkit-scrollbar {
            width: 5px;
        }

        .permission-list::-webkit-scrollbar-thumb {
            background: #ced4da;
            border-radius: 10px;
        }

        .permission-badge {
            display: inline-block;
            font-size: 11px;
            margin: 2px;
            padding: 5px 8px;
            border-radius: 4px;
        }

        .permission-summary {
            cursor: pointer;
            list-style: none;
        }

        .permission-summary::-webkit-details-marker {
            display: none;
        }

        .permission-summary:hover {
            text-decoration: underline;
        }

        .action-buttons .btn {
            margin-left: 3px;
        }

        .empty-state {
            padding: 60px 20px;
        }

        .empty-state-icon {
            width: 65px;
            height: 65px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f1f3f5;
            color: #adb5bd;
            font-size: 27px;
            margin-bottom: 15px;
        }

        .alert {
            border-radius: 6px;
        }

        @media(max-width: 767px) {

            .role-page-title {
                font-size: 18px;
            }

            .role-page-header .btn {
                margin-top: 15px;
                width: 100%;
            }

            .stat-card {
                margin-bottom: 15px;
            }

            .role-table {
                min-width: 850px;
            }

        }

    </style>

</head>


<body>

@include('tenant.nav')


<div class="page-wrapper">

    <div class="container-fluid">


        <!-- =========================================================
             PAGE HEADER
        ========================================================== -->

        <div class="row page-titles">

            <div class="col-md-7 align-self-center">

                <h4 class="text-themecolor mb-1">
                    <i class="ti-lock text-primary me-2"></i>
                    Roles & Permissions
                </h4>

                <small class="text-muted">
                    Manage staff roles, access levels, and module authorizations.
                </small>

            </div>


            <div class="col-md-5 align-self-center text-end">

                @can('roles.create')

                    <a href="{{ route('tenant.roles.create') }}"
                       class="btn btn-primary">

                        <i class="ti-plus me-1"></i>

                        Add Custom Role

                    </a>

                @endcan

            </div>

        </div>


        <!-- =========================================================
             SESSION SUCCESS
        ========================================================== -->

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show"
                 role="alert">

                <i class="fa fa-check-circle me-1"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                </button>

            </div>

        @endif


        <!-- =========================================================
             SESSION ERROR
        ========================================================== -->

        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show"
                 role="alert">

                <i class="fa fa-exclamation-circle me-1"></i>

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                </button>

            </div>

        @endif


        <!-- =========================================================
             STATISTICS
        ========================================================== -->

        @php

            $totalRoles = $roles->count();

            $customRoles = $roles->filter(function ($role) {
                return !is_null($role->tenant_id);
            })->count();

            $systemRoles = $roles->filter(function ($role) {
                return is_null($role->tenant_id);
            })->count();

            $fullAccessRoles = $roles->filter(function ($role) {
                return $role->permissions->count() >= count(\App\Enums\PermissionEnum::cases());
            })->count();

        @endphp


        <div class="row mb-4">


            <!-- TOTAL ROLES -->

            <div class="col-lg-3 col-md-6">

                <div class="card stat-card shadow-sm">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="stat-icon bg-light-primary text-primary me-3">

                                <i class="ti-user"></i>

                            </div>

                            <div>

                                <div class="stat-number">

                                    {{ $totalRoles }}

                                </div>

                                <div class="stat-label">

                                    Total Roles

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- SYSTEM ROLES -->

            <div class="col-lg-3 col-md-6">

                <div class="card stat-card shadow-sm">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="stat-icon bg-light-info text-info me-3">

                                <i class="ti-bookmark"></i>

                            </div>

                            <div>

                                <div class="stat-number">

                                    {{ $systemRoles }}

                                </div>

                                <div class="stat-label">

                                    System Roles

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- CUSTOM ROLES -->

            <div class="col-lg-3 col-md-6">

                <div class="card stat-card shadow-sm">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="stat-icon bg-light-success text-success me-3">

                                <i class="ti-user"></i>

                            </div>

                            <div>

                                <div class="stat-number">

                                    {{ $customRoles }}

                                </div>

                                <div class="stat-label">

                                    Custom Roles

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- FULL ACCESS -->

            <div class="col-lg-3 col-md-6">

                <div class="card stat-card shadow-sm">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div class="stat-icon bg-light-warning text-warning me-3">

                                <i class="ti-star"></i>

                            </div>

                            <div>

                                <div class="stat-number">

                                    {{ $fullAccessRoles }}

                                </div>

                                <div class="stat-label">

                                    Full Access Roles

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


        </div>


        <!-- =========================================================
             ROLES TABLE
        ========================================================== -->

        <div class="card shadow-sm role-card">

            <div class="card-header bg-white py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="mb-1">

                            <i class="ti-lock text-primary me-1"></i>

                            Available Roles

                        </h5>

                        <small class="text-muted">

                            Review and manage role permissions.

                        </small>

                    </div>

                    <span class="badge bg-light-primary text-primary">

                        {{ $totalRoles }} Roles

                    </span>

                </div>

            </div>

            {{-- Search --}}
            <div class="card-body pt-0">
                <form method="GET" action="{{ route('tenant.roles.index') }}">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Search roles..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0 role-table">

                        <thead>

                        <tr>

                            <th class="ps-3"
                                style="width: 60px;">

                                #

                            </th>

                            <th style="width: 220px;">

                                Role Name

                            </th>

                            <th style="width: 160px;">

                                Type

                            </th>

                            <th>

                                Assigned Permissions

                            </th>

                            <th class="text-end pe-3"
                                style="width: 180px;">

                                Actions

                            </th>

                        </tr>

                        </thead>


                        <tbody>


                        @forelse($roles as $role)

                            <tr>


                                <!-- NUMBER -->

                                <td class="ps-3">

                                    <span class="role-number">

                                        {{ $loop->iteration }}

                                    </span>

                                </td>


                                <!-- ROLE NAME -->

                                <td>

                                    <div class="role-name">

                                        {{ ucwords(str_replace('_', ' ', $role->name)) }}

                                    </div>

                                    <div class="role-slug">

                                        {{ $role->name }}

                                    </div>

                                </td>


                                <!-- ROLE TYPE -->

                                <td>

                                    @if(is_null($role->tenant_id))

                                        <span class="badge bg-light-primary text-primary px-2 py-1">

                                            <i class="ti-bookmark me-1"></i>

                                            System Default

                                        </span>

                                    @else

                                        <span class="badge bg-light-success text-success px-2 py-1">

                                            <i class="ti-user me-1"></i>

                                            Custom Role

                                        </span>

                                    @endif

                                </td>


                                <!-- PERMISSIONS -->

                                <td>

                                    @php

                                        $permissionCount =
                                            $role->permissions->count();

                                        $totalPermissions =
                                            count(\App\Enums\PermissionEnum::cases());

                                        $hasFullAccess =
                                            $permissionCount >= $totalPermissions;

                                    @endphp


                                    @if($hasFullAccess)

                                        <span class="badge bg-success text-white px-2 py-1">

                                            <i class="ti-star me-1"></i>

                                            All Permissions

                                        </span>

                                        <small class="text-muted ms-1">

                                            Full Access

                                        </small>

                                    @elseif($permissionCount > 0)

                                        <div class="d-flex flex-wrap align-items-center">

                                            <span class="badge bg-primary text-white me-2">

                                                {{ $permissionCount }}

                                                {{ $permissionCount == 1 ? 'Permission' : 'Permissions' }}

                                            </span>


                                            <details>

                                                <summary class="permission-summary text-primary small">

                                                    <i class="fa fa-list me-1"></i>

                                                    View List

                                                </summary>


                                                <div class="permission-list mt-2 p-2 border rounded bg-light">

                                                    @foreach($role->permissions as $perm)

                                                        @php

                                                            $enumCase =
                                                                \App\Enums\PermissionEnum::tryFrom($perm->name);

                                                            $label =
                                                                $enumCase
                                                                    ? $enumCase->label()
                                                                    : $perm->name;

                                                        @endphp


                                                        <span class="badge bg-info text-dark permission-badge">

                                                            {{ $label }}

                                                        </span>

                                                    @endforeach

                                                </div>

                                            </details>

                                        </div>

                                    @else

                                        <span class="badge bg-light-danger text-danger">

                                            <i class="ti-na me-1"></i>

                                            No Permissions

                                        </span>

                                    @endif

                                </td>


                                <!-- ACTIONS -->

                                <td class="text-end pe-3">

                                    <div class="action-buttons">


                                        <!-- EDIT -->

                                        @can('roles.edit')

                                            <a href="{{ route('tenant.roles.edit', $role) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               title="Edit Role">

                                                <i class="ti-pencil"></i>

                                                <span class="d-none d-md-inline">

                                                    Edit

                                                </span>

                                            </a>

                                        @endcan


                                        <!-- DELETE -->

                                        @if(!is_null($role->tenant_id)
                                            && $role->name !== 'tenant_owner')

                                            @can('roles.delete')

                                                <form action="{{ route('tenant.roles.destroy', $role) }}"
                                                      method="POST"
                                                      class="d-inline delete-role-form">

                                                    @csrf

                                                    @method('DELETE')


                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Delete Role">

                                                        <i class="ti-trash"></i>

                                                        <span class="d-none d-md-inline">

                                                            Delete

                                                        </span>

                                                    </button>

                                                </form>

                                            @endcan

                                        @endif


                                        <!-- PROTECTED ROLE -->

                                        @if($role->name === 'tenant_owner')

                                            <span class="badge bg-light-secondary text-secondary ms-1">

                                                <i class="ti-lock me-1"></i>

                                                Protected

                                            </span>

                                        @endif


                                    </div>

                                </td>


                            </tr>

                        @empty


                            <!-- EMPTY STATE -->

                            <tr>

                                <td colspan="5">

                                    <div class="text-center empty-state">

                                        <div class="empty-state-icon">

                                            <i class="ti-lock"></i>

                                        </div>

                                        <h5 class="text-muted">

                                            No Roles Found

                                        </h5>

                                        <p class="text-muted mb-3">

                                            There are currently no roles available.

                                        </p>


                                        @can('roles.create')

                                            <a href="{{ route('tenant.roles.create') }}"
                                               class="btn btn-primary">

                                                <i class="ti-plus me-1"></i>

                                                Create Custom Role

                                            </a>

                                        @endcan

                                    </div>

                                </td>

                            </tr>


                        @endforelse


                        </tbody>

                    </table>

                </div>

            </div>


            @if($roles->count() > 0)

                <div class="card-footer bg-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <small class="text-muted">

                            Showing

                            <strong>
                                {{ $roles->count() }}
                            </strong>

                            role(s)

                        </small>


                        <small class="text-muted">

                            <i class="ti-info-alt me-1"></i>

                            System default roles cannot be deleted.

                        </small>

                    </div>

                </div>

            @endif


        </div>


    </div>

</div>


@include('tenant.footer')


<!-- =========================================================
     DELETE CONFIRMATION
========================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {


    document
        .querySelectorAll('.delete-role-form')
        .forEach(function (form) {


            form.addEventListener('submit', function (e) {

                e.preventDefault();


                const confirmed =
                    confirm(
                        'Are you sure you want to delete this role?\n\nThis action cannot be undone.'
                    );


                if (confirmed) {

                    const button =
                        form.querySelector('button[type="submit"]');


                    if (button) {

                        button.disabled = true;


                        button.innerHTML = `

                            <i class="fa fa-spinner fa-spin"></i>

                            Deleting...

                        `;

                    }


                    form.submit();

                }

            });

        });


    /*
    |--------------------------------------------------------------------------
    | AUTO HIDE ALERTS
    |--------------------------------------------------------------------------
    */

    setTimeout(function () {

        document
            .querySelectorAll('.alert.alert-dismissible')
            .forEach(function (alert) {

                if (
                    typeof bootstrap !== 'undefined'
                ) {

                    const alertInstance =
                        bootstrap.Alert.getOrCreateInstance(alert);

                    alertInstance.close();

                }

            });

    }, 5000);


});

</script>


</body>

</html>
