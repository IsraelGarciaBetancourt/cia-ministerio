<?php

namespace App\Http\Controllers;

use App\Models\FileCategory;
use App\Models\FileGroup;
use Illuminate\Http\Request;

class FileCategoryController extends Controller
{
    // Crear categoría dentro de un grupo
    public function create(FileGroup $group)
    {
        return view('admin.files.categories.create', compact('group'));
    }

    public function store(Request $request, FileGroup $group)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['file_group_id'] = $group->id;

        FileCategory::create($data);

        return redirect()->route('files.groups.show', $group)
            ->with('success', 'Categoría creada correctamente');
    }

    public function edit(FileGroup $group, FileCategory $category)
    {
        return view('admin.files.categories.edit', compact('group', 'category'));
    }

    public function update(Request $request, FileGroup $group, FileCategory $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($data);

        return redirect()->route('files.groups.show', $group)
            ->with('success', 'Categoría actualizada');
    }

    public function destroy(FileGroup $group, FileCategory $category)
    {
        $category->delete();

        return redirect()->route('files.groups.show', $group)
            ->with('success', 'Categoría eliminada');
    }

    // Lista de documentos de la categoría (con buscador)
    public function show(FileGroup $group, FileCategory $category, Request $request)
    {
        $q = $request->query('q');

        $files = $category->privateFiles()
            ->when($q, function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
            })
            ->with('user')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.files.categories.show', compact('group', 'category', 'files', 'q'));
    }
}
