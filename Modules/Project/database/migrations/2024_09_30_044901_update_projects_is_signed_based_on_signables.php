<?php

use Illuminate\Database\Migrations\Migration;
use Modules\Contract\app\Enums\SignableStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('projects')
            ->join('signables', function ($join) {
                $join->on('projects.target_id', '=', 'signables.target_id')
                    ->whereRaw('RIGHT(projects.target_type, 9) = RIGHT(signables.target_type, 9)');
            })
            ->where('signables.status', SignableStatus::Signed)
            ->update(['projects.is_signed' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('projects')->update(['is_signed' => 0]);
    }
};
