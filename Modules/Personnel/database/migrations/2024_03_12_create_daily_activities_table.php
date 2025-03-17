<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::create('daily_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('total_duration')->nullable(); // Duration in seconds
            $table->string('status')->default('inactive'); // active, inactive
            $table->boolean('is_physical_day')->default(false);
            $table->json('location_data')->nullable();
            $table->boolean('edit_request')->default(false);
            $table->json('edit_request_data')->nullable();
            $table->timestamps();
            
            // حذف محدودیت unique برای امکان ثبت چندین فعالیت در یک روز
            // $table->unique(['user_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_activities');
    }
}; 