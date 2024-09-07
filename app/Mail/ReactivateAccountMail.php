<?php

namespace App\Mail;

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
        $restoreLink = url("/reactivate-account?token={$this->token}&email={$this->email}");
        return $this->subject('Reactivate your account')
            ->view('mail.deactivated_mail')
            ->with([
                'restoreLink' => $restoreLink,
            ]);
    }
}
