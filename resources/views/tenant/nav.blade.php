@php

    /*
    |--------------------------------------------------------------------------
    | FREE SERVICES
    |--------------------------------------------------------------------------
    | Load active free services for the logged-in tenant.
    | This makes $freeServices available on every tenant page
    | because tenant.nav is shared across the application.
    |--------------------------------------------------------------------------
    */

    $freeServices = collect();

    if (
        auth()->check() &&
        auth()->user()->tenant_id
    ) {

        $freeServices = \App\Models\FreeService::where(
            'tenant_id',
            auth()->user()->tenant_id
        )
            ->where('status', true)
            ->orderBy('service_name')
            ->get();

    }

@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Elite Admin Dashboard">
    <meta name="author" content="">

    <link rel="icon"
          type="image/png"
          sizes="16x16"
          href="{{ asset('assets/images/favicon.png') }}">

    <title>Elite Admin Dashboard</title>
    <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet"
      href="{{ asset('assets/node_modules/calendar/dist/fullcalendar.css') }}">

      
    <link rel="stylesheet"
          href="{{ asset('assets/node_modules/morrisjs/morris.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/node_modules/toast-master/css/jquery.toast.css') }}">

    {{-- Main CSS --}}
    <link rel="stylesheet"href="{{ asset('assets/dist/css/style.min.css') }}">

    {{-- Dashboard CSS --}}
    <link rel="stylesheet"href="{{ asset('assets/dist/css/pages/dashboard1.css') }}">

    <style>

        #settingsSidebar {
            width: 800px;
            max-width: 90%;
        }

        #settingsSidebar .offcanvas-header {
            background: #e46a75;
            color: #fff;
        }

        #settingsSidebar .offcanvas-title {
            font-weight: 500;
        }

        #settingsSidebar .form-label {
            font-weight: 500;
        }

        #settingsSidebar .form-control,
        #settingsSidebar .form-select {
            border-radius: 4px;
        }

        #settingsSidebar .btn-info {
            background-color: #2cabe3;
            border-color: #2cabe3;
        }

        #settingsSidebar .btn-info:hover {
            background-color: #1e96ca;
            border-color: #1e96ca;
        }
        .offcanvas-header{
            background-color: #e46a75;
        }

        /* =========================================================
   TENANT PAGE PIN SECURITY
   ========================================================= */

.tenant-page-link {
    position: relative;
}

.tenant-pin-lock {
    margin-left: 5px;
    font-size: 12px;
    opacity: 0.75;
}


/* =========================================================
   TENANT PAGE PIN MODAL
   ========================================================= */

#tenantPagePinModal .modal-content {
    border: 0;
    border-radius: 10px;
    overflow: hidden;
}

#tenantPagePinModal .modal-header {
    background: #2cabe3;
    color: #fff;
}

#tenantPagePinModal .modal-title {
    font-weight: 500;
}

#tenantPagePinModal .pin-security-icon {
    width: 65px;
    height: 65px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eaf7fd;
    color: #2cabe3;
    font-size: 30px;
    margin: 0 auto 15px;
}

#tenantPagePinInput {
    text-align: center;
    letter-spacing: 8px;
    font-size: 22px;
    font-weight: 600;
}

#tenantPagePinError {
    display: none;
}

.tenant-pin-loading {
    pointer-events: none;
    opacity: .75;
}
    </style>
    
</head>

<body class="horizontal-nav boxed skin-megna fixed-layout">

    {{-- ========================================================= --}}
    {{-- PRELOADER --}}
    {{-- ========================================================= --}}

    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Elite admin</p>
        </div>
    </div>


    @php
    $tenantPagePins = collect();

    if (auth()->check() && auth()->user()->tenant_id) {
        $tenantPagePins = \App\Models\TenantPagePin::where(
            'tenant_id',
            auth()->user()->tenant_id
        )
        ->get()
        ->keyBy('page_key');
    }

    $tenantPinEnabled = function ($pageKey) use ($tenantPagePins) {
        return (bool) (
            $tenantPagePins->get($pageKey)?->enabled ?? false
        );
    };
@endphp
    {{-- ========================================================= --}}
    {{-- MAIN WRAPPER --}}
    {{-- ========================================================= --}}

    <div id="main-wrapper">

        {{-- ========================================================= --}}
        {{-- TOPBAR --}}
        {{-- ========================================================= --}}

        <header class="topbar">

            <nav class="navbar top-navbar navbar-expand-md navbar-dark">

                {{-- Logo --}}
                <div class="navbar-header">

                    <a class="navbar-brand"
                       href="{{ url('/tenant/dashboard') }}">

                        <b>
                            <!-- <img src="{{ asset('storage/' . ltrim(auth()->user()->tenant?->logo ?? '', '/')) }}" alt="{{ auth()->user()->tenant?->business_name ?? 'Business logo' }}" width="50"> -->
                        </b>

                        <span class="hidden-sm-down">

                            {{ auth()->user()->tenant->business_name }}

                        </span>

                    </a>

                </div>

                {{-- Navbar --}}
                <div class="navbar-collapse">

                    {{-- Left --}}
                    <ul class="navbar-nav me-auto">

                        <li class="nav-item">
                            <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark"
                               href="javascript:void(0)">
                                <i class="ti-menu"></i>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link sidebartoggler d-none waves-effect waves-dark"
                               href="javascript:void(0)">
                                <i class="icon-menu"></i>
                            </a>
                        </li>


                    </ul>

                    {{-- Right --}}
                    <ul class="navbar-nav my-lg-0">

                        {{-- Notifications --}}
                        <li class="nav-item dropdown">

    <a class="nav-link dropdown-toggle waves-effect waves-dark"
       href="#"
       data-bs-toggle="dropdown"
       aria-haspopup="true"
       aria-expanded="false">

        <i class="ti-email"></i>

        <div class="notify" id="tenantNotificationBell">
            <span class="heartbit"></span>
            <span class="point"></span>
        </div>

    </a>

    <div class="dropdown-menu dropdown-menu-end mailbox animated bounceInDown">

        <ul>

            <li>
                <div class="drop-title">
                    Notifications
                </div>
            </li>

            <li>

                <div class="message-center"
                     id="tenantNotificationMessages">

                    <a href="{{ url('/tenant/chat') }}">

                        <div class="btn btn-primary btn-circle text-white">
                            <i class="ti-email"></i>
                        </div>

                        <div class="mail-contnet">

                            <h5>Loading...</h5>

                            <span class="mail-desc">
                                Checking messages...
                            </span>

                        </div>

                    </a>

                </div>

            </li>

            <li>

                <a class="nav-link text-center link"
                   href="{{ url('/tenant/chat') }}">

                    <strong>
                        Check all messages
                    </strong>

                    <i class="fa fa-angle-right"></i>

                </a>

            </li>

        </ul>

    </div>

</li>
                        
                        {{-- Messages --}}
                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle waves-effect waves-dark"
                               href="#"
                               id="messageDropdown"
                               data-bs-toggle="dropdown"
                               aria-haspopup="true"
                               aria-expanded="false">

                                <i class="icon-note"></i>

                                <div class="notify">
                                    <span class="heartbit"></span>
                                    <span class="point"></span>
                                </div>

                            </a>

                            <div class="dropdown-menu mailbox dropdown-menu-end animated bounceInDown"
                                 aria-labelledby="messageDropdown">

                                <ul>

                                    <li>
                                        <div class="drop-title">
                                            You have 4 new messages
                                        </div>
                                    </li>

                                    <li>

                                        <div class="message-center">

                                            @foreach ([
                                                ['1.jpg', 'Pavan kumar', 'Just see the my admin!', '9:30 AM', 'online'],
                                                ['2.jpg', 'Sonu Nigam', "I've sung a song! See you at", '9:10 AM', 'busy'],
                                                ['3.jpg', 'Arijit Sinh', 'I am a singer!', '9:08 AM', 'away'],
                                                ['4.jpg', 'Pavan kumar', 'Just see the my admin!', '9:02 AM', 'offline']
                                            ] as $message)

                                                <a href="javascript:void(0)">

                                                    <div class="user-img">

                                                        <img src="{{ asset('assets/images/users/' . $message[0]) }}"
                                                             alt="user"
                                                             class="img-circle">

                                                        <span class="profile-status {{ $message[4] }} pull-right"></span>

                                                    </div>

                                                    <div class="mail-contnet">

                                                        <h5>{{ $message[1] }}</h5>

                                                        <span class="mail-desc">
                                                            {{ $message[2] }}
                                                        </span>

                                                        <span class="time">
                                                            {{ $message[3] }}
                                                        </span>

                                                    </div>

                                                </a>

                                            @endforeach

                                        </div>

                                    </li>

                                    <li>

                                        <a class="nav-link text-center link"
                                           href="javascript:void(0)">

                                            <strong>
                                                See all e-Mails
                                            </strong>

                                            <i class="fa fa-angle-right"></i>

                                        </a>

                                    </li>

                                </ul>

                            </div>

                        </li>
                        {{-- User Profile --}}
                        <li class="nav-item dropdown u-pro">

                            <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic"
                               href="#"
                               data-bs-toggle="dropdown"
                               aria-haspopup="true"
                               aria-expanded="false">

                                <!-- <img src="{{ asset('storage/' . auth()->user()->tenant->logo) }}" alt=""> -->

                                <span class="hidden-md-down">

                                    {{auth()->user()->tenant->owner_name}}
                                    <i class="fa fa-angle-down"></i>

                                </span>

                            </a>

                            <div class="dropdown-menu dropdown-menu-end animated flipInY">
                                <a href="{{url("/tenant/chat")}}"
                                   class="dropdown-item">

                                    <i class="ti-email"></i>
                                    Inbox

                                </a>

                                <div class="dropdown-divider"></div>

    <a href="{{ url('/tenant/settings') }}" class="dropdown-item">
        <i class="ti-settings"></i>
        Settings
    </a>

    <a href="{{ url('tenant/accepted-terms-conditions') }}" class="dropdown-item">
        <i class="fas fa-file-contract"></i>
        Terms & Conditions
    </a>


                                <div class="dropdown-divider"></div>

                                <a href="{{ url('/login') }}"
                                   class="dropdown-item">

                                    <i class="fa fa-power-off"></i>
                                    Logout

                                </a>

                            </div>

                        </li>

                        {{-- Settings --}}
                        <li class="nav-item">

                            <a class="nav-link waves-effect waves-light"
                               href="#settingsSidebar"
                               data-bs-toggle="offcanvas"
                               aria-controls="settingsSidebar"
                               title="Booking">

                                <i class="ti-calendar"></i>

                            </a>

                        </li>

                    </ul>

                </div>

            </nav>

        </header>


        {{-- ========================================================= --}}
        {{-- SIDEBAR --}}
        {{-- ========================================================= --}}

        <aside class="left-sidebar">

    <div class="scroll-sidebar">

        <nav class="sidebar-nav">

            <ul id="sidebarnav">

                {{-- ========================================================= --}}
                {{-- USER --}}
                {{-- ========================================================= --}}

                <li class="user-pro">

                    <a class="has-arrow waves-effect waves-dark"
                       href="javascript:void(0)"
                       aria-expanded="false">

                        <img
                            src="{{ asset('assets/images/users/1.jpg') }}"
                            alt="user-img"
                            class="img-circle"
                        >

                        <span class="hide-menu">
                            {{ auth()->user()->name ?? 'User' }}
                        </span>

                    </a>

                    <ul aria-expanded="false" class="collapse">

                        {{-- PROFILE --}}

                        <li>

                            <a
                                href="{{ route('tenant.profile.edit') }}"
                                class="tenant-page-link"
                                data-page-pin="profile"
                                data-page-locked="{{ $tenantPinEnabled('profile') ? '1' : '0' }}"
                            >

                                <i class="ti-user"></i>

                                My Profile

                                @if($tenantPinEnabled('profile'))

                                    <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                @endif

                            </a>

                        </li>


                        {{-- LOGOUT --}}

                        <li>

                            <a href="{{ url('/login') }}">

                                <i class="fa fa-power-off"></i>

                                Logout

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- ========================================================= --}}
                {{-- DASHBOARD --}}
                {{-- ========================================================= --}}
                {{-- Dashboard intentionally NOT protected by Tenant Page PIN --}}

                @can('dashboard.view')

                    <li>

                        <a
                            class="waves-effect waves-dark"
                            href="{{ route('tenant.dashboard') }}"
                            aria-expanded="false"
                        >

                            <span class="hide-menu">

                                Dashboard

                            </span>

                        </a>

                    </li>

                @endcan


                {{-- ========================================================= --}}
                {{-- BUSINESS MANAGEMENT --}}
                {{-- ========================================================= --}}

                <li class="nav-small-cap">

                    --- BUSINESS MANAGEMENT

                </li>


                {{-- ================= CUSTOMERS ================= --}}

                @can('customers.view')

                    <li>

                        <a class="has-arrow waves-effect waves-dark"
                           href="javascript:void(0)"
                           aria-expanded="false">

                            <span class="hide-menu">

                                Customers

                            </span>

                        </a>

                        <ul aria-expanded="false" class="collapse">


                            {{-- ALL CUSTOMERS --}}

                            <li>

                                <a
                                    href="{{ url('/customers') }}"
                                    class="tenant-page-link"
                                    data-page-pin="customers"
                                    data-page-locked="{{ $tenantPinEnabled('customers') ? '1' : '0' }}"
                                >

                                    All Customers

                                    @if($tenantPinEnabled('customers'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>


                            {{-- ADD CUSTOMER --}}

                            @can('customers.create')

                                <li>

                                    <a
                                        href="{{ url('/customers/create') }}"
                                        class="tenant-page-link"
                                        data-page-pin="customers"
                                        data-page-locked="{{ $tenantPinEnabled('customers') ? '1' : '0' }}"
                                    >

                                        Add Customer

                                        @if($tenantPinEnabled('customers'))

                                            <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                        @endif

                                    </a>

                                </li>

                            @endcan

                        </ul>

                    </li>

                @endcan


                {{-- ================= VENUES ================= --}}

                @can('venues.view')

                    <!-- Venues / Halls intentionally disabled -->

                @endcan


                {{-- ================= SERVICES ================= --}}

                @can('services.view')

                    <li>

                        <a class="has-arrow waves-effect waves-dark"
                           href="javascript:void(0)"
                           aria-expanded="false">

                            <span class="hide-menu">

                                Services

                            </span>

                        </a>

                        <ul aria-expanded="false" class="collapse">


                            {{-- ALL SERVICES --}}

                            <li>

                                <a
                                    href="{{ url('/services') }}"
                                    class="tenant-page-link"
                                    data-page-pin="services"
                                    data-page-locked="{{ $tenantPinEnabled('services') ? '1' : '0' }}"
                                >

                                    All Services

                                    @if($tenantPinEnabled('services'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>


                            {{-- CREATE SERVICE --}}

                            @can('services.create')

                                <li>

                                    <a
                                        href="{{ url('/services/create') }}"
                                        class="tenant-page-link"
                                        data-page-pin="services"
                                        data-page-locked="{{ $tenantPinEnabled('services') ? '1' : '0' }}"
                                    >

                                        Add Service

                                        @if($tenantPinEnabled('services'))

                                            <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                        @endif

                                    </a>

                                </li>

                            @endcan

                        </ul>

                    </li>

                @endcan


                {{-- ================= LAWN TYPES ================= --}}

                @can('venues.view')

                    <li>

                        <a class="has-arrow waves-effect waves-dark"
                           href="javascript:void(0)"
                           aria-expanded="false">

                            <span class="hide-menu">

                                Lawn Types

                            </span>

                        </a>

                        <ul aria-expanded="false" class="collapse">


                            {{-- ALL LAWN TYPES --}}

                            <li>

                                <a
                                    href="{{ url('/lawn_types') }}"
                                    class="tenant-page-link"
                                    data-page-pin="lawn_types"
                                    data-page-locked="{{ $tenantPinEnabled('lawn_types') ? '1' : '0' }}"
                                >

                                    All Lawn Types

                                    @if($tenantPinEnabled('lawn_types'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>


                            {{-- CREATE LAWN TYPE --}}

                            @can('venues.create')

                                <li>

                                    <a
                                        href="{{ url('/lawn_types/create') }}"
                                        class="tenant-page-link"
                                        data-page-pin="lawn_types"
                                        data-page-locked="{{ $tenantPinEnabled('lawn_types') ? '1' : '0' }}"
                                    >

                                        Add Lawn Type

                                        @if($tenantPinEnabled('lawn_types'))

                                            <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                        @endif

                                    </a>

                                </li>

                            @endcan

                        </ul>

                    </li>

                @endcan


                {{-- ========================================================= --}}
                {{-- BOOKINGS --}}
                {{-- ========================================================= --}}

                <li class="nav-small-cap">

                    --- BOOKINGS

                </li>


                @can('bookings.calendar.view')

                    <li>

                        <a class="has-arrow waves-effect waves-dark"
                           href="javascript:void(0)"
                           aria-expanded="false">

                            <span class="hide-menu">

                                Bookings

                            </span>

                        </a>


                        <ul aria-expanded="false" class="collapse">


                            {{-- ================================================= --}}
                            {{-- CALENDAR --}}
                            {{-- ================================================= --}}

                            <li>

                                <a
                                    href="{{ route('bookings.calendar') }}"
                                    class="tenant-page-link"
                                    data-page-pin="bookings"
                                    data-page-locked="{{ $tenantPinEnabled('bookings') ? '1' : '0' }}"
                                >

                                    Bookings Calendar View

                                    @if($tenantPinEnabled('bookings'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>


                            {{-- ================================================= --}}
                            {{-- TABLE --}}
                            {{-- ================================================= --}}

                            <li>

                                <a
                                    class="waves-effect waves-dark tenant-page-link"
                                    href="{{ url('/bookings') }}"
                                    aria-expanded="false"
                                    data-page-pin="bookings"
                                    data-page-locked="{{ $tenantPinEnabled('bookings') ? '1' : '0' }}"
                                >

                                    <span class="hide-menu">

                                        Bookings Table View

                                        @if($tenantPinEnabled('bookings'))

                                            <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                        @endif

                                    </span>

                                </a>

                            </li>


                            {{-- ================================================= --}}
                            {{-- CANCELLED --}}
                            {{-- ================================================= --}}

                            <li>

                                <a
                                    href="{{ route('bookings.cancelled') }}"
                                    class="tenant-page-link"
                                    data-page-pin="bookings"
                                    data-page-locked="{{ $tenantPinEnabled('bookings') ? '1' : '0' }}"
                                >

                                    Cancelled Bookings

                                    @if($tenantPinEnabled('bookings'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>


                            {{-- ================================================= --}}
                            {{-- QUOTATIONS --}}
                            {{-- ================================================= --}}

                            <li>

                                <a
                                    href="{{ url('/quotations') }}"
                                    class="tenant-page-link"
                                    data-page-pin="quotations"
                                    data-page-locked="{{ $tenantPinEnabled('quotations') ? '1' : '0' }}"
                                >

                                    All Quotations

                                    @if($tenantPinEnabled('quotations'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>


                            {{-- CREATE QUOTATION --}}

                            <li>

                                <a
                                    href="{{ url('/quotations/create') }}"
                                    class="tenant-page-link"
                                    data-page-pin="quotations"
                                    data-page-locked="{{ $tenantPinEnabled('quotations') ? '1' : '0' }}"
                                >

                                    Create Quotation

                                    @if($tenantPinEnabled('quotations'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>


                            {{-- ================================================= --}}
                            {{-- REMINDERS --}}
                            {{-- ================================================= --}}

                            <li>

                                <a
                                    href="{{ route('bookings.reminders') }}"
                                    class="tenant-page-link"
                                    data-page-pin="bookings"
                                    data-page-locked="{{ $tenantPinEnabled('bookings') ? '1' : '0' }}"
                                >

                                    Booking Reminders

                                    @if($tenantPinEnabled('bookings'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>

                        </ul>

                    </li>

                @endcan


                {{-- ========================================================= --}}
                {{-- BILLING & FINANCE --}}
                {{-- ========================================================= --}}

                <li class="nav-small-cap">

                    --- BILLING & FINANCE

                </li>


                {{-- ================= INVOICES ================= --}}

                @can('invoices.view')

                    <li>

                        <a class="has-arrow waves-effect waves-dark"
                           href="javascript:void(0)"
                           aria-expanded="false">

                            <span class="hide-menu">

                                Invoices

                            </span>

                        </a>

                        <ul aria-expanded="false" class="collapse">


                            {{-- ALL INVOICES --}}

                            <li>

                                <a
                                    href="{{ url('/invoices') }}"
                                    class="tenant-page-link"
                                    data-page-pin="invoices"
                                    data-page-locked="{{ $tenantPinEnabled('invoices') ? '1' : '0' }}"
                                >

                                    All Invoices

                                    @if($tenantPinEnabled('invoices'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>


                            {{-- CREATE INVOICE --}}

                            @can('invoices.create')

                                <li>

                                    <a
                                        href="{{ url('/invoices/create') }}"
                                        class="tenant-page-link"
                                        data-page-pin="invoices"
                                        data-page-locked="{{ $tenantPinEnabled('invoices') ? '1' : '0' }}"
                                    >

                                        Create Invoice

                                        @if($tenantPinEnabled('invoices'))

                                            <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                        @endif

                                    </a>

                                </li>

                            @endcan


                            {{-- PAID --}}

                            <li>

                                <a
                                    href="{{ url('/invoices/paid') }}"
                                    class="tenant-page-link"
                                    data-page-pin="invoices"
                                    data-page-locked="{{ $tenantPinEnabled('invoices') ? '1' : '0' }}"
                                >

                                    Paid Invoices

                                    @if($tenantPinEnabled('invoices'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>


                            {{-- UNPAID --}}

                            <li>

                                <a
                                    href="{{ url('/invoices/unpaid') }}"
                                    class="tenant-page-link"
                                    data-page-pin="invoices"
                                    data-page-locked="{{ $tenantPinEnabled('invoices') ? '1' : '0' }}"
                                >

                                    Unpaid Invoices

                                    @if($tenantPinEnabled('invoices'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>

                        </ul>

                    </li>

                @endcan


                {{-- ================= FINANCE ================= --}}

                @can('finance.view')

                    <li>

                        <a class="has-arrow waves-effect waves-dark"
                           href="javascript:void(0)"
                           aria-expanded="false">

                            <span class="hide-menu">

                                Finance

                            </span>

                        </a>

                        <ul aria-expanded="false" class="collapse">

                            {{-- EXPENSES --}}

                            <li>

                                <a
                                    href="{{ url('/expenses/create') }}"
                                    class="tenant-page-link"
                                    data-page-pin="expenses"
                                    data-page-locked="{{ $tenantPinEnabled('expenses') ? '1' : '0' }}"
                                >

                                    Expenses

                                    @if($tenantPinEnabled('expenses'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>

                            {{-- VENDOR PAYMENTS --}}

                            <li>

                                <a
                                    href="{{ url('/finance/vendor-payments/create') }}"
                                    class="tenant-page-link"
                                    data-page-pin="vendor_payments"
                                    data-page-locked="{{ $tenantPinEnabled('vendor_payments') ? '1' : '0' }}"
                                >

                                    Vendor Payments

                                    @if($tenantPinEnabled('vendor_payments'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>

                            {{-- BOOKING PAYMENTS --}}

                            <li>

                                <a
                                    href="{{ url('/finance/booking-payments/create') }}"
                                    class="tenant-page-link"
                                    data-page-pin="booking_payments"
                                    data-page-locked="{{ $tenantPinEnabled('booking_payments') ? '1' : '0' }}"
                                >

                                    Booking Payments

                                    @if($tenantPinEnabled('booking_payments'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>

                        </ul>

                    </li>

                @endcan


                {{-- ================= ACCOUNTS ================= --}}

                @can('accounts.view')

                    <li>

                        <a class="has-arrow waves-effect waves-dark"
                           href="javascript:void(0)"
                           aria-expanded="false">

                            <span class="hide-menu">

                                Accounts & Ledger

                            </span>

                        </a>

                        <ul aria-expanded="false" class="collapse">

                            <li>

                                <a href="{{ url('/ledger') }}">

                                    General Ledger

                                </a>

                            </li>


                            <li>

                                <a href="{{ url('/ledger/customer') }}">

                                    Customer Ledger

                                </a>

                            </li>


                            <li>

                                <a href="{{ url('/ledger/vendor') }}">

                                    Supplier Ledger

                                </a>

                            </li>

                        </ul>

                    </li>

                @endcan


                {{-- ========================================================= --}}
                {{-- ROLES & PERMISSIONS --}}
                {{-- ========================================================= --}}

                @can('roles.view')

                    <li>

                        <a class="has-arrow waves-effect waves-dark"
                           href="javascript:void(0)"
                           aria-expanded="false">

                            <span class="hide-menu">

                                Roles & Permissions

                            </span>

                        </a>

                        <ul aria-expanded="false" class="collapse">


                            {{-- ROLES --}}

                            <li>

                                <a
                                    href="{{ route('tenant.roles.index') }}"
                                    class="tenant-page-link"
                                    data-page-pin="roles"
                                    data-page-locked="{{ $tenantPinEnabled('roles') ? '1' : '0' }}"
                                >

                                    All Roles

                                    @if($tenantPinEnabled('roles'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>


                            <li>

                                <a
                                    href="{{ route('tenant.roles.create') }}"
                                    class="tenant-page-link"
                                    data-page-pin="roles"
                                    data-page-locked="{{ $tenantPinEnabled('roles') ? '1' : '0' }}"
                                >

                                    Create Role

                                    @if($tenantPinEnabled('roles'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>


                            {{-- STAFF --}}

                            <li>

                                <a
                                    href="{{ route('tenant.staff.index') }}"
                                    class="tenant-page-link"
                                    data-page-pin="staff"
                                    data-page-locked="{{ $tenantPinEnabled('staff') ? '1' : '0' }}"
                                >

                                    All Staff

                                    @if($tenantPinEnabled('staff'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>


                            <li>

                                <a
                                    href="{{ route('tenant.staff.create') }}"
                                    class="tenant-page-link"
                                    data-page-pin="staff"
                                    data-page-locked="{{ $tenantPinEnabled('staff') ? '1' : '0' }}"
                                >

                                    Add Staff Member

                                    @if($tenantPinEnabled('staff'))

                                        <i class="bi bi-lock-fill tenant-pin-lock"></i>

                                    @endif

                                </a>

                            </li>

                        </ul>

                    </li>

                @endcan


                {{-- ========================================================= --}}
                {{-- REPORTS --}}
                {{-- ========================================================= --}}

                @can('reports.view')

                    <li class="nav-small-cap">

                        --- REPORTS & ANALYTICS

                    </li>


                    <li>

                        <a class="has-arrow waves-effect waves-dark"
                           href="javascript:void(0)"
                           aria-expanded="false">

                            <span class="hide-menu">

                                Reports

                            </span>

                        </a>

                        <ul aria-expanded="false" class="collapse">

                            <li>

                                <a href="{{ url('/reports/sales') }}">

                                    Sales Report

                                </a>

                            </li>


                            <li>

                                <a href="{{ url('/reports/orders') }}">

                                    Orders Report

                                </a>

                            </li>


                            <li>

                                <a href="{{ url('/reports/revenue') }}">

                                    Revenue Report

                                </a>

                            </li>


                            <li>

                                <a href="{{ url('/reports/expenses') }}">

                                    Expense Report

                                </a>

                            </li>


                            <li>

                                <a href="{{ url('/reports/payments') }}">

                                    Payment Report

                                </a>

                            </li>


                            <li>

                                <a href="{{ url('/reports/profit') }}">

                                    Profit & Loss

                                </a>

                            </li>

                        </ul>

                    </li>

                @endcan


                {{-- ========================================================= --}}
                {{-- NOTIFICATIONS --}}
                {{-- ========================================================= --}}

                @can('vendor.view')

                    <li class="nav-small-cap">

                        --- REPORTS & ANALYTICS

                    </li>


                    <li>

                        <a class="has-arrow waves-effect waves-dark"
                           href="javascript:void(0)"
                           aria-expanded="false">

                            <span class="hide-menu">

                                Vendors

                            </span>

                        </a>

                        <ul aria-expanded="false" class="collapse">

                            <li>

                                <a href="{{ url('/tenant/vendors') }}">

                                    View Vendors

                                </a>

                            </li>


                            <li>

                                <a href="{{ url('/tenant/vendors/create') }}">

                                    Create Vendor

                                </a>

                            </li>

                        </ul>

                    </li>

                @endcan

            </ul>

        </nav>

    </div>

</aside>

        {{-- ========================================================= --}}
{{-- TENANT PAGE PIN MODAL --}}
{{-- ========================================================= --}}

<div class="modal fade"
     id="tenantPagePinModal"
     tabindex="-1"
     aria-labelledby="tenantPagePinModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title"
                    id="tenantPagePinModalLabel">

                    <i class="bi bi-shield-lock-fill me-2"></i>

                    Page Security

                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>

            </div>


            <div class="modal-body text-center p-4">

                <div class="pin-security-icon">

                    <i class="bi bi-lock-fill"></i>

                </div>


                <h5 class="mb-2">
                    2-PIN Security Required
                </h5>


                <p class="text-muted mb-4">

                    This page is protected by Tenant 2-PIN security.
                    Please enter your PIN to continue.

                </p>


                <div id="tenantPagePinError"
                     class="alert alert-danger text-start">

                    <i class="bi bi-exclamation-triangle-fill me-1"></i>

                    <span id="tenantPagePinErrorText">
                        Wrong PIN. Please try again.
                    </span>

                </div>


                <div class="mb-3">

                    <input
                        type="password"
                        id="tenantPagePinInput"
                        class="form-control"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        maxlength="6"
                        placeholder="••••••"
                    >

                </div>


                <button type="button"
                        id="tenantPagePinVerifyBtn"
                        class="btn btn-info text-white w-100">

                    <i class="bi bi-unlock-fill me-1"></i>

                    Verify & Continue

                </button>


                <button type="button"
                        class="btn btn-light w-100 mt-2"
                        data-bs-dismiss="modal">

                    Cancel

                </button>

            </div>

        </div>

    </div>

</div>

        {{-- ===================================================== --}}
{{-- BOOKING FORM --}}
{{-- ===================================================== --}}

<div class="offcanvas offcanvas-end"
     tabindex="-1"
     id="settingsSidebar"
     aria-labelledby="settingsSidebarLabel">


    {{-- ================================================= --}}
    {{-- HEADER --}}
    {{-- ================================================= --}}

    <div class="offcanvas-header">

        <h5 class="offcanvas-title"
            id="settingsSidebarLabel">

            <i class="ti-calendar me-2"></i>

            Create Booking

        </h5>


        <button type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="offcanvas"
                aria-label="Close">

        </button>

    </div>


    {{-- ================================================= --}}
    {{-- BODY --}}
    {{-- ================================================= --}}

    <div class="offcanvas-body">


        {{-- ================================================= --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ================================================= --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="fa fa-check-circle me-1"></i>

                {{ session('success') }}


                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">

                </button>

            </div>

        @endif


        {{-- ================================================= --}}
        {{-- ERRORS --}}
        {{-- ================================================= --}}

        @if($errors->any())

            <div class="alert alert-danger alert-dismissible fade show">

                <strong>

                    <i class="ti-alert me-1"></i>

                    Please fix the following errors:

                </strong>


                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>


                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">

                </button>

            </div>

        @endif


        {{-- ================================================= --}}
        {{-- BOOKING FORM --}}
        {{-- ================================================= --}}

        <form action="{{ route('bookings.store') }}"
              method="POST"
              id="bookingForm">

            @csrf


            {{-- ================================================= --}}
            {{-- EVENT & LAWN INFORMATION --}}
            {{-- ================================================= --}}

            <h4 class="section-title">

                <i class="ti-layout-grid2 me-1"></i>

                Event & Lawn Information

            </h4>

            <hr>


            <div class="row">


                {{-- ================================================= --}}
                {{-- LAWN TYPE --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="form-group mb-3">

                        <label for="lawn_type"
                               class="form-label">

                            Lawn Type

                            <span class="text-danger">*</span>

                        </label>


                        <select name="lawn_type"
                                id="lawn_type"
                                class="form-select @error('lawn_type') is-invalid @enderror"
                                required>

                            <option value="">

                                -- Select Lawn Type --

                            </option>


                            @foreach($lawnTypes ?? [] as $lawn)

                                <option value="{{ $lawn->id }}"
                                    {{ old('lawn_type') == $lawn->id ? 'selected' : '' }}>

                                    {{ $lawn->lawn_type }}

                                </option>

                            @endforeach

                        </select>


                        @error('lawn_type')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- DAY / NIGHT --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="form-group mb-3">

                        <label for="booking_time"
                               class="form-label">

                            Select Day / Night

                            <span class="text-danger">*</span>

                        </label>


                        <select name="booking_time"
                                id="booking_time"
                                class="form-select @error('booking_time') is-invalid @enderror"
                                required>

                            <option value="">

                                -- Select Day / Night --

                            </option>


                            <option value="day"
                                {{ old('booking_time') == 'day' ? 'selected' : '' }}>

                                Day

                            </option>


                            <option value="night"
                                {{ old('booking_time') == 'night' ? 'selected' : '' }}>

                                Night

                            </option>

                        </select>


                        @error('booking_time')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- BOOKING DATE --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="form-group mb-3">

                        <label for="booking_date"
                               class="form-label">

                            Select Date

                            <span class="text-danger">*</span>

                        </label>


                        <input type="date"
                               name="booking_date"
                               id="booking_date"
                               class="form-control @error('booking_date') is-invalid @enderror"
                               value="{{ old('booking_date') }}"
                               min="{{ date('Y-m-d') }}"
                               required>


                        <div id="availabilityMessage"></div>


                        @error('booking_date')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- EVENT INFORMATION --}}
            {{-- ================================================= --}}

            <div class="row">


                {{-- ================================================= --}}
                {{-- EVENT TYPE --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <div class="form-group mb-3">

                        <label for="event_type"
                               class="form-label">

                            Select Event

                            <span class="text-danger">*</span>

                        </label>


                        <select name="event_type"
                                id="event_type"
                                class="form-select @error('event_type') is-invalid @enderror"
                                required>

                            <option value="">

                                -- Select Event --

                            </option>


                            @foreach([

                                'barat' =>
                                    'Barat',

                                'valeema' =>
                                    'Valeema',

                                'mehendi' =>
                                    'Mehendi',

                                'normal_dawat' =>
                                    'Normal Dawat',

                                'birthday' =>
                                    'Birthday',

                                'haqeeqa' =>
                                    'Haqeeqa',

                                'milad' =>
                                    'Milad',

                                'roza_kushai' =>
                                    'Roza Kushai',

                                'nama_e_taraweh' =>
                                    'Nama-e-Taraweh'

                            ] as $value => $label)

                                <option value="{{ $value }}"
                                    {{ old('event_type') == $value ? 'selected' : '' }}>

                                    {{ $label }}

                                </option>

                            @endforeach

                        </select>


                        @error('event_type')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- NUMBER OF GUESTS --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <div class="form-group mb-3">

                        <label for="number_of_guests"
                               class="form-label">

                            Number of Guests

                            <span class="text-danger">*</span>

                        </label>


                        <input type="number"
                               name="number_of_guests"
                               id="number_of_guests"
                               class="form-control @error('number_of_guests') is-invalid @enderror"
                               value="{{ old('number_of_guests') }}"
                               min="1"
                               placeholder="Enter number of guests"
                               required>


                        @error('number_of_guests')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- CUSTOMER INFORMATION --}}
            {{-- ================================================= --}}

            <h4 class="section-title">

                <i class="ti-user me-1"></i>

                Customer Information

            </h4>

            <hr>


            <div class="row">


                {{-- ================================================= --}}
                {{-- CUSTOMER NAME --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="form-group mb-3">

                        <label for="customer_name"
                               class="form-label">

                            Customer Name

                            <span class="text-danger">*</span>

                        </label>


                        <input type="text"
                               name="customer_name"
                               id="customer_name"
                               class="form-control @error('customer_name') is-invalid @enderror"
                               value="{{ old('customer_name') }}"
                               placeholder="Enter customer name"
                               required>


                        @error('customer_name')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- CUSTOMER NUMBER --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="form-group mb-3">

                        <label for="customer_number"
                               class="form-label">

                            Customer Number

                            <span class="text-danger">*</span>

                        </label>


                        <input type="text"
                               name="customer_number"
                               id="customer_number"
                               class="form-control @error('customer_number') is-invalid @enderror"
                               value="{{ old('customer_number') }}"
                               placeholder="03XXXXXXXXX"
                               maxlength="11"
                               inputmode="numeric"
                               required>


                        @error('customer_number')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- CUSTOMER NIC --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="form-group mb-3">

                        <label for="customer_nic_number"
                               class="form-label">

                            Customer NIC Number

                            <span class="text-danger">*</span>

                        </label>


                        <input type="text"
                               name="customer_nic_number"
                               id="customer_nic_number"
                               class="form-control @error('customer_nic_number') is-invalid @enderror"
                               value="{{ old('customer_nic_number') }}"
                               placeholder="XXXXX-XXXXXXX-X"
                               maxlength="15"
                               inputmode="numeric"
                               required>


                        @error('customer_nic_number')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- EMAIL / ADDRESS --}}
            {{-- ================================================= --}}

            <div class="row">


                {{-- ================================================= --}}
                {{-- EMAIL --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <div class="form-group mb-3">

                        <label for="customer_email"
                               class="form-label">

                            Customer Email-Address

                            <span class="text-danger">*</span>

                        </label>


                        <input type="email"
                               name="customer_email"
                               id="customer_email"
                               class="form-control @error('customer_email') is-invalid @enderror"
                               value="{{ old('customer_email') }}"
                               placeholder="Enter customer email"
                               maxlength="100"
                               required>


                        @error('customer_email')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ADDRESS --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <div class="form-group mb-3">

                        <label for="customer_address"
                               class="form-label">

                            Customer Address

                            <span class="text-danger">*</span>

                        </label>


                        <textarea name="customer_address"
                                  id="customer_address"
                                  rows="1"
                                  class="form-control @error('customer_address') is-invalid @enderror"
                                  placeholder="Enter customer address"
                                  required>{{ old('customer_address') }}</textarea>


                        @error('customer_address')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- PAID SERVICES --}}
            {{-- ================================================= --}}

            <h4 class="section-title">

                <i class="ti-settings me-1"></i>

                Paid Services

            </h4>

            <hr>


            <div id="paidServicesContainer">


                <div class="service-row row align-items-end mb-3">


                    {{-- SERVICE --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Service

                        </label>


                        <select name="paid_services[0][service_id]"
                                class="form-select paid-service-select">

                            <option value="">

                                -- Select Paid Service --

                            </option>


                            @foreach($services ?? [] as $service)

                                @php

                                    $isFreeService =
                                        isset(
                                            $service->is_free
                                        )

                                        ? (bool)
                                            $service->is_free

                                        : (

                                            isset(
                                                $service->service_type
                                            )

                                            &&
                                            in_array(

                                                strtolower(
                                                    (string)
                                                    $service->service_type
                                                ),

                                                [
                                                    'free',
                                                    'free_service'
                                                ],

                                                true

                                            )

                                        );

                                @endphp


                                @if(!$isFreeService)

                                    <option value="{{ $service->id }}"
                                            data-price="{{ $service->amount }}">

                                        {{ $service->service_name }}

                                        -

                                        {{ number_format(
                                            $service->amount
                                        ) }}

                                    </option>

                                @endif

                            @endforeach

                        </select>

                    </div>


                    {{-- QUANTITY --}}
                    <div class="col-md-2">

                        <label class="form-label">

                            Quantity

                        </label>


                        <input type="number"
                               name="paid_services[0][quantity]"
                               class="form-control paid-service-quantity"
                               value="1"
                               min="1">

                    </div>


                    {{-- PRICE --}}
                    <div class="col-md-2">

                        <label class="form-label">

                            Price

                        </label>


                        <input type="number"
                               name="paid_services[0][price]"
                               class="form-control paid-service-price"
                               value="0.00"
                               step="0.01"
                               readonly>

                    </div>


                    {{-- REMOVE --}}
                    <div class="col-md-2">

                        <button type="button"
                                class="btn btn-danger remove-paid-service">

                            Remove

                        </button>

                    </div>

                </div>

            </div>


            <button type="button"
                    id="addPaidService"
                    class="btn btn-info text-white">

                + Add Paid Service

            </button>


            {{-- ================================================= --}}
            {{-- FREE SERVICES --}}
            {{-- ================================================= --}}

            <h4 class="section-title mt-4">

                <i class="ti-gift me-1"></i>

                Free Services

            </h4>

            <hr>


            <div id="freeServicesContainer">


                <div class="free-service-row row align-items-end mb-3">


                    {{-- FREE SERVICE --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Free Service

                        </label>


                        <select name="free_services[0][service_id]"
                                class="form-select free-service-select">

                            <option value="">

                                -- Select Free Service --

                            </option>


                            @foreach($freeServices ?? [] as $freeService)

                                <option value="{{ $freeService->id }}">

                                    {{ $freeService->service_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- QUANTITY --}}
                    <div class="col-md-2">

                        <label class="form-label">

                            Quantity

                        </label>


                        <input type="number"
                               name="free_services[0][quantity]"
                               class="form-control free-service-quantity"
                               value="1"
                               min="1">

                    </div>


                    {{-- REMOVE --}}
                    <div class="col-md-2">

                        <button type="button"
                                class="btn btn-danger remove-free-service">

                            Remove

                        </button>

                    </div>

                </div>

            </div>


            <button type="button"
                    id="addFreeService"
                    class="btn btn-info text-white">

                + Add Free Service

            </button>


            {{-- ================================================= --}}
            {{-- AMOUNT & TAX INFORMATION --}}
            {{-- ================================================= --}}

            <h4 class="section-title mt-4">

                <i class="ti-wallet me-1"></i>

                Amount & Tax Information

            </h4>

            <hr>


            <div class="row">


                {{-- ================================================= --}}
                {{-- TOTAL SERVICES --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="form-group mb-3">

                        <label for="total_services_amount"
                               class="form-label">

                            Total Services Amount

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                {{ optional($settings)->currency ?? 'PKR' }}

                            </span>


                            <input
                                type="number"
                                name="total_services_amount"
                                id="total_services_amount"
                                class="form-control"
                                value="{{ old('total_services_amount', 0) }}"
                                step="0.01"
                                readonly
                            >

                        </div>


                        <small class="text-muted">

                            Total of all selected paid services.

                        </small>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- BANQUET BOOKING --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="form-group mb-3">

                        <label for="booking_amount"
                               class="form-label">

                            Banquet Booking Amount

                            <span class="text-danger">*</span>

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                {{ optional($settings)->currency ?? 'PKR' }}

                            </span>


                            <input
                                type="number"
                                name="booking_amount"
                                id="booking_amount"
                                class="form-control @error('booking_amount') is-invalid @enderror"
                                value="{{ old('booking_amount', 0) }}"
                                min="0"
                                step="0.01"
                                placeholder="Enter full banquet booking amount"
                                required
                            >

                        </div>


                        @error('booking_amount')

                            <div class="invalid-feedback d-block">

                                {{ $message }}

                            </div>

                        @enderror


                        <small class="text-muted">

                            Enter the complete agreed amount for this banquet booking.

                        </small>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TAX --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <div class="form-group mb-3">

                        <label class="form-label">

                            Tax

                        </label>


                        @if(optional($settings)->tax_enabled)

                            <div class="alert mb-0">

                                <strong>

                                    {{ optional($settings)->tax_name ?: 'Tax' }}

                                </strong>


                                <span class="float-end">

                                    {{ number_format(
                                        $taxPercent
                                        ?? optional($settings)->tax_percentage
                                        ?? 0,
                                        2
                                    ) }}%

                                </span>

                            </div>


                            <input type="hidden"
                                   id="tax_percentage"
                                   name="tax_percentage"
                                   value="{{ $taxPercent ?? optional($settings)->tax_percentage ?? 0 }}">

                        @else

                            <div class="alert alert-secondary mb-0">

                                Tax is disabled from Tenant Settings.

                            </div>


                            <input type="hidden"
                                   id="tax_percentage"
                                   name="tax_percentage"
                                   value="0">

                        @endif

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- AMOUNT CALCULATION --}}
            {{-- ================================================= --}}

            <div class="row">


                {{-- ================================================= --}}
                {{-- TOTAL AMOUNT --}}
                {{-- ================================================= --}}

                <div class="col-md-3">

                    <div class="form-group mb-3">

                        <label class="form-label">

                            Total Banquet Amount

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                {{ optional($settings)->currency ?? 'PKR' }}

                            </span>


                            <input type="number"
                                   name="total_amount"
                                   id="total_amount"
                                   class="form-control"
                                   value="{{ old('total_amount', 0) }}"
                                   step="0.01"
                                   readonly>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- TAX AMOUNT --}}
                {{-- ================================================= --}}

                <div class="col-md-3">

                    <div class="form-group mb-3">

                        <label class="form-label">

                            Tax Amount

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                {{ optional($settings)->currency ?? 'PKR' }}

                            </span>


                            <input type="number"
                                   name="tax_amount"
                                   id="tax_amount"
                                   class="form-control"
                                   value="{{ old('tax_amount', 0) }}"
                                   step="0.01"
                                   readonly>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- DISCOUNT --}}
                {{-- ================================================= --}}

                <div class="col-md-3">

                    <div class="form-group mb-3">

                        <label for="discount"
                               class="form-label">

                            Discount

                            <span class="text-danger">*</span>

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                {{ optional($settings)->currency ?? 'PKR' }}

                            </span>


                            <input
                                type="number"
                                name="discount"
                                id="discount"
                                class="form-control @error('discount') is-invalid @enderror"
                                value="{{ old('discount', 0) }}"
                                min="0"
                                step="0.01"
                                placeholder="Enter discount amount"
                                required
                            >

                        </div>


                        @error('discount')

                            <div class="invalid-feedback d-block">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- GRAND TOTAL --}}
                {{-- ================================================= --}}

                <div class="col-md-3">

                    <div class="form-group mb-3">

                        <label class="form-label">

                            Grand Total

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                {{ optional($settings)->currency ?? 'PKR' }}

                            </span>


                            <input type="number"
                                   name="grand_total"
                                   id="grand_total"
                                   class="form-control fw-bold"
                                   value="{{ old('grand_total', 0) }}"
                                   step="0.01"
                                   readonly>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- REMAINING AMOUNT --}}
            {{-- ================================================= --}}

            <div class="row">

                <div class="col-md-12">

                    <div class="form-group mb-3">

                        <label class="form-label">

                            Remaining Amount

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                {{ optional($settings)->currency ?? 'PKR' }}

                            </span>


                            <input type="number"
                                   name="remaining_amount"
                                   id="remaining_amount"
                                   class="form-control fw-bold text-danger"
                                   value="{{ old('remaining_amount', 0) }}"
                                   step="0.01"
                                   readonly>

                        </div>


                        <small class="text-muted">

                            The full invoice amount remains outstanding until an actual payment is received.

                        </small>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- INVOICE PAYMENT REQUIREMENT --}}
            {{-- ================================================= --}}

            <h4 class="section-title mt-4">

                <i class="ti-credit-card me-1"></i>

                Invoice Payment Requirement

            </h4>

            <hr>


            <div class="alert alert-info">

                <i class="fa fa-info-circle me-1"></i>

                <strong>Pay Amount:</strong>

                Yahan enter ki gayi amount invoice par
                <strong>Pay Amount</strong> ke naam se show hogi.

                Is ka matlab hai ke customer ko ye amount pay karni hai.

                Ye actual received payment nahi hai, is liye booking create
                karte waqt <strong>booking_payments</strong> table mein koi
                payment entry create nahi hogi.

            </div>


            <div class="row">

                {{-- ================================================= --}}
                {{-- PAYMENT METHOD --}}
                {{-- ================================================= --}}

                <div class="col-md-3">

                    <div class="form-group mb-3">

                        <label for="payment_method"
                               class="form-label">

                            Preferred Payment Method

                        </label>


                        <select name="payment_method"
                                id="payment_method"
                                class="form-select @error('payment_method') is-invalid @enderror">

                            <option value="">

                                -- Select Payment Method --

                            </option>


                            <option value="cash"
                                {{ old('payment_method', optional($settings)->payment_method ?? '') === 'cash' ? 'selected' : '' }}>

                                Cash

                            </option>


                            <option value="bank_transfer"
                                {{ old('payment_method', optional($settings)->payment_method ?? '') === 'bank_transfer' ? 'selected' : '' }}>

                                Bank Transfer

                            </option>


                            <option value="card"
                                {{ old('payment_method', optional($settings)->payment_method ?? '') === 'card' ? 'selected' : '' }}>

                                Card

                            </option>


                            <option value="online"
                                {{ old('payment_method', optional($settings)->payment_method ?? '') === 'online' ? 'selected' : '' }}>

                                Online Payment

                            </option>


                            <option value="cheque"
                                {{ old('payment_method', optional($settings)->payment_method ?? '') === 'cheque' ? 'selected' : '' }}>

                                Cheque

                            </option>

                        </select>


                        @error('payment_method')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror


                        <small class="text-muted">

                            Optional: preferred payment method only.

                        </small>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PAY AMOUNT --}}
                {{-- ================================================= --}}

                <div class="col-md-3">

                    <div class="form-group mb-3">

                        <label for="advance_amount"
                               class="form-label">

                            Pay Amount

                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                {{ optional($settings)->currency ?? 'PKR' }}

                            </span>


                            <input
                                type="number"
                                name="advance_amount"
                                id="advance_amount"
                                class="form-control @error('advance_amount') is-invalid @enderror"
                                value="{{ old('advance_amount', 0) }}"
                                min="0"
                                step="0.01"
                                placeholder="Enter amount customer must pay"
                            >

                        </div>


                        @error('advance_amount')

                            <div class="invalid-feedback d-block">

                                {{ $message }}

                            </div>

                        @enderror


                        <small class="text-muted">

                            Required payment amount shown on the invoice.
                            This is not actual received payment.

                        </small>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- EXPECTED PAYMENT DATE --}}
                {{-- ================================================= --}}

                <div class="col-md-3">

                    <div class="form-group mb-3">

                        <label for="paid_at"
                               class="form-label">

                            Expected Payment Date

                        </label>


                        <input
                            type="date"
                            name="paid_at"
                            id="paid_at"
                            class="form-control @error('paid_at') is-invalid @enderror"
                            value="{{ old('paid_at') }}"
                        >


                        @error('paid_at')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror


                        <small class="text-muted">

                            Optional planning information.

                        </small>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- REFERENCE --}}
                {{-- ================================================= --}}

                <div class="col-md-3">

                    <div class="form-group mb-3">

                        <label for="transaction_reference"
                               class="form-label">

                            Payment Reference No.

                        </label>


                        <input
                            type="text"
                            name="transaction_reference"
                            id="transaction_reference"
                            class="form-control"
                            value="{{ old('transaction_reference') }}"
                            placeholder="Optional reference number"
                        >


                        <small class="text-muted">

                            Optional reference for the payment requirement.

                        </small>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- PAYMENT REQUIREMENT NOTES --}}
            {{-- ================================================= --}}

            <div class="row">

                <div class="col-md-12">

                    <div class="form-group mb-3">

                        <label for="payment_notes"
                               class="form-label">

                            Payment Requirement Notes

                        </label>


                        <textarea
                            name="payment_notes"
                            id="payment_notes"
                            rows="3"
                            class="form-control"
                            placeholder="Enter notes about the required payment..."
                        >{{ old('payment_notes') }}</textarea>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- PAYMENT SUMMARY --}}
            {{-- ================================================= --}}

            <div class="row mt-3">

                {{-- ================================================= --}}
                {{-- GRAND TOTAL --}}
                {{-- ================================================= --}}

                <div class="col-md-3">

                    <div class="card summary-card bg-light border">

                        <div class="card-body">

                            <h6 class="text-muted mb-1">

                                Grand Total

                            </h6>


                            <h4 class="mb-0">

                                {{ optional($settings)->currency ?? 'PKR' }}

                                <span id="payment_grand_total_display">

                                    0.00

                                </span>

                            </h4>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- PAY AMOUNT --}}
                {{-- ================================================= --}}

                <div class="col-md-3">

                    <div class="card summary-card bg-light border">

                        <div class="card-body">

                            <h6 class="text-muted mb-1">

                                Pay Amount

                            </h6>


                            <h4 class="mb-0 text-warning">

                                {{ optional($settings)->currency ?? 'PKR' }}

                                <span id="payment_pay_amount_display">

                                    0.00

                                </span>

                            </h4>


                            <small class="text-muted">

                                Amount customer is required to pay.

                            </small>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- ACTUAL PAID --}}
                {{-- ================================================= --}}

                <div class="col-md-3">

                    <div class="card summary-card bg-light border">

                        <div class="card-body">

                            <h6 class="text-muted mb-1">

                                Paid Amount

                            </h6>


                            <h4 class="mb-0 text-success">

                                {{ optional($settings)->currency ?? 'PKR' }}

                                <span id="payment_paid_display">

                                    0.00

                                </span>

                            </h4>


                            <small class="text-muted">

                                Actual payment received.

                            </small>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- REMAINING --}}
                {{-- ================================================= --}}

                <div class="col-md-3">

                    <div class="card summary-card bg-light border">

                        <div class="card-body">

                            <h6 class="text-muted mb-1">

                                Remaining Amount

                            </h6>


                            <h4 class="mb-0 text-danger">

                                {{ optional($settings)->currency ?? 'PKR' }}

                                <span id="payment_remaining_display">

                                    0.00

                                </span>

                            </h4>


                            <small class="text-muted">

                                Full outstanding invoice balance.

                            </small>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- ACTION BUTTONS --}}
            {{-- ================================================= --}}

            <div class="form-actions border-top pt-3 mt-4">


                <button type="submit"
                        id="submitBooking"
                        class="btn btn-success text-white me-2">

                    <i class="fa fa-check me-1"></i>

                    Save Booking

                </button>


                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="offcanvas">

                    <i class="ti-close me-1"></i>

                    Cancel

                </button>

            </div>


        </form>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const form =
        document.getElementById('bookingForm');


    if (!form) {
        return;
    }


    const dateInput =
        document.getElementById('booking_date');


    const lawnInput =
        document.getElementById('lawn_type');


    const timeInput =
        document.getElementById('booking_time');


    const messageBox =
        document.getElementById('availabilityMessage');


    const paidServicesContainer =
        document.getElementById(
            'paidServicesContainer'
        );


    const freeServicesContainer =
        document.getElementById(
            'freeServicesContainer'
        );


    const addPaidServiceBtn =
        document.getElementById(
            'addPaidService'
        );


    const addFreeServiceBtn =
        document.getElementById(
            'addFreeService'
        );


    const bookingAmount =
        document.getElementById(
            'booking_amount'
        );


    const totalServicesAmount =
        document.getElementById(
            'total_services_amount'
        );


    const discountInput =
        document.getElementById(
            'discount'
        );


    /*
    |--------------------------------------------------------------------------
    | PAY AMOUNT
    |--------------------------------------------------------------------------
    |
    | advance_amount is kept as the form field name for backend compatibility.
    | Its meaning here is Pay Amount, not actual received payment.
    |
    | Is amount ki booking creation par booking_payments mein entry nahi hogi.
    |
    */

    const payAmount =
        document.getElementById(
            'advance_amount'
        );


    const totalAmount =
        document.getElementById(
            'total_amount'
        );


    const taxAmount =
        document.getElementById(
            'tax_amount'
        );


    const grandTotal =
        document.getElementById(
            'grand_total'
        );


    const remainingAmount =
        document.getElementById(
            'remaining_amount'
        );


    const taxPercentage =
        parseFloat(

            document.getElementById(
                'tax_percentage'
            )?.value || 0

        );


    const paymentGrandTotalDisplay =
        document.getElementById(
            'payment_grand_total_display'
        );


    const paymentPayAmountDisplay =
        document.getElementById(
            'payment_pay_amount_display'
        );


    const paymentPaidDisplay =
        document.getElementById(
            'payment_paid_display'
        );


    const paymentRemainingDisplay =
        document.getElementById(
            'payment_remaining_display'
        );


    /*
    |--------------------------------------------------------------------------
    | STATE
    |--------------------------------------------------------------------------
    */

    let checkingAvailability =
        false;


    let slotAvailable =
        false;


    let paidServiceIndex =
        1;


    let freeServiceIndex =
        1;


    /*
    |--------------------------------------------------------------------------
    | FORM ENABLE / DISABLE
    |--------------------------------------------------------------------------
    */

    function setFormDisabled(
        disabled
    ) {


        form.querySelectorAll(
            'input, select, textarea, button'
        ).forEach(function (element) {


            if (
                element.type === 'hidden'
            ) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | AVAILABILITY FIELDS ALWAYS ENABLED
            |--------------------------------------------------------------------------
            */

            const alwaysEnabled = [

                'booking_date',

                'lawn_type',

                'booking_time'

            ];


            if (
                alwaysEnabled.includes(
                    element.id
                )
            ) {

                return;

            }


            element.disabled =
                disabled;

        });

    }


    /*
    |--------------------------------------------------------------------------
    | RESET AVAILABILITY
    |--------------------------------------------------------------------------
    */

    function resetAvailability() {


        slotAvailable =
            false;


        if (
            messageBox
        ) {

            messageBox.innerHTML =
                '';

        }


        setFormDisabled(
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK AVAILABILITY
    |--------------------------------------------------------------------------
    */

    async function checkAvailability() {


        const bookingDate =
            dateInput?.value || '';


        const lawnType =
            lawnInput?.value || '';


        const bookingTime =
            timeInput?.value || '';


        slotAvailable =
            false;


        if (
            messageBox
        ) {

            messageBox.innerHTML =
                '';

        }


        /*
        |--------------------------------------------------------------------------
        | REQUIRED
        |--------------------------------------------------------------------------
        */

        if (

            !bookingDate
            ||
            !lawnType
            ||
            !bookingTime

        ) {

            setFormDisabled(
                true
            );

            return;

        }


        checkingAvailability =
            true;


        setFormDisabled(
            true
        );


        if (
            messageBox
        ) {

            messageBox.innerHTML = `

                <div class="alert alert-info">

                    <i class="fa fa-spinner fa-spin me-1"></i>

                    Checking booking availability...

                </div>

            `;

        }


        try {


            const url =
                new URL(

                    "{{ route('bookings.checkAvailability') }}",

                    window.location.origin

                );


            url.searchParams.set(
                'booking_date',
                bookingDate
            );


            url.searchParams.set(
                'lawn_type',
                lawnType
            );


            url.searchParams.set(
                'booking_time',
                bookingTime
            );


            const response =
                await fetch(

                    url,

                    {

                        method:
                            'GET',

                        headers: {

                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'

                        }

                    }

                );


            const data =
                await response.json();


            if (
                !response.ok
            ) {

                throw new Error(

                    data.message
                    ||
                    'Unable to check booking availability.'

                );

            }


            /*
            |--------------------------------------------------------------------------
            | AVAILABLE
            |--------------------------------------------------------------------------
            */

            if (
                data.available === true
            ) {


                slotAvailable =
                    true;


                if (
                    messageBox
                ) {


                    messageBox.innerHTML = `

                        <div class="alert alert-success"
                             id="availabilitySuccessMessage">

                            <i class="fa fa-check-circle me-1"></i>

                            ${
                                data.message
                                ||
                                'This lawn is available for booking.'
                            }

                        </div>

                    `;


                    setTimeout(
                        function () {


                            const successMessage =
                                document.getElementById(
                                    'availabilitySuccessMessage'
                                );


                            if (
                                successMessage
                            ) {


                                successMessage.style.transition =
                                    'opacity 0.5s ease';


                                successMessage.style.opacity =
                                    '0';


                                setTimeout(
                                    function () {

                                        successMessage.remove();

                                    },
                                    500
                                );

                            }

                        },
                        4000
                    );

                }


                setFormDisabled(
                    false
                );


                calculateTotals();

            }


            /*
            |--------------------------------------------------------------------------
            | NOT AVAILABLE
            |--------------------------------------------------------------------------
            */

            else {


                slotAvailable =
                    false;


                if (
                    messageBox
                ) {


                    messageBox.innerHTML = `

                        <div class="alert alert-danger">

                            <i class="fa fa-times-circle me-1"></i>

                            ${
                                data.message
                                ||
                                'This lawn is already booked for the selected date and time.'
                            }

                        </div>

                    `;

                }


                setFormDisabled(
                    true
                );

            }


        } catch (
            error
        ) {


            console.error(

                'Availability Error:',

                error

            );


            slotAvailable =
                false;


            if (
                messageBox
            ) {


                messageBox.innerHTML = `

                    <div class="alert alert-danger">

                        <i class="fa fa-exclamation-triangle me-1"></i>

                        ${
                            error.message
                            ||
                            'Something went wrong while checking availability.'
                        }

                    </div>

                `;

            }


            setFormDisabled(
                true
            );

        } finally {

            checkingAvailability =
                false;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | AVAILABILITY EVENTS
    |--------------------------------------------------------------------------
    */

    dateInput?.addEventListener(
        'change',
        function () {

            resetAvailability();

            checkAvailability();

        }
    );


    lawnInput?.addEventListener(
        'change',
        function () {

            resetAvailability();

            checkAvailability();

        }
    );


    timeInput?.addEventListener(
        'change',
        function () {

            resetAvailability();

            checkAvailability();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PAID SERVICE PRICE
    |--------------------------------------------------------------------------
    */

    function updatePaidServicePrice(
        row
    ) {


        if (!row) {
            return;
        }


        const serviceSelect =
            row.querySelector(
                '.paid-service-select'
            );


        const quantityInput =
            row.querySelector(
                '.paid-service-quantity'
            );


        const priceInput =
            row.querySelector(
                '.paid-service-price'
            );


        if (

            !serviceSelect
            ||
            !quantityInput
            ||
            !priceInput

        ) {

            return;

        }


        const selectedOption =
            serviceSelect.options[
                serviceSelect.selectedIndex
            ];


        const servicePrice =
            parseFloat(
                selectedOption?.dataset?.price || 0
            );


        const quantity =
            parseFloat(
                quantityInput.value || 0
            );


        priceInput.value =
            (
                servicePrice
                *
                quantity
            ).toFixed(2);

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE ALL SERVICE PRICES
    |--------------------------------------------------------------------------
    */

    function updateAllPaidServicePrices() {


        paidServicesContainer
            ?.querySelectorAll(
                '.service-row'
            )
            .forEach(
                updatePaidServicePrice
            );

    }


    /*
    |--------------------------------------------------------------------------
    | SERVICE SELECT CHANGE
    |--------------------------------------------------------------------------
    */

    paidServicesContainer?.addEventListener(
        'change',
        function (event) {


            if (

                !event.target.classList.contains(
                    'paid-service-select'
                )

            ) {

                return;

            }


            updatePaidServicePrice(

                event.target.closest(
                    '.service-row'
                )

            );


            calculateTotals();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SERVICE QUANTITY CHANGE
    |--------------------------------------------------------------------------
    */

    paidServicesContainer?.addEventListener(
        'input',
        function (event) {


            if (

                !event.target.classList.contains(
                    'paid-service-quantity'
                )

            ) {

                return;

            }


            updatePaidServicePrice(

                event.target.closest(
                    '.service-row'
                )

            );


            calculateTotals();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ADD PAID SERVICE
    |--------------------------------------------------------------------------
    */

    addPaidServiceBtn?.addEventListener(
        'click',
        function () {


            const firstRow =
                paidServicesContainer
                    ?.querySelector(
                        '.service-row'
                    );


            if (!firstRow) {
                return;
            }


            const newRow =
                firstRow.cloneNode(
                    true
                );


            const serviceSelect =
                newRow.querySelector(
                    '.paid-service-select'
                );


            if (
                serviceSelect
            ) {


                serviceSelect.name =
                    `paid_services[${paidServiceIndex}][service_id]`;


                serviceSelect.value =
                    '';

            }


            const quantityInput =
                newRow.querySelector(
                    '.paid-service-quantity'
                );


            if (
                quantityInput
            ) {


                quantityInput.name =
                    `paid_services[${paidServiceIndex}][quantity]`;


                quantityInput.value =
                    '1';

            }


            const priceInput =
                newRow.querySelector(
                    '.paid-service-price'
                );


            if (
                priceInput
            ) {


                priceInput.name =
                    `paid_services[${paidServiceIndex}][price]`;


                priceInput.value =
                    '0.00';

            }


            paidServicesContainer
                .appendChild(
                    newRow
                );


            paidServiceIndex++;


            calculateTotals();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | REMOVE PAID SERVICE
    |--------------------------------------------------------------------------
    */

    paidServicesContainer?.addEventListener(
        'click',
        function (event) {


            const removeButton =
                event.target.closest(
                    '.remove-paid-service'
                );


            if (!removeButton) {
                return;
            }


            const rows =
                paidServicesContainer
                    .querySelectorAll(
                        '.service-row'
                    );


            if (
                rows.length <= 1
            ) {


                const row =
                    rows[0];


                if (
                    row
                ) {


                    const serviceSelect =
                        row.querySelector(
                            '.paid-service-select'
                        );


                    const quantityInput =
                        row.querySelector(
                            '.paid-service-quantity'
                        );


                    if (
                        serviceSelect
                    ) {

                        serviceSelect.value =
                            '';

                    }


                    if (
                        quantityInput
                    ) {

                        quantityInput.value =
                            '1';

                    }


                    updatePaidServicePrice(
                        row
                    );

                }


                calculateTotals();


                return;

            }


            removeButton
                .closest(
                    '.service-row'
                )
                ?.remove();


            calculateTotals();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ADD FREE SERVICE
    |--------------------------------------------------------------------------
    */

    addFreeServiceBtn?.addEventListener(
        'click',
        function () {


            const firstRow =
                freeServicesContainer
                    ?.querySelector(
                        '.free-service-row'
                    );


            if (!firstRow) {
                return;
            }


            const newRow =
                firstRow.cloneNode(
                    true
                );


            const serviceSelect =
                newRow.querySelector(
                    '.free-service-select'
                );


            if (
                serviceSelect
            ) {


                serviceSelect.name =
                    `free_services[${freeServiceIndex}][service_id]`;


                serviceSelect.value =
                    '';

            }


            const quantityInput =
                newRow.querySelector(
                    '.free-service-quantity'
                );


            if (
                quantityInput
            ) {


                quantityInput.name =
                    `free_services[${freeServiceIndex}][quantity]`;


                quantityInput.value =
                    '1';

            }


            freeServicesContainer
                .appendChild(
                    newRow
                );


            freeServiceIndex++;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | REMOVE FREE SERVICE
    |--------------------------------------------------------------------------
    */

    freeServicesContainer?.addEventListener(
        'click',
        function (event) {


            const removeButton =
                event.target.closest(
                    '.remove-free-service'
                );


            if (!removeButton) {
                return;
            }


            const rows =
                freeServicesContainer
                    .querySelectorAll(
                        '.free-service-row'
                    );


            if (
                rows.length <= 1
            ) {


                const select =
                    rows[0]
                        ?.querySelector(
                            '.free-service-select'
                        );


                if (
                    select
                ) {

                    select.value =
                        '';

                }


                return;

            }


            removeButton
                .closest(
                    '.free-service-row'
                )
                ?.remove();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CALCULATE TOTALS
    |--------------------------------------------------------------------------
    */

    function calculateTotals() {


        /*
        |--------------------------------------------------------------------------
        | SERVICES TOTAL
        |--------------------------------------------------------------------------
        */

        let servicesTotal =
            0;


        paidServicesContainer
            ?.querySelectorAll(
                '.service-row'
            )
            .forEach(
                function (row) {


                    const price =
                        parseFloat(

                            row.querySelector(

                                '.paid-service-price'

                            )?.value || 0

                        );


                    /*
                    |--------------------------------------------------------------------------
                    | paid-service-price already contains:
                    | service price × quantity
                    |--------------------------------------------------------------------------
                    */

                    servicesTotal +=
                        price;

                }
            );


        /*
        |--------------------------------------------------------------------------
        | SERVICES FIELD
        |--------------------------------------------------------------------------
        */

        if (
            totalServicesAmount
        ) {

            totalServicesAmount.value =
                servicesTotal.toFixed(2);

        }


        /*
        |--------------------------------------------------------------------------
        | BANQUET AMOUNT
        |--------------------------------------------------------------------------
        */

        const banquetAmount =
            parseFloat(
                bookingAmount?.value || 0
            );


        /*
        |--------------------------------------------------------------------------
        | SUBTOTAL
        |--------------------------------------------------------------------------
        */

        const subtotal =
            Math.max(

                banquetAmount
                +
                servicesTotal,

                0

            );


        /*
        |--------------------------------------------------------------------------
        | DISCOUNT
        |--------------------------------------------------------------------------
        */

        let discount =
            parseFloat(
                discountInput?.value || 0
            );


        if (
            isNaN(discount)
        ) {

            discount =
                0;

        }


        discount =
            Math.max(
                discount,
                0
            );


        if (
            discount > subtotal
        ) {


            discount =
                subtotal;


            if (
                discountInput
            ) {

                discountInput.value =
                    discount.toFixed(2);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | TAXABLE AMOUNT
        |--------------------------------------------------------------------------
        */

        const taxableAmount =
            Math.max(

                subtotal
                -
                discount,

                0

            );


        /*
        |--------------------------------------------------------------------------
        | TAX
        |--------------------------------------------------------------------------
        */

        const tax =
            taxableAmount
            *
            (
                taxPercentage
                /
                100
            );


        /*
        |--------------------------------------------------------------------------
        | GRAND TOTAL
        |--------------------------------------------------------------------------
        */

        const finalGrandTotal =
            Math.max(

                taxableAmount
                +
                tax,

                0

            );


        /*
        |--------------------------------------------------------------------------
        | PAY AMOUNT
        |--------------------------------------------------------------------------
        |
        | Pay Amount = amount customer is required to pay.
        | It is NOT actual received payment.
        |
        */

        let payAmountValue =
            parseFloat(
                payAmount?.value || 0
            );


        if (
            isNaN(payAmountValue)
        ) {

            payAmountValue =
                0;

        }


        payAmountValue =
            Math.max(
                payAmountValue,
                0
            );


        /*
        |--------------------------------------------------------------------------
        | PAY AMOUNT CANNOT EXCEED GRAND TOTAL
        |--------------------------------------------------------------------------
        */

        if (
            payAmountValue > finalGrandTotal
        ) {

            payAmountValue =
                finalGrandTotal;


            if (
                payAmount
            ) {

                payAmount.value =
                    payAmountValue.toFixed(2);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | ACTUAL PAID AMOUNT
        |--------------------------------------------------------------------------
        |
        | Booking create par actual payment zero hai.
        |
        */

        const paidAmount =
            0;


        /*
        |--------------------------------------------------------------------------
        | ACTUAL REMAINING
        |--------------------------------------------------------------------------
        |
        | Pay Amount sirf required amount hai.
        | Payment receive nahi hui, isliye full grand total outstanding hai.
        |
        */

        const remaining =
            finalGrandTotal;


        /*
        |--------------------------------------------------------------------------
        | UPDATE HIDDEN/READONLY FIELDS
        |--------------------------------------------------------------------------
        */

        if (
            totalAmount
        ) {

            totalAmount.value =
                subtotal.toFixed(2);

        }


        if (
            taxAmount
        ) {

            taxAmount.value =
                tax.toFixed(2);

        }


        if (
            grandTotal
        ) {

            grandTotal.value =
                finalGrandTotal.toFixed(2);

        }


        if (
            remainingAmount
        ) {

            remainingAmount.value =
                remaining.toFixed(2);

        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT SUMMARY
        |--------------------------------------------------------------------------
        */

        if (
            paymentGrandTotalDisplay
        ) {

            paymentGrandTotalDisplay.textContent =
                finalGrandTotal.toFixed(2);

        }


        if (
            paymentPayAmountDisplay
        ) {

            paymentPayAmountDisplay.textContent =
                payAmountValue.toFixed(2);

        }


        if (
            paymentPaidDisplay
        ) {

            paymentPaidDisplay.textContent =
                paidAmount.toFixed(2);

        }


        if (
            paymentRemainingDisplay
        ) {

            paymentRemainingDisplay.textContent =
                remaining.toFixed(2);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | BOOKING AMOUNT
    |--------------------------------------------------------------------------
    */

    bookingAmount?.addEventListener(
        'input',
        calculateTotals
    );


    /*
    |--------------------------------------------------------------------------
    | DISCOUNT
    |--------------------------------------------------------------------------
    */

    discountInput?.addEventListener(
        'input',
        calculateTotals
    );


    /*
    |--------------------------------------------------------------------------
    | PAY AMOUNT
    |--------------------------------------------------------------------------
    */

    payAmount?.addEventListener(
        'input',
        calculateTotals
    );


    /*
    |--------------------------------------------------------------------------
    | PAYMENT METHOD
    |--------------------------------------------------------------------------
    */

    const paymentMethod =
        document.getElementById(
            'payment_method'
        );


    paymentMethod?.addEventListener(
        'change',
        function () {

            clearError(
                paymentMethod
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CNIC FORMAT
    |--------------------------------------------------------------------------
    */

    const customerNic =
        document.getElementById(
            'customer_nic_number'
        );


    customerNic?.addEventListener(
        'input',
        function () {


            let digits =
                this.value.replace(
                    /\D/g,
                    ''
                );


            digits =
                digits.substring(
                    0,
                    13
                );


            let formatted =
                '';


            /*
            |--------------------------------------------------------------------------
            | FIRST 5 DIGITS
            |--------------------------------------------------------------------------
            */

            formatted +=
                digits.substring(
                    0,
                    5
                );


            /*
            |--------------------------------------------------------------------------
            | NEXT 7 DIGITS
            |--------------------------------------------------------------------------
            */

            if (
                digits.length > 5
            ) {


                formatted +=
                    '-'
                    +
                    digits.substring(
                        5,
                        12
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | LAST DIGIT
            |--------------------------------------------------------------------------
            */

            if (
                digits.length > 12
            ) {


                formatted +=
                    '-'
                    +
                    digits.substring(
                        12,
                        13
                    );

            }


            this.value =
                formatted;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PHONE FORMAT
    |--------------------------------------------------------------------------
    */

    const customerNumber =
        document.getElementById(
            'customer_number'
        );


    customerNumber?.addEventListener(
        'input',
        function () {


            this.value =
                this.value.replace(
                    /\D/g,
                    ''
                );


            this.value =
                this.value.substring(
                    0,
                    11
                );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER NAME
    |--------------------------------------------------------------------------
    */

    const customerName =
        document.getElementById(
            'customer_name'
        );


    customerName?.addEventListener(
        'input',
        function () {


            this.value =
                this.value.replace(
                    /[^a-zA-ZÀ-ÿ\s.'-]/g,
                    ''
                );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ERROR HELPERS
    |--------------------------------------------------------------------------
    */

    function showError(
        input,
        message
    ) {


        if (!input) {
            return;
        }


        clearError(
            input
        );


        input.classList.add(
            'is-invalid'
        );


        let error =
            input.parentElement
                ?.querySelector(
                    '.js-error'
                );


        if (!error) {


            error =
                document.createElement(
                    'div'
                );


            error.className =
                'invalid-feedback js-error';


            input.parentElement
                ?.appendChild(
                    error
                );

        }


        error.textContent =
            message;

    }


    function clearError(
        input
    ) {


        if (!input) {
            return;
        }


        input.classList.remove(
            'is-invalid'
        );


        const error =
            input.parentElement
                ?.querySelector(
                    '.js-error'
                );


        if (
            error
        ) {

            error.remove();

        }

    }


    function clearAllErrors() {


        form
            .querySelectorAll(
                '.is-invalid'
            )
            .forEach(
                function (input) {

                    input.classList.remove(
                        'is-invalid'
                    );

                }
            );


        form
            .querySelectorAll(
                '.js-error'
            )
            .forEach(
                function (error) {

                    error.remove();

                }
            );

    }


    function numberValue(
        input
    ) {


        const value =
            parseFloat(
                input?.value
            );


        return isNaN(value)
            ? 0
            : value;

    }


    function isValidEmail(
        email
    ) {

        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/
            .test(
                email
            );

    }


    function isValidPhone(
        phone
    ) {

        return /^03[0-9]{9}$/
            .test(
                phone
            );

    }


    function isValidCNIC(
        cnic
    ) {

        return /^[0-9]{5}-[0-9]{7}-[0-9]{1}$/
            .test(
                cnic
            );

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE EMPTY SERVICE ROWS
    |--------------------------------------------------------------------------
    */

    function removeEmptyServiceRows() {


        paidServicesContainer
            ?.querySelectorAll(
                '.service-row'
            )
            .forEach(
                function (row) {


                    const service =
                        row.querySelector(
                            '.paid-service-select'
                        );


                    if (
                        !service?.value
                    ) {

                        row.remove();

                    }

                }
            );


        freeServicesContainer
            ?.querySelectorAll(
                '.free-service-row'
            )
            .forEach(
                function (row) {


                    const service =
                        row.querySelector(
                            '.free-service-select'
                        );


                    if (
                        !service?.value
                    ) {

                        row.remove();

                    }

                }
            );

    }


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        'submit',
        function (event) {


            clearAllErrors();


            let valid =
                true;


            /*
            |--------------------------------------------------------------------------
            | AVAILABILITY
            |--------------------------------------------------------------------------
            */

            if (
                checkingAvailability
            ) {


                event.preventDefault();


                alert(
                    'Please wait while booking availability is being checked.'
                );


                return;

            }


            if (

                !dateInput?.value
                ||
                !lawnInput?.value
                ||
                !timeInput?.value

            ) {


                event.preventDefault();


                alert(
                    'Please select Booking Date, Lawn Type and Day/Night first.'
                );


                return;

            }


            if (
                !slotAvailable
            ) {


                event.preventDefault();


                alert(
                    'Please check booking availability before submitting the form.'
                );


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | EVENT TYPE
            |--------------------------------------------------------------------------
            */

            const eventType =
                document.getElementById(
                    'event_type'
                );


            if (
                !eventType.value
            ) {


                showError(
                    eventType,
                    'Please select event type.'
                );


                valid =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | GUESTS
            |--------------------------------------------------------------------------
            */

            const guests =
                document.getElementById(
                    'number_of_guests'
                );


            if (

                !guests.value
                ||
                parseInt(
                    guests.value,
                    10
                ) < 1

            ) {


                showError(
                    guests,
                    'Number of guests must be at least 1.'
                );


                valid =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | CUSTOMER NAME
            |--------------------------------------------------------------------------
            */

            const name =
                customerName
                    .value
                    .trim();


            if (!name) {


                showError(
                    customerName,
                    'Customer name is required.'
                );


                valid =
                    false;


            } else if (
                name.length < 3
            ) {


                showError(
                    customerName,
                    'Customer name must contain at least 3 characters.'
                );


                valid =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | PHONE
            |--------------------------------------------------------------------------
            */

            const phone =
                customerNumber
                    .value
                    .trim();


            if (

                !phone
                ||
                !isValidPhone(
                    phone
                )

            ) {


                showError(
                    customerNumber,
                    'Please enter a valid Pakistani mobile number (03XXXXXXXXX).'
                );


                valid =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | CNIC
            |--------------------------------------------------------------------------
            */

            const cnic =
                customerNic
                    .value
                    .trim();


            if (

                !cnic
                ||
                !isValidCNIC(
                    cnic
                )

            ) {


                showError(
                    customerNic,
                    'Please enter CNIC in XXXXX-XXXXXXX-X format.'
                );


                valid =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | EMAIL
            |--------------------------------------------------------------------------
            */

            const customerEmail =
                document.getElementById(
                    'customer_email'
                );


            const email =
                customerEmail
                    .value
                    .trim();


            if (

                !email
                ||
                !isValidEmail(
                    email
                )

            ) {


                showError(
                    customerEmail,
                    'Please enter a valid email address.'
                );


                valid =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | ADDRESS
            |--------------------------------------------------------------------------
            */

            const customerAddress =
                document.getElementById(
                    'customer_address'
                );


            const address =
                customerAddress
                    .value
                    .trim();


            if (

                !address
                ||
                address.length < 5

            ) {


                showError(
                    customerAddress,
                    'Customer address must contain at least 5 characters.'
                );


                valid =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | PAYMENT METHOD
            |--------------------------------------------------------------------------
            */

            if (
                paymentMethod?.value
            ) {

                clearError(
                    paymentMethod
                );

            }


            /*
            |--------------------------------------------------------------------------
            | AMOUNTS
            |--------------------------------------------------------------------------
            */

            const booking =
                numberValue(
                    bookingAmount
                );


            const discount =
                numberValue(
                    discountInput
                );


            const payment =
                numberValue(
                    payAmount
                );


            /*
            |--------------------------------------------------------------------------
            | BOOKING AMOUNT
            |--------------------------------------------------------------------------
            */

            if (
                booking < 0
            ) {


                showError(
                    bookingAmount,
                    'Booking amount cannot be negative.'
                );


                valid =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | SERVICES TOTAL
            |--------------------------------------------------------------------------
            */

            let servicesTotal =
                0;


            paidServicesContainer
                ?.querySelectorAll(
                    '.service-row'
                )
                .forEach(
                    function (row) {


                        servicesTotal +=
                            numberValue(

                                row.querySelector(
                                    '.paid-service-price'
                                )

                            );

                    }
                );


            /*
            |--------------------------------------------------------------------------
            | SUBTOTAL
            |--------------------------------------------------------------------------
            */

            const subtotal =
                Math.max(

                    booking
                    +
                    servicesTotal,

                    0

                );


            /*
            |--------------------------------------------------------------------------
            | DISCOUNT
            |--------------------------------------------------------------------------
            */

            if (
                discount < 0
            ) {


                showError(
                    discountInput,
                    'Discount cannot be negative.'
                );


                valid =
                    false;

            }


            if (
                discount > subtotal
            ) {


                showError(
                    discountInput,
                    'Discount cannot be greater than total booking and services amount.'
                );


                valid =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | GRAND TOTAL
            |--------------------------------------------------------------------------
            */

            const taxable =
                Math.max(

                    subtotal
                    -
                    discount,

                    0

                );


            const calculatedTax =
                taxable
                *
                (
                    taxPercentage
                    /
                    100
                );


            const calculatedGrandTotal =
                taxable
                +
                calculatedTax;


            /*
            |--------------------------------------------------------------------------
            | INVOICE PAYMENT
            |--------------------------------------------------------------------------
            */

            if (
                payment < 0
            ) {


                showError(
                    payAmount,
                    'Pay amount cannot be negative.'
                );


                valid =
                    false;

            }


            if (
                payment > calculatedGrandTotal
            ) {


                showError(
                    payAmount,
                    'Pay amount cannot be greater than grand total.'
                );


                valid =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | EXPECTED PAYMENT DATE
            |--------------------------------------------------------------------------
            |
            | Optional only. It does not represent an actual received payment.
            |
            */


            /*
            |--------------------------------------------------------------------------
            | REMOVE EMPTY SERVICE ROWS
            |--------------------------------------------------------------------------
            */

            removeEmptyServiceRows();


            /*
            |--------------------------------------------------------------------------
            | PAID SERVICE VALIDATION
            |--------------------------------------------------------------------------
            */

            const paidServiceRows =
                paidServicesContainer
                    ?.querySelectorAll(
                        '.service-row'
                    )
                ||
                [];


            paidServiceRows.forEach(
                function (row) {


                    const service =
                        row.querySelector(
                            '.paid-service-select'
                        );


                    const quantity =
                        row.querySelector(
                            '.paid-service-quantity'
                        );


                    if (
                        !service?.value
                    ) {


                        showError(
                            service,
                            'Please select a paid service.'
                        );


                        valid =
                            false;

                    }


                    if (

                        !quantity?.value
                        ||
                        parseInt(
                            quantity.value,
                            10
                        ) < 1

                    ) {


                        showError(
                            quantity,
                            'Quantity must be at least 1.'
                        );


                        valid =
                            false;

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | FREE SERVICE VALIDATION
            |--------------------------------------------------------------------------
            */

            const freeServiceRows =
                freeServicesContainer
                    ?.querySelectorAll(
                        '.free-service-row'
                    )
                ||
                [];


            freeServiceRows.forEach(
                function (row) {


                    const service =
                        row.querySelector(
                            '.free-service-select'
                        );


                    const quantity =
                        row.querySelector(
                            '.free-service-quantity'
                        );


                    if (
                        !service?.value
                    ) {


                        showError(
                            service,
                            'Please select a free service.'
                        );


                        valid =
                            false;

                    }


                    if (

                        !quantity?.value
                        ||
                        parseInt(
                            quantity.value,
                            10
                        ) < 1

                    ) {


                        showError(
                            quantity,
                            'Free service quantity must be at least 1.'
                        );


                        valid =
                            false;

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | INVALID
            |--------------------------------------------------------------------------
            */

            if (!valid) {


                event.preventDefault();


                const firstError =
                    form.querySelector(
                        '.is-invalid'
                    );


                if (
                    firstError
                ) {


                    firstError.scrollIntoView({

                        behavior:
                            'smooth',

                        block:
                            'center'

                    });


                    firstError.focus();

                }


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | PREVENT DOUBLE SUBMIT
            |--------------------------------------------------------------------------
            */

            const submitButton =
                document.getElementById(
                    'submitBooking'
                );


            if (
                submitButton
            ) {


                submitButton.disabled =
                    true;


                submitButton.innerHTML =

                    '<i class="fa fa-spinner fa-spin me-1"></i> Saving Booking...';

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL AVAILABILITY
    |--------------------------------------------------------------------------
    */

    if (

        dateInput?.value
        &&
        lawnInput?.value
        &&
        timeInput?.value

    ) {


        checkAvailability();

    } else {


        setFormDisabled(
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | INITIAL PRICE/TOTAL CALCULATION
    |--------------------------------------------------------------------------
    */

    updateAllPaidServicePrices();

    calculateTotals();


    /*
    |--------------------------------------------------------------------------
    | TENANT NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    const notificationMessages =
        document.getElementById(
            'tenantNotificationMessages'
        );


    const notificationBell =
        document.getElementById(
            'tenantNotificationBell'
        );


    if (
        notificationMessages
    ) {


        fetch(

            '{{ route('tenant.notifications') }}',

            {

                headers: {

                    'Accept':
                        'application/json',

                    'X-Requested-With':
                        'XMLHttpRequest'

                }

            }

        )
        .then(
            response =>
                response.json()
        )
        .then(
            data => {


                if (
                    data.count > 0
                ) {


                    if (
                        notificationBell
                    ) {


                        notificationBell.classList.add(
                            'show-count'
                        );


                        notificationBell.innerHTML =

                            '<span class="badge bg-danger rounded-circle" style="font-size: 8px; padding: 4px; min-width: 8px; height: 8px;"></span>';

                    }


                    let html =
                        '';


                    data.messages.forEach(
                        function (msg) {


                            html += `

                                <a href="${msg.url}"
                                   class="d-flex align-items-center p-2 border-bottom">

                                    <div class="btn btn-primary btn-circle text-white me-2">

                                        <i class="ti-email"></i>

                                    </div>


                                    <div class="mail-contnet">

                                        <h5>

                                            ${msg.sender_name}

                                        </h5>


                                        <span class="mail-desc">

                                            ${msg.message}

                                        </span>


                                        <small class="text-muted">

                                            ${msg.time}

                                        </small>

                                    </div>

                                </a>

                            `;

                        }
                    );


                    if (
                        html
                    ) {

                        notificationMessages.innerHTML =
                            html;

                    }

                } else {


                    notificationMessages.innerHTML = `

                        <a href="{{ url('/tenant/chat') }}">

                            <div class="btn btn-primary btn-circle text-white">

                                <i class="ti-email"></i>

                            </div>


                            <div class="mail-contnet">

                                <h5>

                                    No new messages

                                </h5>


                                <span class="mail-desc">

                                    Check your chat for conversations

                                </span>

                            </div>

                        </a>

                    `;

                }

            }
        )
        .catch(
            error => {


                console.error(

                    'Error loading notifications:',

                    error

                );

            }
        );

    }

});
</script>