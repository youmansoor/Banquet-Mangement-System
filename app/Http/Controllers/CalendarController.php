<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class CalendarController extends Controller
{
    public function index()
    {
        $tenantId = (int) auth()->user()->tenant_id;

        $bookings = Booking::with([
            'customer',
            'lawnType',
        ])
            ->where('tenant_id', $tenantId)
            ->orderBy('booking_date')
            ->get();

        return view('bookings.calendar', compact('bookings'));
    }
}
