<?php

namespace Modules\Contract\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Admin\app\Models\Admin;

class Signable extends Model
{
    use HasFactory;

    protected $casts = [
        'sign_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'target_type',
        'target_id',
        'make_admin_id',
        'sign_admin_id',
        'status',
        'sign_at',
    ];

    public function target(): MorphTo
    {
        return $this->morphTo();
    }

    public function makeAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'make_admin_id');
    }
}
