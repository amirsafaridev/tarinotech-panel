<?php

use App\Enums\Database\Chat\ChatType;
use Illuminate\Support\Facades\Broadcast;
use Modules\Support\app\Models\Chat;
use Modules\Support\app\Models\ChatUser;
use Modules\User\app\Models\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat', function ($user) {
    return true;
});

Broadcast::channel('public', function ($user) {
    return true;
});

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {

    $chat = Chat::query()
        ->findOrFail($chatId);

    if ($chat->type === ChatType::Public) {
        return true;
    }

    return ChatUser::query()
        ->where('user_id', $user->id)
        ->where('user_type', $user::class)
        ->where('chat_id', $chatId)
        ->exists();

});
