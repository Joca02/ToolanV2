<?php

namespace App\Services;

use App\Mail\UserVerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserRegistrationService
{
    public function registerUser(array $data){
        $user=User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'gender' => $data['gender'],
            'verification_token' => Str::random(60)
        ]);
        Log::info("Registered user with id: {$user->id}");
        Mail::to($user->email)->send(new UserVerificationMail($user));
    }

    public function isUsernameAvailable($username){
        return !User::where('username', $username)->exists();
    }

    public function isEmailAvailable($email){
        return !User::where('email', $email)->exists();
    }
}
