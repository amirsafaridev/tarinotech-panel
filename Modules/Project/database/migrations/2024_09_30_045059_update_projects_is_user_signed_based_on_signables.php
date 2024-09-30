<?php

use Illuminate\Database\Migrations\Migration;
use Modules\Contract\app\Enums\UserSignableStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('projects')
            ->join('user_signables', function ($join) {
                $join->on('projects.target_id', '=', 'user_signables.target_id')
                    ->whereRaw('RIGHT(projects.target_type, 9) = RIGHT(user_signables.target_type, 9)');
            })
            ->where('user_signables.status', UserSignableStatus::Accepted)
            ->update(['projects.is_signed_user' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('projects')->update(['is_signed_user' => 0]);
    }
};
