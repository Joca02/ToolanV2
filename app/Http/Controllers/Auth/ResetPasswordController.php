<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService){
        $this->authService = $authService;
    }

    public function requestPasswordReset(Request $request){
        $resp=$this->authService->requestPasswordReset($request->email);
        return response()->json($resp);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        return $this->authService->resetPassword(
            $request->token,
            $request->email,
            $request->password
        );
    }

    public function showResetForm(Request $request)
    {
        return view('auth.reset_password');
    }
}
