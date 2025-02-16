<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_bases', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('title');
            $table->timestamps();
        });

        $this->addBaseProject();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_bases');
    }

    private function addBaseProject(): void
    {
        $types = ['طراحی سایت', 'سئو', 'تبلیغات ادورز'];
        foreach ($types as $type) {
            $items[] = [
                'title' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('project_bases')->insert($items);
    }
};
