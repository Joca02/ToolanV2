<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function authenticate(string $username, string $password){
        $user=User::where('username',$username)->first();
        if(!$user || !Hash::check($password, $user->password)){
            return false;
        }
        return $user;
    }
}
