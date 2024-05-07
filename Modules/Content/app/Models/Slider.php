<?php

namespace Modules\Content\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'photo',
        'link',
        'sort_id',
        'published_at',
        'archived_at',
        'status',
    ];

    protected $casts = [
        'archived_at' => 'datetime',
        'published_at' => 'datetime',
        'status' => 'boolean',
    ];
}
