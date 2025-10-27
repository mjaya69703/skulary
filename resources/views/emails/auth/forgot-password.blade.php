@extends('emails.email-core')

@section('email-content')
    <div class="email-header">
        <h1>Reset Your Password</h1>
        <p>Skulary Account Password Reset Request</p>
    </div>

    <div class="email-body">
        <h2>Hello {{ $user->name }},</h2>

        <p>We received a request to reset the password for your account associated with this email address.</p>

        <p>Click the button below to reset your password. This link will expire in <strong>1 hour</strong>.</p>

        <div style="text-align: center;">
            <a href="{{ $resetLink }}" class="email-button">Reset Password</a>
        </div>

        <p class="text-muted">Or copy and paste this link in your browser:</p>
        <div class="email-code">{{ $resetLink }}</div>

        <div class="warning">
            <strong>⚠️ Security Notice:</strong> If you did not request this password reset, please ignore this email or <a href="mailto:support@skulary.com" style="color: inherit; text-decoration: underline;">contact support</a> immediately.
        </div>

        <p class="text-muted">For your security, never share your password or this link with anyone.</p>
    </div>
@endsection
