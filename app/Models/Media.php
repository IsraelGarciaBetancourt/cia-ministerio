<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'type',
        'file_path',
        'original_filename',
        'mime_type',
        'is_external',
        'external_url',
    ];

    protected $casts = [
        'is_external' => 'boolean',
    ];

    // Cada media pertenece a un post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Devuelve la URL lista para usar (local o externa)
    public function getUrlAttribute()
    {
        if ($this->is_external) {
            return $this->external_url;
        }

        return $this->file_path 
            ? asset('storage/' . $this->file_path) 
            : null;
    }

    // ¿Es una imagen?
    public function isImage()
    {
        return $this->type === 'image';
    }

    // ¿Es un video?
    public function isVideo()
    {
        return $this->type === 'video';
    }
}
