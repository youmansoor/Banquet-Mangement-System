<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Payment History | {{ $tenant->business_name }}
    </title>

    <link rel="icon"
          type="image/png"
          href="{{ asset('assets/images/favicon.png') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/dist/css/style.min.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/dist/css/pages/dashboard1.css') }}">

</head>

<body>

@include('admin.nav')


<div class="container-fluid mt-4">

    {{-- HEADER --}}
    <div class="row page-titles">

        <div class="col-md-8">

            <h4 class="text-themecolor">

                <i class="ti-history me-2"></i>

                Payment History

            </h4>

            <p class="text-muted mb-0">

                {{ $tenant->business_name }}

                @if($tenant->owner_name)

                    — {{ $tenant->owner_name }}

                @endif

            </p>

        </div>


        <div class="col-md-4 text-end">

            <a
                href="{{ route('admin.tenant-payments.index') }}"
                class="btn btn-secondary"
            >

                <i class="fa fa-arrow-left me-1"></i>

                Back

            </a>

        </div>

    </div>


    {{-- SUMMARY --}}
    <div class="row">


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Due
                    </h6>

                    <h3>

                        {{ number_format(
                            (float) $totalDue,
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Paid
                    </h6>

                    <h3 class="text-success">

                        {{ number_format(
                            (float) $totalPaid,
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Remaining
                    </h6>

                    <h3 class="text-danger">

                        {{ number_format(
                            (float) $totalRemaining,
                            2
                        ) }}

                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- COMBINED HISTORY TABLE --}}
    <div class="row">

        <div class="col-lg-12">

            <div class="card">

                <div class="card-body">

                    <h4 class="card-title">
                        Subscription Payment History
                    </h4>

                    <h6 class="card-subtitle">
                        Monthly dues and tenant payments
                    </h6>


                    <div class="table-responsive">

                        <table class="table color-table primary-table">

                            <thead>

                                <tr>

                                    <th>#</th>

                                    <th>Month</th>

                                    <th>Due Date</th>

                                    <th>Subscription</th>

                                    <th>Due</th>

                                    <th>Payment Date</th>

                                    <th>Payment Type</th>

                                    <th>In / Out</th>

                                    <th>Paid</th>

                                    <th>Remaining</th>

                                    <th>Status</th>

                                    <th>Reference</th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($dues as $due)

                                    @php

                                        /*
                                        |--------------------------------------------------------------------------
                                        | Payments belonging to this billing period
                                        |--------------------------------------------------------------------------
                                        */

                                        $monthPayments = $payments->filter(function ($payment) use ($due) {

                                            if (!$payment->payment_date) {
                                                return false;
                                            }

                                            return $payment->payment_date->format('Y-m')
                                                === $due->billing_month->format('Y-m');

                                        });

                                    @endphp


                                    @if($monthPayments->count())

                                        @foreach($monthPayments as $payment)

                                            <tr>

                                                <td>
                                                    {{ $loop->parent->iteration }}
                                                </td>


                                                {{-- MONTH --}}
                                                <td>

                                                    <strong>

                                                        {{ $due->billing_month->format('F Y') }}

                                                    </strong>

                                                </td>


                                                {{-- DUE DATE --}}
                                                <td>

                                                    {{ $due->due_date->format('d-m-Y') }}

                                                </td>


                                                {{-- SUBSCRIPTION --}}
                                                <td>

                                                    {{ number_format(
                                                        (float) $subscription->amount,
                                                        2
                                                    ) }}

                                                </td>


                                                {{-- MONTHLY DUE --}}
                                                <td>

                                                    {{ number_format(
                                                        (float) $due->amount,
                                                        2
                                                    ) }}

                                                </td>


                                                {{-- PAYMENT DATE --}}
                                                <td>

                                                    {{ $payment->payment_date
                                                        ? $payment->payment_date->format('d-m-Y')
                                                        : 'N/A'
                                                    }}

                                                </td>


                                                {{-- PAYMENT TYPE --}}
                                                <td>

                                                    @if($payment->payment_type === 'cash')

                                                        <span class="label label-info">
                                                            Cash
                                                        </span>

                                                    @elseif($payment->payment_type === 'bank')

                                                        <span class="label label-primary">
                                                            Bank
                                                        </span>

                                                    @elseif($payment->payment_type === 'online')

                                                        <span class="label label-success">
                                                            Online
                                                        </span>

                                                    @elseif($payment->payment_type === 'card')

                                                        <span class="label label-warning">
                                                            Card
                                                        </span>

                                                    @else

                                                        {{ ucfirst(
                                                            $payment->payment_type ?? 'N/A'
                                                        ) }}

                                                    @endif

                                                </td>


                                                {{-- IN / OUT --}}
                                                <td>

                                                    @if($payment->payment_direction === 'in')

                                                        <span class="label label-success">
                                                            IN
                                                        </span>

                                                    @else

                                                        <span class="label label-danger">
                                                            OUT
                                                        </span>

                                                    @endif

                                                </td>


                                                {{-- PAID --}}
                                                <td>

                                                    <strong class="text-success">

                                                        {{ number_format(
                                                            (float) $payment->payment_amount,
                                                            2
                                                        ) }}

                                                    </strong>

                                                </td>


                                                {{-- REMAINING --}}
                                                <td>

                                                    <strong
                                                        class="{{ (float) $due->remaining_amount > 0
                                                            ? 'text-danger'
                                                            : 'text-success'
                                                        }}"
                                                    >

                                                        {{ number_format(
                                                            (float) $due->remaining_amount,
                                                            2
                                                        ) }}

                                                    </strong>

                                                </td>


                                                {{-- STATUS --}}
                                                <td>

                                                    @if($due->status === 'paid')

                                                        <span class="label label-success">
                                                            Paid
                                                        </span>

                                                    @elseif($due->status === 'partial')

                                                        <span class="label label-warning">
                                                            Partial
                                                        </span>

                                                    @else

                                                        <span class="label label-danger">
                                                            Unpaid
                                                        </span>

                                                    @endif

                                                </td>


                                                {{-- REFERENCE --}}
                                                <td>

                                                    {{ $payment->transaction_reference ?? '—' }}

                                                </td>

                                            </tr>

                                        @endforeach


                                    @else

                                        {{-- NO PAYMENT FOR THIS MONTH --}}
                                        <tr>

                                            <td>
                                                {{ $loop->iteration }}
                                            </td>

                                            <td>

                                                <strong>

                                                    {{ $due->billing_month->format('F Y') }}

                                                </strong>

                                            </td>

                                            <td>

                                                {{ $due->due_date->format('d-m-Y') }}

                                            </td>

                                            <td>

                                                {{ number_format(
                                                    (float) $subscription->amount,
                                                    2
                                                ) }}

                                            </td>

                                            <td>

                                                {{ number_format(
                                                    (float) $due->amount,
                                                    2
                                                ) }}

                                            </td>

                                            <td>
                                                —
                                            </td>

                                            <td>
                                                —
                                            </td>

                                            <td>
                                                —
                                            </td>

                                            <td>

                                                <strong class="text-success">
                                                    0.00
                                                </strong>

                                            </td>

                                            <td>

                                                <strong class="text-danger">

                                                    {{ number_format(
                                                        (float) $due->remaining_amount,
                                                        2
                                                    ) }}

                                                </strong>

                                            </td>

                                            <td>

                                                @if($due->status === 'partial')

                                                    <span class="label label-warning">
                                                        Partial
                                                    </span>

                                                @elseif($due->status === 'paid')

                                                    <span class="label label-success">
                                                        Paid
                                                    </span>

                                                @else

                                                    <span class="label label-danger">
                                                        Unpaid
                                                    </span>

                                                @endif

                                            </td>

                                            <td>
                                                —
                                            </td>

                                        </tr>

                                    @endif


                                @empty

                                    <tr>

                                        <td
                                            colspan="12"
                                            class="text-center"
                                        >

                                            <i class="fa fa-info-circle me-1"></i>

                                            No payment history found.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>


</div>


@include('admin.footer')

</body>

</html>