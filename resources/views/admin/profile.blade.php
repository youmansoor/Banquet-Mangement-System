<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Super Admin Profile</title>

    <link rel="icon"
          type="image/png"
          href="{{ asset('assets/images/favicon.png') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/dist/css/style.min.css') }}">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body class="horizontal-nav boxed skin-megna fixed-layout">

<div id="main-wrapper">

    {{-- ========================================================= --}}
    {{-- NAVBAR --}}
    {{-- ========================================================= --}}

    @include('admin.nav')


    <div class="page-wrapper">

        <div class="container-fluid">


            {{-- ========================================================= --}}
            {{-- PAGE TITLE --}}
            {{-- ========================================================= --}}

            <div class="row page-titles">

                <div class="col-md-6 align-self-center">

                    <h4 class="text-themecolor">

                        <i class="fa fa-user-circle me-2"></i>

                        Super Admin Profile

                    </h4>

                </div>


                <div class="col-md-6 align-self-center text-end">

                    <a href="{{ route('admin.dashboard') }}"
                       class="btn btn-secondary">

                        <i class="fa fa-arrow-left me-1"></i>

                        Dashboard

                    </a>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- SUCCESS --}}
            {{-- ========================================================= --}}

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show"
                     role="alert">

                    <i class="fa fa-check-circle me-1"></i>

                    {{ session('success') }}


                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- ERRORS --}}
            {{-- ========================================================= --}}

            @if($errors->any())

                <div class="alert alert-danger alert-dismissible fade show"
                     role="alert">

                    <strong>

                        <i class="fa fa-exclamation-triangle me-1"></i>

                        Please fix these errors:

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


            {{-- ========================================================= --}}
            {{-- ADMIN PAGE PIN STATUS FORMS --}}
            {{-- ========================================================= --}}
            {{-- IMPORTANT:
                 These forms are OUTSIDE the main profile form.
                 This avoids invalid nested HTML forms.
            --}}
            @foreach($pages as $index => $page)

                <form
                    id="adminPagePinStatusForm{{ $index }}"
                    method="POST"
                    action="{{ route('admin.profile.page-pins.status') }}"
                    class="d-none"
                >

                    @csrf

                    <input type="hidden"
                           name="page_key"
                           value="{{ $page['key'] }}">

                    <input type="hidden"
                           name="enabled"
                           value="0">

                </form>

            @endforeach


            {{-- ========================================================= --}}
            {{-- MAIN PROFILE FORM --}}
            {{-- ========================================================= --}}

            <form
                action="{{ route('admin.profile.update') }}"
                method="POST"
                id="adminProfileForm"
            >

                @csrf

                @method('PUT')


                {{-- ===================================================== --}}
                {{-- ACCOUNT SETTINGS CARD --}}
                {{-- ===================================================== --}}

                <div class="card">


                    {{-- CARD HEADER --}}
                    <div class="card-body p-b-0">

                        <h4 class="card-title">

                            <i class="ti-user text-primary me-2"></i>

                            Account Settings

                        </h4>


                        <h6 class="card-subtitle">

                            Update your Super Admin account information
                            and password.

                        </h6>

                    </div>


                    {{-- ================================================= --}}
                    {{-- TABS --}}
                    {{-- ================================================= --}}

                    <ul class="nav nav-tabs customtab"
                        role="tablist">


                        {{-- PROFILE --}}
                        <li class="nav-item">

                            <a class="nav-link active"
                               data-bs-toggle="tab"
                               href="#profileInformation"
                               role="tab">

                                <span class="hidden-sm-up">
                                    <i class="ti-user"></i>
                                </span>


                                <span class="hidden-xs-down">

                                    <i class="ti-user me-1"></i>

                                    Profile

                                </span>

                            </a>

                        </li>


                        {{-- SECURITY --}}
                        <li class="nav-item">

                            <a class="nav-link"
                               data-bs-toggle="tab"
                               href="#securitySettings"
                               role="tab">

                                <span class="hidden-sm-up">
                                    <i class="ti-lock"></i>
                                </span>


                                <span class="hidden-xs-down">

                                    <i class="ti-lock me-1"></i>

                                    Security

                                </span>

                            </a>

                        </li>


                        {{-- ADMIN PIN --}}
                        <li class="nav-item">

                            <a class="nav-link"
                               data-bs-toggle="tab"
                               href="#pinAdminSecurity"
                               role="tab">

                                <span class="hidden-sm-up">
                                    <i class="ti-key"></i>
                                </span>


                                <span class="hidden-xs-down">

                                    <i class="ti-key me-1"></i>

                                    2-PIN Admin Security

                                </span>

                            </a>

                        </li>


                        {{-- TENANT PIN --}}
                        <li class="nav-item">

                            <a class="nav-link"
                               data-bs-toggle="tab"
                               href="#pinSecurity"
                               role="tab">

                                <span class="hidden-sm-up">
                                    <i class="ti-key"></i>
                                </span>


                                <span class="hidden-xs-down">

                                    <i class="ti-key me-1"></i>

                                    2-PIN Tenant Security

                                </span>

                            </a>

                        </li>


                        {{-- BANKS --}}
                        <li class="nav-item">

                            <a class="nav-link"
                               data-bs-toggle="tab"
                               href="#bankSettings"
                               role="tab">

                                <span class="hidden-sm-up">
                                    <i class="fa fa-bank"></i>
                                </span>


                                <span class="hidden-xs-down">

                                    <i class="fa fa-bank me-1"></i>

                                    Banks

                                </span>

                            </a>

                        </li>


                        {{-- TERMS & CONDITIONS --}}
                        <li class="nav-item">

                            <a class="nav-link"
                               data-bs-toggle="tab"
                               href="#termsConditions"
                               role="tab">

                                <span class="hidden-sm-up">
                                    <i class="fa fa-file-text"></i>
                                </span>


                                <span class="hidden-xs-down">

                                    <i class="fa fa-file-text me-1"></i>

                                    Terms & Conditions

                                </span>

                            </a>

                        </li>

                        {{-- FOOTER --}}
<li class="nav-item">

    <a class="nav-link"
       data-bs-toggle="tab"
       href="#footerSettings"
       role="tab">

        <span class="hidden-sm-up">
            <i class="fa fa-window-maximize"></i>
        </span>

        <span class="hidden-xs-down">

            <i class="fa fa-window-maximize me-1"></i>

            Footer

        </span>

    </a>

</li>

                    </ul>


                    {{-- ================================================= --}}
                    {{-- TAB CONTENT --}}
                    {{-- ================================================= --}}

                    <div class="tab-content">


                        {{-- ================================================= --}}
                        {{-- PROFILE --}}
                        {{-- ================================================= --}}

                        <div class="tab-pane active"
                             id="profileInformation"
                             role="tabpanel">

                            <div class="p-20">

                                <div class="row">


                                    <div class="col-md-12 mb-4">

                                        <h4 class="card-title mb-1">

                                            <i class="ti-user text-primary me-2"></i>

                                            Personal Information

                                        </h4>


                                        <p class="text-muted mb-0">

                                            Update the information associated
                                            with your Super Admin account.

                                        </p>

                                    </div>


                                    {{-- NAME --}}
                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">

                                            Full Name

                                            <span class="text-danger">
                                                *
                                            </span>

                                        </label>


                                        <input
                                            type="text"
                                            name="name"
                                            class="form-control"
                                            value="{{ old('name', $user->name) }}"
                                            placeholder="Enter your name"
                                            required
                                        >

                                    </div>


                                    {{-- EMAIL --}}
                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">

                                            Email Address

                                            <span class="text-danger">
                                                *
                                            </span>

                                        </label>


                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control"
                                            value="{{ old('email', $user->email) }}"
                                            placeholder="Enter email address"
                                            required
                                        >

                                    </div>


                                    {{-- PHONE --}}
                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">

                                            Phone Number

                                        </label>


                                        <input
                                            type="text"
                                            name="phone"
                                            class="form-control"
                                            value="{{ old('phone', $user->phone) }}"
                                            placeholder="03XXXXXXXXX"
                                        >

                                    </div>


                                    {{-- ROLE --}}
                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">

                                            Account Role

                                        </label>


                                        <input
                                            type="text"
                                            class="form-control"
                                            value="Super Admin"
                                            readonly
                                        >


                                        <small class="text-muted">

                                            Your account role cannot be changed
                                            from this page.

                                        </small>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- PASSWORD SECURITY --}}
                        {{-- ================================================= --}}

                        <div class="tab-pane"
                             id="securitySettings"
                             role="tabpanel">

                            <div class="p-20">

                                <div class="row">


                                    <div class="col-md-12 mb-4">

                                        <h4 class="card-title mb-1">

                                            <i class="ti-lock text-danger me-2"></i>

                                            Change Password

                                        </h4>


                                        <p class="text-muted mb-0">

                                            Leave these fields empty if you
                                            don't want to change your password.

                                        </p>

                                    </div>


                                    {{-- CURRENT PASSWORD --}}
                                    <div class="col-md-12 mb-3">

                                        <label class="form-label">

                                            Current Password

                                        </label>


                                        <input
                                            type="password"
                                            name="current_password"
                                            class="form-control"
                                            placeholder="Enter current password"
                                        >


                                        <small class="text-muted">

                                            Required only when changing
                                            your password.

                                        </small>

                                    </div>


                                    {{-- NEW PASSWORD --}}
                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">

                                            New Password

                                        </label>


                                        <input
                                            type="password"
                                            name="password"
                                            class="form-control"
                                            placeholder="Enter new password"
                                        >


                                        <small class="text-muted">

                                            Minimum 8 characters.

                                        </small>

                                    </div>


                                    {{-- CONFIRM PASSWORD --}}
                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">

                                            Confirm New Password

                                        </label>


                                        <input
                                            type="password"
                                            name="password_confirmation"
                                            class="form-control"
                                            placeholder="Confirm new password"
                                        >

                                    </div>


                                    <div class="col-md-12 mt-3">

                                        <div class="alert alert-light-warning">

                                            <i class="ti-info-alt me-1"></i>

                                            <strong>
                                                Security Tip:
                                            </strong>

                                            Use a strong password and do not
                                            share your Super Admin credentials
                                            with anyone.

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- ADMIN 2-PIN SECURITY --}}
                        {{-- ================================================= --}}

                        <div class="tab-pane"
                             id="pinAdminSecurity"
                             role="tabpanel">

                            <div class="p-20">

                                <div class="row">


                                    <div class="col-md-12 mb-4">

                                        <h4 class="card-title mb-1">

                                            <i class="ti-key text-warning me-2"></i>

                                            2-PIN Admin Security

                                        </h4>


                                        <p class="text-muted mb-0">

                                            Protect individual Super Admin pages
                                            with a separate PIN.

                                        </p>

                                    </div>


                                    <div class="col-md-12">

                                        <div class="alert alert-warning">

                                            <i class="fa fa-shield me-1"></i>

                                            Enable 2-PIN security for any page
                                            that requires an additional security PIN.

                                        </div>

                                    </div>


                                    <div class="col-md-12">

                                        <div class="table-responsive">

                                            <table class="table table-bordered table-hover">

                                                <thead>

                                                    <tr>

                                                        <th width="60">
                                                            #
                                                        </th>

                                                        <th>
                                                            Page Name
                                                        </th>

                                                        <th width="180">
                                                            2-PIN Status
                                                        </th>

                                                        <th width="150">
                                                            PIN
                                                        </th>

                                                    </tr>

                                                </thead>


                                                <tbody>

                                                @forelse($pages as $index => $page)

                                                    <tr>

                                                        <td>
                                                            {{ $index + 1 }}
                                                        </td>


                                                        <td>

                                                            <strong>
                                                                {{ $page['name'] }}
                                                            </strong>

                                                        </td>


                                                        <td>

                                                            <div class="form-check form-switch">

                                                                <input
                                                                    type="checkbox"
                                                                    class="form-check-input"
                                                                    id="adminPinSwitch{{ $index }}"
                                                                    name="admin_pin_switch[{{ $index }}]"
                                                                    value="1"
                                                                    form="adminPagePinStatusForm{{ $index }}"
                                                                    onchange="submitAdminPagePinForm({{ $index }}, this)"
                                                                    {{ $page['enabled'] ? 'checked' : '' }}
                                                                >


                                                                <label
                                                                    class="form-check-label"
                                                                    for="adminPinSwitch{{ $index }}"
                                                                >

                                                                    {{ $page['enabled'] ? 'Enabled' : 'Disabled' }}

                                                                </label>

                                                            </div>

                                                        </td>


                                                        <td>

                                                            @if($page['has_pin'])

                                                                <span class="badge bg-success">

                                                                    <i class="fa fa-check me-1"></i>

                                                                    PIN Set

                                                                </span>

                                                            @else

                                                                <span class="badge bg-danger">

                                                                    <i class="fa fa-times me-1"></i>

                                                                    PIN Not Set

                                                                </span>

                                                            @endif

                                                        </td>

                                                    </tr>

                                                @empty

                                                    <tr>

                                                        <td
                                                            colspan="4"
                                                            class="text-center text-muted"
                                                        >

                                                            No admin pages found.

                                                        </td>

                                                    </tr>

                                                @endforelse

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>


                                    <div class="col-md-12 mt-3">

                                        <a
                                            href="{{ route('admin.profile.page-pins') }}"
                                            class="btn btn-primary"
                                        >

                                            <i class="ti-settings me-1"></i>

                                            Manage Page PINs

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- TENANT 2-PIN SECURITY --}}
                        {{-- ================================================= --}}

                        <div class="tab-pane"
                             id="pinSecurity"
                             role="tabpanel">

                            <div class="p-20">

                                <div class="row">


                                    <div class="col-md-12 mb-4">

                                        <h4 class="card-title mb-1">

                                            <i class="ti-key text-warning me-2"></i>

                                            2-PIN Tenant Security

                                        </h4>


                                        <p class="text-muted mb-0">

                                            Select a tenant to manage their
                                            individual page security PINs.

                                        </p>

                                    </div>


                                    <div class="col-md-12">

                                        <div class="alert alert-warning">

                                            <i class="fa fa-shield me-1"></i>

                                            Select a tenant below to manage
                                            2-PIN security for their pages.

                                        </div>

                                    </div>


                                    <div class="col-md-12">

                                        <div class="table-responsive">

                                            <table class="table table-bordered table-hover">

                                                <thead>

                                                    <tr>

                                                        <th width="60">
                                                            #
                                                        </th>

                                                        <th>
                                                            Tenant Name
                                                        </th>

                                                        <th>
                                                            Email
                                                        </th>

                                                        <th width="180">
                                                            Action
                                                        </th>

                                                    </tr>

                                                </thead>


                                                <tbody>

                                                @forelse($tenants as $index => $tenant)

                                                    <tr>

                                                        <td>
                                                            {{ $index + 1 }}
                                                        </td>


                                                        <td>

                                                            <strong>
                                                                {{ $tenant->owner_name }}
                                                            </strong>

                                                        </td>


                                                        <td>

                                                            {{ $tenant->email ?? '-' }}

                                                        </td>


                                                        <td>

                                                            <a
                                                                href="{{ route(
                                                                    'admin.profile.tenant-page-pins',
                                                                    ['tenant' => $tenant->id]
                                                                ) }}"
                                                                class="btn btn-primary btn-sm"
                                                            >

                                                                <i class="ti-settings me-1"></i>

                                                                Manage Pages

                                                            </a>

                                                        </td>

                                                    </tr>

                                                @empty

                                                    <tr>

                                                        <td
                                                            colspan="4"
                                                            class="text-center text-muted"
                                                        >

                                                            No tenants found.

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


                        {{-- ================================================= --}}
                        {{-- BANKS --}}
                        {{-- ================================================= --}}

                        <div class="tab-pane"
                             id="bankSettings"
                             role="tabpanel">

                            <div class="p-20">

                                <div class="row">


                                    {{-- HEADER --}}
                                    <div class="col-md-12 mb-4">

                                        <div class="d-flex justify-content-between align-items-center">

                                            <div>

                                                <h4 class="card-title mb-1">

                                                    <i class="fa fa-bank text-primary me-2"></i>

                                                    Banks

                                                </h4>


                                                <p class="text-muted mb-0">

                                                    Manage your bank accounts
                                                    and opening balances.

                                                </p>

                                            </div>


                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                id="addBankBtn"
                                            >

                                                <i class="fa fa-plus me-1"></i>

                                                Add Bank

                                            </button>

                                        </div>

                                    </div>


                                    {{-- BANK FORM FLAG --}}
                                    <div class="col-md-12">

                                        <input
                                            type="hidden"
                                            name="banks_form"
                                            value="1"
                                        >

                                    </div>


                                    {{-- BANK LIST --}}
                                    <div class="col-md-12">

                                        <div id="banksContainer">


                                            @forelse($banks as $index => $bank)

                                                <div class="card border mb-3 bank-row">

                                                    <div class="card-body">

                                                        <div class="row align-items-end">


                                                            {{-- BANK ID --}}
                                                            <input
                                                                type="hidden"
                                                                name="banks[{{ $index }}][id]"
                                                                value="{{ $bank->id }}"
                                                            >


                                                            {{-- BANK NAME --}}
                                                            <div class="col-md-5 mb-3">

                                                                <label class="form-label">

                                                                    Bank Name

                                                                    <span class="text-danger">
                                                                        *
                                                                    </span>

                                                                </label>


                                                                <input
                                                                    type="text"
                                                                    name="banks[{{ $index }}][name]"
                                                                    class="form-control"
                                                                    value="{{ old(
                                                                        "banks.$index.name",
                                                                        $bank->bank_name
                                                                    ) }}"
                                                                    placeholder="Enter bank name"
                                                                    required
                                                                >

                                                            </div>


                                                            {{-- OPENING BALANCE --}}
                                                            <div class="col-md-5 mb-3">

                                                                <label class="form-label">

                                                                    Opening Balance

                                                                    <span class="text-danger">
                                                                        *
                                                                    </span>

                                                                </label>


                                                                <input
                                                                    type="number"
                                                                    name="banks[{{ $index }}][opening_balance]"
                                                                    class="form-control"
                                                                    value="{{ old(
                                                                        "banks.$index.opening_balance",
                                                                        $bank->opening_balance
                                                                    ) }}"
                                                                    min="0"
                                                                    step="0.01"
                                                                    placeholder="0.00"
                                                                    required
                                                                >

                                                            </div>


                                                            {{-- REMOVE --}}
                                                            <div class="col-md-2 mb-3">

                                                                <button
                                                                    type="button"
                                                                    class="btn btn-danger w-100 remove-bank-btn"
                                                                >

                                                                    <i class="fa fa-trash me-1"></i>

                                                                    Remove

                                                                </button>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            @empty

                                            @endforelse


                                        </div>


                                        {{-- EMPTY MESSAGE --}}
                                        <div
                                            id="noBanksMessage"
                                            class="{{ $banks->count() > 0 ? 'd-none' : '' }}"
                                        >

                                            <div class="alert alert-light-info">

                                                <i class="fa fa-info-circle me-1"></i>

                                                No bank added yet.

                                                Click
                                                <strong>+ Add Bank</strong>
                                                to add one.

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- TERMS & CONDITIONS --}}
                        {{-- ================================================= --}}

                        <div class="tab-pane"
                             id="termsConditions"
                             role="tabpanel">

                            <div class="p-20">

                                <div class="row">


                                    {{-- HEADER --}}
                                    <div class="col-md-12 mb-4">

                                        <div class="d-flex justify-content-between align-items-center">

                                            <div>

                                                <h4 class="card-title mb-1">

                                                    <i class="fa fa-file-text text-primary me-2"></i>

                                                    Terms & Conditions

                                                </h4>


                                                <p class="text-muted mb-0">

                                                    Manage the terms and conditions
                                                    used in your banquet bookings
                                                    and documents.

                                                </p>

                                            </div>


                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                id="addTermBtn"
                                            >

                                                <i class="fa fa-plus me-1"></i>

                                                Add Term

                                            </button>

                                        </div>

                                    </div>


                                    {{-- TERMS CONTAINER --}}
                                    <div class="col-md-12">

                                        <div id="termsContainer">


                                            @forelse($termsConditions as $index => $term)

                                                <div class="card border mb-3 term-row">

                                                    <div class="card-body">

                                                        <div class="row align-items-end">


                                                            {{-- TERM ID --}}
                                                            <input
                                                                type="hidden"
                                                                name="terms_conditions[{{ $index }}][id]"
                                                                value="{{ (int) $term->id }}"
                                                            >


                                                            {{-- HEADING --}}
                                                            <div class="col-md-4 mb-3">

                                                                <label class="form-label">

                                                                    Heading

                                                                    <span class="text-danger">
                                                                        *
                                                                    </span>

                                                                </label>


                                                                <input
                                                                    type="text"
                                                                    name="terms_conditions[{{ $index }}][heading]"
                                                                    class="form-control"
                                                                    value="{{ old(
                                                                        "terms_conditions.$index.heading",
                                                                        $term->heading
                                                                    ) }}"
                                                                    placeholder="e.g. Payment Terms"
                                                                    required
                                                                >

                                                            </div>


                                                            {{-- DESCRIPTION --}}
                                                            <div class="col-md-6 mb-3">

                                                                <label class="form-label">

                                                                    Terms & Conditions

                                                                    <span class="text-danger">
                                                                        *
                                                                    </span>

                                                                </label>


                                                                <textarea
                                                                    name="terms_conditions[{{ $index }}][description]"
                                                                    class="form-control"
                                                                    rows="3"
                                                                    placeholder="Enter terms and conditions..."
                                                                    required
                                                                >{{ old(
                                                                    "terms_conditions.$index.description",
                                                                    $term->description
                                                                ) }}</textarea>

                                                            </div>


                                                            {{-- REMOVE --}}
                                                            <div class="col-md-2 mb-3">

                                                                <button
                                                                    type="button"
                                                                    class="btn btn-danger w-100 remove-term-btn"
                                                                >

                                                                    <i class="fa fa-trash me-1"></i>

                                                                    Remove

                                                                </button>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            @empty

                                            @endforelse


                                        </div>


                                        {{-- EMPTY MESSAGE --}}
                                        <div
                                            id="noTermsMessage"
                                            class="{{ $termsConditions->count() > 0 ? 'd-none' : '' }}"
                                        >

                                            <div class="alert alert-light-info">

                                                <i class="fa fa-info-circle me-1"></i>

                                                No Terms & Conditions added yet.

                                                Click
                                                <strong>+ Add Term</strong>
                                                to add one.

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

{{-- ================================================= --}} 
{{-- TENANT FOOTER SETTINGS --}}
{{-- ================================================= --}}

<div class="tab-pane"
     id="footerSettings"
     role="tabpanel">

    <div class="p-20">

        <div class="row">


            {{-- ================================================= --}}
            {{-- HEADER --}}
            {{-- ================================================= --}}

            <div class="col-md-12 mb-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h4 class="card-title mb-1">

                            <i class="fa fa-window-maximize text-primary me-2"></i>

                            Tenant Footer

                        </h4>

                        <p class="text-muted mb-0">

                            Configure the footer that will be displayed
                            on Admin Tenant pages.

                        </p>

                    </div>


                    {{-- STATUS --}}

                    <div>

                        <div class="form-check form-switch">

                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="footer_enabled"
                                name="footer_enabled"
                                value="1"
                                {{ old(
                                    'footer_enabled',
                                    optional($footer ?? null)->enabled ?? true
                                ) ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="footer_enabled"
                            >

                                Enable Footer

                            </label>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- INFO --}}
            {{-- ================================================= --}}

            <div class="col-md-12 mb-4">

                <div class="alert alert-light-info">

                    <i class="fa fa-info-circle me-1"></i>

                    These settings will be used for the footer shown
                    on Admin Tenant pages.

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- FOOTER TEXT --}}
            {{-- ================================================= --}}

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Footer Text

                    <span class="text-danger">
                        *
                    </span>

                </label>

                <input
                    type="text"
                    name="footer_text"
                    id="footer_text"
                    class="form-control"
                    value="{{ old(
                        'footer_text',
                        optional($footer ?? null)->footer_text
                        ?? '© 2026 VenueFlow'
                    ) }}"
                    placeholder="e.g. © 2026 VenueFlow"
                >

                <small class="text-muted">

                    Main copyright/footer text.

                </small>

            </div>


            {{-- ================================================= --}}
            {{-- BRAND NAME --}}
            {{-- ================================================= --}}

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Company / Brand Name

                    <span class="text-danger">
                        *
                    </span>

                </label>

                <input
                    type="text"
                    name="footer_brand_name"
                    id="footer_brand_name"
                    class="form-control"
                    value="{{ old(
                        'footer_brand_name',
                        optional($footer ?? null)->brand_name
                        ?? 'VenueFlow'
                    ) }}"
                    placeholder="e.g. VenueFlow"
                >

                <small class="text-muted">

                    Name displayed as the footer brand.

                </small>

            </div>


            {{-- ================================================= --}}
            {{-- LINK TEXT --}}
            {{-- ================================================= --}}

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Footer Link Text

                </label>

                <input
                    type="text"
                    name="footer_link_text"
                    id="footer_link_text"
                    class="form-control"
                    value="{{ old(
                        'footer_link_text',
                        optional($footer ?? null)->link_text
                        ?? ''
                    ) }}"
                    placeholder="e.g. Powered by VenueFlow"
                >

                <small class="text-muted">

                    Optional clickable text.

                </small>

            </div>


            {{-- ================================================= --}}
            {{-- LINK URL --}}
            {{-- ================================================= --}}

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Footer Link URL

                </label>

                <input
                    type="url"
                    name="footer_link_url"
                    id="footer_link_url"
                    class="form-control"
                    value="{{ old(
                        'footer_link_url',
                        optional($footer ?? null)->link_url
                        ?? ''
                    ) }}"
                    placeholder="https://example.com"
                >

                <small class="text-muted">

                    Optional URL for the footer link.

                </small>

            </div>


            {{-- ================================================= --}}
            {{-- PREVIEW --}}
            {{-- ================================================= --}}

            <div class="col-md-12 mt-4">

                <h4 class="card-title mb-3">

                    <i class="fa fa-eye text-primary me-2"></i>

                    Footer Preview

                </h4>


                <div
                    class="border rounded bg-light p-4 text-center"
                    id="tenantFooterPreview"
                >

                    <span id="previewFooterText">

                        {{ old(
                            'footer_text',
                            optional($footer ?? null)->footer_text
                            ?? '© 2026 VenueFlow'
                        ) }}

                    </span>


                    <span
                        id="previewFooterBrandWrapper"
                        class="ms-1"
                    >

                        <a
                            href="#"
                            id="previewFooterBrand"
                            target="_blank"
                            rel="noopener"
                        >

                            {{ old(
                                'footer_link_text',
                                optional($footer ?? null)->link_text
                                ?? 'VenueFlow'
                            ) }}

                        </a>

                    </span>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- DEFAULT TENANT FOOTER EXAMPLE --}}
            {{-- ================================================= --}}

            <div class="col-md-12 mt-4">

                <div class="alert alert-secondary">

                    <strong>

                        Example:

                    </strong>

                    <br>

                    © 2026 VenueFlow

                    <a href="#"
                       class="ms-1">

                        Powered by VenueFlow

                    </a>

                </div>

            </div>


        </div>

    </div>

</div>


                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- SAVE CARD --}}
                {{-- ===================================================== --}}

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">


                            <div>

                                <h4 class="card-title mb-1">

                                    Save Changes

                                </h4>


                                <small class="text-muted">

                                    Your Super Admin account information
                                    will be updated immediately.

                                </small>

                            </div>


                            <div>

                                <a
                                    href="{{ route('admin.dashboard') }}"
                                    class="btn btn-secondary me-2"
                                >

                                    <i class="fa fa-times me-1"></i>

                                    Cancel

                                </a>


                                <button
                                    type="submit"
                                    class="btn btn-success text-white"
                                >

                                    <i class="fa fa-save me-1"></i>

                                    Save Changes

                                </button>

                            </div>

                        </div>

                    </div>

                </div>


            </form>


        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /* ===================================================== */
        /* BANK ELEMENTS */
        /* ===================================================== */

        const addBankBtn =
            document.getElementById(
                'addBankBtn'
            );


        const banksContainer =
            document.getElementById(
                'banksContainer'
            );


        const noBanksMessage =
            document.getElementById(
                'noBanksMessage'
            );


        /* ===================================================== */
        /* BANK SAFETY CHECK */
        /* ===================================================== */

        if (
            addBankBtn &&
            banksContainer &&
            noBanksMessage
        ) {


            let bankIndex =
                {{ $banks->count() }};


            function updateEmptyMessage() {

                const rows =
                    banksContainer.querySelectorAll(
                        '.bank-row'
                    );


                if (
                    rows.length === 0
                ) {

                    noBanksMessage.classList.remove(
                        'd-none'
                    );

                }
                else {

                    noBanksMessage.classList.add(
                        'd-none'
                    );

                }

            }


            /* ================================================= */
            /* ADD BANK */
            /* ================================================= */

            addBankBtn.addEventListener(
                'click',
                function () {


                    const row =
                        document.createElement(
                            'div'
                        );


                    row.className =
                        'card border mb-3 bank-row';


                    row.innerHTML = `

                        <div class="card-body">

                            <div class="row align-items-end">

                                <input
                                    type="hidden"
                                    name="banks[${bankIndex}][id]"
                                    value=""
                                >


                                <div class="col-md-5 mb-3">

                                    <label class="form-label">

                                        Bank Name

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>

                                    <input
                                        type="text"
                                        name="banks[${bankIndex}][name]"
                                        class="form-control"
                                        placeholder="Enter bank name"
                                        required
                                    >

                                </div>


                                <div class="col-md-5 mb-3">

                                    <label class="form-label">

                                        Opening Balance

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>

                                    <input
                                        type="number"
                                        name="banks[${bankIndex}][opening_balance]"
                                        class="form-control"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01"
                                        required
                                    >

                                </div>


                                <div class="col-md-2 mb-3">

                                    <button
                                        type="button"
                                        class="btn btn-danger w-100 remove-bank-btn"
                                    >

                                        <i class="fa fa-trash me-1"></i>

                                        Remove

                                    </button>

                                </div>

                            </div>

                        </div>

                    `;


                    banksContainer.appendChild(
                        row
                    );


                    bankIndex++;


                    updateEmptyMessage();

                }
            );


            /* ================================================= */
            /* REMOVE BANK */
            /* ================================================= */

            banksContainer.addEventListener(
                'click',
                function (event) {


                    const button =
                        event.target.closest(
                            '.remove-bank-btn'
                        );


                    if (!button) {
                        return;
                    }


                    const row =
                        button.closest(
                            '.bank-row'
                        );


                    if (row) {

                        row.remove();

                        updateEmptyMessage();

                    }

                }
            );


            updateEmptyMessage();

        }


        /* ===================================================== */
        /* TERMS ELEMENTS */
        /* ===================================================== */

        const addTermBtn =
            document.getElementById(
                'addTermBtn'
            );


        const termsContainer =
            document.getElementById(
                'termsContainer'
            );


        const noTermsMessage =
            document.getElementById(
                'noTermsMessage'
            );


        /* ===================================================== */
        /* TERMS */
        /* ===================================================== */

        if (
            addTermBtn &&
            termsContainer &&
            noTermsMessage
        ) {


            let termIndex =
                {{ $termsConditions->count() }};


            function updateTermsEmptyMessage() {

                const rows =
                    termsContainer.querySelectorAll(
                        '.term-row'
                    );


                if (
                    rows.length === 0
                ) {

                    noTermsMessage.classList.remove(
                        'd-none'
                    );

                }
                else {

                    noTermsMessage.classList.add(
                        'd-none'
                    );

                }

            }


            /* ================================================= */
            /* ADD TERM */
            /* ================================================= */

            addTermBtn.addEventListener(
                'click',
                function () {


                    const row =
                        document.createElement(
                            'div'
                        );


                    row.className =
                        'card border mb-3 term-row';


                    row.innerHTML = `

                        <div class="card-body">

                            <div class="row align-items-end">

                                <input
                                    type="hidden"
                                    name="terms_conditions[${termIndex}][id]"
                                    value=""
                                >


                                <div class="col-md-4 mb-3">

                                    <label class="form-label">

                                        Heading

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>

                                    <input
                                        type="text"
                                        name="terms_conditions[${termIndex}][heading]"
                                        class="form-control"
                                        placeholder="e.g. Payment Terms"
                                        required
                                    >

                                </div>


                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Terms & Conditions

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>

                                    <textarea
                                        name="terms_conditions[${termIndex}][description]"
                                        class="form-control"
                                        rows="3"
                                        placeholder="Enter terms and conditions..."
                                        required
                                    ></textarea>

                                </div>


                                <div class="col-md-2 mb-3">

                                    <button
                                        type="button"
                                        class="btn btn-danger w-100 remove-term-btn"
                                    >

                                        <i class="fa fa-trash me-1"></i>

                                        Remove

                                    </button>

                                </div>

                            </div>

                        </div>

                    `;


                    termsContainer.appendChild(
                        row
                    );


                    termIndex++;


                    updateTermsEmptyMessage();

                }
            );


            /* ================================================= */
            /* REMOVE TERM */
            /* ================================================= */

            termsContainer.addEventListener(
                'click',
                function (event) {


                    const button =
                        event.target.closest(
                            '.remove-term-btn'
                        );


                    if (!button) {
                        return;
                    }


                    const row =
                        button.closest(
                            '.term-row'
                        );


                    if (row) {

                        row.remove();

                        updateTermsEmptyMessage();

                    }

                }
            );


            updateTermsEmptyMessage();

        }

    }
);


/* ========================================================= */
/* ADMIN PAGE PIN FORM SUBMISSION */
/* ========================================================= */

function submitAdminPagePinForm(
    index,
    checkbox
) {

    const form =
        document.getElementById(
            'adminPagePinStatusForm' + index
        );


    if (!form) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Checkbox checked = enabled = 1
    | Checkbox unchecked = enabled = 0
    |--------------------------------------------------------------------------
    */

    let enabledInput =
        form.querySelector(
            'input[name="enabled"][type="hidden"]'
        );


    if (!enabledInput) {

        enabledInput =
            document.createElement(
                'input'
            );

        enabledInput.type =
            'hidden';

        enabledInput.name =
            'enabled';

        form.appendChild(
            enabledInput
        );

    }


    enabledInput.value =
        checkbox.checked
            ? '1'
            : '0';


    form.submit();

}
/* ===================================================== */
/* TENANT FOOTER PREVIEW */
/* ===================================================== */

const footerText =
    document.getElementById('footer_text');

const footerLinkText =
    document.getElementById('footer_link_text');

const footerLinkUrl =
    document.getElementById('footer_link_url');

const previewFooterText =
    document.getElementById('previewFooterText');

const previewFooterBrand =
    document.getElementById('previewFooterBrand');

const previewFooterBrandWrapper =
    document.getElementById('previewFooterBrandWrapper');


function updateFooterPreview() {

    if (
        !footerText ||
        !footerLinkText ||
        !footerLinkUrl ||
        !previewFooterText ||
        !previewFooterBrand
    ) {
        return;
    }


    /* Footer text */

    previewFooterText.textContent =
        footerText.value ||
        '© 2026 VenueFlow';


    /* Link text */

    const linkText =
        footerLinkText.value.trim();


    if (linkText !== '') {

        previewFooterBrandWrapper.classList.remove(
            'd-none'
        );

        previewFooterBrand.textContent =
            linkText;

    } else {

        previewFooterBrandWrapper.classList.add(
            'd-none'
        );

    }


    /* Link URL */

    const linkUrl =
        footerLinkUrl.value.trim();


    if (linkUrl !== '') {

        previewFooterBrand.href =
            linkUrl;

    } else {

        previewFooterBrand.href =
            '#';

    }

}


/* ===================================================== */
/* FOOTER INPUT EVENTS */
/* ===================================================== */

if (footerText) {

    footerText.addEventListener(
        'input',
        updateFooterPreview
    );

}


if (footerLinkText) {

    footerLinkText.addEventListener(
        'input',
        updateFooterPreview
    );

}


if (footerLinkUrl) {

    footerLinkUrl.addEventListener(
        'input',
        updateFooterPreview
    );

}


updateFooterPreview();

</script>


@include('admin.footer')


</body>

</html>