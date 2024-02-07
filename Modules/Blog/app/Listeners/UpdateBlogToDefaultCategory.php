<?php

namespace Modules\Blog\app\Listeners;

use Modules\Blog\app\Events\CategoryWasDeleted;
use Modules\Blog\app\Models\Blog;

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
    public function handle(CategoryWasDeleted $event): void
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
