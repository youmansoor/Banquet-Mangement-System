<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Subscription Plan</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

@include('admin.nav')

<div class="container-fluid mt-4">

    <div class="row">

        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title mb-4">
                        Edit Subscription Plan
                    </h4>


                    {{-- Errors --}}
                    @if ($errors->any())

                        <div class="alert alert-danger">

                            <strong>
                                Please fix the following errors:
                            </strong>

                            <ul class="mb-0 mt-2">

                                @foreach ($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form
                        action="{{ route('admin.subscription-plans.update', $subscriptionPlan->id) }}"
                        method="POST"
                    >

                        @csrf

                        @method('PUT')


                        <div class="row">


                            {{-- Plan Name --}}
                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $subscriptionPlan->name) }}"
                                        placeholder="Plan Name"
                                        required
                                    >

                                    <label for="name">
                                        Plan Name
                                    </label>

                                </div>

                            </div>


                            {{-- Slug --}}
                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="slug"
                                        name="slug"
                                        value="{{ old('slug', $subscriptionPlan->slug) }}"
                                        placeholder="Slug"
                                        required
                                    >

                                    <label for="slug">
                                        Slug
                                    </label>

                                </div>

                            </div>


                            {{-- Monthly Price --}}
                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="number"
                                        class="form-control"
                                        id="monthly_price"
                                        name="monthly_price"
                                        value="{{ old('monthly_price', $subscriptionPlan->monthly_price) }}"
                                        placeholder="Monthly Price"
                                        min="0"
                                        step="0.01"
                                        required
                                    >

                                    <label for="monthly_price">
                                        Monthly Price
                                    </label>

                                </div>

                            </div>


                            {{-- Yearly Price --}}
                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="number"
                                        class="form-control"
                                        id="yearly_price"
                                        name="yearly_price"
                                        value="{{ old('yearly_price', $subscriptionPlan->yearly_price) }}"
                                        placeholder="Yearly Price"
                                        min="0"
                                        step="0.01"
                                        required
                                    >

                                    <label for="yearly_price">
                                        Yearly Price
                                    </label>

                                </div>

                            </div>


                            {{-- Trial Days --}}
                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="number"
                                        class="form-control"
                                        id="trial_days"
                                        name="trial_days"
                                        value="{{ old('trial_days', $subscriptionPlan->trial_days) }}"
                                        placeholder="Trial Days"
                                        min="0"
                                        required
                                    >

                                    <label for="trial_days">
                                        Trial Days
                                    </label>

                                </div>

                            </div>


                            {{-- Max Users --}}
                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="number"
                                        class="form-control"
                                        id="max_users"
                                        name="max_users"
                                        value="{{ old('max_users', $subscriptionPlan->max_users) }}"
                                        placeholder="Maximum Users"
                                        min="1"
                                    >

                                    <label for="max_users">
                                        Maximum Users
                                    </label>

                                </div>

                                <small class="text-muted">
                                    Leave empty for unlimited.
                                </small>

                            </div>


                            {{-- Max Venues --}}
                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="number"
                                        class="form-control"
                                        id="max_venues"
                                        name="max_venues"
                                        value="{{ old('max_venues', $subscriptionPlan->max_venues) }}"
                                        placeholder="Maximum Venues"
                                        min="1"
                                    >

                                    <label for="max_venues">
                                        Maximum Venues
                                    </label>

                                </div>

                                <small class="text-muted">
                                    Leave empty for unlimited.
                                </small>

                            </div>


                            {{-- Max Bookings --}}
                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <input
                                        type="number"
                                        class="form-control"
                                        id="max_bookings"
                                        name="max_bookings"
                                        value="{{ old('max_bookings', $subscriptionPlan->max_bookings) }}"
                                        placeholder="Maximum Bookings"
                                        min="1"
                                    >

                                    <label for="max_bookings">
                                        Maximum Bookings
                                    </label>

                                </div>

                                <small class="text-muted">
                                    Leave empty for unlimited.
                                </small>

                            </div>


                            {{-- Features --}}
                            <div class="col-12 mb-3">

                                <label
                                    for="features"
                                    class="form-label fw-semibold"
                                >
                                    Features
                                </label>

                                @php

                                    $features = $subscriptionPlan->features;

                                    if (is_array($features)) {
                                        $featuresText = implode("\n", $features);
                                    } else {
                                        $featuresText = $features ?? '';
                                    }

                                @endphp

                                <textarea
                                    class="form-control"
                                    id="features"
                                    name="features"
                                    rows="5"
                                    placeholder="Enter one feature per line"
                                >{{ old('features', $featuresText) }}</textarea>

                                <small class="text-muted">
                                    Enter one feature per line.
                                </small>

                            </div>


                            {{-- Status --}}
                            <div class="col-md-6 mb-3">

                                <div class="form-floating">

                                    <select
                                        class="form-select"
                                        id="status"
                                        name="status"
                                        required
                                    >

                                        <option
                                            value="1"
                                            {{ old('status', $subscriptionPlan->status) == 1 ? 'selected' : '' }}
                                        >
                                            Active
                                        </option>

                                        <option
                                            value="0"
                                            {{ old('status', $subscriptionPlan->status) == 0 ? 'selected' : '' }}
                                        >
                                            Inactive
                                        </option>

                                    </select>

                                    <label for="status">
                                        Status
                                    </label>

                                </div>

                            </div>


                            {{-- Buttons --}}
                            <div class="col-12 mt-3">

                                <div class="d-flex align-items-center">

                                    <a
                                        href="{{ route('admin.subscription-plans.index') }}"
                                        class="btn btn-secondary me-2"
                                    >
                                        Back
                                    </a>

                                    <button
                                        type="submit"
                                        class="btn btn-primary text-white ms-auto"
                                    >
                                        Update Subscription Plan
                                    </button>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@include('admin.footer')

</body>

</html>