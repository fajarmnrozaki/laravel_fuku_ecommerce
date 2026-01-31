<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this
            ->subject('Email Verification OTP')
            ->view('mail.register-otp')
            ->with([
                'name' => $this->user->name,
                'otp'  => $this->user->otpcode->otp,
            ]);
    }
}
