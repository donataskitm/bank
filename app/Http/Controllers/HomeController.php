<?php

namespace App\Http\Controllers;
use App\Models\Account;
use App\Models\User;
use App\Models\Transfer;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */



    public function index(){
        $accounts= Account::where('user_id', '=',  auth()->user()->id)->get();

        return view('pages.index', ['accounts'=>$accounts]);
    }
    public function search(){
         return view('pages.search');

    }
    public function transfer(){
        return view('pages.transfer');

    }
    public function list(Request $request){


        $transfers1= User::join('accounts', 'accounts.user_id', 'users.id')
            ->join('transfers', 'account_id_from', 'accounts.id')
            ->where('users.id', '=', auth()->user()->id)
            ->where('account_no', '=', $request->input('faccount'))
            ->whereBetween('date', [$request->input('datefrom'), $request->input('dateto')]);

        $transfers= User::join('accounts', 'accounts.user_id', 'users.id')
            ->join('transfers', 'account_id_to', 'accounts.id')
            ->where('users.id', '=', auth()->user()->id)
            ->where('account_no', '=', $request->input('faccount'))
            ->whereBetween('date', [$request->input('datefrom'), $request->input('dateto')])
            ->union($transfers1)
            ->get();



        //  $transfers = User::find(auth()->user()->id);
        $soul = 'kkk' ;
        return view('pages.list', compact('transfers'));
    }
    public function error(){
        return view('pages.error');
    }


}
