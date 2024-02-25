<?php

namespace Modules\Chat\app\Events\Message;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Support\app\Models\Chat;

class ChatUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    private Chat $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;

    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'chat-update';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return ['chat' => [
            'id' => $this->chat->id,
        ]];
    }
}
