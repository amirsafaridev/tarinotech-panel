<?php

use Illuminate\Database\Migrations\Migration;
use Modules\Admin\app\Models\Admin;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('admins')
            ->insert([
                'first_name' => 'معین',
                'last_name' => 'تقی زاده',
                'mobile' => '09195331311',
                'email' => 'mnte170@gmail.com',
                'password' => bcrypt('sg5454ghOpd[!@4'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        DB::table('admins')
            ->insert([
                'first_name' => 'علی',
                'last_name' => 'موسوی',
                'mobile' => '09358394242',
                'email' => 'mosaviali701@gmail.com',
                'password' => bcrypt('12345678@p'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        Admin::query()->find(1)->syncRoles(1);
        Admin::query()->find(2)->syncRoles(1);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
