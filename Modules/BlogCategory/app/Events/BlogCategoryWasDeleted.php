<?php

namespace Modules\BlogCategory\app\Events;

use Illuminate\Queue\SerializesModels;

class BlogCategoryWasDeleted
{
    use SerializesModels;

    public int $categoryId;

    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
    }
}
