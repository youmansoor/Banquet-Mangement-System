<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        {{ $quotation->quotation_number }}
    </title>

</head>

<body>

@include('tenant.nav')

<div class="page-wrapper">

<div class="container-fluid">


<div class="row page-titles">

<div class="col-md-8">

<h4 class="text-themecolor">

    Quotation

    <strong>
        {{ $quotation->quotation_number }}
    </strong>

</h4>

</div>


<div class="col-md-4 text-end">
<a
    href="{{ route('quotations.edit', $quotation) }}"
    class="btn btn-primary"
>
    Edit
</a>

</div>

</div>


@if(session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif


<div class="card">

<div class="card-body">


<div class="row">


<div class="col-md-6">

<h5>
    Customer Information
</h5>

<hr>

<p>
    <strong>Name:</strong>
    {{ $quotation->customer->name ?? 'N/A' }}
</p>

<p>
    <strong>Phone:</strong>
    {{ $quotation->customer->phone_1 ?? 'N/A' }}
</p>

<p>
    <strong>CNIC:</strong>
    {{ $quotation->customer->nic_number
        ?? $quotation->customer->cnic
        ?? 'N/A' }}
</p>

<p>
    <strong>Address:</strong>
    {{ $quotation->customer->address ?? 'N/A' }}
</p>

</div>


@if(auth()->user()->role === 'super_admin')

<div class="col-md-6">

<h5>
    Tenant Information
</h5>

<hr>

<p>
    <strong>Business:</strong>
    {{ $quotation->tenant->business_name ?? 'N/A' }}
</p>

<p>
    <strong>Owner:</strong>
    {{ $quotation->tenant->owner_name ?? 'N/A' }}
</p>

<p>
    <strong>Phone:</strong>
    {{ $quotation->tenant->phone ?? 'N/A' }}
</p>

</div>

@endif


</div>


<hr>


<div class="row">


<div class="col-md-4">

<strong>Event Type</strong>

<p>
    {{ $quotation->event_type }}
</p>

</div>


<div class="col-md-4">

<strong>Venue</strong>

<p>
    {{ $quotation->lawnType->lawn_type ?? 'N/A' }}
</p>

</div>


<div class="col-md-4">

<strong>Event Date</strong>

<p>
    {{ $quotation->event_date?->format('d M Y') }}
</p>

</div>


<div class="col-md-4">

<strong>Time</strong>

<p>
    {{ ucfirst($quotation->booking_time) }}
</p>

</div>


<div class="col-md-4">

<strong>Guests</strong>

<p>
    {{ number_format($quotation->number_of_guests) }}
</p>

</div>


<div class="col-md-4">

<strong>Package</strong>

<p>
    {{ $quotation->package_name ?? 'N/A' }}
</p>

</div>

</div>


<hr>


<div class="row">


<div class="col-md-6">

<h5>
    Menu Details
</h5>

<div class="border p-3">

{!! nl2br(e($quotation->menu_details ?? 'N/A')) !!}

</div>

</div>


<div class="col-md-6">
<h5>
    Services
</h5>

<div class="border p-3">

    @php
        $services = $quotation->services;

        // Database mein JSON string save hai
        if (is_string($services)) {
            $services = json_decode($services, true);
        }

        // Agar null ya invalid JSON ho
        if (!is_array($services)) {
            $services = [];
        }
    @endphp

    @if(count($services) > 0)

        <div class="table-responsive">

            <table class="table table-bordered table-sm mb-0">

                <thead>
                    <tr>
                        <th>Service</th>
                        <th class="text-end">Amount</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($services as $service)

                        <tr>

                            <td>
                                {{ $service['name'] ?? 'N/A' }}
                            </td>

                            <td class="text-end">
                                Rs.
                                {{ number_format((float)($service['amount'] ?? 0), 2) }}
                            </td>

                            <td class="text-center">
                                {{ $service['quantity'] ?? 0 }}
                            </td>

                            <td class="text-end">
                                Rs.
                                {{ number_format((float)($service['total'] ?? 0), 2) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

                <tfoot>

                    <tr class="table-light">

                        <th colspan="3" class="text-end">
                            Services Total
                        </th>

                        <th class="text-end">
                            Rs.
                            {{ number_format(
                                collect($services)->sum(function ($service) {
                                    return (float)($service['total'] ?? 0);
                                }),
                                2
                            ) }}
                        </th>

                    </tr>

                </tfoot>

            </table>

        </div>

    @else

        <p class="text-muted mb-0">
            No services added.
        </p>

    @endif

</div>
</div>


</div>


<hr>


<div class="row justify-content-end">

<div class="col-md-5">

<table class="table">

<tr>

<th>
    Subtotal
</th>

<td class="text-end">
    Rs. {{ number_format($quotation->subtotal, 2) }}
</td>

</tr>


<tr>

<th>
    Discount
</th>

<td class="text-end text-danger">

    - Rs.
    {{ number_format($quotation->discount, 2) }}

</td>

</tr>


<tr>

<th>
    Tax
</th>

<td class="text-end">

    Rs.
    {{ number_format($quotation->tax, 2) }}

</td>

</tr>


<tr class="table-success">

<th>
    Total
</th>

<th class="text-end">

    Rs.
    {{ number_format($quotation->totalamount, 2) }}

</th>

</tr>

</table>

</div>

</div>


<hr>


<div class="row">


<div class="col-md-4">

<strong>
    Valid Until
</strong>

<p>

{{ $quotation->valid_until
    ? $quotation->valid_until->format('d M Y')
    : 'N/A' }}

</p>

</div>


<div class="col-md-4">

<strong>
    Status
</strong>

<p>

{{ ucfirst($quotation->status) }}

</p>

</div>


<div class="col-md-4">

<strong>
    Notes
</strong>

<p>

{{ $quotation->notes ?? 'N/A' }}

</p>

</div>

</div>


</div>

</div>

</div>

</div>

@include('tenant.footer')

</body>

</html>