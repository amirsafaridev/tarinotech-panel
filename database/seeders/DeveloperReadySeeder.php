<?php

namespace Database\Seeders;

use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use Illuminate\Database\Seeder;
use Modules\Admin\app\Models\Admin;
use Modules\Project\app\Models\Project;
use Modules\Support\app\Models\Chat;
use Modules\User\app\Enums\UserType;
use Modules\User\app\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DeveloperReadySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::query()
            ->get()
            ->pluck('id')->toArray();

        $role = Role::first();
        $role->givePermissionTo($permissions);

        $admin = Admin::first();
        $admin->role($role);
        $admin->update([
            'password' => bcrypt('12345678'),
        ]);

        /*$user = User::first();
        $user->update([
            'user_type' => UserType::Primary,
            'is_block' => false,
        ]);

        $project = Project::first();
        $project->update([
            'user_id' => $user->id,
        ]);

        $chat = Chat::query()->create([
            'title' => 'گروه پشتیبانی',
            'type' => ChatType::Group,
            'status' => ChatStatus::Open,
            'project_id' => $project->id,
        ]);

        $chat->users()->create([
            'user_id' => $user->id,
            'user_type' => User::class,
            'seen_at' => now(),
        ]);

        foreach ([1, 2, 3] as $adminId) {
            $chat->users()->create([
                'user_id' => $adminId,
                'user_type' => Admin::class,
                'seen_at' => now(),
            ]);
        }*/
    }
}
