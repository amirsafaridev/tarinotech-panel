<?php

namespace Modules\Contract\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserSignable extends Model
{
    use HasFactory;

    public function target(): MorphTo
    {
        return $this->morphTo();
    }
}
