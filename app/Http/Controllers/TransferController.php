<?php

namespace App\Http\Controllers;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\User;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Models\Transfer;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\Rule;
use App\Jobs\ProcessPodcast;
use App\Jobs\SendMoney;
use Illuminate\Validation\ValidationException;


class TransferController extends Controller
{

    public function filterAccNumber($acc_id){
        return DB::table('accounts')
            ->select('id')
            ->where('account_no', '=', $acc_id)
            ->first();
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
        ],
            [
                'faccountfrom.required'=> 'Pasirinkite sąskaitą',
                'faccountfrom.string'=>  'Klaidinga sąskaita',
                'faccountfrom.min'=>  'Klaida. Klaidinga sąskaita',
                'faccountfrom.max'=> 'Klaida. Klaidinga sąskaita',
                'faccountto.exists'=> 'Klaida. Tokia sąskaita neegzistuoja',
                'faccountto.different'=> 'Klaida. Gavėjo sąskaita sutampa su pavedimo sąskaita',
                'faccountto.required'=> 'Įveskite gavėjo sąskaitos numerį',
                'surname.required' => 'Įveskite pavardę',
                'surname.exists' => 'Neteisinga pavardė. Šiam vartotojui nepriklauso nurodyta sąskaita',
                'name.required' => 'Įveskite vardą',
                'name.exists' => 'Neteisingas vardas. Šiam vartotojui nepriklauso nurodyta sąskaita',
                'purpose.required' => 'Įveskite pavedimo paskirtį',
                'purpose.max' => 'Sutrumpinkite pavedimo paskirties tekstą',
                'amount.required' => 'Įveskite pavedimo sumą'

            ]
        );



        if($ac1['balance']-$ac1['reserved']>=request('amount')) {
            Account::where('account_no', request('faccountfrom'))->update(['reserved' => $ac1['reserved']+request('amount')]);
              }else{
            throw ValidationException::withMessages(['Saskaitoje nepakanka lėšų']);
           // return redirect()->back()->withInput();
        }

        $tid=Transfer::create([
            'account_id_from' => self::filterAccNumber(request('faccountfrom'))->id, //
            'account_id_to'=> self::filterAccNumber(request('faccountto'))->id,
            'purpose'=>request('purpose'),
            'status'=>1,
            'amount'=> request('amount'),
            'date'=>now()->format('Y-m-d')
        ]);

        $data=SendMoney::dispatch($ac, $ac1, request('faccountfrom'), request('faccountto'), request('amount'),  $tid->id )
            ->delay(120);

        //dd($request);
       // dd($ats->id);prepareForValidation

        //return redirect('/transfer');
        //return Redirect::back('/transfer')->withStatus('Mokejimas atliktas');
        return redirect('/transfer')->with('message','Mokejimas atliktas');
    }

    public function cancel($account)
    {
      //  $deletedRows = Transfer::where('id', $account)->delete();

        $jobs = Job::get();
        // $j1 = Job::select('payload')->get();
        foreach ($jobs as $job){
                    $aw = json_decode($job->payload)->data->command;
                    $cm = unserialize($aw);
                   //dd($cm->tid.$job->id);


            if($cm->tid == $account){

                try {  //jei ivyks klaida kurioj nors is 4-iu uzklausu, nebus trinami irasai
                    DB::beginTransaction();
                      DB::table('jobs')->whereId($job->id)->delete();
                      DB::table('transfers')->whereId($account)->delete();
                      $acc_reserved=DB::table('accounts')->where('account_no',$cm->faccountfrom)->first()->reserved;
                      Account::where('account_no', $cm->faccountfrom)->update(['reserved' => $acc_reserved-$cm->amount]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }
                      //dar reik siust zinute pavyko
                    }else{
                        //dar reik siust zinute nepavyko arba
                    }
        }
        return redirect('/');
//https://stackoverflow.com/questions/40139208/how-do-i-nicely-decode-laravel-failed-jobs-json
//        dd($cm->tid);
    }
}
