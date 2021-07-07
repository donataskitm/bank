<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transfer;

class TransferController extends Controller
{

    public function store(Request $request){


        //dd($request);
        Transfer::create([
            'account_id_from' => 3,
            'account_id_to'=>4,
            'purpose'=>request('purpose'),
            'status'=>1,
            'amount'=>request('amount'),
            'date'=>now()->format('Y-m-d')
        ]);
        return redirect('/');
    }


}
