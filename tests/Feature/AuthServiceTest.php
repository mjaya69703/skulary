<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Services\System\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock all emails
        Mail::fake();
        
        // Create roles fresh untuk setiap test
        $this->createRoles();
        
        $this->authService = new AuthService();
    }

    /**
     * Helper method untuk create roles
     */
    private function createRoles(): void
    {
        foreach (['admin', 'guru', 'siswa', 'peserta-ppdb', 'parents'] as $roleName) {
            Role::create(['name' => $roleName, 'guard_name' => 'web']);
        }
    }

    /** @test */
    public function test_attempt_login_with_email()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'username' => 'testuser123',
            'phone' => '+628112345670',
            'password' => Hash::make('password123'),
        ]);

        $result = $this->authService->attemptLogin('test@example.com', 'password123');

        $this->assertTrue($result['success']);
        $this->assertEquals($user->id, $result['user']->id);
        $this->assertEquals('Login successful!', $result['message']);
    }

    /** @test */
    public function test_attempt_login_with_wrong_password()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'username' => 'testuser456',
            'phone' => '+628112345671',
            'password' => Hash::make('password123'),
        ]);

        $result = $this->authService->attemptLogin('test@example.com', 'wrongpassword');

        $this->assertFalse($result['success']);
        $this->assertEquals('The provided password is incorrect.', $result['message']);
        $this->assertNull($result['user']);
    }

    /** @test */
    public function test_attempt_login_with_non_existent_user()
    {
        $result = $this->authService->attemptLogin('nonexistent@example.com', 'password123');

        $this->assertFalse($result['success']);
        $this->assertNull($result['user']);
    }

    /** @test */
    public function test_attempt_login_with_phone()
    {
        $user = User::factory()->create([
            'phone' => '+628123456789',
            'username' => 'phonetestuser',
            'email' => 'phone@example.com',
            'password' => Hash::make('password123'),
        ]);

        $result = $this->authService->attemptLogin('+628123456789', 'password123');

        $this->assertTrue($result['success']);
        $this->assertEquals($user->id, $result['user']->id);
    }

    /** @test */
    public function test_register_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+628123456789',
            'password' => 'SecurePass123!',
        ];

        $result = $this->authService->register($data);

        $this->assertTrue($result['success']);
        $this->assertNotNull($result['user']);
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);
    }

    /** @test */
    public function test_user_has_multiple_roles()
    {
        $user = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->first();
        $guruRole = Role::where('name', 'guru')->first();

        $user->assignRole($adminRole, $guruRole);

        $this->assertTrue($this->authService->hasMultipleRoles($user));
    }

    /** @test */
    public function test_user_has_single_role()
    {
        $user = User::factory()->create();
        $siswaRole = Role::where('name', 'siswa')->first();

        $user->assignRole($siswaRole);

        $this->assertFalse($this->authService->hasMultipleRoles($user));
    }

    /** @test */
    public function test_get_user_roles()
    {
        $user = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->first();
        $guruRole = Role::where('name', 'guru')->first();

        $user->assignRole($adminRole, $guruRole);

        $roles = $this->authService->getUserRoles($user);

        $this->assertCount(2, $roles);
        $this->assertTrue($roles->contains('name', 'admin'));
        $this->assertTrue($roles->contains('name', 'guru'));
    }

    /** @test */
    public function test_get_single_role()
    {
        $user = User::factory()->create();
        $siswaRole = Role::where('name', 'siswa')->first();

        $user->assignRole($siswaRole);

        $singleRole = $this->authService->getSingleRole($user);

        $this->assertNotNull($singleRole);
        $this->assertEquals('siswa', $singleRole->name);
    }

    /** @test */
    public function test_save_biodata()
    {
        $user = User::factory()->create();
        $biodata = [
            'nama_depan' => 'John',
            'nama_belakang' => 'Doe',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-15',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'gol_darah' => 'O',
            'tinggi_badan' => 170,
            'berat_badan' => 70,
        ];

        $result = $this->authService->saveBiodata($user, $biodata);

        $this->assertTrue($result);
        $this->assertDatabaseHas('biodatas', [
            'user_id' => $user->id,
            'nama_depan' => 'John',
            'jenis_kelamin' => 'L',
        ]);
    }

    /** @test */
    public function test_save_biodata_with_partial_data()
    {
        $user = User::factory()->create();
        $biodata = [
            'nama_depan' => 'Jane',
            'nama_belakang' => null,
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1995-05-20',
            'jenis_kelamin' => 'P',
            'agama' => null,
            'gol_darah' => null,
            'tinggi_badan' => null,
            'berat_badan' => null,
        ];

        $result = $this->authService->saveBiodata($user, $biodata);

        $this->assertTrue($result);
        $this->assertDatabaseHas('biodatas', [
            'user_id' => $user->id,
            'nama_depan' => 'Jane',
            'nama_belakang' => null,
        ]);
    }

    /** @test */
    public function test_send_welcome_email()
    {
        Mail::fake();

        $user = User::factory()->create();

        $result = $this->authService->sendWelcomeEmail($user);

        $this->assertTrue($result);
        Mail::assertSent(\App\Mail\Auth\WelcomeMail::class);
    }

    /** @test */
    public function test_send_verification_email()
    {
        Mail::fake();

        $user = User::factory()->create();

        $result = $this->authService->sendVerificationEmail($user);

        $this->assertTrue($result);
        Mail::assertSent(\App\Mail\Auth\VerifyEmailMail::class);
        
        // Verify token is cached
        $cachedToken = Cache::get('email_verification_' . $user->id);
        $this->assertNotNull($cachedToken);
    }

    /** @test */
    public function test_verify_email_with_correct_token()
    {
        $user = User::factory()->create(['fst_setup' => 1]);
        $token = 'valid_token_123';

        // Store token in cache
        Cache::put('email_verification_' . $user->id, $token, now()->addDay());

        $result = $this->authService->verifyEmail($user, $token);

        $this->assertTrue($result);
        $this->assertTrue($user->fresh()->email_verified_at !== null);
        $this->assertEquals(0, $user->fresh()->fst_setup);
        
        // Token should be cleared from cache
        $this->assertNull(Cache::get('email_verification_' . $user->id));
    }

    /** @test */
    public function test_verify_email_with_invalid_token()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'fst_setup' => 1,
        ]);
        $validToken = 'valid_token_123';

        Cache::put('email_verification_' . $user->id, $validToken, now()->addDay());

        $result = $this->authService->verifyEmail($user, 'invalid_token');

        $this->assertFalse($result);
        $this->assertNull($user->fresh()->email_verified_at);
        $this->assertEquals(1, $user->fresh()->fst_setup);
    }

    /** @test */
    public function test_verify_email_with_expired_token()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'fst_setup' => 1,
        ]);

        // Token is not in cache (expired)
        $result = $this->authService->verifyEmail($user, 'some_token');

        $this->assertFalse($result);
        $this->assertNull($user->fresh()->email_verified_at);
    }

    /** @test */
    public function test_get_user_by_identifier_email()
    {
        $user = User::factory()->create(['email' => 'john@example.com']);

        $foundUser = $this->authService->getUserByIdentifier('john@example.com');

        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    /** @test */
    public function test_get_user_by_identifier_phone()
    {
        $user = User::factory()->create(['phone' => '+628123456789']);

        $foundUser = $this->authService->getUserByIdentifier('+628123456789');

        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    /** @test */
    public function test_get_user_by_identifier_username()
    {
        $user = User::factory()->create(['username' => 'johndoe']);

        $foundUser = $this->authService->getUserByIdentifier('johndoe');

        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    /** @test */
    public function test_complete_auth_flow_with_single_role()
    {
        // 1. Register user
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'phone' => '+628123456789',
            'password' => 'SecurePass123!',
        ];
        $registerResult = $this->authService->register($data);
        $this->assertTrue($registerResult['success']);
        $user = $registerResult['user'];

        // 2. User automatically gets peserta-ppdb role from register, so now has 1 role
        // Check that hasMultipleRoles is false (only 1 role)
        $this->assertFalse($this->authService->hasMultipleRoles($user));

        // 3. Get single role (peserta-ppdb)
        $singleRole = $this->authService->getSingleRole($user);
        $this->assertEquals('peserta-ppdb', $singleRole->name);

        // 4. Save biodata
        $biodata = [
            'nama_depan' => 'Test',
            'nama_belakang' => 'User',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'gol_darah' => 'O',
            'tinggi_badan' => 175,
            'berat_badan' => 70,
        ];
        $biodataResult = $this->authService->saveBiodata($user, $biodata);
        $this->assertTrue($biodataResult);

        // 5. Send verification email
        $verifyResult = $this->authService->sendVerificationEmail($user);
        $this->assertTrue($verifyResult);

        // 6. Verify email
        $token = Cache::get('email_verification_' . $user->id);
        $verifiedResult = $this->authService->verifyEmail($user, $token);
        $this->assertTrue($verifiedResult);
        $this->assertEquals(0, $user->fresh()->fst_setup);
    }

    /** @test */
    public function test_complete_auth_flow_with_multiple_roles()
    {
        // 1. Register user (automatically gets peserta-ppdb role)
        $data = [
            'name' => 'Multi Role User',
            'email' => 'multiuser@example.com',
            'phone' => '+628987654321',
            'password' => 'SecurePass123!',
        ];
        $registerResult = $this->authService->register($data);
        $user = $registerResult['user'];

        // 2. Assign additional roles (admin + guru)
        // Now user has: peserta-ppdb, admin, guru = 3 roles total
        $adminRole = Role::where('name', 'admin')->first();
        $guruRole = Role::where('name', 'guru')->first();
        $user->assignRole($adminRole, $guruRole);
        $user = $user->fresh(); // Refresh to pick up role changes

        // 3. Check for multiple roles (should be true, has 3 roles now)
        $this->assertTrue($this->authService->hasMultipleRoles($user));

        // 4. Get all roles (should have 3)
        $roles = $this->authService->getUserRoles($user);
        $this->assertCount(3, $roles);

        // 5. Save biodata
        $biodata = [
            'nama_depan' => 'Multi',
            'nama_belakang' => 'Role',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1998-05-10',
            'jenis_kelamin' => 'P',
            'agama' => 'Kristen',
            'gol_darah' => 'A',
            'tinggi_badan' => 160,
            'berat_badan' => 55,
        ];
        $biodataResult = $this->authService->saveBiodata($user, $biodata);
        $this->assertTrue($biodataResult);

        // 6. Send verification email
        $verifyResult = $this->authService->sendVerificationEmail($user);
        $this->assertTrue($verifyResult);

        // 7. Verify email
        $token = Cache::get('email_verification_' . $user->id);
        $verifiedResult = $this->authService->verifyEmail($user, $token);
        $this->assertTrue($verifiedResult);
        $this->assertEquals(0, $user->fresh()->fst_setup);
    }
}
