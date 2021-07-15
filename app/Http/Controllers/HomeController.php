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

        $query_userID = auth()->user()->id;

        $validateData = $request->validate([
            'faccount'=>'required|string|min:20|max:20',
            'datefrom'=>'required|date|before_or_equal:dateto',
            'dateto'=>'required|date|after_or_equal:datefrom|before:now+1 second'
        ]);

        $transfers1= User::join('accounts', 'accounts.user_id', 'users.id')
            ->join('transfers', 'account_id_from', 'accounts.id')
            ->where('users.id', '=', $query_userID)
            ->where('account_no', '=', $request->input('faccount'))
            ->whereBetween('date', [$request->input('datefrom'), $request->input('dateto')]);

        $transfers= User::join('accounts', 'accounts.user_id', 'users.id')
            ->join('transfers', 'account_id_to', 'accounts.id')
            ->where('users.id', '=', $query_userID)
            ->where('account_no', '=', $request->input('faccount'))
            ->whereBetween('date', [$request->input('datefrom'), $request->input('dateto')])
            ->union($transfers1)
            ->get();

        $transfers->moredata = $request->input('faccount');

        //  $transfers = User::find(auth()->user()->id);
       // $sask=  $request->input('faccount');
        return view('pages.list', compact('transfers'));
    }
    public function error(){
        return view('pages.error');
    }

}
