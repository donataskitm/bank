<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Account;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class
SendMoney implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 120;

    public $ac;
    public $ac1;
    public $faccountfrom;
    public $faccountto;
    public $purpose;
    public $amount;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ac, $ac1, $faccountfrom, $faccountto, $purpose, $amount)
    {

        $this->ac=$ac;
        $this->ac1=$ac1;
        $this->faccountfrom=$faccountfrom;
        $this->faccountto=$faccountto;
        $this->purpose=$purpose;
        $this->amount=$amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//dd($this->amount);
        Account::where('account_no', $this->faccountfrom)->update(['reserved' => $this->ac1['reserved']-$this->amount]);
        Account::where('account_no',  $this->faccountto)->update(['balance' => $this->ac['balance']+$this->amount]);
        Account::where('account_no', $this->faccountfrom)->update(['balance' => $this->ac1['balance']-$this->amount]);

        //Account::where('account_no', 'LT655597745445546503')->update(['balance' => $this->h]);
        Transfer::create([
            'account_id_from' => self::filterAccNumber($this->faccountfrom)->id, //
            'account_id_to'=> self::filterAccNumber($this->faccountto)->id,
            'purpose'=>$this->purpose,
            'status'=>1,
            'amount'=> $this->amount,
            'date'=>now()->format('Y-m-d')
        ]);
    }
    public function filterAccNumber($acc_id){
        return DB::table('accounts')
            ->select('id')
            ->where('account_no', '=', $acc_id)
            ->first();
    }
}
