<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Create Role</title>

    <style>

        body {
            background: #f5f6fa;
        }

        .permission-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            margin-bottom: 20px;
            overflow: hidden;
        }

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
        }

        .permission-body {
            padding: 20px;
        }

        .permission-item {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            margin-right: 25px;
            margin-bottom: 12px;

            cursor: pointer;
        }
        
        .permission-item input {
            width: 17px;
            height: 17px;
            cursor: pointer;
        }
        .select-all {
            cursor: pointer;
            font-size: 14px;
        }
    </style>

</head>

<body>

@include('admin.nav')

<div class="container py-4">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">
                Create Role
            </h2>

            <p class="text-muted mb-0">
                Role ka naam aur permissions select karein.
            </p>
        </div>

        <a href="{{ route('admin.roles.index') }}"
           class="btn btn-secondary">

            Back

        </a>

    </div>


    {{-- Validation Errors --}}

    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form action="{{ route('admin.roles.store') }}"
          method="POST">

        @csrf


        {{-- Role Name --}}

        <div class="card mb-4">

            <div class="card-body">

                <div class="mb-0">

                    <label class="form-label fw-bold">
                        Role Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="e.g. Manager"
                        value="{{ old('name') }}"
                        required
                    >

                </div>

            </div>

        </div>


        {{-- Permissions --}}

        <h4 class="mb-3">
            Page Permissions
        </h4>


        @foreach ($groupedPermissions as $module => $modulePermissions)

            @php
                $moduleId = 'module_' . Str::slug($module, '_');
            @endphp


            <div class="permission-card">


                {{-- Module Header --}}

                <div class="permission-header">

                    <h5>

                        <i class="{{ \App\Enums\PermissionEnum::moduleIcon($module) }}"></i>

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


                {{-- Permissions --}}

                <div class="permission-body">


                    @foreach ($modulePermissions as $permission)

                        <label class="permission-item">

                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permission['id'] }}"
                                class="permission-checkbox {{ $moduleId }}"
                                data-module="{{ $moduleId }}"

                                {{ in_array(
                                    $permission['id'],
                                    old('permissions', [])
                                ) ? 'checked' : '' }}
                            >

                            <span>
                                {{ $permission['label'] }}
                            </span>

                        </label>

                    @endforeach


                </div>

            </div>

        @endforeach


        {{-- Submit --}}

        <div class="text-end">

            <a href="{{ route('admin.roles.index') }}"
               class="btn btn-light">

                Cancel

            </a>

            <button
                type="submit"
                class="btn btn-primary">

                Create Role

            </button>

        </div>


    </form>

</div>


@include('tenant.footer')


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Select All
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

                    checkbox.checked = selectAllCheckbox.checked;

                });

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
                    '.select-all-checkbox[data-module="' + module + '"]'
                );

                const permissions = document.querySelectorAll(
                    '.' + module
                );

                const checkedPermissions = document.querySelectorAll(
                    '.' + module + ':checked'
                );


                /*
                | Agar saari permissions checked hain
                | to Select All bhi checked ho.
                */

                selectAll.checked =
                    permissions.length === checkedPermissions.length;


                /*
                | Agar kuch permissions checked hain
                | to Select All indeterminate ho.
                */

                selectAll.indeterminate =
                    checkedPermissions.length > 0 &&
                    checkedPermissions.length < permissions.length;

            });

        });


    /*
    |--------------------------------------------------------------------------
    | Set Initial Select All State
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

            selectAll.checked =
                permissions.length === checkedPermissions.length &&
                permissions.length > 0;

            selectAll.indeterminate =
                checkedPermissions.length > 0 &&
                checkedPermissions.length < permissions.length;

        });

});

</script>


</body>
</html>