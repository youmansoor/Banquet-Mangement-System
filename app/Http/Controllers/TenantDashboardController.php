<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\FreeService;
use App\Models\LawnType;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\PermissionRegistrar;

class TenantDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | CHECK TENANT USER
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $user->tenant_id !== null,
            403,
            'Tenant not found.'
        );

        /*
        |--------------------------------------------------------------------------
        | SET SPATIE TENANT CONTEXT
        |--------------------------------------------------------------------------
        */

        app(PermissionRegistrar::class)
            ->setPermissionsTeamId(
                $user->tenant_id
            );

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD PERMISSION
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $user->can('dashboard.view'),
            403,
            'You do not have permission to view the dashboard.'
        );

        $tenantId = $user->tenant_id;


        /*
        |--------------------------------------------------------------------------
        | TENANT DATA
        |--------------------------------------------------------------------------
        */

        $totalCustomers = Customer::where(
            'tenant_id',
            $tenantId
        )->count();


        $totalLawnTypes = LawnType::where(
            'tenant_id',
            $tenantId
        )->count();


        $totalBookings = Booking::where(
            'tenant_id',
            $tenantId
        )->count();


        $todayBookings = Booking::where(
            'tenant_id',
            $tenantId
        )
            ->whereDate(
                'booking_date',
                today()
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | UPCOMING BOOKINGS - NEXT 7 DAYS
        |--------------------------------------------------------------------------
        */

        $upcomingBookings = Booking::with([
            'customer',
            'lawnType',
        ])
            ->where(
                'tenant_id',
                $tenantId
            )
            ->whereDate(
                'booking_date',
                '>=',
                today()
            )
            ->whereDate(
                'booking_date',
                '<=',
                now()->addDays(7)->toDateString()
            )
            ->where(
                'status',
                '!=',
                'cancelled'
            )
            ->orderBy(
                'booking_date'
            )
            ->orderBy(
                'booking_time'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | UPCOMING BOOKING COUNT
        |--------------------------------------------------------------------------
        */

        $upcomingBookingsCount =
            $upcomingBookings->count();


        /*
        |--------------------------------------------------------------------------
        | TOMORROW BOOKING REMINDERS
        |--------------------------------------------------------------------------
        |
        | Sirf current tenant ki tomorrow wali bookings.
        |
        */

        $tomorrow = Carbon::tomorrow()->toDateString();

        $tomorrowBookings = Booking::with([
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
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PREPARE REMINDER DATA
        |--------------------------------------------------------------------------
        */

        $reminders = $tomorrowBookings->map(
            function ($booking) {

                return [

                    'id' =>
                        $booking->id,

                    'event_type' =>
                        $booking->event_type
                        ?? 'Event',

                    'customer_name' =>
                        optional(
                            $booking->customer
                        )->name
                        ?? 'N/A',

                    'lawn_type' =>
                        optional(
                            $booking->lawnType
                        )->lawn_type
                        ?? 'N/A',

                    'booking_date' =>
                        $booking->booking_date,

                    'booking_time' =>
                        $booking->booking_time
                        ?? 'N/A',

                    'guests' =>
                        $booking->number_of_guests
                        ?? 0,

                ];
            }
        )->values()->toArray();


        /*
        |--------------------------------------------------------------------------
        | STORE REMINDERS IN SESSION
        |--------------------------------------------------------------------------
        */

        if (!empty($reminders)) {

            session()->put(
                'tenant_booking_reminders',
                $reminders
            );

        } else {

            /*
            |----------------------------------------------------------------------
            | No tomorrow bookings
            |----------------------------------------------------------------------
            */

            session()->forget(
                'tenant_booking_reminders'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NAVBAR DATA
        |--------------------------------------------------------------------------
        */

        $lawnTypes = LawnType::where(
            'tenant_id',
            $tenantId
        )
            ->orderBy(
                'lawn_type'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PAID SERVICES
        |--------------------------------------------------------------------------
        */

        $services = Service::where(
            'tenant_id',
            $tenantId
        )
            ->orderBy(
                'service_name'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | FREE SERVICES
        |--------------------------------------------------------------------------
        */

        $freeServices = FreeService::where(
            'tenant_id',
            $tenantId
        )
            ->where(
                'status',
                true
            )
            ->orderBy(
                'service_name'
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'tenant.dashboard',
            compact(
                'totalCustomers',
                'totalLawnTypes',
                'totalBookings',
                'todayBookings',
                'upcomingBookings',
                'upcomingBookingsCount',
                'lawnTypes',
                'services',
                'freeServices'
            )
        );
    }
}