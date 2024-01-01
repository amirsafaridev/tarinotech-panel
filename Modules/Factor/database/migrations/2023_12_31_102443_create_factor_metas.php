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
        Schema::create('factor_metas', function (Blueprint $table) {
            $table->id();
            $table->string('customer_fullname');
            $table->char('customer_mobile', 11);
            $table->string('project_title');

            $table->unsignedBigInteger('factor_id');
            $table->unsignedTinyInteger('type_id');

            $table->foreign('factor_id')
                ->references('id')
                ->on('factors');

            $table->foreign('type_id')
                ->references('id')
                ->on('project_types');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factor_metas');
    }
};
