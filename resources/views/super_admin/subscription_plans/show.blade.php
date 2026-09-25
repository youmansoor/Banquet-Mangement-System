<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Subscription Plan</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

@include('admin.nav')

<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                {{ $subscriptionPlan->name }}
            </h2>

            <p class="text-muted mb-0">
                Subscription Plan Details
            </p>

        </div>

        <div>

            <a
                href="{{ route('admin.subscription-plans.edit', $subscriptionPlan->id) }}"
                class="btn btn-warning"
            >
                Edit
            </a>

            <a
                href="{{ route('admin.subscription-plans.index') }}"
                class="btn btn-secondary"
            >
                Back
            </a>

        </div>

    </div>


    <div class="row">


        {{-- Basic Information --}}
        <div class="col-md-6 mb-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h5 class="fw-bold mb-4">
                        Plan Information
                    </h5>


                    <div class="mb-3">

                        <strong>
                            Name:
                        </strong>

                        {{ $subscriptionPlan->name }}

                    </div>


                    <div class="mb-3">

                        <strong>
                            Slug:
                        </strong>

                        {{ $subscriptionPlan->slug }}

                    </div>


                    <div class="mb-3">

                        <strong>
                            Monthly Price:
                        </strong>

                        Rs.
                        {{ number_format($subscriptionPlan->monthly_price, 2) }}

                    </div>


                    <div class="mb-3">

                        <strong>
                            Yearly Price:
                        </strong>

                        Rs.
                        {{ number_format($subscriptionPlan->yearly_price, 2) }}

                    </div>


                    <div class="mb-3">

                        <strong>
                            Trial:
                        </strong>

                        {{ $subscriptionPlan->trial_days }}
                        days

                    </div>


                    <div>

                        <strong>
                            Status:
                        </strong>

                        @if($subscriptionPlan->status)

                            <span class="badge bg-success">
                                Active
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Inactive
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Limits --}}
        <div class="col-md-6 mb-4">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h5 class="fw-bold mb-4">
                        Plan Limits
                    </h5>


                    <div class="mb-3">

                        <strong>
                            Maximum Users:
                        </strong>

                        {{ $subscriptionPlan->max_users ?? 'Unlimited' }}

                    </div>


                    <div class="mb-3">

                        <strong>
                            Maximum Venues:
                        </strong>

                        {{ $subscriptionPlan->max_venues ?? 'Unlimited' }}

                    </div>


                    <div class="mb-3">

                        <strong>
                            Maximum Bookings:
                        </strong>

                        {{ $subscriptionPlan->max_bookings ?? 'Unlimited' }}

                    </div>


                    <div>

                        <strong>
                            Current Subscriptions:
                        </strong>

                        {{ $subscriptionPlan->subscriptions_count }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Features --}}
        <div class="col-12">

            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Features
                    </h5>


                    @if(!empty($subscriptionPlan->features))

                        <ul class="list-group">

                            @foreach($subscriptionPlan->features as $feature)

                                <li class="list-group-item">
                                    ✓ {{ $feature }}
                                </li>

                            @endforeach

                        </ul>

                    @else

                        <p class="text-muted mb-0">
                            No features added.
                        </p>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@include('admin.footer')

</body>

</html>