<?php

namespace Modules\Chat\app\Events\Message;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Chat\app\Resources\Message\MessageResource;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    private MessageResource $message;

    private string $htmlRendered = '';

    public function __construct(MessageResource $message, $htmlRendered = '')
    {
        $this->message = $message;
        $this->htmlRendered = $htmlRendered;
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('chat-group'),
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
        return ['message' => $this->message, 'htmlRendered' => $this->htmlRendered];
    }
}
