<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Tenant;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $tenants = Tenant::query()
            ->where('status', true)
            ->orderBy('business_name')
            ->get();

        return view('payments.index', compact('tenants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank,online,card',
            'payment_status' => 'required|in:pending,partial,paid',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'paid_at' => 'nullable|date',
        ]);

        Payment::create($validated);

        return back()->with('success', 'Payment saved successfully.');
    }
}
