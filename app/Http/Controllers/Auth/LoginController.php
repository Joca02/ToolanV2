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

  //  protected $redirectTo = '/home';


    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request){
        $auth=$this->authService->authenticate(
            $request->username,
            $request->password
        );
        if(!$auth){
            return redirect()->back()->with('failure', 'Username and password do not match');
        }
        else if(!$this->authService->isUserVerified()){
            return redirect()->back()->with('failure', 'Please verify your email address before logging in');
        }
        else{
            return redirect('user/home');
        }
    }

    public function logout(){
        return $this->authService->logout();
    }
}
