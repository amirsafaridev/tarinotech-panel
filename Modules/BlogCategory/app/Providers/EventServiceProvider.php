<?php

namespace Modules\BlogCategory\app\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\BlogCategory\app\Events\BlogCategoryWasDeleted;
use Modules\BlogCategory\app\Listeners\UpdateBlogToDefaultCategory;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BlogCategoryWasDeleted::class => [
            UpdateBlogToDefaultCategory::class,
        ],
    ];
}
