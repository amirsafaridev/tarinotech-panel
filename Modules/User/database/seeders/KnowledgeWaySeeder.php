<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Modules\User\database\factories\KnowledgeWayFactory;

class KnowledgeWaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KnowledgeWayFactory::new()->count(10)->create();
    }
}
