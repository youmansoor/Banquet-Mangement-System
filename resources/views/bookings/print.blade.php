<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Contract #{{ $booking->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #212529;
        }
        .print-wrapper {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            padding: 40px;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .business-header {
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .business-logo {
            max-width: 120px;
            max-height: 80px;
            object-fit: contain;
        }
        .contract-title {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: #0d6efd;
            margin: 20px 0 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .contract-subtitle {
            text-align: center;
            color: #6c757d;
            margin-bottom: 25px;
            font-size: 13px;
        }
        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #0d6efd;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 6px;
            margin: 22px 0 14px;
        }
        .info-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px dashed #e9ecef;
            font-size: 14px;
        }
        .info-row:last-child {
            border-bottom: 0;
        }
        .info-label {
            width: 180px;
            color: #6c757d;
            font-weight: 600;
        }
        .info-value {
            flex: 1;
            font-weight: 500;
        }
        .amount-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 18px;
            margin-top: 15px;
        }
        .amount-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        .amount-row.total {
            border-top: 2px solid #0d6efd;
            margin-top: 8px;
            padding-top: 12px;
            font-size: 18px;
            font-weight: 700;
            color: #0d6efd;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 80px;
        }
        .signature-box {
            width: 40%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #343a40;
            padding-top: 10px;
            font-size: 13px;
            color: #6c757d;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-confirmed { background: #d1e7dd; color: #0f5132; }
        .status-pending   { background: #fff3cd; color: #664d03; }
        .status-cancelled { background: #f8d7da; color: #842029; }
        .table th {
            font-size: 13px;
            font-weight: 600;
            background: #f8f9fa;
        }
        .table td {
            font-size: 13px;
            vertical-align: middle;
        }
        @media print {
            body { background: #fff; }
            .print-wrapper {
                box-shadow: none;
                border: 0;
                margin: 0;
                padding: 20px;
            }
            .no-print { display: none !important; }
        }
        .print-btn-bar {
            max-width: 900px;
            margin: 0 auto 15px;
            text-align: right;
        }
    </style>
</head>
<body>

<div class="no-print print-btn-bar">
    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary me-2">
        <i class="fa fa-arrow-left me-1"></i> Back
    </a>
    <button onclick="window.print()" class="btn btn-primary">
        <i class="fa fa-print me-1"></i> Print Contract
    </button>
</div>

<div class="print-wrapper">

    {{-- Business Header --}}
    <div class="business-header row align-items-center">
        <div class="col-3">
            @if($tenant && $tenant->logo)
                <img src="{{ asset('storage/'.$tenant->logo) }}" alt="Logo" class="business-logo">
            @else
                <div class="d-flex align-items-center">
                    <i class="fa fa-building fa-2x text-primary me-2"></i>
                    <div>
                        <h6 class="mb-0">{{ $tenant->business_name ?? 'Banquet Management' }}</h6>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-6 text-center">
            <h3 class="mb-1">{{ $tenant->business_name ?? 'Banquet Management System' }}</h3>
            <p class="text-muted mb-1" style="font-size: 13px;">
                {{ $tenant->address ?? '' }}
            </p>
            <p class="mb-0" style="font-size: 13px;">
                <i class="fa fa-phone text-primary"></i> {{ $tenant->phone ?? '' }}
                &nbsp;|&nbsp;
                <i class="fa fa-envelope text-primary"></i> {{ $tenant->email ?? '' }}
            </p>
        </div>
        <div class="col-3 text-end">
            <div style="font-size: 12px; color: #6c757d;">Booking ID</div>
            <div style="font-size: 20px; font-weight: 700; color: #0d6efd;">#{{ $booking->id }}</div>
            <div>
                <span class="status-badge status-{{ $booking->status }}">
                    {{ strtoupper($booking->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="contract-title">Booking Contract</div>
    <div class="contract-subtitle">
        Dated: {{ \Carbon\Carbon::parse($booking->created_at)->format('d F, Y') }}
        &nbsp;|&nbsp;
        Booking Date: {{ \Carbon\Carbon::parse($booking->booking_date)->format('d F, Y') }}
        ({{ ucfirst($booking->booking_time) }})
    </div>

    {{-- Customer Info --}}
    <div class="section-title"><i class="fa fa-user-circle me-2"></i>Customer Information</div>
    <div class="row">
        <div class="col-md-6">
            <div class="info-row">
                <div class="info-label">Customer Name</div>
                <div class="info-value">{{ $booking->customer->name ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone # 1</div>
                <div class="info-value">{{ $booking->customer->phone_1 ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone # 2</div>
                <div class="info-value">{{ $booking->customer->phone_2 ?? '-' }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-row">
                <div class="info-label">CNIC Number</div>
                <div class="info-value">{{ $booking->customer->nic_number ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $booking->customer->email ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Address</div>
                <div class="info-value">{{ $booking->customer->address ?? '-' }}</div>
            </div>
        </div>
    </div>

    {{-- Event Info --}}
    <div class="section-title"><i class="fa fa-calendar-alt me-2"></i>Event Information</div>
    <div class="row">
        <div class="col-md-6">
            <div class="info-row">
                <div class="info-label">Event Type</div>
                <div class="info-value">{{ ucwords(str_replace('_', ' ', $booking->event_type)) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Venue / Hall</div>
                <div class="info-value">{{ $booking->lawnType->lawn_type ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Venue Capacity</div>
                <div class="info-value">{{ $booking->lawnType->capacity ?? '-' }} persons</div>
            </div>
            <div class="info-row">
                <div class="info-label">Booking Date</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('l, d F, Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Booking Time</div>
                <div class="info-value">{{ ucfirst($booking->booking_time) }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-row">
                <div class="info-label">Start Time</div>
                <div class="info-value">{{ $booking->start_time ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">End Time</div>
                <div class="info-value">{{ $booking->end_time ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Expected Guests</div>
                <div class="info-value">{{ $booking->guest_count ?? 0 }} persons</div>
            </div>
            <div class="info-row">
                <div class="info-label">Per Person Rate</div>
                <div class="info-value">Rs. {{ number_format($booking->per_head_rate ?? 0, 2) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Payment Method</div>
                <div class="info-value">{{ ucwords(str_replace('_', ' ', $booking->payment_method ?? '-')) }}</div>
            </div>
        </div>
    </div>

    {{-- Paid Services --}}
    @if($booking->bookingServices && $booking->bookingServices->where('service_type', 'paid')->count() > 0)
        <div class="section-title"><i class="fa fa-list-alt me-2"></i>Paid Services &amp; Menu</div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Service Name</th>
                <th class="text-center">Quantity</th>
                <th class="text-end">Rate (Rs.)</th>
                <th class="text-end">Amount (Rs.)</th>
            </tr>
            </thead>
            <tbody>
            @php $svcNo = 1; $svcTotal = 0; @endphp
            @foreach($booking->bookingServices->where('service_type', 'paid') as $bs)
                @php
                    $name = $bs->paidService->service_name ?? 'Custom Service';
                    $qty = $bs->quantity ?? 1;
                    $rate = $bs->rate ?? 0;
                    $amt = $qty * $rate;
                    $svcTotal += $amt;
                @endphp
                <tr>
                    <td>{{ $svcNo++ }}</td>
                    <td>
                        {{ $name }}
                        @if($bs->paidService && $bs->paidService->description)
                            <div class="text-muted" style="font-size: 11px;">{{ $bs->paidService->description }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $qty }}</td>
                    <td class="text-end">{{ number_format($rate, 2) }}</td>
                    <td class="text-end">{{ number_format($amt, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="4" class="text-end">Services Subtotal</th>
                <th class="text-end">{{ number_format($svcTotal, 2) }}</th>
            </tr>
            </tfoot>
        </table>
    @endif

    {{-- Free Services --}}
    @if($booking->bookingServices && $booking->bookingServices->where('service_type', 'free')->count() > 0)
        <div class="section-title"><i class="fa fa-gift me-2"></i>Complimentary Services</div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Service Name</th>
                <th class="text-center">Quantity</th>
            </tr>
            </thead>
            <tbody>
            @php $fNo = 1; @endphp
            @foreach($booking->bookingServices->where('service_type', 'free') as $bs)
                <tr>
                    <td>{{ $fNo++ }}</td>
                    <td>{{ $bs->freeService->service_name ?? 'Complimentary Service' }}</td>
                    <td class="text-center">{{ $bs->quantity ?? 1 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    {{-- Payment Summary --}}
    <div class="section-title"><i class="fa fa-calculator me-2"></i>Payment Summary</div>
    <div class="row">
        <div class="col-md-7">
            @if($booking->payments && $booking->payments->count() > 0)
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Ref #</th>
                        <th class="text-end">Amount (Rs.)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $paidTotal = 0; @endphp
                    @foreach($booking->payments as $payment)
                        @php $paidTotal += $payment->amount ?? 0; @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $payment->payment_method ?? '-')) }}</td>
                            <td>{{ $payment->transaction_reference ?? '-' }}</td>
                            <td class="text-end">{{ number_format($payment->amount ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total Paid</th>
                        <th class="text-end">{{ number_format($paidTotal, 2) }}</th>
                    </tr>
                    </tfoot>
                </table>
            @else
                <div class="alert alert-warning">
                    <i class="fa fa-info-circle me-1"></i>
                    No payments recorded yet.
                </div>
            @endif
        </div>
        <div class="col-md-5">
            <div class="amount-box">
                @php
                    $subtotal = $booking->total_amount ?? 0;
                    $discount = $booking->discount_amount ?? 0;
                    $tax = $booking->tax_amount ?? 0;
                    $grand = $booking->grand_total ?? ($subtotal - $discount + $tax);
                    $paidTotal = $booking->payments->sum('amount') ?? 0;
                    $remaining = max(0, $grand - $paidTotal);
                @endphp
                <div class="amount-row">
                    <span>Subtotal</span>
                    <span>Rs. {{ number_format($subtotal, 2) }}</span>
                </div>
                @if($discount > 0)
                    <div class="amount-row">
                        <span>Discount</span>
                        <span class="text-success">- Rs. {{ number_format($discount, 2) }}</span>
                    </div>
                @endif
                @if($tax > 0)
                    <div class="amount-row">
                        <span>Tax</span>
                        <span>+ Rs. {{ number_format($tax, 2) }}</span>
                    </div>
                @endif
                <div class="amount-row total">
                    <span>Grand Total</span>
                    <span>Rs. {{ number_format($grand, 2) }}</span>
                </div>
                <hr>
                <div class="amount-row">
                    <span>Total Paid</span>
                    <span class="text-success">Rs. {{ number_format($paidTotal, 2) }}</span>
                </div>
                <div class="amount-row" style="font-weight: 700;">
                    <span>Balance Due</span>
                    <span class="{{ $remaining > 0 ? 'text-danger' : 'text-success' }}">
                        Rs. {{ number_format($remaining, 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Notes / Terms --}}
    @if($booking->notes || $booking->special_requirements)
        <div class="section-title"><i class="fa fa-sticky-note me-2"></i>Notes &amp; Special Requirements</div>
        @if($booking->special_requirements)
            <p class="mb-2" style="font-size: 14px;"><strong>Special Requirements:</strong> {{ $booking->special_requirements }}</p>
        @endif
        @if($booking->notes)
            <p style="font-size: 14px;"><strong>Notes:</strong> {{ $booking->notes }}</p>
        @endif
    @endif

    {{-- Signatures --}}
    <div class="signatures">
        <div class="signature-box">
            <div class="signature-line">
                <strong>Customer Signature</strong><br>
                {{ $booking->customer->name ?? '' }}
            </div>
        </div>
        <div class="signature-box">
            <div class="signature-line">
                <strong>Authorized Signatory</strong><br>
                {{ $tenant->business_name ?? '' }}
            </div>
        </div>
    </div>

    <div class="mt-5 text-center text-muted" style="font-size: 11px;">
        <p class="mb-1">
            This is a computer generated contract. By signing, both parties agree to the booking terms and conditions.
        </p>
        <p class="mb-0">
            Generated on {{ \Carbon\Carbon::now()->format('d F, Y h:i A') }}
        </p>
    </div>

</div>

</body>
</html>
