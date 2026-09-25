<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    @include('admin.nav');
    <div class="row g-0">

    @if(auth()->user()->role === 'super_admin')

        {{-- ============================= --}}
        {{-- SUPER ADMIN DASHBOARD --}}
        {{-- ============================= --}}

        @php

            $stats = [

                [
                    'icon' => 'icon-home',
                    'title' => 'TOTAL TENANTS',
                    'value' => $totalTenants,
                    'color' => 'primary',
                    'bg' => 'primary'
                ],

                [
                    'icon' => 'icon-check',
                    'title' => 'ACTIVE TENANTS',
                    'value' => $activeTenants,
                    'color' => 'success',
                    'bg' => 'success'
                ],

                [
                    'icon' => 'icon-close',
                    'title' => 'INACTIVE TENANTS',
                    'value' => $inactiveTenants,
                    'color' => 'danger',
                    'bg' => 'danger'
                ],

                [
                    'icon' => 'icon-people',
                    'title' => 'TOTAL USERS',
                    'value' => $totalUsers,
                    'color' => 'cyan',
                    'bg' => 'cyan'
                ],

            ];

        @endphp


    @else

        {{-- ============================= --}}
        {{-- OWNER / TENANT DASHBOARD --}}
        {{-- ============================= --}}

        @php

            $stats = [

                [
                    'icon' => 'icon-people',
                    'title' => 'TOTAL CUSTOMERS',
                    'value' => $totalCustomers,
                    'color' => 'primary',
                    'bg' => 'primary'
                ],

                [
                    'icon' => 'icon-calendar',
                    'title' => 'TOTAL BOOKINGS',
                    'value' => 0,
                    'color' => 'cyan',
                    'bg' => 'cyan'
                ],

                [
                    'icon' => 'icon-doc',
                    'title' => 'TOTAL INVOICES',
                    'value' => 0,
                    'color' => 'purple',
                    'bg' => 'purple'
                ],

                [
                    'icon' => 'icon-wallet',
                    'title' => 'TOTAL PAYMENTS',
                    'value' => 0,
                    'color' => 'success',
                    'bg' => 'success'
                ],

            ];

        @endphp

    @endif


    @foreach ($stats as $stat)

        <div class="col-lg-3 col-md-6">

            <div class="card border">

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="d-flex no-block align-items-center">

                                <div>

                                    <h3>
                                        <i class="{{ $stat['icon'] }}"></i>
                                    </h3>

                                    <p class="text-muted">
                                        {{ $stat['title'] }}
                                    </p>

                                </div>

                                <div class="ms-auto">

                                    <h2 class="counter text-{{ $stat['color'] }}">
                                        {{ $stat['value'] }}
                                    </h2>

                                </div>

                            </div>

                        </div>


                        <div class="col-12">

                            <div class="progress">

                                <div
                                    class="progress-bar bg-{{ $stat['bg'] }}"
                                    role="progressbar"
                                    style="width: 85%; height: 6px;"
                                    aria-valuenow="85"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endforeach

</div>
@include('admin.footer');
</body>
</html>