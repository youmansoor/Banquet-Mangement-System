<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Edit Subscription
    </title>


    <link
        rel="icon"
        type="image/png"
        href="{{ asset('assets/images/favicon.png') }}"
    >


    <link
        rel="stylesheet"
        href="{{ asset('assets/dist/css/style.min.css') }}"
    >


    <link
        rel="stylesheet"
        href="{{ asset('assets/dist/css/pages/dashboard1.css') }}"
    >

</head>


<body>


@include('admin.nav')


<div class="page-wrapper">

    <div class="container-fluid">


        {{-- ====================================================== --}}
        {{-- PAGE TITLE --}}
        {{-- ====================================================== --}}

        <div class="row page-titles">

            <div class="col-md-8 align-self-center">

                <h4 class="text-themecolor">

                    <i class="fa fa-credit-card me-2"></i>

                    Edit Subscription

                </h4>


                <h6 class="card-subtitle">

                    Update tenant subscription information

                </h6>

            </div>


            <div class="col-md-4 text-end">

                <a
                    href="{{ route('admin.subscriptions.index') }}"
                    class="btn btn-secondary"
                >

                    <i class="fa fa-arrow-left me-1"></i>

                    Back

                </a>

            </div>

        </div>



        {{-- ====================================================== --}}
        {{-- FORM --}}
        {{-- ====================================================== --}}

        <div class="row">

            <div class="col-12">

                <div class="card">


                    <div class="card-body">


                        {{-- ================================================== --}}
                        {{-- CARD TITLE --}}
                        {{-- ================================================== --}}

                        <h4 class="card-title">

                            Subscription Information

                        </h4>


                        <h6 class="card-subtitle">

                            Edit the subscription details below

                        </h6>



                        {{-- ================================================== --}}
                        {{-- VALIDATION ERRORS --}}
                        {{-- ================================================== --}}

                        @if ($errors->any())

                            <div class="alert alert-danger">

                                <h5 class="mb-2">

                                    <i class="fa fa-exclamation-triangle me-1"></i>

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



                        {{-- ================================================== --}}
                        {{-- FORM --}}
                        {{-- ================================================== --}}

                        <form
                            action="{{ route(
                                'admin.subscriptions.update',
                                $subscription
                            ) }}"
                            method="POST"
                        >

                            @csrf

                            @method('PUT')


                            <div class="row">


                                {{-- ================================================== --}}
                                {{-- TENANT --}}
                                {{-- ================================================== --}}

                                <div class="col-md-6">

                                    <div class="form-floating mb-3">


                                        <select
                                            name="tenant_id"
                                            id="tenant_id"
                                            class="form-control @error('tenant_id') is-invalid @enderror"
                                            required
                                        >

                                            <option value="">
                                                Select Tenant
                                            </option>


                                            @foreach ($tenants as $tenant)

                                                <option
                                                    value="{{ $tenant->id }}"
                                                    {{ old(
                                                        'tenant_id',
                                                        $subscription->tenant_id
                                                    ) == $tenant->id
                                                        ? 'selected'
                                                        : ''
                                                    }}
                                                >

                                                    {{ $tenant->business_name }}

                                                    @if($tenant->owner_name)

                                                        -
                                                        {{ $tenant->owner_name }}

                                                    @endif

                                                </option>

                                            @endforeach


                                        </select>


                                        <label for="tenant_id">

                                            Tenant

                                        </label>


                                        @error('tenant_id')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror


                                    </div>

                                </div>



                                {{-- ================================================== --}}
                                {{-- AMOUNT --}}
                                {{-- ================================================== --}}

                                <div class="col-md-6">

                                    <div class="form-floating mb-3">


                                        <input
                                            type="number"
                                            name="amount"
                                            id="amount"
                                            class="form-control @error('amount') is-invalid @enderror"
                                            step="0.01"
                                            min="0"
                                            value="{{ old(
                                                'amount',
                                                $subscription->amount
                                            ) }}"
                                            placeholder="Enter amount"
                                            required
                                        >


                                        <label for="amount">

                                            Monthly Amount

                                        </label>


                                        @error('amount')

                                            <div class="invalid-feedback">

                                                {{ $message }}

                                            </div>

                                        @enderror


                                    </div>

                                </div>



                                {{-- ================================================== --}}
                                {{-- CURRENT TENANT --}}
                                {{-- ================================================== --}}

                                <div class="col-md-6">

                                    <div class="form-floating mb-3">


                                        <input
                                            type="text"
                                            class="form-control"
                                            id="current_tenant"
                                            value="{{ $subscription->tenant->business_name ?? 'N/A' }}"
                                            placeholder="Current Tenant"
                                            readonly
                                        >


                                        <label for="current_tenant">

                                            Current Tenant

                                        </label>


                                    </div>

                                </div>



                                {{-- ================================================== --}}
                                {{-- SUBSCRIPTION ID --}}
                                {{-- ================================================== --}}

                                <div class="col-md-6">

                                    <div class="form-floating mb-3">


                                        <input
                                            type="text"
                                            class="form-control"
                                            id="subscription_id"
                                            value="#{{ $subscription->id }}"
                                            placeholder="Subscription ID"
                                            readonly
                                        >


                                        <label for="subscription_id">

                                            Subscription ID

                                        </label>


                                    </div>

                                </div>



                                {{-- ================================================== --}}
                                {{-- CREATED DATE --}}
                                {{-- ================================================== --}}

                                <div class="col-md-6">

                                    <div class="form-floating mb-3">


                                        <input
                                            type="text"
                                            class="form-control"
                                            id="created_at"
                                            value="{{ $subscription->created_at
                                                ? $subscription->created_at->format('d M Y, h:i A')
                                                : 'N/A'
                                            }}"
                                            placeholder="Created"
                                            readonly
                                        >


                                        <label for="created_at">

                                            Created

                                        </label>


                                    </div>

                                </div>



                                {{-- ================================================== --}}
                                {{-- LAST UPDATED --}}
                                {{-- ================================================== --}}

                                <div class="col-md-6">

                                    <div class="form-floating mb-3">


                                        <input
                                            type="text"
                                            class="form-control"
                                            id="updated_at"
                                            value="{{ $subscription->updated_at
                                                ? $subscription->updated_at->format('d M Y, h:i A')
                                                : 'N/A'
                                            }}"
                                            placeholder="Last Updated"
                                            readonly
                                        >


                                        <label for="updated_at">

                                            Last Updated

                                        </label>


                                    </div>

                                </div>



                                {{-- ================================================== --}}
                                {{-- ACTION BUTTONS --}}
                                {{-- ================================================== --}}

                                <div class="col-12">


                                    <div
                                        class="d-md-flex align-items-center mt-3"
                                    >


                                        <div>

                                            <a
                                                href="{{ route(
                                                    'admin.subscriptions.index'
                                                ) }}"
                                                class="btn btn-secondary"
                                            >

                                                <i class="fa fa-times me-1"></i>

                                                Cancel

                                            </a>

                                        </div>


                                        <div class="ms-auto mt-3 mt-md-0">


                                            <button
                                                type="submit"
                                                class="btn btn-primary text-white"
                                            >

                                                <i class="fa fa-save me-1"></i>

                                                Update Subscription

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


@include('admin.footer')


</body>

</html>