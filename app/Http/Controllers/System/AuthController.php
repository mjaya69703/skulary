<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// SRP Modules
use App\Http\Requests\System\AuthRequest;
use App\Http\Requests\System\RegisterRequest;
use App\Services\System\AuthService;
// Use Models
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        $data['pages'] = 'Authentication';
        $data['menus'] = 'Auth Menu';

        return view('themes.auth.signin-index', $data);
    }

    public function register()
    {
        $data['pages'] = 'Authentication';
        $data['menus'] = 'Auth Menu';

        return view('themes.auth.signup-index', $data);
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
            return redirect()->intended(route('blank-index'))->with('success', $result['message']);
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
            return redirect()->route('auth.login')->with('success', $result['message']);
        }

        // Return back with error
        return redirect()
            ->back()
            ->withErrors(['error' => $result['message']])
            ->withInput();
    }
}
