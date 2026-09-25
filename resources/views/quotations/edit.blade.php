<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Quotation</title>

    <style>

        /* =========================================================
           QUOTATION FORM UI
        ========================================================= */

        .quotation-page {
            padding-bottom: 30px;
        }

        .quotation-page .page-header {
            background: #fff;
            border-radius: 10px;
            padding: 18px 22px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,.05);
            border-left: 4px solid #7460ee;
        }

        .quotation-page .page-header h4 {
            margin: 0;
            font-weight: 600;
            color: #2c3e50;
        }

        .quotation-page .page-header p {
            margin: 4px 0 0;
            color: #8898aa;
            font-size: 13px;
        }

        .quotation-card {
            border: 0;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .quotation-card .card-header {
            background: #fff;
            border-bottom: 1px solid #edf1f5;
            padding: 16px 20px;
        }

        .quotation-card .card-header h5 {
            margin: 0;
            color: #2c3e50;
            font-size: 16px;
            font-weight: 600;
        }

        .quotation-card .card-header small {
            display: block;
            margin-top: 3px;
            color: #98a6ad;
            font-size: 12px;
        }

        .quotation-card .card-body {
            padding: 22px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            min-height: 40px;
            border-radius: 6px;
            border: 1px solid #e4e9ef;
            font-size: 13px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #7460ee;
            box-shadow: 0 0 0 .15rem rgba(116,96,238,.12);
        }

        textarea.form-control {
            min-height: auto;
        }

        .required-star {
            color: #f62d51;
        }

        /* =========================================================
           SERVICE BOX
        ========================================================= */

        .services-wrapper {
            background: #f8f9fb;
            border: 1px solid #edf0f4;
            border-radius: 8px;
            padding: 12px;
        }

        .service-row {
            background: #fff;
            border: 1px solid #e8ecf1;
            border-radius: 7px;
            padding: 10px 6px;
            margin-bottom: 8px !important;
            align-items: end;
        }

        .service-row:last-child {
            margin-bottom: 0 !important;
        }

        .service-price {
            background: #f5f7fa !important;
            font-weight: 600;
            color: #28a745;
        }

        .service-action-btn {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .service-help {
            display: block;
            margin-top: 8px;
            font-size: 12px;
            color: #98a6ad;
        }

        /* =========================================================
           PRICING
        ========================================================= */

        .pricing-input {
            font-weight: 600;
        }

        .readonly-field {
            background-color: #f7f9fb !important;
            color: #495057;
        }

        .tax-box {
            border-radius: 7px;
            padding: 10px 13px;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .tax-box .tax-name {
            font-weight: 600;
        }

        .tax-box .tax-percent {
            float: right;
            font-weight: 600;
        }

        .total-card {
            background: linear-gradient(
                135deg,
                #7460ee 0%,
                #6652d9 100%
            );
            color: #fff;
            border-radius: 8px;
            padding: 15px 18px;
            margin-top: 5px;
        }

        .total-card .total-label {
            font-size: 12px;
            opacity: .85;
        }

        .total-card .total-value {
            font-size: 24px;
            font-weight: 700;
            margin-top: 2px;
        }

        .amount-summary {
            background: #f8f9fb;
            border: 1px solid #edf0f4;
            border-radius: 8px;
            padding: 14px;
            margin-top: 15px;
        }

        .amount-summary .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 13px;
        }

        .amount-summary .summary-item strong {
            color: #2c3e50;
        }

        /* =========================================================
           STATUS
        ========================================================= */

        .status-preview {
            margin-top: 8px;
        }

        /* =========================================================
           BUTTONS
        ========================================================= */

        .form-actions {
            padding-top: 8px;
            border-top: 1px solid #edf0f4;
            margin-top: 8px;
        }

        .form-actions .btn {
            min-width: 130px;
            border-radius: 6px;
            font-weight: 500;
        }

        /* =========================================================
           ALERTS
        ========================================================= */

        .quotation-alert {
            border-radius: 7px;
            border: 0;
            font-size: 13px;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 767px) {

            .quotation-card .card-body {
                padding: 15px;
            }

            .quotation-page .page-header {
                padding: 15px;
            }

            .service-row {
                padding: 10px;
            }

            .service-row > div {
                margin-bottom: 8px;
            }

            .service-row > div:last-child {
                margin-bottom: 0;
            }

            .form-actions .btn {
                width: 100%;
                margin-bottom: 8px;
            }

        }

    </style>

</head>

<body>

@include('tenant.nav')

<div class="page-wrapper quotation-page">

    <div class="container-fluid">

        {{-- =========================================================
             PAGE HEADER
        ========================================================= --}}

        <div class="page-header">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>

                    <h4>
                        <i class="fa fa-file-text-o me-2 text-primary"></i>
                        Edit Quotation
                    </h4>

                    <p>
                        Update quotation customer, event, services and pricing details.
                    </p>

                </div>

                <div>

                    <a href="{{ route('quotations.index') }}"
                       class="btn btn-secondary btn-sm">

                        <i class="fa fa-arrow-left me-1"></i>
                        Back to Quotations

                    </a>

                </div>

            </div>

        </div>


        {{-- =========================================================
             ERRORS
        ========================================================= --}}

        @if($errors->any())

            <div class="alert alert-danger quotation-alert alert-dismissible fade show">

                <strong>
                    <i class="fa fa-exclamation-circle me-1"></i>
                    Please fix the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- =========================================================
             SUCCESS
        ========================================================= --}}

        @if(session('success'))

            <div class="alert alert-success quotation-alert alert-dismissible fade show">

                <i class="fa fa-check-circle me-1"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        @php

            $quotationServices = $quotation->services ?? [];

            if (is_string($quotationServices)) {
                $quotationServices = json_decode($quotationServices, true) ?? [];
            }

            if (!is_array($quotationServices)) {
                $quotationServices = [];
            }

        @endphp


        {{-- =========================================================
             MAIN FORM
        ========================================================= --}}

        <form
            action="{{ route('quotations.update', $quotation->id) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            {{-- =====================================================
                 CUSTOMER & EVENT INFORMATION
            ===================================================== --}}

            <div class="card quotation-card">

                <div class="card-header">

                    <h5>
                        <i class="fa fa-user-circle text-primary me-2"></i>
                        Customer & Event Information
                    </h5>

                    <small>
                        Update customer and event booking information.
                    </small>

                </div>


                <div class="card-body">

                    <div class="row">


                        {{-- CUSTOMER NAME --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Customer Name

                                <span class="required-star">*</span>

                            </label>

                            <input
                                type="text"
                                name="customer_name"
                                class="form-control"
                                placeholder="Enter customer name"
                                value="{{ old('customer_name', $quotation->customer?->name ?? $quotation->customer_name ?? '') }}"
                                required
                            >

                        </div>


                        {{-- VENUE --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Venue / Lawn

                                <span class="required-star">*</span>

                            </label>

                            <select
                                name="lawn_type_id"
                                class="form-control form-select"
                                required
                            >

                                <option value="">
                                    Select Venue
                                </option>

                                @foreach($lawnTypes as $lawnType)

                                    <option
                                        value="{{ $lawnType->id }}"
                                        {{ old('lawn_type_id', $quotation->lawn_type_id) == $lawnType->id ? 'selected' : '' }}
                                    >
                                        {{ $lawnType->lawn_type }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- EVENT TYPE --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Event Type

                                <span class="required-star">*</span>

                            </label>

                            <select
                                name="event_type"
                                class="form-control form-select"
                                required
                            >

                                <option value="">
                                    Select Event
                                </option>

                                @foreach([
                                    'Wedding',
                                    'Mehndi',
                                    'Walima',
                                    'Birthday',
                                    'Engagement',
                                    'Corporate Event',
                                    'Conference',
                                    'Other'
                                ] as $eventType)

                                    <option
                                        value="{{ $eventType }}"
                                        {{ old('event_type', $quotation->event_type) === $eventType ? 'selected' : '' }}
                                    >
                                        {{ $eventType }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- EVENT DATE --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                Event Date

                                <span class="required-star">*</span>

                            </label>

                            <input
                                type="date"
                                name="event_date"
                                class="form-control"
                                value="{{ old('event_date', $quotation->event_date ? \Carbon\Carbon::parse($quotation->event_date)->format('Y-m-d') : '') }}"
                                required
                            >

                        </div>


                        {{-- TIME --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">

                                Time

                                <span class="required-star">*</span>

                            </label>

                            <select
                                name="booking_time"
                                class="form-control form-select"
                                required
                            >

                                <option value="">
                                    Select Time
                                </option>

                                <option
                                    value="day"
                                    {{ old('booking_time', $quotation->booking_time) === 'day' ? 'selected' : '' }}
                                >
                                    Day
                                </option>

                                <option
                                    value="night"
                                    {{ old('booking_time', $quotation->booking_time) === 'night' ? 'selected' : '' }}
                                >
                                    Night
                                </option>

                            </select>

                        </div>


                        {{-- GUESTS --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Number of Guests

                                <span class="required-star">*</span>

                            </label>

                            <input
                                type="number"
                                name="number_of_guests"
                                class="form-control"
                                min="1"
                                value="{{ old('number_of_guests', $quotation->number_of_guests ?? 1) }}"
                                required
                            >

                        </div>


                        {{-- PACKAGE --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Package
                            </label>

                            <input
                                type="text"
                                name="package_name"
                                class="form-control"
                                placeholder="Package name"
                                value="{{ old('package_name', $quotation->package_name ?? '') }}"
                            >

                        </div>


                        {{-- VALID UNTIL --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Valid Until
                            </label>

                            <input
                                type="date"
                                name="valid_until"
                                class="form-control"
                                value="{{ old('valid_until', $quotation->valid_until ? \Carbon\Carbon::parse($quotation->valid_until)->format('Y-m-d') : '') }}"
                            >

                        </div>


                        {{-- MENU --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                <i class="fa fa-cutlery me-1 text-primary"></i>
                                Menu Details
                            </label>

                            <textarea
                                name="menu_details"
                                class="form-control"
                                rows="6"
                                placeholder="Enter menu details..."
                            >{{ old('menu_details', $quotation->menu_details ?? '') }}</textarea>

                        </div>


                        {{-- =================================================
                             SERVICES
                        ================================================= --}}

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                <i class="fa fa-cogs me-1 text-primary"></i>
                                Services

                            </label>


                            <div class="services-wrapper">

                                <div id="services-container">

                                    @if(count($quotationServices) > 0)

                                        @foreach($quotationServices as $index => $quotationService)

                                            @php

                                                $serviceId =
                                                    $quotationService['id']
                                                    ?? $quotationService['service_id']
                                                    ?? '';

                                                $serviceQuantity =
                                                    $quotationService['quantity']
                                                    ?? 1;

                                                $serviceAmount =
                                                    $quotationService['amount']
                                                    ?? 0;

                                            @endphp


                                            <div class="row service-row">


                                                {{-- SERVICE --}}
                                                <div class="col-md-5">

                                                    <label class="form-label small">
                                                        Service
                                                    </label>

                                                    <select
                                                        name="service_ids[]"
                                                        class="form-control form-select service-select"
                                                    >

                                                        <option value="">
                                                            Select Service
                                                        </option>

                                                        @foreach($services as $service)

                                                            <option
                                                                value="{{ $service->id }}"
                                                                data-amount="{{ $service->amount }}"
                                                                {{ (string)$serviceId === (string)$service->id ? 'selected' : '' }}
                                                            >

                                                                {{ $service->service_name }}
                                                                -
                                                                Rs.
                                                                {{ number_format($service->amount, 2) }}

                                                            </option>

                                                        @endforeach

                                                    </select>

                                                </div>


                                                {{-- PRICE --}}
                                                <div class="col-md-3">

                                                    <label class="form-label small">
                                                        Unit Price
                                                    </label>

                                                    <input
                                                        type="number"
                                                        class="form-control service-price"
                                                        value="{{ number_format($serviceAmount, 2, '.', '') }}"
                                                        readonly
                                                    >

                                                </div>


                                                {{-- QUANTITY --}}
                                                <div class="col-md-2">

                                                    <label class="form-label small">
                                                        Qty
                                                    </label>

                                                    <input
                                                        type="number"
                                                        name="service_quantities[]"
                                                        class="form-control service-quantity"
                                                        value="{{ $serviceQuantity }}"
                                                        min="1"
                                                    >

                                                </div>


                                                {{-- ACTION --}}
                                                <div class="col-md-2">

                                                    <label class="form-label small">
                                                        Action
                                                    </label>

                                                    @if($index === 0)

                                                        <button
                                                            type="button"
                                                            class="btn btn-success service-action-btn add-service"
                                                            title="Add Service"
                                                        >
                                                            <i class="fa fa-plus"></i>
                                                        </button>

                                                    @else

                                                        <button
                                                            type="button"
                                                            class="btn btn-danger service-action-btn remove-service"
                                                            title="Remove Service"
                                                        >
                                                            <i class="fa fa-minus"></i>
                                                        </button>

                                                    @endif

                                                </div>

                                            </div>

                                        @endforeach

                                    @else

                                        {{-- EMPTY SERVICE ROW --}}

                                        <div class="row service-row">

                                            <div class="col-md-5">

                                                <label class="form-label small">
                                                    Service
                                                </label>

                                                <select
                                                    name="service_ids[]"
                                                    class="form-control form-select service-select"
                                                >

                                                    <option value="">
                                                        Select Service
                                                    </option>

                                                    @foreach($services as $service)

                                                        <option
                                                            value="{{ $service->id }}"
                                                            data-amount="{{ $service->amount }}"
                                                        >
                                                            {{ $service->service_name }}
                                                            -
                                                            Rs.
                                                            {{ number_format($service->amount, 2) }}
                                                        </option>

                                                    @endforeach

                                                </select>

                                            </div>


                                            <div class="col-md-3">

                                                <label class="form-label small">
                                                    Unit Price
                                                </label>

                                                <input
                                                    type="number"
                                                    class="form-control service-price"
                                                    value="0.00"
                                                    readonly
                                                >

                                            </div>


                                            <div class="col-md-2">

                                                <label class="form-label small">
                                                    Qty
                                                </label>

                                                <input
                                                    type="number"
                                                    name="service_quantities[]"
                                                    class="form-control service-quantity"
                                                    value="1"
                                                    min="1"
                                                >

                                            </div>


                                            <div class="col-md-2">

                                                <label class="form-label small">
                                                    Action
                                                </label>

                                                <button
                                                    type="button"
                                                    class="btn btn-success service-action-btn add-service"
                                                    title="Add Service"
                                                >
                                                    <i class="fa fa-plus"></i>
                                                </button>

                                            </div>

                                        </div>

                                    @endif

                                </div>


                                <span class="service-help">

                                    <i class="fa fa-info-circle me-1"></i>

                                    Service price is automatically multiplied by quantity.

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
                 PRICING
            ========================================================= --}}

            <div class="card quotation-card">

                <div class="card-header">

                    <h5>
                        <i class="fa fa-money text-success me-2"></i>
                        Pricing & Payment
                    </h5>

                    <small>
                        Update quotation pricing, discount, tax and advance payment.
                    </small>

                </div>


                <div class="card-body">

                    <div class="row">


                        {{-- BANQUET --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Banquet Full Booking Amount

                                <span class="required-star">*</span>

                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="banquet_booking_amount"
                                name="banquet_booking_amount"
                                class="form-control pricing-input"
                                value="{{ old('banquet_booking_amount', $quotation->banquet_booking_amount ?? 0) }}"
                                placeholder="Enter booking amount"
                                required
                            >

                            <small class="text-muted">
                                Complete banquet / venue booking amount.
                            </small>

                        </div>


                        {{-- SERVICES TOTAL --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Services Total
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="services_total"
                                name="services_total"
                                class="form-control readonly-field"
                                value="{{ old('services_total', $quotation->services_total ?? 0) }}"
                                readonly
                            >

                            <small class="text-muted">
                                Services price × quantity.
                            </small>

                        </div>


                        {{-- SUBTOTAL --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Subtotal
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="subtotal"
                                name="subtotal"
                                class="form-control readonly-field"
                                value="{{ old('subtotal', $quotation->subtotal ?? 0) }}"
                                readonly
                            >

                            <small class="text-muted">
                                Banquet Amount + Services.
                            </small>

                        </div>


                        {{-- DISCOUNT --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Discount
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="discount"
                                name="discount"
                                class="form-control pricing-input"
                                value="{{ old('discount', $quotation->discount ?? 0) }}"
                                placeholder="Enter discount"
                            >

                            <small class="text-muted">
                                Discount will be deducted from subtotal.
                            </small>

                        </div>


                        {{-- TAX --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Tax
                            </label>

                            @if(optional($settings)->tax_enabled)

                                <div class="alert alert-warning tax-box">

                                    <span class="tax-name">

                                        <i class="fa fa-percent me-1"></i>

                                        {{ optional($settings)->tax_name ?: 'Tax' }}

                                    </span>

                                    <span class="tax-percent">

                                        {{ number_format(optional($settings)->tax_percentage ?? 0, 2) }}%

                                    </span>

                                </div>


                                <input
                                    type="hidden"
                                    id="tax_percentage"
                                    name="tax_percentage"
                                    value="{{ optional($settings)->tax_percentage ?? 0 }}"
                                >


                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    id="tax"
                                    name="tax"
                                    class="form-control readonly-field"
                                    value="{{ old('tax', $quotation->tax ?? 0) }}"
                                    readonly
                                >

                                <small class="text-muted">
                                    Tax is calculated automatically from tenant settings.
                                </small>

                            @else

                                <div class="alert alert-secondary tax-box">

                                    <i class="fa fa-info-circle me-1"></i>

                                    Tax is disabled from Tenant Settings.

                                </div>


                                <input
                                    type="hidden"
                                    id="tax_percentage"
                                    name="tax_percentage"
                                    value="0"
                                >


                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    id="tax"
                                    name="tax"
                                    class="form-control readonly-field"
                                    value="0.00"
                                    readonly
                                >

                            @endif

                        </div>


                        {{-- TOTAL --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Total Amount
                            </label>

                            <div class="total-card">

                                <div class="total-label">
                                    Final Payable Amount
                                </div>

                                <div class="total-value">

                                    Rs.
                                    <span id="total-display">
                                        {{ number_format($quotation->totalamount ?? 0, 2) }}
                                    </span>

                                </div>

                            </div>


                            <input
                                type="hidden"
                                id="totalamount"
                                name="totalamount"
                                value="{{ old('totalamount', $quotation->totalamount ?? 0) }}"
                            >

                        </div>


                        {{-- BOOKING / ADVANCE --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Booking / Advance Amount
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="bookingamount"
                                name="bookingamount"
                                class="form-control pricing-input"
                                value="{{ old('bookingamount', $quotation->bookingamount ?? 0) }}"
                                placeholder="Amount received"
                            >

                            <small class="text-muted">
                                Advance amount received from customer.
                            </small>

                        </div>


                        {{-- REMAINING --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Remaining Amount
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="remainingamount"
                                name="remainingamount"
                                class="form-control readonly-field"
                                value="{{ old('remainingamount', $quotation->remainingamount ?? 0) }}"
                                readonly
                            >

                            <small class="text-muted">
                                Total Amount − Booking / Advance Amount.
                            </small>

                        </div>

                    </div>


                    {{-- =================================================
                         AMOUNT SUMMARY
                    ================================================= --}}

                    <div class="amount-summary">

                        <div class="summary-item">

                            <span>
                                Banquet Amount
                            </span>

                            <strong>
                                Rs.
                                <span id="summary-banquet">
                                    0.00
                                </span>
                            </strong>

                        </div>


                        <div class="summary-item">

                            <span>
                                Services
                            </span>

                            <strong>
                                Rs.
                                <span id="summary-services">
                                    0.00
                                </span>
                            </strong>

                        </div>


                        <div class="summary-item">

                            <span>
                                Discount
                            </span>

                            <strong class="text-danger">

                                - Rs.
                                <span id="summary-discount">
                                    0.00
                                </span>

                            </strong>

                        </div>


                        <div class="summary-item">

                            <span>
                                Tax
                            </span>

                            <strong class="text-warning">

                                + Rs.
                                <span id="summary-tax">
                                    0.00
                                </span>

                            </strong>

                        </div>


                        <hr class="my-2">


                        <div class="summary-item">

                            <strong>
                                Final Total
                            </strong>

                            <strong class="text-success">

                                Rs.
                                <span id="summary-total">
                                    0.00
                                </span>

                            </strong>

                        </div>


                        <div class="summary-item">

                            <span>
                                Advance Paid
                            </span>

                            <strong>
                                Rs.
                                <span id="summary-advance">
                                    0.00
                                </span>
                            </strong>

                        </div>


                        <div class="summary-item">

                            <strong>
                                Remaining
                            </strong>

                            <strong class="text-danger">

                                Rs.
                                <span id="summary-remaining">
                                    0.00
                                </span>

                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================================
                 STATUS & NOTES
            ========================================================= --}}

            <div class="card quotation-card">

                <div class="card-header">

                    <h5>
                        <i class="fa fa-cog text-primary me-2"></i>
                        Status & Notes
                    </h5>

                    <small>
                        Update quotation status and add additional notes.
                    </small>

                </div>


                <div class="card-body">

                    <div class="row">


                        {{-- STATUS --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Quotation Status
                            </label>

                            <select
                                name="status"
                                id="quotation-status"
                                class="form-control form-select"
                            >

                                @foreach([
                                    'draft' => 'Draft',
                                    'sent' => 'Sent',
                                    'accepted' => 'Accepted',
                                    'rejected' => 'Rejected',
                                    'expired' => 'Expired'
                                ] as $statusValue => $statusLabel)

                                    <option
                                        value="{{ $statusValue }}"
                                        {{ old('status', $quotation->status ?? 'draft') === $statusValue ? 'selected' : '' }}
                                    >
                                        {{ $statusLabel }}
                                    </option>

                                @endforeach

                            </select>

                            <div class="status-preview">

                                <span id="status-badge"
                                      class="badge bg-warning text-dark">

                                    Draft

                                </span>

                            </div>

                        </div>


                        {{-- NOTES --}}
                        <div class="col-md-8 mb-3">

                            <label class="form-label">
                                Notes
                            </label>

                            <textarea
                                name="notes"
                                class="form-control"
                                rows="4"
                                placeholder="Additional notes..."
                            >{{ old('notes', $quotation->notes ?? '') }}</textarea>

                        </div>

                    </div>


                    {{-- =================================================
                         ACTIONS
                    ================================================= --}}

                    <div class="form-actions">

                        <button
                            type="submit"
                            class="btn btn-success me-2"
                        >

                            <i class="fa fa-save me-1"></i>

                            Update Quotation

                        </button>


                        <a
                            href="{{ route('quotations.index') }}"
                            class="btn btn-secondary"
                        >

                            <i class="fa fa-times me-1"></i>

                            Cancel

                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    // =========================================================
    // GET ELEMENTS
    // =========================================================

    const banquetBookingAmount =
        document.getElementById('banquet_booking_amount');

    const servicesTotalInput =
        document.getElementById('services_total');

    const subtotalInput =
        document.getElementById('subtotal');

    const discountInput =
        document.getElementById('discount');

    const taxInput =
        document.getElementById('tax');

    const taxPercentageInput =
        document.getElementById('tax_percentage');

    const totalAmountInput =
        document.getElementById('totalamount');

    const bookingAmountInput =
        document.getElementById('bookingamount');

    const remainingAmountInput =
        document.getElementById('remainingamount');


    // =========================================================
    // DISPLAY ELEMENTS
    // =========================================================

    const totalDisplay =
        document.getElementById('total-display');

    const summaryBanquet =
        document.getElementById('summary-banquet');

    const summaryServices =
        document.getElementById('summary-services');

    const summaryDiscount =
        document.getElementById('summary-discount');

    const summaryTax =
        document.getElementById('summary-tax');

    const summaryTotal =
        document.getElementById('summary-total');

    const summaryAdvance =
        document.getElementById('summary-advance');

    const summaryRemaining =
        document.getElementById('summary-remaining');


    // =========================================================
    // FORMAT NUMBER
    // =========================================================

    function formatNumber(number) {

        return Number(number || 0).toLocaleString(
            'en-US',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );

    }


    // =========================================================
    // TAX PERCENTAGE
    // =========================================================

    function getTaxPercentage() {

        if (!taxPercentageInput) {
            return 0;
        }

        let percentage =
            parseFloat(taxPercentageInput.value) || 0;

        if (percentage < 0) {
            percentage = 0;
        }

        return percentage;

    }


    // =========================================================
    // SERVICES TOTAL
    // =========================================================

    function calculateServicesTotal() {

        let serviceTotal = 0;

        document
            .querySelectorAll('.service-row')
            .forEach(function (row) {

                const select =
                    row.querySelector('.service-select');

                const priceInput =
                    row.querySelector('.service-price');

                const quantityInput =
                    row.querySelector('.service-quantity');


                let price = 0;

                let quantity =
                    parseFloat(
                        quantityInput?.value
                    ) || 0;


                if (select && select.value) {

                    const selectedOption =
                        select.options[
                            select.selectedIndex
                        ];

                    price =
                        parseFloat(
                            selectedOption.dataset.amount
                        ) || 0;


                    if (priceInput) {

                        priceInput.value =
                            price.toFixed(2);

                    }

                } else {

                    if (priceInput) {

                        priceInput.value =
                            '0.00';

                    }

                }


                serviceTotal +=
                    price * quantity;

            });


        return serviceTotal;

    }


    // =========================================================
    // MAIN CALCULATION
    // =========================================================

    function calculateTotal() {

        // -----------------------------------------------------
        // BANQUET
        // -----------------------------------------------------

        let banquetAmount =
            parseFloat(
                banquetBookingAmount?.value
            ) || 0;


        if (banquetAmount < 0) {
            banquetAmount = 0;
        }


        // -----------------------------------------------------
        // SERVICES
        // -----------------------------------------------------

        let servicesTotal =
            calculateServicesTotal();


        if (servicesTotalInput) {

            servicesTotalInput.value =
                servicesTotal.toFixed(2);

        }


        // -----------------------------------------------------
        // SUBTOTAL
        // -----------------------------------------------------

        let subtotal =
            banquetAmount + servicesTotal;


        if (subtotal < 0) {
            subtotal = 0;
        }


        if (subtotalInput) {

            subtotalInput.value =
                subtotal.toFixed(2);

        }


        // -----------------------------------------------------
        // DISCOUNT
        // -----------------------------------------------------

        let discount =
            parseFloat(
                discountInput?.value
            ) || 0;


        if (discount < 0) {
            discount = 0;
        }


        if (discount > subtotal) {

            discount = subtotal;


            if (discountInput) {

                discountInput.value =
                    subtotal.toFixed(2);

            }

        }


        // -----------------------------------------------------
        // AFTER DISCOUNT
        // -----------------------------------------------------

        let afterDiscount =
            subtotal - discount;


        if (afterDiscount < 0) {
            afterDiscount = 0;
        }


        // -----------------------------------------------------
        // TAX
        // -----------------------------------------------------

        const taxPercentage =
            getTaxPercentage();


        let tax =
            afterDiscount *
            (taxPercentage / 100);


        if (tax < 0) {
            tax = 0;
        }


        if (taxInput) {

            taxInput.value =
                tax.toFixed(2);

            taxInput.readOnly = true;

        }


        // -----------------------------------------------------
        // TOTAL
        // -----------------------------------------------------

        let total =
            afterDiscount + tax;


        if (total < 0) {
            total = 0;
        }


        if (totalAmountInput) {

            totalAmountInput.value =
                total.toFixed(2);

        }


        if (totalDisplay) {

            totalDisplay.textContent =
                formatNumber(total);

        }


        // -----------------------------------------------------
        // BOOKING / ADVANCE
        // -----------------------------------------------------

        let bookingAmount =
            parseFloat(
                bookingAmountInput?.value
            ) || 0;


        if (bookingAmount < 0) {
            bookingAmount = 0;
        }


        if (bookingAmount > total) {

            bookingAmount = total;


            if (bookingAmountInput) {

                bookingAmountInput.value =
                    total.toFixed(2);

            }

        }


        // -----------------------------------------------------
        // REMAINING
        // -----------------------------------------------------

        let remaining =
            total - bookingAmount;


        if (remaining < 0) {
            remaining = 0;
        }


        if (remainingAmountInput) {

            remainingAmountInput.value =
                remaining.toFixed(2);

        }


        // -----------------------------------------------------
        // UPDATE SUMMARY
        // -----------------------------------------------------

        if (summaryBanquet) {

            summaryBanquet.textContent =
                formatNumber(banquetAmount);

        }


        if (summaryServices) {

            summaryServices.textContent =
                formatNumber(servicesTotal);

        }


        if (summaryDiscount) {

            summaryDiscount.textContent =
                formatNumber(discount);

        }


        if (summaryTax) {

            summaryTax.textContent =
                formatNumber(tax);

        }


        if (summaryTotal) {

            summaryTotal.textContent =
                formatNumber(total);

        }


        if (summaryAdvance) {

            summaryAdvance.textContent =
                formatNumber(bookingAmount);

        }


        if (summaryRemaining) {

            summaryRemaining.textContent =
                formatNumber(remaining);

        }

    }


    // =========================================================
    // BANQUET EVENTS
    // =========================================================

    if (banquetBookingAmount) {

        banquetBookingAmount.addEventListener(
            'input',
            calculateTotal
        );

        banquetBookingAmount.addEventListener(
            'change',
            calculateTotal
        );

    }


    // =========================================================
    // DISCOUNT EVENTS
    // =========================================================

    if (discountInput) {

        discountInput.addEventListener(
            'input',
            calculateTotal
        );

        discountInput.addEventListener(
            'change',
            calculateTotal
        );

    }


    // =========================================================
    // ADVANCE EVENTS
    // =========================================================

    if (bookingAmountInput) {

        bookingAmountInput.addEventListener(
            'input',
            calculateTotal
        );

        bookingAmountInput.addEventListener(
            'change',
            calculateTotal
        );

    }


    // =========================================================
    // SERVICE SELECT
    // =========================================================

    document.addEventListener(
        'change',
        function (event) {

            if (
                event.target.classList.contains(
                    'service-select'
                )
            ) {

                calculateTotal();

            }

        }
    );


    // =========================================================
    // SERVICE QUANTITY
    // =========================================================

    document.addEventListener(
        'input',
        function (event) {

            if (
                event.target.classList.contains(
                    'service-quantity'
                )
            ) {

                calculateTotal();

            }

        }
    );


    // =========================================================
    // ADD SERVICE
    // =========================================================

    document.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest('.add-service');


            if (!button) {
                return;
            }


            const container =
                document.getElementById(
                    'services-container'
                );


            if (!container) {
                return;
            }


            const firstRow =
                container.querySelector(
                    '.service-row'
                );


            if (!firstRow) {
                return;
            }


            const newRow =
                firstRow.cloneNode(true);


            // RESET SELECT

            const newSelect =
                newRow.querySelector(
                    '.service-select'
                );


            if (newSelect) {

                newSelect.value = '';

            }


            // RESET PRICE

            const newPrice =
                newRow.querySelector(
                    '.service-price'
                );


            if (newPrice) {

                newPrice.value =
                    '0.00';

            }


            // RESET QUANTITY

            const newQuantity =
                newRow.querySelector(
                    '.service-quantity'
                );


            if (newQuantity) {

                newQuantity.value =
                    '1';

            }


            // CHANGE BUTTON

            const newButton =
                newRow.querySelector(
                    '.add-service'
                );


            if (newButton) {

                newButton.classList.remove(
                    'btn-success'
                );

                newButton.classList.add(
                    'btn-danger'
                );

                newButton.classList.remove(
                    'add-service'
                );

                newButton.classList.add(
                    'remove-service'
                );

                newButton.innerHTML =
                    '<i class="fa fa-minus"></i>';

                newButton.title =
                    'Remove Service';

            }


            container.appendChild(
                newRow
            );


            calculateTotal();

        }
    );


    // =========================================================
    // REMOVE SERVICE
    // =========================================================

    document.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest(
                    '.remove-service'
                );


            if (!button) {
                return;
            }


            const rows =
                document.querySelectorAll(
                    '.service-row'
                );


            // At least one row remains

            if (rows.length > 1) {

                const row =
                    button.closest(
                        '.service-row'
                    );


                if (row) {

                    row.remove();

                }


                calculateTotal();

            }

        }
    );


    // =========================================================
    // STATUS BADGE
    // =========================================================

    const statusSelect =
        document.getElementById(
            'quotation-status'
        );

    const statusBadge =
        document.getElementById(
            'status-badge'
        );


    function updateStatusBadge() {

        if (!statusSelect || !statusBadge) {
            return;
        }


        const status =
            statusSelect.value;


        statusBadge.className =
            'badge';


        if (status === 'accepted') {

            statusBadge.classList.add(
                'bg-success'
            );

            statusBadge.innerHTML =
                '<i class="fa fa-check me-1"></i> Accepted';

        }
        else if (status === 'rejected') {

            statusBadge.classList.add(
                'bg-danger'
            );

            statusBadge.innerHTML =
                '<i class="fa fa-times me-1"></i> Rejected';

        }
        else if (status === 'sent') {

            statusBadge.classList.add(
                'bg-info'
            );

            statusBadge.innerHTML =
                '<i class="fa fa-paper-plane me-1"></i> Sent';

        }
        else if (status === 'expired') {

            statusBadge.classList.add(
                'bg-secondary'
            );

            statusBadge.innerHTML =
                '<i class="fa fa-calendar me-1"></i> Expired';

        }
        else {

            statusBadge.classList.add(
                'bg-warning',
                'text-dark'
            );

            statusBadge.innerHTML =
                '<i class="fa fa-file me-1"></i> Draft';

        }

    }


    if (statusSelect) {

        statusSelect.addEventListener(
            'change',
            updateStatusBadge
        );

    }


    // =========================================================
    // INITIAL LOAD
    // =========================================================

    updateStatusBadge();

    calculateTotal();

});

</script>


@include('tenant.footer')

</body>

</html>