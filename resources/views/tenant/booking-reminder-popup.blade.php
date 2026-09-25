@if(session()->has('tenant_booking_reminders'))

    @php

        $reminders = session(
            'tenant_booking_reminders',
            []
        );

    @endphp


    @if(!empty($reminders))

        <script>

            document.addEventListener(
                'DOMContentLoaded',
                function () {

                    /*
                    |--------------------------------------------------------------------------
                    | CHECK SWEETALERT
                    |--------------------------------------------------------------------------
                    */

                    if (
                        typeof Swal === 'undefined'
                    ) {

                        console.error(
                            'SweetAlert2 is not loaded.'
                        );

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | REMINDER DATA
                    |--------------------------------------------------------------------------
                    */

                    const reminders =
                        @json($reminders);


                    /*
                    |--------------------------------------------------------------------------
                    | CREATE HTML
                    |--------------------------------------------------------------------------
                    */

                    let reminderHtml = '';


                    reminders.forEach(
                        function (booking) {

                            let bookingDate = '-';


                            /*
                            |--------------------------------------------------------------------------
                            | DATE FORMAT
                            |--------------------------------------------------------------------------
                            */

                            if (
                                booking.booking_date
                            ) {

                                const date =
                                    new Date(
                                        booking.booking_date
                                    );


                                if (
                                    !isNaN(
                                        date.getTime()
                                    )
                                ) {

                                    bookingDate =
                                        date.toLocaleDateString(
                                            'en-GB',
                                            {
                                                day: '2-digit',
                                                month: 'short',
                                                year: 'numeric'
                                            }
                                        );

                                }

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | BOOKING CARD
                            |--------------------------------------------------------------------------
                            */

                            reminderHtml += `

                                <div
                                    style="
                                        text-align:left;
                                        border:1px solid #e9ecef;
                                        border-radius:8px;
                                        padding:12px;
                                        margin-bottom:10px;
                                        background:#fff;
                                    "
                                >

                                    <div
                                        style="
                                            display:flex;
                                            justify-content:space-between;
                                            align-items:center;
                                            margin-bottom:8px;
                                        "
                                    >

                                        <strong
                                            style="
                                                font-size:16px;
                                                color:#343a40;
                                            "
                                        >

                                            ${booking.event_type ?? 'Event'}

                                        </strong>


                                        <span
                                            style="
                                                background:#fff3cd;
                                                color:#856404;
                                                padding:4px 9px;
                                                border-radius:15px;
                                                font-size:11px;
                                                font-weight:600;
                                            "
                                        >

                                            Tomorrow

                                        </span>

                                    </div>


                                    <div
                                        style="
                                            font-size:13px;
                                            color:#67757c;
                                            line-height:1.8;
                                        "
                                    >

                                        <div>

                                            <strong>
                                                Booking:
                                            </strong>

                                            #${booking.id ?? '-'}

                                        </div>


                                        <div>

                                            <strong>
                                                Customer:
                                            </strong>

                                            ${booking.customer_name ?? 'N/A'}

                                        </div>


                                        <div>

                                            <strong>
                                                Lawn:
                                            </strong>

                                            ${booking.lawn_type ?? 'N/A'}

                                        </div>


                                        <div>

                                            <strong>
                                                Date:
                                            </strong>

                                            ${bookingDate}

                                        </div>


                                        <div>

                                            <strong>
                                                Time:
                                            </strong>

                                            ${booking.booking_time ?? 'N/A'}

                                        </div>


                                        <div>

                                            <strong>
                                                Guests:
                                            </strong>

                                            ${booking.guests ?? 0}

                                        </div>

                                    </div>

                                </div>

                            `;

                        }
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | SWEETALERT
                    |--------------------------------------------------------------------------
                    */

                    Swal.fire({

                        icon: 'warning',

                        title:
                            'Upcoming Booking Reminder',

                        html: `

                            <div
                                style="
                                    font-size:14px;
                                    color:#67757c;
                                    margin-bottom:15px;
                                "
                            >

                                <strong>
                                    ${reminders.length}
                                </strong>

                                booking(s) are scheduled for tomorrow.

                            </div>


                            <div
                                style="
                                    max-height:350px;
                                    overflow-y:auto;
                                    padding-right:4px;
                                "
                            >

                                ${reminderHtml}

                            </div>

                        `,

                        width: '650px',

                        confirmButtonText:
                            '<i class="fa fa-calendar me-1"></i> View Bookings',

                        cancelButtonText:
                            'Close',

                        showCancelButton:
                            true,

                        confirmButtonColor:
                            '#03a9f4',

                        cancelButtonColor:
                            '#6c757d',

                        allowOutsideClick:
                            false,

                        allowEscapeKey:
                            false,

                        focusConfirm:
                            true,

                        customClass: {

                            popup:
                                'booking-reminder-popup',

                            title:
                                'booking-reminder-title',

                            htmlContainer:
                                'booking-reminder-content'

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | VIEW BOOKINGS
                        |--------------------------------------------------------------------------
                        */

                        preConfirm: function () {

                            window.location.href =
                                "{{ url('/bookings') }}";

                        }

                    });

                }
            );

        </script>


        <style>

            .booking-reminder-popup {

                border-radius: 12px !important;

                padding: 25px !important;

            }


            .booking-reminder-title {

                font-size: 22px !important;

                font-weight: 600 !important;

                color: #343a40 !important;

            }


            .booking-reminder-content {

                padding: 0 !important;

            }


            @media (max-width: 576px) {

                .booking-reminder-popup {

                    width: 94% !important;

                    padding: 18px !important;

                }


                .booking-reminder-title {

                    font-size: 19px !important;

                }

            }

        </style>

    @endif

@endif