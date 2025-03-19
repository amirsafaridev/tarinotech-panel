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
        Schema::create('personnel_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('admins')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('diterminant_user_id')->constrained('admins')->onUpdate('cascade')->onDelete('cascade')->nullable()->default(null);

            $table->enum('type', ['daily', 'hourly'])->comment('نوع مرخصی: روزانه یا ساعتی');
            $table->date('start_date')->nullable()->comment('تاریخ شروع مرخصی روزانه');
            $table->date('end_date')->nullable()->comment('تاریخ پایان مرخصی روزانه');
            $table->date('date')->nullable()->comment('تاریخ مرخصی ساعتی');
            $table->time('start_time')->nullable()->comment('ساعت شروع مرخصی ساعتی');
            $table->time('end_time')->nullable()->comment('ساعت پایان مرخصی ساعتی');
            $table->text('description')->nullable()->comment('توضیحات');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->comment('وضعیت درخواست');
            $table->boolean('rules_accepted')->default(false)->comment('پذیرش قوانین مرخصی');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_reports');
    }
};
