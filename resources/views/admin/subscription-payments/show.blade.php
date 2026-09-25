<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Payment Details</title>
</head>
<body>
    @include('admin.nav')
    <div class="row g-0">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Subscription Payment Details</h3>
                    <a href="{{ route('admin.subscription-payments.index') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $subscriptionPayment->id }}</td>
                                </tr>
                                <tr>
                                    <th>Tenant</th>
                                    <td>{{ $subscriptionPayment->tenant->business_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Subscription</th>
                                    <td>{{ $subscriptionPayment->subscription->plan->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>{{ number_format($subscriptionPayment->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Method</th>
                                    <td>{{ ucfirst($subscriptionPayment->payment_method) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ $subscriptionPayment->status === 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($subscriptionPayment->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment Date</th>
                                    <td>{{ $subscriptionPayment->paid_at ? $subscriptionPayment->paid_at->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Transaction Reference</th>
                                    <td>{{ $subscriptionPayment->transaction_reference ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Notes</th>
                                    <td>{{ $subscriptionPayment->notes ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $subscriptionPayment->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.subscription-payments.edit', $subscriptionPayment) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('admin.footer')
</body>
</html>