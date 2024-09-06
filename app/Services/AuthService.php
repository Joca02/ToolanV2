<?php

namespace App\Services;

use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthService
{
    public function authenticate(string $username, string $password){
        $user=User::where('username',$username)->first();
        if(!$user || !Hash::check($password, $user->password)){
            return false;
        }
        Log::info("Login success for user with username: {$user->username}");
        Auth::login($user);
        return $user;
    }

    public function logout(){
        Log::info('Logging out user with username: ' . Auth::user()->username);
        Auth::logout();
        return redirect('/login');
    }

    public function isUserVerified(){
        Log::info('Checking if user with username '.Auth::user()->username.' is verified');
        return Auth::user()->verified;
    }

    public function requestPasswordReset($email){
        $user = User::where('email', $email)->first();

        if (!$user) {
            return ['status' => 'error', 'message' => 'User not found'];
        }
        $token = Str::random(60);
        $user->verification_token = $token;
        $user->save();

        Mail::to($user->email)->send(new PasswordResetMail($token, $user->email));

        return ['status' => 'success', 'message' => 'Password reset link sent to your email'];
    }

    public function resetPassword($token, $email,$password){
        $user = User::where('email', $email)->first();

        if (!$user || $user->verification_token !== $token) {
            Log::info("FAIL");
            return redirect()->route('login')->with('failure', 'Invalid token, please create a new request for password reset');        }

        $user->password = Hash::make($password);
        $user->verification_token = null;
        $user->save();

        return redirect('/login')->with('success', 'Password reset successfully.');
    }
}
