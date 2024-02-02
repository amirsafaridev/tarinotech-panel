<?php

namespace Modules\Auth\app\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Modules\Admin\app\Models\Admin;
use Modules\Log\app\Enums\LogNames;
use Modules\User\app\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Login extends Model
{
    use HasFactory;
    use HasUlids;
    use LogsActivity;

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

    public static function userLogin(Admin|User $user)
    {
        $user->logins()->create([
            'id' => Str::uuid(),
            'ip' => request()->ip(),
            'agent' => request()->userAgent(),
            'login_at' => now(),
        ]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::LOGIN)
            ->logAll();
    }
}
