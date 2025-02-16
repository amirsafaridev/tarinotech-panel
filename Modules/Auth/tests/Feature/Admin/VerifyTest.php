<?php

namespace Modules\Auth\Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Admin\app\Models\Admin;
use Modules\Admin\database\factories\AdminFactory;
use Route;
use Tests\TestCase;

class VerifyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if the admin verify route exists.
     */
    public function test_admin_verify_route_exists(): void
    {
        $this->assertTrue(Route::has('auth.admin.verify'));
    }

    /**
     * Test redirect to log in if session OTP key is not set.
     */
    public function test_redirects_to_login_if_otp_key_not_set(): void
    {
        $response = $this->get(route('auth.admin.verify'));

        $response->assertStatus(302)
            ->assertRedirect(route('auth.admin.login'));
    }

    /**
     * Test admin can verify and log in with a valid OTP code.
     */
    public function test_admin_can_verify_and_login_with_valid_code(): void
    {
        $admin = $this->createAdminWithOtp('1234');

        $response = $this->withSession([config('auth.otp_code_session_key') => $admin->email])
            ->post(route('auth.admin.verify.submit'), ['code' => '1234']);

        $response->assertStatus(302)
            ->assertRedirect(route('admin.dashboard.index'));
    }

    /**
     * Test admin cannot log in with an invalid OTP code.
     */
    public function test_admin_cannot_login_with_invalid_code(): void
    {
        $admin = $this->createAdminWithOtp('4321');

        $response = $this->withSession([config('auth.otp_code_session_key') => $admin->email])
            ->post(route('auth.admin.verify.submit'), ['code' => '1234']);

        $response->assertStatus(302)
            ->assertRedirect(route('auth.admin.verify'))
            ->assertSessionHas('error');
    }

    protected function createAdminWithOtp(string $otpCode): Admin
    {
        $admin = AdminFactory::new()->create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('secret'),
            'is_block' => false,
        ]);

        $admin->otpCodes()->create([
            'code' => $otpCode,
            'identify' => $admin->email,
            'ip' => '127.0.0.1',
            'agent' => '',
            'expired_at' => now()->addMinutes(10),
        ]);

        return $admin;
    }
}
