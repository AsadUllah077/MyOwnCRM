<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\saleDetail;
use App\Models\SaleLedger;
use Illuminate\Http\Request;

class SaleController extends Controller
{
   public function index()
    {
        $sales = Sale::latest()->get();
        return view('sales.index', ['sales' => $sales]);
    }

    public function create()
    {
        $lastsaleId = Sale::latest('id')->value('sale_number');
        $sale_num = $lastsaleId ? $lastsaleId + 1 : 1000;
        // dd($sale_num);
        return view('sales.create', ['customers' => Customer::get(), 'products' => Product::get(), 'categories' => Category::get(), 'pur_num' => $sale_num]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'sale_number' => 'required|string|unique:sales,sale_number',
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
$pending_amount = $total_after_discount - $paid_amount;
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

        // Create sale
        $sale = \App\Models\Sale::create([
            'customer_id' => $request->customer_id,
            'sale_date' => $request->sale_date,
            'sale_number' => $request->sale_number,
            'total_amount' => $total_after_discount,
            'paid_amount' => $paid_amount,
            'discount' => $request->discount,
            'payment_status' => $payment_status,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

         SaleLedger::create([
            'sale_number' => $request->sale_number,
            'paid_amount' => $paid_amount,
            'pending_amount' => $pending_amount,
            'total_amount' => $total_after_discount,
        ]);

        // Create sale items
        foreach ($request->items as $item) {
            saleDetail::create([
                'category_id' => $item['category_id'],
                'product_id' => $item['product_id'],
                'price' => $item['price'],
                'qty' => $item['quantity'],
                'sale_id' => $sale['id'],
            ]);
        }

        return redirect()->route('sales.index')->with('success', 'sale created successfully!');
    }

    public function edit($id)
    {
        $sale = Sale::with('saleDetails')->findOrFail($id);
        $customers = Customer::all();
        $categories = Category::all();
        $products = Product::all();

        return view('sales.update', [
            'sale' => $sale,
            'customers' => $customers,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
    public function show($id)
    {
        $sale = Sale::with('saleDetails')->findOrFail($id);
        $customers = Customer::all();
        $categories = Category::all();
        $products = Product::all();

        return view('sales.show', [
            'sale' => $sale,
            'customers' => $customers,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'paid_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.category_id' => 'required|exists:categories,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $sale = Sale::findOrFail($id);
        $total = 0;
        foreach ($request->items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        $discount = $request->discount ?? 0;
        $total_after_discount = max(0, $total - $discount);

        // Clamp paid amount to total_after_discount
        $paid_amount = min($request->paid_amount ?? 0, $total_after_discount);
 $pending_amount = $total_after_discount - $paid_amount;
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

        $sale->update([
            'customer_id' => $request->customer_id,
            'sale_date' => $request->sale_date,
            'notes' => $request->notes,
            'paid_amount' => $request->paid_amount ?? 0,
            'discount' => $request->discount,
            'total_amount' => $total_after_discount,
            'payment_status' => $payment_status,
        ]);

        $sale_ledger = SaleLedger::where('sale_number', $sale->sale_number)->first();
        $sale_ledger->update([
            'paid_amount' => $paid_amount,
            'pending_amount' => $pending_amount,
            'total_amount' => $total_after_discount,
        ]);
        // Remove old sale details
        $sale->saleDetails()->delete();

        // Add new sale details
        foreach ($request->items as $item) {
            $sale->saleDetails()->create([
                'category_id' => $item['category_id'],
                'product_id' => $item['product_id'],
                'price' => $item['price'],
                'qty' => $item['quantity'],
                'sale_id' => $id,
            ]);
        }
        toastr()->success('sale updated successfully!');
        return redirect()->route('sales.index');
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        saleDetail::where('sale_id', $id)->delete();
        SaleLedger::where('sale_number', $sale->sale_number)->delete();
        $sale->delete();
        toastr()->success('sale deleted successfully.');
        return redirect()->route('sales.index');
    }
}
