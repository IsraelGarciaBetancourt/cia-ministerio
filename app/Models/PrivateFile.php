<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrivateFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'mime_type',
        'uploaded_by',
    ];

    // Usuario que subió el archivo
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Ruta solo accesible desde Auth
    public function getFileUrlAttribute()
    {
        return route('private-files.download', $this->id);
    }
}
