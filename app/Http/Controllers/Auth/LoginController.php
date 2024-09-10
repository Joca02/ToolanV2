<?php

namespace App\Http\Controllers\Auth;

use App\Enum\ActionType;
use App\Enum\UserType;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\StatisticsService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        else if(!$auth->verified){
            return redirect()
                ->back()
                ->with('failure', 'Please verify your email address before logging in');
        }
        else if($this->authService->isUserAccountDeactivated($auth->id_user)){
            $deactivatedAccount=$this->authService->getDeactivatedAccountById($auth->id_user);
            $this->authService->sendReactivateAccountMail($deactivatedAccount->token,$auth->email);
            return redirect()
                ->back()
                ->with(
                    'failure',
                    'Your account is deactivated, please check your email box for reactivation instructions. '
                );
        }
        else if($this->authService->isUserBanned($auth->id_user)){
            $banInfo=$this->authService->getBan($auth->id_user);
            return redirect()
                ->back()
                ->with(
                    'failure',
                    'Your account has been banned until '.$banInfo->date_end. '. Reason behind ban: '.$banInfo->ban_reason
                );

        }
        else{
            Log::info("Login success for user with username: {$auth->username}");
            StatisticsService::insertAction(ActionType::LOGIN);
            Auth::login($auth);
            return redirect('user/home');
        }
    }

    public function showLoginForm()
    {
        if(Auth::check()){
            if (Auth::user()->user_type==UserType::USER->value) {
                return redirect('/user/home');
            }
            else if(Auth::user()->user_type==UserType::ADMIN->value) {
                return redirect('/admin/home');
            }
        }

        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function logout(){
        return $this->authService->logout();
    }
}
