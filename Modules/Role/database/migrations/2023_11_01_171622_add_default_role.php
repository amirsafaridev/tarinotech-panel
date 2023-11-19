<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roles = [
            [
                'name' => 'مدیریت',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
                'can_action' => false,
            ],
            [
                'name' => 'کارشناس فروش',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
                'can_action' => false,
            ],
            [
                'name' => 'مدیر پروژه',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
                'can_action' => false,
            ],
            [
                'name' => 'کارشناس طراحی',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
                'can_action' => false,
            ],
            [
                'name' => 'پشتیبان وب سایت',
                'guard_name' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
                'can_action' => false,
            ],
        ];
        DB::table('roles')->insert($roles);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
