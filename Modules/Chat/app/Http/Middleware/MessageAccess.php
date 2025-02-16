<?php

namespace Modules\Chat\app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Support\app\Models\ChatMessage;
use Modules\Support\app\Models\ChatMessageAttachment;

class MessageAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $file = ChatMessageAttachment::query()
            ->where('id', $request->input('file_id'))
            ->whereNull('chat_message_id')
            ->exists();

        if ($file) {
            return $next($request);
        }

        $messageId = $request->input('message_id');

        if (! $messageId || ! $this->userCanAccessMessage($messageId)) {
            abort(404);
        }

        return $next($request);
    }

    private function userCanAccessMessage($chatId): bool
    {

        return ChatMessage::query()
            ->where('user_id', auth()->id())
            ->exists();
    }
}
