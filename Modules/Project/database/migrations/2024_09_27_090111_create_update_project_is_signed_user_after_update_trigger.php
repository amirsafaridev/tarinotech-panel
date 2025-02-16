<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (config('database.default') == 'sqlite') {
            return;
        }
        DB::unprepared('
            CREATE TRIGGER update_project_is_signed_user_after_update
            AFTER UPDATE ON user_signables
            FOR EACH ROW
            BEGIN
                IF NEW.status = 4 THEN
                    UPDATE projects
                    SET is_signed_user = 1
                    WHERE target_id = NEW.target_id
                    AND RIGHT(target_type, 9) = RIGHT(NEW.target_type, 9);
                ELSE
                    UPDATE projects
                    SET is_signed_user = 0
                    WHERE target_id = NEW.target_id
                    AND RIGHT(target_type, 9) = RIGHT(NEW.target_type, 9);
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_project_is_signed_user_after_update');
    }
};
