<?php

namespace Modules\Blog\app\Events;

use Illuminate\Queue\SerializesModels;

class CategoryWasDeleted
{
    use SerializesModels;

    public int $categoryId;

    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
    }
}
