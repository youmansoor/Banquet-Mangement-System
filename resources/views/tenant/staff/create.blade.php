<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff Member - Banquet Management</title>
</head>
<body>
    @include('tenant.nav')

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h4 class="mb-0 text-dark font-weight-bold">
                            <i class="ti-user text-primary me-2"></i> Add New Staff Member
                        </h4>
                        <a href="{{ route('tenant.staff.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ti-arrow-left"></i> Back to Staff List
                        </a>
                    </div>

                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('tenant.staff.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                {{-- Full Name --}}
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label font-weight-bold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Muhammad Ali" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label font-weight-bold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="e.g. ali@banquet.test" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Phone Number --}}
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label font-weight-bold">Phone Number</label>
                                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="e.g. 03001234567">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Role Selection --}}
                                <div class="col-md-6 mb-3">
                                    <label for="role_id" class="form-label font-weight-bold">Assign Role <span class="text-danger">*</span></label>
                                    <select name="role_id" id="role_id" class="form-select form-control @error('role_id') is-invalid @enderror" required>
                                        <option value="">-- Select Role --</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>
                                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                                @if(is_null($role->tenant_id)) (System Template) @else (Custom) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Password --}}
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label font-weight-bold">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimum 6 characters" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Status --}}
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label font-weight-bold">Account Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select form-control @error('status') is-invalid @enderror" required>
                                        <option value="1" @selected(old('status', '1') == '1')>Active (Can login)</option>
                                        <option value="0" @selected(old('status') == '0')>Inactive (Login blocked)</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="ti-save me-1"></i> Save Staff Member
                                </button>
                                <a href="{{ route('tenant.staff.index') }}" class="btn btn-secondary px-4">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('tenant.footer')
</body>
</html>
