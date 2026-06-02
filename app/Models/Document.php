<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'title',
        'original_filename',
        'file_path',
        'mime_type',
        'file_size',
        'status',
        'summary',
        'error_message',
        'processed_at',
    ];


}
