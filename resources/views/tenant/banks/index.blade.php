<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banks</title>
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style.min.css') }}">
</head>
<body>

@include('tenant.nav')

<div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="row page-titles">
        <div class="col-md-8">
            <h4 class="text-themecolor">
                <i class="fa fa-bank me-2"></i>
                Banks
            </h4>
            <p class="text-muted mb-0">Manage your bank accounts</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('tenant.banks.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i>
                Add Bank
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa fa-check-circle me-1"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Banks Table --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">All Banks</h4>
            <div class="table-responsive">
                <table class="table color-table primary-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bank Name</th>
                            <th>Opening Balance</th>
                            <th>Current Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banks as $bank)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $bank->bank_name }}</strong></td>
                                <td>Rs. {{ number_format($bank->opening_balance, 2) }}</td>
                                <td>Rs. {{ number_format($bank->current_balance, 2) }}</td>
                                <td>
                                    <a href="{{ route('tenant.banks.show', $bank) }}" class="btn btn-primary waves-effect waves-light me-1" title="View">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('tenant.banks.edit', $bank) }}" class="btn btn-warning waves-effect waves-light text-white me-1" title="Edit">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('tenant.banks.destroy', $bank) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger waves-effect waves-light" title="Delete" onclick="return confirm('Are you sure you want to delete this bank?')">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No banks found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@include('tenant.footer')

</body>
</html>