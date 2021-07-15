<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

    protected function create(array $data)
    {

        try {  //jei ivyks klaida kurioj nors is 3-ju uzklausu, nebus iterpiami irasai, o iterpti atsaukti
            DB::beginTransaction();
            $user = User::create([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $acc1 = Account::create([
                'user_id' => $user->id,
                'balance' => 500,
                'reserved' =>0,
                'main_account' => 1,
                'account_no' => self::generateAccNumber(),
            ]);
          $acc2 = Account::create([
                'user_id' => $user->id,
                'balance' => 0,
                'reserved' =>0,
                'main_account' => 2,
               'account_no' => self::generateAccNumber(),
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
        }

        return $user;

    }

    public function generateAccNumber(): string
    {
        do {
            $refrence_id = mt_rand(100000000000000000, 999999999999999999);
        } while ( DB::table( 'accounts' )->where( 'account_no', 'LT'.$refrence_id )->exists() );
        return  'LT'.$refrence_id;
    }
}
