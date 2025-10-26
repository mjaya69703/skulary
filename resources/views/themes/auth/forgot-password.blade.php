@extends('themes.auth-core')
@section('content')
    <div>
        <div class="mb-8 sm:mb-10">
            <h1 class="mb-3 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md">
                Forgot Password
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Enter your email address and we'll send you a link to reset your password.
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

        <div>
            <form action="{{ route('auth.send-reset-link') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Email Address<span class="text-error-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            placeholder="Enter your email address" 
                            value="{{ old('email') }}" 
                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 @error('email') border-error-500 dark:border-error-500/30 @enderror" 
                            required
                        />
                        @error('email')
                            <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Button -->
                    <div>
                        <button type="submit" class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                            Send Reset Link
                        </button>
                    </div>

                    <!-- Back to Login -->
                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Remember your password?
                            <a href="{{ route('auth.signin-index') }}" class="text-brand-500 hover:text-brand-600 dark:text-brand-400 font-medium">
                                Sign In
                            </a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
