<?php

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class VerifyEmailMail extends Mailable
{
    public function __construct(
        public User $user,
        public string $verificationLink,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->user->email,
            subject: 'Verify Your Email Address - Skulary',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.verify-email',
            with: [
                'user' => $this->user,
                'verificationLink' => $this->verificationLink,
            ]
        );
    }
}
