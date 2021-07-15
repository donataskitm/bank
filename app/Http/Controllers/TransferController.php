<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Transfer;
use Illuminate\Validation\Rule;
use App\Jobs\ProcessPodcast;
use App\Jobs\SendMoney;
use Illuminate\Validation\ValidationException;


class TransferController extends Controller
{


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
        if(isset($ac)) {
            $us = User::where('id', '=', $ac['user_id'])->first();
        }else{
            throw ValidationException::withMessages(['Įvesta klaidinga gavėjo sąskaita']);
        }
        $ac1  = Account::where('account_no', '=', request('faccountfrom'))->first();


        //dd($na['surname']);

        $validateData = $request->validate([
            'faccountfrom'=>'required|string|min:20|max:20',
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

        if($ac1['balance']-$ac1['reserved']>=request('amount')) {
            Account::where('account_no', request('faccountfrom'))->update(['reserved' => $ac1['reserved']+request('amount')]);
              }else{
            throw ValidationException::withMessages(['Saskaitoje nepakanka lėšų']);
        }

        SendMoney::dispatch($ac, $ac1, request('faccountfrom'), request('faccountto'), request('purpose'), request('amount') )
            ->delay(10);

        //dd($request);
       // dd($ats->id);prepareForValidation


        //return redirect('/transfer');
        //return Redirect::back('/transfer')->withStatus('Mokejimas atliktas');
        return redirect('/transfer')->with('message','Mokejimas atliktas');
    }
}
