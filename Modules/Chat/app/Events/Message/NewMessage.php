<?php

namespace Modules\Chat\app\Events\Message;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Chat\app\Resources\Message\MessageResource;
use Modules\Support\app\Models\ChatMessage;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    private ChatMessage $chatMessage;

    private string $htmlRendered = '';

    public function __construct(ChatMessage $chatMessage, $htmlRendered = '')
    {
        $this->chatMessage = $chatMessage;
        $this->htmlRendered = $htmlRendered;
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.'.$this->chatMessage->chat_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'new-message';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return ['message' => new MessageResource($this->chatMessage), 'htmlRendered' => $this->htmlRendered];
    }
}
