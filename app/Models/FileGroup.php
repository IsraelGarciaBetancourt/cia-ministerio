<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileGroup extends Model
{
    protected $fillable = ['name', 'description'];

    public function categories()
    {
        return $this->hasMany(FileCategory::class);
    }

    public function privateFilesCount(): int
    {
        return $this->categories()
            ->withCount('privateFiles')
            ->get()
            ->sum('private_files_count');
    }
}
