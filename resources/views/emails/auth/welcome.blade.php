@extends('emails.email-core')

@section('email-content')
    <div class="email-header">
        <h1>Welcome to Skulary! 🎉</h1>
        <p>Your account has been created successfully</p>
    </div>

    <div class="email-body">
        <h2>Hello {{ $user->name }},</h2>

        <p>Thank you for registering with Skulary! Your account has been created and is ready to use.</p>

        <h3 style="font-size: 16px; color: #1f2937; margin-top: 20px; margin-bottom: 10px;">Account Information:</h3>
        <div style="background-color: #f3f4f6; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
            <p style="margin-bottom: 8px;"><strong>Email:</strong> {{ $user->email }}</p>
            <p style="margin-bottom: 8px;"><strong>Username:</strong> {{ $user->username }}</p>
            <p><strong>Account Created:</strong> {{ $user->created_at->format('F j, Y H:i') }}</p>
        </div>

        <p>You can now log in to your account using your email, username, or phone number along with your password.</p>

        <div style="text-align: center;">
            <a href="{{ $loginUrl }}" class="email-button">Go to Login</a>
        </div>

        <h3 style="font-size: 16px; color: #1f2937; margin-top: 20px; margin-bottom: 10px;">Getting Started:</h3>
        <ul style="margin-left: 20px; margin-bottom: 20px;">
            <li style="margin-bottom: 8px;">Complete your profile information</li>
            <li style="margin-bottom: 8px;">Set up your preferences</li>
            <li style="margin-bottom: 8px;">Explore the available features</li>
        </ul>

        <div class="warning">
            <strong>🔒 Security Reminder:</strong> Never share your password with anyone. Skulary support will never ask for your password.
        </div>

        <p class="text-muted" style="margin-top: 20px;">If you have any questions or need assistance, please don't hesitate to <a href="mailto:support@skulary.com" style="color: #667eea; text-decoration: underline;">contact our support team</a>.</p>
    </div>
@endsection
