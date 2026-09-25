<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add New Tenant</title>

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
        .js-error {
            color: #f44336;
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }

        .is-invalid {
            border-color: #f44336 !important;
        }

        .is-valid {
            border-color: #4caf50 !important;
        }
    </style>

</head>


<body>

    @include('admin.nav')

    <div class="container-fluid mt-4">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-body">

                        <h4 class="card-title mb-4">
                            Add New Tenant
                        </h4>


                        {{-- Laravel Validation Errors --}}
                        @if ($errors->any())

                            <div class="alert alert-danger">

                                <ul class="mb-0">

                                    @foreach ($errors->all() as $error)

                                        <li>
                                            {{ $error }}
                                        </li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        <form
                            id="tenantCreateForm"
                            action="{{ route('admin.tenants.store') }}"
                            method="POST"
                            enctype="multipart/form-data"
                            novalidate
                        >

                            @csrf


                            <div class="row">


                                {{-- Business Name --}}
                                <div class="col-md-6 mb-3">

                                    <div class="form-floating">

                                        <input
                                            type="text"
                                            id="business_name"
                                            name="business_name"
                                            class="form-control"
                                            value="{{ old('business_name') }}"
                                            placeholder="Business Name"
                                            required
                                        >

                                        <label for="business_name">
                                            Business / Banquet Name
                                        </label>

                                    </div>

                                    <div
                                        id="business_name_error"
                                        class="js-error"
                                    ></div>

                                </div>


                                {{-- Owner --}}
                                <div class="col-md-6 mb-3">

                                    <div class="form-floating">

                                        <input
                                            type="text"
                                            id="owner_name"
                                            name="owner_name"
                                            class="form-control"
                                            value="{{ old('owner_name') }}"
                                            placeholder="Owner Name"
                                        >

                                        <label for="owner_name">
                                            Owner Name
                                        </label>

                                    </div>

                                    <div
                                        id="owner_name_error"
                                        class="js-error"
                                    ></div>

                                </div>


                                {{-- Business Email --}}
                                <div class="col-md-6 mb-3">

                                    <div class="form-floating">

                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            class="form-control"
                                            value="{{ old('email') }}"
                                            placeholder="Email"
                                        >

                                        <label for="email">
                                            Business Email
                                        </label>

                                    </div>

                                    <div
                                        id="email_error"
                                        class="js-error"
                                    ></div>

                                </div>


                                {{-- Business Phone --}}
                                <div class="col-md-6 mb-3">

                                    <div class="form-floating">

                                        <input
                                            type="text"
                                            id="phone"
                                            name="phone"
                                            class="form-control"
                                            value="{{ old('phone') }}"
                                            placeholder="Phone"
                                            maxlength="15"
                                        >

                                        <label for="phone">
                                            Business Phone
                                        </label>

                                    </div>

                                    <div
                                        id="phone_error"
                                        class="js-error"
                                    ></div>

                                </div>

                                {{-- NTN Number --}}
                                <div class="col-md-6 mb-3">

                                    <div class="form-floating">

                                        <input
                                            type="text"
                                            id="ntn_number"
                                            name="ntn_number"
                                            class="form-control"
                                            value="{{ old('ntn_number') }}"
                                            placeholder="ntn_number"
                                            maxlength="15"
                                        >

                                        <label for="phone">
                                            NTN Number
                                        </label>

                                    </div>

                                    <div
                                        id="phone_error"
                                        class="js-error"
                                    ></div>

                                </div>

                                {{-- NIC Number --}}
                                <div class="col-md-6 mb-3">

                                    <div class="form-floating">

                                        <input
                                            type="text"
                                            id="nic_number"
                                            name="nic_number"
                                            class="form-control"
                                            value="{{ old('nic_number') }}"
                                            placeholder="nic_number"
                                            maxlength="15"
                                        >

                                        <label for="phone">
                                            NIC Number
                                        </label>

                                    </div>

                                    <div
                                        id="phone_error"
                                        class="js-error"
                                    ></div>

                                </div>


                                {{-- Address --}}
                                <div class="col-6 mb-3">

                                    <div class="form-floating">

                                        <textarea
                                            id="business_address"
                                            name="business_address"
                                            class="form-control"
                                            placeholder="Address"
                                            style="height: 100px"
                                        >{{ old('business_address') }}</textarea>

                                        <label for="business_address">
                                            Business Address
                                        </label>

                                    </div>

                                    <div
                                        id="address_error"
                                        class="js-error"
                                    ></div>

                                </div>

                                {{-- Home Address --}}
                                <div class="col-6 mb-3">

                                    <div class="form-floating">

                                        <textarea
                                            id="home_address"
                                            name="home_address"
                                            class="form-control"
                                            placeholder="Home Address"
                                            style="height: 100px"
                                        >{{ old('home_address') }}</textarea>

                                        <label for="address">
                                            Home Address
                                        </label>

                                    </div>

                                    <div
                                        id="address_error"
                                        class="js-error"
                                    ></div>

                                </div>
                                <hr>
                                <h5 class="card-title mb-4">
                                    Tenant Login Credential
                                </h5>

                                {{-- Owner Login Email --}}
                                <div class="col-md-6 mb-3">

                                    <div class="form-floating">

                                        <input
                                            type="email"
                                            id="owner_email"
                                            name="owner_email"
                                            class="form-control"
                                            value="{{ old('owner_email') }}"
                                            placeholder="Owner Email"
                                            required
                                        >

                                        <label for="owner_email">
                                            Owner Login Email
                                        </label>

                                    </div>

                                    <div
                                        id="owner_email_error"
                                        class="js-error"
                                    ></div>

                                </div>


                                {{-- Owner Password --}}
                                <div class="col-md-6 mb-3">

                                    <div class="form-floating">

                                        <input
                                            type="password"
                                            id="owner_password"
                                            name="owner_password"
                                            class="form-control"
                                            placeholder="Owner Password"
                                            minlength="8"
                                            required
                                        >

                                        <label for="owner_password">
                                            Owner Login Password
                                        </label>

                                    </div>

                                    <div
                                        id="owner_password_error"
                                        class="js-error"
                                    ></div>

                                </div>


                                {{-- Confirm Password --}}
                                <div class="col-md-6 mb-3">

                                    <div class="form-floating">

                                        <input
                                            type="password"
                                            id="owner_password_confirmation"
                                            name="owner_password_confirmation"
                                            class="form-control"
                                            placeholder="Confirm Password"
                                            minlength="8"
                                            required
                                        >

                                        <label for="owner_password_confirmation">
                                            Confirm Owner Password
                                        </label>

                                    </div>

                                    <div
                                        id="owner_password_confirmation_error"
                                        class="js-error"
                                    ></div>

                                </div>


                                {{-- Logo --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Business Logo
                                    </label>

                                    <input
                                        type="file"
                                        id="logo"
                                        name="logo"
                                        class="form-control"
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                    >

                                    <div
                                        id="logo_error"
                                        class="js-error"
                                    ></div>

                                    <small class="text-muted">
                                        JPG, JPEG, PNG or WEBP. Maximum 2MB.
                                    </small>

                                </div>

                                {{-- Buttons --}}
                                <div class="col-12 mt-3">

                                    <div class="d-flex">

                                        <a
                                            href="{{ route('admin.tenants.index') }}"
                                            class="btn btn-secondary"
                                        >
                                            Back
                                        </a>

                                        <button
                                            type="submit"
                                            id="submitBtn"
                                            class="btn btn-primary text-white ms-auto"
                                        >
                                            Create Tenant
                                        </button>

                                    </div>

                                </div>


                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>


    @include('admin.footer')


    {{-- =========================================================
         JAVASCRIPT VALIDATION
    ========================================================== --}}

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const form =
                    document.getElementById(
                        'tenantCreateForm'
                    );

                const submitBtn =
                    document.getElementById(
                        'submitBtn'
                    );


                /*
                |--------------------------------------------------------------------------
                | Helper Functions
                |--------------------------------------------------------------------------
                */

                function showError(
                    field,
                    message
                ) {

                    const errorElement =
                        document.getElementById(
                            field.id + '_error'
                        );

                    field.classList.remove(
                        'is-valid'
                    );

                    field.classList.add(
                        'is-invalid'
                    );


                    if (errorElement) {

                        errorElement.textContent =
                            message;

                        errorElement.style.display =
                            'block';
                    }

                }


                function clearError(field) {

                    const errorElement =
                        document.getElementById(
                            field.id + '_error'
                        );

                    field.classList.remove(
                        'is-invalid'
                    );

                    field.classList.add(
                        'is-valid'
                    );


                    if (errorElement) {

                        errorElement.textContent =
                            '';

                        errorElement.style.display =
                            'none';
                    }

                }


                function resetField(field) {

                    field.classList.remove(
                        'is-invalid',
                        'is-valid'
                    );

                    const errorElement =
                        document.getElementById(
                            field.id + '_error'
                        );

                    if (errorElement) {

                        errorElement.textContent =
                            '';

                        errorElement.style.display =
                            'none';
                    }

                }


                function isValidEmail(email) {

                    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/
                        .test(email);

                }


                function isValidPhone(phone) {

                    /*
                    | Pakistani formats:
                    | 03001234567
                    | +923001234567
                    | 923001234567
                    */

                    return /^(?:\+92|92|0)3\d{9}$/
                        .test(phone);

                }


                /*
                |--------------------------------------------------------------------------
                | Business Name
                |--------------------------------------------------------------------------
                */

                function validateBusinessName() {

                    const field =
                        document.getElementById(
                            'business_name'
                        );

                    const value =
                        field.value.trim();


                    if (!value) {

                        showError(
                            field,
                            'Business / Banquet name is required.'
                        );

                        return false;
                    }


                    if (value.length < 3) {

                        showError(
                            field,
                            'Business name must be at least 3 characters.'
                        );

                        return false;
                    }


                    if (value.length > 255) {

                        showError(
                            field,
                            'Business name cannot exceed 255 characters.'
                        );

                        return false;
                    }


                    clearError(field);

                    return true;
                }


                /*
                |--------------------------------------------------------------------------
                | Owner Name
                |--------------------------------------------------------------------------
                */

                function validateOwnerName() {

                    const field =
                        document.getElementById(
                            'owner_name'
                        );

                    const value =
                        field.value.trim();


                    /*
                    | Owner name optional
                    */

                    if (!value) {

                        resetField(field);

                        return true;
                    }


                    if (value.length < 2) {

                        showError(
                            field,
                            'Owner name must be at least 2 characters.'
                        );

                        return false;
                    }


                    if (value.length > 255) {

                        showError(
                            field,
                            'Owner name cannot exceed 255 characters.'
                        );

                        return false;
                    }


                    clearError(field);

                    return true;
                }


                /*
                |--------------------------------------------------------------------------
                | Business Email
                |--------------------------------------------------------------------------
                */

                function validateBusinessEmail() {

                    const field =
                        document.getElementById(
                            'email'
                        );

                    const value =
                        field.value.trim();


                    /*
                    | Business email optional
                    */

                    if (!value) {

                        resetField(field);

                        return true;
                    }


                    if (!isValidEmail(value)) {

                        showError(
                            field,
                            'Please enter a valid business email.'
                        );

                        return false;
                    }


                    clearError(field);

                    return true;
                }


                /*
                |--------------------------------------------------------------------------
                | Business Phone
                |--------------------------------------------------------------------------
                */

                function validatePhone() {

                    const field =
                        document.getElementById(
                            'phone'
                        );

                    const value =
                        field.value.trim();


                    /*
                    | Business phone optional
                    */

                    if (!value) {

                        resetField(field);

                        return true;
                    }


                    const normalizedPhone =
                        value.replace(
                            /[\s-]/g,
                            ''
                        );


                    if (!isValidPhone(normalizedPhone)) {

                        showError(
                            field,
                            'Enter a valid Pakistani mobile number, e.g. 03001234567.'
                        );

                        return false;
                    }


                    clearError(field);

                    return true;
                }


                /*
                |--------------------------------------------------------------------------
                | Address
                |--------------------------------------------------------------------------
                */

                function validateAddress() {

                    const field =
                        document.getElementById(
                            'address'
                        );

                    const value =
                        field.value.trim();


                    /*
                    | Address optional
                    */

                    if (!value) {

                        resetField(field);

                        return true;
                    }


                    if (value.length > 2000) {

                        showError(
                            field,
                            'Address cannot exceed 2000 characters.'
                        );

                        return false;
                    }


                    clearError(field);

                    return true;
                }


                /*
                |--------------------------------------------------------------------------
                | Owner Email
                |--------------------------------------------------------------------------
                */

                function validateOwnerEmail() {

                    const field =
                        document.getElementById(
                            'owner_email'
                        );

                    const value =
                        field.value.trim();


                    if (!value) {

                        showError(
                            field,
                            'Owner login email is required.'
                        );

                        return false;
                    }


                    if (!isValidEmail(value)) {

                        showError(
                            field,
                            'Please enter a valid owner login email.'
                        );

                        return false;
                    }


                    clearError(field);

                    return true;
                }


                /*
                |--------------------------------------------------------------------------
                | Password
                |--------------------------------------------------------------------------
                */

                function validatePassword() {

                    const field =
                        document.getElementById(
                            'owner_password'
                        );

                    const value =
                        field.value;


                    if (!value) {

                        showError(
                            field,
                            'Owner password is required.'
                        );

                        return false;
                    }


                    if (value.length < 8) {

                        showError(
                            field,
                            'Password must be at least 8 characters.'
                        );

                        return false;
                    }


                    if (value.length > 72) {

                        showError(
                            field,
                            'Password cannot exceed 72 characters.'
                        );

                        return false;
                    }


                    clearError(field);

                    return true;
                }


                /*
                |--------------------------------------------------------------------------
                | Confirm Password
                |--------------------------------------------------------------------------
                */

                function validatePasswordConfirmation() {

                    const field =
                        document.getElementById(
                            'owner_password_confirmation'
                        );

                    const password =
                        document.getElementById(
                            'owner_password'
                        ).value;

                    const confirmation =
                        field.value;


                    if (!confirmation) {

                        showError(
                            field,
                            'Please confirm the owner password.'
                        );

                        return false;
                    }


                    if (confirmation !== password) {

                        showError(
                            field,
                            'Passwords do not match.'
                        );

                        return false;
                    }


                    clearError(field);

                    return true;
                }


                /*
                |--------------------------------------------------------------------------
                | Logo
                |--------------------------------------------------------------------------
                */

                function validateLogo() {

                    const field =
                        document.getElementById(
                            'logo'
                        );


                    /*
                    | Logo optional
                    */

                    if (!field.files.length) {

                        resetField(field);

                        return true;
                    }


                    const file =
                        field.files[0];


                    const allowedTypes = [
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                        'image/webp'
                    ];


                    const maxSize =
                        2 * 1024 * 1024;


                    if (!allowedTypes.includes(
                        file.type
                    )) {

                        showError(
                            field,
                            'Only JPG, JPEG, PNG and WEBP images are allowed.'
                        );

                        return false;
                    }


                    if (file.size > maxSize) {

                        showError(
                            field,
                            'Logo size must not exceed 2MB.'
                        );

                        return false;
                    }


                    clearError(field);

                    return true;
                }


                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                function validateStatus() {

                    const field =
                        document.getElementById(
                            'status'
                        );


                    if (
                        field.value !== '1' &&
                        field.value !== '0'
                    ) {

                        showError(
                            field,
                            'Please select a valid status.'
                        );

                        return false;
                    }


                    clearError(field);

                    return true;
                }


                /*
                |--------------------------------------------------------------------------
                | Validate All
                |--------------------------------------------------------------------------
                */

                function validateForm() {

                    let isValid = true;


                    if (!validateBusinessName()) {
                        isValid = false;
                    }


                    if (!validateOwnerName()) {
                        isValid = false;
                    }


                    if (!validateBusinessEmail()) {
                        isValid = false;
                    }


                    if (!validatePhone()) {
                        isValid = false;
                    }


                    if (!validateAddress()) {
                        isValid = false;
                    }


                    if (!validateOwnerEmail()) {
                        isValid = false;
                    }


                    if (!validatePassword()) {
                        isValid = false;
                    }


                    if (!validatePasswordConfirmation()) {
                        isValid = false;
                    }


                    if (!validateLogo()) {
                        isValid = false;
                    }


                    if (!validateStatus()) {
                        isValid = false;
                    }


                    return isValid;
                }


                /*
                |--------------------------------------------------------------------------
                | Live Validation
                |--------------------------------------------------------------------------
                */

                document
                    .getElementById('business_name')
                    .addEventListener(
                        'input',
                        validateBusinessName
                    );


                document
                    .getElementById('owner_name')
                    .addEventListener(
                        'input',
                        validateOwnerName
                    );


                document
                    .getElementById('email')
                    .addEventListener(
                        'input',
                        validateBusinessEmail
                    );


                document
                    .getElementById('phone')
                    .addEventListener(
                        'input',
                        function () {

                            /*
                            | Allow only numbers,
                            | +, spaces and -
                            */

                            this.value =
                                this.value.replace(
                                    /[^0-9+\s-]/g,
                                    ''
                                );

                            validatePhone();

                        }
                    );


                document
                    .getElementById('address')
                    .addEventListener(
                        'input',
                        validateAddress
                    );


                document
                    .getElementById('owner_email')
                    .addEventListener(
                        'input',
                        validateOwnerEmail
                    );


                document
                    .getElementById('owner_password')
                    .addEventListener(
                        'input',
                        function () {

                            validatePassword();

                            /*
                            | Recheck confirmation
                            | when password changes.
                            */

                            const confirmation =
                                document.getElementById(
                                    'owner_password_confirmation'
                                );

                            if (confirmation.value) {
                                validatePasswordConfirmation();
                            }

                        }
                    );


                document
                    .getElementById(
                        'owner_password_confirmation'
                    )
                    .addEventListener(
                        'input',
                        validatePasswordConfirmation
                    );


                document
                    .getElementById('logo')
                    .addEventListener(
                        'change',
                        validateLogo
                    );


                document
                    .getElementById('status')
                    .addEventListener(
                        'change',
                        validateStatus
                    );


                /*
                |--------------------------------------------------------------------------
                | Form Submit
                |--------------------------------------------------------------------------
                */

                form.addEventListener(
                    'submit',
                    function (event) {

                        event.preventDefault();


                        const valid =
                            validateForm();


                        if (!valid) {

                            const firstInvalid =
                                form.querySelector(
                                    '.is-invalid'
                                );


                            if (firstInvalid) {

                                firstInvalid.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });

                                firstInvalid.focus();

                            }


                            return;
                        }


                        /*
                        | Disable button to prevent
                        | double submission.
                        */

                        submitBtn.disabled =
                            true;

                        submitBtn.innerHTML =
                            'Creating Tenant...';


                        form.submit();

                    }
                );

            }
        );

    </script>

</body>

</html>