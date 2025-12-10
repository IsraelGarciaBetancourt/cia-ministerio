<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'post_date',
        'cover_image',
        'created_by',
    ];

    protected $casts = [
        'post_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {

            // Eliminar portada
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }

            // Eliminar media asociado
            foreach ($post->media as $media) {

                if (!$media->is_external && $media->file_path) {
                    Storage::disk('public')->delete($media->file_path);
                }

                $media->delete();
            }
        });
    }

    // Un post tiene muchas imágenes/videos
    public function media()
    {
        return $this->hasMany(Media::class);
    }

    // Usuario que creó el post
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Portada en Storage
    public function getCoverUrlAttribute()
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : null;
    }
}