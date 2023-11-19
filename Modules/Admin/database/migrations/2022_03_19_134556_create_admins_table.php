<?php

use App\Enums\Database\Admin\TypeInsurance;
use App\Enums\Database\Admin\WorkLocation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();

            /**
             * Information Of User
             */
            $table->string('first_name');
            $table->string('last_name');

            /**
             * Auth Of User
             */
            $table->char('mobile', 11)->index();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('has_access')->default(true);
            /**
             * User Information
             */
            $table->string('avatar')->nullable();
            $table->date('dob')->nullable();
            $table->date('start_cooperation')->nullable();
            $table->date('start_last_contract')->nullable();
            $table->date('end_last_contract')->nullable();
            $table->char('mobile_company', 11)->nullable();
            $table->char('number_company', 11)->nullable();
            $table->text('resume')->nullable();
            $table->text('description')->nullable();

            $table->char('tel', 11)->nullable();
            $table->char('postal_code', 10)->nullable();
            $table->unsignedTinyInteger('work_location')->default(WorkLocation::None);
            $table->unsignedTinyInteger('type_insurance')->default(TypeInsurance::None);
            $table->boolean('has_contract')->default(false);
            $table->unsignedInteger('promissory')->default(0);
            $table->char('national_code', 10)->nullable();
            $table->char('shaba_number', 26)->nullable();
            $table->char('cart_number', 16)->nullable();

            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
};
