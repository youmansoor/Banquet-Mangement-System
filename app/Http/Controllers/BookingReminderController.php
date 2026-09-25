<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BookingReminderController extends Controller
{
    /**
     * Display booking reminders page
     */
    public function index()
    {
        $user = Auth::user();

        if (! $user->tenant_id) {
            return back()->with(
                'error',
                'Only tenants can access booking reminders.'
            );
        }

        $upcomingBookings = Booking::with([
            'customer',
            'lawnType',
        ])
            ->where('tenant_id', $user->tenant_id)
            ->whereDate(
                'booking_date',
                '>=',
                now()->startOfDay()
            )
            ->whereDate(
                'booking_date',
                '<=',
                now()->addDays(7)->endOfDay()
            )
            ->where('status', '!=', 'cancelled')
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get();

        return view(
            'bookings.reminders',
            compact('upcomingBookings')
        );
    }

    /**
     * Send reminder email
     */
    public function sendReminder(
        Request $request,
        Booking $booking
    ) {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | TENANT SECURITY
        |--------------------------------------------------------------------------
        */

        if (
            ! $user->tenant_id ||
            $booking->tenant_id != $user->tenant_id
        ) {
            return back()->with(
                'error',
                'You are not authorized to send reminders for this booking.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER EMAIL
        |--------------------------------------------------------------------------
        */

        $booking->load([
            'customer',
            'lawnType',
        ]);

        if (
            ! $booking->customer ||
            empty($booking->customer->email)
        ) {
            return back()->with(
                'error',
                'Customer email not found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SEND EMAIL
        |--------------------------------------------------------------------------
        */

        try {

            $emailContent =
                $this->generateReminderEmailContent(
                    $booking
                );

            Mail::raw(
                $emailContent,
                function ($message) use ($booking) {

                    $message
                        ->to($booking->customer->email)
                        ->subject(
                            'Booking Reminder - '.
                            $booking->event_type
                        );
                }
            );

            return back()->with(
                'success',
                'Reminder email sent successfully to '.
                $booking->customer->email
            );

        } catch (\Throwable $e) {

            return back()->with(
                'error',
                'Failed to send reminder email: '.
                $e->getMessage()
            );
        }
    }

    /**
     * Generate reminder email content
     */
    private function generateReminderEmailContent(
        Booking $booking
    ) {

        $customerName =
            $booking->customer->name
            ?? 'Customer';

        $eventType =
            $booking->event_type
            ?? 'Event';

        $bookingDate =
            $booking->booking_date
            ? Carbon::parse(
                $booking->booking_date
            )->format('l, F j, Y')
            : 'Not specified';

        $bookingTime =
            $booking->booking_time
            ?? 'Not specified';

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        | Aapke code mein lawn_types table ka field
        | "lawn_type" use ho raha hai.
        */

        $lawnType =
            $booking->lawnType->lawn_type
            ?? $booking->lawn_type
            ?? 'Not specified';

        $guests =
            $booking->number_of_guests
            ?? 0;

        return "Dear {$customerName},

This is a friendly reminder about your upcoming booking.

Booking Details
----------------------------

Event: {$eventType}
Date: {$bookingDate}
Time: {$bookingTime}
Lawn: {$lawnType}
Number of Guests: {$guests}

Please make sure to arrive on time for your event.

If you need to make any changes to your booking, please contact us as soon as possible.

Thank you for choosing our services.

Best regards,
Banquet Management Team";
    }
}
