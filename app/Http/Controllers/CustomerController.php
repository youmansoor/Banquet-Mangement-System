<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Customer List
     */
    /**
     * Customer List
     */
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        /*
        |--------------------------------------------------------------------------
        | Base Query - Current Tenant Only
        |--------------------------------------------------------------------------
        */

        $query = Customer::where('tenant_id', $tenantId);

        /*
        |--------------------------------------------------------------------------
        | Customer Name
        |--------------------------------------------------------------------------
        */

        if ($request->filled('name')) {

            $name = trim($request->name);

            $query->where(
                'name',
                'like',
                "%{$name}%"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Email
        |--------------------------------------------------------------------------
        */

        if ($request->filled('email')) {

            $email = trim($request->email);

            $query->where(
                'email',
                'like',
                "%{$email}%"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Phone
        |--------------------------------------------------------------------------
        | Searches both phone_1 and phone_2
        |--------------------------------------------------------------------------
        */

        if ($request->filled('phone')) {

            $phone = trim($request->phone);

            $query->where(function ($q) use ($phone) {

                $q->where(
                    'phone_1',
                    'like',
                    "%{$phone}%"
                )
                    ->orWhere(
                        'phone_2',
                        'like',
                        "%{$phone}%"
                    );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | NIC Number
        |--------------------------------------------------------------------------
        */

        if ($request->filled('nic_number')) {

            $nicNumber = trim($request->nic_number);

            $query->where(
                'nic_number',
                'like',
                "%{$nicNumber}%"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Address
        |--------------------------------------------------------------------------
        */

        if ($request->filled('address')) {

            $address = trim($request->address);

            $query->where(
                'address',
                'like',
                "%{$address}%"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CNIC Document Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('cnic')) {

            switch ($request->cnic) {

                /*
                | Both Front + Back Uploaded
                */

                case 'both':

                    $query->whereNotNull('cnic_front')
                        ->where('cnic_front', '!=', '')
                        ->whereNotNull('cnic_back')
                        ->where('cnic_back', '!=', '');

                    break;

                    /*
                    | Front Uploaded
                    */

                case 'front':

                    $query->whereNotNull('cnic_front')
                        ->where('cnic_front', '!=', '');

                    break;

                    /*
                    | Back Uploaded
                    */

                case 'back':

                    $query->whereNotNull('cnic_back')
                        ->where('cnic_back', '!=', '');

                    break;

                    /*
                    | Either Front or Back Missing
                    */

                case 'missing':

                    $query->where(function ($q) {

                        $q->whereNull('cnic_front')
                            ->orWhere('cnic_front', '')
                            ->orWhereNull('cnic_back')
                            ->orWhere('cnic_back', '');

                    });

                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $customers = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'customers.index',
            compact('customers')
        );
    }

    /**
     * Create Customer Form
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store Customer
     */
    public function store(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone_1' => [
                'required',
                'string',
                'max:30',
            ],

            'phone_2' => [
                'nullable',
                'string',
                'max:30',
            ],

            'nic_number' => [
                'required',
                'string',
                'max:30',
                Rule::unique('customers', 'nic_number')
                    ->where(function ($query) use ($tenantId) {
                        return $query->where('tenant_id', $tenantId);
                    }),
            ],

            'address' => [
                'required',
                'string',
            ],

            'cnic_front' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'cnic_back' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Tenant ID
        |--------------------------------------------------------------------------
        */

        $validated['tenant_id'] = $tenantId;

        /*
        |--------------------------------------------------------------------------
        | Upload CNIC Front
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('cnic_front')) {

            $validated['cnic_front'] = $request
                ->file('cnic_front')
                ->store('customers/cnic', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Upload CNIC Back
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('cnic_back')) {

            $validated['cnic_back'] = $request
                ->file('cnic_back')
                ->store('customers/cnic', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Create Customer
        |--------------------------------------------------------------------------
        */

        Customer::create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer added successfully.');
    }

    /**
     * Show Customer
     */
    public function show(string $id)
    {
        $customer = Customer::where(
            'tenant_id',
            auth()->user()->tenant_id
        )->findOrFail($id);

        return view('customers.show', compact('customer'));
    }

    /**
     * Edit Customer
     */
    public function edit(string $id)
    {
        $customer = Customer::where(
            'tenant_id',
            auth()->user()->tenant_id
        )->findOrFail($id);

        return view('customers.edit', compact('customer'));
    }

    /**
     * Update Customer
     */
    public function update(Request $request, string $id)
    {
        $tenantId = auth()->user()->tenant_id;

        $customer = Customer::where('tenant_id', $tenantId)
            ->findOrFail($id);

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone_1' => [
                'required',
                'string',
                'max:30',
            ],

            'phone_2' => [
                'nullable',
                'string',
                'max:30',
            ],

            'nic_number' => [
                'required',
                'string',
                'max:30',
                Rule::unique('customers', 'nic_number')
                    ->where(function ($query) use ($tenantId) {
                        return $query->where('tenant_id', $tenantId);
                    })
                    ->ignore($customer->id),
            ],

            'address' => [
                'required',
                'string',
            ],

            'cnic_front' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'cnic_back' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | CNIC Front
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('cnic_front')) {

            if ($customer->cnic_front) {
                Storage::disk('public')->delete($customer->cnic_front);
            }

            $validated['cnic_front'] = $request
                ->file('cnic_front')
                ->store('customers/cnic', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | CNIC Back
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('cnic_back')) {

            if ($customer->cnic_back) {
                Storage::disk('public')->delete($customer->cnic_back);
            }

            $validated['cnic_back'] = $request
                ->file('cnic_back')
                ->store('customers/cnic', 'public');
        }

        $customer->update($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Delete Customer
     */
    public function destroy(string $id)
    {
        $customer = Customer::where(
            'tenant_id',
            auth()->user()->tenant_id
        )->findOrFail($id);

        if ($customer->cnic_front) {
            Storage::disk('public')->delete($customer->cnic_front);
        }

        if ($customer->cnic_back) {
            Storage::disk('public')->delete($customer->cnic_back);
        }

        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
