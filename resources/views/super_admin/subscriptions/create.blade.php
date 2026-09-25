<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Subscription</title>

    <link rel="stylesheet"
          href="{{ asset('assets/dist/css/style.min.css') }}">
</head>

<body>

@include('admin.nav')

<div class="col-12 p-3">

    <div class="card">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h4 class="card-title">
                    Create Subscription
                </h4>

                <a href="{{ route('admin.subscriptions.index') }}"
                   class="btn btn-secondary text-white">
                    Back
                </a>

            </div>


            {{-- Error Message --}}
            @if(session('error'))

                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>

            @endif


            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="alert alert-danger">

                    <strong>Please fix the following errors:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- Form --}}
            <form action="{{ route('admin.subscriptions.store') }}"
                  method="POST">

                @csrf


                {{-- Tenant --}}
                <div class="mb-3">

                    <label for="tenant_id"
                           class="form-label">
                        Tenant
                    </label>

                    <select
                        name="tenant_id"
                        id="tenant_id"
                        class="form-control @error('tenant_id') is-invalid @enderror"
                        required
                    >

                        <option value="">
                            Select Tenant
                        </option>

                        @foreach($tenants as $tenant)

                            <option
                                value="{{ $tenant->id }}"
                                {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}
                            >

                                {{ $tenant->business_name
                                    ?? $tenant->owner_name
                                    ?? 'Tenant #'.$tenant->id }}

                            </option>

                        @endforeach

                    </select>


                    @error('tenant_id')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Amount --}}
                <div class="mb-3">

                    <label for="amount"
                           class="form-label">
                        Amount
                    </label>

                    <input
                        type="number"
                        name="amount"
                        id="amount"
                        class="form-control @error('amount') is-invalid @enderror"
                        step="0.01"
                        min="0"
                        value="{{ old('amount') }}"
                        placeholder="Enter subscription amount"
                        required
                    >


                    @error('amount')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                <button
                    type="submit"
                    class="btn btn-primary text-white"
                >
                    <i class="fa fa-save"></i>
                    Create Subscription
                </button>


                <a href="{{ route('admin.subscriptions.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

@include('admin.footer')

</body>
</html>