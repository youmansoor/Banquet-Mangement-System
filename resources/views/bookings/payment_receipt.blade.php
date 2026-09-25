<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Payment Receipt {{ $receipt->receipt_number }}
    </title>


    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
            color: #222;
        }


        .receipt-container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 35px;
            border: 1px solid #ddd;
        }


        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }


        .header h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }


        .header p {
            margin: 3px 0;
            color: #666;
        }


        .receipt-title {
            text-align: center;
            margin-bottom: 25px;
        }


        .receipt-title h2 {
            margin-bottom: 5px;
        }


        .meta-table,
        .history-table,
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }


        .meta-table td {
            padding: 8px 0;
        }


        .meta-label {
            width: 180px;
            font-weight: bold;
        }


        .history-table th,
        .history-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }


        .history-table th {
            background: #f1f3f5;
            text-align: left;
        }


        .history-table .amount {
            text-align: right;
            font-weight: bold;
        }


        .current-payment {
            background: #eaf7ee;
        }


        .summary {
            margin-top: 25px;
            margin-left: auto;
            width: 400px;
        }


        .summary-table td {
            padding: 9px;
            border-bottom: 1px solid #eee;
        }


        .summary-table td:last-child {
            text-align: right;
            font-weight: bold;
        }


        .remaining {
            color: #dc3545;
        }


        .paid {
            color: #198754;
        }


        .footer {
            border-top: 1px solid #ddd;
            margin-top: 35px;
            padding-top: 15px;
            text-align: center;
            color: #777;
            font-size: 13px;
        }


        .actions {
            text-align: center;
            margin-bottom: 20px;
        }


        .btn {
            display: inline-block;
            padding: 10px 18px;
            margin: 0 5px;
            text-decoration: none;
            border-radius: 4px;
            color: #fff;
            border: 0;
            cursor: pointer;
        }


        .btn-primary {
            background: #007bff;
        }


        .btn-secondary {
            background: #6c757d;
        }


        @media print {

            body {
                background: #fff;
                padding: 0;
            }


            .receipt-container {
                border: 0;
                max-width: none;
            }


            .actions {
                display: none;
            }

        }

    </style>

</head>


<body>


<div class="actions">

    <a
        href="{{ route(
            'bookings.payments.history',
            $receipt->booking_id
        ) }}"
        class="btn btn-secondary"
    >

        Back to Payment History

    </a>


    <button
        type="button"
        onclick="window.print()"
        class="btn btn-primary"
    >

        Print Receipt

    </button>

</div>



<div class="receipt-container">


    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="header">

        <h1>

            {{ $settings?->business_name
                ?: 'Banquet Management System'
            }}

        </h1>


        @if($settings?->business_address)

            <p>
                {{ $settings->business_address }}
            </p>

        @endif


        @if($settings?->business_phone)

            <p>
                Phone:
                {{ $settings->business_phone }}
            </p>

        @endif


        @if($settings?->business_email)

            <p>
                Email:
                {{ $settings->business_email }}
            </p>

        @endif

    </div>



    {{-- ========================================================= --}}
    {{-- TITLE --}}
    {{-- ========================================================= --}}

    <div class="receipt-title">

        <h2>
            PAYMENT RECEIPT
        </h2>

        <p>
            Installment Payment Invoice
        </p>

    </div>



    {{-- ========================================================= --}}
    {{-- RECEIPT META --}}
    {{-- ========================================================= --}}

    <table class="meta-table">

        <tr>

            <td class="meta-label">
                Receipt No:
            </td>

            <td>
                {{ $receipt->receipt_number }}
            </td>

            <td class="meta-label">
                Receipt Date:
            </td>

            <td>
                {{ $receipt->receipt_date?->format('d-m-Y') }}
            </td>

        </tr>


        <tr>

            <td class="meta-label">
                Booking ID:
            </td>

            <td>
                {{ $receipt->booking_id }}
            </td>

            <td class="meta-label">
                Job Number:
            </td>

            <td>

                JOB-{{ str_pad(
                    $receipt->booking_id,
                    6,
                    '0',
                    STR_PAD_LEFT
                ) }}

            </td>

        </tr>

    </table>



    <hr>



    {{-- ========================================================= --}}
    {{-- CUSTOMER --}}
    {{-- ========================================================= --}}

    <h3>
        Customer Information
    </h3>


    <table class="meta-table">

        <tr>

            <td class="meta-label">
                Customer Name:
            </td>

            <td>
                {{ $receipt->customer?->name ?? 'N/A' }}
            </td>

        </tr>


        <tr>

            <td class="meta-label">
                Phone:
            </td>

            <td>
                {{ $receipt->customer?->phone_1 ?? 'N/A' }}
            </td>

        </tr>


        <tr>

            <td class="meta-label">
                CNIC:
            </td>

            <td>
                {{ $receipt->customer?->nic_number ?? 'N/A' }}
            </td>

        </tr>

    </table>



    {{-- ========================================================= --}}
    {{-- BOOKING --}}
    {{-- ========================================================= --}}

    <h3>
        Booking Information
    </h3>


    <table class="meta-table">

        <tr>

            <td class="meta-label">
                Event:
            </td>

            <td>
                {{ ucfirst(
                    str_replace(
                        '_',
                        ' ',
                        $receipt->booking?->event_type
                        ?? 'N/A'
                    )
                ) }}
            </td>

        </tr>


        <tr>

            <td class="meta-label">
                Lawn:
            </td>

            <td>
                {{ $receipt->booking?->lawnType?->lawn_type
                    ?? 'N/A'
                }}
            </td>

        </tr>


        <tr>

            <td class="meta-label">
                Booking Date:
            </td>

            <td>
                {{ $receipt->booking?->booking_date?->format(
                    'd-m-Y'
                ) }}
            </td>

        </tr>

    </table>



    {{-- ========================================================= --}}
    {{-- PAYMENT HISTORY --}}
    {{-- ========================================================= --}}

    <h3>
        Payment History
    </h3>


    <table class="history-table">

        <thead>

            <tr>

                <th>
                    #
                </th>

                <th>
                    Date
                </th>

                <th>
                    Payment Type
                </th>

                <th>
                    Method
                </th>

                <th>
                    Reference
                </th>

                <th style="text-align:right;">
                    Amount
                </th>

            </tr>

        </thead>


        <tbody>

            @foreach(
                ($receipt->payment_history ?? [])
                as $index => $history
            )

                @php

                    $isCurrent =
                        (int) ($history['payment_id'] ?? 0)
                        ===
                        (int) $receipt->booking_payment_id;

                @endphp


                <tr
                    class="{{ $isCurrent
                        ? 'current-payment'
                        : ''
                    }}"
                >

                    <td>
                        {{ $index + 1 }}
                    </td>


                    <td>
                        {{ $history['date'] ?? 'N/A' }}
                    </td>


                    <td>

                        {{ $history['installment_type']
                            ?? 'Advance'
                        }}

                    </td>


                    <td>

                        {{ ucfirst(
                            str_replace(
                                '_',
                                ' ',
                                $history['payment_method']
                                ?? 'N/A'
                            )
                        ) }}

                    </td>


                    <td>

                        {{ $history[
                            'transaction_reference'
                        ] ?? 'N/A' }}

                    </td>


                    <td class="amount">

                        {{ number_format(
                            (float) (
                                $history['amount']
                                ?? 0
                            ),
                            2
                        ) }}

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>



    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="summary">

        <table class="summary-table">

            <tr>

                <td>
                    Booking Total
                </td>

                <td>
                    PKR
                    {{ number_format(
                        (float)
                        $receipt->booking_total,
                        2
                    ) }}
                </td>

            </tr>


            <tr>

                <td>
                    Paid Before This Payment
                </td>

                <td>
                    PKR
                    {{ number_format(
                        (float)
                        $receipt->paid_before,
                        2
                    ) }}
                </td>

            </tr>


            <tr>

                <td>
                    Current Payment
                </td>

                <td class="paid">

                    PKR
                    {{ number_format(
                        (float)
                        $receipt->current_payment,
                        2
                    ) }}

                </td>

            </tr>


            <tr>

                <td>
                    Total Paid
                </td>

                <td class="paid">

                    PKR
                    {{ number_format(
                        (float)
                        $receipt->total_paid_after,
                        2
                    ) }}

                </td>

            </tr>


            <tr>

                <td>
                    Remaining Balance
                </td>

                <td class="remaining">

                    PKR
                    {{ number_format(
                        (float)
                        $receipt->remaining_after,
                        2
                    ) }}

                </td>

            </tr>

        </table>

    </div>



    {{-- ========================================================= --}}
    {{-- NOTES --}}
    {{-- ========================================================= --}}

    @if($receipt->notes)

        <div style="margin-top:30px;">

            <strong>
                Notes:
            </strong>

            <p>
                {{ $receipt->notes }}
            </p>

        </div>

    @endif



    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <div class="footer">

        © {{ date('Y') }}

        {{ $settings?->footer_text
            ?: $settings?->business_name
            ?: 'Banquet Management System'
        }}

    </div>


</div>


</body>

</html>