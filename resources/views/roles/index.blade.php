<!DOCTYPE html> <html lang="en"> <head>
<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Roles & Permissions</title>

<style>

    .role-table {
        min-width: 900px;
    }

    .role-table th {
        white-space: nowrap;
        font-size: 13px;
        font-weight: 600;
        vertical-align: middle;
    }

    .role-table td {
        vertical-align: middle;
        font-size: 13px;
    }

    .role-name {
        font-weight: 600;
        color: #343a40;
    }

    .permission-badge {
        display: inline-block;
        margin: 2px 3px 2px 0;
        font-size: 11px;
        font-weight: 500;
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

    .role-count {
        font-size: 13px;
        color: #6c757d;
    }

</style>

</head> <body>

@include('admin.nav')

<div class="page-wrapper">
<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="row page-titles">

        <div class="col-md-6">

            <h4 class="text-themecolor">

                <i class="fa fa-shield-alt me-2"></i>

                Roles & Permissions

            </h4>

            <p class="text-muted mb-0">
                Manage system roles and their permissions
            </p>

        </div>

        <div class="col-md-6 text-end">

            <a
                href="{{ route('admin.roles.create') }}"
                class="btn btn-info text-white"
            >

                <i class="fa fa-plus me-1"></i>

                Create Role

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
    {{-- SEARCH CARD --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm filter-card mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.roles.index') }}"
            >

                <div class="row g-3">

                    {{-- SEARCH --}}
                    <div class="col-md-10">

                        <label class="form-label">
                            Search Roles
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Search role name..."
                        >

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
                            href="{{ route('admin.roles.index') }}"
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


    {{-- ========================================================= --}}
    {{-- ROLES TABLE --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-body">

            {{-- TABLE HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>

                    <h5 class="mb-1">
                        All Roles
                    </h5>

                    <span class="role-count">
                        Manage roles and assigned permissions
                    </span>

                </div>

            </div>


            <div class="table-responsive">

                <table class="table color-table primary-table align-middle mb-0 role-table">

                    <thead>

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Role Name
                            </th>

                            <th>
                                Permissions
                            </th>

                            <th width="180">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($roles as $role)

                        <tr>

                            {{-- NUMBER --}}
                            <td>
                                {{ $loop->iteration }}
                            </td>


                            {{-- ROLE NAME --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    <div
                                        class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                        style="width:35px;height:35px;"
                                    >

                                        <i class="fa fa-user-shield"></i>

                                    </div>

                                    <div>

                                        <span class="role-name">
                                            {{ $role->name }}
                                        </span>

                                        @if($role->name === 'Super Admin')

                                            <br>

                                            <span class="badge bg-danger">
                                                System Role
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- PERMISSIONS --}}
                            <td>

                                @forelse($role->permissions as $permission)

                                    <span class="badge bg-primary permission-badge">

                                        <i class="fa fa-key me-1"></i>

                                        {{ $permission->name }}

                                    </span>

                                @empty

                                    <span class="text-muted">
                                        No permissions assigned
                                    </span>

                                @endforelse

                            </td>


                            {{-- ACTION --}}
                            <td>

                                <div class="action-buttons d-flex gap-1">

                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('admin.roles.edit', $role->id) }}"
                                        class="btn btn-warning btn-sm"
                                        title="Edit Role"
                                    >

                                        <span class="btn-label">
                                            <i class="fa fa-edit"></i>
                                        </span>

                                        Edit

                                    </a>


                                    {{-- DELETE --}}
                                    @if($role->name !== 'Super Admin')

                                        <form
                                            action="{{ route('admin.roles.destroy', $role->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this role?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm"
                                                title="Delete Role"
                                            >

                                                <span class="btn-label">
                                                    <i class="fa fa-trash"></i>
                                                </span>

                                                Delete

                                            </button>

                                        </form>

                                    @else

                                        <span
                                            class="badge bg-light text-muted border d-flex align-items-center"
                                            style="padding: 8px 10px;"
                                        >

                                            <i class="fa fa-lock me-1"></i>

                                            Protected

                                        </span>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="text-center"
                            >

                                <div class="empty-state">

                                    <i
                                        class="fa fa-user-shield fa-3x text-muted mb-3"
                                    ></i>

                                    <h5 class="text-muted">
                                        No roles found
                                    </h5>

                                    <p class="text-muted mb-3">
                                        There are no roles matching your search.
                                    </p>

                                    <a
                                        href="{{ route('admin.roles.create') }}"
                                        class="btn btn-primary"
                                    >

                                        <i class="fa fa-plus me-1"></i>

                                        Create First Role

                                    </a>

                                </div>

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

</body> </html>