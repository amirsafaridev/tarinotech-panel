<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OtpCode extends Model
{
    use HasFactory;
    use HasUlids;

    protected $primaryKey = 'id';

    protected $fillable = [
        'identify',
        'expired_at',
        'ip',
        'agent',
        'code',
    ];

    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
