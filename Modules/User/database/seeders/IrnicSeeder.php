<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Modules\User\database\factories\IrnicFactory;

class IrnicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IrnicFactory::new()->count(20)->create();
    }
}
