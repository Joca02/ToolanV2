<?php

namespace App\Mail;

use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        $name=UserService::getUsersNameByEmail($this->email);
        $resetLink = url("/reset-password?token={$this->token}&email={$this->email}");
        return $this ->from('toolan@admin.com')
            ->subject('Reset Your Password')
            ->view('mail.password_reset')
            ->with([
                'resetLink' => $resetLink,
                'name' => $name
            ]);
    }
}

