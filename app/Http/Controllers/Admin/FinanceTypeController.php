<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class FinanceTypeController extends Controller
{
    public function index()
    {
        $types = FinanceType::orderBy('name')->paginate(15);

        return view('admin.finance-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.finance-types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'requires_brother' => 'boolean',
            'allows_multiple'  => 'boolean',
            'is_active'        => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['requires_brother'] = $request->boolean('requires_brother');
        $data['allows_multiple']  = $request->boolean('allows_multiple');
        $data['is_active']        = $request->boolean('is_active');

        FinanceType::create($data);

        return redirect()
            ->route('finance-types.index')
            ->with('success', 'Tipo de finanza creado correctamente');
    }


    public function edit(FinanceType $type)
    {
        return view('admin.finance-types.edit', compact('type'));
    }

    public function update(Request $request, FinanceType $type)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'icon' => ['nullable','string','max:50'],
            'color' => ['nullable','string','max:30'],
            'requires_brother' => ['nullable'],
            'allows_multiple' => ['nullable'],
            'is_active' => ['nullable'],
            // slug no editable por ahora (para evitar romper reglas)
        ]);

        $data['requires_brother'] = $request->boolean('requires_brother');
        $data['allows_multiple']  = $request->boolean('allows_multiple');
        $data['is_active']        = $request->boolean('is_active');

        // ✅ Si cambia el nombre, regeneramos slug (manteniendo único)
        if ($data['name'] !== $type->name) {
            $baseSlug = Str::slug($data['name']);
            $slug = $baseSlug;
            $i = 2;
            while (FinanceType::where('slug', $slug)->where('id', '!=', $type->id)->exists()) {
                $slug = $baseSlug.'-'.$i++;
            }
            $data['slug'] = $slug;
        }

        $type->update($data);

        return redirect()->route('finance-types.index')
            ->with('success', 'Tipo de finanza actualizado.');
    }

    public function toggle(FinanceType $type)
    {
        $type->is_active = !$type->is_active;
        $type->save();

        return back()->with('success', 'Estado actualizado.');
    }

    public function destroy(FinanceType $type)
    {
        // ✅ No permitir borrar si ya tiene movimientos asociados
        if ($type->entries()->exists()) {
            return back()->with('error', 'No se puede eliminar: tiene movimientos financieros asociados.');
        }

        // (opcional) Proteger “diezmo” y “ofrenda”
        if (in_array($type->slug, ['diezmo', 'ofrenda'])) {
            return back()->with('error', 'No se puede eliminar un tipo base del sistema.');
        }

        $type->delete();

        return back()->with('success', 'Tipo eliminado correctamente.');
    }
}
