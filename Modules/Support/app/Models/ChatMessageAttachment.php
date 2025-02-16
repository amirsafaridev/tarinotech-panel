<?php

namespace Modules\Support\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessageAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_message_id',
        'type',
        'file_type',
        'file_name',
        'file_extension',
        'file_size',
        'file_path',
    ];
}
