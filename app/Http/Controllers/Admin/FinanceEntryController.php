<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use App\Models\FinanceEntry;
use App\Models\FinanceType;
use App\Models\Brother;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FinanceEntryController extends Controller
{
    /**
     * Registrar un nuevo movimiento financiero
     */
    public function store(Request $request, Finance $finance)
    {
        // 🚫 No permitir registrar si la finanza está cerrada
        if ($finance->is_closed) {
            return back()->with('error', 'Esta finanza está cerrada. No se pueden registrar movimientos.');
        }

        // 1) Obtener tipo (y que exista)
        $type = FinanceType::query()
            ->whereKey($request->input('finance_type_id'))
            ->firstOrFail();

        // 2) No permitir usar tipos inactivos
        if (!$type->is_active) {
            return back()->withErrors([
                'finance_type_id' => 'Este tipo de finanza está inactivo.',
            ]);
        }

        // 3) Reglas base
        $rules = [
            'finance_type_id' => ['required', 'exists:finance_types,id'],
            'amount'          => ['required', 'numeric', 'min:0.01'],
            'notes'           => ['nullable', 'string'],
            // brother_id se define abajo (condicional)
        ];

        // ✅ VALIDACIÓN CONDICIONAL REAL (según requires_brother)
        if ($type->requires_brother) {
            $rules['brother_id'] = [
                'required',
                'integer',
                // solo hermanos activos
                'exists:brothers,id,is_active,1',
            ];
        } else {
            $rules['brother_id'] = [
                'nullable',
                'integer',
                // si viene, debe existir y estar activo (pero puede ser null)
                'exists:brothers,id,is_active,1',
            ];
        }

        $data = $request->validate($rules);

        // 🚫 Regla: si NO permite múltiples, solo 1 registro de ese tipo por finanza
        if (!$type->allows_multiple) {
            $already = $finance->entries()
                ->where('finance_type_id', $type->id)
                ->exists();

            if ($already) {
                return back()->withErrors([
                    'finance_type_id' => 'Este tipo solo permite un registro por finanza.',
                ]);
            }
        }

        FinanceEntry::create([
            'finance_id'      => $finance->id,
            'finance_type_id' => $type->id,
            'brother_id'      => $data['brother_id'] ?? null,
            'amount'          => $data['amount'],
            'notes'           => $data['notes'] ?? null,
            'created_by'      => auth()->id(),
        ]);

        return back()->with('success', 'Movimiento registrado correctamente.');
    }

    /**
     * Eliminar un movimiento financiero
     */
    public function destroy(FinanceEntry $entry)
    {
        // No permitir eliminar si la jornada está cerrada
        if ($entry->finance->is_closed) {
            return back()->with('error', 'No se puede eliminar un movimiento de una jornada cerrada.');
        }

        $entry->delete();

        // El total se recalcula SOLO (boot del modelo)
        return back()->with('success', 'Movimiento eliminado correctamente.');
    }
}
