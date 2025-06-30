<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('suppliers.index', ['suppliers' => $suppliers]);
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|min:5',
            'email'  => 'required|email|unique:suppliers,email',
            'number' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        Supplier::create([
            'name'    => $request->name,
            'number'  => $request->number,
            'email'   => $request->email,
            'address' => $request->address,
        ]);

        toastr()->success('Supplier created successfully.');
        return redirect()->route('suppliers.index');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.update', ['supplier' => $supplier]);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $request->validate([
            'name'   => 'required|string|min:5',
            'email'  => 'required|email|unique:suppliers,email,' . $id,
            'number' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $supplier->update([
            'name'    => $request->name,
            'number'  => $request->number,
            'email'   => $request->email,
            'address' => $request->address,
        ]);
        toastr()->success('Supplier updated successfully.');
        return redirect()->route('suppliers.index');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        toastr()->success('Supplier deleted successfully.');
        return redirect()->route('suppliers.index');
    }
}
