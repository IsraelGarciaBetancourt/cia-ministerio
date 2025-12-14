<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class FinanceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'requires_brother',
        'allows_multiple',
        'is_active',
    ];

    protected $casts = [
        'requires_brother' => 'boolean', // ✅ nuevo
        'allows_multiple' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Boot para generar slug automático
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($type) {
            if (empty($type->slug)) {
                $type->slug = Str::slug($type->name);
            }
        });
    }

    // Relaciones
    public function entries()
    {
        return $this->hasMany(FinanceEntry::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helpers
    public function getIconHtmlAttribute()
    {
        return '<span class="text-2xl">' . ($this->icon ?? '💰') . '</span>';
    }
}