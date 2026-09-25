<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    {{ $quotation->quotation_number }}
</title>

<style>

body {
    font-family: Arial, sans-serif;
    color: #222;
    margin: 40px;
}

.header {
    display: flex;
    justify-content: space-between;
    border-bottom: 2px solid #222;
    padding-bottom: 15px;
    margin-bottom: 25px;
}

.header h1 {
    margin: 0;
}

.info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 25px;
}

.info-box {
    width: 48%;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th,
table td {
    border: 1px solid #ddd;
    padding: 10px;
}

table th {
    background: #f5f5f5;
    text-align: left;
}

.total {
    font-size: 18px;
    font-weight: bold;
}

.section {
    margin-top: 25px;
}

@media print {

    .no-print {
        display: none;
    }

    body {
        margin: 10px;
    }

}

</style>

</head>

<body>


<div class="no-print"
     style="text-align:right;margin-bottom:20px;">

<button onclick="window.print()">
    Print
</button>

</div>


<div class="header">

<div>

<h1>
    QUOTATION
</h1>

<p>
    {{ $quotation->quotation_number }}
</p>

</div>


<div style="text-align:right;">

<h3>
    {{ $quotation->tenant->business_name ?? 'Banquet Business' }}
</h3>

<p>
    {{ $quotation->tenant->address ?? '' }}
</p>

<p>
    {{ $quotation->tenant->phone ?? '' }}
</p>

</div>

</div>


<div class="info">


<div class="info-box">

<h3>
    Customer
</h3>

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


<div class="info-box">

<h3>
    Event
</h3>

<p>
    <strong>Event:</strong>
    {{ $quotation->event_type }}
</p>

<p>
    <strong>Venue:</strong>
    {{ $quotation->lawnType->lawn_type ?? 'N/A' }}
</p>

<p>
    <strong>Date:</strong>
    {{ $quotation->event_date?->format('d M Y') }}
</p>

<p>
    <strong>Time:</strong>
    {{ ucfirst($quotation->booking_time) }}
</p>

<p>
    <strong>Guests:</strong>
    {{ number_format($quotation->number_of_guests) }}
</p>

</div>

</div>


<div class="section">

<h3>
    Package
</h3>

<p>
    {{ $quotation->package_name ?? 'N/A' }}
</p>

</div>


<div class="section">

<h3>
    Menu Details
</h3>

<p>
    {!! nl2br(e($quotation->menu_details ?? 'N/A')) !!}
</p>

</div>


<div class="section">

<h3>
    Services
</h3>

<p>
    {!! nl2br(e($quotation->services ?? 'N/A')) !!}
</p>

</div>


<table>

<tr>

<th>
    Description
</th>

<th style="width:200px;">
    Amount
</th>

</tr>


<tr>

<td>
    Subtotal
</td>

<td>
    Rs. {{ number_format($quotation->subtotal, 2) }}
</td>

</tr>


<tr>

<td>
    Discount
</td>

<td>
    - Rs. {{ number_format($quotation->discount, 2) }}
</td>

</tr>


<tr>

<td>
    Tax
</td>

<td>
    Rs. {{ number_format($quotation->tax, 2) }}
</td>

</tr>


<tr class="total">

<td>
    TOTAL
</td>

<td>
    Rs. {{ number_format($quotation->total_amount, 2) }}
</td>

</tr>

</table>


<div class="section">

<p>
    <strong>
        Valid Until:
    </strong>

    {{ $quotation->valid_until
        ? $quotation->valid_until->format('d M Y')
        : 'N/A' }}

</p>

<p>

<strong>
    Status:
</strong>

{{ ucfirst($quotation->status) }}

</p>

<p>

<strong>
    Notes:
</strong>

{{ $quotation->notes ?? 'N/A' }}

</p>

</div>


<div style="margin-top:80px;">

<table>

<tr>

<td style="border:none;text-align:center;">

________________________

<br>

Customer Signature

</td>


<td style="border:none;text-align:center;">

________________________

<br>

Authorized Signature

</td>

</tr>

</table>

</div>


</body>

</html>