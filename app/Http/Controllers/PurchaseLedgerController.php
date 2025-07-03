<?php

namespace App\Http\Controllers;

use App\Models\PurchaseLedger;
use Illuminate\Http\Request;

class PurchaseLedgerController extends Controller
{
    public function index(){
        $purchaseledgers = PurchaseLedger::all();
        return view('purchaseledger.index',compact('purchaseledgers'));
    }
}
