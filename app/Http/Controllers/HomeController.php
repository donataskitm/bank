<?php

namespace App\Http\Controllers;
use App\Models\Account;
use App\Models\User;
use App\Models\Transfer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        $accounts = Account::where('user_id', '=',  auth()->user()->id)->get();
        return view('pages.index', ['accounts'=>$accounts]);
    }


    public function search(){
        $users_id=Account::where('user_id', '=',auth()->user()->id)->get() ;
       // $users_id=DB::Table('accounts')->select( 'account_no')->where('user_id', auth()->user()->id)->get();
         return view('pages.search', compact('users_id'));

    }
    public function transfer(){
        return view('pages.transfer');
    }

    public function transfer1(){
        return view('pages.transfer1');
    }

    public function list(Request $request){

        $query_userID = auth()->user()->id;
        $validateData = $request->validate([
            'faccount'=>'required|string|min:20|max:20',
            'datefrom'=>'required|date|before_or_equal:dateto',
            'dateto'=>'required|date|after_or_equal:datefrom|before:now+1 second'
        ],
            [
                'faccount.string'=> 'Klaidinga sąskaita',
                'faccount.required'=> 'Pasirinkite sąsaitą',
                'faccount.min'=>'Sąskaitos numeris per trumpas',
                'faccount.max'=>'Sąskaitos numeris per ilgas',
                'datefrom.required'=>'Pasirinkite periodo pradžios datą',
                'datefrom.date'=>'Netinkamas datos formatas',
                'datefrom.before_or_equal'=>'Periodo pradžia negali būti vėlesnė nei perodo pabaigos data',
                'dateto.required'=>'Pasirinkite periodo pabaigos datą',
                'dateto.date'=>'Klaidinga data',
                'dateto.after_or_equal'=>'Periodo pabaigos data negali būti ankstesnė už periodo pradžios datą',
                'dateto.before'=>'Periodo pabaigos data negali būti vėlesnė nei šiandienos data',
            ]
        );

//        $transfers1= User::join('accounts', 'accounts.user_id', 'users.id')
//            ->join('transfers', 'account_id_from', 'accounts.id')
//            ->where('users.id', '=', $query_userID)
//            ->where('account_no', '=', $request->input('faccount'))
//            ->whereBetween('date', [$request->input('datefrom'), $request->input('dateto')]);
//
//        $transfers= User::join('accounts', 'accounts.user_id', 'users.id')
//            ->join('transfers', 'account_id_to', 'accounts.id')
//            ->where('users.id', '=', $query_userID)
//            ->where('account_no', '=', $request->input('faccount'))
//            ->whereBetween('date', [$request->input('datefrom'), $request->input('dateto')])
//            ->union($transfers1)
//            ->orderBy('date', 'desc')
//            ->get();


        $transfers=Transfer:: select('status', 'transfers.account_id_from', 'transfers.account_id_to',  'purpose', 'amount', 'transfers.id', 'a1.account_no as account_no_from' , 'a2.account_no as account_no_to', 'u1.name as name1', 'u1.surname as surname1', 'u2.name as name2', 'u2.surname as surname2', 'transfers.date')
            ->join('accounts as a1', 'a1.id', '=', 'transfers.account_id_from')
            ->join('users as u1', 'u1.id', '=', 'a1.user_id')
            ->join('accounts as a2', 'a2.id', '=', 'transfers.account_id_to')
            ->join('users as u2', 'u2.id', '=', 'a2.user_id')
            ->whereBetween('transfers.date', [$request->input('datefrom'), $request->input('dateto')])
            ->where('a1.account_no', '=', $request->input('faccount'))
            ->orWhere('a2.account_no', '=', $request->input('faccount'))
            ->orderBy('transfers.date', 'desc')
            ->get( );


       // $transfers->moredata = $request->input('faccount');
        //  $transfers = User::find(auth()->user()->id);
        $sask=  $request->input('faccount');
        $acc_id = Account::firstWhere('account_no', $request->input('faccount'));
       // dd($transfers.'k');
        return view('pages.list', compact('transfers'), compact('acc_id'));
    }
}
