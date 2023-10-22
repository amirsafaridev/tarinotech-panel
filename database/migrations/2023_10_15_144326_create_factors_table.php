<?php

use App\Enums\Database\Factor\FactorStatus;
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
        Schema::create('factors', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('admin_id');
            $table->char('identify')->unique()->index();
            $table->char('transaction_id')->index()->nullable();
            $table->unsignedBigInteger('project_id');
            $table->unsignedTinyInteger('status')->default(FactorStatus::Pending);
            $table->boolean('is_official')->default(false);
            $table->dateTime('expired_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->json('gateway_data');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('admin_id')
                ->references('id')
                ->on('admins');

            $table->foreign('project_id')
                ->references('id')
                ->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factors');
    }
};
