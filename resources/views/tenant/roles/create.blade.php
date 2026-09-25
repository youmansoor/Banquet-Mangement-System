<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Create Role - Banquet Management</title>

    <style>

        body {
            background: #f5f6fa;
        }

        /* =========================================================
           PAGE
           ========================================================= */

        .role-page {
            padding-top: 25px;
            padding-bottom: 30px;
        }


        /* =========================================================
           HEADER
           ========================================================= */

        .role-page-header {
            margin-bottom: 25px;
        }

        .role-page-header h2 {
            margin-bottom: 5px;
            font-weight: 600;
            color: #343a40;
        }

        .role-page-header p {
            margin-bottom: 0;
            color: #6c757d;
        }


        /* =========================================================
           ROLE NAME CARD
           ========================================================= */

        .role-name-card {
            border: 0;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            margin-bottom: 25px;
        }

        .role-name-card .card-body {
            padding: 22px;
        }

        .role-name-card .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #343a40;
        }

        .role-name-card .form-control {
            height: 44px;
            border-radius: 6px;
        }


        /* =========================================================
           PERMISSION TITLE
           ========================================================= */

        .permissions-title {
            margin-bottom: 18px;
            font-weight: 600;
            color: #343a40;
        }


        /* =========================================================
           PERMISSION CARD
           ========================================================= */

        .permission-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .03);
        }


        /* =========================================================
           PERMISSION HEADER
           ========================================================= */

        .permission-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #e5e7eb;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .permission-header h5 {
            margin: 0;
            font-weight: 600;
            color: #343a40;
            font-size: 16px;
        }

        .permission-header h5 i {
            color: #03a9f4;
            margin-right: 6px;
        }


        /* =========================================================
           SELECT ALL
           ========================================================= */

        .select-all {
            cursor: pointer;
            font-size: 13px;
            color: #495057;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .select-all input {
            width: 17px;
            height: 17px;
            cursor: pointer;
        }


        /* =========================================================
           PERMISSION BODY
           ========================================================= */

        .permission-body {
            padding: 20px;
        }


        /* =========================================================
           PERMISSION ITEM
           ========================================================= */

        .permission-item {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            margin-right: 25px;
            margin-bottom: 12px;

            cursor: pointer;

            color: #495057;
            font-size: 13px;
        }

        .permission-item input {
            width: 17px;
            height: 17px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .permission-item:hover {
            color: #03a9f4;
        }


        /* =========================================================
           ERROR
           ========================================================= */

        .validation-alert {
            border-radius: 7px;
            margin-bottom: 20px;
        }


        /* =========================================================
           ACTION BUTTONS
           ========================================================= */

        .role-actions {
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            margin-top: 10px;
        }

        .role-actions .btn {
            min-width: 110px;
        }


        /* =========================================================
           EMPTY PERMISSIONS
           ========================================================= */

        .no-permissions {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 30px;
            color: #6c757d;
            text-align: center;
        }


        /* =========================================================
           RESPONSIVE
           ========================================================= */

        @media (max-width: 767px) {

            .role-page {
                padding-top: 15px;
            }

            .permission-header {
                padding: 13px 15px;
            }

            .permission-body {
                padding: 15px;
            }

            .permission-item {
                display: flex;
                margin-right: 0;
            }

            .role-page-header .btn {
                margin-top: 15px;
            }

            .role-actions {
                text-align: center !important;
            }

            .role-actions .btn {
                margin-bottom: 5px;
            }

        }

    </style>

</head>


<body>

@include('tenant.nav')


<div class="page-wrapper">

    <div class="container-fluid role-page">


        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="role-page-header d-flex justify-content-between align-items-center">

            <div>

                <h2>
                    <i class="ti-lock text-primary me-2"></i>
                    Create Role
                </h2>

                <p>
                    Role ka naam aur permissions select karein.
                </p>

            </div>


            <div>

                <a
                    href="{{ route('tenant.roles.index') }}"
                    class="btn btn-secondary"
                >

                    <i class="ti-arrow-left me-1"></i>

                    Back

                </a>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- VALIDATION ERRORS --}}
        {{-- ========================================================= --}}

        @if ($errors->any())

            <div class="alert alert-danger validation-alert">

                <strong>
                    Please fix the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- FORM --}}
        {{-- ========================================================= --}}

        <form
            action="{{ route('tenant.roles.store') }}"
            method="POST"
        >

            @csrf


            {{-- ===================================================== --}}
            {{-- ROLE NAME --}}
            {{-- ===================================================== --}}

            <div class="card role-name-card">

                <div class="card-body">

                    <div class="mb-0">

                        <label
                            for="name"
                            class="form-label"
                        >

                            Role Name
                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="e.g. Manager / Sales Officer / Event Manager"
                            value="{{ old('name') }}"
                            required
                        >


                        @error('name')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- PERMISSIONS TITLE --}}
            {{-- ===================================================== --}}

            <h4 class="permissions-title">

                <i class="ti-key text-primary me-1"></i>

                Page Permissions

            </h4>


            {{-- ===================================================== --}}
            {{-- PERMISSIONS --}}
            {{-- ===================================================== --}}

            @forelse ($groupedPermissions as $module => $modulePermissions)

                @php

                    $moduleId = 'module_' . \Illuminate\Support\Str::slug(
                        $module,
                        '_'
                    );

                    $moduleIcon =
                        \App\Enums\PermissionEnum::moduleIcon($module);

                @endphp


                <div class="permission-card">


                    {{-- ================================================= --}}
                    {{-- MODULE HEADER --}}
                    {{-- ================================================= --}}

                    <div class="permission-header">

                        <h5>

                            <i class="{{ $moduleIcon }}"></i>

                            {{ $module }}

                        </h5>


                        <label class="select-all">

                            <input
                                type="checkbox"
                                class="select-all-checkbox"
                                data-module="{{ $moduleId }}"
                            >

                            <strong>
                                Select All
                            </strong>

                        </label>

                    </div>


                    {{-- ================================================= --}}
                    {{-- MODULE PERMISSIONS --}}
                    {{-- ================================================= --}}

                    <div class="permission-body">


                        @foreach ($modulePermissions as $permission)

                            @php

                                $enumCase =
                                    \App\Enums\PermissionEnum::tryFrom(
                                        $permission->name
                                    );

                                $label =
                                    $enumCase
                                    ? $enumCase->label()
                                    : $permission->name;

                            @endphp


                            <label class="permission-item">

                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->id }}"
                                    class="permission-checkbox {{ $moduleId }}"
                                    data-module="{{ $moduleId }}"

                                    {{ in_array(
                                        $permission->id,
                                        old('permissions', [])
                                    ) ? 'checked' : '' }}
                                >


                                <span>
                                    {{ $label }}
                                </span>

                            </label>

                        @endforeach


                    </div>

                </div>

            @empty


                <div class="no-permissions">

                    <i class="ti-info-alt fa-2x mb-2"></i>

                    <p class="mb-0">

                        No permissions found in the database.

                    </p>

                    <small>

                        Please run the permissions seeder.

                    </small>

                </div>


            @endforelse


            {{-- ========================================================= --}}
            {{-- ACTION BUTTONS --}}
            {{-- ========================================================= --}}

            <div class="role-actions text-end">

                <a
                    href="{{ route('tenant.roles.index') }}"
                    class="btn btn-light"
                >

                    <i class="ti-close me-1"></i>

                    Cancel

                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="ti-save me-1"></i>

                    Create Role

                </button>

            </div>


        </form>


    </div>

</div>


@include('tenant.footer')


<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | Module Select All
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.select-all-checkbox')
        .forEach(function (selectAllCheckbox) {


            selectAllCheckbox.addEventListener('change', function () {

                const module = this.dataset.module;

                const permissions = document.querySelectorAll(
                    '.' + module
                );


                permissions.forEach(function (checkbox) {

                    checkbox.checked =
                        selectAllCheckbox.checked;

                });


                /*
                | Indeterminate remove karein
                */

                selectAllCheckbox.indeterminate = false;

            });

        });


    /*
    |--------------------------------------------------------------------------
    | Individual Permission Change
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.permission-checkbox')
        .forEach(function (checkbox) {


            checkbox.addEventListener('change', function () {

                const module = this.dataset.module;


                const selectAll = document.querySelector(
                    '.select-all-checkbox[data-module="' +
                    module +
                    '"]'
                );


                const permissions = document.querySelectorAll(
                    '.' + module
                );


                const checkedPermissions = document.querySelectorAll(
                    '.' + module + ':checked'
                );


                /*
                |--------------------------------------------------------------------------
                | All permissions selected
                |--------------------------------------------------------------------------
                */

                selectAll.checked =
                    permissions.length > 0 &&
                    permissions.length ===
                    checkedPermissions.length;


                /*
                |--------------------------------------------------------------------------
                | Some permissions selected
                |--------------------------------------------------------------------------
                */

                selectAll.indeterminate =
                    checkedPermissions.length > 0 &&
                    checkedPermissions.length <
                    permissions.length;

            });

        });


    /*
    |--------------------------------------------------------------------------
    | Initial Select All State
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.select-all-checkbox')
        .forEach(function (selectAll) {


            const module = selectAll.dataset.module;


            const permissions = document.querySelectorAll(
                '.' + module
            );


            const checkedPermissions = document.querySelectorAll(
                '.' + module + ':checked'
            );


            /*
            | All checked
            */

            selectAll.checked =
                permissions.length > 0 &&
                permissions.length ===
                checkedPermissions.length;


            /*
            | Partially checked
            */

            selectAll.indeterminate =
                checkedPermissions.length > 0 &&
                checkedPermissions.length <
                permissions.length;

        });

});

</script>


</body>
</html>
