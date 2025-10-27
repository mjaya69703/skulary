<?php

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ForgotPasswordMail extends Mailable
{
    public function __construct(
        public User $user,
        public string $resetLink,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->user->email,
            subject: 'Reset Your Password - Skulary',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.forgot-password',
            with: [
                'user' => $this->user,
                'resetLink' => $this->resetLink,
            ]
        );
    }
}
