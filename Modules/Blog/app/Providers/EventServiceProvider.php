<?php

namespace Modules\Blog\app\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Blog\app\Events\CategoryWasDeleted;
use Modules\Blog\app\Listeners\UpdateBlogToDefaultCategory;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CategoryWasDeleted::class => [
            UpdateBlogToDefaultCategory::class,
        ],
    ];
}
