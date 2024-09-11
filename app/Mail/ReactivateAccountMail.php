<?php

namespace App\Mail;

use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReactivateAccountMail
    extends Mailable
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
        $restoreLink = url("/reactivate-account?token={$this->token}&email={$this->email}");
        return $this ->from('toolan@admin.com')
            ->subject('Reactivate your account')
            ->view('mail.deactivated_mail')
            ->with([
                'restoreLink' => $restoreLink,
                'name'=> $name
            ]);
    }
}
