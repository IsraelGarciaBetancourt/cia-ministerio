<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use App\Models\FinanceType;
use App\Models\Brother;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Listado de jornadas financieras
     */
    public function index()
    {
        $finances = Finance::orderByDesc('finance_date')
            ->paginate(10);

        return view('admin.finances.index', compact('finances'));
    }

    /**
     * Formulario para crear una nueva jornada financiera
     */
    public function create()
    {
        return view('admin.finances.create');
    }

    /**
     * Guardar nueva jornada financiera
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        Finance::create([
            'title'        => $validated['title'],
            'finance_date' => $validated['date'],
            'description'  => $validated['description'] ?? null,
            'is_closed'    => false,
            'created_by'   => auth()->id(),
        ]);

        return redirect()
            ->route('finances.index')
            ->with('success', 'Finanza creada correctamente.');
    }

    /**
     * Ver una jornada financiera
     */
    public function show(Finance $finance)
    {
        $finance->load([
            'entries.brother',
            'entries.type',
            'entries.creator',
        ]);

        $types = FinanceType::where('is_active', true)
            ->orderBy('name')
            ->get();

        $brothers = Brother::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'lastname', 'phone'])
            ->map(fn ($b) => [
                'id' => $b->id,
                'full_name' => trim($b->name.' '.$b->lastname),
            ])
            ->values();


        $totalsByType = $finance->entries
            ->groupBy(fn($e) => $e->type->name)
            ->map(fn($items) => $items->sum('amount'));


        // ✅ Agrupar por ID de tipo (más confiable)
        $entriesByTypeId = $finance->entries->groupBy('finance_type_id');

        // ✅ Orden: primero allows_multiple = false, luego true
        $orderedTypeIds = $types
            ->sortBy(fn($t) => $t->allows_multiple ? 1 : 0)
            ->pluck('id')
            ->toArray();

        // ✅ Reordenar el groupBy según el orden deseado
        $entriesByType = collect($orderedTypeIds)
            ->filter(fn($typeId) => $entriesByTypeId->has($typeId))
            ->mapWithKeys(function ($typeId) use ($entriesByTypeId, $types) {
                $typeName = optional($types->firstWhere('id', $typeId))->name ?? 'Tipo';
                return [$typeName => $entriesByTypeId->get($typeId)];
            });

        return view('admin.finances.show', compact(
            'finance',
            'types',
            'brothers',
            'entriesByType',
            'totalsByType'
        ));

    }

    /**
     * Cerrar jornada financiera
     */
    public function close(Finance $finance)
    {
        if ($finance->is_closed) {
            return back()->with('error', 'La jornada ya está cerrada.');
        }

        $finance->close(auth()->id());

        return back()->with('success', 'Jornada financiera cerrada correctamente.');
    }

    /**
     * Reabrir jornada financiera
     */
    public function reopen(Finance $finance)
    {
        if (! $finance->is_closed) {
            return back()->with('error', 'La jornada ya está abierta.');
        }

        $finance->reopen();

        return back()->with('success', 'Jornada financiera reabierta correctamente.');
    }
}
