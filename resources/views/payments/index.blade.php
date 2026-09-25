<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tenant Payments | VenueFlow</title>

    <link rel="icon"
          type="image/png"
          href="{{ asset('assets/images/favicon.png') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/dist/css/style.min.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/dist/css/pages/dashboard1.css') }}">
</head>

<body>

@include('admin.nav')

<div class="container-fluid mt-4">

    {{-- HEADER --}}
    <div class="row page-titles">

        <div class="col-md-8">

            <h4 class="text-themecolor">

                <i class="ti-wallet me-2"></i>

                Tenant Payments

            </h4>

            <p class="text-muted mb-0">

                Manage tenant subscription payment history

            </p>

        </div>

        <div class="col-md-4 text-end">

            <a href="{{ route('admin.tenant-payments.create') }}"
               class="btn btn-primary">

                <i class="fa fa-plus me-1"></i>

                Add Payment

            </a>

        </div>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success">

            <i class="fa fa-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- TABLE --}}
    <div class="row">

        <div class="col-lg-12">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title">
                        Tenants
                    </h4>

                    <h6 class="card-subtitle">
                        Select a tenant to view complete payment history
                    </h6>


                    <div class="table-responsive">

                        <table class="table color-table primary-table">

                            <thead>

                                <tr>

                                    <th>#</th>

                                    <th>Tenant Name</th>

                                    <th>Banquet Name</th>

                                    <th>Account Created</th>

                                    <th>Action</th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($tenants as $tenant)

                                    <tr>

                                        {{-- NUMBER --}}
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>


                                        {{-- TENANT NAME --}}
                                        <td>

                                            <strong>

                                                {{ $tenant->owner_name ?? 'N/A' }}

                                            </strong>

                                        </td>


                                        {{-- BANQUET NAME --}}
                                        <td>

                                            {{ $tenant->business_name }}

                                        </td>


                                        {{-- CREATED AT --}}
                                        <td>

                                            {{ $tenant->created_at
                                                ? $tenant->created_at->format('d-m-Y')
                                                : 'N/A'
                                            }}

                                        </td>


                                        {{-- PAYMENT HISTORY --}}
                                        <td>

                                            <a
                                                href="{{ route(
                                                    'admin.tenant-payments.history',
                                                    $tenant->id
                                                ) }}"
                                                class="btn btn-sm btn-primary"
                                            >

                                                <i class="fa fa-history me-1"></i>

                                                Payment History

                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5"
                                            class="text-center">

                                            <i class="fa fa-info-circle me-1"></i>

                                            No tenants found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@include('admin.footer')

</body>

</html>