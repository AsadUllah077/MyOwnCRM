<?php

namespace App\Http\Controllers;

use App\Models\PurchaseLedger;
use App\Models\SaleLedger;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $purchaserecods = PurchaseLedger::all();
        $salerecods = SaleLedger::all();

        // Purchase totals
        $purchaseTotalAmount = $purchaserecods->sum('total_amount');
        $purchasePaidAmount = $purchaserecods->sum('paid_amount');
        $purchasePendingAmount = $purchaserecods->sum('pending_amount');

        // Sale totals
        $saleTotalAmount = $salerecods->sum('total_amount');
        $salePaidAmount = $salerecods->sum('paid_amount');
        $salePendingAmount = $salerecods->sum('pending_amount');

        // Counts
        $purchaseCount = $purchaserecods->count();
        $saleCount = $salerecods->count();

        return view('reports.index', compact(
            'purchaseTotalAmount',
            'purchasePaidAmount',
            'purchasePendingAmount',
            'saleTotalAmount',
            'salePaidAmount',
            'salePendingAmount',
            'purchaseCount',
            'saleCount'
        ));
    }
}
