<?php

namespace Modules\User\app\Models;

use Crypt;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Irnic extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'identify',
        'password',
        'user_id',
    ];

    protected function password(): Attribute
    {

        return Attribute::make(
            get: fn (string $value) => ! empty($value) ? Crypt::decrypt($value) : '',
        );
    }
}
