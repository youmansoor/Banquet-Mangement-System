<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Create Vendor</title>

</head>

<body>

    @include('tenant.nav')


    <div class="page-wrapper">

        <div class="container-fluid">


            {{-- ====================================================== --}}
            {{-- PAGE TITLE --}}
            {{-- ====================================================== --}}

            <div class="row page-titles">

                <div class="col-md-6 align-self-center">

                    <h4 class="text-themecolor">

                        <i class="fa fa-truck me-2"></i>

                        Create Vendor

                    </h4>

                </div>


                <div class="col-md-6 align-self-center text-end">

                    <a
                        href="{{ route('tenant.vendors.index') }}"
                        class="btn btn-secondary"
                    >

                        <i class="fa fa-arrow-left"></i>

                        Back

                    </a>

                </div>

            </div>


            {{-- ====================================================== --}}
            {{-- VALIDATION ERRORS --}}
            {{-- ====================================================== --}}

            @if ($errors->any())

                <div class="alert alert-danger">

                    <h5 class="alert-heading">

                        Please fix the following errors:

                    </h5>

                    <ul class="mb-0">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ====================================================== --}}
            {{-- SUCCESS --}}
            {{-- ====================================================== --}}

            @if (session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif


            {{-- ====================================================== --}}
            {{-- CREATE VENDOR --}}
            {{-- ====================================================== --}}

            <form
                action="{{ route('tenant.vendors.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf


                {{-- ====================================================== --}}
                {{-- VENDOR INFORMATION --}}
                {{-- ====================================================== --}}

                <div class="card">

                    <div class="card-header bg-primary">

                        <h4 class="m-b-0 text-white">

                            <i class="fa fa-truck me-2"></i>

                            Vendor Information

                        </h4>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Vendor Name --}}

                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="text"
                                        name="vendor_name"
                                        id="vendor_name"
                                        class="form-control"
                                        value="{{ old('vendor_name') }}"
                                        placeholder="Vendor Name"
                                        required
                                    >

                                    <label for="vendor_name">

                                        Vendor / Company Name

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>

                                </div>

                            </div>


                            {{-- Vendor Type --}}

                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <select
                                        name="vendor_type"
                                        id="vendor_type"
                                        class="form-control"
                                    >

                                        <option value="">
                                            Select Vendor Type
                                        </option>

                                        <option
                                            value="food"
                                            {{ old('vendor_type') == 'food' ? 'selected' : '' }}
                                        >
                                            Food Supplier
                                        </option>

                                        <option
                                            value="beverages"
                                            {{ old('vendor_type') == 'beverages' ? 'selected' : '' }}
                                        >
                                            Beverages Supplier
                                        </option>

                                        <option
                                            value="decoration"
                                            {{ old('vendor_type') == 'decoration' ? 'selected' : '' }}
                                        >
                                            Decoration Supplier
                                        </option>

                                        <option
                                            value="furniture"
                                            {{ old('vendor_type') == 'furniture' ? 'selected' : '' }}
                                        >
                                            Furniture Supplier
                                        </option>

                                        <option
                                            value="sound"
                                            {{ old('vendor_type') == 'sound' ? 'selected' : '' }}
                                        >
                                            Sound / Lighting Supplier
                                        </option>

                                        <option
                                            value="equipment"
                                            {{ old('vendor_type') == 'equipment' ? 'selected' : '' }}
                                        >
                                            Equipment Supplier
                                        </option>

                                        <option
                                            value="stationery"
                                            {{ old('vendor_type') == 'stationery' ? 'selected' : '' }}
                                        >
                                            Stationery Supplier
                                        </option>

                                        <option
                                            value="other"
                                            {{ old('vendor_type') == 'other' ? 'selected' : '' }}
                                        >
                                            Other
                                        </option>

                                    </select>

                                    <label for="vendor_type">

                                        Vendor Type

                                    </label>

                                </div>

                            </div>


                            {{-- Contact Person --}}

                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="text"
                                        name="contact_person"
                                        id="contact_person"
                                        class="form-control"
                                        value="{{ old('contact_person') }}"
                                        placeholder="Contact Person"
                                    >

                                    <label for="contact_person">

                                        Contact Person

                                    </label>

                                </div>

                            </div>


                            {{-- Phone --}}

                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="text"
                                        name="phone"
                                        id="phone"
                                        class="form-control"
                                        value="{{ old('phone') }}"
                                        placeholder="Phone"
                                    >

                                    <label for="phone">

                                        Phone Number

                                    </label>

                                </div>

                            </div>


                            {{-- Email --}}

                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control"
                                        value="{{ old('email') }}"
                                        placeholder="Email"
                                    >

                                    <label for="email">

                                        Email Address

                                    </label>

                                </div>

                            </div>


                            {{-- NTN --}}

                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="text"
                                        name="ntn_number"
                                        id="ntn_number"
                                        class="form-control"
                                        value="{{ old('ntn_number') }}"
                                        placeholder="NTN"
                                    >

                                    <label for="ntn_number">

                                        NTN Number

                                    </label>

                                </div>

                            </div>


                            {{-- Logo --}}

                            <div class="col-md-6 mb-3">

                                <label
                                    for="logo"
                                    class="form-label fw-bold"
                                >

                                    Vendor Logo

                                </label>

                                <input
                                    type="file"
                                    name="logo"
                                    id="logo"
                                    class="form-control"
                                    accept="image/jpeg,image/png,image/webp"
                                >

                                <small class="text-muted">

                                    JPG, JPEG, PNG or WEBP.
                                    Maximum 2MB.

                                </small>

                            </div>


                        </div>

                    </div>

                </div>


                {{-- ====================================================== --}}
                {{-- ADDRESS --}}
                {{-- ====================================================== --}}

                <div class="card">

                    <div class="card-header bg-info">

                        <h4 class="m-b-0 text-white">

                            <i class="fa fa-map-marker me-2"></i>

                            Vendor Address

                        </h4>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Address --}}

                            <div class="col-md-8 mb-3">

                                <div class="form-floating">

                                    <textarea
                                        name="address"
                                        id="address"
                                        class="form-control"
                                        placeholder="Address"
                                        style="height: 100px;"
                                    >{{ old('address') }}</textarea>

                                    <label for="address">

                                        Complete Address

                                    </label>

                                </div>

                            </div>


                            {{-- City --}}

                            <div class="col-md-4 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="text"
                                        name="city"
                                        id="city"
                                        class="form-control"
                                        value="{{ old('city') }}"
                                        placeholder="City"
                                    >

                                    <label for="city">

                                        City

                                    </label>

                                </div>

                            </div>


                        </div>

                    </div>

                </div>


                {{-- ====================================================== --}}
                {{-- ACCOUNT / OPENING BALANCE --}}
                {{-- ====================================================== --}}

                <div class="card">

                    <div class="card-header bg-success">

                        <h4 class="m-b-0 text-white">

                            <i class="fa fa-money me-2"></i>

                            Vendor Account

                        </h4>

                    </div>


                    <div class="card-body">

                        <div class="row">


                            {{-- Opening Balance --}}

                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="number"
                                        name="opening_balance"
                                        id="opening_balance"
                                        class="form-control"
                                        value="{{ old('opening_balance', 0) }}"
                                        min="0"
                                        step="0.01"
                                        placeholder="Opening Balance"
                                    >

                                    <label for="opening_balance">

                                        Opening Payable Balance

                                    </label>

                                </div>

                                <small class="text-muted">

                                    Agar vendor ko pehle se koi payment
                                    deni hai to yahan outstanding amount
                                    enter karein.

                                </small>

                            </div>


                            {{-- Status --}}

                            <div class="col-md-6 mb-3">

                                <div class="form-check form-switch mt-3">

                                    <input
                                        type="checkbox"
                                        name="status"
                                        value="1"
                                        class="form-check-input"
                                        id="status"
                                        {{ old('status', true) ? 'checked' : '' }}
                                    >

                                    <label
                                        class="form-check-label fw-bold"
                                        for="status"
                                    >

                                        Active Vendor

                                    </label>

                                </div>

                                <small class="text-muted">

                                    Inactive vendors will not be available
                                    for new purchases.

                                </small>

                            </div>


                        </div>

                    </div>

                </div>


                {{-- ====================================================== --}}
                {{-- NOTES --}}
                {{-- ====================================================== --}}

                <div class="card">

                    <div class="card-header bg-secondary">

                        <h4 class="m-b-0 text-white">

                            <i class="fa fa-sticky-note me-2"></i>

                            Additional Information

                        </h4>

                    </div>


                    <div class="card-body">

                        <div class="form-floating">

                            <textarea
                                name="notes"
                                id="notes"
                                class="form-control"
                                placeholder="Notes"
                                style="height: 120px;"
                            >{{ old('notes') }}</textarea>

                            <label for="notes">

                                Notes

                            </label>

                        </div>

                    </div>

                </div>


                {{-- ====================================================== --}}
                {{-- ACTION BUTTONS --}}
                {{-- ====================================================== --}}

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route('tenant.vendors.index') }}"
                                class="btn btn-secondary"
                            >

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>


                            <button
                                type="reset"
                                class="btn btn-warning"
                            >

                                <i class="fa fa-refresh"></i>

                                Reset

                            </button>


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="fa fa-save"></i>

                                Create Vendor

                            </button>

                        </div>

                    </div>

                </div>


            </form>

        </div>

    </div>


    @include('tenant.footer')

</body>

</html>