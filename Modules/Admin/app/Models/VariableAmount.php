<?php

namespace Modules\Admin\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariableAmount extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['job_title_id','date', 'base_units_count','extra_units_amount', 'performance_amount', 'reward_basis'];
    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class);
    }
    
}
