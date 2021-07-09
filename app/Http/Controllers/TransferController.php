<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Transfer;
use Illuminate\Validation\Rule;


class TransferController extends Controller
{
    public function filterAccNumber($acc_id){
        return DB::table('accounts')
            ->select('id')
            ->where('account_no', '=', $acc_id)
            ->first();
    }

    public function rules()
    {
        return [

        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([

            //'account_id_to' =>(int) self::filterAccNumber(request('faccountto'))->id,
           // 'body' => filter_malicious_content($this->body),
            //'tags' => convert_comma_separated_values_to_array($this->tags),
            //'is_published' => (bool) $this->is_published,
        ]);
    }

    public function store(Request $request){

        $ac  = Account::where('account_no', '=', request('faccountto'))->first();
        $us = User::where('id', '=', $ac['user_id'])->first();
       //dd($na['surname']);

        $validateData = $request->validate([
            'faccountfrom'=>'required|string|min:12|max:12',
            'faccountto'=>[
                'required',
                'string',
                'different:faccountfrom',
                 Rule::exists('accounts', 'account_no')
                    ->where('account_no', request('faccountto'))],
            'surname'=>[
                'required',
                Rule::exists('users', 'surname')
                    ->where('surname', $us['surname'])],
            'name'=>[
                'required',
                Rule::exists('users', 'name')
                    ->where('name', $us['name'])],
            'purpose'=>'required|max:255',
            'amount'=>'required'
        ]);

        //dd($request);
       // dd($ats->id);prepareForValidation
        Transfer::create([
            'account_id_from' => self::filterAccNumber(request('faccountfrom'))->id, //
            'account_id_to'=> self::filterAccNumber(request('faccountto'))->id,
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
