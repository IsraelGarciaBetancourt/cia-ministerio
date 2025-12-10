<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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