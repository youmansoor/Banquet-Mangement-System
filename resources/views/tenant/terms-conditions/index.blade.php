<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions</title>
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style.min.css') }}">
</head>
<body>

@include('tenant.nav')

<div class="container-fluid mt-4">

    {{-- Header --}}
    <div class="row page-titles">
        <div class="col-md-8">
            <h4 class="text-themecolor">
                <i class="fas fa-file-contract me-2"></i>
                Terms & Conditions
            </h4>
            <p class="text-muted mb-0">Manage your banquet terms and conditions</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('tenant.terms-conditions.create') }}" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i>
                Add Term
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

    {{-- Terms Table --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">All Terms & Conditions</h4>
            <div class="table-responsive">
                <table class="table color-table primary-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Heading</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($terms as $term)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $term->heading }}</strong></td>
                                <td>{{ \Illuminate\Support\Str::limit($term->description, 100) }}</td>
                                <td>
                                    <a href="{{ route('tenant.terms-conditions.show', $term) }}" class="btn btn-primary waves-effect waves-light me-1" title="View">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('tenant.terms-conditions.edit', $term) }}" class="btn btn-warning waves-effect waves-light text-white me-1" title="Edit">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('tenant.terms-conditions.destroy', $term) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger waves-effect waves-light" title="Delete" onclick="return confirm('Are you sure you want to delete this term?')">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">No terms & conditions found.</td>
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