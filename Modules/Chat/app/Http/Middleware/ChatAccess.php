<?php

namespace Modules\Chat\app\Http\Middleware;

use App\Enums\Database\Chat\ChatType;
use Closure;
use Illuminate\Http\Request;
use Modules\Support\app\Models\Chat;
use Modules\Support\app\Models\ChatUser;

class ChatAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $chatId = $request->input('chat_id');

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
        return true;

        return ChatUser::query()
            ->where('user_id', auth()->id())
            ->where('chat_id', $chatId)
            ->exists();
    }
}
