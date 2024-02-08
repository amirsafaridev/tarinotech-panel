<?php

namespace Modules\Chat\app\Http\Middleware\Api;

use App\Enums\Database\Chat\ChatType;
use Closure;
use Illuminate\Http\Request;
use Modules\Support\app\Models\Chat;
use Modules\Support\app\Models\ChatUser;
use Modules\User\app\Models\User;

class UserChatAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $chatId = $request->route()->parameter('chatId');

        $chat = Chat::query()->findOrFail($chatId);

        if ($chat->type === ChatType::Public) {
            return $next($request);
        }

        if (! $this->userCanAccessChat($chatId)) {
            abort(404);
        }

        return $next($request);
    }

    private function userCanAccessChat($chatId): bool
    {

        return ChatUser::query()
            ->where('user_id', auth()->id())
            ->where('user_type', User::class)
            ->where('chat_id', $chatId)
            ->exists();
    }
}
