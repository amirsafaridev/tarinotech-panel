<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            CREATE TRIGGER message_counter AFTER INSERT ON chat_messages
            FOR EACH ROW
            BEGIN
                DECLARE done BOOLEAN DEFAULT FALSE;
                DECLARE id_value INT;
                DECLARE seen_at_timestamp DATETIME;
                DECLARE message_count INT;

                DECLARE cur CURSOR FOR 
                    SELECT seen_at, id
                    FROM chat_users
                    WHERE chat_users.chat_id = NEW.chat_id;

                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

                UPDATE chat_users
                SET seen_at = NOW()
                WHERE user_id = NEW.user_id
                AND chat_users.chat_id = NEW.chat_id
                AND RIGHT(user_type, 4) = RIGHT(NEW.user_type, 4);
               
                OPEN cur;

                read_loop: LOOP
                    FETCH cur INTO seen_at_timestamp, id_value;
                    IF done THEN
                        LEAVE read_loop;
                    END IF;

                    SELECT COUNT(*) INTO message_count 
                    FROM chat_messages
                    WHERE chat_id = NEW.chat_id 
                    AND created_at > seen_at_timestamp;

                    UPDATE chat_users 
                    SET unread = message_count 
                    WHERE id = id_value;
                END LOOP;

                CLOSE cur;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS message_counter');
    }
};
