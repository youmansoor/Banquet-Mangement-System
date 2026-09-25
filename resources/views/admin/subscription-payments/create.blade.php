<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Subscription Payment</title>
</head>
<body>
    @include('admin.nav')
    <div class="row g-0">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Subscription Payment</h3>
                    <a href="{{ route('admin.subscription-payments.index') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.subscription-payments.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="tenant_id">Tenant</label>
                            <select name="tenant_id" id="tenant_id" class="form-control" required>
                                <option value="">Select Tenant</option>
                                @foreach($subscriptions as $subscription)
                                    <option value="{{ $subscription->tenant->id }}">{{ $subscription->tenant->business_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subscription_id">Subscription</label>
                            <select name="subscription_id" id="subscription_id" class="form-control" required>
                                <option value="">Select Subscription</option>
                                @foreach($subscriptions as $subscription)
                                    <option value="{{ $subscription->id }}">Subscription #{{ $subscription->id }} - {{ $subscription->plan->name ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="transaction_reference">Transaction Reference</label>
                            <input type="text" name="transaction_reference" id="transaction_reference" class="form-control" maxlength="255">
                        </div>
                        <div class="form-group">
                            <label for="paid_at">Payment Date</label>
                            <input type="date" name="paid_at" id="paid_at" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="failed">Failed</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.footer')
</body>
</html>