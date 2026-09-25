<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link
    href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css"
    rel="stylesheet"
>
</head>
<body>
    @include('tenant.nav')
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
                'icon' => 'icon-people',
                'title' => 'TOTAL BOOKINGS',
                'value' => $totalBookings,
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
<div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">Calendar</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-end">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active">Calendar</li>
                            </ol>
                            <button type="button" class="btn btn-info d-none d-lg-block m-l-15 text-white"><i class="fa fa-plus-circle"></i> Create New</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card-body b-l calender-sidebar">
                                            <div id="calendar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN MODAL -->
                <div class="modal none-border" id="my-event">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"><strong>Add Event</strong></h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-success save-event waves-effect waves-light">Create event</button>
                                <button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-bs-dismiss="modal">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" data-skin="skin-default" class="default-theme">1</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-red" class="red-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-blue" class="blue-theme">4</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-megna" class="megna-theme working">6</a></li>
                                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                <li><a href="javascript:void(0)" data-skin="skin-default-dark" class="default-dark-theme ">7</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-red-dark" class="red-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-megna-dark" class="megna-dark-theme ">12</a></li>
                            </ul>
                            <ul class="m-t-20 chatonline">
                                <li><b>Chat option</b></li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/7.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../assets/images/users/8.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
</div>
{{-- =============================================================
     BOOKING DETAILS MODAL
============================================================= --}}

<div class="modal fade"
     id="bookingDetailsModal"
     tabindex="-1"
     aria-labelledby="bookingDetailsModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content">


            {{-- =================================================
                 MODAL HEADER
            ================================================== --}}

            <div class="modal-header bg-info text-white">

                <h4 class="modal-title"
                    id="bookingDetailsModalLabel">

                    <i class="ti-calendar me-1"></i>

                    Booking Details

                </h4>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>

            </div>


            {{-- =================================================
                 MODAL BODY
            ================================================== --}}

            <div class="modal-body">

                <div class="card mb-0">

                    <div class="card-body">

                        <h4 class="card-title">
                            Booking Details
                        </h4>

                        <h6 class="card-subtitle mb-4">
                            Complete customer, booking and payment information
                        </h6>


                        {{-- =====================================
                             TABS
                        ====================================== --}}

                        <ul class="nav nav-tabs"
                            role="tablist">

                            <li class="nav-item">

                                <a class="nav-link active"
                                   data-bs-toggle="tab"
                                   href="#customerInformationTab"
                                   role="tab">

                                    <i class="ti-user me-1"></i>

                                    Customer Information

                                </a>

                            </li>


                            <li class="nav-item">

                                <a class="nav-link"
                                   data-bs-toggle="tab"
                                   href="#bookingInformationTab"
                                   role="tab">

                                    <i class="ti-calendar me-1"></i>

                                    Booking Information

                                </a>

                            </li>


                            <li class="nav-item">

                                <a class="nav-link"
                                   data-bs-toggle="tab"
                                   href="#paymentInformationTab"
                                   role="tab">

                                    <i class="ti-money me-1"></i>

                                    Payment Information

                                </a>

                            </li>

                        </ul>


                        {{-- =====================================
                             TAB CONTENT
                        ====================================== --}}

                        <div class="tab-content tabcontent-border">


                            {{-- =================================
                                 CUSTOMER INFORMATION
                            ================================== --}}

                            <div class="tab-pane active"
                                 id="customerInformationTab"
                                 role="tabpanel">

                                <div class="p-20">

                                    <h3 class="mb-2">
                                        Customer Information
                                    </h3>

                                    <h4 class="card-subtitle mb-4">
                                        Customer Details
                                    </h4>


                                    <div class="table-responsive mt-3">

                                        <table class="table color-table primary-table">

                                            <thead>

                                                <tr>

                                                    <th>
                                                        Particular
                                                    </th>

                                                    <th class="text-end">
                                                        Details
                                                    </th>

                                                </tr>

                                            </thead>


                                            <tbody>

                                                <tr>

                                                    <td>
                                                        Customer Name
                                                    </td>

                                                    <td class="text-end"
                                                        id="customer_name_value">
                                                        -
                                                    </td>

                                                </tr>


                                                <tr>

                                                    <td>
                                                        Customer Number
                                                    </td>

                                                    <td class="text-end"
                                                        id="customer_number_value">
                                                        -
                                                    </td>

                                                </tr>


                                                <tr>

                                                    <td>
                                                        Customer NIC
                                                    </td>

                                                    <td class="text-end"
                                                        id="customer_nic_value">
                                                        -
                                                    </td>

                                                </tr>


                                                <tr>

                                                    <td>
                                                        Customer Address
                                                    </td>

                                                    <td class="text-end"
                                                        id="customer_address_value">
                                                        -
                                                    </td>

                                                </tr>

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================
                                 BOOKING INFORMATION
                            ================================== --}}

                            <div class="tab-pane"
                                 id="bookingInformationTab"
                                 role="tabpanel">

                                <div class="p-20">

                                    <h3 class="mb-2">
                                        Booking Information
                                    </h3>

                                    <h4 class="card-subtitle mb-4">
                                        Event & Booking Details
                                    </h4>


                                    <div class="table-responsive mt-3">

                                        <table class="table color-table primary-table">

                                            <thead>

                                                <tr>

                                                    <th>
                                                        Particular
                                                    </th>

                                                    <th class="text-end">
                                                        Details
                                                    </th>

                                                </tr>

                                            </thead>


                                            <tbody>

                                                <tr>

                                                    <td>
                                                        Event Type
                                                    </td>

                                                    <td class="text-end"
                                                        id="event_type_value">
                                                        -
                                                    </td>

                                                </tr>


                                                <tr>

                                                    <td>
                                                        Booking Date
                                                    </td>

                                                    <td class="text-end"
                                                        id="booking_date_value">
                                                        -
                                                    </td>

                                                </tr>


                                                <tr>

                                                    <td>
                                                        Booking Time
                                                    </td>

                                                    <td class="text-end"
                                                        id="booking_time_value">
                                                        -
                                                    </td>

                                                </tr>


                                                <tr>

                                                    <td>
                                                        Number of Guests
                                                    </td>

                                                    <td class="text-end"
                                                        id="guests_value">
                                                        -
                                                    </td>

                                                </tr>


                                                <tr>

                                                    <td>
                                                        Booking Status
                                                    </td>

                                                    <td class="text-end"
                                                        id="status_value">
                                                        -
                                                    </td>

                                                </tr>

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================
                                 PAYMENT INFORMATION
                            ================================== --}}

                            <div class="tab-pane"
                                 id="paymentInformationTab"
                                 role="tabpanel">

                                <div class="p-20">

                                    <h3 class="mb-2">
                                        Payment Information
                                    </h3>

                                    <h4 class="card-subtitle mb-4">
                                        Complete Payment Details
                                    </h4>


                                    <div class="table-responsive mt-3">

                                        <table class="table color-table primary-table">

                                            <thead>

                                                <tr>

                                                    <th>
                                                        Particular
                                                    </th>

                                                    <th class="text-end">
                                                        Amount
                                                    </th>

                                                </tr>

                                            </thead>


                                            <tbody>


                                                {{-- BOOKING AMOUNT --}}

                                                <tr>

                                                    <td>
                                                        Banquet Booking Amount
                                                    </td>

                                                    <td class="text-end">
                                                        PKR
                                                        <span id="booking_amount_value">
                                                            0.00
                                                        </span>
                                                    </td>

                                                </tr>


                                                {{-- SERVICES AMOUNT --}}

                                                <tr>

                                                    <td>
                                                        Services Amount
                                                    </td>

                                                    <td class="text-end">
                                                        PKR
                                                        <span id="services_amount_value">
                                                            0.00
                                                        </span>
                                                    </td>

                                                </tr>


                                                {{-- TOTAL AMOUNT --}}

                                                <tr>

                                                    <td>
                                                        Total Amount
                                                    </td>

                                                    <td class="text-end">
                                                        PKR
                                                        <span id="total_amount_value">
                                                            0.00
                                                        </span>
                                                    </td>

                                                </tr>


                                                {{-- TAX --}}

                                                <tr>

                                                    <td>
                                                        Tax Amount
                                                    </td>

                                                    <td class="text-end text-warning">
                                                        PKR
                                                        <span id="tax_amount_value">
                                                            0.00
                                                        </span>
                                                    </td>

                                                </tr>


                                                {{-- GRAND TOTAL --}}

                                                <tr>

                                                    <td>
                                                        <strong>
                                                            Grand Total
                                                        </strong>
                                                    </td>

                                                    <td class="text-end">

                                                        <strong class="text-primary">

                                                            PKR
                                                            <span id="grand_total_value">
                                                                0.00
                                                            </span>

                                                        </strong>

                                                    </td>

                                                </tr>


                                                {{-- ADVANCE --}}

                                                <tr>

                                                    <td>
                                                        Advance Paid
                                                    </td>

                                                    <td class="text-end text-success">

                                                        PKR

                                                        <span id="advance_amount_value">
                                                            0.00
                                                        </span>

                                                    </td>

                                                </tr>


                                                {{-- REMAINING --}}

                                                <tr>

                                                    <td>
                                                        Remaining Amount
                                                    </td>

                                                    <td class="text-end text-danger">

                                                        PKR

                                                        <span id="remaining_amount_value">
                                                            0.00
                                                        </span>

                                                    </td>

                                                </tr>


                                                {{-- PAYMENT STATUS --}}

                                                <tr>

                                                    <td>
                                                        Payment Status
                                                    </td>

                                                    <td class="text-end"
                                                        id="payment_status_value">

                                                        -

                                                    </td>

                                                </tr>


                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 MODAL FOOTER
            ================================================== --}}

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    <i class="ti-close me-1"></i>

                    Close

                </button>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
     FULLCALENDAR
============================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {


    // =========================================================
    // CALENDAR ELEMENT
    // =========================================================

    const calendarEl =
        document.getElementById('calendar');


    if (!calendarEl) {
        return;
    }


    // =========================================================
    // HELPER FUNCTIONS
    // =========================================================

    function setValue(id, value, fallback = '-') {

        const element =
            document.getElementById(id);

        if (!element) {
            return;
        }

        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {

            element.textContent =
                fallback;

        } else {

            element.textContent =
                value;

        }

    }


    function formatNumber(value) {

        const number =
            parseFloat(value) || 0;

        return number.toLocaleString(
            'en-PK',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );

    }


    function formatDate(date) {

        if (!date) {
            return '-';
        }

        const d =
            new Date(date);

        if (isNaN(d.getTime())) {
            return date;
        }

        return d.toLocaleDateString(
            'en-GB',
            {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }
        );

    }


    // =========================================================
    // FULLCALENDAR
    // =========================================================

    const calendar =
        new FullCalendar.Calendar(
            calendarEl,
            {

                initialView: 'dayGridMonth',

                height: 'auto',

                events: "{{ route('bookings.calendar.events') }}",

                eventDisplay: 'block',

                eventColor: '#03a9f4',

                eventTextColor: '#ffffff',


                // =================================================
                // EVENT CLICK
                // =================================================

                eventClick: function(info) {


                    const event =
                        info.event;


                    const data =
                        event.extendedProps || {};


                    // =================================================
                    // CUSTOMER INFORMATION
                    // =================================================

                    setValue(
                        'customer_name_value',
                        data.customer_name
                    );


                    setValue(
                        'customer_number_value',
                        data.customer_number ||
                        data.phone_1 ||
                        data.phone
                    );


                    setValue(
                        'customer_nic_value',
                        data.customer_nic_number ||
                        data.customer_nic ||
                        data.nic_number
                    );


                    setValue(
                        'customer_address_value',
                        data.customer_address ||
                        data.address
                    );


                    // =================================================
                    // BOOKING INFORMATION
                    // =================================================

                    setValue(
                        'event_type_value',
                        data.event_type ||
                        data.event
                    );


                    setValue(
                        'booking_date_value',
                        data.booking_date ||
                        formatDate(event.start)
                    );


                    setValue(
                        'booking_time_value',
                        data.booking_time
                    );


                    setValue(
                        'guests_value',
                        data.guests ||
                        data.number_of_guests
                    );


                    setValue(
                        'status_value',
                        data.status
                    );


                    // =================================================
                    // PAYMENT INFORMATION
                    // =================================================

                    setValue(
                        'booking_amount_value',
                        formatNumber(
                            data.booking_amount ||
                            data.booking_price ||
                            0
                        )
                    );


                    /*
                     * bookingServices agar API se array mein aa raha ho
                     * to uska total calculate hoga.
                     */

                    let servicesAmount = 0;


                    if (
                        Array.isArray(
                            data.bookingServices
                        )
                    ) {

                        servicesAmount =
                            data.bookingServices.reduce(
                                function(total, service) {

                                    const price =
                                        parseFloat(
                                            service.price
                                        ) || 0;

                                    const quantity =
                                        parseInt(
                                            service.quantity
                                        ) || 0;

                                    return total +
                                        (
                                            price *
                                            quantity
                                        );

                                },
                                0
                            );

                    }


                    /*
                     * Agar backend already services_amount bhej raha
                     * hai to usko priority di jayegi.
                     */

                    if (
                        data.services_amount !== undefined &&
                        data.services_amount !== null
                    ) {

                        servicesAmount =
                            parseFloat(
                                data.services_amount
                            ) || 0;

                    }


                    setValue(
                        'services_amount_value',
                        formatNumber(
                            servicesAmount
                        )
                    );


                    setValue(
                        'total_amount_value',
                        formatNumber(
                            data.total_amount || 0
                        )
                    );


                    setValue(
                        'tax_amount_value',
                        formatNumber(
                            data.tax_amount || 0
                        )
                    );


                    setValue(
                        'grand_total_value',
                        formatNumber(
                            data.grand_total || 0
                        )
                    );


                    setValue(
                        'advance_amount_value',
                        formatNumber(
                            data.advance_amount || 0
                        )
                    );


                    setValue(
                        'remaining_amount_value',
                        formatNumber(
                            data.remaining_amount || 0
                        )
                    );


                    setValue(
                        'payment_status_value',
                        data.payment_status ||
                        data.status
                    );


                    // =================================================
                    // OPEN MODAL
                    // =================================================

                    const modalElement =
                        document.getElementById(
                            'bookingDetailsModal'
                        );


                    if (
                        typeof bootstrap !== 'undefined' &&
                        bootstrap.Modal
                    ) {

                        const modal =
                            bootstrap.Modal.getOrCreateInstance(
                                modalElement
                            );

                        modal.show();

                    } else {

                        console.error(
                            'Bootstrap Modal is not loaded.'
                        );

                    }

                }

            }
        );


    // =========================================================
    // RENDER CALENDAR
    // =========================================================

    calendar.render();

});

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@include('tenant.booking-reminder-popup')
@include('tenant.footer');
</body>
</html>