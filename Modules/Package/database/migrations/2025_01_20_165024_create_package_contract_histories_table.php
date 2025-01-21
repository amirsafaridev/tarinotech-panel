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
        Schema::create('package_contract_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('package_id');
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->text('contract_content');
            $table->text('change_reason')->nullable();
            $table->timestamp('restored_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('package_id')
                ->references('id')
                ->on('packages')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_contract_histories');
    }
};
