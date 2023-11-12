<?php

namespace Modules\BlogCategory\app\Models;

use App\Traits\HasSlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Blog\app\Models\Blog;

class BlogCategory extends Model
{
    use HasFactory,HasSlugTrait;

    protected $fillable = [
        'title',
        'photo',
        'meta_keywords',
        'slug',
        'meta_description',
    ];

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }
}
