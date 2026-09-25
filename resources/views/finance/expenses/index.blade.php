<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses</title>
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style.min.css') }}">
    <style>
        .expense-table { min-width: 1200px; }
        .expense-table th {
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
            vertical-align: middle;
        }
        .expense-table td {
            vertical-align: middle;
            font-size: 13px;
        }
        .amount-cell {
            text-align: right;
            white-space: nowrap;
            font-weight: 600;
            color: #dc3545;
        }
        .category-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: #e7f1ff;
            color: #0a58ca;
        }
        .filter-card {
            border: 0;
            border-radius: 8px;
        }
        .filter-card .form-label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .action-buttons { white-space: nowrap; }
        .action-buttons .btn { margin-right: 3px; }
        .empty-state { padding: 50px 20px; }
        .summary-card {
            border: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }
        .summary-card .card-title {
            font-size: 13px;
            opacity: 0.9;
            margin-bottom: 8px;
        }
        .summary-card .amount {
            font-size: 26px;
            font-weight: 700;
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
                    <i class="fa fa-money me-2"></i>
                    Expenses
                </h4>
                <p class="text-muted mb-0">
                    Manage all recorded business expenses
                </p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('expenses.create') }}" class="btn btn-info text-white">
                    <i class="fa fa-plus me-1"></i>
                    Add New Expense
                </a>
            </div>
        </div>

        {{-- Success / Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa fa-check-circle me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Summary Card --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="card-title mb-1">
                                    <i class="fa fa-file-invoice-dollar me-1"></i>
                                    TOTAL EXPENSES
                                </div>
                                <div class="amount">
                                    Rs. {{ number_format($totalAmount, 2) }}
                                </div>
                            </div>
                            <i class="fa fa-chart-line fa-2x opacity-50"></i>
                        </div>
                        <div class="mt-2 opacity-75" style="font-size: 12px;">
                            {{ $expenses->total() }} expense record(s)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card filter-card mb-3 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('expenses.index') }}">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Description, Invoice, Cheque, Vendor..."
                                value="{{ request('search') }}"
                            >
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input
                                type="date"
                                name="from_date"
                                class="form-control"
                                value="{{ request('from_date') }}"
                            >
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input
                                type="date"
                                name="to_date"
                                class="form-control"
                                value="{{ request('to_date') }}"
                            >
                        </div>
                        <div class="col-md-5 text-end">
                            <button type="submit" class="btn btn-primary me-1">
                                <i class="fa fa-search me-1"></i> Apply
                            </button>
                            <a href="{{ route('expenses.index') }}" class="btn btn-light border">
                                <i class="fa fa-times me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Expense Table --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover expense-table mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Vendor</th>
                            <th>Beneficiary</th>
                            <th>Bank</th>
                            <th>Description</th>
                            <th class="text-end">Amount (Rs.)</th>
                            <th>Invoice / Cheque</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($expenses as $index => $expense)
                            <tr>
                                <td>{{ $expenses->firstItem() + $index }}</td>
                                <td style="white-space: nowrap;">
                                    {{ \Carbon\Carbon::parse($expense->transaction_date)->format('d M, Y') }}
                                </td>
                                <td>
                                    @if($expense->paymentCategory)
                                        <span class="category-badge">
                                            {{ $expense->paymentCategory->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($expense->vendor)
                                        <div style="font-weight: 600;">{{ $expense->vendor->vendor_name }}</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $expense->beneficiary->name ?? '-' }}</td>
                                <td>
                                    @if($expense->tenantBank)
                                        <div style="font-size: 12px;">
                                            <strong>{{ $expense->tenantBank->bank_name }}</strong><br>
                                            <span class="text-muted">{{ $expense->tenantBank->account_number }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td style="max-width: 240px;">
                                    {{ \Illuminate\Support\Str::limit($expense->item_description, 60) ?? '-' }}
                                    @if($expense->remarks)
                                        <div class="text-muted" style="font-size: 11px;">
                                            {{ \Illuminate\Support\Str::limit($expense->remarks, 40) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="amount-cell">
                                    {{ number_format($expense->amount, 2) }}
                                </td>
                                <td style="font-size: 12px;">
                                    @if($expense->vendor_invoice)
                                        <div><strong>Inv:</strong> {{ $expense->vendor_invoice }}</div>
                                    @endif
                                    @if($expense->cheque_number)
                                        <div><strong>Chq:</strong> {{ $expense->cheque_number }}</div>
                                        @if($expense->cheque_date)
                                            <div class="text-muted">
                                                {{ \Carbon\Carbon::parse($expense->cheque_date)->format('d M, Y') }}
                                            </div>
                                        @endif
                                    @endif
                                    @if(!$expense->vendor_invoice && !$expense->cheque_number)
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="action-buttons">
                                    <a
                                        href="{{ route('expenses.show', $expense->id) }}"
                                        class="btn btn-sm btn-secondary"
                                        title="View"
                                    >
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a
                                        href="{{ route('expenses.edit', $expense->id) }}"
                                        class="btn btn-sm btn-primary"
                                        title="Edit"
                                    >
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form
                                        method="POST"
                                        action="{{ route('expenses.destroy', $expense->id) }}"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this expense? This cannot be undone.');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                            title="Delete"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center empty-state">
                                    <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">No expenses found</h6>
                                    <p class="text-muted mb-3">
                                        {{ request('search') || request('from_date') || request('to_date')
                                            ? 'Try adjusting your filters.'
                                            : 'Start by recording your first expense.' }}
                                    </p>
                                    <a href="{{ route('expenses.create') }}" class="btn btn-info text-white">
                                        <i class="fa fa-plus me-1"></i> Record First Expense
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($expenses->hasPages())
                    <div class="card-footer bg-white border-top">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p class="mb-0 text-muted" style="font-size: 13px;">
                                    Showing {{ $expenses->firstItem() }} to {{ $expenses->lastItem() }}
                                    of {{ $expenses->total() }} results
                                </p>
                            </div>
                            <div class="col-md-6 text-end">
                                {{ $expenses->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@include('tenant.footer')

</body>
</html>
