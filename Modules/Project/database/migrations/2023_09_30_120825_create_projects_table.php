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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedInteger('price');

            $table->string('domain')->index();

            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedTinyInteger('type_id');
            $table->unsignedTinyInteger('base_id');
            $table->unsignedTinyInteger('status_id')
                ->nullable();

            $table->morphs('target');

            $table->date('agreement_at')->nullable();
            $table->date('deadline_at')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('base_id')
                ->on('project_bases')
                ->references('id');

            $table->foreign('type_id')
                ->references('id')
                ->on('project_types');

            $table->foreign('admin_id')
                ->references('id')
                ->on('admins');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('status_id')
                ->on('project_statuses')
                ->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
