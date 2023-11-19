<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Login\app\Models\Login;

class LoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Login::factory(50)->create();
    }
}
