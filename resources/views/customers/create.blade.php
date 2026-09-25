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
                    <h4 class="card-title mb-4">Add New Customer</h4>

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

                    <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            {{-- Customer Name --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Customer Name" required>
                                    <label for="name">Customer Name</label>
                                </div>
                            </div>

                            {{-- Customer Email Address --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Customer Email" required>
                                    <label for="email">Customer Email Address</label>
                                </div>
                            </div>

                            {{-- Contact Number 1 --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="phone_1" name="phone_1" value="{{ old('phone_1') }}" placeholder="Contact Number 1" required>
                                    <label for="phone_1">Customer Contact Number 1</label>
                                </div>
                            </div>

                            {{-- Contact Number 2 --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="phone_2" name="phone_2" value="{{ old('phone_2') }}" placeholder="Contact Number 2">
                                    <label for="phone_2">Customer Contact Number 2 (Optional)</label>
                                </div>
                            </div>

                            {{-- NIC Number --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="nic_number" name="nic_number" value="{{ old('nic_number') }}" placeholder="NIC Number" required>
                                    <label for="nic_number">Customer NIC Number</label>
                                </div>
                            </div>

                            {{-- Home Address --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" placeholder="Home Address" required>
                                    <label for="address">Customer Home Address</label>
                                </div>
                            </div>

                            {{-- NIC Image Front --}}
                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">CNIC (front)</span>
                                    <input type="file" class="form-control" id="cnic_front" name="cnic_front" accept="image/*" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">CNIC (back)</span>
                                    <input type="file" class="form-control" id="cnic_back" name="cnic_back" accept="image/*" required>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('customers.index') }}" class="btn btn-secondary me-2">Back</a>
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