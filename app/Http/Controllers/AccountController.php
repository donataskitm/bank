<?php

namespace App\Http\Controllers;
use App\Models\Account;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


class AccountController extends Controller
{


    /**
     * @return \Illuminate\Http\Response
     * @param $id
    */


  public function show(Account $accounts){
//       $accounts =  Account::all();
//        //return view('pages.home', compact('account'));
//        return view('pages.home', ['accounts'=>$accounts]);
 }




}
