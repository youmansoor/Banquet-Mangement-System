<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Term & Condition</title>
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
                Edit Term & Condition
            </h4>
            <p class="text-muted mb-0">Update term and condition information</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ url('/tenant/settings') }}#terms-pane" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i>
                Back
            </a>
        </div>
    </div>

    {{-- Validation Errors --}}
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

    {{-- Form --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit Term Details</h4>
            <form method="POST" action="{{ route('tenant.terms-conditions.update', $term) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Heading <span class="text-danger">*</span></label>
                        <input type="text" name="heading" class="form-control" value="{{ old('heading', $term->heading) }}" placeholder="e.g. Booking Cancellation Policy" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Enter terms and conditions..." required>{{ old('description', $term->description) }}</textarea>
                    </div>

                    <div class="col-12 text-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i>
                            Update Term
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

@include('tenant.footer')

</body>
</html>