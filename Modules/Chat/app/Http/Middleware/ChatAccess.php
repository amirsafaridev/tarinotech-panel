<?php

namespace Modules\Chat\app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Support\app\Models\ChatUser;

class ChatAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $chatId = $request->input('chat_id');

        if (! $chatId || ! $this->userCanAccessChat($chatId)) {
            abort(404);
        }

        return $next($request);
    }

    private function userCanAccessChat($chatId): bool
    {

        return ChatUser::query()
            ->where('user_id', auth()->id())
            ->where('chat_id', $chatId)
            ->exists();
    }
}
