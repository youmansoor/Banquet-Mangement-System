<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Subscription Details</title>


    <link
        rel="icon"
        type="image/png"
        href="{{ asset('assets/images/favicon.png') }}"
    >


    <link
        rel="stylesheet"
        href="{{ asset('assets/dist/css/style.min.css') }}"
    >


    <link
        rel="stylesheet"
        href="{{ asset('assets/dist/css/pages/dashboard1.css') }}"
    >


    <style>

        /* =========================================================
           PAGE
           ========================================================= */

        .subscription-details-page {
            padding-bottom: 40px;
        }


        /* =========================================================
           TABLE
           ========================================================= */

        .subscription-detail-table {
            margin-bottom: 0;
        }


        .subscription-detail-table th {
            font-size: 13px;
            font-weight: 600;
            vertical-align: middle;
        }


        .subscription-detail-table td {
            font-size: 13px;
            vertical-align: middle;
        }


        .subscription-field {
            font-weight: 600;
            color: #343a40;
        }


        .subscription-value {
            color: #343a40;
            word-break: break-word;
        }


        .subscription-money {
            font-size: 16px;
            font-weight: 700;
        }


        /* =========================================================
           STATUS
           ========================================================= */

        .subscription-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;

            padding: 5px 12px;

            border-radius: 20px;

            font-size: 11px;
            font-weight: 600;

            white-space: nowrap;
        }


        .subscription-status-active {
            background: #d1e7dd;
            color: #198754;
        }


        .subscription-status-inactive {
            background: #f8d7da;
            color: #dc3545;
        }


        /* =========================================================
           RESPONSIVE
           ========================================================= */

        @media (max-width: 767px) {

            .subscription-detail-table th,
            .subscription-detail-table td {
                font-size: 12px;
            }

        }

    </style>

</head>


<body>


@include('admin.nav')


<div class="page-wrapper">

    <div class="container-fluid subscription-details-page">


        {{-- ====================================================== --}}
        {{-- PAGE TITLE --}}
        {{-- ====================================================== --}}

        <div class="row page-titles">


            <div class="col-md-8 align-self-center">

                <h4 class="text-themecolor">

                    <i class="fa fa-credit-card me-2"></i>

                    Subscription Details

                </h4>


                <h6 class="card-subtitle">

                    View complete subscription information

                </h6>

            </div>


            <div class="col-md-4 text-end">

                <a
                    href="{{ route('admin.subscriptions.index') }}"
                    class="btn btn-secondary"
                >

                    <i class="fa fa-arrow-left me-1"></i>

                    Back

                </a>

            </div>


        </div>



        {{-- ====================================================== --}}
        {{-- DETAILS TABLES --}}
        {{-- ====================================================== --}}

        <div class="row">


            {{-- ================================================== --}}
            {{-- SUBSCRIPTION INFORMATION --}}
            {{-- ================================================== --}}

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-body">


                        <h4 class="card-title">

                            <i class="fa fa-credit-card text-primary me-2"></i>

                            Subscription Information

                        </h4>


                        <h6 class="card-subtitle">

                            Subscription details

                        </h6>


                        <div class="table-responsive">


                            {{-- PRIMARY TABLE UI --}}

                            <table
                                class="table color-table primary-table subscription-detail-table"
                            >


                                <thead>

                                    <tr>

                                        <th width="40%">
                                            Field
                                        </th>

                                        <th>
                                            Details
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>


                                    {{-- ================================= --}}
                                    {{-- SUBSCRIPTION ID --}}
                                    {{-- ================================= --}}

                                    <tr>

                                        <th>

                                            Subscription ID

                                        </th>


                                        <td>

                                            <span class="subscription-value">

                                                #{{ $subscription->id }}

                                            </span>

                                        </td>

                                    </tr>



                                    {{-- ================================= --}}
                                    {{-- TENANT --}}
                                    {{-- ================================= --}}

                                    <tr>

                                        <th>

                                            Tenant

                                        </th>


                                        <td>

                                            <span class="subscription-value">

                                                {{ $subscription->tenant->business_name
                                                    ?? 'N/A'
                                                }}

                                            </span>


                                            @if(
                                                $subscription->tenant
                                                && $subscription->tenant->owner_name
                                            )

                                                <br>

                                                <small class="text-muted">

                                                    Owner:

                                                    {{ $subscription->tenant->owner_name }}

                                                </small>

                                            @endif

                                        </td>

                                    </tr>



                                    {{-- ================================= --}}
                                    {{-- AMOUNT --}}
                                    {{-- ================================= --}}

                                    <tr>

                                        <th>

                                            Monthly Amount

                                        </th>


                                        <td>

                                            <span
                                                class="subscription-money text-primary"
                                            >

                                                Rs.

                                                {{ number_format(
                                                    (float) $subscription->amount,
                                                    2
                                                ) }}

                                            </span>

                                        </td>

                                    </tr>



                                    {{-- ================================= --}}
                                    {{-- STATUS --}}
                                    {{-- ================================= --}}

                                    <tr>

                                        <th>

                                            Status

                                        </th>


                                        <td>


                                            @if(
                                                isset($subscription->status)
                                            )


                                                @if($subscription->status)

                                                    <span
                                                        class="subscription-status subscription-status-active"
                                                    >

                                                        <i class="fa fa-check-circle"></i>

                                                        Active

                                                    </span>

                                                @else

                                                    <span
                                                        class="subscription-status subscription-status-inactive"
                                                    >

                                                        <i class="fa fa-times-circle"></i>

                                                        Inactive

                                                    </span>

                                                @endif


                                            @else

                                                <span
                                                    class="subscription-status subscription-status-active"
                                                >

                                                    <i class="fa fa-check-circle"></i>

                                                    Active

                                                </span>

                                            @endif


                                        </td>

                                    </tr>


                                </tbody>

                            </table>


                        </div>

                    </div>

                </div>

            </div>



            {{-- ================================================== --}}
            {{-- RECORD INFORMATION --}}
            {{-- ================================================== --}}

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-body">


                        <h4 class="card-title">

                            <i class="fa fa-database text-success me-2"></i>

                            Record Information

                        </h4>


                        <h6 class="card-subtitle">

                            Subscription record timestamps

                        </h6>


                        <div class="table-responsive">


                            {{-- SUCCESS TABLE UI --}}

                            <table
                                class="table color-table success-table subscription-detail-table"
                            >


                                <thead>

                                    <tr>

                                        <th width="40%">
                                            Field
                                        </th>

                                        <th>
                                            Details
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>


                                    {{-- ================================= --}}
                                    {{-- CREATED --}}
                                    {{-- ================================= --}}

                                    <tr>

                                        <th>

                                            Created

                                        </th>


                                        <td>

                                            <span class="subscription-value">

                                                {{ $subscription->created_at
                                                    ? $subscription->created_at->format('d M Y H:i')
                                                    : 'N/A'
                                                }}

                                            </span>

                                        </td>

                                    </tr>



                                    {{-- ================================= --}}
                                    {{-- UPDATED --}}
                                    {{-- ================================= --}}

                                    <tr>

                                        <th>

                                            Last Updated

                                        </th>


                                        <td>

                                            <span class="subscription-value">

                                                {{ $subscription->updated_at
                                                    ? $subscription->updated_at->format('d M Y H:i')
                                                    : 'N/A'
                                                }}

                                            </span>

                                        </td>

                                    </tr>



                                    {{-- ================================= --}}
                                    {{-- TENANT ID --}}
                                    {{-- ================================= --}}

                                    <tr>

                                        <th>

                                            Tenant ID

                                        </th>


                                        <td>

                                            <span class="subscription-value">

                                                {{ $subscription->tenant_id ?? 'N/A' }}

                                            </span>

                                        </td>

                                    </tr>



                                    {{-- ================================= --}}
                                    {{-- ACCOUNT --}}
                                    {{-- ================================= --}}

                                    <tr>

                                        <th>

                                            Tenant Account

                                        </th>


                                        <td>

                                            <span class="subscription-value">

                                                @if($subscription->tenant)

                                                    {{ $subscription->tenant->business_name }}

                                                @else

                                                    N/A

                                                @endif

                                            </span>

                                        </td>

                                    </tr>


                                </tbody>

                            </table>


                        </div>

                    </div>

                </div>

            </div>


        </div>



        {{-- ====================================================== --}}
        {{-- ACTIONS --}}
        {{-- ====================================================== --}}

        <div class="row">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body">


                        <h4 class="card-title">

                            <i class="fa fa-cogs text-warning me-2"></i>

                            Actions

                        </h4>


                        <h6 class="card-subtitle">

                            Manage this subscription

                        </h6>


                        <a
                            href="{{ route(
                                'admin.subscriptions.edit',
                                $subscription
                            ) }}"
                            class="btn btn-warning me-2"
                        >

                            <i class="fa fa-edit me-1"></i>

                            Edit Subscription

                        </a>


                        <a
                            href="{{ route(
                                'admin.subscriptions.index'
                            ) }}"
                            class="btn btn-secondary"
                        >

                            <i class="fa fa-arrow-left me-1"></i>

                            Back to Subscriptions

                        </a>


                    </div>

                </div>

            </div>

        </div>


    </div>

</div>


@include('admin.footer')


</body>

</html>