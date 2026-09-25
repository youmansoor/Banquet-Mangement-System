<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Subscription Plans</title>

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
                Subscription Plans
            </h2>

            <p class="text-muted mb-0">
                Manage SaaS subscription plans
            </p>

        </div>

        <a
            href="{{ route('admin.subscription-plans.create') }}"
            class="btn btn-primary"
        >
            + Add Subscription Plan
        </a>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- Error --}}
    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- Search --}}
    <form method="GET" action="{{ route('admin.subscription-plans.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" 
                   name="search" 
                   class="form-control" 
                   placeholder="Search subscription plans..." 
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i> Search
            </button>
        </div>
    </form>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>

                            <th>Plan</th>

                            <th>Monthly</th>

                            <th>Yearly</th>

                            <th>Trial</th>

                            <th>Users</th>

                            <th>Venues</th>

                            <th>Bookings</th>

                            <th>Status</th>

                            <th width="220">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($subscriptionPlans as $plan)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td>

                                <strong>
                                    {{ $plan->name }}
                                </strong>

                                <br>

                                <small class="text-muted">
                                    {{ $plan->slug }}
                                </small>

                            </td>


                            <td>
                                Rs.
                                {{ number_format($plan->monthly_price, 2) }}
                            </td>


                            <td>
                                Rs.
                                {{ number_format($plan->yearly_price, 2) }}
                            </td>


                            <td>
                                {{ $plan->trial_days }} days
                            </td>


                            <td>
                                {{ $plan->max_users ?? 'Unlimited' }}
                            </td>


                            <td>
                                {{ $plan->max_venues ?? 'Unlimited' }}
                            </td>


                            <td>
                                {{ $plan->max_bookings ?? 'Unlimited' }}
                            </td>


                            <td>

                                @if($plan->status)

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            <td>

                                <div class="d-flex gap-1">

                                    <a
                                        href="{{ route('admin.subscription-plans.show', $plan->id) }}"
                                        class="btn btn-sm btn-info"
                                    >
                                        View
                                    </a>


                                    <a
                                        href="{{ route('admin.subscription-plans.edit', $plan->id) }}"
                                        class="btn btn-sm btn-warning"
                                    >
                                        Edit
                                    </a>


                                    <form
                                        action="{{ route('admin.subscription-plans.destroy', $plan->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this plan?')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="10"
                                class="text-center py-5"
                            >

                                <h5 class="text-muted">
                                    No subscription plans found
                                </h5>

                                <p class="text-muted mb-0">
                                    Create your first subscription plan.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@include('admin.footer')

</body>

</html>