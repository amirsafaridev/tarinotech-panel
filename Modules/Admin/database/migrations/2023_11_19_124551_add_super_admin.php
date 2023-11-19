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
                'email' => 'mn71@gmail.com',
                'password' => bcrypt('sg5454ghOpd[!@4'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        Admin::first()->syncRoles(1);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
