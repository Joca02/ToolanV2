<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UserVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build(): UserVerificationMail
    {
        $verificationUrl = route('verification.verify', $this->user->verification_token);
        Log::info("Sending verification mail to: {$this->user->email}");
        return $this->from('toolan@admin.com')
            ->subject('Verify Your Email Address')
            ->view("mail.verify")
            ->with([
                'name' => $this->user->name,
                'verificationUrl' => $verificationUrl,
            ]);
    }
}
