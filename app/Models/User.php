<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'firstname',
        'lastname',
        'avatar',
        'email',
        'email_verified_at',
        'password',
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
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function words()
    {
        return $this->belongsToMany(Word::class, 'user_word', 'user_id')
            ->withPivot(['is_knew', 'correct_answer', 'wrong_answer', 'repeat']);
    }

    public function sentence()
    {
        return $this->belongsToMany(Sentence::class, 'user_sentence', 'user_id');
    }

    public function block()
    {
        return $this->hasOne(UserBlock::class);
    }

    public function avatar()
    {
        return $this->belongsTo(Avatar::class);
    }

    public function logins()
    {
        return $this->morphMany(Login::class, 'user')
            ->latest();
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
