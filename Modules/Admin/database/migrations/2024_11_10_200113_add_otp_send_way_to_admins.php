<?php

use App\Enums\Database\Admin\OtpSendWay;
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
        Schema::table('admins', function (Blueprint $table) {
            $table->after('is_block', function (Blueprint $table) {
                $table->unsignedTinyInteger('otp_send_way')->default(OtpSendWay::SMS);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('otp_send_way');
        });
    }
};
