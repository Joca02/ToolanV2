<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
}
