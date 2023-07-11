<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
Use Redirect;
use Auth;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function postLogin(Request $request)
    {
        //    dd($request->all());

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {

            Redirect::to('/')
                ->withErrors($validator)
                ->withInput()->send();

        } else {
            $credentials = $request->only('username', 'password');
            //dd($credentials);

            $password = Hash::make($credentials['password']);

            $user = User::where('username', $credentials['username'])
                //  ->where('password', $password)
                // ->where('role','restaurant')
                ->where('is_active', 1)
                ->first();

            if ($user && Hash::check($credentials['password'], $user->password)) {
                Auth::loginUsingId($user->user_id);
                // -- OR -- //
                Auth::login($user, true);


                if($user->role=="admin_user")
                    return redirect()->intended('/blogs');
                return redirect()->intended('/dashboard');


            } else {
                return redirect()->intended('/')->withErrors([$this->username() => 'You have entered an invalid username or password']);
            }
            //

        }
    }
}
