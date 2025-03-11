<?php

namespace Modules\Admin\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\app\Models\Admin;

class BonusesDeduction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['date','price', 'type', 'user_id','reason_id', 'description'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }
    public function reason(): BelongsTo
    {
        return $this->belongsTo(Reason::class, 'reason_id');
    }
  
}
