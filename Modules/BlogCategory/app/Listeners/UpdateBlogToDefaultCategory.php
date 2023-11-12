<?php

namespace Modules\BlogCategory\app\Listeners;

use Modules\Blog\app\Models\Blog;
use Modules\BlogCategory\app\Events\BlogCategoryWasDeleted;

class UpdateBlogToDefaultCategory
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BlogCategoryWasDeleted $event): void
    {
        // Update blog entries with the deleted category to a default category
        $defaultCategoryId = 1; // Change this to the ID of your default category
        Blog::query()
            ->where('blog_category_id', $event->categoryId)
            ->update([
                'blog_category_id' => $defaultCategoryId,
            ]);
    }
}
