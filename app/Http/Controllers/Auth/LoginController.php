<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|exists:users',
            'password' => 'required',
        ];

        $custommessages = [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
        ];

        $this->validate($request, $rules, $custommessages);

        try {
            //code...
            $data = $request->all();
            unset($data['_token']);
            // dd(Auth::attempt($data), auth()->user()['status'] == 1);
            if (Auth::attempt($data) && auth()->user()['status'] == 1) {
                if (auth()->user()->role == 'admin') {
                    # code...
                    Log::channel('custom')->info('Userlogin ' . 'UserName -> ' . auth()->user()->name . ' ' . auth()->user()->lastname);
                    return redirect()->route('backend.home');
                } else {
                    # code...
                    Log::channel('custom')->info('Userlogin ' . 'UserName -> ' . auth()->user()->name . ' ' . auth()->user()->lastname);
                    return redirect()->route('user.backend.home');
                }
            } else {
                // validation not successful, send back to form

                Auth::logout();
                throw new \Exception('User is not Active.');
            }
        } catch (\Exception $e) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
