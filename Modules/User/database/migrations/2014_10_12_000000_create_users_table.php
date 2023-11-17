<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\User\app\Enums\PersonType;
use Modules\User\app\Enums\UserType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('en_first_name')->nullable();
            $table->string('en_last_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('national_photo')->nullable();
            $table->string('national_id')->nullable();
            $table->string('document_id')->nullable();
            $table->string('tel')->nullable();
            $table->string('email')->nullable();

            $table->string('avatar')->nullable();
            $table->date('dob')->nullable();
            $table->unsignedTinyInteger('person_type')->default(PersonType::Person);

            /* Billing */
            $table->boolean('official_bill')->default(false);

            /* Auth */
            $table->string('mobile')->unique();
            $table->dateTime('verify_at')->nullable();
            $table->boolean('is_block')->default(false);
            $table->unsignedTinyInteger('user_type')->default(UserType::Primary);

            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
