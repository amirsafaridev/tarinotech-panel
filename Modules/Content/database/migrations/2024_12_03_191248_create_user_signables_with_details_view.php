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
        DB::statement("
            CREATE OR REPLACE VIEW `user_signables_with_details` AS
            SELECT
                `user_signables`.`id`,
                `user_signables`.`target_id`,
                `user_signables`.`target_type`,
                `user_signables`.`status`,
                `user_signables`.`created_at`,
                CASE
                    WHEN `user_signables`.`target_type` = 'Modules\\\\Project\\\\app\\\\Models\\\\ProjectWeb'
                        THEN `project_web_projects`.`title`
                    WHEN `user_signables`.`target_type` = 'Modules\\\\Project\\\\app\\\\Models\\\\ProjectSeo'
                        THEN `project_seo_projects`.`title`
                    ELSE NULL
                END AS `project_title`,
                CASE
                    WHEN `user_signables`.`target_type` = 'Modules\\\\Project\\\\app\\\\Models\\\\ProjectWeb'
                        THEN `project_web_projects`.`id`
                    WHEN `user_signables`.`target_type` = 'Modules\\\\Project\\\\app\\\\Models\\\\ProjectSeo'
                        THEN `project_seo_projects`.`id`
                    ELSE NULL
                END AS `project_id`,
                CASE
                    WHEN `user_signables`.`target_type` = 'Modules\\\\Project\\\\app\\\\Models\\\\ProjectWeb'
                        THEN `project_web_projects`.`domain`
                    WHEN `user_signables`.`target_type` = 'Modules\\\\Project\\\\app\\\\Models\\\\ProjectSeo'
                        THEN `project_seo_projects`.`domain`
                    ELSE NULL
                END AS `project_domain`,
                `admins`.`first_name` AS `admin_first_name`,
                `admins`.`last_name` AS `admin_last_name`,
                `users`.`first_name` AS `user_first_name`,
                `users`.`last_name` AS `user_last_name`
            FROM `user_signables`
            LEFT JOIN `project_webs`
                ON `user_signables`.`target_id` = `project_webs`.`id`
                AND `user_signables`.`target_type` = 'Modules\\\\Project\\\\app\\\\Models\\\\ProjectWeb'
            LEFT JOIN `project_seo`
                ON `user_signables`.`target_id` = `project_seo`.`id`
                AND `user_signables`.`target_type` = 'Modules\\\\Project\\\\app\\\\Models\\\\ProjectSeo'
            LEFT JOIN `projects` AS `project_web_projects`
                ON `project_webs`.`id` = `project_web_projects`.`target_id`
                AND `project_web_projects`.`target_type` = 'Modules\\\\Project\\\\app\\\\Models\\\\ProjectWeb'
                AND `project_web_projects`.`deleted_at` IS NULL
            LEFT JOIN `projects` AS `project_seo_projects`
                ON `project_seo`.`id` = `project_seo_projects`.`target_id`
                AND `project_seo_projects`.`target_type` = 'Modules\\\\Project\\\\app\\\\Models\\\\ProjectSeo'
                AND `project_seo_projects`.`deleted_at` IS NULL
            LEFT JOIN `admins`
                ON `project_web_projects`.`admin_id` = `admins`.`id`
                OR `project_seo_projects`.`admin_id` = `admins`.`id`
            LEFT JOIN `users` on `user_signables`.`user_id` = `users`.`id`
            WHERE `project_web_projects`.`id` IS NOT NULL
               OR `project_seo_projects`.`id` IS NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') == 'sqlite') {
            return;
        }
        DB::statement('DROP VIEW IF EXISTS `user_signables_with_details`');
    }
};
