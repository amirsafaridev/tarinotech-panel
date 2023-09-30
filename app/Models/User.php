<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'en_first_name',
        'en_last_name',
        'father_name',
        'national_id',
        'document_id',
        'tel',
        'email',
        'avatar',
        'national_photo',
        'dob',
        'person_type',
        'official_bill',
        'mobile',
        'verify_at',
        'is_block',
        'user_type',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verify_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function irnic(): HasOne
    {
        return $this->hasOne(Irnic::class);
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function accessProjects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'presenter_project');
    }

    public function latestLogin(): MorphOne
    {
        return $this->morphOne(Login::class, 'user')->latest('login_at');
    }
}
