<!DOCTYPE html> <html lang="en"> <head>
<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Create Quotation</title>

</head> <body>
@include('tenant.nav')

<div class="page-wrapper">
<div class="container-fluid">

    {{-- PAGE TITLE --}}
    <div class="row page-titles">

        <div class="col-md-6">

            <h4 class="text-themecolor">

                <i class="ti-file me-2"></i>
                Create Quotation

            </h4>

        </div>

    </div>

    {{-- ERRORS --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

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

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    <form
        action="{{ route('quotations.store') }}"
        method="POST">

        @csrf

        {{-- ========================================================= --}}
        {{-- CUSTOMER & EVENT --}}
        {{-- ========================================================= --}}

        <div class="card">

            <div class="card-body">

                <h4 class="card-title">
                    Customer & Event Information
                </h4>

                <h6 class="card-subtitle">
                    Enter customer and event details
                </h6>

                <div class="row">

                    {{-- CUSTOMER NAME --}}
                    <div class="col-md-6">

                        <div class="form-floating mb-3">

                            <input
                                type="text"
                                name="customer_name"
                                id="customer_name"
                                class="form-control"
                                placeholder="Customer Name"
                                value="{{ old('customer_name') }}"
                                required
                            >

                            <label for="customer_name">
                                Customer Name
                                <span class="text-danger">*</span>
                            </label>

                        </div>

                    </div>

                    {{-- VENUE --}}
                    <div class="col-md-6">

                        <div class="form-floating mb-3">

                            <select
                                name="lawn_type_id"
                                id="lawn_type_id"
                                class="form-control form-select"
                                required>

                                <option value="">
                                    Select Venue
                                </option>

                                @foreach($lawnTypes as $lawnType)

                                    <option
                                        value="{{ $lawnType->id }}"
                                        {{ old('lawn_type_id') == $lawnType->id ? 'selected' : '' }}>

                                        {{ $lawnType->lawn_type }}

                                    </option>

                                @endforeach

                            </select>

                            <label for="lawn_type_id">
                                Venue / Lawn
                                <span class="text-danger">*</span>
                            </label>

                        </div>

                    </div>

                    {{-- EVENT TYPE --}}
                    <div class="col-md-6">

                        <div class="form-floating mb-3">

                            <select
                                name="event_type"
                                id="event_type"
                                class="form-control form-select"
                                required>

                                <option value="">
                                    Select Event
                                </option>

                                <option
                                    value="Wedding"
                                    {{ old('event_type') == 'Wedding' ? 'selected' : '' }}>
                                    Wedding
                                </option>

                                <option
                                    value="Mehndi"
                                    {{ old('event_type') == 'Mehndi' ? 'selected' : '' }}>
                                    Mehndi
                                </option>

                                <option
                                    value="Walima"
                                    {{ old('event_type') == 'Walima' ? 'selected' : '' }}>
                                    Walima
                                </option>

                                <option
                                    value="Birthday"
                                    {{ old('event_type') == 'Birthday' ? 'selected' : '' }}>
                                    Birthday
                                </option>

                                <option
                                    value="Engagement"
                                    {{ old('event_type') == 'Engagement' ? 'selected' : '' }}>
                                    Engagement
                                </option>

                                <option
                                    value="Corporate Event"
                                    {{ old('event_type') == 'Corporate Event' ? 'selected' : '' }}>
                                    Corporate Event
                                </option>

                                <option
                                    value="Conference"
                                    {{ old('event_type') == 'Conference' ? 'selected' : '' }}>
                                    Conference
                                </option>

                                <option
                                    value="Other"
                                    {{ old('event_type') == 'Other' ? 'selected' : '' }}>
                                    Other
                                </option>

                            </select>

                            <label for="event_type">
                                Event Type
                                <span class="text-danger">*</span>
                            </label>

                        </div>

                    </div>

                    {{-- EVENT DATE --}}
                    <div class="col-md-3">

                        <div class="form-floating mb-3">

                            <input
                                type="date"
                                name="event_date"
                                id="event_date"
                                class="form-control"
                                value="{{ old('event_date') }}"
                                required
                            >

                            <label for="event_date">
                                Event Date
                                <span class="text-danger">*</span>
                            </label>

                        </div>

                    </div>

                    {{-- TIME --}}
                    <div class="col-md-3">

                        <div class="form-floating mb-3">

                            <select
                                name="booking_time"
                                id="booking_time"
                                class="form-control form-select"
                                required>

                                <option value="">
                                    Select Time
                                </option>

                                <option
                                    value="day"
                                    {{ old('booking_time') === 'day' ? 'selected' : '' }}>
                                    Day
                                </option>

                                <option
                                    value="night"
                                    {{ old('booking_time') === 'night' ? 'selected' : '' }}>
                                    Night
                                </option>

                            </select>

                            <label for="booking_time">
                                Time
                                <span class="text-danger">*</span>
                            </label>

                        </div>

                    </div>

                    {{-- GUESTS --}}
                    <div class="col-md-4">

                        <div class="form-floating mb-3">

                            <input
                                type="number"
                                name="number_of_guests"
                                id="number_of_guests"
                                class="form-control"
                                min="1"
                                value="{{ old('number_of_guests', 1) }}"
                                placeholder="Number of Guests"
                                required
                            >

                            <label for="number_of_guests">
                                Number of Guests
                                <span class="text-danger">*</span>
                            </label>

                        </div>

                    </div>

                    {{-- PACKAGE --}}
                    <div class="col-md-4">

                        <div class="form-floating mb-3">

                            <input
                                type="text"
                                name="package_name"
                                id="package_name"
                                class="form-control"
                                placeholder="Package Name"
                                value="{{ old('package_name') }}"
                            >

                            <label for="package_name">
                                Package
                            </label>

                        </div>

                    </div>

                    {{-- VALID UNTIL --}}
                    <div class="col-md-4">

                        <div class="form-floating mb-3">

                            <input
                                type="date"
                                name="valid_until"
                                id="valid_until"
                                class="form-control"
                                value="{{ old('valid_until') }}"
                            >

                            <label for="valid_until">
                                Valid Until
                            </label>

                        </div>

                    </div>

                    {{-- MENU --}}
                    <div class="col-md-6">

                        <div class="form-floating mb-3">

                            <textarea
                                name="menu_details"
                                id="menu_details"
                                class="form-control"
                                placeholder="Menu Details"
                                style="height: 120px;"
                            >{{ old('menu_details') }}</textarea>

                            <label for="menu_details">
                                Menu Details
                            </label>

                        </div>

                    </div>

{{-- ========================================================= --}}
{{-- PAID SERVICES --}}
{{-- ========================================================= --}}

<div class="col-md-6">

    <h6 class="mb-3">
        Paid Services
    </h6>

    <div id="services-container">

        <div class="row service-row mb-2">

            <div class="col-md-5">

                <div class="form-floating">

                    <select
                        name="service_ids[]"
                        class="form-control form-select service-select"
                    >

                        <option value="">
                            Select Paid Service
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

                    <label>
                        Paid Service
                    </label>

                </div>

            </div>


            <div class="col-md-3">

                <div class="form-floating">

                    <input
                        type="number"
                        class="form-control service-price"
                        value="0.00"
                        readonly
                    >

                    <label>
                        Unit Price
                    </label>

                </div>

            </div>


            <div class="col-md-2">

                <div class="form-floating">

                    <input
                        type="number"
                        name="service_quantities[]"
                        class="form-control service-quantity"
                        value="1"
                        min="1"
                    >

                    <label>
                        Qty
                    </label>

                </div>

            </div>


            <div class="col-md-2 d-flex align-items-center">

                <button
                    type="button"
                    class="btn btn-success add-service"
                >

                    <i class="fa fa-plus"></i>

                    Add

                </button>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- FREE SERVICES --}}
{{-- ========================================================= --}}

<div class="col-md-6">

    <h6 class="mb-3">
        Free Services
    </h6>

    <div id="free-services-container">

        <div class="row free-service-row mb-2">

            <div class="col-md-7">

                <div class="form-floating">

                    <select
                        name="free_service_ids[]"
                        class="form-control form-select free-service-select"
                    >

                        <option value="">
                            Select Free Service
                        </option>

                        @foreach($freeServices as $freeService)

                            <option
                                value="{{ $freeService->id }}"
                            >

                                {{ $freeService->service_name }}

                            </option>

                        @endforeach

                    </select>

                    <label>
                        Free Service
                    </label>

                </div>

            </div>


            <div class="col-md-3">

                <div class="form-floating">

                    <input
                        type="number"
                        name="free_service_quantities[]"
                        class="form-control free-service-quantity"
                        value="1"
                        min="1"
                    >

                    <label>
                        Qty
                    </label>

                </div>

            </div>


            <div class="col-md-2 d-flex align-items-center">

                <button
                    type="button"
                    class="btn btn-success add-free-service"
                >

                    <i class="fa fa-plus"></i>

                    Add

                </button>

            </div>

        </div>

    </div>


    <small class="text-muted">

        Free services do not increase quotation amount.

    </small>

</div>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- PRICING --}}
        {{-- ========================================================= --}}

        <div class="card">

            <div class="card-body">

                <h4 class="card-title">
                    Pricing
                </h4>

                <h6 class="card-subtitle">
                    Calculate quotation pricing and payment
                </h6>

                <div class="row">

                    {{-- BANQUET --}}
                    <div class="col-md-4">

                        <div class="form-floating mb-3">

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="banquet_booking_amount"
                                name="banquet_booking_amount"
                                class="form-control"
                                value="{{ old('banquet_booking_amount', 0) }}"
                                placeholder="Banquet Amount"
                                required
                            >

                            <label for="banquet_booking_amount">
                                Banquet Full Booking Amount
                                <span class="text-danger">*</span>
                            </label>

                        </div>

                        <small class="text-muted">
                            Complete banquet / venue booking amount.
                        </small>

                    </div>

                    {{-- SERVICES TOTAL --}}
                    <div class="col-md-4">

                        <div class="form-floating mb-3">

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="services_total"
                                name="services_total"
                                class="form-control"
                                value="{{ old('services_total', 0) }}"
                                placeholder="Services Total"
                                readonly
                            >

                            <label for="services_total">
                                Services Total
                            </label>

                        </div>

                        <small class="text-muted">
                            Services price × quantity.
                        </small>

                    </div>

                    {{-- SUBTOTAL --}}
                    <div class="col-md-4">

                        <div class="form-floating mb-3">

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="subtotal"
                                name="subtotal"
                                class="form-control"
                                value="{{ old('subtotal', 0) }}"
                                placeholder="Subtotal"
                                readonly
                            >

                            <label for="subtotal">
                                Subtotal
                            </label>

                        </div>

                        <small class="text-muted">
                            Banquet Amount + Services.
                        </small>

                    </div>

                    {{-- DISCOUNT --}}
                    <div class="col-md-4">

                        <div class="form-floating mb-3">

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="discount"
                                name="discount"
                                class="form-control"
                                value="{{ old('discount', 0) }}"
                                placeholder="Discount"
                            >

                            <label for="discount">
                                Discount
                            </label>

                        </div>

                    </div>

                    {{-- TAX --}}
                    <div class="col-md-4">

                        <div class="form-floating mb-3">

                            @if(optional($settings)->tax_enabled)

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    id="tax"
                                    name="tax"
                                    class="form-control"
                                    value="{{ old('tax', 0) }}"
                                    placeholder="Tax"
                                    readonly
                                >

                                <label for="tax">
                                    Tax
                                    ({{ number_format(optional($settings)->tax_percentage ?? 0, 2) }}%)
                                </label>

                                <input
                                    type="hidden"
                                    id="tax_percentage"
                                    name="tax_percentage"
                                    value="{{ optional($settings)->tax_percentage ?? 0 }}"
                                >

                            @else

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    id="tax"
                                    name="tax"
                                    class="form-control"
                                    value="0.00"
                                    placeholder="Tax"
                                    readonly
                                >

                                <label for="tax">
                                    Tax
                                </label>

                                <input
                                    type="hidden"
                                    id="tax_percentage"
                                    name="tax_percentage"
                                    value="0"
                                >

                            @endif

                        </div>

                        <small class="text-muted">

                            @if(optional($settings)->tax_enabled)

                                Tax automatically calculated from
                                tenant settings.

                            @else

                                Tax is disabled from Tenant Settings.

                            @endif

                        </small>

                    </div>

                    {{-- TOTAL --}}
                    <div class="col-md-4">

                        <div class="form-floating mb-3">

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="totalamount"
                                name="totalamount"
                                class="form-control"
                                value="{{ old('totalamount', 0) }}"
                                placeholder="Total Amount"
                                readonly
                            >

                            <label for="totalamount">
                                Total Amount
                            </label>

                        </div>

                        <small class="text-muted">
                            Final payable amount.
                        </small>

                    </div>

                    {{-- BOOKING / ADVANCE --}}
                    <div class="col-md-4">

                        <div class="form-floating mb-3">

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="bookingamount"
                                name="bookingamount"
                                class="form-control"
                                value="{{ old('bookingamount', 0) }}"
                                placeholder="Booking Amount"
                            >

                            <label for="bookingamount">
                                Booking / Advance Amount
                            </label>

                        </div>

                        <small class="text-muted">
                            Advance amount received from customer.
                        </small>

                    </div>

                    {{-- REMAINING --}}
                    <div class="col-md-4">

                        <div class="form-floating mb-3">

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="remainingamount"
                                name="remainingamount"
                                class="form-control"
                                value="{{ old('remainingamount', 0) }}"
                                placeholder="Remaining Amount"
                                readonly
                            >

                            <label for="remainingamount">
                                Remaining Amount
                            </label>

                        </div>

                        <small class="text-muted">
                            Total Amount − Booking / Advance Amount.
                        </small>

                    </div>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- STATUS & NOTES --}}
        {{-- ========================================================= --}}

        <div class="card">

            <div class="card-body">

                <h4 class="card-title">
                    Status & Notes
                </h4>

                <h6 class="card-subtitle">
                    Set quotation status and additional information
                </h6>

                <div class="row">

                    {{-- STATUS --}}
                    <div class="col-md-4">

                        <div class="form-floating mb-3">

                            <select
                                name="status"
                                id="status"
                                class="form-control form-select">

                                <option
                                    value="draft"
                                    {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>
                                    Draft
                                </option>

                                <option
                                    value="sent"
                                    {{ old('status') === 'sent' ? 'selected' : '' }}>
                                    Sent
                                </option>

                                <option
                                    value="accepted"
                                    {{ old('status') === 'accepted' ? 'selected' : '' }}>
                                    Accepted
                                </option>

                                <option
                                    value="rejected"
                                    {{ old('status') === 'rejected' ? 'selected' : '' }}>
                                    Rejected
                                </option>

                                <option
                                    value="expired"
                                    {{ old('status') === 'expired' ? 'selected' : '' }}>
                                    Expired
                                </option>

                            </select>

                            <label for="status">
                                Status
                            </label>

                        </div>

                    </div>

                    {{-- NOTES --}}
                    <div class="col-md-8">

                        <div class="form-floating mb-3">

                            <textarea
                                name="notes"
                                id="notes"
                                class="form-control"
                                placeholder="Additional Notes"
                                style="height: 100px;"
                            >{{ old('notes') }}</textarea>

                            <label for="notes">
                                Notes
                            </label>

                        </div>

                    </div>

                </div>

                {{-- BUTTONS --}}
                <div class="d-md-flex align-items-center mt-3">

                    <div class="ms-auto">

                        <a
                            href="{{ route('quotations.index') }}"
                            class="btn btn-secondary waves-effect waves-light me-1">

                            <span class="btn-label">
                                <i class="fa fa-arrow-left"></i>
                            </span>

                            Cancel

                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary text-white waves-effect waves-light">

                            <span class="btn-label">
                                <i class="fa fa-save"></i>
                            </span>

                            Save Quotation

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

</div> <script>
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

    let banquetAmount =
        parseFloat(
            banquetBookingAmount?.value
        ) || 0;

    if (banquetAmount < 0) {
        banquetAmount = 0;
    }

    // SERVICES

    let servicesTotal =
        calculateServicesTotal();

    if (servicesTotalInput) {

        servicesTotalInput.value =
            servicesTotal.toFixed(2);

    }

    // SUBTOTAL

    let subtotal =
        banquetAmount + servicesTotal;

    if (subtotal < 0) {
        subtotal = 0;
    }

    if (subtotalInput) {

        subtotalInput.value =
            subtotal.toFixed(2);

    }

    // DISCOUNT

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

    // AFTER DISCOUNT

    let afterDiscount =
        subtotal - discount;

    if (afterDiscount < 0) {
        afterDiscount = 0;
    }

    // TAX

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

    // TOTAL

    let total =
        afterDiscount + tax;

    if (total < 0) {
        total = 0;
    }

    if (totalAmountInput) {

        totalAmountInput.value =
            total.toFixed(2);

    }

    // BOOKING / ADVANCE

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

    // REMAINING

    let remaining =
        total - bookingAmount;

    if (remaining < 0) {
        remaining = 0;
    }

    if (remainingAmountInput) {

        remainingAmountInput.value =
            remaining.toFixed(2);

    }

}

// =========================================================
// INPUT EVENTS
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
            event.target.closest(
                '.add-service'
            );

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

        // CHANGE BUTTON TO REMOVE

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
                '<span class="btn-label">' +
                '<i class="fa fa-minus"></i>' +
                '</span> Remove';

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
// INITIAL CALCULATION
// =========================================================

calculateTotal();

});

</script>
@include('tenant.footer')

</body> </html>