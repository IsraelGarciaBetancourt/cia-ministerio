<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateFileAttachment extends Model
{
    protected $fillable = [
        'private_file_id',
        'file_path',
        'original_filename',
        'mime_type',
        'size',
    ];

    public function privateFile()
    {
        return $this->belongsTo(PrivateFile::class);
    }
}
