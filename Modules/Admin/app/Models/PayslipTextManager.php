<?php

namespace Modules\Admin\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Database\factories\PayslipTextManagerFactory;

class PayslipTextManager extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): PayslipTextManagerFactory
    {
        //return PayslipTextManagerFactory::new();
    }
}
