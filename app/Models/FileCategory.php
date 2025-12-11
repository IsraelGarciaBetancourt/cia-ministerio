<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileCategory extends Model
{
    protected $fillable = ['file_group_id', 'name', 'description'];

    public function group()
    {
        return $this->belongsTo(FileGroup::class, 'file_group_id');
    }

    public function privateFiles()
    {
        return $this->hasMany(PrivateFile::class);
    }
}
