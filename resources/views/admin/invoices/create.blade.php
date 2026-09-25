<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @include('admin.nav')
    <div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="row page-titles">
        <div class="col-md-7 align-self-center">
            <h3 class="text-themecolor">
                <i class="ti-receipt me-2"></i>
                Create Tenant Payment Invoice
            </h3>

            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('/admin') }}">
                        Dashboard
                    </a>
                </li>

                <li class="breadcrumb-item">
                    Invoices
                </li>

                <li class="breadcrumb-item active">
                    Create
                </li>
            </ol>
        </div>

        <div class="col-md-5 text-end">
            <a
                href="{{ url('/admin/tenant-payments') }}"
                class="btn btn-secondary"
            >
                <i class="fa fa-arrow-left me-1"></i>
                Back
            </a>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- ALERTS --}}
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
            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- ========================================================= --}}
    {{-- FORM --}}
    {{-- ========================================================= --}}

    <div class="row">

        <div class="col-lg-12">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>
                            <h4 class="card-title mb-1">
                                Tenant Subscription Payment
                            </h4>

                            <p class="text-muted mb-0">
                                Record subscription payment received from a tenant.
                            </p>
                        </div>

                        <span class="badge bg-primary">
                            Subscription Invoice
                        </span>

                    </div>


                    <form
                        action="{{ route('admin.tenant-payments.store') }}"
                        method="POST"
                        id="tenantPaymentInvoiceForm"
                    >

                        @csrf


                        {{-- ================================================= --}}
                        {{-- TENANT INFORMATION --}}
                        {{-- ================================================= --}}

                        <div class="card border mb-4">

                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="ti-user me-1"></i>
                                    Tenant Information
                                </h5>
                            </div>

                            <div class="card-body">

                                <div class="row">

                                    {{-- TENANT --}}
                                    <div class="col-md-6 mb-3">

                                        <label
                                            for="tenant_id"
                                            class="form-label"
                                        >
                                            Tenant Name
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select
                                            name="tenant_id"
                                            id="tenant_id"
                                            class="form-select @error('tenant_id') is-invalid @enderror"
                                            required
                                        >

                                            <option value="">
                                                -- Select Tenant --
                                            </option>

                                            @foreach($tenants as $tenant)

                                                @php
                                                    $subscription = $tenant->activeSubscription;
                                                @endphp

                                                <option
                                                    value="{{ $tenant->id }}"

                                                    data-subscription-id="{{ $subscription?->id ?? '' }}"
                                                    data-subscription-amount="{{ $subscription?->amount ?? 0 }}"
                                                    data-outstanding="{{ $tenant->current_outstanding ?? 0 }}"
                                                    data-created-at="{{ optional($tenant->created_at)->format('d-m-Y') }}"
                                                    data-owner-name="{{ $tenant->owner_name ?? '' }}"
                                                    data-email="{{ $tenant->email ?? '' }}"
                                                    data-address="{{ $tenant->address ?? '' }}"

                                                    {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}
                                                >

                                                    {{ $tenant->business_name ?? $tenant->owner_name }}

                                                </option>

                                            @endforeach

                                        </select>

                                        @error('tenant_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    {{-- SUBSCRIPTION ID --}}
                                    <div class="col-md-6 mb-3">

                                        <label
                                            for="subscription_id"
                                            class="form-label"
                                        >
                                            Subscription
                                        </label>

                                        <select
                                            name="subscription_id"
                                            id="subscription_id"
                                            class="form-select"
                                            required
                                        >

                                            <option value="">
                                                -- Select Tenant First --
                                            </option>

                                        </select>

                                    </div>

                                </div>


                                {{-- AUTO FETCHED TENANT DETAILS --}}

                                <div
                                    id="tenantDetailsCard"
                                    class="row mt-2"
                                    style="display:none;"
                                >

                                    {{-- TENANT NAME --}}
                                    <div class="col-md-3 mb-3">

                                        <label class="form-label">
                                            Tenant Name
                                        </label>

                                        <input
                                            type="text"
                                            id="display_tenant_name"
                                            class="form-control"
                                            readonly
                                        >

                                    </div>


                                    {{-- OWNER --}}
                                    <div class="col-md-3 mb-3">

                                        <label class="form-label">
                                            Owner Name
                                        </label>

                                        <input
                                            type="text"
                                            id="display_owner_name"
                                            class="form-control"
                                            readonly
                                        >

                                    </div>


                                    {{-- ACCOUNT CREATION --}}
                                    <div class="col-md-3 mb-3">

                                        <label class="form-label">
                                            Account Creation Date
                                        </label>

                                        <input
                                            type="text"
                                            id="display_created_at"
                                            class="form-control"
                                            readonly
                                        >

                                    </div>


                                    {{-- EMAIL --}}
                                    <div class="col-md-3 mb-3">

                                        <label class="form-label">
                                            Address / Email
                                        </label>

                                        <input
                                            type="text"
                                            id="display_email"
                                            class="form-control"
                                            readonly
                                        >

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- SUBSCRIPTION INFORMATION --}}
                        {{-- ================================================= --}}

                        <div class="card border mb-4">

                            <div class="card-header bg-light">

                                <h5 class="mb-0">
                                    <i class="ti-wallet me-1"></i>
                                    Subscription Payment
                                </h5>

                            </div>


                            <div class="card-body">

                                <div class="row">

                                    {{-- SUBSCRIPTION PAYMENT FETCH --}}
                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">
                                            Subscription Payment Fetch
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                PKR
                                            </span>

                                            <input
                                                type="text"
                                                id="subscription_amount_display"
                                                class="form-control"
                                                value="0.00"
                                                readonly
                                            >

                                        </div>

                                    </div>


                                    {{-- CURRENT OUTSTANDING --}}
                                    <div class="col-md-4 mb-3">

                                        <label class="form-label">
                                            Current Remaining
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                PKR
                                            </span>

                                            <input
                                                type="text"
                                                id="current_outstanding_display"
                                                class="form-control"
                                                value="0.00"
                                                readonly
                                            >

                                        </div>

                                    </div>


                                    {{-- PAYMENT DATE --}}
                                    <div class="col-md-4 mb-3">

                                        <label
                                            for="payment_date"
                                            class="form-label"
                                        >
                                            Payment Date
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="date"
                                            name="payment_date"
                                            id="payment_date"
                                            class="form-control @error('payment_date') is-invalid @enderror"
                                            value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                                            required
                                        >

                                        @error('payment_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>


                                <div class="row">

                                    {{-- PAYMENT TYPE --}}
                                    <div class="col-md-4 mb-3">

                                        <label
                                            for="payment_method"
                                            class="form-label"
                                        >
                                            Payment Type
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select
                                            name="payment_method"
                                            id="payment_method"
                                            class="form-select @error('payment_method') is-invalid @enderror"
                                            required
                                        >

                                            <option value="">
                                                -- Select Payment Type --
                                            </option>

                                            <option
                                                value="cash"
                                                {{ old('payment_method') === 'cash' ? 'selected' : '' }}
                                            >
                                                Cash
                                            </option>

                                            <option
                                                value="bank"
                                                {{ old('payment_method') === 'bank' ? 'selected' : '' }}
                                            >
                                                Bank
                                            </option>

                                            <option
                                                value="online"
                                                {{ old('payment_method') === 'online' ? 'selected' : '' }}
                                            >
                                                Online
                                            </option>

                                            <option
                                                value="card"
                                                {{ old('payment_method') === 'card' ? 'selected' : '' }}
                                            >
                                                Card
                                            </option>

                                        </select>

                                        @error('payment_method')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    {{-- PAYMENT IN / OUT --}}
                                    <div class="col-md-4 mb-3">

                                        <label
                                            for="payment_direction"
                                            class="form-label"
                                        >
                                            Payment (In / Out)
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select
                                            name="payment_direction"
                                            id="payment_direction"
                                            class="form-select @error('payment_direction') is-invalid @enderror"
                                            required
                                        >

                                            <option value="in"
                                                {{ old('payment_direction', 'in') === 'in' ? 'selected' : '' }}>
                                                Payment In
                                            </option>

                                            <option value="out"
                                                {{ old('payment_direction') === 'out' ? 'selected' : '' }}>
                                                Payment Out
                                            </option>

                                        </select>

                                        @error('payment_direction')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>


                                    {{-- PAYMENT AMOUNT --}}
                                    <div class="col-md-4 mb-3">

                                        <label
                                            for="amount"
                                            class="form-label"
                                        >
                                            Payment Amount
                                            <span class="text-danger">*</span>
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text">
                                                PKR
                                            </span>

                                            <input
                                                type="number"
                                                name="amount"
                                                id="amount"
                                                class="form-control @error('amount') is-invalid @enderror"
                                                value="{{ old('amount', 0) }}"
                                                min="0.01"
                                                step="0.01"
                                                required
                                            >

                                        </div>

                                        @error('amount')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>


                                {{-- ================================================= --}}
                                {{-- REMAINING AMOUNT --}}
                                {{-- ================================================= --}}

                                <div class="row mt-2">

                                    <div class="col-md-4 offset-md-8">

                                        <div class="card bg-light border">

                                            <div class="card-body">

                                                <div class="d-flex justify-content-between">

                                                    <span class="fw-semibold">
                                                        Remaining Amount
                                                    </span>

                                                    <span
                                                        id="remaining_amount_display"
                                                        class="fw-bold"
                                                    >
                                                        PKR 0.00
                                                    </span>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- ================================================= --}}
                                {{-- OPTIONAL NOTES --}}
                                {{-- ================================================= --}}

                                <div class="row mt-3">

                                    <div class="col-md-6 mb-3">

                                        <label
                                            for="transaction_reference"
                                            class="form-label"
                                        >
                                            Transaction Reference
                                        </label>

                                        <input
                                            type="text"
                                            name="transaction_reference"
                                            id="transaction_reference"
                                            class="form-control"
                                            value="{{ old('transaction_reference') }}"
                                            placeholder="Optional"
                                        >

                                    </div>


                                    <div class="col-md-6 mb-3">

                                        <label
                                            for="notes"
                                            class="form-label"
                                        >
                                            Notes
                                        </label>

                                        <textarea
                                            name="notes"
                                            id="notes"
                                            rows="2"
                                            class="form-control"
                                            placeholder="Optional notes..."
                                        >{{ old('notes') }}</textarea>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- ACTION BUTTONS --}}
                        {{-- ================================================= --}}

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ url('/admin/tenant-payments') }}"
                                class="btn btn-secondary"
                            >
                                <i class="fa fa-times me-1"></i>
                                Cancel
                            </a>

                            <button
                                type="submit"
                                id="savePaymentBtn"
                                class="btn btn-success text-white"
                            >
                                <i class="fa fa-save me-1"></i>
                                Save Payment Invoice
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================== --}}
{{-- JAVASCRIPT --}}
{{-- =============================================================== --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tenantSelect =
        document.getElementById('tenant_id');

    const subscriptionSelect =
        document.getElementById('subscription_id');

    const tenantDetailsCard =
        document.getElementById('tenantDetailsCard');

    const tenantName =
        document.getElementById('display_tenant_name');

    const ownerName =
        document.getElementById('display_owner_name');

    const createdAt =
        document.getElementById('display_created_at');

    const email =
        document.getElementById('display_email');

    const subscriptionAmount =
        document.getElementById('subscription_amount_display');

    const currentOutstanding =
        document.getElementById('current_outstanding_display');

    const paymentDirection =
        document.getElementById('payment_direction');

    const amountInput =
        document.getElementById('amount');

    const remainingDisplay =
        document.getElementById('remaining_amount_display');

    const form =
        document.getElementById('tenantPaymentInvoiceForm');

    const saveButton =
        document.getElementById('savePaymentBtn');


    /*
    |--------------------------------------------------------------------------
    | FORMAT MONEY
    |--------------------------------------------------------------------------
    */

    function money(value) {

        const number =
            parseFloat(value || 0);

        return number.toLocaleString(
            'en-PK',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE TENANT DATA
    |--------------------------------------------------------------------------
    */

    function updateTenantData() {

        const option =
            tenantSelect.options[
                tenantSelect.selectedIndex
            ];

        if (
            !option ||
            !option.value
        ) {

            subscriptionSelect.innerHTML =
                '<option value="">-- Select Tenant First --</option>';

            subscriptionAmount.value =
                '0.00';

            currentOutstanding.value =
                '0.00';

            tenantDetailsCard.style.display =
                'none';

            updateRemaining();

            return;
        }


        const subscriptionId =
            option.dataset.subscriptionId || '';

        const amount =
            parseFloat(
                option.dataset.subscriptionAmount || 0
            );

        const outstanding =
            parseFloat(
                option.dataset.outstanding || 0
            );


        const businessName =
            option.textContent.trim();

        const owner =
            option.dataset.ownerName || '';

        const accountDate =
            option.dataset.createdAt || '';

        const tenantEmail =
            option.dataset.email ||
            option.dataset.address ||
            '';


        /*
        |--------------------------------------------------------------------------
        | TENANT DETAILS
        |--------------------------------------------------------------------------
        */

        tenantName.value =
            businessName;

        ownerName.value =
            owner;

        createdAt.value =
            accountDate;

        email.value =
            tenantEmail;


        tenantDetailsCard.style.display =
            'flex';


        /*
        |--------------------------------------------------------------------------
        | SUBSCRIPTION
        |--------------------------------------------------------------------------
        */

        subscriptionSelect.innerHTML = '';


        if (subscriptionId) {

            const subscriptionOption =
                document.createElement('option');

            subscriptionOption.value =
                subscriptionId;

            subscriptionOption.textContent =
                'Active Subscription - PKR ' +
                money(amount);

            subscriptionOption.selected =
                true;

            subscriptionSelect.appendChild(
                subscriptionOption
            );

        } else {

            const noSubscription =
                document.createElement('option');

            noSubscription.value =
                '';

            noSubscription.textContent =
                '-- No Active Subscription --';

            subscriptionSelect.appendChild(
                noSubscription
            );

        }


        /*
        |--------------------------------------------------------------------------
        | AMOUNTS
        |--------------------------------------------------------------------------
        */

        subscriptionAmount.value =
            money(amount);

        currentOutstanding.value =
            money(outstanding);


        updateRemaining();
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE REMAINING
    |--------------------------------------------------------------------------
    */

    function updateRemaining() {

        const option =
            tenantSelect.options[
                tenantSelect.selectedIndex
            ];

        if (
            !option ||
            !option.value
        ) {

            remainingDisplay.textContent =
                'PKR 0.00';

            return;
        }


        const outstanding =
            parseFloat(
                option.dataset.outstanding || 0
            );

        let paymentAmount =
            parseFloat(
                amountInput.value || 0
            );


        if (isNaN(paymentAmount)) {
            paymentAmount = 0;
        }


        let remaining;


        /*
        |--------------------------------------------------------------------------
        | PAYMENT IN
        |--------------------------------------------------------------------------
        */

        if (
            paymentDirection.value === 'in'
        ) {

            remaining =
                Math.max(
                    outstanding - paymentAmount,
                    0
                );

        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT OUT
        |--------------------------------------------------------------------------
        */

        else {

            remaining =
                outstanding + paymentAmount;

        }


        remainingDisplay.textContent =
            'PKR ' + money(remaining);
    }


    /*
    |--------------------------------------------------------------------------
    | TENANT CHANGE
    |--------------------------------------------------------------------------
    */

    tenantSelect.addEventListener(
        'change',
        function () {

            updateTenantData();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PAYMENT DIRECTION CHANGE
    |--------------------------------------------------------------------------
    */

    paymentDirection.addEventListener(
        'change',
        function () {

            updateRemaining();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | AMOUNT CHANGE
    |--------------------------------------------------------------------------
    */

    amountInput.addEventListener(
        'input',
        function () {

            updateRemaining();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        'submit',
        function () {

            if (saveButton) {

                saveButton.disabled =
                    true;

                saveButton.innerHTML =
                    '<i class="fa fa-spinner fa-spin me-1"></i> Saving...';

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    updateTenantData();

});
</script>
    @include('admin.footer')
</body>
</html>