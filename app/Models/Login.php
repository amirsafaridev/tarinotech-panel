<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Login extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'id',
        'user_type',
        'user_id',
        'login_at',
        'agent',
        'ip',
    ];

    protected $casts = [
        'login_at' => 'datetime',
    ];

    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    public function userLogin(Admin $user)
    {
        $user->logins()->create([
            'id' => Str::uuid(),
            'ip' => request()->ip(),
            'agent' => request()->userAgent(),
            'login_at' => now(),
        ]);
    }
}
