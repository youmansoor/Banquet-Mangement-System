<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @include('tenant.nav');
    <div class="row m-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Add New Lawn Type</h4>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('lawn_types.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            {{-- Lawn Type Input --}}
                            <div class="col-md-12 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="lawn_type" name="lawn_type" value="{{ old('lawn_type') }}" placeholder="Lawn Type Name" required>
                                    <label for="lawn_type">Lawn Type Name</label>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('lawn_types.index') }}" class="btn btn-secondary me-2">Back</a>
                                    <button type="submit" class="btn btn-primary text-white ms-auto">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('tenant.footer');
</body>
</html>