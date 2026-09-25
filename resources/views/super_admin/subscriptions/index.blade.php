<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Subscriptions</title>


    {{-- Existing CSS --}}
    <link
        rel="stylesheet"
        href="{{ asset('assets/node_modules/morrisjs/morris.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/node_modules/toast-master/css/jquery.toast.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/dist/css/style.min.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/dist/css/pages/dashboard1.css') }}"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    >


    <style>

        /* =========================================================
           SUBSCRIPTION PAGE
        ========================================================= */

        .subscription-page {
            padding: 20px;
        }


        /* =========================================================
           PAGE HEADER
        ========================================================= */

        .subscription-page-header {
            margin-bottom: 20px;
        }

        .subscription-page-header h4 {
            margin-bottom: 5px;
        }

        .subscription-page-header p {
            font-size: 13px;
        }


        /* =========================================================
           FILTER CARD
        ========================================================= */

        .filter-card {
            border: 0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
        }

        .filter-card .card-body {
            padding: 20px;
        }

        .filter-card .form-label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #455a64;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            height: 38px;
            font-size: 13px;
        }


        /* =========================================================
           TABLE CARD
        ========================================================= */

        .subscription-table-card {
            border: 0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
        }

        .subscription-table-card .card-body {
            padding: 20px;
        }


        /* =========================================================
           TABLE
        ========================================================= */

        .subscription-table {
            min-width: 900px;
        }

        .subscription-table th {
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
            vertical-align: middle;
        }

        .subscription-table td {
            vertical-align: middle;
            font-size: 13px;
        }

        .subscription-table tbody tr {
            transition: background .2s ease;
        }

        .subscription-table tbody tr:hover {
            background: #f8fbfd;
        }


        /* =========================================================
           SUBSCRIPTION ID
        ========================================================= */

        .subscription-id {
            color: #99abb4;
            font-weight: 600;
            white-space: nowrap;
        }


        /* =========================================================
           TENANT
        ========================================================= */

        .tenant-wrapper {
            display: flex;
            align-items: center;
            min-width: 200px;
        }

        .tenant-icon {
            width: 40px;
            height: 40px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            margin-right: 10px;

            background: #eaf7fd;
            color: #03a9f4;

            font-size: 16px;

            flex-shrink: 0;
        }

        .tenant-name {
            color: #455a64;
            font-weight: 600;
        }

        .tenant-owner {
            display: block;
            margin-top: 2px;
            color: #99abb4;
            font-size: 12px;
        }


        /* =========================================================
           AMOUNT
        ========================================================= */

        .subscription-amount {
            white-space: nowrap;
            color: #198754;
            font-weight: 600;
        }


        /* =========================================================
           CREATED DATE
        ========================================================= */

        .subscription-date {
            white-space: nowrap;
            color: #67757c;
        }


        /* =========================================================
           ACTION BUTTONS
        ========================================================= */

        .subscription-actions {
            white-space: nowrap;
        }

        .subscription-actions .btn {
            height: 32px;

            min-width: 70px;

            padding: 6px 10px;

            margin-right: 3px;

            border: 0;
            border-radius: 4px;

            font-size: 12px;
            font-weight: 500;

            transition: all .2s ease;
        }

        .subscription-actions .btn:hover {
            transform: translateY(-1px);
        }


        /* View */

        


        /* =========================================================
           ADD BUTTON
        ========================================================= */

        .btn-add-subscription {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 7px;

            height: 38px;

            padding: 8px 16px;

            background: #03a9f4;

            border: 1px solid #03a9f4;

            border-radius: 4px;

            color: #fff !important;

            font-size: 13px;
            font-weight: 500;

            text-decoration: none;

            transition: all .2s ease;
        }

        .btn-add-subscription:hover {
            background: #0288d1;
            border-color: #0288d1;

            color: #fff !important;

            transform: translateY(-1px);

            box-shadow: 0 3px 8px rgba(3, 169, 244, .25);
        }


        /* =========================================================
           ALERTS
        ========================================================= */

        .subscription-alert {
            border: 0;
            border-radius: 5px;
            font-size: 13px;
        }


        /* =========================================================
           EMPTY STATE
        ========================================================= */

        .subscription-empty {
            padding: 55px 20px !important;
            text-align: center;
            color: #99abb4 !important;
        }

        .subscription-empty i {
            display: block;

            margin-bottom: 12px;

            font-size: 42px;

            color: #cfd8dc;
        }

        .subscription-empty strong {
            display: block;

            margin-bottom: 5px;

            color: #67757c;

            font-size: 15px;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 767px) {

            .subscription-page {
                padding: 10px;
            }

            .subscription-table-card .card-body {
                padding: 15px;
            }

            .subscription-page-header {
                align-items: flex-start !important;
                flex-direction: column;
            }

            .btn-add-subscription {
                width: 100%;
                margin-top: 12px;
            }

            .subscription-table {
                min-width: 900px;
            }

            .subscription-actions .btn {
                min-width: 34px;
                width: 34px;
                height: 32px;
                padding: 0;
            }

            .subscription-actions .btn span {
                display: none;
            }

        }

    </style>

</head>


<body class="horizontal-nav boxed skin-megna fixed-layout">


@include('admin.nav')


<div class="page-wrapper">


    <div class="container-fluid subscription-page">


        {{-- =========================================================
             HEADER + ADD BUTTON
        ========================================================= --}}

        <div
            class="d-flex justify-content-between align-items-center subscription-page-header"
        >
<div class="row page-titles">

            <div class="col-md-12">

                <h4 class="text-themecolor">

                    <!-- <i class="fa fa-credit-card me-2"></i> -->

                    Subscription Management

                </h4>

                <p class="text-muted mb-0">

                    Manage all tenant subscriptions

                </p>

            </div>

        </div>

            <a
                href="{{ route('admin.subscriptions.create') }}"
                class="btn-add-subscription"
            >

                <i class="fa fa-plus-circle"></i>

                <span>
                    Add New Subscription
                </span>

            </a>

        </div>


        {{-- =========================================================
             SUCCESS MESSAGE
        ========================================================= --}}

        @if(session('success'))

            <div
                class="alert alert-success alert-dismissible fade show subscription-alert"
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


        {{-- =========================================================
             ERROR MESSAGE
        ========================================================= --}}

        @if(session('error'))

            <div
                class="alert alert-danger alert-dismissible fade show subscription-alert"
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


        {{-- =========================================================
             SEARCH / FILTER CARD
        ========================================================= --}}

        <div class="card shadow-sm filter-card mb-4">

            <div class="card-body">

                <form
                    method="GET"
                    action="{{ route('admin.subscriptions.index') }}"
                >

                    <div class="row g-3">


                        {{-- SEARCH --}}

                        <div class="col-lg-3 col-md-12">

                            <label class="form-label">

                                Search

                            </label>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Subscription ID, business name or owner name..."
                            >

                        </div>


                        {{-- CREATED DATE --}}

                        <div class="col-lg-2 col-md-6">

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


                        {{-- AMOUNT --}}

                        <div class="col-lg-3 col-md-6">

                            <label class="form-label">

                                Amount

                            </label>

                            <input
                                type="number"
                                name="amount"
                                class="form-control"
                                value="{{ request('amount') }}"
                                min="0"
                                step="0.01"
                                placeholder="Enter amount..."
                            >

                        </div>


                        {{-- BUTTONS --}}

                        <div class="col-lg-3 col-md-12 d-flex align-items-end justify-content-end">

                            <div class="d-flex gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >

                                    <i class="fa fa-search me-1"></i>

                                    Search

                                </button>


                                @if(request()->hasAny([
                                    'search',
                                    'created_date',
                                    'amount'
                                ]))

                                    <a
                                        href="{{ route('admin.subscriptions.index') }}"
                                        class="btn btn-secondary"
                                        title="Reset Filters"
                                    >

                                        <i class="fa fa-refresh me-1"></i>

                                        Reset

                                    </a>

                                @else

                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        disabled
                                    >

                                        <i class="fa fa-refresh me-1"></i>

                                        Reset

                                    </button>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- SEARCH HELP --}}

                    <div class="mt-3">

                        <small class="text-muted">

                            <i class="fa fa-info-circle"></i>

                            Search by Subscription ID, Business Name or Owner Name.

                        </small>

                    </div>

                </form>

            </div>

        </div>


        {{-- =========================================================
             SUBSCRIPTION TABLE
        ========================================================= --}}

        <div class="card subscription-table-card">

            <div class="card-body">

                <div class="table-responsive">

                    <table
                        class="table color-table primary-table subscription-table align-middle mb-0"
                    >

                        <thead>

                            <tr>

                                <th>
                                    #ID
                                </th>

                                <th>
                                    Tenant
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Created
                                </th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                        @forelse($subscriptions as $subscription)


                            <tr>


                                {{-- ID --}}

                                <td>

                                    <span class="subscription-id">

                                        #{{ $subscription->id }}

                                    </span>

                                </td>


                                {{-- TENANT --}}

                                <td>

                                    <div class="tenant-wrapper">


                                        <div class="tenant-icon">

                                            <i class="fa fa-building"></i>

                                        </div>


                                        <div>

                                            <span class="tenant-name">

                                                {{ $subscription->tenant->business_name
                                                    ?? $subscription->tenant->owner_name
                                                    ?? 'N/A' }}

                                            </span>


                                            @if(
                                                $subscription->tenant &&
                                                $subscription->tenant->business_name &&
                                                $subscription->tenant->owner_name
                                            )

                                                <span class="tenant-owner">

                                                    {{ $subscription->tenant->owner_name }}

                                                </span>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- AMOUNT --}}

                                <td>

                                    <span class="subscription-amount">

                                        Rs.
                                        {{ number_format(
                                            (float) $subscription->amount,
                                            2
                                        ) }}

                                    </span>

                                </td>


                                {{-- CREATED --}}

                                <td>

                                    <span class="subscription-date">

                                        {{ $subscription->created_at
                                            ? $subscription->created_at->format('d M Y')
                                            : 'N/A'
                                        }}

                                    </span>

                                </td>


                                {{-- ACTIONS --}}

                                <td class="text-end subscription-actions">


                                    {{-- VIEW --}}

                                    <a
                                        href="{{ route(
                                            'admin.subscriptions.show',
                                            $subscription->id
                                        ) }}"
                                        class="btn btn-primary btn-subscription-view"
                                        title="View Subscription"
                                    >

                                        <i class="fa fa-eye"></i>

                                        <span>
                                            View
                                        </span>

                                    </a>


                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route(
                                            'admin.subscriptions.edit',
                                            $subscription->id
                                        ) }}"
                                        class="btn btn-warning btn-subscription-edit"
                                        title="Edit Subscription"
                                    >

                                        <i class="fa fa-edit"></i>

                                        <span>
                                            Edit
                                        </span>

                                    </a>


                                    {{-- DELETE --}}

                                    <form
                                        action="{{ route(
                                            'admin.subscriptions.destroy',
                                            $subscription->id
                                        ) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm(
                                            'Are you sure you want to delete this subscription?'
                                        );"
                                    >

                                        @csrf

                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-subscription-delete"
                                            title="Delete Subscription"
                                        >

                                            <i class="fa fa-trash"></i>

                                            <span>
                                                Delete
                                            </span>

                                        </button>

                                    </form>


                                </td>


                            </tr>


                        @empty


                            {{-- EMPTY STATE --}}

                            <tr>

                                <td
                                    colspan="5"
                                    class="subscription-empty"
                                >

                                    <i class="fa fa-credit-card"></i>

                                    <strong>
                                        No subscriptions found
                                    </strong>

                                    <span>
                                        There are currently no subscriptions matching your search.
                                    </span>

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
