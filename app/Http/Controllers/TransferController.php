<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Transfer;

class TransferController extends Controller
{
    public function filterAccNumber($acc_id){
        return DB::table('accounts')
            ->select('id')
            ->where('account_no', '=', $acc_id)
            ->first();
    }

    public function store(Request $request){
        $validateData = $request->validate([
            'purpose'=>'required|max:255',
            'amount'=>'required'
        ]);

       // dd($ats->id);prepareForValidation
        Transfer::create([
            'account_id_from' => self::filterAccNumber(request('faccountfrom'))->id,
            'account_id_to'=>self::filterAccNumber(request('faccountto'))->id,
            'purpose'=>request('purpose'),
            'status'=>1,
            'amount'=>request('amount'),
            'date'=>now()->format('Y-m-d')
        ]);
        //return redirect('/transfer');
        //return Redirect::back('/transfer')->withStatus('Mokejimas atliktas');
        return redirect('/transfer')->with('message','Mokejimas atliktas');
    }
}
