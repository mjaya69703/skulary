<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
// SRP Modules
use App\Http\Requests\System\AuthRequest;
use App\Http\Requests\System\RegisterRequest;
use App\Services\System\AuthService;
use App\Mail\Auth\ForgotPasswordMail;
// Use Models
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        $data['pages'] = 'Login Page';
        $data['menus'] = 'Auth Menu';

        return view('themes.auth.signin-index', $data);
    }

    public function register()
    {
        $data['pages'] = 'Register Page';
        $data['menus'] = 'Auth Menu';

        return view('themes.auth.signup-index', $data);
    }

    public function chooseRole(AuthService $authService)
    {
        $user = Auth::user();
        $roles = $authService->getUserRoles($user);
        
        $data['pages'] = 'Gateway Page';
        $data['menus'] = 'Auth Menu';
        $data['roles'] = $roles;

        return view('themes.auth.signin-gateway', $data);
    }

    public function setRole(Request $request, AuthService $authService)
    {
        $user = Auth::user();
        $selectedRole = $request->input('role');

        // Validate that the selected role belongs to the user
        $roleExists = $user->roles()->where('name', $selectedRole)->exists();

        if (!$roleExists) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Invalid role selected.']);
        }

        // Set the active role in session
        session(['active_role' => $selectedRole]);

        return redirect()->intended(route($selectedRole . '.blank-index'))->with('success', 'Role selected successfully!');
    }

    public function handleSignIn(AuthRequest $request, AuthService $authService)
    {
        $input = $request->input('login');
        $password = $request->input('password');
        $remember = $request->has('remember');

        // Use AuthService to attempt login
        $result = $authService->attemptLogin($input, $password, $remember);

        if ($result['success']) {
            $request->session()->regenerate();
            
            $user = $result['user'];
            
            // Check if user has multiple roles
            if ($authService->hasMultipleRoles($user)) {
                // Redirect to role selection gateway
                return redirect()->route('auth.gateway-choose');
            } else {
                // Get single role and set as active role
                $singleRole = $authService->getSingleRole($user);
                if ($singleRole) {
                    session(['active_role' => $singleRole->name]);
                }
                return redirect()->intended(route($singleRole->name . '.blank-index'))->with('success', $result['message']);
            }
        }

        // Return back with specific error
        return redirect()
            ->back()
            ->withErrors([$result['field'] => $result['message']])
            ->withInput();
    }

    public function handleSignUp(RegisterRequest $request, AuthService $authService)
    {
        // Prepare data from validated request
        $data = $request->only(['name', 'email', 'phone', 'password']);

        // Use AuthService to register user
        $result = $authService->register($data);

        if ($result['success']) {
            // Send welcome email
            $authService->sendWelcomeEmail($result['user']);
            
            return redirect()->route('auth.signin-index')->with('success', 'Account created successfully! Check your email for welcome message. Please sign in.');
        }

        // Return back with error
        return redirect()
            ->back()
            ->withErrors(['error' => $result['message']])
            ->withInput();
    }

    public function logout(Request $request)
    {
        // Clear active role session
        session()->forget('active_role');

        // Logout user
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();

        // Regenerate token
        $request->session()->regenerateToken();

        return redirect()->route('auth.signin-index')->with('success', 'Logged out successfully!');
    }

    public function forgotPassword()
    {
        $data['pages'] = 'Forgot Password Page';
        $data['menus'] = 'Auth Menu';

        return view('themes.auth.forgot-password', $data);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Email must be valid',
            'email.exists' => 'Email not found in our system',
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->firstOrFail();

        // Generate reset token
        $token = Str::random(64);

        // Store token in cache for 1 hour
        \Illuminate\Support\Facades\Cache::put(
            'password_reset_' . $email,
            $token,
            now()->addHour()
        );

        // Create reset link
        $resetLink = route('auth.reset-password', ['token' => $token]);

        // Send email
        try {
            Mail::send(new ForgotPasswordMail($user, $resetLink));
            
            return redirect()
                ->back()
                ->with('success', 'Password reset link has been sent to your email address. Please check your inbox.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send reset email: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to send reset email. Please try again later.']);
        }
    }

    public function resetPassword(Request $request, $token)
    {
        $data['pages'] = 'Reset Password Page';
        $data['menus'] = 'Auth Menu';
        $data['token'] = $token;

        return view('themes.auth.reset-password', $data);
    }

    public function handleResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $email = $request->input('email');
        $token = $request->input('token');
        $password = $request->input('password');

        // Verify token
        $cachedToken = \Illuminate\Support\Facades\Cache::get('password_reset_' . $email);

        if (!$cachedToken || $cachedToken !== $token) {
            return redirect()
                ->back()
                ->withErrors(['token' => 'Invalid or expired reset link']);
        }

        // Update password
        $user = User::where('email', $email)->firstOrFail();
        $user->update(['password' => Hash::make($password)]);

        // Delete cached token
        \Illuminate\Support\Facades\Cache::forget('password_reset_' . $email);

        return redirect()
            ->route('auth.signin-index')
            ->with('success', 'Password reset successfully! Please login with your new password.');
    }
}
