<?php

namespace Modules\Content\app\Models;

use App\Traits\HasSlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BlogCategory extends Model
{
    use HasFactory;
    use HasSlugTrait;
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::BLOG_CATEGORY)
            ->logAll();
    }
}
