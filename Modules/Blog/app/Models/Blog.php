<?php

namespace Modules\Blog\app\Models;

use App\Traits\HasSlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\BlogCategory\app\Models\BlogCategory;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Blog extends Model
{
    use HasFactory;
    use HasSlugTrait;
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::BLOG)
            ->logAll();
    }
}
