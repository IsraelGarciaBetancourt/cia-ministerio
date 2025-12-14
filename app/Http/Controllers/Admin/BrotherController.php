<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brother;
use Illuminate\Http\Request;

class BrotherController extends Controller
{
    /**
     * Listado de hermanos
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $brothers = Brother::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.brothers.index', compact('brothers', 'search'));
    }

    /**
     * Formulario crear hermano
     */
    public function create()
    {
        return view('admin.brothers.create');
    }

    /**
     * Guardar hermano
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'lastname'  => 'nullable|string|max:255',
            'phone'     => 'nullable|string|max:50',
            'email'     => 'nullable|email|max:255',
            'zona'     => 'nullable|string|max:50',
            'notes'     => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        // Checkbox fix (cuando no viene, es false)
        $validated['is_active'] = $request->has('is_active');

        Brother::create($validated);

        return redirect()
            ->route('brothers.index')
            ->with('success', 'Hermano registrado correctamente.');
    }


    /**
     * Editar hermano
     */
    public function edit(Brother $brother)
    {
        return view('admin.brothers.edit', compact('brother'));
    }

    /**
     * Actualizar hermano
     */
    public function update(Request $request, Brother $brother)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'lastname'  => 'nullable|string|max:255',
            'phone'     => 'nullable|string|max:50',
            'email'     => 'nullable|email|max:255',
            'zona'     => 'nullable|string|max:50',
            'notes'     => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $brother->update($validated);

        return redirect()
            ->route('brothers.index')
            ->with('success', 'Hermano actualizado correctamente.');
    }

    public function toggle(Brother $brother)
    {
        $brother->update([
            'is_active' => ! $brother->is_active
        ]);

        return back()->with('success', 'Estado del hermano actualizado');
    }

    public function destroy(Brother $brother)
    {
        if ($brother->financeEntries()->exists()) {
            return back()->with('error', 
                'No se puede eliminar este hermano porque tiene movimientos financieros registrados.'
            );
        }

        $brother->delete();

        return back()->with('success', 'Hermano eliminado correctamente.');
    }

}
