<?php

namespace App\Http\Controllers;

use App\Models\SaleLedger;
use Illuminate\Http\Request;

class SaleLedgerController extends Controller
{
      public function index(){
        $saleledgers = SaleLedger::all();
        return view('saleledger.index',compact('saleledgers'));
    }
}
