<?php

namespace Modules\Content\app\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Content\app\Events\CategoryWasDeleted;
use Modules\Content\app\Listeners\UpdateBlogToDefaultCategory;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CategoryWasDeleted::class => [
            UpdateBlogToDefaultCategory::class,
        ],
    ];
}
