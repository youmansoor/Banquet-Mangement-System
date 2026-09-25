<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Service</title>

</head>

<body>

@include('tenant.nav')

<div class="page-wrapper">

    <div class="container-fluid">

        {{-- Page Title --}}
        <div class="row page-titles">

            <div class="col-md-6">

                <h4 class="text-themecolor">
                    Edit Service
                </h4>

            </div>

            <div class="col-md-6 text-end">

                <a href="{{ route('services.index') }}"
                   class="btn btn-secondary">

                    <i class="fa fa-arrow-left"></i>
                    Back

                </a>

            </div>

        </div>


        {{-- Validation Errors --}}
        @if($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <div class="row">

            <div class="col-md-12">

                <div class="card">

                    <div class="card-body">

                        <h4 class="card-title">
                            Edit Service Details
                        </h4>


                        <form
                            method="POST"
                            action="{{ route('services.update', $service) }}">

                            @csrf

                            @method('PUT')


                            <div class="row">

                                {{-- Service Name --}}
                                <div class="col-md-4">

                                    <div class="form-floating mb-3">

                                        <input
                                            type="text"
                                            name="service_name"
                                            class="form-control @error('service_name') is-invalid @enderror"
                                            id="service_name"
                                            value="{{ old('service_name', $service->service_name) }}"
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

                                {{-- Service Unit --}}
                                <div class="col-md-4">

                                    <div class="form-floating mb-3">

                                        <input
                                            type="text"
                                            name="service_unit"
                                            class="form-control @error('service_unit') is-invalid @enderror"
                                            id="service_unit"
                                            value="{{ old('service_unit', $service->service_unit) }}"
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

                                {{-- Amount --}}
                                <div class="col-md-4">

                                    <div class="form-floating mb-3">

                                        <input
                                            type="number"
                                            name="amount"
                                            class="form-control @error('amount') is-invalid @enderror"
                                            id="amount"
                                            step="0.01"
                                            min="0"
                                            value="{{ old('amount', $service->amount) }}"
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

                                {{-- Vendor --}}
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
                                                    {{ old('vendor_id', $service->vendor_id) == $vendor->id ? 'selected' : '' }}
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

                                {{-- Purchase Amount --}}
                                <div class="col-md-4">

                                    <div class="form-floating mb-3">

                                        <input
                                            type="number"
                                            name="purchase_amount"
                                            class="form-control @error('purchase_amount') is-invalid @enderror"
                                            id="purchase_amount"
                                            step="0.01"
                                            min="0"
                                            value="{{ old('purchase_amount', $service->purchase_amount) }}"
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

                                {{-- Payment Status --}}
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
                                                {{ old('payment_status', $service->payment_status) == 'unpaid' ? 'selected' : '' }}
                                            >
                                                Unpaid
                                            </option>

                                            <option
                                                value="paid"
                                                {{ old('payment_status', $service->payment_status) == 'paid' ? 'selected' : '' }}
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

                                {{-- Update Button --}}
                                <div class="col-12">

                                    <div class="d-md-flex align-items-center mt-3">

                                        <div class="ms-auto">

                                            <button
                                                type="submit"
                                                class="btn btn-primary text-white waves-effect waves-light">

                                                <span class="btn-label">
                                                    <i class="fa fa-save"></i>
                                                </span>

                                                Update Service

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

@include('tenant.footer')

</body>

</html>