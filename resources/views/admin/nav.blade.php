@php
    /*
    |--------------------------------------------------------------------------
    | ADMIN PAGE PIN STATUS
    |--------------------------------------------------------------------------
    |
    | Used by sidebar links to decide whether to show the PIN popup.
    |
    */
    $adminPagePins = \App\Models\AdminPagePin::where(
        'user_id',
        auth()->id()
    )
    ->get()
    ->keyBy('page_key');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Elite Admin Dashboard">
    <meta name="author" content="">

    <link rel="icon"
          type="image/png"
          sizes="16x16"
          href="{{ asset('assets/images/favicon.png') }}">

    <title>Elite Admin Dashboard</title>

    <link rel="stylesheet"
          href="{{ asset('assets/node_modules/morrisjs/morris.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/node_modules/toast-master/css/jquery.toast.css') }}">

    {{-- Main CSS --}}
    <link rel="stylesheet"
          href="{{ asset('assets/dist/css/style.min.css') }}">

    {{-- Dashboard CSS --}}
    <link rel="stylesheet"
          href="{{ asset('assets/dist/css/pages/dashboard1.css') }}">

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

        .offcanvas-header {
            background-color: #e46a75;
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN PAGE PIN MODAL
        |--------------------------------------------------------------------------
        */

        #adminPagePinModal .modal-content {
            border: 0;
            border-radius: 10px;
            overflow: hidden;
        }

        #adminPagePinModal .modal-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        #adminPagePinModal .pin-icon {
            font-size: 42px;
            line-height: 1;
        }

        #adminPagePinInput {
            letter-spacing: 8px;
            font-size: 24px;
            font-weight: 600;
        }

        .admin-page-locked-icon {
            margin-left: 6px;
            font-size: 12px;
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

                    <a class="navbar-brand admin-page-link"
                       href="{{ route('admin.dashboard') }}"
                       data-page-pin="dashboard"
                       data-page-locked="{{ ($adminPagePins['dashboard']?->enabled ?? false) ? '1' : '0' }}">

                        <span class="hidden-sm-down">
                            {{ auth()->user()->name }}
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

                                <i class="ti-bell"></i>

                                <div class="notify"
                                     id="adminNotificationIndicator"
                                     style="display:none;">

                                    <span class="heartbit"></span>
                                    <span class="point"></span>

                                </div>

                            </a>

                            <div class="dropdown-menu dropdown-menu-end mailbox animated bounceInDown">

                                <ul>

                                    <li>
                                        <div class="drop-title"
                                             id="adminNotificationTitle">
                                            Notifications
                                        </div>
                                    </li>

                                    <li>
                                        <div class="message-center"
                                             id="adminNotificationList">

                                            <div class="text-center p-3 text-muted">
                                                Loading...
                                            </div>

                                        </div>
                                    </li>

                                    <li>
                                        <a class="nav-link text-center link"
                                           href="{{ route('admin.notifications') }}">
                                            <strong>
                                                View all notifications
                                            </strong>

                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </li>

                                </ul>

                            </div>

                        </li>

                        {{-- =========================================================
                             ADMIN MESSAGES NOTIFICATION DROPDOWN
                        ========================================================== --}}
                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle waves-effect waves-dark"
                               href="#"
                               id="messageDropdown"
                               data-bs-toggle="dropdown"
                               aria-haspopup="true"
                               aria-expanded="false">

                                <i class="icon-note"></i>

                                <div class="notify"
                                     id="messageNotify"
                                     style="display: none;">

                                    <span class="heartbit"></span>
                                    <span class="point"></span>

                                </div>

                            </a>

                            <div class="dropdown-menu mailbox dropdown-menu-end animated bounceInDown"
                                 aria-labelledby="messageDropdown"
                                 style="width: 360px;">

                                <ul>

                                    <li>
                                        <div class="drop-title"
                                             id="messageDropdownTitle">
                                            Messages
                                        </div>
                                    </li>

                                    <li>

                                        <div class="message-center"
                                             id="adminMessageNotifications">

                                            <div class="text-center text-muted py-4">

                                                <i class="fa fa-spinner fa-spin me-1"></i>

                                                Loading messages...

                                            </div>

                                        </div>

                                    </li>

                                    <li>

                                        <a class="nav-link text-center link"
                                           href="{{ route('admin.chat.index') }}">

                                            <strong>
                                                See all messages
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

                                <img src="{{ asset('assets/images/users/1.jpg') }}"
                                     alt="user">

                                <span class="hidden-md-down">
                                    {{ auth()->user()->name }}
                                </span>

                            </a>

                            <div class="dropdown-menu dropdown-menu-end animated flipInY">

                                <a href="{{ url('/admin/chat') }}"
                                   class="dropdown-item">

                                    <i class="ti-email"></i>
                                    Inbox

                                </a>

                                <div class="dropdown-divider"></div>

                                <a href="{{ route('admin.profile') }}"
                                   class="dropdown-item">

                                    <i class="ti-settings"></i>
                                    Account Setting

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
                               title="Settings">

                                <i class="ti-settings"></i>

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

                        {{-- User --}}
                        <li class="user-pro">

                            <a class="has-arrow waves-effect waves-dark"
                               href="javascript:void(0)"
                               aria-expanded="false">

                                <img src="{{ asset('assets/images/users/1.jpg') }}"
                                     alt="user-img"
                                     class="img-circle">

                                <span class="hide-menu">
                                    {{ auth()->user()->name }}
                                </span>

                            </a>

                            <ul aria-expanded="false" class="collapse">

                                <li>

                                    <a href="{{ route('admin.profile') }}">
                                        <i class="ti-user"></i>
                                        My Profile
                                    </a>

                                </li>

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

                        @can('dashboard.view')

                            <li>

                                <a class="waves-effect waves-dark admin-page-link"
                                   href="{{ route('admin.dashboard') }}"
                                   data-page-pin="dashboard"
                                   data-page-locked="{{ ($adminPagePins['dashboard']?->enabled ?? false) ? '1' : '0' }}">

                                    <i class="icon-speedometer"></i>

                                    <span class="hide-menu">
                                        Dashboard
                                    </span>

                                    @if($adminPagePins['dashboard']?->enabled ?? false)
                                        <i class="fa fa-lock admin-page-locked-icon"
                                           title="PIN protected"></i>
                                    @endif

                                </a>

                            </li>

                        @endcan


                        {{-- TENANT MANAGEMENT --}}
                        <li class="nav-small-cap">
                            --- TENANT MANAGEMENT
                        </li>


                        {{-- Tenants --}}
                        @can('users.view')

                            <li>

                                <a class="has-arrow waves-effect waves-dark"
                                   href="javascript:void(0)"
                                   aria-expanded="false">

                                    <i class="ti-home"></i>

                                    <span class="hide-menu">
                                        Tenants
                                    </span>

                                </a>

                                <ul aria-expanded="false" class="collapse">

                                    <li>

                                        <a class="admin-page-link"
                                           href="{{ route('admin.tenants.index') }}"
                                           data-page-pin="tenants"
                                           data-page-locked="{{ ($adminPagePins['tenants']?->enabled ?? false) ? '1' : '0' }}">

                                            All Tenants

                                            @if($adminPagePins['tenants']?->enabled ?? false)
                                                <i class="fa fa-lock admin-page-locked-icon"
                                                   title="PIN protected"></i>
                                            @endif

                                        </a>

                                    </li>

                                    @can('users.create')

                                        <li>

                                            <a class="admin-page-link"
                                               href="{{ route('admin.tenants.create') }}"
                                               data-page-pin="tenants"
                                               data-page-locked="{{ ($adminPagePins['tenants']?->enabled ?? false) ? '1' : '0' }}">

                                                Add Tenant

                                                @if($adminPagePins['tenants']?->enabled ?? false)
                                                    <i class="fa fa-lock admin-page-locked-icon"
                                                       title="PIN protected"></i>
                                                @endif

                                            </a>

                                        </li>

                                    @endcan

                                </ul>

                            </li>

                        @endcan


                        {{-- SUBSCRIPTIONS --}}
                        <li class="nav-small-cap">
                            --- SUBSCRIPTIONS
                        </li>

                        <li>

                            <a class="has-arrow waves-effect waves-dark"
                               href="javascript:void(0)"
                               aria-expanded="false">

                                <i class="ti-layers"></i>

                                <span class="hide-menu">
                                    Subscriptions
                                </span>

                            </a>

                            <ul aria-expanded="false" class="collapse">

                                <li>

                                    <a class="admin-page-link"
                                       href="{{ route('admin.subscriptions.index') }}"
                                       data-page-pin="subscriptions"
                                       data-page-locked="{{ ($adminPagePins['subscriptions']?->enabled ?? false) ? '1' : '0' }}">

                                        All Subscriptions

                                        @if($adminPagePins['subscriptions']?->enabled ?? false)
                                            <i class="fa fa-lock admin-page-locked-icon"
                                               title="PIN protected"></i>
                                        @endif

                                    </a>

                                </li>

                                <li>

                                    <a class="admin-page-link"
                                       href="{{ route('admin.subscriptions.create') }}"
                                       data-page-pin="subscriptions"
                                       data-page-locked="{{ ($adminPagePins['subscriptions']?->enabled ?? false) ? '1' : '0' }}">

                                        Add Subscription

                                        @if($adminPagePins['subscriptions']?->enabled ?? false)
                                            <i class="fa fa-lock admin-page-locked-icon"
                                               title="PIN protected"></i>
                                        @endif

                                    </a>

                                </li>

                            </ul>

                        </li>


                        {{-- BOOKINGS --}}
                        @can('bookings.view')

                            <li>

                                <a class="waves-effect waves-dark admin-page-link"
                                   href="{{ route('admin.bookings.index') }}"
                                   data-page-pin="bookings"
                                   data-page-locked="{{ ($adminPagePins['bookings']?->enabled ?? false) ? '1' : '0' }}">

                                    <i class="ti-calendar"></i>

                                    <span class="hide-menu">
                                        Bookings
                                    </span>

                                    @if($adminPagePins['bookings']?->enabled ?? false)
                                        <i class="fa fa-lock admin-page-locked-icon"
                                           title="PIN protected"></i>
                                    @endif

                                </a>

                            </li>

                        @endcan


                        {{-- PAYMENTS --}}
                        @can('payments.view')

                            <li>

                                <a class="waves-effect waves-dark admin-page-link"
                                   href="{{ route('admin.payments.index') }}"
                                   data-page-pin="payments"
                                   data-page-locked="{{ ($adminPagePins['payments']?->enabled ?? false) ? '1' : '0' }}">

                                    <i class="ti-money"></i>

                                    <span class="hide-menu">
                                        Payments
                                    </span>

                                    @if($adminPagePins['payments']?->enabled ?? false)
                                        <i class="fa fa-lock admin-page-locked-icon"
                                           title="PIN protected"></i>
                                    @endif

                                </a>

                            </li>

                        @endcan


                        {{-- ACCESS CONTROL --}}
                        @canany([
                            'roles.view',
                            'roles.create',
                            'roles.edit',
                            'roles.delete'
                        ])

                            <li class="nav-small-cap">
                                --- ACCESS CONTROL
                            </li>
{{-- =========================================================
     INVOICES
========================================================= --}}

<li class="nav-small-cap">
    --- INVOICES
</li>

<li>

    <a class="has-arrow waves-effect waves-dark"
       href="javascript:void(0)"
       aria-expanded="false">

        <i class="ti-receipt"></i>

        <span class="hide-menu">
            Invoices
        </span>

    </a>

    <ul aria-expanded="false" class="collapse">


        {{-- ALL INVOICES --}}

        <li>

            <a class="admin-page-link"
               href="{{ route('admin.invoices.index') }}"
               data-page-pin="payments"
               data-page-locked="{{ ($adminPagePins['payments']?->enabled ?? false) ? '1' : '0' }}">

                All Invoices

                @if($adminPagePins['payments']?->enabled ?? false)

                    <i class="fa fa-lock admin-page-locked-icon"
                       title="PIN protected"></i>

                @endif

            </a>

        </li>


        {{-- CREATE INVOICE --}}

        <li>

            <a class="admin-page-link"
               href="{{ route('admin.invoices.create') }}"
               data-page-pin="payments"
               data-page-locked="{{ ($adminPagePins['payments']?->enabled ?? false) ? '1' : '0' }}">

                Create Invoice

                @if($adminPagePins['payments']?->enabled ?? false)

                    <i class="fa fa-lock admin-page-locked-icon"
                       title="PIN protected"></i>

                @endif

            </a>

        </li>

    </ul>

</li>
                            <li>

                                <a class="has-arrow waves-effect waves-dark"
                                   href="javascript:void(0)"
                                   aria-expanded="false">

                                    <i class="ti-lock"></i>

                                    <span class="hide-menu">
                                        Roles & Permissions
                                    </span>

                                </a>

                                <ul aria-expanded="false" class="collapse">

                                    @can('roles.view')

                                        <li>

                                            <a class="admin-page-link"
                                               href="{{ route('admin.roles.index') }}"
                                               data-page-pin="roles"
                                               data-page-locked="{{ ($adminPagePins['roles']?->enabled ?? false) ? '1' : '0' }}">

                                                Roles

                                                @if($adminPagePins['roles']?->enabled ?? false)
                                                    <i class="fa fa-lock admin-page-locked-icon"
                                                       title="PIN protected"></i>
                                                @endif

                                            </a>

                                        </li>

                                    @endcan

                                    @can('roles.view')

                                        <li>

                                            <a href="{{ url('/permissions') }}">
                                                Permissions
                                            </a>

                                        </li>

                                    @endcan

                                </ul>

                            </li>

                        @endcanany


                        {{-- REPORTS --}}
                        @can('reports.view')

                            <li class="nav-small-cap">
                                --- REPORTS
                            </li>

                            <li>

                                <a class="has-arrow waves-effect waves-dark"
                                   href="javascript:void(0)"
                                   aria-expanded="false">

                                    <i class="ti-bar-chart"></i>

                                    <span class="hide-menu">
                                        Reports
                                    </span>

                                </a>

                                <ul aria-expanded="false" class="collapse">

                                    <li>
                                        <a href="{{ url('/reports/tenants') }}">
                                            Tenant Reports
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ url('/reports/subscriptions') }}">
                                            Subscription Reports
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ url('/reports/revenue') }}">
                                            Revenue Reports
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ url('/reports/bookings') }}">
                                            Booking Reports
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ url('/reports/payments') }}">
                                            Payment Reports
                                        </a>
                                    </li>

                                </ul>

                            </li>

                        @endcan


                        {{-- PROFILE --}}
                        <li>

                            <a class="waves-effect waves-dark"
                               href="{{ route('admin.profile') }}">

                                <i class="ti-user"></i>

                                <span class="hide-menu">
                                    My Profile
                                </span>

                            </a>

                        </li>

                    </ul>

                </nav>

            </div>

        </aside>


        {{-- ========================================================= --}}
        {{-- SETTINGS SIDEBAR --}}
        {{-- ========================================================= --}}

        <div class="offcanvas offcanvas-end"
             tabindex="-1"
             id="settingsSidebar"
             aria-labelledby="settingsSidebarLabel"
             style="width: 800px;">

            <div class="offcanvas-header text-white">

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

            <div class="offcanvas-body">

                @if (isset($errors) && $errors->any())

                    <div class="alert alert-danger">

                        <strong>
                            Please fix the following errors:
                        </strong>

                        <ul class="mb-0 mt-2">

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif

                @if(session('success'))

                    <div class="alert alert-success">

                        <i class="fa fa-check-circle me-1"></i>

                        {{ session('success') }}

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ADMIN PAGE PIN MODAL --}}
        {{-- ========================================================= --}}

        <div class="modal fade"
             id="adminPagePinModal"
             tabindex="-1"
             aria-labelledby="adminPagePinModalLabel"
             aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered"
                 style="max-width: 390px;">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title"
                            id="adminPagePinModalLabel">

                            <i class="fa fa-lock text-warning me-2"></i>

                            Enter Page PIN

                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                        </button>

                    </div>

                    <form method="POST"
                          action="{{ route('admin.profile.page-pins.verify') }}"
                          id="adminPagePinForm">

                        @csrf

                        <input type="hidden"
                               name="page_key"
                               id="adminPagePinPageKey">

                        <div class="modal-body">

                            <div class="text-center mb-3">

                                <div class="pin-icon text-warning mb-3">
                                    <i class="fa fa-shield"></i>
                                </div>

                                <h5 class="mb-1">
                                    Protected Page
                                </h5>

                                <p class="text-muted mb-0">
                                    Enter the PIN to open this page.
                                </p>

                            </div>

                            <div class="mb-3">

                                <label for="adminPagePinInput"
                                       class="form-label">

                                    PIN

                                </label>

                                <input type="password"
                                       name="pin"
                                       id="adminPagePinInput"
                                       class="form-control text-center"
                                       maxlength="6"
                                       minlength="4"
                                       inputmode="numeric"
                                       pattern="[0-9]{4,6}"
                                       autocomplete="off"
                                       placeholder="••••"
                                       required>

                            </div>

                            <div id="adminPagePinClientError"
                                 class="alert alert-danger py-2 d-none mb-0">

                                Please enter a 4 to 6 digit PIN.

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">

                                Cancel

                            </button>

                            <button type="submit"
                                    class="btn btn-warning text-dark">

                                <i class="fa fa-unlock-alt me-1"></i>

                                Unlock Page

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ADMIN PAGE PIN JAVASCRIPT --}}
        {{-- ========================================================= --}}

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const modalElement =
                    document.getElementById('adminPagePinModal');

                const form =
                    document.getElementById('adminPagePinForm');

                const pageKeyInput =
                    document.getElementById('adminPagePinPageKey');

                const pinInput =
                    document.getElementById('adminPagePinInput');

                const clientError =
                    document.getElementById('adminPagePinClientError');

                if (
                    !modalElement ||
                    !form ||
                    !pageKeyInput ||
                    !pinInput
                ) {
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | BOOTSTRAP MODAL
                |--------------------------------------------------------------------------
                */

                const pinModal =
                    bootstrap.Modal.getOrCreateInstance(
                        modalElement
                    );

                /*
                |--------------------------------------------------------------------------
                | PROTECTED PAGE LINKS
                |--------------------------------------------------------------------------
                */

                document
                    .querySelectorAll('.admin-page-link[data-page-pin]')
                    .forEach(function (link) {

                        link.addEventListener('click', function (event) {

                            const locked =
                                this.dataset.pageLocked === '1';

                            /*
                            |--------------------------------------------------------------------------
                            | UNLOCKED PAGE
                            |--------------------------------------------------------------------------
                            |
                            | Let the browser navigate normally.
                            |
                            */

                            if (!locked) {
                                return;
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | LOCKED PAGE
                            |--------------------------------------------------------------------------
                            |
                            | Stop navigation and show PIN popup.
                            |
                            */

                            event.preventDefault();

                            const pageKey =
                                this.dataset.pagePin;

                            pageKeyInput.value =
                                pageKey;

                            pinInput.value = '';

                            clientError.classList.add('d-none');

                            pinModal.show();

                            setTimeout(function () {
                                pinInput.focus();
                            }, 250);

                        });

                    });

                /*
                |--------------------------------------------------------------------------
                | PIN INPUT - ONLY NUMBERS
                |--------------------------------------------------------------------------
                */

                pinInput.addEventListener('input', function () {

                    this.value =
                        this.value
                            .replace(/\D/g, '')
                            .slice(0, 6);

                    clientError.classList.add('d-none');

                });

                /*
                |--------------------------------------------------------------------------
                | FORM VALIDATION
                |--------------------------------------------------------------------------
                */

                /*
|--------------------------------------------------------------------------
| PIN FORM SUBMIT
|--------------------------------------------------------------------------
*/

form.addEventListener('submit', function (event) {

    event.preventDefault();

    const pin =
        pinInput.value.trim();

    const pageKey =
        pageKeyInput.value;

    /*
    |--------------------------------------------------------------------------
    | GET ORIGINAL TARGET URL
    |--------------------------------------------------------------------------
    */

    const lockedLink =
        document.querySelector(
            '.admin-page-link[data-page-pin="' +
            CSS.escape(pageKey) +
            '"][data-page-locked="1"]'
        );

    const targetUrl =
        lockedLink
            ? lockedLink.href
            : null;

    /*
    |--------------------------------------------------------------------------
    | CLIENT VALIDATION
    |--------------------------------------------------------------------------
    */

    if (!/^\d{4,6}$/.test(pin)) {

        clientError.textContent =
            'PIN must contain 4 to 6 digits.';

        clientError.classList.remove('d-none');

        pinInput.focus();

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | HIDE OLD ERROR
    |--------------------------------------------------------------------------
    */

    clientError.classList.add('d-none');

    /*
    |--------------------------------------------------------------------------
    | DISABLE BUTTON
    |--------------------------------------------------------------------------
    */

    const submitButton =
        form.querySelector(
            'button[type="submit"]'
        );

    if (submitButton) {

        submitButton.disabled = true;

        submitButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1"
                  role="status"
                  aria-hidden="true"></span>

            Verifying...
        `;
    }

    /*
    |--------------------------------------------------------------------------
    | SEND PIN TO SERVER
    |--------------------------------------------------------------------------
    */

    fetch(form.action, {

        method: 'POST',

        headers: {

            'Content-Type':
                'application/x-www-form-urlencoded; charset=UTF-8',

            'Accept':
                'application/json',

            'X-Requested-With':
                'XMLHttpRequest',

            'X-CSRF-TOKEN':
                document
                    .querySelector(
                        'meta[name="csrf-token"]'
                    )
                    ?.getAttribute('content')

        },

        body: new URLSearchParams({

            _token:
                document
                    .querySelector(
                        'meta[name="csrf-token"]'
                    )
                    ?.getAttribute('content'),

            page_key:
                pageKey,

            pin:
                pin

        })

    })

    /*
    |--------------------------------------------------------------------------
    | RESPONSE
    |--------------------------------------------------------------------------
    */

    .then(function (response) {

        return response.json()
            .then(function (data) {

                return {
                    ok: response.ok,
                    status: response.status,
                    data: data
                };

            });

    })

    /*
    |--------------------------------------------------------------------------
    | HANDLE RESULT
    |--------------------------------------------------------------------------
    */

    .then(function (result) {

        /*
        |--------------------------------------------------------------------------
        | WRONG PIN / ERROR
        |--------------------------------------------------------------------------
        */

        if (!result.ok || !result.data.success) {

            clientError.textContent =
                result.data.message ||
                'Wrong PIN. Please try again.';

            clientError.classList.remove(
                'd-none'
            );

            pinInput.value = '';

            pinInput.focus();

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | CORRECT PIN
        |--------------------------------------------------------------------------
        */

        clientError.classList.add(
            'd-none'
        );

        /*
        |--------------------------------------------------------------------------
        | CLOSE MODAL
        |--------------------------------------------------------------------------
        */

        pinModal.hide();

        /*
        |--------------------------------------------------------------------------
        | OPEN REQUESTED PAGE
        |--------------------------------------------------------------------------
        */

        if (targetUrl) {

            window.location.href =
                targetUrl;

            return;

        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        window.location.href =
            "{{ route('admin.dashboard') }}";

    })

    /*
    |--------------------------------------------------------------------------
    | NETWORK ERROR
    |--------------------------------------------------------------------------
    */

    .catch(function (error) {

        console.error(
            'Page PIN verification error:',
            error
        );

        clientError.textContent =
            'Unable to verify PIN. Please try again.';

        clientError.classList.remove(
            'd-none'
        );

        pinInput.focus();

    })

    /*
    |--------------------------------------------------------------------------
    | RESTORE BUTTON
    |--------------------------------------------------------------------------
    */

    .finally(function () {

        if (submitButton) {

            submitButton.disabled = false;

            submitButton.innerHTML = `
                <i class="fa fa-unlock-alt me-1"></i>
                Unlock Page
            `;

        }

    });

});

                /*
                |--------------------------------------------------------------------------
                | ENTER KEY
                |--------------------------------------------------------------------------
                */

                pinInput.addEventListener('keydown', function (event) {

                    if (event.key === 'Enter') {

                        event.preventDefault();

                        form.requestSubmit();

                    }

                });

            });
        </script>


        {{-- ========================================================= --}}
        {{-- ADMIN CHAT / NOTIFICATIONS SCRIPTS --}}
        {{-- ========================================================= --}}

        <script>

            document.addEventListener('DOMContentLoaded', function () {

                const button =
                    document.getElementById('adminChatButton');

                const popup =
                    document.getElementById('adminChatPopup');

                const close =
                    document.getElementById('adminChatClose');

                const back =
                    document.getElementById('adminChatBack');

                const list =
                    document.getElementById('adminChatList');

                const listHeader =
                    document.getElementById('adminChatListHeader');

                const conversationHeader =
                    document.getElementById('adminConversationHeader');

                const conversation =
                    document.getElementById('adminConversation');

                const messagesBox =
                    document.getElementById('adminConversationMessages');

                const form =
                    document.getElementById('adminPopupChatForm');

                const input =
                    document.getElementById('adminPopupMessage');

                const sendButton =
                    document.getElementById('adminPopupSend');

                const tenantName =
                    document.getElementById('adminConversationTenant');

                const tenantAvatar =
                    document.getElementById('adminConversationAvatar');

                let currentConversationId = null;

                button?.addEventListener('click', function () {
                    popup?.classList.toggle('active');
                });

                close?.addEventListener('click', function () {
                    popup?.classList.remove('active');
                });

                document
                    .querySelectorAll('.admin-chat-item')
                    .forEach(function (item) {

                        item.addEventListener('click', function () {

                            const conversationId =
                                this.dataset.conversationId;

                            const name =
                                this.dataset.tenantName || 'Tenant';

                            openConversation(
                                conversationId,
                                name
                            );

                        });

                    });

                function openConversation(
                    conversationId,
                    name
                ) {

                    currentConversationId = conversationId;

                    if (tenantName) {
                        tenantName.textContent = name;
                    }

                    if (tenantAvatar) {
                        tenantAvatar.textContent =
                            name.charAt(0).toUpperCase();
                    }

                    listHeader?.classList.add('d-none');
                    list?.classList.add('d-none');
                    conversationHeader?.classList.remove('d-none');
                    conversation?.classList.remove('d-none');

                    if (!messagesBox) {
                        return;
                    }

                    messagesBox.innerHTML = `

                        <div class="admin-chat-loading">

                            <div class="spinner-border spinner-border-sm text-success"></div>

                            <span>
                                Loading messages...
                            </span>

                        </div>

                    `;

                    fetch(
                        `/admin/chat/conversation/${conversationId}/messages`,
                        {
                            method: 'GET',

                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    )
                    .then(function (response) {

                        if (!response.ok) {
                            throw new Error(
                                'Unable to load conversation.'
                            );
                        }

                        return response.json();

                    })
                    .then(function (data) {

                        renderMessages(
                            data.messages || []
                        );

                        scrollMessages();

                    })
                    .catch(function (error) {

                        console.error(error);

                        messagesBox.innerHTML = `

                            <div class="admin-chat-no-messages">

                                Unable to load messages.

                            </div>

                        `;

                    });

                }

                function renderMessages(messages) {

                    if (!messagesBox) {
                        return;
                    }

                    if (!messages.length) {

                        messagesBox.innerHTML = `

                            <div class="admin-chat-no-messages">

                                No messages yet.

                            </div>

                        `;

                        return;
                    }

                    messagesBox.innerHTML = '';

                    messages.forEach(function (message) {
                        addMessageToUI(message);
                    });

                }

                function addMessageToUI(message) {

                    if (!messagesBox) {
                        return;
                    }

                    const row =
                        document.createElement('div');

                    row.className =
                        'admin-message-row ' +
                        (
                            message.is_mine
                                ? 'admin-message-right'
                                : 'admin-message-left'
                        );

                    const wrapper =
                        document.createElement('div');

                    const bubble =
                        document.createElement('div');

                    bubble.className =
                        'admin-message ' +
                        (
                            message.is_mine
                                ? 'admin-message-own'
                                : 'admin-message-tenant'
                        );

                    bubble.textContent =
                        message.message;

                    const time =
                        document.createElement('div');

                    time.className =
                        'admin-message-time';

                    time.textContent =
                        message.time;

                    wrapper.appendChild(bubble);
                    wrapper.appendChild(time);
                    row.appendChild(wrapper);
                    messagesBox.appendChild(row);

                }

                form?.addEventListener('submit', function (event) {

                    event.preventDefault();

                    if (!currentConversationId || !input) {
                        return;
                    }

                    const message =
                        input.value.trim();

                    if (!message) {
                        return;
                    }

                    if (sendButton) {
                        sendButton.disabled = true;
                    }

                    fetch(
                        `/admin/chat/conversation/${currentConversationId}/send`,
                        {
                            method: 'POST',

                            headers: {
                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest',

                                'X-CSRF-TOKEN':
                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        ?.getAttribute('content')
                            },

                            body: JSON.stringify({
                                message: message
                            })
                        }
                    )
                    .then(function (response) {

                        if (!response.ok) {

                            return response.json()
                                .then(function (data) {

                                    throw new Error(
                                        data.message ||
                                        'Message could not be sent.'
                                    );

                                });

                        }

                        return response.json();

                    })
                    .then(function (data) {

                        if (data.success) {

                            const empty =
                                messagesBox?.querySelector(
                                    '.admin-chat-no-messages'
                                );

                            empty?.remove();

                            addMessageToUI(
                                data.message
                            );

                            input.value = '';

                            scrollMessages();

                        }

                    })
                    .catch(function (error) {

                        console.error(error);

                        alert(
                            error.message ||
                            'Message could not be sent.'
                        );

                    })
                    .finally(function () {

                        if (sendButton) {
                            sendButton.disabled = false;
                        }

                        input.focus();

                    });

                });

                back?.addEventListener('click', function () {

                    currentConversationId = null;

                    conversationHeader?.classList.add('d-none');
                    conversation?.classList.add('d-none');
                    listHeader?.classList.remove('d-none');
                    list?.classList.remove('d-none');

                    if (input) {
                        input.value = '';
                    }

                });

                input?.addEventListener('keydown', function (event) {

                    if (
                        event.key === 'Enter' &&
                        !event.shiftKey
                    ) {

                        event.preventDefault();

                        form?.requestSubmit();

                    }

                });

                function scrollMessages() {

                    if (!messagesBox) {
                        return;
                    }

                    setTimeout(function () {

                        messagesBox.scrollTop =
                            messagesBox.scrollHeight;

                    }, 50);

                }

            });

            document.addEventListener('DOMContentLoaded', function () {

                const notificationList =
                    document.getElementById(
                        'adminNotificationList'
                    );

                const notificationTitle =
                    document.getElementById(
                        'adminNotificationTitle'
                    );

                const notificationIndicator =
                    document.getElementById(
                        'adminNotificationIndicator'
                    );

                if (
                    !notificationList ||
                    !notificationTitle ||
                    !notificationIndicator
                ) {
                    return;
                }

                function escapeHtml(value) {

                    const div =
                        document.createElement('div');

                    div.textContent =
                        value ?? '';

                    return div.innerHTML;

                }

                function loadAdminNotifications() {

                    fetch(
                        "{{ route('admin.notifications') }}",
                        {
                            headers: {
                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            }
                        }
                    )
                    .then(response => {

                        if (!response.ok) {

                            throw new Error(
                                'Failed to load notifications'
                            );

                        }

                        return response.json();

                    })
                    .then(data => {

                        const count =
                            data.count || 0;

                        if (count > 0) {

                            notificationIndicator
                                .style
                                .display = 'block';

                            notificationTitle.innerHTML =
                                `You have ${count} new notification${count > 1 ? 's' : ''}`;

                        } else {

                            notificationIndicator
                                .style
                                .display = 'none';

                            notificationTitle.innerHTML =
                                'Notifications';

                        }

                        if (
                            !data.messages ||
                            data.messages.length === 0
                        ) {

                            notificationList.innerHTML = `

                                <div class="text-center p-4 text-muted">

                                    <i class="ti-bell"
                                       style="font-size:30px;">
                                    </i>

                                    <div class="mt-2">
                                        No new notifications
                                    </div>

                                </div>

                            `;

                            return;

                        }

                        notificationList.innerHTML = '';

                        data.messages.forEach(
                            notification => {

                                const item =
                                    document.createElement('a');

                                item.href =
                                    notification.url || '#';

                                item.className =
                                    'notification-item';

                                let icon =
                                    'ti-bell';

                                if (
                                    notification.type ===
                                    'booking_created'
                                ) {

                                    icon =
                                        'ti-calendar';

                                } else if (
                                    notification.type ===
                                    'booking_cancelled'
                                ) {

                                    icon =
                                        'ti-close';

                                } else if (
                                    notification.type ===
                                    'payment_received'
                                ) {

                                    icon =
                                        'ti-money';

                                }

                                item.innerHTML = `

                                    <div class="btn btn-info btn-circle text-white">

                                        <i class="${icon}"></i>

                                    </div>

                                    <div class="mail-contnet">

                                        <h5>
                                            ${escapeHtml(
                                                notification.title
                                            )}
                                        </h5>

                                        <span class="mail-desc">

                                            ${escapeHtml(
                                                notification.message
                                            )}

                                        </span>

                                        <span class="time">

                                            ${escapeHtml(
                                                notification.tenant_name
                                            )}
                                            -
                                            ${escapeHtml(
                                                notification.time
                                            )}

                                        </span>

                                    </div>

                                `;

                                notificationList
                                    .appendChild(item);

                            }
                        );

                    })
                    .catch(error => {

                        console.error(
                            'Admin notification error:',
                            error
                        );

                        notificationList.innerHTML = `

                            <div class="text-center p-3 text-danger">

                                Unable to load notifications.

                            </div>

                        `;

                    });

                }

                loadAdminNotifications();

                setInterval(
                    loadAdminNotifications,
                    5000
                );

            });

            document.addEventListener('DOMContentLoaded', function () {

                const messageList =
                    document.getElementById(
                        'adminMessageNotifications'
                    );

                const messageTitle =
                    document.getElementById(
                        'messageDropdownTitle'
                    );

                const messageNotify =
                    document.getElementById(
                        'messageNotify'
                    );

                if (
                    !messageList ||
                    !messageTitle ||
                    !messageNotify
                ) {
                    return;
                }

                function escapeHtml(value) {

                    const div =
                        document.createElement('div');

                    div.textContent =
                        value ?? '';

                    return div.innerHTML;

                }

                function loadAdminMessageNotifications() {

                    fetch(
                        "{{ route('admin.chat.notifications') }}",
                        {
                            method: 'GET',

                            headers: {
                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            },

                            credentials: 'same-origin'
                        }
                    )
                    .then(function (response) {

                        if (!response.ok) {

                            throw new Error(
                                'Failed to load messages.'
                            );

                        }

                        return response.json();

                    })
                    .then(function (data) {

                        const messages =
                            Array.isArray(data.messages)
                                ? data.messages
                                : [];

                        const count =
                            Number(data.count || 0);

                        if (count > 0) {

                            messageNotify.style.display =
                                'block';

                            messageTitle.textContent =
                                `You have ${count} new message${count > 1 ? 's' : ''}`;

                        } else {

                            messageNotify.style.display =
                                'none';

                            messageTitle.textContent =
                                'Messages';

                        }

                        if (messages.length === 0) {

                            messageList.innerHTML = `

                                <div class="text-center text-muted py-4">

                                    <i class="ti-email"
                                       style="font-size:30px;"></i>

                                    <div class="mt-2">
                                        No new messages
                                    </div>

                                </div>

                            `;

                            return;
                        }

                        messageList.innerHTML = '';

                        messages.forEach(function (message) {

                            const conversationId =
                                message.conversation_id;

                            const tenantName =
                                message.tenant_name ||
                                'Tenant';

                            const tenantInitial =
                                tenantName
                                    .charAt(0)
                                    .toUpperCase();

                            const item =
                                document.createElement('a');

                            if (conversationId) {

                                item.href =
                                    `/admin/chat/conversation/${encodeURIComponent(conversationId)}`;

                            } else {

                                item.href =
                                    "{{ route('admin.chat.index') }}";

                            }

                            item.className =
                                'd-flex align-items-center';

                            item.style.cursor =
                                'pointer';

                            item.innerHTML = `

                                <div class="user-img">

                                    <div
                                        class="
                                            rounded-circle
                                            bg-success
                                            text-white
                                            d-flex
                                            align-items-center
                                            justify-content-center
                                        "
                                        style="
                                            width:40px;
                                            height:40px;
                                            font-weight:600;
                                        "
                                    >
                                        ${escapeHtml(
                                            tenantInitial
                                        )}
                                    </div>

                                    <span
                                        class="
                                            profile-status
                                            online
                                            pull-right
                                        "
                                    ></span>

                                </div>

                                <div class="mail-contnet">

                                    <h5>
                                        ${escapeHtml(
                                            tenantName
                                        )}
                                    </h5>

                                    <span class="mail-desc">
                                        ${escapeHtml(
                                            message.message ||
                                            ''
                                        )}
                                    </span>

                                    <span class="time">

                                        ${escapeHtml(
                                            message.sender_name ||
                                            'Tenant User'
                                        )}

                                        -

                                        ${escapeHtml(
                                            message.time ||
                                            ''
                                        )}

                                    </span>

                                </div>

                            `;

                            messageList.appendChild(item);

                        });

                    })
                    .catch(function (error) {

                        console.error(
                            'Admin message notification error:',
                            error
                        );

                        messageList.innerHTML = `

                            <div class="text-center text-danger p-3">

                                <i class="fa fa-exclamation-triangle me-1"></i>

                                Unable to load messages.

                            </div>

                        `;

                    });

                }

                loadAdminMessageNotifications();

                setInterval(
                    loadAdminMessageNotifications,
                    5000
                );

            });

        </script>
