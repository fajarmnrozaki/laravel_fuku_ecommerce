<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;

class GenerateOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS'), 'Ecommerce App'),
            subject: 'Generate Ulang OTP Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.generate-otp',
            with: [
                'name' => $this->user->name,
                'otp'  => $this->user->otpcode->otp,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
