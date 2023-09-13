<?php

namespace App\Models;

use App\Notifications\Admin\Auth\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasRoles;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'avatar',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'password',
        'has_access',
        'dob',
        'start_cooperation',
        'start_last_contract',
        'end_last_contract',
        'resume',
        'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'datetime',
        'start_cooperation' => 'datetime',
        'start_last_contract' => 'datetime',
        'end_last_contract' => 'datetime',
    ];

    public function routeNotificationForSms($driver, $notification = null)
    {
        return $this->mobile;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function logins(): MorphMany
    {
        return $this->morphMany(Login::class, 'user');
    }

    public function latestLogin(): MorphOne
    {
        return $this->morphOne(Login::class, 'user')->latest('login_at');
    }

    public function otpCodes(): MorphMany
    {
        return $this->morphMany(OtpCode::class, 'user');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
