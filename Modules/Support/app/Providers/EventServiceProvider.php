<?php

namespace Modules\Support\app\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Support\app\Models\ChatMessage;
use Modules\Support\app\Observers\ChatMessageObserver;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        ChatMessage::observe(ChatMessageObserver::class);
    }
}
