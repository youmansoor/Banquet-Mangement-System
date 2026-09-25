<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tenant Settings</title>

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


    {{-- ====================================================== --}}
    {{-- TENANT NAVIGATION --}}
    {{-- ====================================================== --}}

    @include('tenant.nav')


    <div class="page-wrapper">


        <div class="container-fluid">


            {{-- ====================================================== --}}
            {{-- PAGE TITLE --}}
            {{-- ====================================================== --}}

            <div class="row page-titles">


                <div class="col-md-6 align-self-center">


                    <h4 class="text-themecolor">

                        <i class="ti-settings me-2"></i>

                        Tenant Settings

                    </h4>


                </div>


                <div class="col-md-6 align-self-center text-end">


                    <a href="{{ url('/tenant/dashboard') }}"
                       class="btn btn-secondary">

                        <i class="fa fa-arrow-left me-1"></i>

                        Dashboard

                    </a>


                </div>


            </div>



            {{-- ====================================================== --}}
            {{-- SUCCESS MESSAGE --}}
            {{-- ====================================================== --}}

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



            {{-- ====================================================== --}}
            {{-- PROFILE SUCCESS MESSAGE --}}
            {{-- ====================================================== --}}

            @if(session('profile_success'))

                <div class="alert alert-success alert-dismissible fade show"
                     role="alert">

                    <i class="fa fa-check-circle me-1"></i>

                    {{ session('profile_success') }}


                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">

                    </button>

                </div>

            @endif



            {{-- ====================================================== --}}
            {{-- ERROR MESSAGE --}}
            {{-- ====================================================== --}}

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



            {{-- ====================================================== --}}
            {{-- MAIN SETTINGS CARD --}}
            {{-- ====================================================== --}}

            <div class="card">


                {{-- ================================================== --}}
                {{-- CARD HEADER --}}
                {{-- ================================================== --}}

                <div class="card-body p-b-0">


                    <h4 class="card-title">

                        <i class="ti-settings text-info me-2"></i>

                        Tenant Settings

                    </h4>


                    <h6 class="card-subtitle">

                        Manage your business, tax, payment and profile settings.

                    </h6>


                </div>



                {{-- ================================================== --}}
                {{-- NAV TABS --}}
                {{-- ================================================== --}}

                <ul class="nav nav-tabs customtab"
                    role="tablist">


                    {{-- BUSINESS --}}

                    <li class="nav-item">


                        <a class="nav-link active"
                           data-bs-toggle="tab"
                           href="#businessSettings"
                           role="tab">


                            <span class="hidden-sm-up">

                                <i class="ti-home"></i>

                            </span>


                            <span class="hidden-xs-down">

                                <i class="ti-home me-1"></i>

                                Business

                            </span>


                        </a>


                    </li>



                    {{-- TAX --}}

                    <li class="nav-item">


                        <a class="nav-link"
                           data-bs-toggle="tab"
                           href="#taxSettings"
                           role="tab">


                            <span class="hidden-sm-up">

                                <i class="ti-receipt"></i>

                            </span>


                            <span class="hidden-xs-down">

                                <i class="ti-receipt me-1"></i>

                                Tax

                            </span>


                        </a>


                    </li>



                    {{-- PAYMENT --}}

                    <li class="nav-item">


                        <a class="nav-link"
                           data-bs-toggle="tab"
                           href="#paymentSettings"
                           role="tab">


                            <span class="hidden-sm-up">

                                <i class="ti-wallet"></i>

                            </span>


                            <span class="hidden-xs-down">

                                <i class="ti-wallet me-1"></i>

                                Payment

                            </span>


                        </a>


                    </li>



                    {{-- PROFILE --}}

                    <li class="nav-item">


                        <a class="nav-link"
                           data-bs-toggle="tab"
                           href="#profileSettings"
                           role="tab">


                            <span class="hidden-sm-up">

                                <i class="ti-user"></i>

                            </span>


                            <span class="hidden-xs-down">

                                <i class="ti-user me-1"></i>

                                My Profile

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

                    {{-- FOOTER --}}

<li class="nav-item">

    <a class="nav-link"
       data-bs-toggle="tab"
       href="#footerSettings"
       role="tab">

        <span class="hidden-sm-up">

            <i class="fa fa-copyright"></i>

        </span>

        <span class="hidden-xs-down">

            <i class="fa fa-copyright me-1"></i>

            Footer

        </span>

    </a>

</li>


                    {{-- MASTER --}}

                    <li class="nav-item">


                        <a class="nav-link"
                           data-bs-toggle="tab"
                           href="#masterSettings"
                           role="tab">


                            <span class="hidden-sm-up">

                                <i class="fa fa-cogs"></i>

                            </span>


                            <span class="hidden-xs-down">

                                <i class="fa fa-cogs me-1"></i>

                                Finance Masters

                            </span>


                        </a>


                    </li>

                    {{-- TERM & CONDITIONS --}}

                    <li class="nav-item">


                        <a class="nav-link"
                           data-bs-toggle="tab"
                           href="#terms-pane"
                           role="tab">


                            <span class="hidden-sm-up">

                                <i class="fa fa-cogs"></i>

                            </span>


                            <span class="hidden-xs-down">

                                <i class="fa fa-cogs me-1"></i>

                                Term & Conditons

                            </span>


                        </a>


                    </li>
                </ul>



                {{-- ================================================== --}}
                {{-- TAB CONTENT --}}
                {{-- ================================================== --}}

                <div class="tab-content">



                    {{-- ================================================= --}}
                    {{-- BUSINESS SETTINGS --}}
                    {{-- ================================================= --}}

                    <div class="tab-pane active"
                         id="businessSettings"
                         role="tabpanel">


                        <form action="{{ route('tenant.settings.update') }}"
                              method="POST">

                            @csrf

                            @method('PUT')


                            <div class="p-20">


                                <div class="row">


                                    <div class="col-md-12 mb-4">


                                        <h4 class="card-title mb-1">

                                            <i class="ti-home text-info me-2"></i>

                                            Business Information

                                        </h4>


                                        <p class="text-muted mb-0">

                                            Enter your business information
                                            used throughout the system.

                                        </p>


                                    </div>



                                    {{-- BUSINESS NAME --}}

                                    <div class="col-md-6 mb-3">


                                        <label class="form-label">

                                            Business Name

                                        </label>


                                        <input type="text"
                                               name="business_name"
                                               class="form-control"
                                               value="{{ old('business_name', $settings?->business_name ?? '') }}"
                                               placeholder="Enter business name">


                                    </div>



                                    {{-- PHONE --}}

                                    <div class="col-md-6 mb-3">


                                        <label class="form-label">

                                            Business Phone

                                        </label>


                                        <input type="text"
                                               name="business_phone"
                                               class="form-control"
                                               value="{{ old('business_phone', $settings?->business_phone ?? '') }}"
                                               placeholder="03XXXXXXXXX">


                                    </div>



                                    {{-- EMAIL --}}

                                    <div class="col-md-6 mb-3">


                                        <label class="form-label">

                                            Business Email

                                        </label>


                                        <input type="email"
                                               name="business_email"
                                               class="form-control"
                                               value="{{ old('business_email', $settings?->business_email ?? '') }}"
                                               placeholder="business@example.com">


                                    </div>



                                    {{-- CURRENCY --}}

                                    <div class="col-md-6 mb-3">


                                        <label class="form-label">

                                            Currency

                                        </label>


                                        <select name="currency"
                                                class="form-control">


                                            <option value="PKR"
                                                {{ old('currency', $settings?->currency ?? 'PKR') == 'PKR' ? 'selected' : '' }}>

                                                PKR - Pakistani Rupee

                                            </option>


                                            <option value="USD"
                                                {{ old('currency', $settings?->currency ?? '') == 'USD' ? 'selected' : '' }}>

                                                USD - US Dollar

                                            </option>


                                            <option value="AED"
                                                {{ old('currency', $settings?->currency ?? '') == 'AED' ? 'selected' : '' }}>

                                                AED - UAE Dirham

                                            </option>


                                            <option value="SAR"
                                                {{ old('currency', $settings?->currency ?? '') == 'SAR' ? 'selected' : '' }}>

                                                SAR - Saudi Riyal

                                            </option>


                                        </select>


                                    </div>



                                    {{-- ADDRESS --}}

                                    <div class="col-md-12 mb-3">


                                        <label class="form-label">

                                            Business Address

                                        </label>


                                        <textarea name="business_address"
                                                  rows="4"
                                                  class="form-control"
                                                  placeholder="Enter business address">{{ old('business_address', $settings?->business_address ?? '') }}</textarea>


                                    </div>



                                    {{-- SAVE --}}

                                    <div class="col-md-12 text-end mt-3">


                                        <button type="submit"
                                                class="btn btn-success text-white">

                                            <i class="fa fa-save me-1"></i>

                                            Save Business Settings

                                        </button>


                                    </div>


                                </div>


                            </div>


                        </form>


                    </div>



                    {{-- ================================================= --}}
                    {{-- TAX SETTINGS --}}
                    {{-- ================================================= --}}

                    <div class="tab-pane"
                         id="taxSettings"
                         role="tabpanel">


                        <form action="{{ route('tenant.settings.update') }}"
                              method="POST">

                            @csrf

                            @method('PUT')


                            <input type="hidden"
                                   name="currency"
                                   value="{{ old('currency', $settings?->currency ?? 'PKR') }}">


                            <div class="p-20">


                                <div class="row">


                                    <div class="col-md-12 mb-4">


                                        <h4 class="card-title mb-1">

                                            <i class="ti-receipt text-warning me-2"></i>

                                            Tax Settings

                                        </h4>


                                        <p class="text-muted mb-0">

                                            Configure tax calculation
                                            for invoices and bookings.

                                        </p>


                                    </div>



                                    {{-- ENABLE TAX --}}

                                    <div class="col-md-12 mb-4">


                                        <div class="p-3 border rounded bg-light">


                                            <div class="form-check form-switch">


                                                <input type="checkbox"
                                                       name="tax_enabled"
                                                       value="1"
                                                       id="tax_enabled"
                                                       class="form-check-input"
                                                       {{ old(
                                                            'tax_enabled',
                                                            $settings?->tax_enabled ?? false
                                                          ) ? 'checked' : '' }}>


                                                <label class="form-check-label fw-bold"
                                                       for="tax_enabled">

                                                    Enable Tax

                                                </label>


                                            </div>


                                            <small class="text-muted">

                                                Enable this option to apply
                                                tax to applicable transactions.

                                            </small>


                                        </div>


                                    </div>



                                    {{-- TAX FIELDS --}}

                                    <div class="col-md-12"
                                         id="taxFields">


                                        <div class="row">


                                            <div class="col-md-12">


                                                <div id="taxRows">


                                                    @php

                                                        $taxes = old(
                                                            'taxes',
                                                            $settings?->taxes ?? []
                                                        );

                                                        if (
                                                            empty($taxes) &&
                                                            !empty($settings?->tax_name)
                                                        ) {

                                                            $taxes = [

                                                                [
                                                                    'name' =>
                                                                        $settings->tax_name,

                                                                    'percentage' =>
                                                                        $settings->tax_percentage,
                                                                ]

                                                            ];

                                                        }

                                                    @endphp



                                                    @if(count($taxes) > 0)


                                                        @foreach($taxes as $index => $tax)


                                                            <div class="tax-row row mb-3">


                                                                <div class="col-md-6">


                                                                    <label class="form-label">

                                                                        Tax Name

                                                                    </label>


                                                                    <input type="text"
                                                                           name="taxes[{{ $index }}][name]"
                                                                           class="form-control"
                                                                           value="{{ $tax['name'] ?? '' }}"
                                                                           placeholder="e.g. GST">


                                                                </div>


                                                                <div class="col-md-5">


                                                                    <label class="form-label">

                                                                        Tax Percentage (%)

                                                                    </label>


                                                                    <div class="input-group">


                                                                        <input type="number"
                                                                               name="taxes[{{ $index }}][percentage]"
                                                                               class="form-control"
                                                                               step="0.01"
                                                                               min="0"
                                                                               max="100"
                                                                               value="{{ $tax['percentage'] ?? '' }}"
                                                                               placeholder="e.g. 18">


                                                                        <span class="input-group-text">

                                                                            %

                                                                        </span>


                                                                    </div>


                                                                </div>


                                                                <div class="col-md-1 d-flex align-items-end">


                                                                    <button type="button"
                                                                            class="btn btn-danger remove-tax"
                                                                            title="Remove Tax">


                                                                        <i class="fa fa-trash"></i>


                                                                    </button>


                                                                </div>


                                                            </div>


                                                        @endforeach


                                                    @else


                                                        <div class="tax-row row mb-3">


                                                            <div class="col-md-6">


                                                                <label class="form-label">

                                                                    Tax Name

                                                                </label>


                                                                <input type="text"
                                                                       name="taxes[0][name]"
                                                                       class="form-control"
                                                                       placeholder="e.g. GST">


                                                            </div>


                                                            <div class="col-md-5">


                                                                <label class="form-label">

                                                                    Tax Percentage (%)

                                                                </label>


                                                                <div class="input-group">


                                                                    <input type="number"
                                                                           name="taxes[0][percentage]"
                                                                           class="form-control"
                                                                           step="0.01"
                                                                           min="0"
                                                                           max="100"
                                                                           placeholder="e.g. 18">


                                                                    <span class="input-group-text">

                                                                        %

                                                                    </span>


                                                                </div>


                                                            </div>


                                                        </div>


                                                    @endif


                                                </div>



                                                {{-- ADD TAX --}}

                                                <button type="button"
                                                        id="addTax"
                                                        class="btn btn-outline-primary mt-2">


                                                    <i class="fa fa-plus me-1"></i>

                                                    Add Tax


                                                </button>


                                            </div>


                                        </div>


                                    </div>



                                    {{-- SAVE --}}

                                    <div class="col-md-12 text-end mt-3">


                                        <button type="submit"
                                                class="btn btn-success text-white">


                                            <i class="fa fa-save me-1"></i>

                                            Save Tax Settings


                                        </button>


                                    </div>


                                </div>


                            </div>


                        </form>


                    </div>



                    {{-- ================================================= --}}
                    {{-- PAYMENT SETTINGS --}}
                    {{-- ================================================= --}}

                    <div class="tab-pane"
                         id="paymentSettings"
                         role="tabpanel">


                        <form action="{{ route('tenant.settings.update') }}"
                              method="POST">


                            @csrf

                            @method('PUT')


                            <input type="hidden"
                                   name="currency"
                                   value="{{ old('currency', $settings?->currency ?? 'PKR') }}">


                            <div class="p-20">


                                <div class="row">


                                    <div class="col-md-12 mb-4">


                                        <h4 class="card-title mb-1">


                                            <i class="ti-wallet text-success me-2"></i>


                                            Advance Booking Payment Settings


                                        </h4>


                                        <p class="text-muted mb-0">


                                            Configure default payment method
                                            and booking advance requirements.


                                        </p>


                                    </div>



                                    {{-- PAYMENT METHOD --}}

                                    <div class="col-md-6 mb-3">


                                        <label class="form-label">

                                            Default Payment Method

                                        </label>


                                        <select name="payment_method"
                                                class="form-control">


                                            <option value="">

                                                -- Select Payment Method --

                                            </option>


                                            <option value="cash"
                                                {{ old(
                                                    'payment_method',
                                                    $settings?->payment_method ?? ''
                                                ) == 'cash' ? 'selected' : '' }}>

                                                Cash

                                            </option>


                                            <option value="bank"
                                                {{ old(
                                                    'payment_method',
                                                    $settings?->payment_method ?? ''
                                                ) == 'bank' ? 'selected' : '' }}>

                                                Bank Transfer

                                            </option>


                                            <option value="online"
                                                {{ old(
                                                    'payment_method',
                                                    $settings?->payment_method ?? ''
                                                ) == 'online' ? 'selected' : '' }}>

                                                Online Payment

                                            </option>


                                            <option value="card"
                                                {{ old(
                                                    'payment_method',
                                                    $settings?->payment_method ?? ''
                                                ) == 'card' ? 'selected' : '' }}>

                                                Card

                                            </option>


                                        </select>


                                    </div>



                                    {{-- BOOKING ADVANCE --}}

                                    <div class="col-md-6 mb-3">


                                        <label class="form-label">

                                            Booking Advance (%)

                                        </label>


                                        <div class="input-group">


                                            <input type="number"
                                                   name="booking_advance_percentage"
                                                   class="form-control"
                                                   min="0"
                                                   max="100"
                                                   step="0.01"
                                                   value="{{ old(
                                                        'booking_advance_percentage',
                                                        $settings?->booking_advance_percentage ?? 0
                                                   ) }}"
                                                   placeholder="e.g. 30">


                                            <span class="input-group-text">

                                                %

                                            </span>


                                        </div>


                                        <small class="text-muted">

                                            Required advance percentage
                                            when creating a booking.

                                        </small>


                                    </div>



                                    <div class="col-md-12 mt-3">


                                        <div class="alert alert-light-info">


                                            <i class="ti-info-alt me-1"></i>


                                            <strong>
                                                Advance Booking Payment Setting:
                                            </strong>


                                            The selected payment method can
                                            be used as the default option
                                            during booking and invoice creation.


                                        </div>


                                    </div>



                                    {{-- INSTALLMENT OPTIONS --}}

                                    <div class="col-md-12 mb-4">


                                        <div class="card border">


                                            <div class="card-header bg-light">


                                                <h5 class="mb-0">


                                                    <i class="ti-list text-primary me-2"></i>


                                                    Installment Options


                                                </h5>


                                            </div>


                                            <div class="card-body">


                                                <p class="text-muted mb-3">


                                                    Add the installment names that tenants can select
                                                    when recording payments after the booking advance.


                                                    Example:


                                                    2nd Installment, 3rd Installment, 4th Installment.


                                                </p>



                                                <div id="installmentOptionsContainer">


                                                    @php

                                                        $installmentOptions = old(
                                                            'installment_options',
                                                            $settings?->installment_options ?? []
                                                        );

                                                        if (!is_array($installmentOptions)) {

                                                            $installmentOptions =
                                                                json_decode(
                                                                    $installmentOptions,
                                                                    true
                                                                ) ?? [];

                                                        }

                                                    @endphp



                                                    @forelse(
                                                        $installmentOptions as $index => $option
                                                    )


                                                        <div class="row installment-option-row mb-2">


                                                            <div class="col-md-10">


                                                                <input
                                                                    type="text"
                                                                    name="installment_options[]"
                                                                    class="form-control"
                                                                    value="{{ $option }}"
                                                                    placeholder="e.g. 2nd Installment"
                                                                >


                                                            </div>


                                                            <div class="col-md-2">


                                                                <button
                                                                    type="button"
                                                                    class="btn btn-danger w-100 remove-installment-option"
                                                                >


                                                                    <i class="fa fa-trash"></i>

                                                                    Remove


                                                                </button>


                                                            </div>


                                                        </div>


                                                    @empty


                                                        <div class="row installment-option-row mb-2">


                                                            <div class="col-md-10">


                                                                <input
                                                                    type="text"
                                                                    name="installment_options[]"
                                                                    class="form-control"
                                                                    placeholder="e.g. 2nd Installment"
                                                                >


                                                            </div>


                                                            <div class="col-md-2">


                                                                <button
                                                                    type="button"
                                                                    class="btn btn-danger w-100 remove-installment-option"
                                                                >


                                                                    <i class="fa fa-trash"></i>

                                                                    Remove


                                                                </button>


                                                            </div>


                                                        </div>


                                                    @endforelse


                                                </div>



                                                <button
                                                    type="button"
                                                    id="addInstallmentOption"
                                                    class="btn btn-info text-white mt-2"
                                                >


                                                    <i class="fa fa-plus me-1"></i>

                                                    Add Installment


                                                </button>



                                                <div class="mt-3">


                                                    <small class="text-muted">


                                                        Example:

                                                        2nd Installment →

                                                        3rd Installment →

                                                        4th Installment →

                                                        5th Installment


                                                    </small>


                                                </div>


                                            </div>


                                        </div>


                                    </div>



                                    {{-- SAVE PAYMENT SETTINGS --}}

                                    <div class="col-md-12 text-end mt-3">


                                        <button type="submit"
                                                class="btn btn-success text-white">


                                            <i class="fa fa-save me-1"></i>

                                            Save Payment Settings


                                        </button>


                                    </div>


                                </div>


                            </div>


                        </form>


                    </div>



                    {{-- ================================================= --}}
                    {{-- MY PROFILE --}}
                    {{-- ================================================= --}}

                    <div class="tab-pane"
                         id="profileSettings"
                         role="tabpanel">


                        <div class="p-20">


                            <div class="row">


                                <div class="col-md-12 mb-4">


                                    <h4 class="card-title mb-1">


                                        <i class="ti-user text-primary me-2"></i>


                                        My Profile


                                    </h4>


                                    <p class="text-muted mb-0">


                                        Update your personal information
                                        and account password.


                                    </p>


                                </div>



                                <div class="col-md-12">


                                    <form action="{{ route('tenant.profile.update') }}"
                                          method="POST">


                                        @csrf

                                        @method('PUT')



                                        {{-- PERSONAL INFORMATION --}}

                                        <div class="card border">


                                            <div class="card-header bg-light">


                                                <h5 class="mb-0">


                                                    <i class="ti-user text-primary me-2"></i>


                                                    Personal Information


                                                </h5>


                                            </div>


                                            <div class="card-body">


                                                <div class="row">


                                                    {{-- NAME --}}

                                                    <div class="col-md-6 mb-3">


                                                        <label class="form-label">

                                                            Owner Name

                                                        </label>


                                                        <input type="text"
                                                               name="name"
                                                               class="form-control"
                                                               value="{{ old(
                                                                   'name',
                                                                   auth()->user()->name
                                                               ) }}"
                                                               required>


                                                    </div>



                                                    {{-- EMAIL --}}

                                                    <div class="col-md-6 mb-3">


                                                        <label class="form-label">

                                                            Email Address

                                                        </label>


                                                        <input type="email"
                                                               name="email"
                                                               class="form-control"
                                                               value="{{ old(
                                                                   'email',
                                                                   auth()->user()->email
                                                               ) }}"
                                                               required>


                                                    </div>



                                                    {{-- PHONE --}}

                                                    <div class="col-md-6 mb-3">


                                                        <label class="form-label">

                                                            Phone

                                                        </label>


                                                        <input type="text"
                                                               name="phone"
                                                               class="form-control"
                                                               value="{{ old(
                                                                   'phone',
                                                                   auth()->user()->phone
                                                               ) }}"
                                                               placeholder="03XXXXXXXXX">


                                                    </div>



                                                    {{-- ROLE --}}

                                                    <div class="col-md-6 mb-3">


                                                        <label class="form-label">

                                                            Account Role

                                                        </label>


                                                        <input type="text"
                                                               class="form-control"
                                                               value="{{ auth()->user()->role
                                                                   ? ucwords(
                                                                       str_replace(
                                                                           '_',
                                                                           ' ',
                                                                           auth()->user()->role
                                                                       )
                                                                   )
                                                                   : 'Tenant Owner'
                                                               }}"
                                                               readonly>


                                                        <small class="text-muted">


                                                            Your account role cannot
                                                            be changed from here.


                                                        </small>


                                                    </div>


                                                </div>


                                            </div>


                                        </div>



                                        {{-- PASSWORD --}}

                                        <div class="card border mt-3">


                                            <div class="card-header bg-light">


                                                <h5 class="mb-0">


                                                    <i class="ti-lock text-danger me-2"></i>


                                                    Change Password


                                                </h5>


                                            </div>


                                            <div class="card-body">


                                                <div class="row">


                                                    <div class="col-md-4 mb-3">


                                                        <label class="form-label">

                                                            Current Password

                                                        </label>


                                                        <input type="password"
                                                               name="current_password"
                                                               class="form-control"
                                                               autocomplete="current-password">


                                                    </div>



                                                    <div class="col-md-4 mb-3">


                                                        <label class="form-label">

                                                            New Password

                                                        </label>


                                                        <input type="password"
                                                               name="password"
                                                               class="form-control"
                                                               autocomplete="new-password">


                                                    </div>



                                                    <div class="col-md-4 mb-3">


                                                        <label class="form-label">

                                                            Confirm Password

                                                        </label>


                                                        <input type="password"
                                                               name="password_confirmation"
                                                               class="form-control"
                                                               autocomplete="new-password">


                                                    </div>


                                                </div>



                                                <small class="text-muted">


                                                    Password change optional hai.
                                                    Agar password change nahi karna
                                                    to fields blank chhor dein.


                                                </small>


                                            </div>


                                        </div>



                                        {{-- SAVE PROFILE --}}

                                        <div class="text-end mt-4">


                                            <button type="submit"
                                                    class="btn btn-primary text-white">


                                                <i class="fa fa-save me-1"></i>


                                                Update My Profile


                                            </button>


                                        </div>


                                    </form>


                                </div>


                            </div>


                        </div>


                    </div>



                    {{-- ================================================= --}}
                    {{-- BANK SETTINGS --}}
                    {{-- ================================================= --}}

                    <div class="tab-pane"
                         id="bankSettings"
                         role="tabpanel">


                        <div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="row page-titles">
        <div class="col-md-8">
            <h4 class="text-themecolor">
                <i class="fa fa-bank me-2"></i>
                Banks
            </h4>
            <p class="text-muted mb-0">Manage your bank accounts</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('tenant.banks.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i>
                Add Bank
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa fa-check-circle me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Banks Table --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">All Banks</h4>
            <div class="table-responsive">
                <table class="table color-table primary-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bank Name</th>
                            <th>Opening Balance</th>
                            <th>Current Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banks as $bank)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $bank->bank_name }}</strong></td>
                                <td>Rs. {{ number_format($bank->opening_balance, 2) }}</td>
                                <td>Rs. {{ number_format($bank->current_balance, 2) }}</td>
                                <td>
                                    <a href="{{ route('tenant.banks.show', $bank) }}" class="btn btn-primary waves-effect waves-light me-1" title="View">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('tenant.banks.edit', $bank) }}" class="btn btn-warning waves-effect waves-light text-white me-1" title="Edit">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('tenant.banks.destroy', $bank) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger waves-effect waves-light" title="Delete" onclick="return confirm('Are you sure you want to delete this bank?')">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No banks found.</td>
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
{{-- FOOTER SETTINGS --}}
{{-- ================================================= --}}

<div class="tab-pane"
     id="footerSettings"
     role="tabpanel">


    <form action="{{ route('tenant.settings.update') }}"
          method="POST">

        @csrf

        @method('PUT')


        <input type="hidden"
               name="footer_form"
               value="1">


        <div class="p-20">


            <div class="row">


                {{-- HEADER --}}

                <div class="col-md-12 mb-4">

                    <h4 class="card-title mb-1">

                        <i class="fa fa-copyright text-primary me-2"></i>

                        Footer Settings

                    </h4>


                    <p class="text-muted mb-0">

                        Configure the footer information displayed
                        throughout your banquet management system.

                    </p>

                </div>



                {{-- FOOTER TEXT --}}

                <div class="col-md-12 mb-3">

                    <label class="form-label">

                        Footer Copyright Text

                    </label>


                    <input
                        type="text"
                        name="footer_text"
                        class="form-control"
                        maxlength="255"
                        value="{{ old(
                            'footer_text',
                            $settings?->footer_text
                                ?? 'Banquet Management System'
                        ) }}"
                        placeholder="e.g. Banquet Management System"
                    >


                    <small class="text-muted">

                        Example:
                        Banquet Management System,
                        VenueFlow, etc.

                    </small>

                </div>



                {{-- FOOTER LINK TEXT --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Footer Link Text

                    </label>


                    <input
                        type="text"
                        name="footer_link_text"
                        class="form-control"
                        maxlength="100"
                        value="{{ old(
                            'footer_link_text',
                            $settings?->footer_link_text
                                ?? 'WrapPixel'
                        ) }}"
                        placeholder="e.g. Website"
                    >

                </div>



                {{-- FOOTER LINK URL --}}

                <div class="col-md-6 mb-3">

                    <label class="form-label">

                        Footer Link URL

                    </label>


                    <input
                        type="url"
                        name="footer_link_url"
                        class="form-control"
                        maxlength="2048"
                        value="{{ old(
                            'footer_link_url',
                            $settings?->footer_link_url
                                ?? 'https://www.wrappixel.com/'
                        ) }}"
                        placeholder="https://example.com/"
                    >

                </div>



                {{-- PREVIEW --}}

                <div class="col-md-12 mt-3">

                    <div class="card border">

                        <div class="card-header bg-light">

                            <h5 class="mb-0">

                                <i class="fa fa-eye text-info me-2"></i>

                                Footer Preview

                            </h5>

                        </div>


                        <div class="card-body">

                            <footer class="footer mb-0">

                                © {{ date('Y') }}

                                <span id="footerPreviewText">

                                    {{ old(
                                        'footer_text',
                                        $settings?->footer_text
                                            ?? 'Banquet Management System'
                                    ) }}

                                </span>


                                <a
                                    href="{{ old(
                                        'footer_link_url',
                                        $settings?->footer_link_url
                                            ?? 'https://www.wrappixel.com/'
                                    ) }}"
                                    id="footerPreviewLink"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >

                                    {{ old(
                                        'footer_link_text',
                                        $settings?->footer_link_text
                                            ?? 'WrapPixel'
                                    ) }}

                                </a>

                            </footer>

                        </div>

                    </div>

                </div>



                {{-- SAVE --}}

                <div class="col-md-12 text-end mt-3">

                    <button
                        type="submit"
                        class="btn btn-success text-white"
                    >

                        <i class="fa fa-save me-1"></i>

                        Save Footer Settings

                    </button>

                </div>


            </div>


        </div>


    </form>


</div>



                    {{-- ================================================= --}}
                    {{-- MASTER SETTINGS --}}
                    {{-- ================================================= --}}

                    <div class="tab-pane"
     id="masterSettings"
     role="tabpanel">

    <div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="row page-titles">
        <div class="col-md-8">
            <h4 class="text-themecolor">
                <i class="fa fa-cogs me-2"></i>
                Finance Masters
            </h4>
            <p class="text-muted mb-0">Manage payment categories, beneficiaries, and vendors</p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa fa-check-circle me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabs --}}
    <div class="card">
        <div class="card-body p-b-0">
            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#paymentCategories" role="tab">
                        <i class="fa fa-list me-1"></i>
                        Payment Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#beneficiaries" role="tab">
                        <i class="fa fa-user me-1"></i>
                        Beneficiaries
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#vendors" role="tab">
                        <i class="fa fa-building me-1"></i>
                        Vendors
                    </a>
                </li>
            </ul>
        </div>

        <div class="tab-content">
            {{-- Payment Categories --}}
            <div class="tab-pane active" id="paymentCategories" role="tabpanel">
                <div class="p-20">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title">Payment Categories</h5>
                        <a href="{{ route('tenant.finance-masters.create', 'payment_category') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-1"></i>
                            Add Category
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table color-table primary-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Transaction Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($masters['payment_category'] ?? collect() as $master)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $master->name }}</strong></td>
                                        <td>{{ $master->parent_key }}</td>
                                        <td>
                                            @if($master->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('tenant.finance-masters.edit', $master) }}" class="btn btn-warning waves-effect waves-light text-white me-1">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('tenant.finance-masters.destroy', $master) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="return confirm('Are you sure?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No payment categories found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Beneficiaries --}}
            <div class="tab-pane" id="beneficiaries" role="tabpanel">
                <div class="p-20">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title">Beneficiaries</h5>
                        <a href="{{ route('tenant.finance-masters.create', 'beneficiary') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-1"></i>
                            Add Beneficiary
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table color-table primary-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($masters['beneficiary'] ?? collect() as $master)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $master->name }}</strong></td>
                                        <td>
                                            @if($master->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('tenant.finance-masters.edit', $master) }}" class="btn btn-warning waves-effect waves-light text-white me-1">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('tenant.finance-masters.destroy', $master) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="return confirm('Are you sure?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">No beneficiaries found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Vendors --}}
            <div class="tab-pane" id="vendors" role="tabpanel">
                <div class="p-20">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title">Vendors</h5>
                        <a href="{{ route('tenant.finance-masters.create', 'vendor') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-1"></i>
                            Add Vendor
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table color-table primary-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($masters['vendor'] ?? collect() as $master)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $master->name }}</strong></td>
                                        <td>
                                            @if($master->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('tenant.finance-masters.edit', $master) }}" class="btn btn-warning waves-effect waves-light text-white me-1">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('tenant.finance-masters.destroy', $master) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="return confirm('Are you sure?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">No vendors found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</div>

                    {{-- ============================================================= --}}
{{-- TERMS & CONDITIONS TAB --}}
{{-- ============================================================= --}}

<div
    class="tab-pane fade"
    id="terms-pane"
    role="tabpanel"
    aria-labelledby="terms-tab"
    tabindex="0"
>
<div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="row page-titles">
        <div class="col-md-8">
            <h4 class="text-themecolor">
                <i class="fas fa-file-contract me-2"></i>
                Terms & Conditions
            </h4>
            <p class="text-muted mb-0">Manage your banquet terms and conditions</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('tenant.terms-conditions.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i>
                Add Term
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa fa-check-circle me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Terms Table --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">All Terms & Conditions</h4>
            <div class="table-responsive">
                <table class="table color-table primary-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Heading</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenantTermsConditions as $term)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $term->heading }}</strong></td>
                                <td>{{ \Illuminate\Support\Str::limit($term->description, 100) }}</td>
                                <td>
                                    <a href="{{ route('tenant.terms-conditions.show', $term) }}" class="btn btn-primary waves-effect waves-light me-1" title="View">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('tenant.terms-conditions.edit', $term) }}" class="btn btn-warning waves-effect waves-light text-white me-1" title="Edit">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('tenant.terms-conditions.destroy', $term) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger waves-effect waves-light" title="Delete" onclick="return confirm('Are you sure you want to delete this term?')">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">No terms & conditions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</div>


                </div>


            </div>


        </div>


    </div>


</div>


@include('tenant.footer')



{{-- ====================================================== --}}
{{-- TAX SETTINGS SCRIPT --}}
{{-- ====================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        const taxEnabled =
            document.getElementById(
                'tax_enabled'
            );


        const taxFields =
            document.getElementById(
                'taxFields'
            );


        const taxRows =
            document.getElementById(
                'taxRows'
            );


        const addTax =
            document.getElementById(
                'addTax'
            );



        function toggleTaxFields() {


            if (!taxEnabled || !taxFields) {

                return;

            }


            taxFields.style.display =
                taxEnabled.checked
                    ? 'block'
                    : 'none';

        }


        toggleTaxFields();



        if (taxEnabled) {


            taxEnabled.addEventListener(
                'change',
                toggleTaxFields
            );


        }



        if (addTax && taxRows) {


            addTax.addEventListener(
                'click',
                function () {


                    const index =
                        taxRows.querySelectorAll(
                            '.tax-row'
                        ).length;


                    const taxRow =
                        document.createElement(
                            'div'
                        );


                    taxRow.className =
                        'tax-row row mb-3';


                    taxRow.innerHTML = `

                        <div class="col-md-6">

                            <label class="form-label">
                                Tax Name
                            </label>

                            <input
                                type="text"
                                name="taxes[${index}][name]"
                                class="form-control"
                                placeholder="e.g. Sales Tax"
                            >

                        </div>


                        <div class="col-md-5">

                            <label class="form-label">
                                Tax Percentage (%)
                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    name="taxes[${index}][percentage]"
                                    class="form-control"
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    placeholder="e.g. 5"
                                >

                                <span class="input-group-text">
                                    %
                                </span>

                            </div>

                        </div>


                        <div class="col-md-1 d-flex align-items-end">

                            <button
                                type="button"
                                class="btn btn-danger remove-tax"
                                title="Remove Tax"
                            >

                                <i class="fa fa-trash"></i>

                            </button>

                        </div>

                    `;


                    taxRows.appendChild(
                        taxRow
                    );


                }
            );


        }



        if (taxRows) {


            taxRows.addEventListener(
                'click',
                function (event) {


                    const button =
                        event.target.closest(
                            '.remove-tax'
                        );


                    if (!button) {

                        return;

                    }


                    const row =
                        button.closest(
                            '.tax-row'
                        );


                    if (row) {

                        row.remove();

                    }


                }
            );


        }


    }
);

</script>



{{-- ====================================================== --}}
{{-- PAYMENT RULE JAVASCRIPT --}}
{{-- ====================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        const table =
            document.getElementById(
                'paymentRulesTable'
            );


        const addButton =
            document.getElementById(
                'addPaymentRule'
            );


        if (!table || !addButton) {

            return;

        }



        function getOrdinal(number) {


            const mod100 =
                number % 100;


            if (
                mod100 >= 11 &&
                mod100 <= 13
            ) {

                return number + 'th';

            }


            switch (
                number % 10
            ) {


                case 1:

                    return number + 'st';


                case 2:

                    return number + 'nd';


                case 3:

                    return number + 'rd';


                default:

                    return number + 'th';


            }

        }



        function updatePaymentRuleNumbers() {


            const rows =
                table.querySelectorAll(
                    'tr'
                );


            rows.forEach(
                function (row, index) {


                    const stageNumber =
                        index + 1;


                    const badge =
                        row.querySelector(
                            'td:first-child .badge'
                        );


                    if (!badge) {

                        return;

                    }


                    badge.textContent =
                        stageNumber;


                    badge.classList.remove(
                        'bg-primary',
                        'bg-info'
                    );


                    if (index === 0) {

                        badge.classList.add(
                            'bg-primary'
                        );

                    } else {

                        badge.classList.add(
                            'bg-info'
                        );

                    }


                }
            );


        }



        addButton.addEventListener(
            'click',
            function () {


                const rows =
                    table.querySelectorAll(
                        'tr'
                    );


                const index =
                    rows.length;


                const stageNumber =
                    index + 1;


                const installmentNumber =
                    index;


                const installmentName =
                    getOrdinal(
                        installmentNumber
                    ) +
                    ' Installment';



                const row =
                    document.createElement(
                        'tr'
                    );



                row.innerHTML = `

                    <td>

                        <span class="badge bg-info">

                            ${stageNumber}

                        </span>

                    </td>


                    <td>

                        <input
                            type="text"
                            name="payment_rules[${index}][name]"
                            class="form-control"
                            value="${installmentName}"
                            required
                        >

                    </td>


                    <td>

                        <input
                            type="text"
                            name="payment_rules[${index}][description]"
                            class="form-control"
                            value=""
                            placeholder="Payment description"
                        >

                    </td>


                    <td>

                        <select
                            name="payment_rules[${index}][amount_type]"
                            class="form-select"
                            required
                        >

                            <option value="fixed">
                                Fixed Amount
                            </option>

                            <option value="percentage">
                                Percentage
                            </option>

                        </select>

                    </td>


                    <td>

                        <input
                            type="number"
                            name="payment_rules[${index}][amount]"
                            class="form-control"
                            min="0"
                            step="0.01"
                            value="0"
                            required
                        >

                    </td>


                    <td class="text-center">

                        <button
                            type="button"
                            class="btn btn-sm btn-danger remove-payment-rule"
                        >

                            <i class="fa fa-trash"></i>

                        </button>

                    </td>

                `;


                table.appendChild(
                    row
                );


                updatePaymentRuleNumbers();


            }
        );



        table.addEventListener(
            'click',
            function (event) {


                const button =
                    event.target.closest(
                        '.remove-payment-rule'
                    );


                if (!button) {

                    return;

                }


                const row =
                    button.closest(
                        'tr'
                    );


                if (!row) {

                    return;

                }


                const rows =
                    table.querySelectorAll(
                        'tr'
                    );


                const rowIndex =
                    Array.from(
                        rows
                    ).indexOf(
                        row
                    );


                if (rowIndex === 0) {


                    alert(
                        'Advance payment cannot be removed.'
                    );


                    return;

                }


                row.remove();


                updatePaymentRuleNumbers();


            }
        );



        updatePaymentRuleNumbers();


    }
);

</script>



{{-- ====================================================== --}}
{{-- INSTALLMENT OPTIONS JAVASCRIPT --}}
{{-- ====================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        const installmentContainer =
            document.getElementById(
                'installmentOptionsContainer'
            );


        const addInstallmentButton =
            document.getElementById(
                'addInstallmentOption'
            );



        function createInstallmentRow() {


            const row =
                document.createElement(
                    'div'
                );


            row.className =
                'row installment-option-row mb-2';


            row.innerHTML = `

                <div class="col-md-10">

                    <input
                        type="text"
                        name="installment_options[]"
                        class="form-control"
                        placeholder="e.g. 2nd Installment"
                    >

                </div>


                <div class="col-md-2">

                    <button
                        type="button"
                        class="btn btn-danger w-100 remove-installment-option"
                    >

                        <i class="fa fa-trash"></i>

                        Remove

                    </button>

                </div>

            `;


            return row;


        }



        if (
            addInstallmentButton &&
            installmentContainer
        ) {


            addInstallmentButton.addEventListener(
                'click',
                function () {


                    installmentContainer.appendChild(
                        createInstallmentRow()
                    );


                }
            );


        }



        if (installmentContainer) {


            installmentContainer.addEventListener(
                'click',
                function (event) {


                    const button =
                        event.target.closest(
                            '.remove-installment-option'
                        );


                    if (!button) {

                        return;

                    }


                    const rows =
                        installmentContainer.querySelectorAll(
                            '.installment-option-row'
                        );


                    if (rows.length <= 1) {


                        const input =
                            rows[0]?.querySelector(
                                'input'
                            );


                        if (input) {

                            input.value = '';

                        }


                        return;

                    }


                    const row =
                        button.closest(
                            '.installment-option-row'
                        );


                    if (row) {

                        row.remove();

                    }


                }
            );


        }


    }
);

</script>



{{-- ====================================================== --}}
{{-- BANK SETTINGS JAVASCRIPT --}}
{{-- ====================================================== --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


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


        if (
            !addBankBtn ||
            !banksContainer ||
            !noBanksMessage
        ) {

            return;

        }



        let bankIndex =
            banksContainer.querySelectorAll(
                '.bank-row'
            ).length;



        function updateEmptyMessage() {


            const rows =
                banksContainer.querySelectorAll(
                    '.bank-row'
                );


            if (rows.length === 0) {

                noBanksMessage.classList.remove(
                    'd-none'
                );

            } else {

                noBanksMessage.classList.add(
                    'd-none'
                );

            }

        }



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

                }


                updateEmptyMessage();


            }
        );



        updateEmptyMessage();


    }
);

</script>



{{-- ====================================================== --}}
{{-- FINANCE MASTER JAVASCRIPT --}}
{{-- ====================================================== --}}




{{-- ====================================================== --}}
{{-- FINANCE MASTER JAVASCRIPT --}}
{{-- ====================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | HARD-CODED TRANSACTION TYPES
    |--------------------------------------------------------------------------
    */

    const transactionTypes = [

        {
            key: 'customer_payment',
            name: 'Banquet Payment (Customer)'
        },

        {
            key: 'vendor_payment',
            name: 'Vendor Payment'
        },

        {
            key: 'banquet_expense',
            name: 'Banquet Expense'
        }

    ];


    /*
    |--------------------------------------------------------------------------
    | DOM ELEMENTS
    |--------------------------------------------------------------------------
    */

    const paymentCategoriesContainer =
        document.getElementById('paymentCategoriesContainer');

    const beneficiariesContainer =
        document.getElementById('beneficiariesContainer');

    const vendorsContainer =
        document.getElementById('vendorsContainer');

    const addPaymentCategoryButton =
        document.getElementById('addPaymentCategory');

    const addBeneficiaryButton =
        document.getElementById('addBeneficiary');

    const addVendorButton =
        document.getElementById('addVendor');


    /*
    |--------------------------------------------------------------------------
    | INDEXES
    |--------------------------------------------------------------------------
    */

    let paymentCategoryIndex =
        paymentCategoriesContainer
            ? paymentCategoriesContainer.querySelectorAll(
                '.payment-category-row'
            ).length
            : 0;


    let beneficiaryIndex =
        beneficiariesContainer
            ? beneficiariesContainer.querySelectorAll(
                '.beneficiary-row'
            ).length
            : 0;


    let vendorIndex =
        vendorsContainer
            ? vendorsContainer.querySelectorAll(
                '.vendor-row'
            ).length
            : 0;


    /*
    |--------------------------------------------------------------------------
    | TRANSACTION TYPE OPTIONS
    |--------------------------------------------------------------------------
    */

    function getTransactionTypeOptions() {

        let options = `
            <option value="">
                Select Transaction Type
            </option>
        `;


        transactionTypes.forEach(function (type) {

            options += `
                <option value="${type.key}">
                    ${type.name}
                </option>
            `;

        });


        return options;
    }


    /*
    |--------------------------------------------------------------------------
    | ADD PAYMENT CATEGORY
    |--------------------------------------------------------------------------
    */

    if (
        addPaymentCategoryButton &&
        paymentCategoriesContainer
    ) {

        addPaymentCategoryButton.addEventListener(
            'click',
            function () {


                const noMessage =
                    document.getElementById(
                        'noPaymentCategoryMessage'
                    );


                if (noMessage) {

                    noMessage.remove();

                }


                const index =
                    paymentCategoryIndex++;


                const row =
                    document.createElement('div');


                row.className =
                    'payment-category-row row mb-3';


                row.innerHTML = `

                    <input
                        type="hidden"
                        name="payment_categories[${index}][id]"
                        value=""
                    >


                    <div class="col-md-5">

                        <label class="form-label">

                            Transaction Type

                            <span class="text-danger">*</span>

                        </label>


                        <select
                            name="payment_categories[${index}][parent_key]"
                            class="form-control payment-category-type"
                            required
                        >

                            ${getTransactionTypeOptions()}

                        </select>

                    </div>


                    <div class="col-md-5">

                        <label class="form-label">

                            Payment Category / Reason

                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="text"
                            name="payment_categories[${index}][name]"
                            class="form-control"
                            placeholder="e.g. Booking Advance"
                            required
                        >

                    </div>


                    <div class="col-md-2 d-flex align-items-end">

                        <button
                            type="button"
                            class="btn btn-danger w-100 remove-payment-category"
                        >

                            <i class="fa fa-trash me-1"></i>

                            Remove

                        </button>

                    </div>

                `;


                paymentCategoriesContainer.appendChild(row);

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE PAYMENT CATEGORY
    |--------------------------------------------------------------------------
    */

    if (paymentCategoriesContainer) {

        paymentCategoriesContainer.addEventListener(
            'click',
            function (event) {

                const button =
                    event.target.closest(
                        '.remove-payment-category'
                    );


                if (!button) {
                    return;
                }


                const row =
                    button.closest(
                        '.payment-category-row'
                    );


                if (!row) {
                    return;
                }


                row.remove();


                const remainingRows =
                    paymentCategoriesContainer.querySelectorAll(
                        '.payment-category-row'
                    );


                if (remainingRows.length === 0) {

                    const message =
                        document.createElement('div');


                    message.id =
                        'noPaymentCategoryMessage';


                    message.className =
                        'alert alert-light border text-muted';


                    message.innerHTML = `

                        <i class="fa fa-info-circle me-2"></i>

                        No payment categories added yet.

                        Click
                        <strong>Add Payment Category</strong>
                        to create one.

                    `;


                    paymentCategoriesContainer.appendChild(
                        message
                    );

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ADD BENEFICIARY
    |--------------------------------------------------------------------------
    */

    if (
        addBeneficiaryButton &&
        beneficiariesContainer
    ) {

        addBeneficiaryButton.addEventListener(
            'click',
            function () {


                const noMessage =
                    document.getElementById(
                        'noBeneficiaryMessage'
                    );


                if (noMessage) {
                    noMessage.remove();
                }


                const index =
                    beneficiaryIndex++;


                const row =
                    document.createElement('div');


                row.className =
                    'beneficiary-row row mb-3';


                row.innerHTML = `

                    <input
                        type="hidden"
                        name="beneficiaries[${index}][id]"
                        value=""
                    >


                    <div class="col-md-10">

                        <label class="form-label">

                            Beneficiary

                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="text"
                            name="beneficiaries[${index}][name]"
                            class="form-control"
                            placeholder="e.g. Staff / Contractor"
                            required
                        >

                    </div>


                    <div class="col-md-2 d-flex align-items-end">

                        <button
                            type="button"
                            class="btn btn-danger w-100 remove-beneficiary"
                        >

                            <i class="fa fa-trash me-1"></i>

                            Remove

                        </button>

                    </div>

                `;


                beneficiariesContainer.appendChild(row);

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE BENEFICIARY
    |--------------------------------------------------------------------------
    */

    if (beneficiariesContainer) {

        beneficiariesContainer.addEventListener(
            'click',
            function (event) {

                const button =
                    event.target.closest(
                        '.remove-beneficiary'
                    );


                if (!button) {
                    return;
                }


                const row =
                    button.closest(
                        '.beneficiary-row'
                    );


                if (row) {
                    row.remove();
                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ADD VENDOR
    |--------------------------------------------------------------------------
    */

    if (
        addVendorButton &&
        vendorsContainer
    ) {

        addVendorButton.addEventListener(
            'click',
            function () {


                const noMessage =
                    document.getElementById(
                        'noVendorMessage'
                    );


                if (noMessage) {
                    noMessage.remove();
                }


                const index =
                    vendorIndex++;


                const row =
                    document.createElement('div');


                row.className =
                    'vendor-row row mb-3';


                row.innerHTML = `

                    <input
                        type="hidden"
                        name="vendors[${index}][id]"
                        value=""
                    >


                    <div class="col-md-10">

                        <label class="form-label">

                            Vendor

                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="text"
                            name="vendors[${index}][name]"
                            class="form-control"
                            placeholder="e.g. ABC Foods"
                            required
                        >

                    </div>


                    <div class="col-md-2 d-flex align-items-end">

                        <button
                            type="button"
                            class="btn btn-danger w-100 remove-vendor"
                        >

                            <i class="fa fa-trash me-1"></i>

                            Remove

                        </button>

                    </div>

                `;


                vendorsContainer.appendChild(row);

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE VENDOR
    |--------------------------------------------------------------------------
    */

    if (vendorsContainer) {

        vendorsContainer.addEventListener(
            'click',
            function (event) {

                const button =
                    event.target.closest(
                        '.remove-vendor'
                    );


                if (!button) {
                    return;
                }


                const row =
                    button.closest('.vendor-row');


                if (row) {
                    row.remove();
                }

            }
        );

    }

});

</script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const footerTextInput =
        document.querySelector('[name="footer_text"]');

    const footerLinkTextInput =
        document.querySelector('[name="footer_link_text"]');

    const footerLinkUrlInput =
        document.querySelector('[name="footer_link_url"]');

    const footerPreviewText =
        document.getElementById('footerPreviewText');

    const footerPreviewLink =
        document.getElementById('footerPreviewLink');


    function updateFooterPreview() {

        const footerText =
            footerTextInput?.value.trim()
            || 'Banquet Management System';

        const linkText =
            footerLinkTextInput?.value.trim();

        const linkUrl =
            footerLinkUrlInput?.value.trim();


        if (footerPreviewText) {

            footerPreviewText.textContent =
                footerText;

        }


        if (
            footerPreviewLink &&
            linkText &&
            linkUrl
        ) {

            footerPreviewLink.textContent =
                linkText;

            footerPreviewLink.href =
                linkUrl;

            footerPreviewLink.style.display =
                'inline';

        } else {

            footerPreviewLink.textContent =
                '';

            footerPreviewLink.removeAttribute(
                'href'
            );

            footerPreviewLink.style.display =
                'none';

        }

    }


    footerTextInput?.addEventListener(
        'input',
        updateFooterPreview
    );

    footerLinkTextInput?.addEventListener(
        'input',
        updateFooterPreview
    );

    footerLinkUrlInput?.addEventListener(
        'input',
        updateFooterPreview
    );


    updateFooterPreview();

});

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('termsContainer');
    const addButton = document.getElementById('addTermBtn');

    if (!container || !addButton) {
        return;
    }

    let termIndex = {{ $tenantTermsConditions->count() }};


    /*
    |--------------------------------------------------------------------------
    | ADD NEW TERM
    |--------------------------------------------------------------------------
    */

    addButton.addEventListener('click', function () {

        const noTermsMessage =
            document.getElementById('noTermsMessage');

        if (noTermsMessage) {
            noTermsMessage.remove();
        }


        const row = document.createElement('div');

        row.className =
            'term-row border rounded p-3 mb-3';

        row.setAttribute(
            'data-term-row',
            ''
        );


        row.innerHTML = `

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h6 class="mb-0">

                    <i class="fas fa-list-ol me-1"></i>

                    Term
                    <span data-term-number></span>

                </h6>


                <button
                    type="button"
                    class="btn btn-sm btn-outline-danger remove-term"
                >

                    <i class="fas fa-trash me-1"></i>

                    Remove

                </button>

            </div>


            <div class="mb-3">

                <label class="form-label fw-semibold">

                    Heading

                    <span class="text-danger">*</span>

                </label>

                <input
                    type="text"
                    name="terms_conditions[${termIndex}][heading]"
                    class="form-control"
                    placeholder="e.g. Booking Cancellation Policy"
                    required
                >

            </div>


            <div class="mb-0">

                <label class="form-label fw-semibold">

                    Description

                    <span class="text-danger">*</span>

                </label>

                <textarea
                    name="terms_conditions[${termIndex}][description]"
                    class="form-control"
                    rows="5"
                    placeholder="Enter Terms & Conditions..."
                    required
                ></textarea>

            </div>
        `;


        container.appendChild(row);

        termIndex++;

        updateTermNumbers();

    });


    /*
    |--------------------------------------------------------------------------
    | REMOVE TERM
    |--------------------------------------------------------------------------
    */

    container.addEventListener(
        'click',
        function (event) {

            const removeButton =
                event.target.closest(
                    '.remove-term'
                );

            if (!removeButton) {
                return;
            }


            const row =
                removeButton.closest(
                    '[data-term-row]'
                );


            if (!row) {
                return;
            }


            if (
                !confirm(
                    'Are you sure you want to remove this term?'
                )
            ) {
                return;
            }


            row.remove();

            updateTermNumbers();


            /*
            |--------------------------------------------------------------------------
            | EMPTY STATE
            |--------------------------------------------------------------------------
            */

            const rows =
                container.querySelectorAll(
                    '[data-term-row]'
                );


            if (rows.length === 0) {

                const empty =
                    document.createElement('div');

                empty.id =
                    'noTermsMessage';

                empty.className =
                    'text-center py-5';

                empty.innerHTML = `

                    <i
                        class="fas fa-file-contract fa-3x text-muted mb-3"
                    ></i>

                    <h6>
                        No Terms & Conditions Added
                    </h6>

                    <p class="text-muted mb-0">

                        Click "Add New Term" to create your first
                        Terms & Conditions.

                    </p>
                `;


                container.appendChild(empty);
            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | UPDATE TERM NUMBERS
    |--------------------------------------------------------------------------
    */

    function updateTermNumbers() {

        const rows =
            container.querySelectorAll(
                '[data-term-row]'
            );


        rows.forEach(
            function (row, index) {

                const number =
                    row.querySelector(
                        '[data-term-number]'
                    );

                if (number) {
                    number.textContent =
                        index + 1;
                }
            }
        );
    }


    updateTermNumbers();

});
</script>

</body>

</html>