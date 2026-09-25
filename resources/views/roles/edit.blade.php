<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Role</title>

</head>

<body>

    @include('admin.nav')

    <div class="container">

        {{-- ================================================= --}}
        {{-- HEADER --}}
        {{-- ================================================= --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2>
                Edit Role
            </h2>

            <a href="{{ route('admin.roles.index') }}"
               class="btn btn-secondary">

                Back

            </a>

        </div>


        {{-- ================================================= --}}
        {{-- ERRORS --}}
        {{-- ================================================= --}}

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


        {{-- ================================================= --}}
        {{-- SUCCESS --}}
        {{-- ================================================= --}}

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif


        {{-- ================================================= --}}
        {{-- PERMISSION CHECK --}}
        {{-- ================================================= --}}

        @can('roles.edit')


            <form action="{{ route('admin.roles.update', $role->id) }}"
                  method="POST">

                @csrf

                @method('PUT')


                <div class="card">

                    <div class="card-body">


                        {{-- ================================= --}}
                        {{-- ROLE NAME --}}
                        {{-- ================================= --}}

                        <div class="mb-4">

                            <label class="form-label">
                                Role Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="e.g. Manager"
                                value="{{ old('name', $role->name) }}"
                                required
                            >

                        </div>


                        <hr>


                        {{-- ================================= --}}
                        {{-- PERMISSIONS --}}
                        {{-- ================================= --}}

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <h5 class="mb-0">
                                Role Permissions
                            </h5>

                            <div>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-success"
                                    id="selectAll">

                                    Select All

                                </button>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-danger"
                                    id="deselectAll">

                                    Deselect All

                                </button>

                            </div>

                        </div>


                        @php

                            $groupedPermissions = $permissions->groupBy(function ($permission) {

                                return explode('.', $permission->name)[0];

                            });

                        @endphp


                        @foreach ($groupedPermissions as $module => $modulePermissions)


                            <div class="card mb-3">

                                {{-- MODULE HEADER --}}

                                <div class="card-header">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <strong>

                                            {{ ucwords(str_replace('_', ' ', $module)) }}

                                        </strong>


                                        <div>

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary select-module"
                                                data-module="{{ $module }}">

                                                Select All

                                            </button>

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary deselect-module"
                                                data-module="{{ $module }}">

                                                Clear

                                            </button>

                                        </div>

                                    </div>

                                </div>


                                {{-- MODULE PERMISSIONS --}}

                                <div class="card-body">

                                    <div class="row">


                                        @foreach ($modulePermissions as $permission)


                                            <div class="col-md-4 mb-2">

                                                <div class="form-check">

                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input permission-checkbox module-{{ $module }}"
                                                        name="permissions[]"
                                                        value="{{ $permission->id }}"

                                                        @if(
                                                            in_array(
                                                                $permission->id,
                                                                old(
                                                                    'permissions',
                                                                    $rolePermissions
                                                                )
                                                            )
                                                        )

                                                            checked

                                                        @endif
                                                    >


                                                    <label class="form-check-label">

                                                        {{ ucwords(str_replace('.', ' ', $permission->name)) }}

                                                    </label>

                                                </div>

                                            </div>


                                        @endforeach


                                    </div>

                                </div>

                            </div>


                        @endforeach


                        {{-- ================================= --}}
                        {{-- ACTIONS --}}
                        {{-- ================================= --}}

                        <div class="mt-4">

                            <button
                                type="submit"
                                class="btn btn-primary">

                                Update Role

                            </button>


                            <a href="{{ route('admin.roles.index') }}"
                               class="btn btn-secondary">

                                Cancel

                            </a>

                        </div>


                    </div>

                </div>


            </form>


        @else


            <div class="alert alert-danger">

                You do not have permission to edit roles.

            </div>


        @endcan


    </div>


    @include('tenant.footer')


    {{-- ================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ================================================= --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {


            /*
            |--------------------------------------------------------------------------
            | Select All Permissions
            |--------------------------------------------------------------------------
            */

            const selectAllButton = document.getElementById('selectAll');

            if (selectAllButton) {

                selectAllButton.addEventListener('click', function () {

                    document
                        .querySelectorAll('.permission-checkbox')
                        .forEach(function (checkbox) {

                            checkbox.checked = true;

                        });

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Deselect All Permissions
            |--------------------------------------------------------------------------
            */

            const deselectAllButton = document.getElementById('deselectAll');

            if (deselectAllButton) {

                deselectAllButton.addEventListener('click', function () {

                    document
                        .querySelectorAll('.permission-checkbox')
                        .forEach(function (checkbox) {

                            checkbox.checked = false;

                        });

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Select Module
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll('.select-module')
                .forEach(function (button) {

                    button.addEventListener('click', function () {

                        const module = this.dataset.module;

                        document
                            .querySelectorAll('.module-' + module)
                            .forEach(function (checkbox) {

                                checkbox.checked = true;

                            });

                    });

                });


            /*
            |--------------------------------------------------------------------------
            | Deselect Module
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll('.deselect-module')
                .forEach(function (button) {

                    button.addEventListener('click', function () {

                        const module = this.dataset.module;

                        document
                            .querySelectorAll('.module-' + module)
                            .forEach(function (checkbox) {

                                checkbox.checked = false;

                            });

                    });

                });


        });

    </script>

</body>

</html>