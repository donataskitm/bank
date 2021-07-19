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
    public $timeout = 10;

    public $ac;
    public $ac1;
    public $faccountfrom;
    public $faccountto;
    public $amount;
    public $tid;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ac, $ac1, $faccountfrom, $faccountto, $amount, $tid)
    {
        $this->ac=$ac;
        $this->ac1=$ac1;
        $this->faccountfrom=$faccountfrom;
        $this->faccountto=$faccountto;
        $this->amount=$amount;
        $this->tid=$tid;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       // $this->job->getJobId();
        Account::where('account_no', $this->faccountfrom)->update(['reserved' => $this->ac1['reserved']-$this->amount]);
        Account::where('account_no', $this->faccountfrom)->update(['balance' => $this->ac1['balance']-$this->amount]);
        Account::where('account_no',  $this->faccountto)->update(['balance' => $this->ac['balance']+$this->amount]);
        Transfer::where('id', $this->tid)->update(['status' => 2]);
        //Account::where('account_no', 'LT655597745445546503')->update(['balance' => $this->h]);
    }
}
