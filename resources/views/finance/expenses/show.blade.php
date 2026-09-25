<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Details #{{ $expenseModel->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style.min.css') }}">
    <style>
        .info-item {
            padding: 12px 0;
            border-bottom: 1px solid #edf1f5;
        }
        .info-item:last-child { border-bottom: 0; }
        .info-item small {
            display: block;
            color: #6c757d;
            margin-bottom: 4px;
            font-size: 12px;
        }
        .info-item h6 {
            margin-bottom: 0;
            font-weight: 600;
            word-break: break-word;
        }
        .amount-card {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: #fff;
            border: 0;
            border-radius: 10px;
        }
        .amount-card .label {
            font-size: 13px;
            opacity: 0.9;
        }
        .amount-card .value {
            font-size: 30px;
            font-weight: 700;
            margin-top: 6px;
        }
    </style>
</head>
<body>

@include('tenant.nav')

<div class="page-wrapper">
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="row page-titles">
            <div class="col-md-6">
                <h4 class="text-themecolor">
                    <i class="fa fa-file-invoice me-2"></i>
                    Expense Details
                </h4>
                <p class="text-muted mb-0">
                    Expense #{{ $expenseModel->id }}
                </p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('expenses.index') }}" class="btn btn-light border me-2">
                    <i class="fa fa-arrow-left me-1"></i> Back to List
                </a>
                <a href="{{ route('expenses.edit', $expenseModel->id) }}" class="btn btn-primary me-2">
                    <i class="fa fa-edit me-1"></i> Edit
                </a>
                <form
                    method="POST"
                    action="{{ route('expenses.destroy', $expenseModel->id) }}"
                    style="display:inline;"
                    onsubmit="return confirm('Delete this expense permanently?');"
                >
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash me-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="row">
            {{-- Amount Summary --}}
            <div class="col-md-4">
                <div class="card amount-card mb-4">
                    <div class="card-body text-center">
                        <div class="label">EXPENSE AMOUNT</div>
                        <div class="value">Rs. {{ number_format($expenseModel->amount, 2) }}</div>
                        <div class="mt-2 opacity-75" style="font-size: 12px;">
                            <i class="fa fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($expenseModel->transaction_date)->format('d F, Y') }}
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="fa fa-tags me-1 text-primary"></i> Classification</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="info-item px-3">
                            <small>Payment Category</small>
                            <h6>{{ $expenseModel->paymentCategory->name ?? '-' }}</h6>
                        </div>
                        <div class="info-item px-3">
                            <small>Transaction Type</small>
                            <h6>
                                <span class="badge bg-danger text-white">
                                    {{ strtoupper(str_replace('_', ' ', $expenseModel->transaction_type)) }}
                                </span>
                            </h6>
                        </div>
                        <div class="info-item px-3">
                            <small>Direction</small>
                            <h6>
                                {{ strtoupper($expenseModel->transaction_direction) }}
                                ({{ $expenseModel->transaction_direction === 'dr' ? 'Debit / Money Out' : 'Credit' }})
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Details --}}
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="fa fa-info-circle me-1 text-primary"></i> Transaction Details</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-6 border-end">
                                <div class="info-item px-3">
                                    <small>Transaction Date</small>
                                    <h6>{{ \Carbon\Carbon::parse($expenseModel->transaction_date)->format('l, d F, Y') }}</h6>
                                </div>
                                <div class="info-item px-3">
                                    <small>Vendor</small>
                                    <h6>{{ $expenseModel->vendor->vendor_name ?? '-' }}</h6>
                                </div>
                                <div class="info-item px-3">
                                    <small>Beneficiary</small>
                                    <h6>{{ $expenseModel->beneficiary->name ?? '-' }}</h6>
                                </div>
                                <div class="info-item px-3">
                                    <small>Bank Account</small>
                                    <h6>
                                        @if($expenseModel->tenantBank)
                                            {{ $expenseModel->tenantBank->bank_name }}
                                            <span class="text-muted fw-normal ms-1">({{ $expenseModel->tenantBank->account_number }})</span>
                                        @else
                                            -
                                        @endif
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item px-3">
                                    <small>Vendor Invoice #</small>
                                    <h6>{{ $expenseModel->vendor_invoice ?? '-' }}</h6>
                                </div>
                                <div class="info-item px-3">
                                    <small>Cheque Number</small>
                                    <h6>{{ $expenseModel->cheque_number ?? '-' }}</h6>
                                </div>
                                <div class="info-item px-3">
                                    <small>Cheque Date</small>
                                    <h6>
                                        {{ $expenseModel->cheque_date
                                            ? \Carbon\Carbon::parse($expenseModel->cheque_date)->format('d F, Y')
                                            : '-' }}
                                    </h6>
                                </div>
                                <div class="info-item px-3">
                                    <small>Recorded At</small>
                                    <h6>{{ $expenseModel->created_at->format('d F, Y h:i A') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Description & Remarks --}}
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0"><i class="fa fa-align-left me-1 text-primary"></i> Description &amp; Remarks</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label text-muted" style="font-size: 12px; font-weight: 600;">Item Description</label>
                            <div>
                                {{ $expenseModel->item_description ?? 'No description provided.' }}
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-muted" style="font-size: 12px; font-weight: 600;">Remarks</label>
                            <div>
                                {{ $expenseModel->remarks ?? 'No remarks provided.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('tenant.footer')

</body>
</html>
