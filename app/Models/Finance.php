<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Finance extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'finance_date',
        'total_amount',
        'is_closed',
        'closed_at',
        'closed_by',
        'created_by',
    ];

    protected $casts = [
        'finance_date' => 'date',
        'total_amount' => 'decimal:2',
        'is_closed' => 'boolean',
        'closed_at' => 'datetime',
    ];

    // Relaciones
    public function entries()
    {
        return $this->hasMany(FinanceEntry::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function closer()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('is_closed', false);
    }

    public function scopeClosed($query)
    {
        return $query->where('is_closed', true);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('finance_date', $date);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('finance_date', now()->year)
                     ->whereMonth('finance_date', now()->month);
    }

    // Métodos de negocio
    public function recalculateTotal()
    {
        $this->total_amount = $this->entries()->sum('amount');
        $this->save();
    }

    public function close($userId)
    {
        if ($this->is_closed) {
            throw new \Exception('Esta finanza ya está cerrada');
        }

        $this->is_closed = true;
        $this->closed_at = now();
        $this->closed_by = $userId;
        $this->save();
    }

    public function reopen()
    {
        $this->is_closed = false;
        $this->closed_at = null;
        $this->closed_by = null;
        $this->save();
    }

    // Helpers
    public function getStatusBadgeAttribute()
    {
        if ($this->is_closed) {
            return '<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">Cerrada</span>';
        }
        return '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">Abierta</span>';
    }

    public function getTotalFormattedAttribute()
    {
        return 'S/. ' . number_format($this->total_amount, 2);
    }

    // Obtener ofrenda (solo una)
    public function getOfrendaAttribute()
    {
        $ofrendaType = FinanceType::where('slug', 'ofrenda')->first();
        if (!$ofrendaType) return null;

        return $this->entries()
                    ->where('finance_type_id', $ofrendaType->id)
                    ->first();
    }

    // Obtener diezmos
    public function getDiezmosAttribute()
    {
        $diezmoType = FinanceType::where('slug', 'diezmo')->first();
        if (!$diezmoType) return collect([]);

        return $this->entries()
                    ->where('finance_type_id', $diezmoType->id)
                    ->with('brother')
                    ->get();
    }

    // Obtener otros tipos (primicias, eventos, etc)
    public function getOtherEntriesAttribute()
    {
        $excludeSlugs = ['diezmo', 'ofrenda'];
        $excludeIds = FinanceType::whereIn('slug', $excludeSlugs)->pluck('id');

        return $this->entries()
                    ->whereNotIn('finance_type_id', $excludeIds)
                    ->with('type')
                    ->get();
    }
}