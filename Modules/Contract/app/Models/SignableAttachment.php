<?php

namespace Modules\Contract\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SignableAttachment extends Model
{
    use HasFactory, HasUlids;

    protected $primaryKey = 'ulid';

    protected $fillable = [
        'ulid',
        'file_type',
        'user_type',
        'user_id',
        'file_size',
        'target_type',
        'file_extension',
        'target_id',
        'file_path',
        'is_used',
    ];

    public function target(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
