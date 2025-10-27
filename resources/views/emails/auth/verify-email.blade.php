@extends('emails.email-core')

@section('email-content')
    <div class="email-header">
        <h1>Verify Your Email Address</h1>
        <p>Complete your account setup on Skulary</p>
    </div>

    <div class="email-body">
        <h2>Hello {{ $user->name }},</h2>

        <p>Thank you for completing your profile information! The last step is to verify your email address.</p>

        <p>Click the button below to confirm your email address:</p>

        <div style="text-align: center;">
            <a href="{{ $verificationLink }}" class="email-button">Verify Email Address</a>
        </div>

        <p class="text-muted">Or copy and paste this link in your browser:</p>
        <div class="email-code">{{ $verificationLink }}</div>

        <p>This link will expire in <strong>24 hours</strong>.</p>

        <div class="warning">
            <strong>⚠️ Important:</strong> If you did not create this account, please ignore this email or <a href="mailto:support@skulary.com" style="color: inherit; text-decoration: underline;">contact support</a>.
        </div>

        <p class="text-muted">Once verified, you'll have full access to your Skulary account.</p>
    </div>
@endsection
