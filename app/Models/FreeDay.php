<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeDay extends Model
{
    use HasFactory;

    protected $casts = [
        'free_at' => 'date',
    ];

    protected $fillable = [
        'title',
        'free_at',
    ];
}
