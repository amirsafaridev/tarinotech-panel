<?php

namespace Modules\Auth\Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mews\Captcha\Facades\Captcha;
use Modules\Admin\app\Models\Admin;
use Modules\Admin\database\factories\AdminFactory;
use Route;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if the admin login route exists.
     */
    public function test_admin_login_route_exists(): void
    {
        $this->assertTrue(Route::has('auth.admin.login'));
    }

    /**
     * Test validation errors for empty email and password fields.
     */
    public function test_email_and_password_validation_errors(): void
    {
        $response = $this->post(route('auth.admin.login.submit'), [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(302);

        $response->assertSessionHasErrors(['email', 'password']);

        $response->assertSessionMissing(config('auth.otp_code_session_key'));
    }

    /**
     * Test a blocked admin cannot log in.
     */
    public function test_blocked_admin_cannot_login(): void
    {
        Captcha::shouldReceive('check')->andReturn(true);

        $admin = $this->createAdmin([
            'is_block' => true,
        ]);

        $response = $this->post(route('auth.admin.login.submit'), [
            'email' => $admin->email,
            'password' => 'secret',
            'captcha' => '1234',
        ]);

        $response->assertRedirect(route('auth.admin.login'))
            ->assertSessionHas('error');
    }

    /**
     * Test a valid admin can log in and is redirected to the verify page.
     */
    public function test_admin_can_login_and_redirect_to_verify(): void
    {
        Captcha::shouldReceive('check')->andReturn(true);

        $admin = $this->createAdmin([
            'is_block' => false,
        ]);

        $response = $this->post(route('auth.admin.login.submit'), [
            'email' => $admin->email,
            'password' => 'secret',
            'captcha' => '1234',
        ]);

        $response->assertRedirect(route('auth.admin.verify'));

        $this->assertDatabaseHas('otp_codes', [
            'identify' => $admin->email,
            'user_type' => $admin::class,
            'user_id' => $admin->id,
            'code' => config('auth.development_otp'),
        ]);

        $response->assertSessionHas(config('auth.otp_code_session_key'), $admin->email);
    }

    /**
     * Helper to create an admin with optional overrides.
     */
    protected function createAdmin(array $overrides = []): Admin
    {
        return AdminFactory::new()->create(array_merge([
            'email' => 'admin@admin.com',
            'password' => bcrypt('secret'),
        ], $overrides));
    }
}
