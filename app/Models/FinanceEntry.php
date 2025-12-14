<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinanceEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'finance_id',
        'finance_type_id',
        'brother_id',
        'amount',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Boot para recalcular total automáticamente
    protected static function boot()
    {
        parent::boot();

        static::created(function ($entry) {
            $entry->finance->recalculateTotal();
        });

        static::updated(function ($entry) {
            $entry->finance->recalculateTotal();
        });

        static::deleted(function ($entry) {
            $entry->finance->recalculateTotal();
        });
    }

    // Relaciones
    public function finance()
    {
        return $this->belongsTo(Finance::class);
    }

    public function type()
    {
        return $this->belongsTo(FinanceType::class, 'finance_type_id');
    }

    public function brother()
    {
        return $this->belongsTo(Brother::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helpers
    public function getAmountFormattedAttribute()
    {
        return 'S/. ' . number_format($this->amount, 2);
    }

    public function getDisplayNameAttribute()
    {
        if ($this->brother) {
            return $this->brother->full_name;
        }
        return $this->type->name;
    }
}