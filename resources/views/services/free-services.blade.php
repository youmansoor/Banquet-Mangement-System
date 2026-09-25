<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Free Services</title>

</head>


<body>

@include('tenant.nav')


<div class="page-wrapper">

    <div class="container-fluid">


        {{-- ========================================================= --}}
        {{-- PAGE TITLE --}}
        {{-- ========================================================= --}}

        <div class="row page-titles">

            <div class="col-md-6">

                <h4 class="text-themecolor">
                    Free Services
                </h4>

            </div>


            <div class="col-md-6">

                <div class="text-end">

                    <a
                        href="{{ route('free-services.create') }}"
                        class="btn btn-success text-white waves-effect waves-light"
                    >

                        <span class="btn-label">

                            <i class="fa fa-plus"></i>

                        </span>

                        Add Free Service

                    </a>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ========================================================= --}}

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



        {{-- ========================================================= --}}
        {{-- ERROR MESSAGE --}}
        {{-- ========================================================= --}}

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



        @if($errors->any())

            <div class="alert alert-danger">

                <strong>Please fix the following errors:</strong>

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
        {{-- FREE SERVICE SUMMARY --}}
        {{-- ========================================================= --}}

        <div class="row">

            <div class="col-lg-4 col-md-6">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div>

                                <h6 class="text-muted mb-1">
                                    Total Free Services
                                </h6>

                                <h2 class="mb-0">
                                    {{ $freeServices->total() }}
                                </h2>

                            </div>


                            <div class="ms-auto">

                                <div
                                    class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
                                    style="width:55px;height:55px;"
                                >

                                    <i
                                        class="fa fa-gift"
                                        style="font-size:22px;"
                                    ></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <div class="col-lg-4 col-md-6">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div>

                                <h6 class="text-muted mb-1">
                                    Active Free Services
                                </h6>

                                <h2 class="mb-0 text-success">

                                    {{
                                        \App\Models\FreeService::where(
                                            'tenant_id',
                                            auth()->user()->tenant_id
                                        )
                                        ->where('status', true)
                                        ->count()
                                    }}

                                </h2>

                            </div>


                            <div class="ms-auto">

                                <div
                                    class="rounded-circle bg-light-success text-success d-flex align-items-center justify-content-center"
                                    style="width:55px;height:55px;"
                                >

                                    <i class="fa fa-check"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <div class="col-lg-4 col-md-6">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div>

                                <h6 class="text-muted mb-1">
                                    Service Type
                                </h6>

                                <h4 class="mb-0 text-success">
                                    FREE
                                </h4>

                            </div>


                            <div class="ms-auto">

                                <div
                                    class="rounded-circle bg-light-success text-success d-flex align-items-center justify-content-center"
                                    style="width:55px;height:55px;"
                                >

                                    <i class="fa fa-money"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- SEARCH + FILTER --}}
        {{-- ========================================================= --}}

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-body">


                        <h4 class="card-title">
                            Search Free Services
                        </h4>

                        <h6 class="card-subtitle">
                            Search and filter your free services.
                        </h6>


                        <form
                            method="GET"
                            action="{{ route('free-services.index') }}"
                        >

                            <div class="row align-items-center">


                                {{-- SEARCH --}}

                                <div class="col-md-6">

                                    <div class="form-floating mb-3">

                                        <input
                                            type="text"
                                            class="form-control"
                                            id="search"
                                            name="search"
                                            value="{{ request('search') }}"
                                            placeholder="Search"
                                        >

                                        <label for="search">
                                            Service Name / Unit
                                        </label>

                                    </div>

                                </div>



                                {{-- STATUS --}}

                                <div class="col-md-3">

                                    <div class="form-floating mb-3">

                                        <select
                                            class="form-select"
                                            id="status"
                                            name="status"
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



                                {{-- BUTTONS --}}

                                <div class="col-md-3">

                                    <div class="d-flex gap-2 mb-3">

                                        <button
                                            type="submit"
                                            class="btn btn-primary text-white waves-effect waves-light"
                                        >

                                            <i class="fa fa-search me-1"></i>

                                            Search

                                        </button>


                                        <a
                                            href="{{ route('free-services.index') }}"
                                            class="btn btn-secondary text-white waves-effect waves-light"
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

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- FREE SERVICES TABLE --}}
        {{-- ========================================================= --}}

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-body">


                        <div class="d-flex align-items-center mb-3">

                            <div>

                                <h4 class="card-title mb-1">
                                    All Free Services
                                </h4>

                                <h6 class="card-subtitle">
                                    These services are available without an additional charge.
                                </h6>

                            </div>


                            <div class="ms-auto">

                                <span class="badge bg-success">

                                    {{ $freeServices->total() }}

                                    {{ $freeServices->total() == 1 ? 'Service' : 'Services' }}

                                </span>

                            </div>

                        </div>



                        <div class="table-responsive">

                            <table
                                class="table table-hover align-middle"
                            >

                                <thead>

                                    <tr>

                                        <th>
                                            #
                                        </th>

                                        <th>
                                            Service Name
                                        </th>

                                        <th>
                                            Service Unit
                                        </th>

                                        <th>
                                            Price
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                        <th>
                                            Created
                                        </th>

                                        <th class="text-end">
                                            Action
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse(
                                        $freeServices as $freeService
                                    )

                                        <tr>

                                            {{-- NUMBER --}}

                                            <td>

                                                {{
                                                    $loop->iteration +
                                                    (
                                                        ($freeServices->currentPage() - 1)
                                                        * $freeServices->perPage()
                                                    )
                                                }}

                                            </td>



                                            {{-- NAME --}}

                                            <td>

                                                <div class="d-flex align-items-center">

                                                    <div
                                                        class="rounded-circle bg-light-success text-success d-flex align-items-center justify-content-center me-2"
                                                        style="width:40px;height:40px;"
                                                    >

                                                        <i class="fa fa-gift"></i>

                                                    </div>


                                                    <div>

                                                        <strong>
                                                            {{ $freeService->service_name }}
                                                        </strong>

                                                        <small class="d-block text-muted">
                                                            Free Service
                                                        </small>

                                                    </div>

                                                </div>

                                            </td>



                                            {{-- UNIT --}}

                                            <td>

                                                {{ $freeService->service_unit }}

                                            </td>



                                            {{-- PRICE --}}

                                            <td>

                                                <span class="fw-bold text-success">

                                                    Rs. 0.00

                                                </span>

                                                <small class="d-block text-muted">
                                                    Free
                                                </small>

                                            </td>



                                            {{-- STATUS --}}

                                            <td>

                                                @if($freeService->status)

                                                    <span class="badge bg-success">

                                                        <i class="fa fa-check me-1"></i>

                                                        Active

                                                    </span>

                                                @else

                                                    <span class="badge bg-danger">

                                                        <i class="fa fa-times me-1"></i>

                                                        Inactive

                                                    </span>

                                                @endif

                                            </td>



                                            {{-- CREATED --}}

                                            <td>

                                                <span
                                                    title="{{ $freeService->created_at?->format('d M Y h:i A') }}"
                                                >

                                                    {{ $freeService->created_at?->format('d M Y') }}

                                                </span>

                                            </td>



                                            {{-- ACTIONS --}}

                                            <td class="text-end">

                                                <div
                                                    class="btn-group"
                                                    role="group"
                                                >


                                                    {{-- VIEW --}}

                                                    <a
                                                        href="{{ route('free-services.show', $freeService) }}"
                                                        class="btn btn-info btn-sm text-white"
                                                        title="View"
                                                    >

                                                        <i class="fa fa-eye"></i>

                                                    </a>



                                                    {{-- EDIT --}}

                                                    <a
                                                        href="{{ route('free-services.edit', $freeService) }}"
                                                        class="btn btn-warning btn-sm text-white"
                                                        title="Edit"
                                                    >

                                                        <i class="fa fa-pencil"></i>

                                                    </a>



                                                    {{-- DELETE --}}

                                                    <form
                                                        method="POST"
                                                        action="{{ route('free-services.destroy', $freeService) }}"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this free service?');"
                                                    >

                                                        @csrf

                                                        @method('DELETE')


                                                        <button
                                                            type="submit"
                                                            class="btn btn-danger btn-sm"
                                                            title="Delete"
                                                        >

                                                            <i class="fa fa-trash"></i>

                                                        </button>

                                                    </form>

                                                </div>

                                            </td>

                                        </tr>


                                    @empty

                                        <tr>

                                            <td
                                                colspan="7"
                                                class="text-center py-5"
                                            >

                                                <div class="py-4">


                                                    <div
                                                        class="rounded-circle bg-light-success text-success d-inline-flex align-items-center justify-content-center"
                                                        style="width:75px;height:75px;font-size:30px;"
                                                    >

                                                        <i class="fa fa-gift"></i>

                                                    </div>


                                                    <h4 class="mt-3 mb-2">
                                                        No Free Services Found
                                                    </h4>


                                                    <p class="text-muted mb-3">

                                                        You have not added any free services yet.

                                                    </p>


                                                    <a
                                                        href="{{ route('free-services.create') }}"
                                                        class="btn btn-success text-white"
                                                    >

                                                        <i class="fa fa-plus me-1"></i>

                                                        Add Free Service

                                                    </a>

                                                </div>

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>



                        {{-- ========================================================= --}}
                        {{-- PAGINATION --}}
                        {{-- ========================================================= --}}

                        @if($freeServices->hasPages())

                            <div class="mt-3">

                                {{ $freeServices->links() }}

                            </div>

                        @endif


                    </div>

                </div>

            </div>

        </div>


    </div>

</div>


@include('tenant.footer')

</body>

</html>