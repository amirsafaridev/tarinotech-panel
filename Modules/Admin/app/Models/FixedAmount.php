<?php

namespace Modules\Admin\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixedAmount extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['basic_rights', 'right_to_housing','right_to_marry', 'childrens_right', 'right_to_eat_and_drink', 'employer_insurance','personnel_insurance', 'employer_insurance_remote', 'personnel_insurance_remote'];
    
}
