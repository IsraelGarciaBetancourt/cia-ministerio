<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PrivateFile extends Model
{
    protected $fillable = [
        'file_category_id',
        'title',
        'description',
        'uploaded_by',
    ];

    public function category()
    {
        return $this->belongsTo(FileCategory::class, 'file_category_id');
    }

    public function attachments()
    {
        return $this->hasMany(PrivateFileAttachment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Borrado en cascada de archivos físicos
    protected static function booted(): void
    {
        static::deleting(function (PrivateFile $file) {
            foreach ($file->attachments as $att) {
                if ($att->file_path) {
                    Storage::delete($att->file_path);
                }
            }
        });
    }
}
