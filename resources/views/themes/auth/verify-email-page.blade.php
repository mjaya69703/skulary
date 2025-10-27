@extends('themes.auth-core')
@section('content')
    <div>
        <div class="mb-8 sm:mb-10">
            <h1 class="mb-3 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md">
                Verify Your Email
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                We've sent a verification link to {{ $user->email }}
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-error-500/10 border border-error-500/20 dark:bg-error-500/5">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-error-500">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 p-4 rounded-lg bg-success-500/10 border border-success-500/20 dark:bg-success-500/5">
                <p class="text-sm text-success-500">{{ session('success') }}</p>
            </div>
        @endif

        <div class="space-y-6">
            <div class="p-6 rounded-lg border-2 border-brand-500/20 bg-brand-50 dark:bg-brand-500/10">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-brand-900 dark:text-white">Check your email</h3>
                        <p class="mt-2 text-sm text-brand-800 dark:text-gray-300">
                            We've sent a verification link to <strong>{{ $user->email }}</strong>. Click the link in the email to verify your account.
                        </p>
                        <p class="mt-3 text-xs text-brand-700 dark:text-gray-400">
                            💡 Tip: Check your spam folder if you don't see the email.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Resend Email Form -->
            <div>
                <p class="mb-3 text-sm text-gray-600 dark:text-gray-400">
                    Didn't receive the email?
                </p>
                <form action="{{ route('auth.send-verification-email') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-brand-600 border-2 border-brand-600 rounded-lg hover:bg-brand-50 dark:text-brand-400 dark:border-brand-400 dark:hover:bg-brand-500/10 transition">
                        Resend Verification Email
                    </button>
                </form>
            </div>

            <!-- Logout -->
            <div>
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-gray-600 border-2 border-gray-300 rounded-lg hover:bg-gray-50 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-500/10 transition">
                        Back to Login
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
