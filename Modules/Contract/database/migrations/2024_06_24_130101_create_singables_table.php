<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Contract\app\Enums\SignableStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('signables', function (Blueprint $table) {
            $table->id();
            $table->morphs('target');

            $table->unsignedBigInteger('make_admin_id');
            $table->unsignedBigInteger('sign_admin_id')
                ->nullable();

            $table->unsignedTinyInteger('status')
                ->default(SignableStatus::Pending);

            $table->text('note')->nullable();

            $table->dateTime('sign_at')
                ->nullable();

            $table->timestamps();

            $table->foreign('make_admin_id')
                ->references('id')
                ->on('admins');

            $table->foreign('sign_admin_id')
                ->references('id')
                ->on('admins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signables');
    }
};
