<?php

namespace App\Models;

use App\Traits\HasSlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    use HasFactory,HasSlugTrait;

    protected $fillable = [
        'title',
        'slug',
        'blog_category_id',
        'photo',
        'body',
        'is_publish',
        'meta_description',
        'meta_keywords',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }
}
