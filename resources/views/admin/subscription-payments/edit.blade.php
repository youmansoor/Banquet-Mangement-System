<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subscription Payment</title>
</head>
<body>
    @include('admin.nav')
    <div class="row g-0">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Subscription Payment</h3>
                    <a href="{{ route('admin.subscription-payments.index') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subscription-payments.update', $subscriptionPayment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="tenant_id">Tenant</label>
                            <select name="tenant_id" id="tenant_id" class="form-control" required>
                                <option value="">Select Tenant</option>
                                @foreach($subscriptions as $subscription)
                                    <option value="{{ $subscription->tenant->id }}" {{ $subscription->tenant->id == $subscriptionPayment->tenant_id ? 'selected' : '' }}>
                                        {{ $subscription->tenant->business_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subscription_id">Subscription</label>
                            <select name="subscription_id" id="subscription_id" class="form-control" required>
                                <option value="">Select Subscription</option>
                                @foreach($subscriptions as $subscription)
                                    <option value="{{ $subscription->id }}" {{ $subscription->id == $subscriptionPayment->subscription_id ? 'selected' : '' }}>
                                        Subscription #{{ $subscription->id }} - {{ $subscription->plan->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0" value="{{ $subscriptionPayment->amount }}" required>
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="cash" {{ $subscriptionPayment->payment_method === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ $subscriptionPayment->payment_method === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cheque" {{ $subscriptionPayment->payment_method === 'cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="online" {{ $subscriptionPayment->payment_method === 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="transaction_reference">Transaction Reference</label>
                            <input type="text" name="transaction_reference" id="transaction_reference" class="form-control" maxlength="255" value="{{ $subscriptionPayment->transaction_reference ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label for="paid_at">Payment Date</label>
                            <input type="date" name="paid_at" id="paid_at" class="form-control" value="{{ $subscriptionPayment->paid_at ? $subscriptionPayment->paid_at->format('Y-m-d') : '' }}">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="pending" {{ $subscriptionPayment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $subscriptionPayment->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $subscriptionPayment->status === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ $subscriptionPayment->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3">{{ $subscriptionPayment->notes ?? '' }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.footer')
</body>
</html>