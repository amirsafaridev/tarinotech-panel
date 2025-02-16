<?php

namespace Modules\Contract\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SignableFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'signable_id',
        'signable_type',
        'file_name',
        'file_path',
    ];

    public function singable(): MorphTo
    {
        return $this->morphTo();
    }
}
