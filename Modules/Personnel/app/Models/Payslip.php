<?php

namespace Modules\Personnel\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Personnel\Database\factories\PayslipFactory;

class Payslip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): PayslipFactory
    {
        //return PayslipFactory::new();
    }
}
