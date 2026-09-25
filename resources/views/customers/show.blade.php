<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @include('tenant.nav');
    <div class="container-fluid mt-4">

    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>
                            <h4 class="card-title mb-1">
                                Customer Details
                            </h4>

                            <small class="text-muted">
                                Customer ID: #{{ $customer->id }}
                            </small>
                        </div>

                        <div>

                            <a href="{{ route('customers.index') }}"
                               class="btn btn-secondary">
                                Back
                            </a>

                            <a href="{{ route('customers.edit', $customer->id) }}"
                               class="btn btn-primary text-white">
                                Edit Customer
                            </a>

                        </div>

                    </div>


                    {{-- Customer Information --}}

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <div class="border rounded p-3 h-100">

                                <h6 class="fw-bold mb-3">
                                    Personal Information
                                </h6>

                                <p class="mb-2">
                                    <strong>Name:</strong>
                                    {{ $customer->name }}
                                </p>

                                <p class="mb-2">
                                    <strong>Email:</strong>
                                    {{ $customer->email ?? 'N/A' }}
                                </p>

                                <p class="mb-2">
                                    <strong>Phone 1:</strong>
                                    {{ $customer->phone_1 }}
                                </p>

                                <p class="mb-2">
                                    <strong>Phone 2:</strong>
                                    {{ $customer->phone_2 ?? 'N/A' }}
                                </p>

                                <p class="mb-2">
                                    <strong>CNIC:</strong>
                                    {{ $customer->nic_number ?? 'N/A' }}
                                </p>

                                <p class="mb-0">
                                    <strong>Address:</strong>
                                    {{ $customer->address ?? 'N/A' }}
                                </p>

                            </div>

                        </div>


                        {{-- Notes --}}

                        <div class="col-md-6 mb-3">

                            <div class="border rounded p-3 h-100">

                                <h6 class="fw-bold mb-3">
                                    Additional Information
                                </h6>

                                <p class="mb-2">
                                    <strong>Notes:</strong>
                                </p>

                                <p class="text-muted">
                                    {{ $customer->notes ?? 'No notes available.' }}
                                </p>

                                <hr>

                                <p class="mb-2">
                                    <strong>Created:</strong>
                                    {{ $customer->created_at?->format('d M Y, h:i A') }}
                                </p>

                                <p class="mb-0">
                                    <strong>Last Updated:</strong>
                                    {{ $customer->updated_at?->format('d M Y, h:i A') }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- CNIC Documents --}}

                    <div class="row mt-2">

                        <div class="col-12">

                            <div class="border rounded p-3">

                                <h6 class="fw-bold mb-4">
                                    CNIC Documents
                                </h6>

                                <div class="row">

                                    {{-- CNIC Front --}}

                                    <div class="col-md-6 text-center mb-4">

                                        <h6 class="mb-3">
                                            CNIC Front
                                        </h6>

                                        @if($customer->cnic_front)

                                            <a href="{{ asset('storage/' . $customer->cnic_front) }}"
                                               target="_blank">

                                                <img src="{{ asset('storage/' . $customer->cnic_front) }}"
                                                     alt="CNIC Front"
                                                     class="img-fluid img-thumbnail"
                                                     style="max-height: 300px;">

                                            </a>

                                            <div class="mt-2">

                                                <a href="{{ asset('storage/' . $customer->cnic_front) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-primary">

                                                    View Full Image

                                                </a>

                                            </div>

                                        @else

                                            <div class="text-muted">
                                                CNIC Front not uploaded.
                                            </div>

                                        @endif

                                    </div>


                                    {{-- CNIC Back --}}

                                    <div class="col-md-6 text-center mb-4">

                                        <h6 class="mb-3">
                                            CNIC Back
                                        </h6>

                                        @if($customer->cnic_back)

                                            <a href="{{ asset('storage/' . $customer->cnic_back) }}"
                                               target="_blank">

                                                <img src="{{ asset('storage/' . $customer->cnic_back) }}"
                                                     alt="CNIC Back"
                                                     class="img-fluid img-thumbnail"
                                                     style="max-height: 300px;">

                                            </a>

                                            <div class="mt-2">

                                                <a href="{{ asset('storage/' . $customer->cnic_back) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-primary">

                                                    View Full Image

                                                </a>

                                            </div>

                                        @else

                                            <div class="text-muted">
                                                CNIC Back not uploaded.
                                            </div>

                                        @endif

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
@include('tenant.footer');
</body>
</html>