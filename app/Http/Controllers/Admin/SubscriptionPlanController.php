<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionPlanController extends Controller
{
    /**
     * Display all subscription plans.
     */
    public function index(Request $request)
    {
        $query = SubscriptionPlan::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('slug', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $subscriptionPlans = $query->latest()->get();

        return view(
            'super_admin.subscription_plans.index',
            compact('subscriptionPlans')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view(
            'super_admin.subscription_plans.create'
        );
    }

    /**
     * Store subscription plan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:subscription_plans,slug',
            ],

            'monthly_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'yearly_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'trial_days' => [
                'required',
                'integer',
                'min:0',
            ],

            'max_users' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'max_venues' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'max_bookings' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'features' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Convert Features Text To JSON Array
        |--------------------------------------------------------------------------
        */

        $features = null;

        if ($request->filled('features')) {

            $features = array_values(
                array_filter(
                    array_map(
                        'trim',
                        preg_split(
                            '/\r\n|\n|\r/',
                            $request->features
                        )
                    )
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Plan
        |--------------------------------------------------------------------------
        */

        SubscriptionPlan::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'monthly_price' => $validated['monthly_price'],
            'yearly_price' => $validated['yearly_price'],
            'trial_days' => $validated['trial_days'],
            'max_users' => $validated['max_users'] ?? null,
            'max_venues' => $validated['max_venues'] ?? null,
            'max_bookings' => $validated['max_bookings'] ?? null,
            'features' => $features,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.subscription-plans.index')
            ->with(
                'success',
                'Subscription plan created successfully.'
            );
    }

    /**
     * Show subscription plan.
     */
    public function show(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->loadCount('subscriptions');

        return view(
            'super_admin.subscription_plans.show',
            compact('subscriptionPlan')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view(
            'super_admin.subscription_plans.edit',
            compact('subscriptionPlan')
        );
    }

    /**
     * Update subscription plan.
     */
    public function update(
        Request $request,
        SubscriptionPlan $subscriptionPlan
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique(
                    'subscription_plans',
                    'slug'
                )->ignore($subscriptionPlan->id),
            ],

            'monthly_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'yearly_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'trial_days' => [
                'required',
                'integer',
                'min:0',
            ],

            'max_users' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'max_venues' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'max_bookings' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'features' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Convert Features
        |--------------------------------------------------------------------------
        */

        $features = null;

        if ($request->filled('features')) {

            $features = array_values(
                array_filter(
                    array_map(
                        'trim',
                        preg_split(
                            '/\r\n|\n|\r/',
                            $request->features
                        )
                    )
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $subscriptionPlan->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'monthly_price' => $validated['monthly_price'],
            'yearly_price' => $validated['yearly_price'],
            'trial_days' => $validated['trial_days'],
            'max_users' => $validated['max_users'] ?? null,
            'max_venues' => $validated['max_venues'] ?? null,
            'max_bookings' => $validated['max_bookings'] ?? null,
            'features' => $features,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.subscription-plans.index')
            ->with(
                'success',
                'Subscription plan updated successfully.'
            );
    }

    /**
     * Delete subscription plan.
     */
    public function destroy(
        SubscriptionPlan $subscriptionPlan
    ) {
        /*
        |--------------------------------------------------------------------------
        | Don't delete plan if tenants are using it
        |--------------------------------------------------------------------------
        */

        if ($subscriptionPlan->subscriptions()->exists()) {

            return back()->with(
                'error',
                'This subscription plan cannot be deleted because it is already assigned to a tenant.'
            );
        }

        $subscriptionPlan->delete();

        return redirect()
            ->route('admin.subscription-plans.index')
            ->with(
                'success',
                'Subscription plan deleted successfully.'
            );
    }
}
