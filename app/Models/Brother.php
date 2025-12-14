<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brother extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lastname',
        'phone',
        'email',
        'zona',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function financeEntries()
    {
        return $this->hasMany(FinanceEntry::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helpers
    public function getFullNameAttribute()
    {
        return trim($this->name . ' ' . $this->lastname);
    }

    public function getTotalDiezmosAttribute()
    {
        return $this->financeEntries()
                    ->whereHas('type', function ($q) {
                        $q->where('slug', 'diezmo');
                    })
                    ->sum('amount');
    }

    public function getLastDiezmoDateAttribute()
    {
        $lastEntry = $this->financeEntries()
                          ->whereHas('type', function ($q) {
                              $q->where('slug', 'diezmo');
                          })
                          ->latest()
                          ->first();

        return $lastEntry ? $lastEntry->created_at : null;
    }
}