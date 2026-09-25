<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Services</title>

</head>


<body>

@include('tenant.nav')


<div class="page-wrapper">

    <div class="container-fluid">


        {{-- ========================================================= --}}
        {{-- PAGE TITLE --}}
        {{-- ========================================================= --}}

        <div class="row page-titles">

            <div class="col-md-6">

                <h4 class="text-themecolor">

                    <i class="fa fa-cogs me-2"></i>

                    Services

                </h4>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- SERVICE TABS --}}
        {{-- ========================================================= --}}

        <div class="row">

            <div class="col-md-12">

                <div class="card">

                    {{-- ================================================= --}}
                    {{-- CARD HEADER --}}
                    {{-- ================================================= --}}

                    <div class="card-body p-b-0">

                        <h4 class="card-title">

                            Services

                        </h4>

                        <h6 class="card-subtitle">

                            Manage your paid and free services.

                        </h6>

                    </div>



                    {{-- ================================================= --}}
                    {{-- NAV TABS --}}
                    {{-- ================================================= --}}

                    <ul class="nav nav-tabs customtab"
                        role="tablist">


                        {{-- ================================================= --}}
                        {{-- PAID SERVICE TAB --}}
                        {{-- ================================================= --}}

                        <li class="nav-item">

                            <a class="nav-link active"
                               data-bs-toggle="tab"
                               href="#paidService"
                               role="tab">

                                <span class="hidden-sm-up">

                                    <i class="fa fa-money"></i>

                                </span>

                                <span class="hidden-xs-down">

                                    <i class="fa fa-money me-1"></i>

                                    Paid Services

                                </span>

                            </a>

                        </li>



                        {{-- ================================================= --}}
                        {{-- FREE SERVICE TAB --}}
                        {{-- ================================================= --}}

                        <li class="nav-item">

                            <a class="nav-link"
                               data-bs-toggle="tab"
                               href="#freeService"
                               role="tab">

                                <span class="hidden-sm-up">

                                    <i class="fa fa-gift"></i>

                                </span>

                                <span class="hidden-xs-down">

                                    <i class="fa fa-gift me-1"></i>

                                    Free Services

                                </span>

                            </a>

                        </li>


                    </ul>



                    {{-- ================================================= --}}
                    {{-- TAB CONTENT --}}
                    {{-- ================================================= --}}

                    <div class="tab-content">


                        {{-- ================================================= --}}
                        {{-- PAID SERVICE --}}
                        {{-- ================================================= --}}

                        <div class="tab-pane active"
                             id="paidService"
                             role="tabpanel">

                            <div class="p-20">

                                <div class="row">


                                    {{-- HEADER --}}
                                    <div class="col-md-12 mb-4">

                                        <div class="d-flex align-items-center">


                                            <div>

                                                <h4 class="card-title mb-1">

                                                    Add Paid Service

                                                </h4>

                                                <h6 class="card-subtitle">

                                                    Add a service that has a charge.

                                                </h6>

                                            </div>


                                            <div class="ms-auto">

                                                <span class="badge bg-primary">

                                                    Paid

                                                </span>

                                            </div>


                                        </div>

                                    </div>



                                    {{-- PAID SERVICE FORM --}}
                                    <div class="col-md-12">

                                        <form
                                            method="POST"
                                            action="{{ route('services.store') }}"
                                        >

                                            @csrf


                                            <div class="row">


                                                {{-- SERVICE NAME --}}

                                                <div class="col-md-4">

                                                    <div class="form-floating mb-3">

                                                        <input
                                                            type="text"
                                                            class="form-control @error('service_name') is-invalid @enderror"
                                                            id="service_name"
                                                            name="service_name"
                                                            value="{{ old('service_name') }}"
                                                            placeholder="Enter Service Name"
                                                            required
                                                        >

                                                        <label for="service_name">

                                                            Service Name

                                                        </label>


                                                        @error('service_name')

                                                            <div class="invalid-feedback">

                                                                {{ $message }}

                                                            </div>

                                                        @enderror

                                                    </div>

                                                </div>



                                                {{-- SERVICE UNIT --}}

                                                <div class="col-md-4">

                                                    <div class="form-floating mb-3">

                                                        <input
                                                            type="text"
                                                            class="form-control @error('service_unit') is-invalid @enderror"
                                                            id="service_unit"
                                                            name="service_unit"
                                                            value="{{ old('service_unit') }}"
                                                            placeholder="Enter Service Unit"
                                                            required
                                                        >

                                                        <label for="service_unit">

                                                            Service Unit

                                                        </label>


                                                        @error('service_unit')

                                                            <div class="invalid-feedback">

                                                                {{ $message }}

                                                            </div>

                                                        @enderror

                                                    </div>

                                                </div>



                                                {{-- AMOUNT --}}

                                                <div class="col-md-4">

                                                    <div class="form-floating mb-3">

                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            min="0"
                                                            class="form-control @error('amount') is-invalid @enderror"
                                                            id="amount"
                                                            name="amount"
                                                            value="{{ old('amount') }}"
                                                            placeholder="Enter Amount"
                                                            required
                                                        >

                                                        <label for="amount">

                                                            Amount (Rs.)

                                                        </label>


                                                        @error('amount')

                                                            <div class="invalid-feedback">

                                                                {{ $message }}

                                                            </div>

                                                        @enderror

                                                    </div>

                                                </div>

                                                {{-- ========================================================= --}}
{{-- VENDOR --}}
{{-- ========================================================= --}}

<div class="col-md-4">

    <div class="form-floating mb-3">

        <select
            class="form-control @error('vendor_id') is-invalid @enderror"
            id="vendor_id"
            name="vendor_id"
        >

            <option value="">
                Select Vendor
            </option>

            @foreach($vendors as $vendor)

                <option
                    value="{{ $vendor->id }}"
                    {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}
                >
                    {{ $vendor->vendor_name }}
                </option>

            @endforeach

        </select>

        <label for="vendor_id">

            Vendor

        </label>

        @error('vendor_id')

            <div class="invalid-feedback">

                {{ $message }}

            </div>

        @enderror

    </div>

</div>



{{-- ========================================================= --}}
{{-- PURCHASE AMOUNT --}}
{{-- ========================================================= --}}

<div class="col-md-4">

    <div class="form-floating mb-3">

        <input
            type="number"
            step="0.01"
            min="0"
            class="form-control @error('purchase_amount') is-invalid @enderror"
            id="purchase_amount"
            name="purchase_amount"
            value="{{ old('purchase_amount', 0) }}"
            placeholder="Purchase Amount"
        >

        <label for="purchase_amount">

            Purchase Amount (Rs.)

        </label>

        @error('purchase_amount')

            <div class="invalid-feedback">

                {{ $message }}

            </div>

        @enderror

    </div>

</div>



{{-- ========================================================= --}}
{{-- PAYMENT STATUS --}}
{{-- ========================================================= --}}

<div class="col-md-4">

    <div class="form-floating mb-3">

        <select
            class="form-control @error('payment_status') is-invalid @enderror"
            id="payment_status"
            name="payment_status"
            required
        >

            <option
                value="unpaid"
                {{ old('payment_status', 'unpaid') == 'unpaid' ? 'selected' : '' }}
            >
                Unpaid
            </option>

            <option
                value="paid"
                {{ old('payment_status') == 'paid' ? 'selected' : '' }}
            >
                Paid
            </option>

        </select>

        <label for="payment_status">

            Vendor Payment Status

        </label>

        @error('payment_status')

            <div class="invalid-feedback">

                {{ $message }}

            </div>

        @enderror

    </div>

</div>



                                                {{-- SAVE --}}

                                                <div class="col-12">

                                                    <div class="d-md-flex align-items-center mt-3">

                                                        <div class="ms-auto">

                                                            <button
                                                                type="submit"
                                                                class="btn btn-primary text-white waves-effect waves-light"
                                                            >

                                                                <span class="btn-label">

                                                                    <i class="fa fa-save"></i>

                                                                </span>

                                                                Save Paid Service

                                                            </button>

                                                        </div>

                                                    </div>

                                                </div>


                                            </div>

                                        </form>

                                    </div>


                                </div>

                            </div>

                        </div>



                        {{-- ================================================= --}}
                        {{-- FREE SERVICE --}}
                        {{-- ================================================= --}}

                        <div class="tab-pane"
                             id="freeService"
                             role="tabpanel">

                            <div class="p-20">

                                <div class="row">


                                    {{-- HEADER --}}
                                    <div class="col-md-12 mb-4">

                                        <div class="d-flex align-items-center">


                                            <div>

                                                <h4 class="card-title mb-1">

                                                    Add Free Service

                                                </h4>

                                                <h6 class="card-subtitle">

                                                    Add a service that has no charge.

                                                </h6>

                                            </div>


                                            <div class="ms-auto">

                                                <span class="badge bg-success">

                                                    Free

                                                </span>

                                            </div>


                                        </div>

                                    </div>



                                    {{-- FREE SERVICE FORM --}}
                                    <div class="col-md-12">

                                        <form
                                            method="POST"
                                            action="{{ route('free-services.store') }}"
                                        >

                                            @csrf


                                            <div class="row">


                                                {{-- SERVICE NAME --}}

                                                <div class="col-md-6">

                                                    <div class="form-floating mb-3">

                                                        <input
                                                            type="text"
                                                            class="form-control @error('service_name') is-invalid @enderror"
                                                            id="free_service_name"
                                                            name="service_name"
                                                            value="{{ old('service_name') }}"
                                                            placeholder="Enter Free Service Name"
                                                            required
                                                        >

                                                        <label for="free_service_name">

                                                            Free Service Name

                                                        </label>


                                                        @error('service_name')

                                                            <div class="invalid-feedback">

                                                                {{ $message }}

                                                            </div>

                                                        @enderror

                                                    </div>

                                                </div>



                                                {{-- SERVICE UNIT --}}

                                                <div class="col-md-6">

                                                    <div class="form-floating mb-3">

                                                        <input
                                                            type="text"
                                                            class="form-control @error('service_unit') is-invalid @enderror"
                                                            id="free_service_unit"
                                                            name="service_unit"
                                                            value="{{ old('service_unit') }}"
                                                            placeholder="Enter Service Unit"
                                                            required
                                                        >

                                                        <label for="free_service_unit">

                                                            Service Unit

                                                        </label>


                                                        @error('service_unit')

                                                            <div class="invalid-feedback">

                                                                {{ $message }}

                                                            </div>

                                                        @enderror

                                                    </div>

                                                </div>


                                                {{-- SAVE --}}

                                                <div class="col-12">

                                                    <div class="d-md-flex align-items-center mt-3">

                                                        <div class="ms-auto">

                                                            <button
                                                                type="submit"
                                                                class="btn btn-success text-white waves-effect waves-light"
                                                            >

                                                                <span class="btn-label">

                                                                    <i class="fa fa-gift"></i>

                                                                </span>

                                                                Save Free Service

                                                            </button>

                                                        </div>

                                                    </div>

                                                </div>


                                            </div>

                                        </form>

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

</body>

</html>