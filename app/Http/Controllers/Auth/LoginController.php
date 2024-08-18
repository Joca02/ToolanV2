<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    private $authService;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
//        $this->middleware('guest')->except('logout');
//        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request){
        $auth=$this->authService->authenticate(
            $request->username,
            $request->password
        );
        if(!$auth){
            return redirect()->back()->with('failure', 'Username and password do not match');
        }
        else{
            return redirect('/home');
        }
    }

    public function logout(){
        return $this->authService->logout();
    }
}
