<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Quotations</title>

    <style>

        .quotation-table {
            min-width: 1450px;
        }

        .quotation-table th {
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
            vertical-align: middle;
        }

        .quotation-table td {
            vertical-align: middle;
            font-size: 13px;
        }

        .amount-cell {
            white-space: nowrap;
            font-weight: 600;
        }

        .quotation-amount {
            color: #6f42c1;
        }

        .customer-name {
            font-weight: 600;
            color: #343a40;
        }

        .customer-phone {
            font-size: 12px;
        }

        .filter-card {
            border: 0;
            border-radius: 8px;
        }

        .filter-card .form-label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .action-buttons {
            white-space: nowrap;
        }

        .action-buttons .btn {
            margin-right: 3px;
        }

        .empty-state {
            padding: 50px 20px;
        }

    </style>

</head>

<body>

@include('tenant.nav')

<div class="page-wrapper">

    <div class="container-fluid">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="row page-titles">

            <div class="col-md-6">

                <h4 class="text-themecolor">

                    <i class="ti-file me-2"></i>

                    Quotations

                </h4>

                <p class="text-muted mb-0">
                    Manage all customer quotations
                </p>

            </div>


            <div class="col-md-6 text-end">

                <a
                    href="{{ route('quotations.create') }}"
                    class="btn btn-primary text-white waves-effect waves-light"
                >

                    <i class="fa fa-plus me-1"></i>

                    Create Quotation

                </a>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >

                <i class="fa fa-check-circle me-1"></i>

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ERROR MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('error'))

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >

                <i class="fa fa-exclamation-circle me-1"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- VALIDATION ERRORS --}}
        {{-- ========================================================= --}}

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Please fix the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ========================================================= --}}
{{-- SEARCH / FILTER CARD --}}
{{-- ========================================================= --}}

<div class="card shadow-sm filter-card mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('quotations.index') }}"
        >

            <div class="row g-3">

                {{-- ================================================= --}}
                {{-- GENERAL SEARCH --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        value="{{ request('search') }}"
                        placeholder="Quotation #, customer, phone, event, venue..."
                    >

                </div>


                {{-- ================================================= --}}
                {{-- EVENT DATE --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Event Date
                    </label>

                    <input
                        type="date"
                        name="event_date"
                        class="form-control"
                        value="{{ request('event_date') }}"
                    >

                </div>


                {{-- ================================================= --}}
                {{-- VALID UNTIL --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Valid Until
                    </label>

                    <input
                        type="date"
                        name="valid_until"
                        class="form-control"
                        value="{{ request('valid_until') }}"
                    >

                </div>


                {{-- ================================================= --}}
                {{-- STATUS --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-control form-select"
                    >

                        <option value="">
                            All Status
                        </option>

                        @foreach([
                            'draft'    => 'Draft',
                            'sent'     => 'Sent',
                            'accepted' => 'Accepted',
                            'rejected' => 'Rejected',
                            'expired'  => 'Expired'
                        ] as $value => $label)

                            <option
                                value="{{ $value }}"
                                {{ request('status') === $value ? 'selected' : '' }}
                            >

                                {{ $label }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ================================================= --}}
                {{-- EVENT TYPE --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Event Type
                    </label>

                    <select
                        name="event_type"
                        class="form-control form-select"
                    >

                        <option value="">
                            All Events
                        </option>

                        @foreach($eventTypes ?? [] as $eventType)

                            <option
                                value="{{ $eventType }}"
                                {{ request('event_type') === $eventType ? 'selected' : '' }}
                            >

                                {{ ucfirst(
                                    str_replace('_', ' ', $eventType)
                                ) }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ================================================= --}}
                {{-- BUTTONS --}}
                {{-- ================================================= --}}

                <div class="col-md-6 d-flex justify-content-start">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="fa fa-search me-1"></i>

                        Search

                    </button>


                    <a
                        href="{{ route('quotations.index') }}"
                        class="btn btn-secondary"
                        title="Reset Filters"
                    >

                        <i class="fa fa-refresh me-1"></i>

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>



        {{-- ========================================================= --}}
        {{-- QUOTATIONS TABLE --}}
        {{-- ========================================================= --}}

        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h4 class="card-title mb-1">
                            All Quotations
                        </h4>

                        <h6 class="card-subtitle">
                            Manage all quotations
                        </h6>

                    </div>

                </div>


                <div class="table-responsive">

                    <table class="table color-table primary-table align-middle mb-0 quotation-table">

                        <thead>

                            <tr>

                                <th>#</th>
                                <th>Quotation #</th>
                                <th>Customer</th>
                                <th>Venue</th>
                                <th>Event</th>
                                <th>Date</th>
                                <th>Guests</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Valid Until</th>
                                <th width="300">Action</th>

                            </tr>

                        </thead>


                        <tbody>

                        @forelse($quotations as $quotation)

                            <tr>

                                {{-- # --}}
                                <td>
                                    {{ $quotations->firstItem() + $loop->index }}
                                </td>


                                {{-- QUOTATION NUMBER --}}
                                <td>

                                    <strong>

                                        QT-{{ str_pad(
                                            $quotation->id,
                                            5,
                                            '0',
                                            STR_PAD_LEFT
                                        ) }}

                                    </strong>

                                </td>


                                {{-- CUSTOMER --}}
                                <td>

                                    <span class="customer-name">

                                        {{ $quotation->customer->name ?? 'N/A' }}

                                    </span>

                                    @if(!empty($quotation->customer->phone_1))

                                        <br>

                                        <small class="text-muted customer-phone">

                                            {{ $quotation->customer->phone_1 }}

                                        </small>

                                    @endif

                                </td>


                                {{-- VENUE --}}
                                <td>

                                    {{ $quotation->lawnType->lawn_type ?? 'N/A' }}

                                </td>


                                {{-- EVENT --}}
                                <td>

                                    {{ ucfirst(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $quotation->event_type
                                        )
                                    ) }}

                                </td>


                                {{-- DATE --}}
                                <td>

                                    @if($quotation->event_date)

                                        <strong>

                                            {{ $quotation->event_date->format('d M Y') }}

                                        </strong>

                                    @else

                                        N/A

                                    @endif

                                </td>


                                {{-- GUESTS --}}
                                <td>

                                    {{ number_format(
                                        $quotation->number_of_guests
                                    ) }}

                                </td>


                                {{-- TOTAL AMOUNT --}}
                                <td class="amount-cell quotation-amount">

                                    Rs.
                                    {{ number_format(
                                        (float) ($quotation->totalamount ?? 0),
                                        2
                                    ) }}

                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @if($quotation->status === 'accepted')

                                        <span class="badge bg-success">

                                            <i class="fa fa-check me-1"></i>

                                            Accepted

                                        </span>

                                    @elseif($quotation->status === 'rejected')

                                        <span class="badge bg-danger">

                                            <i class="fa fa-times me-1"></i>

                                            Rejected

                                        </span>

                                    @elseif($quotation->status === 'sent')

                                        <span class="badge bg-info">

                                            <i class="fa fa-paper-plane me-1"></i>

                                            Sent

                                        </span>

                                    @elseif($quotation->status === 'expired')

                                        <span class="badge bg-secondary">

                                            <i class="fa fa-calendar me-1"></i>

                                            Expired

                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">

                                            <i class="fa fa-file me-1"></i>

                                            Draft

                                        </span>

                                    @endif

                                </td>


                                {{-- VALID UNTIL --}}
                                <td>

                                    @if($quotation->valid_until)

                                        {{ $quotation->valid_until->format('d M Y') }}

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- ACTIONS --}}
                                <td>

                                    <div class="action-buttons d-flex gap-1">


                                        {{-- VIEW --}}
                                        <a
                                            href="{{ route(
                                                'quotations.show',
                                                $quotation
                                            ) }}"
                                            class="btn btn-primary"
                                            title="View Quotation"
                                        >

                                            <span class="btn-label">

                                                <i class="fa fa-eye"></i>

                                            </span>

                                            View

                                        </a>


                                        {{-- EDIT --}}
                                        <a
                                            href="{{ route(
                                                'quotations.edit',
                                                $quotation
                                            ) }}"
                                            class="btn btn-warning text-white"
                                            title="Edit Quotation"
                                        >

                                            <span class="btn-label">

                                                <i class="fa fa-edit"></i>

                                            </span>

                                            Edit

                                        </a>


                                        {{-- CONVERT --}}
                                        <button
                                            type="button"
                                            class="btn btn-success"
                                            title="Convert to Booking"
                                            onclick="convertToBooking({{ $quotation->id }})"
                                        >

                                            <span class="btn-label">

                                                <i class="fa fa-exchange"></i>

                                            </span>

                                            Convert

                                        </button>


                                        {{-- DELETE --}}
                                        <form
                                            action="{{ route(
                                                'quotations.destroy',
                                                $quotation
                                            ) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Delete this quotation?')"
                                        >

                                            @csrf

                                            @method('DELETE')


                                            <button
                                                type="submit"
                                                class="btn btn-danger"
                                                title="Delete Quotation"
                                            >

                                                <span class="btn-label">

                                                    <i class="fa fa-trash"></i>

                                                </span>

                                                Delete

                                            </button>

                                        </form>


                                    </div>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="11"
                                    class="text-center empty-state"
                                >

                                    <i
                                        class="fa fa-file-text-o fa-3x text-muted mb-3"
                                    ></i>

                                    <h5 class="text-muted">

                                        No quotations found

                                    </h5>

                                    <p class="text-muted mb-0">

                                        There are no quotations available yet.

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}
                @if($quotations->hasPages())

                    <div class="mt-4">

                        {{ $quotations
                            ->appends(request()->query())
                            ->links('pagination::bootstrap-5')
                        }}

                    </div>

                @endif

            </div>

        </div>


    </div>

</div>


{{-- ========================================================= --}}
{{-- CONVERT TO BOOKING MODAL --}}
{{-- ========================================================= --}}

<div
    class="modal fade"
    id="convertModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">


            {{-- MODAL HEADER --}}
            <div class="modal-header bg-success text-white">

                <h5 class="modal-title">

                    <i class="fa fa-exchange me-1"></i>

                    Convert to Booking

                </h5>


                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            {{-- FORM --}}
            <form
                id="convertForm"
                method="POST"
            >

                @csrf


                <div class="modal-body">

                    <div class="alert alert-warning">

                        <i class="fa fa-exclamation-triangle me-1"></i>

                        Are you sure you want to convert this quotation
                        into a booking?

                    </div>


                    <p class="mb-0 text-muted">

                        All quotation details will automatically be
                        transferred to the booking.

                    </p>

                </div>


                {{-- FOOTER --}}
                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        <i class="fa fa-times me-1"></i>

                        Cancel

                    </button>


                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        <i class="fa fa-check me-1"></i>

                        Yes, Convert

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

function convertToBooking(quotationId)
{
    const form = document.getElementById('convertForm');

    form.action = `/quotations/${quotationId}/convert-to-booking`;

    const modalElement = document.getElementById('convertModal');

    const modal = new bootstrap.Modal(modalElement);

    modal.show();
}

</script>


@include('tenant.footer')

</body>

</html>
