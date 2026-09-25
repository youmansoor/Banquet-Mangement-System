<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet"
          href="{{ asset('assets/node_modules/morrisjs/morris.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/node_modules/toast-master/css/jquery.toast.css') }}">

    {{-- Main CSS --}}
    <link rel="stylesheet"href="{{ asset('assets/dist/css/style.min.css') }}">

    {{-- Dashboard CSS --}}
    <link rel="stylesheet"href="{{ asset('assets/dist/css/pages/dashboard1.css') }}">
</head>
<body>
    @include('admin.nav');
    <div class="container-fluid mt-4">

    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title mb-4">
                        Edit Tenant
                    </h4>


                    @if ($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form
                        action="{{ route('admin.tenants.update', $tenant->id) }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >

                        @csrf
                        @method('PUT')


                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="text"
                                        name="business_name"
                                        class="form-control"
                                        value="{{ old('business_name', $tenant->business_name) }}"
                                        required
                                    >

                                    <label>
                                        Business / Banquet Name
                                    </label>

                                </div>

                            </div>


                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="text"
                                        name="owner_name"
                                        class="form-control"
                                        value="{{ old('owner_name', $tenant->owner_name) }}"
                                    >

                                    <label>
                                        Owner Name
                                    </label>

                                </div>

                            </div>


                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control"
                                        value="{{ old('email', $tenant->email) }}"
                                    >

                                    <label>
                                        Business Email
                                    </label>

                                </div>

                            </div>


                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="text"
                                        name="phone"
                                        class="form-control"
                                        value="{{ old('phone', $tenant->phone) }}"
                                    >

                                    <label>
                                        Business Phone
                                    </label>

                                </div>

                            </div>


                            <div class="col-6 mb-3">

                                <div class="form-floating">

                                    <textarea
                                        name="address"
                                        class="form-control"
                                        style="height: 100px"
                                    >{{ old('address', $tenant->address) }}</textarea>

                                    <label>
                                        Business Address
                                    </label>

                                </div>

                            </div>
                            <div class="col-md-6 mb-3">
    <div class="form-floating">
        <input
            type="password"
            name="password"
            class="form-control"
            placeholder="Leave blank to keep current"
        >
        <label>
            New Password
        </label>
    </div>
    <small class="text-muted">Khali chor dein agar password change nahi karna</small>
</div>

<div class="col-md-6 mb-3">
    <div class="form-floating">
        <input
            type="password"
            name="password_confirmation"
            class="form-control"
        >
        <label>
            Confirm New Password
        </label>
    </div>
</div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Business Logo
                                </label>

                                <input
                                    type="file"
                                    name="logo"
                                    class="form-control"
                                    accept="image/*"
                                >


                                @if($tenant->logo)

                                    <div class="mt-2">

                                        <small class="text-muted">
                                            Current Logo:
                                        </small>

                                        <br>

                                        <img
                                            src="{{ asset('storage/' . $tenant->logo) }}"
                                            width="80"
                                            height="80"
                                            class="rounded border mt-1"
                                            style="object-fit: cover;"
                                        >

                                    </div>

                                @endif

                            </div>


                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="form-select"
                                >

                                    <option
                                        value="1"
                                        {{ $tenant->status ? 'selected' : '' }}
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="0"
                                        {{ !$tenant->status ? 'selected' : '' }}
                                    >
                                        Inactive
                                    </option>

                                </select>

                            </div>


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
                                        class="btn btn-primary text-white ms-auto"
                                    >
                                        Update Tenant
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
@include('admin.footer');
</body>
</html>