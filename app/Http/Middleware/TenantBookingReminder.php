<?php

namespace App\Http\Middleware;

use App\Models\Booking;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantBookingReminder
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | ONLY LOGGED-IN TENANTS
        |--------------------------------------------------------------------------
        */

        if (
            ! auth()->check() ||
            ! auth()->user()->tenant_id
        ) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | TENANT
        |--------------------------------------------------------------------------
        */

        $tenantId = auth()->user()->tenant_id;

        /*
        |--------------------------------------------------------------------------
        | TOMORROW
        |--------------------------------------------------------------------------
        */

        $tomorrow = now()
            ->addDay()
            ->toDateString();

        /*
        |--------------------------------------------------------------------------
        | DATE-BASED SHOWN KEY
        |--------------------------------------------------------------------------
        */

        $shownKey =
            'tenant_booking_reminder_shown_' .
            $tenantId .
            '_' .
            $tomorrow;

        /*
        |--------------------------------------------------------------------------
        | REMOVE OLD CURRENT-REQUEST REMINDER
        |--------------------------------------------------------------------------
        |
        | This prevents stale reminder data from another request.
        |
        */

        session()->forget(
            'tenant_booking_reminders'
        );

        /*
        |--------------------------------------------------------------------------
        | ONLY CHECK ON DASHBOARD
        |--------------------------------------------------------------------------
        |
        | Popup dashboard par hi show karna hai.
        |
        */

        if (
            $request->routeIs(
                'tenant.dashboard'
            )
        ) {

            /*
            |--------------------------------------------------------------------------
            | ALREADY SHOWN TODAY FOR TOMORROW DATE
            |--------------------------------------------------------------------------
            */

            if (
                ! session()->has($shownKey)
            ) {

                /*
                |--------------------------------------------------------------------------
                | TOMORROW'S BOOKINGS
                |--------------------------------------------------------------------------
                */

                $bookings = Booking::with([
                    'customer',
                    'lawnType',
                ])
                    ->where(
                        'tenant_id',
                        $tenantId
                    )
                    ->whereDate(
                        'booking_date',
                        $tomorrow
                    )
                    ->where(
                        'status',
                        '!=',
                        'cancelled'
                    )
                    ->orderBy(
                        'booking_time'
                    )
                    ->orderBy(
                        'id'
                    )
                    ->get();

                /*
                |--------------------------------------------------------------------------
                | BUILD REMINDER DATA
                |--------------------------------------------------------------------------
                */

                $reminders = $bookings
                    ->map(
                        function (Booking $booking) {

                            return [
                                'id' =>
                                    $booking->id,

                                'event_type' =>
                                    $booking->event_type
                                    ?? 'Event',

                                'booking_date' =>
                                    $booking->booking_date
                                    ? $booking->booking_date
                                        ->format('Y-m-d')
                                    : null,

                                'booking_time' =>
                                    $booking->booking_time
                                    ?? '-',

                                'guests' =>
                                    $booking->number_of_guests
                                    ?? 0,

                                'customer_name' =>
                                    $booking->customer?->name
                                    ?? 'N/A',

                                'lawn_type' =>
                                    $booking->lawnType?->lawn_type
                                    ?? $booking->lawn_type
                                    ?? 'N/A',
                            ];
                        }
                    )
                    ->values()
                    ->toArray();

                /*
                |--------------------------------------------------------------------------
                | ONLY SET SESSION WHEN BOOKINGS EXIST
                |--------------------------------------------------------------------------
                */

                if (
                    ! empty($reminders)
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | CURRENT REQUEST SESSION
                    |--------------------------------------------------------------------------
                    */

                    session()->now(
                        'tenant_booking_reminders',
                        $reminders
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | MARK THIS TOMORROW DATE AS SHOWN
                    |--------------------------------------------------------------------------
                    */

                    session()->put(
                        $shownKey,
                        true
                    );
                }
            }
        }

        return $next($request);
    }
}