<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
     public function index()
    {
        $customers = Customer::latest()->get();
        return view('customers.index', ['customers' => $customers]);
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|min:5',
            'email'  => 'required|email|unique:customers,email',
            'number' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        Customer::create([
            'name'    => $request->name,
            'number'  => $request->number,
            'email'   => $request->email,
            'address' => $request->address,
        ]);

        toastr()->success('Customer created successfully.');
        return redirect()->route('customers.index');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.update', ['customer' => $customer]);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $request->validate([
            'name'   => 'required|string|min:5',
            'email'  => 'required|email|unique:customers,email,' . $id,
            'number' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $customer->update([
            'name'    => $request->name,
            'number'  => $request->number,
            'email'   => $request->email,
            'address' => $request->address,
        ]);
        toastr()->success('customer updated successfully.');
        return redirect()->route('customers.index');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        toastr()->success('customer deleted successfully.');
        return redirect()->route('customers.index');
    }
}
