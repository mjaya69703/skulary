<?php

namespace App\Services\System;

use App\Models\User;
use App\Mail\Auth\WelcomeMail;
use App\Mail\Auth\VerifyEmailMail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    /**
     * Attempt login with flexible field detection (email, phone, or username).
     *
     * @param string $input The login identifier (email, phone, or username)
     * @param string $password The password
     * @param bool $remember Whether to remember the user
     * @return array ['success' => bool, 'message' => string, 'user' => User|null]
     */
    public function attemptLogin(string $input, string $password, bool $remember = false): array
    {
        try {
            // Determine field type: email, phone, or username
            $fieldType = $this->detectFieldType($input);

            // Attempt authentication with the detected field type
            if (Auth::attempt([$fieldType => $input, 'password' => $password], $remember)) {
                $user = Auth::user();
                return [
                    'success' => true,
                    'message' => 'Login successful!',
                    'user' => $user,
                ];
            }

            // Check if user exists to provide specific error message
            $userExists = User::where($fieldType, $input)->exists();

            if ($userExists) {
                return [
                    'success' => false,
                    'message' => 'The provided password is incorrect.',
                    'field' => 'password',
                    'user' => null,
                ];
            }

            return [
                'success' => false,
                'message' => 'No account found with the provided credentials.',
                'field' => 'login',
                'user' => null,
            ];
        } catch (\Throwable $th) {
            Log::error('AuthService Login Error: ' . $th->getMessage(), [
                'input' => $input,
                'exception' => $th,
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred during login. Please try again later.',
                'field' => 'error',
                'user' => null,
            ];
        }
    }

    /**
     * Detect the field type based on input format.
     *
     * @param string $input The login identifier
     * @return string The field type: 'email', 'phone', or 'username'
     */
    private function detectFieldType(string $input): string
    {
        // Check if input is a valid email
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        // Check if input is a phone number (digits, +, -, spaces, parentheses)
        if (preg_match('/^[0-9+\-\s()]+$/', $input)) {
            return 'phone';
        }

        // Default to username
        return 'username';
    }

    /**
     * Get user by login identifier (email, phone, or username).
     *
     * @param string $input The login identifier
     * @return User|null
     */
    public function getUserByIdentifier(string $input): ?User
    {
        $fieldType = $this->detectFieldType($input);
        return User::where($fieldType, $input)->first();
    }

    /**
     * Check if a user exists with the given identifier.
     *
     * @param string $input The login identifier
     * @return bool
     */
    public function userExists(string $input): bool
    {
        $fieldType = $this->detectFieldType($input);
        return User::where($fieldType, $input)->exists();
    }

    /**
     * Register a new user with validation.
     *
     * @param array $data Array with keys: name, email, phone, password
     * @return array ['success' => bool, 'message' => string, 'user' => User|null]
     */
    public function register(array $data): array
    {
        try {
            // Create new user with hashed password
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'code' => uniqid(),
                'username' => $this->generateUsername($data['email']),
                'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
            ]);

            Role::findByName('peserta-ppdb')->users()->attach($user->id);

            return [
                'success' => true,
                'message' => 'Account created successfully! Please sign in.',
                'user' => $user,
            ];
        } catch (\Throwable $th) {
            Log::error('AuthService Registration Error: ' . $th->getMessage(), [
                'data' => $data,
                'exception' => $th,
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred during registration. Please try again later.',
                'user' => null,
            ];
        }
    }

    /**
     * Generate a unique username from email.
     *
     * @param string $email
     * @return string
     */
    private function generateUsername(string $email): string
    {
        // Extract username part from email and append random number if taken
        $baseUsername = explode('@', $email)[0];
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Get user roles.
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserRoles(User $user)
    {
        return $user->roles;
    }

    /**
     * Check if user has multiple roles.
     *
     * @param User $user
     * @return bool
     */
    public function hasMultipleRoles(User $user): bool
    {
        return $user->roles()->count() > 1;
    }

    /**
     * Get user's single role (if only one role).
     *
     * @param User $user
     * @return \Spatie\Permission\Models\Role|null
     */
    public function getSingleRole(User $user)
    {
        if ($user->roles()->count() === 1) {
            return $user->roles()->first();
        }
        return null;
    }

    /**
     * Send welcome email to newly registered user.
     *
     * @param User $user
     * @return bool
     */
    public function sendWelcomeEmail(User $user): bool
    {
        try {
            Mail::send(new WelcomeMail($user));
            return true;
        } catch (\Throwable $th) {
            Log::error('Failed to send welcome email: ' . $th->getMessage(), [
                'user_id' => $user->id,
                'exception' => $th,
            ]);
            return false;
        }
    }

    /**
     * Save user biodata from first setup.
     *
     * @param User $user
     * @param array $data Biodata from FirstSetupRequest
     * @return bool
     */
    public function saveBiodata(User $user, array $data): bool
    {
        try {
            // Create or update biodata
            $user->biodata()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_depan' => $data['nama_depan'],
                    'nama_belakang' => $data['nama_belakang'] ?? null,
                    'tempat_lahir' => $data['tempat_lahir'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'agama' => $data['agama'] ?? null,
                    'gol_darah' => $data['gol_darah'] ?? null,
                    'tinggi_badan' => $data['tinggi_badan'] ?? null,
                    'berat_badan' => $data['berat_badan'] ?? null,
                ]
            );

            return true;
        } catch (\Throwable $th) {
            Log::error('Failed to save biodata: ' . $th->getMessage(), [
                'user_id' => $user->id,
                'exception' => $th,
            ]);
            return false;
        }
    }

    /**
     * Send email verification link.
     *
     * @param User $user
     * @return bool
     */
    public function sendVerificationEmail(User $user): bool
    {
        try {
            $verificationToken = \Illuminate\Support\Str::random(64);
            
            // Cache token for 24 hours
            \Illuminate\Support\Facades\Cache::put(
                'email_verification_' . $user->id,
                $verificationToken,
                now()->addDay()
            );

            $verificationLink = route('auth.verify-email', [
                'user_id' => $user->id,
                'token' => $verificationToken,
            ]);

            Mail::send(new VerifyEmailMail($user, $verificationLink));
            
            return true;
        } catch (\Throwable $th) {
            Log::error('Failed to send verification email: ' . $th->getMessage(), [
                'user_id' => $user->id,
                'exception' => $th,
            ]);
            return false;
        }
    }

    /**
     * Verify email token.
     *
     * @param User $user
     * @param string $token
     * @return bool
     */
    public function verifyEmail(User $user, string $token): bool
    {
        try {
            $cachedToken = \Illuminate\Support\Facades\Cache::get('email_verification_' . $user->id);

            if (!$cachedToken || $cachedToken !== $token) {
                return false;
            }

            // Update user email_verified_at and fst_setup
            // fst_setup = 0 berarti setup sudah selesai/tidak aktif
            $user->update([
                'email_verified_at' => now(),
                'fst_setup' => 0,
            ]);

            // Clear cached token
            \Illuminate\Support\Facades\Cache::forget('email_verification_' . $user->id);

            return true;
        } catch (\Throwable $th) {
            Log::error('Failed to verify email: ' . $th->getMessage(), [
                'user_id' => $user->id,
                'exception' => $th,
            ]);
            return false;
        }
    }
}
