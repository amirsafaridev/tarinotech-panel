<?php

namespace Tests\Feature\Controller\Admin;

use App\Enums\Database\Company\CompanyType;
use App\Enums\Database\User\IrnicStatus;
use App\Enums\Database\User\PersonType;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_user()
    {
        Admin::factory(1)->create();
        $admin = Admin::first();
        $this->actingAs($admin, 'admin');
        $this->post(route('admin.user.store'), [
            ...$this->userAttribute(),
            'person_type' => PersonType::Person,
            'irnic_status' => IrnicStatus::DoesNotNeed,
        ])->assertOk();

        $this->userAddressTest();
    }

    public function test_admin_can_create_user_legal()
    {
        Admin::factory(1)->create();
        $admin = Admin::first();
        $this->actingAs($admin, 'admin');
        $this->post(route('admin.user.store'), [
            ...$this->userAttribute(),
            'person_type' => PersonType::Legal,
            'irnic_status' => IrnicStatus::DoesNotNeed,
            'company_name' => 'کمپانی گوگل',
            'company_identify' => '12345678',
            'company_register_id' => '12345678',
            'company_type' => CompanyType::PublicStock,
        ])->assertOk();

        $this->userAddressTest();
        $this->userCompanyTest();
    }

    public function test_admin_can_create_user_have_irnic()
    {
        Admin::factory(1)->create();
        $admin = Admin::first();
        $this->actingAs($admin, 'admin');
        $this->post(route('admin.user.store'), [
            ...$this->userAttribute(),
            'person_type' => PersonType::Person,
            'irnic_status' => IrnicStatus::HasIt,
            'irnic_identify' => '12345678',
            'irnic_password' => '12345678',
        ])->assertOk();

        $this->userAddressTest();
        $this->userIrnicTest();
    }

    private function userAddressTest()
    {
        $this->assertDatabaseHas('addresses', [
            'address' => 'آدرس شرکت',
            'postal_code' => '33616',
            'user_id' => 1,
        ]);
    }

    private function userIrnicTest()
    {
        $this->assertDatabaseHas('irnics', [
            'status' => IrnicStatus::HasIt,
            'identify' => '12345678',
            'user_id' => 1,
        ]);
    }

    private function userCompanyTest()
    {
        $this->assertDatabaseHas('companies', [
            'name' => 'کمپانی گوگل',
            'identify' => '12345678',
            'register_id' => '12345678',
            'type' => CompanyType::PublicStock,
            'user_id' => 1,
        ]);
    }

    private function userAttribute(): array
    {
        return [
            'first_name' => 'علی',
            'last_name' => 'موسوی',
            'en_first_name' => 'Ali',
            'en_last_name' => 'Mousavi',
            'father_name' => 'رضا',
            'national_id' => '1',
            'document_id' => '1',
            'tel' => '1',
            'email' => 'ali@exampple.com',
            'mobile' => '09358394242',
            'dob' => now()->subYears(31)->format('Y-m-d'),
            'address' => 'آدرس شرکت',
            'postal_code' => '33616',
        ];
    }
}
