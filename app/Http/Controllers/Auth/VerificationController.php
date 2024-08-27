<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();
        $user->update([
            'verified' => true,
            'verification_token' => null
        ]);

        return redirect('/login')->with('success', 'Your email has been verified!');
    }
}
