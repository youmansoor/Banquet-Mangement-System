<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Service Details</title>

    <style>

        .service-info-table th {
            width: 35%;
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
            vertical-align: middle;
        }

        .service-info-table td {
            font-size: 14px;
            vertical-align: middle;
        }

        .service-name {
            font-size: 18px;
            font-weight: 600;
            color: #343a40;
        }

        .service-amount {
            font-size: 20px;
            font-weight: 700;
            color: #198754;
        }

        .service-id {
            font-size: 13px;
            color: #6c757d;
        }

        .action-card {
            border: 0;
            border-radius: 8px;
        }

        .action-card .btn {
            margin-bottom: 10px;
        }

        .info-card {
            border: 0;
            border-radius: 8px;
        }

        .detail-icon {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            margin-right: 8px;
        }

        .meta-box {
            background: #f8f9fa;
            border-radius: 7px;
            padding: 12px 15px;
        }

        .meta-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 3px;
        }

        .meta-value {
            font-size: 13px;
            font-weight: 600;
            color: #343a40;
        }

    </style>

</head>

<body>

@include('tenant.nav')

<div class="page-wrapper">

    <div class="container-fluid">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="row page-titles">

            <div class="col-md-6">

                <h4 class="text-themecolor">

                    <i class="fa fa-cube me-2"></i>

                    Service Details

                </h4>

                <p class="text-muted mb-0">
                    View complete information about this service
                </p>

            </div>


            <div class="col-md-6 text-end">

                <a
                    href="{{ route('services.index') }}"
                    class="btn btn-secondary waves-effect waves-light me-1"
                >

                    <i class="fa fa-arrow-left me-1"></i>

                    Back

                </a>


                <a
                    href="{{ route('services.edit', $service) }}"
                    class="btn btn-warning text-white waves-effect waves-light"
                >

                    <i class="fa fa-edit me-1"></i>

                    Edit Service

                </a>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SERVICE DETAILS --}}
        {{-- ========================================================= --}}

        <div class="row">

            {{-- ===================================================== --}}
            {{-- SERVICE INFORMATION --}}
            {{-- ===================================================== --}}

            <div class="col-lg-8">

                <div class="card shadow-sm info-card">

                    <div class="card-body">

                        {{-- CARD HEADER --}}
                        <div class="d-flex align-items-center mb-4">

                            <div class="detail-icon">

                                <i class="fa fa-cube"></i>

                            </div>

                            <div>

                                <h4 class="card-title mb-1">
                                    Service Information
                                </h4>

                                <h6 class="card-subtitle text-muted mb-0">
                                    Details of the selected service
                                </h6>

                            </div>

                        </div>


                        {{-- SERVICE TABLE --}}
                        <div class="table-responsive">

                            <table
                                class="table color-table primary-table service-info-table mb-0"
                            >

                                <tbody>

                                    {{-- SERVICE NAME --}}
                                    <tr>

                                        <th>
                                            Service Name
                                        </th>

                                        <td>

                                            <span class="service-name">

                                                {{ $service->service_name }}

                                            </span>

                                        </td>

                                    </tr>


                                    {{-- SERVICE UNIT --}}
                                    <tr>

                                        <th>
                                            Service Unit
                                        </th>

                                        <td>

                                            <strong>
                                                {{ $service->service_unit }}
                                            </strong>

                                        </td>

                                    </tr>


                                    {{-- AMOUNT --}}
                                    <tr>

                                        <th>
                                            Service Amount
                                        </th>

                                        <td>

                                            <span class="service-amount">

                                                Rs.
                                                {{ number_format(
                                                    (float) $service->amount,
                                                    2
                                                ) }}

                                            </span>

                                        </td>

                                    </tr>


                                    {{-- VENDOR --}}
                                    <tr>

                                        <th>
                                            Vendor
                                        </th>

                                        <td>

                                            @if($service->vendor)

                                                <a href="{{ route('tenant.vendors.show', $service->vendor) }}" class="text-decoration-none">

                                                    <i class="fa fa-user me-1"></i>

                                                    {{ $service->vendor->vendor_name }}

                                                </a>

                                            @else

                                                <span class="text-muted">
                                                    No vendor assigned
                                                </span>

                                            @endif

                                        </td>

                                    </tr>


                                    {{-- PURCHASE AMOUNT --}}
                                    <tr>

                                        <th>
                                            Purchase Amount
                                        </th>

                                        <td>

                                            <strong>
                                                Rs.
                                                {{ number_format(
                                                    (float) $service->purchase_amount,
                                                    2
                                                ) }}
                                            </strong>

                                        </td>

                                    </tr>


                                    {{-- PAYMENT STATUS --}}
                                    <tr>

                                        <th>
                                            Vendor Payment Status
                                        </th>

                                        <td>

                                            @if($service->payment_status === 'paid')

                                                <span class="badge bg-success">
                                                    Paid
                                                </span>

                                            @else

                                                <span class="badge bg-warning">
                                                    Unpaid
                                                </span>

                                            @endif

                                        </td>

                                    </tr>


                                    {{-- SERVICE ID --}}
                                    <tr>

                                        <th>
                                            Service ID
                                        </th>

                                        <td>

                                            <span class="service-id">

                                                #{{ $service->id }}

                                            </span>

                                        </td>

                                    </tr>


                                    {{-- CREATED --}}
                                    <tr>

                                        <th>
                                            Created At
                                        </th>

                                        <td>

                                            @if($service->created_at)

                                                {{ $service->created_at->format('d M Y h:i A') }}

                                            @else

                                                <span class="text-muted">
                                                    N/A
                                                </span>

                                            @endif

                                        </td>

                                    </tr>


                                    {{-- UPDATED --}}
                                    <tr>

                                        <th>
                                            Last Updated
                                        </th>

                                        <td>

                                            @if($service->updated_at)

                                                {{ $service->updated_at->format('d M Y h:i A') }}

                                            @else

                                                <span class="text-muted">
                                                    N/A
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- ACTION CARD --}}
            {{-- ===================================================== --}}

            <div class="col-lg-4">

                <div class="card shadow-sm action-card">

                    <div class="card-body">

                        <h4 class="card-title mb-1">

                            <i class="fa fa-cog me-1"></i>

                            Actions

                        </h4>

                        <h6 class="card-subtitle text-muted mb-4">
                            Manage this service
                        </h6>


                        {{-- QUICK SERVICE INFO --}}
                        <div class="meta-box mb-3">

                            <div class="meta-label">
                                Service
                            </div>

                            <div class="meta-value">

                                {{ $service->service_name }}

                            </div>

                        </div>


                        <div class="meta-box mb-4">

                            <div class="meta-label">
                                Amount
                            </div>

                            <div class="meta-value text-success">

                                Rs.
                                {{ number_format(
                                    (float) $service->amount,
                                    2
                                ) }}

                            </div>

                        </div>


                        {{-- EDIT --}}
                        <a
                            href="{{ route('services.edit', $service) }}"
                            class="btn btn-warning text-white waves-effect waves-light w-100"
                        >

                            <span class="btn-label">

                                <i class="fa fa-edit"></i>

                            </span>

                            Edit Service

                        </a>


                        {{-- DELETE --}}
                        <form
                            action="{{ route('services.destroy', $service) }}"
                            method="POST"
                        >

                            @csrf

                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-danger waves-effect waves-light w-100"
                                onclick="return confirm('Are you sure you want to delete this service?')"
                            >

                                <span class="btn-label">

                                    <i class="fa fa-trash"></i>

                                </span>

                                Delete Service

                            </button>

                        </form>


                        {{-- BACK --}}
                        <a
                            href="{{ route('services.index') }}"
                            class="btn btn-secondary waves-effect waves-light w-100 mt-2"
                        >

                            <span class="btn-label">

                                <i class="fa fa-arrow-left"></i>

                            </span>

                            Back to Services

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@include('tenant.footer')

</body>

</html>
