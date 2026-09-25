<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Tenant Details | {{ $tenant->business_name }}
    </title>


    <style>

        /* =========================================================
           PAGE
           ========================================================= */

        .tenant-view-page {
            padding-bottom: 40px;
        }


        /* =========================================================
           PROFILE HEADER
           ========================================================= */

        .tenant-profile-header {
            display: flex;
            align-items: center;
            gap: 18px;
        }


        .tenant-profile-logo {
            width: 85px;
            height: 85px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #eef1f5;
            background: #f8f9fa;
        }


        .tenant-profile-placeholder {
            width: 85px;
            height: 85px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #e8f4ff;
            color: #03a9f4;

            font-size: 32px;
        }


        .tenant-profile-name {
            font-size: 24px;
            font-weight: 700;
            color: #343a40;
        }


        .tenant-profile-owner {
            color: #6c757d;
            font-size: 14px;
        }


        /* =========================================================
           STATUS BADGE
           ========================================================= */

        .tenant-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;

            padding: 5px 12px;

            border-radius: 20px;

            font-size: 11px;
            font-weight: 600;

            white-space: nowrap;
        }


        .tenant-status-active {
            background: #d1e7dd;
            color: #198754;
        }


        .tenant-status-inactive {
            background: #f8d7da;
            color: #dc3545;
        }


        /* =========================================================
           TABLE
           ========================================================= */

        .tenant-detail-table {
            margin-bottom: 0;
        }


        .tenant-detail-table th {
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
            vertical-align: middle;
        }


        .tenant-detail-table td {
            font-size: 13px;
            vertical-align: middle;
        }


        .tenant-detail-value {
            font-weight: 600;
            color: #343a40;
            word-break: break-word;
        }


        .tenant-detail-muted {
            color: #67757c;
            word-break: break-word;
        }


        .tenant-money {
            font-weight: 700;
        }


        /* =========================================================
           QUICK STATS
           ========================================================= */

        .stat-card {
            border-radius: 8px;
            overflow: hidden;
        }


        .stat-label {
            font-size: 12px;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 5px;
        }


        .stat-value {
            font-size: 22px;
            font-weight: 700;
        }


        /* =========================================================
           EMPTY / WARNING
           ========================================================= */

        .tenant-warning {
            margin-bottom: 0;
        }


        /* =========================================================
           RESPONSIVE
           ========================================================= */

        @media (max-width: 767px) {

            .tenant-profile-header {
                flex-direction: column;
                align-items: flex-start;
            }


            .tenant-detail-table th {
                white-space: normal;
            }

        }

    </style>

</head>


<body>


@include('admin.nav')


<div class="page-wrapper">

    <div class="container-fluid tenant-view-page">


        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="row page-titles">

            <div class="col-md-8">

                <h4 class="text-themecolor">

                    <i class="fa fa-building me-2"></i>

                    Tenant Details

                </h4>


                <p class="text-muted mb-0">

                    Full information of registered tenant

                </p>

            </div>


            <div class="col-md-4 text-end">

                <a
                    href="{{ route('admin.tenants.index') }}"
                    class="btn btn-secondary"
                >

                    <i class="fa fa-arrow-left me-1"></i>

                    Back

                </a>


                <a
                    href="{{ route(
                        'admin.tenants.edit',
                        $tenant->id
                    ) }}"
                    class="btn btn-warning"
                >

                    <i class="fa fa-edit me-1"></i>

                    Edit

                </a>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- TENANT PROFILE HEADER --}}
        {{-- ========================================================= --}}

        <div class="row">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body">


                        <div class="tenant-profile-header">


                            {{-- LOGO / PLACEHOLDER --}}

                            <div class="tenant-profile-placeholder">

                                <i class="fa fa-building"></i>

                            </div>


                            {{-- NAME / OWNER / STATUS --}}

                            <div>

                                <div class="tenant-profile-name">

                                    {{ $tenant->business_name }}

                                </div>


                                <div class="tenant-profile-owner">

                                    Owner:

                                    {{ $tenant->owner_name ?? 'N/A' }}

                                </div>


                                <div class="mt-2">

                                    @if($tenant->status)

                                        <span
                                            class="tenant-status tenant-status-active"
                                        >

                                            <i class="fa fa-check-circle"></i>

                                            Active

                                        </span>

                                    @else

                                        <span
                                            class="tenant-status tenant-status-inactive"
                                        >

                                            <i class="fa fa-times-circle"></i>

                                            Inactive

                                        </span>

                                    @endif

                                </div>

                            </div>


                        </div>


                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- BUSINESS + OWNER --}}
        {{-- ========================================================= --}}

        <div class="row">


            {{-- ===================================================== --}}
            {{-- BUSINESS INFORMATION --}}
            {{-- ===================================================== --}}

            <div class="col-lg-7">

                <div class="card">

                    <div class="card-body">


                        <h4 class="card-title">

                            <i class="fa fa-building text-primary me-2"></i>

                            Business Information

                        </h4>


                        <h6 class="card-subtitle">

                            Tenant business details

                        </h6>


                        <div class="table-responsive">

                            {{-- PRIMARY TABLE UI --}}

                            <table class="table color-table primary-table tenant-detail-table">

                                <thead>

                                    <tr>

                                        <th>
                                            Information
                                        </th>

                                        <th>
                                            Details
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>


                                    {{-- TENANT ID --}}

                                    <tr>

                                        <th>
                                            Tenant ID
                                        </th>

                                        <td>

                                            <span class="tenant-detail-value">

                                                #{{ $tenant->id }}

                                            </span>

                                        </td>

                                    </tr>


                                    {{-- BUSINESS NAME --}}

                                    <tr>

                                        <th>
                                            Business Name
                                        </th>

                                        <td>

                                            <span class="tenant-detail-value">

                                                {{ $tenant->business_name ?? 'N/A' }}

                                            </span>

                                        </td>

                                    </tr>


                                    {{-- OWNER NAME --}}

                                    <tr>

                                        <th>
                                            Owner Name
                                        </th>

                                        <td>

                                            <span class="tenant-detail-value">

                                                {{ $tenant->owner_name ?? 'N/A' }}

                                            </span>

                                        </td>

                                    </tr>


                                    {{-- EMAIL --}}

                                    <tr>

                                        <th>
                                            Business Email
                                        </th>

                                        <td>

                                            <span class="tenant-detail-muted">

                                                {{ $tenant->email ?? 'N/A' }}

                                            </span>

                                        </td>

                                    </tr>


                                    {{-- PHONE --}}

                                    <tr>

                                        <th>
                                            Phone
                                        </th>

                                        <td>

                                            <span class="tenant-detail-muted">

                                                {{ $tenant->phone ?? 'N/A' }}

                                            </span>

                                        </td>

                                    </tr>


                                    {{-- NTN --}}

                                    <tr>

                                        <th>
                                            NTN Number
                                        </th>

                                        <td>

                                            <span class="tenant-detail-value">

                                                {{ $tenant->ntn_number ?? 'N/A' }}

                                            </span>

                                        </td>

                                    </tr>


                                    {{-- NIC --}}

                                    <tr>

                                        <th>
                                            CNIC / NIC Number
                                        </th>

                                        <td>

                                            <span class="tenant-detail-value">

                                                {{ $tenant->nic_number ?? 'N/A' }}

                                            </span>

                                        </td>

                                    </tr>


                                    {{-- BUSINESS ADDRESS --}}

                                    <tr>

                                        <th>
                                            Business Address
                                        </th>

                                        <td>

                                            <span class="tenant-detail-value">

                                                {{ $tenant->business_address
                                                    ?? $tenant->address
                                                    ?? 'N/A'
                                                }}

                                            </span>

                                        </td>

                                    </tr>


                                    {{-- HOME ADDRESS --}}

                                    <tr>

                                        <th>
                                            Home Address
                                        </th>

                                        <td>

                                            <span class="tenant-detail-value">

                                                {{ $tenant->home_address ?? 'N/A' }}

                                            </span>

                                        </td>

                                    </tr>


                                    {{-- STATUS --}}

                                    <tr>

                                        <th>
                                            Account Status
                                        </th>

                                        <td>

                                            @if($tenant->status)

                                                <span class="text-success">

                                                    <i class="fa fa-check-circle me-1"></i>

                                                    Active

                                                </span>

                                            @else

                                                <span class="text-danger">

                                                    <i class="fa fa-times-circle me-1"></i>

                                                    Inactive

                                                </span>

                                            @endif

                                        </td>

                                    </tr>


                                    {{-- CREATED --}}

                                    <tr>

                                        <th>
                                            Account Created
                                        </th>

                                        <td>

                                            <span class="tenant-detail-muted">

                                                @if($tenant->created_at)

                                                    {{ $tenant->created_at->format(
                                                        'd M Y, h:i A'
                                                    ) }}

                                                @else

                                                    N/A

                                                @endif

                                            </span>

                                        </td>

                                    </tr>


                                    {{-- UPDATED --}}

                                    <tr>

                                        <th>
                                            Last Updated
                                        </th>

                                        <td>

                                            <span class="tenant-detail-muted">

                                                @if($tenant->updated_at)

                                                    {{ $tenant->updated_at->format(
                                                        'd M Y, h:i A'
                                                    ) }}

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


            {{-- ===================================================== --}}
            {{-- OWNER ACCOUNT --}}
            {{-- ===================================================== --}}

            <div class="col-lg-5">

                <div class="card">

                    <div class="card-body">


                        <h4 class="card-title">

                            <i class="fa fa-user text-success me-2"></i>

                            Owner Account

                        </h4>


                        <h6 class="card-subtitle">

                            Tenant login account information

                        </h6>


                        @if($owner)

                            <div class="table-responsive">


                                {{-- SUCCESS TABLE UI --}}

                                <table class="table color-table success-table tenant-detail-table">

                                    <thead>

                                        <tr>

                                            <th>
                                                Information
                                            </th>

                                            <th>
                                                Details
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>


                                        {{-- OWNER NAME --}}

                                        <tr>

                                            <th>
                                                Owner Name
                                            </th>

                                            <td>

                                                <span class="tenant-detail-value">

                                                    {{ $owner->name ?? 'N/A' }}

                                                </span>

                                            </td>

                                        </tr>


                                        {{-- LOGIN EMAIL --}}

                                        <tr>

                                            <th>
                                                Login Email
                                            </th>

                                            <td>

                                                <span class="tenant-detail-muted">

                                                    {{ $owner->email ?? 'N/A' }}

                                                </span>

                                            </td>

                                        </tr>


                                        {{-- LOGIN PHONE --}}

                                        <tr>

                                            <th>
                                                Login Phone
                                            </th>

                                            <td>

                                                <span class="tenant-detail-muted">

                                                    {{ $owner->phone ?? 'N/A' }}

                                                </span>

                                            </td>

                                        </tr>


                                        {{-- ROLE --}}

                                        <tr>

                                            <th>
                                                Role
                                            </th>

                                            <td>

                                                <span class="tenant-detail-value">

                                                    {{ $owner->role ?? 'tenant_owner' }}

                                                </span>

                                            </td>

                                        </tr>


                                        {{-- USER STATUS --}}

                                        <tr>

                                            <th>
                                                User Status
                                            </th>

                                            <td>

                                                @if($owner->status)

                                                    <span class="text-success">

                                                        <i class="fa fa-check-circle me-1"></i>

                                                        Active

                                                    </span>

                                                @else

                                                    <span class="text-danger">

                                                        <i class="fa fa-times-circle me-1"></i>

                                                        Inactive

                                                    </span>

                                                @endif

                                            </td>

                                        </tr>


                                        {{-- USER CREATED --}}

                                        <tr>

                                            <th>
                                                Account Created
                                            </th>

                                            <td>

                                                <span class="tenant-detail-muted">

                                                    @if($owner->created_at)

                                                        {{ $owner->created_at->format(
                                                            'd M Y, h:i A'
                                                        ) }}

                                                    @else

                                                        N/A

                                                    @endif

                                                </span>

                                            </td>

                                        </tr>


                                    </tbody>

                                </table>

                            </div>

                        @else

                            <div class="alert alert-warning tenant-warning">

                                <i
                                    class="fa fa-exclamation-triangle me-1"
                                ></i>

                                Owner account not found.

                            </div>

                        @endif


                    </div>

                </div>

            </div>


        </div>


        {{-- ========================================================= --}}
        {{-- SUBSCRIPTION INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="row">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body">


                        <h4 class="card-title">

                            <i class="fa fa-credit-card text-primary me-2"></i>

                            Subscription Information

                        </h4>


                        <h6 class="card-subtitle">

                            Current tenant subscription

                        </h6>


                        @if($activeSubscription)

                            <div class="table-responsive">


                                {{-- PRIMARY TABLE UI --}}

                                <table class="table color-table primary-table tenant-detail-table">

                                    <thead>

                                        <tr>

                                            <th>
                                                Information
                                            </th>

                                            <th>
                                                Details
                                            </th>

                                            <th>
                                                Information
                                            </th>

                                            <th>
                                                Details
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>


                                        {{-- SUBSCRIPTION ID + MONTHLY AMOUNT --}}

                                        <tr>

                                            <th>
                                                Subscription ID
                                            </th>

                                            <td>

                                                <span class="tenant-detail-value">

                                                    #{{ $activeSubscription->id }}

                                                </span>

                                            </td>


                                            <th>
                                                Monthly Amount
                                            </th>

                                            <td>

                                                <span
                                                    class="tenant-money text-primary"
                                                >

                                                    Rs.

                                                    {{ number_format(
                                                        (float) (
                                                            $activeSubscription->amount
                                                            ?? 0
                                                        ),
                                                        2
                                                    ) }}

                                                </span>

                                            </td>

                                        </tr>


                                        {{-- STATUS + OUTSTANDING --}}

                                        <tr>

                                            <th>
                                                Subscription Status
                                            </th>

                                            <td>

                                                <span class="tenant-detail-value">

                                                    {{ ucfirst(
                                                        $activeSubscription->status
                                                        ?? 'Active'
                                                    ) }}

                                                </span>

                                            </td>


                                            <th>
                                                Outstanding
                                            </th>

                                            <td>

                                                <span class="tenant-money text-danger">

                                                    Rs.

                                                    {{ number_format(
                                                        (float) (
                                                            $subscriptionOutstanding
                                                            ?? 0
                                                        ),
                                                        2
                                                    ) }}

                                                </span>

                                            </td>

                                        </tr>


                                        {{-- STARTED + UPDATED --}}

                                        <tr>

                                            <th>
                                                Subscription Started
                                            </th>

                                            <td>

                                                <span class="tenant-detail-muted">

                                                    @if($activeSubscription->created_at)

                                                        {{ $activeSubscription->created_at->format(
                                                            'd M Y'
                                                        ) }}

                                                    @else

                                                        N/A

                                                    @endif

                                                </span>

                                            </td>


                                            <th>
                                                Last Updated
                                            </th>

                                            <td>

                                                <span class="tenant-detail-muted">

                                                    @if($activeSubscription->updated_at)

                                                        {{ $activeSubscription->updated_at->format(
                                                            'd M Y'
                                                        ) }}

                                                    @else

                                                        N/A

                                                    @endif

                                                </span>

                                            </td>

                                        </tr>


                                    </tbody>

                                </table>

                            </div>

                        @else

                            <div class="alert alert-warning tenant-warning">

                                <i
                                    class="fa fa-exclamation-triangle me-1"
                                ></i>

                                This tenant does not have an active
                                subscription.

                            </div>

                        @endif


                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- QUICK STATS --}}
        {{-- ========================================================= --}}

        <div class="row">


            {{-- TENANT ID --}}

            <div class="col-lg-3 col-md-6">

                <div class="card stat-card">

                    <div class="card-body">

                        <div class="stat-label">

                            Tenant ID

                        </div>

                        <div class="stat-value text-primary">

                            #{{ $tenant->id }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- STATUS --}}

            <div class="col-lg-3 col-md-6">

                <div class="card stat-card">

                    <div class="card-body">

                        <div class="stat-label">

                            Status

                        </div>

                        <div
                            class="stat-value
                            {{ $tenant->status
                                ? 'text-success'
                                : 'text-danger'
                            }}"
                        >

                            {{ $tenant->status
                                ? 'Active'
                                : 'Inactive'
                            }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- USERS --}}

            <div class="col-lg-3 col-md-6">

                <div class="card stat-card">

                    <div class="card-body">

                        <div class="stat-label">

                            Tenant Users

                        </div>

                        <div class="stat-value text-info">

                            {{ $tenantUsers->count() }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- OUTSTANDING --}}

            <div class="col-lg-3 col-md-6">

                <div class="card stat-card">

                    <div class="card-body">

                        <div class="stat-label">

                            Subscription Outstanding

                        </div>

                        <div class="stat-value text-danger">

                            Rs.

                            {{ number_format(
                                (float) (
                                    $subscriptionOutstanding ?? 0
                                ),
                                2
                            ) }}

                        </div>

                    </div>

                </div>

            </div>


        </div>


    </div>

</div>


@include('admin.footer')


</body>

</html>