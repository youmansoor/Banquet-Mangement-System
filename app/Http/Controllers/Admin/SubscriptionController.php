<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Subscription list
     */
    /**
     * Subscription list
     */
    /**
     * Subscription list
     */
    public function index(Request $request)
    {
        $query = Subscription::with('tenant');

        /*
        |--------------------------------------------------------------------------
        | Global Search
        |--------------------------------------------------------------------------
        | Search subscription + tenant related fields
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | Subscription fields
                |--------------------------------------------------------------------------
                */

                $q->where('id', 'like', "%{$search}%")

                    ->orWhere('status', 'like', "%{$search}%")

                    ->orWhere('amount', 'like', "%{$search}%")

                    ->orWhereDate('starts_at', $search)

                    ->orWhereDate('ends_at', $search)

                    ->orWhereDate('created_at', $search)

                    /*
                    |--------------------------------------------------------------------------
                    | Tenant fields
                    |--------------------------------------------------------------------------
                    */

                    ->orWhereHas('tenant', function ($tenantQuery) use ($search) {

                        $tenantQuery
                            ->where('id', 'like', "%{$search}%")

                            ->orWhere('business_name', 'like', "%{$search}%")

                            ->orWhere('owner_name', 'like', "%{$search}%")

                            ->orWhere('email', 'like', "%{$search}%")

                            ->orWhere('phone', 'like', "%{$search}%")

                            ->orWhere('address', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->input('status')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Amount Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('amount')) {

            $query->where(
                'amount',
                $request->input('amount')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Created Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('created_date')) {

            $query->whereDate(
                'created_at',
                $request->input('created_date')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Start Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('starts_at')) {

            $query->whereDate(
                'starts_at',
                $request->input('starts_at')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | End Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('ends_at')) {

            $query->whereDate(
                'ends_at',
                $request->input('ends_at')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get Subscriptions
        |--------------------------------------------------------------------------
        */

        $subscriptions = $query
            ->latest()
            ->get();

        return view(
            'super_admin.subscriptions.index',
            compact('subscriptions')
        );
    }

    /**
     * Create subscription form
     */
    public function create()
    {
        $tenants = Tenant::query()
            ->orderBy('business_name')
            ->orderBy('owner_name')
            ->get();

        return view(
            'super_admin.subscriptions.create',
            compact('tenants')
        );
    }

    /**
     * Store subscription
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => [
                'required',
                'integer',
                'exists:tenants,id',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Check active subscription
        |--------------------------------------------------------------------------
        */

        $alreadyActive = Subscription::query()
            ->where('tenant_id', $validated['tenant_id'])
            ->where('status', 'active')
            ->exists();

        if ($alreadyActive) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'This tenant already has an active subscription.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Create subscription
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated) {

            $startDate = now();

            Subscription::create([
                'tenant_id' => $validated['tenant_id'],

                'amount' => $validated['amount'],

                'starts_at' => $startDate,

                // Subscription valid for one month
                'ends_at' => $startDate
                    ->copy()
                    ->addMonth(),

                'status' => 'active',
            ]);
        });

        return redirect()
            ->route('admin.subscriptions.index')
            ->with(
                'success',
                'Subscription created successfully.'
            );
    }

    /**
     * Show subscription
     */
    public function show(Subscription $subscription)
    {
        $subscription->load('tenant');

        return view(
            'super_admin.subscriptions.show',
            compact('subscription')
        );
    }

    /**
     * Edit subscription
     */
    public function edit(Subscription $subscription)
    {
        $tenants = Tenant::query()
            ->orderBy('business_name')
            ->orderBy('owner_name')
            ->get();

        return view(
            'super_admin.subscriptions.edit',
            compact(
                'subscription',
                'tenants'
            )
        );
    }

    /**
     * Update subscription
     */
    public function update(
        Request $request,
        Subscription $subscription
    ) {
        $validated = $request->validate([
            'tenant_id' => [
                'required',
                'integer',
                'exists:tenants,id',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate active subscription
        |--------------------------------------------------------------------------
        */

        $alreadyActive = Subscription::query()
            ->where('tenant_id', $validated['tenant_id'])
            ->where('id', '!=', $subscription->id)
            ->where('status', 'active')
            ->exists();

        if ($alreadyActive) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'This tenant already has another active subscription.'
                );
        }

        $subscription->update([
            'tenant_id' => $validated['tenant_id'],
            'amount' => $validated['amount'],
        ]);

        return redirect()
            ->route('admin.subscriptions.index')
            ->with(
                'success',
                'Subscription updated successfully.'
            );
    }

    /**
     * Delete subscription
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()
            ->route('admin.subscriptions.index')
            ->with(
                'success',
                'Subscription deleted successfully.'
            );
    }
}
