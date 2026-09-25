<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Business Profile</title>

    <link rel="stylesheet"
          href="{{ asset('assets/node_modules/morrisjs/morris.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/node_modules/toast-master/css/jquery.toast.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/dist/css/style.min.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/dist/css/pages/dashboard1.css') }}">
</head>

<body>

    @include('admin.nav')

    <div class="container-fluid mt-4">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <h4 class="card-title mb-0">
                                My Business Profile
                            </h4>

                        </div>


                        {{-- Success Message --}}
                        @if(session('success'))

                            <div class="alert alert-success alert-dismissible fade show">

                                {{ session('success') }}

                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="alert">
                                </button>

                            </div>

                        @endif


                        {{-- Validation Errors --}}
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
                            action="{{ route('tenant.profile.update') }}"
                            method="POST"
                            enctype="multipart/form-data"
                        >

                            @csrf

                            @method('PUT')


                            <div class="row">

                                {{-- Business Name --}}
                                <div class="col-md-6 mb-3">

                                    <div class="form-floating">

                                        <input
                                            type="text"
                                            name="business_name"
                                            class="form-control"
                                            value="{{ old('business_name', $tenant->business_name) }}"
                                            placeholder="Business Name"
                                            required
                                        >

                                        <label>
                                            Business / Banquet Name
                                        </label>

                                    </div>

                                </div>


                                {{-- Owner Name --}}
                                <div class="col-md-6 mb-3">

                                    <div class="form-floating">

                                        <input
                                            type="text"
                                            name="owner_name"
                                            class="form-control"
                                            value="{{ old('owner_name', $tenant->owner_name) }}"
                                            placeholder="Owner Name"
                                        >

                                        <label>
                                            Owner Name
                                        </label>

                                    </div>

                                </div>


                                {{-- Email --}}
                                <div class="col-md-6 mb-3">

                                    <div class="form-floating">

                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control"
                                            value="{{ old('email', $tenant->email) }}"
                                            placeholder="Business Email"
                                        >

                                        <label>
                                            Business Email
                                        </label>

                                    </div>

                                </div>


                                {{-- Phone --}}
                                <div class="col-md-6 mb-3">

                                    <div class="form-floating">

                                        <input
                                            type="text"
                                            name="phone"
                                            class="form-control"
                                            value="{{ old('phone', $tenant->phone) }}"
                                            placeholder="Business Phone"
                                        >

                                        <label>
                                            Business Phone
                                        </label>

                                    </div>

                                </div>


                                {{-- Address --}}
                                <div class="col-12 mb-3">

                                    <div class="form-floating">

                                        <textarea
                                            name="address"
                                            class="form-control"
                                            placeholder="Business Address"
                                            style="height: 100px"
                                        >{{ old('address', $tenant->address) }}</textarea>

                                        <label>
                                            Business Address
                                        </label>

                                    </div>

                                </div>


                                {{-- Logo --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Business Logo
                                    </label>

                                    <input
                                        type="file"
                                        name="logo"
                                        class="form-control"
                                        accept="image/jpeg,image/png,image/webp"
                                    >

                                    @if($tenant->logo)

                                        <div class="mt-3">

                                            <small class="text-muted d-block mb-2">
                                                Current Logo
                                            </small>

                                            <img
                                                src="{{ asset('storage/' . $tenant->logo) }}"
                                                alt="Business Logo"
                                                width="100"
                                                height="100"
                                                class="rounded border"
                                                style="object-fit: cover;"
                                            >

                                        </div>

                                    @else

                                        <small class="text-muted d-block mt-2">
                                            No logo uploaded.
                                        </small>

                                    @endif

                                </div>


                                {{-- Status --}}
                                {{-- Owner ko status change nahi karne dena --}}
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Account Status
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ $tenant->status ? 'Active' : 'Inactive' }}"
                                        readonly
                                    >

                                </div>


                                {{-- Buttons --}}
                                <div class="col-12 mt-3">

                                    <div class="d-flex">

                                        <a
                                            href="{{ route('tenant.dashboard') }}"
                                            class="btn btn-secondary"
                                        >
                                            Back
                                        </a>

                                        <button
                                            type="submit"
                                            class="btn btn-primary text-white ms-auto"
                                        >
                                            Update Business Profile
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

</body>

</html>