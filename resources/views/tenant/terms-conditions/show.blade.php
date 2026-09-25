<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Term Details</title>
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
                Term Details
            </h4>
            <p class="text-muted mb-0">View term and condition information</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ url('/tenant/settings') }}#terms-pane" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i>
                Back
            </a>
        </div>
    </div>

    {{-- Term Info --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $term->heading }}</h4>
            <div class="table-responsive">
                <table class="table color-table primary-table">
                    <tbody>
                        <tr>
                            <th>Heading</th>
                            <td><strong>{{ $term->heading }}</strong></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ nl2br($term->description) }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $term->created_at->format('d M Y h:i A') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@include('tenant.footer')

</body>
</html>