<?php

namespace Modules\Support\app\Observers;

use Modules\Support\app\Models\ChatMessage;
use Modules\Support\app\Models\ChatUser;

class ChatMessageObserver
{
    /**
     * Handle the ChatMessageObserver "created" event.
     */
    public function created(ChatMessage $chatMessage): void
    {
        $chatId = $chatMessage->chat_id;

        $userChats = ChatUser::query()
            ->where('chat_id', $chatId)
            ->get();

        foreach ($userChats as $userChat) {
            $count = ChatMessage::query()
                ->where('chat_id', $chatId)
                ->where('created_at', '>', $userChat->seen_at)
                ->count();
            $userChat->update([
                'unread' => $count,
            ]);
        }
    }

    /**
     * Handle the ChatMessage "updated" event.
     */
    public function updated(ChatMessage $chatMessage): void
    {
        //
    }

    /**
     * Handle the ChatMessage "deleted" event.
     */
    public function deleted(ChatMessage $chatMessage): void
    {
        //
    }

    /**
     * Handle the ChatMessage "restored" event.
     */
    public function restored(ChatMessage $chatMessage): void
    {
        //
    }

    /**
     * Handle the ChatMessage "force deleted" event.
     */
    public function forceDeleted(ChatMessage $chatMessage): void
    {
        //
    }
}
