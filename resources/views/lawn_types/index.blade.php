<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Lawn Types</title>

    <style>

        .lawn-table {
            min-width: 800px;
        }

        .lawn-table th {
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
            vertical-align: middle;
        }

        .lawn-table td {
            vertical-align: middle;
            font-size: 13px;
        }

        .lawn-name {
            font-weight: 600;
            color: #343a40;
        }

        .lawn-id {
            font-weight: 600;
            color: #6c757d;
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

                    <i class="fa fa-building me-2"></i>

                    Lawn Types

                </h4>

                <p class="text-muted mb-0">
                    Manage all available lawn types
                </p>

            </div>


            <div class="col-md-6 text-end">

                <a
                    href="{{ route('lawn_types.create') }}"
                    class="btn btn-info text-white waves-effect waves-light"
                >

                    <i class="fa fa-plus me-1"></i>

                    Add New Lawn Type

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
                    action="{{ route('lawn_types.index') }}"
                >

                    <div class="row g-3">

                        {{-- SEARCH --}}
                        <div class="col-md-9">

                            <label
                                for="lawn_type_search"
                                class="form-label"
                            >
                                Search Lawn Type
                            </label>

                            <input
                                type="text"
                                name="search"
                                id="lawn_type_search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Search by lawn type name..."
                            >

                        </div>


                        {{-- BUTTONS --}}
                        <div class="col-md-3 d-flex align-items-end gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary flex-fill waves-effect waves-light"
                            >

                                <i class="fa fa-search me-1"></i>

                                Search

                            </button>


                            <a
                                href="{{ route('lawn_types.index') }}"
                                class="btn btn-secondary waves-effect waves-light"
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
        {{-- LAWN TYPES TABLE --}}
        {{-- ========================================================= --}}

        <div class="card">

            <div class="card-body">

                {{-- TABLE HEADER --}}
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h4 class="card-title mb-1">
                            Lawn Types List
                        </h4>

                        <h6 class="card-subtitle text-muted">
                            All registered lawn types
                        </h6>

                    </div>

                </div>


                {{-- TABLE --}}
                <div class="table-responsive">

                    <table
                        class="table color-table primary-table lawn-table align-middle mb-0"
                    >

                        <thead>

                            <tr>

                                <th>#ID</th>

                                <th>Lawn Type</th>

                                <th>Created At</th>

                                <th width="190">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @forelse($lawnTypes as $lawnType)

                            <tr>

                                {{-- ID --}}
                                <td>

                                    <span class="lawn-id">

                                        #{{ $lawnType->id }}

                                    </span>

                                </td>


                                {{-- LAWN TYPE --}}
                                <td>

                                    <div class="lawn-name">

                                        <i class="fa fa-building text-primary me-1"></i>

                                        {{ $lawnType->lawn_type }}

                                    </div>

                                </td>


                                {{-- CREATED AT --}}
                                <td>

                                    @if($lawnType->created_at)

                                        <span>

                                            {{ $lawnType->created_at->format('d M Y') }}

                                        </span>

                                        <br>

                                        <small class="text-muted">

                                            {{ $lawnType->created_at->format('h:i A') }}

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- ACTIONS --}}
                                <td>

                                    <div class="action-buttons d-flex gap-1">

                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route('lawn_types.edit', $lawnType->id) }}"
                                            class="btn btn-sm btn-warning text-white waves-effect waves-light"
                                            title="Edit Lawn Type"
                                        >

                                            <span class="btn-label">

                                                <i class="fa fa-edit"></i>

                                            </span>

                                            Edit

                                        </a>


                                        {{-- DELETE --}}
                                        <form
                                            action="{{ route('lawn_types.destroy', $lawnType->id) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this lawn type?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger waves-effect waves-light"
                                                title="Delete Lawn Type"
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
                                    colspan="4"
                                    class="text-center empty-state"
                                >

                                    <i
                                        class="fa fa-building fa-3x text-muted mb-3"
                                    ></i>

                                    <h5 class="text-muted">
                                        No lawn types found
                                    </h5>

                                    <p class="text-muted mb-3">
                                        There are no lawn types matching your search.
                                    </p>

                                    @if(request('search'))

                                        <a
                                            href="{{ route('lawn_types.index') }}"
                                            class="btn btn-secondary btn-sm"
                                        >

                                            <i class="fa fa-refresh me-1"></i>

                                            Clear Search

                                        </a>

                                    @endif

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
