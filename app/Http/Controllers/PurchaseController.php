<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::latest()->get();
        return view('purchase.index', ['purchases' => $purchases]);
    }

    public function create()
    {
        $lastPurchaseId = Purchase::latest('id')->value('purchase_number');
        $purchase_num = $lastPurchaseId ? $lastPurchaseId + 1 : 1000;
        // dd($purchase_num);
        return view('purchase.create', ['suppliers' => Supplier::get(), 'products' => Product::get(), 'categories' => Category::get(), 'pur_num' => $purchase_num]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'purchase_number' => 'required|string|unique:purchases,purchase_number',
            'paid_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|in:pending,partial,paid',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.category_id' => 'nullable|exists:categories,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Calculate total from items
        $total = 0;
        foreach ($request->items as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Apply discount
        // dd($request->discount);
        $discount = $request->discount ?? 0;
        $total_after_discount = max(0, $total - $discount);

        // Clamp paid amount to total_after_discount
        $paid_amount = min($request->paid_amount ?? 0, $total_after_discount);

        // Determine payment status automatically if not set
        $payment_status = $request->payment_status;
        if (!$payment_status) {
            if ($paid_amount == 0) {
                $payment_status = 'pending';
            } elseif ($paid_amount < $total_after_discount) {
                $payment_status = 'partial';
            } else {
                $payment_status = 'paid';
            }
        }

        // Create purchase
        $purchase = \App\Models\Purchase::create([
            'supplier_id' => $request->supplier_id,
            'purchase_date' => $request->purchase_date,
            'purchase_number' => $request->purchase_number,
            'total_amount' => $total_after_discount,
            'paid_amount' => $paid_amount,
            'discount' => $request->discount,
            'payment_status' => $payment_status,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        // Create purchase items
        foreach ($request->items as $item) {
            PurchaseDetails::create([
                'category_id' => $item['category_id'],
                'product_id' => $item['product_id'],
                'price' => $item['price'],
                'qty' => $item['quantity'],
                'purchase_id' => $purchase['id'],
            ]);
        }

        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully!');
    }

    public function edit($id)
    {
        $purchase = Purchase::with('purchaseDetails')->findOrFail($id);
        $suppliers = Supplier::all();
        $categories = Category::all();
        $products = Product::all();

        return view('purchase.update', [
            'purchase' => $purchase,
            'suppliers' => $suppliers,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
    public function show($id)
    {
        $purchase = Purchase::with('purchaseDetails')->findOrFail($id);
        $suppliers = Supplier::all();
        $categories = Category::all();
        $products = Product::all();

        return view('purchase.show', [
            'purchase' => $purchase,
            'suppliers' => $suppliers,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'paid_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.category_id' => 'required|exists:categories,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $purchase = Purchase::findOrFail($id);
        $total = 0;
        foreach ($request->items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        $discount = $request->discount ?? 0;
        $total_after_discount = max(0, $total - $discount);

        // Clamp paid amount to total_after_discount
        $paid_amount = min($request->paid_amount ?? 0, $total_after_discount);

        // Determine payment status automatically if not set
        $payment_status = $request->payment_status;
        if (!$payment_status) {
            if ($paid_amount == 0) {
                $payment_status = 'pending';
            } elseif ($paid_amount < $total_after_discount) {
                $payment_status = 'partial';
            } else {
                $payment_status = 'paid';
            }
        }
        // Calculate new total

        $discount = $request->discount ?? 0;
        $total_after_discount = max($total - $discount, 0);

        $purchase->update([
            'supplier_id' => $request->supplier_id,
            'purchase_date' => $request->purchase_date,
            'notes' => $request->notes,
            'paid_amount' => $request->paid_amount ?? 0,
            'discount' => $request->discount,
            'total_amount' => $total_after_discount,
            'payment_status' => $payment_status,
        ]);

        // Remove old purchase details
        $purchase->purchaseDetails()->delete();

        // Add new purchase details
        foreach ($request->items as $item) {
            $purchase->purchaseDetails()->create([
                'category_id' => $item['category_id'],
                'product_id' => $item['product_id'],
                'price' => $item['price'],
                'qty' => $item['quantity'],
                'purchase_id' => $id,
            ]);
        }
        toastr()->success('Purchase updated successfully!');
        return redirect()->route('purchases.index');
    }

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        PurchaseDetails::where('purchase_id', $id)->delete();
        $purchase->delete();
        toastr()->success('Purchase deleted successfully.');
        return redirect()->route('purchases.index');
    }
}
