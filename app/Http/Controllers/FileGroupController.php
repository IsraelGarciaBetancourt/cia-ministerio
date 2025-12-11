<?php

namespace App\Http\Controllers;

use App\Models\FileGroup;
use Illuminate\Http\Request;

class FileGroupController extends Controller
{
    public function index()
    {
        $groups = FileGroup::withCount([
            'categories as documents_count' => function ($q) {
                $q->withCount('privateFiles');
            }
        ])->get();

        return view('admin.files.groups.index', compact('groups'));
    }

    public function create()
    {
        return view('admin.files.groups.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        FileGroup::create($data);

        return redirect()->route('files.groups.index')
            ->with('success', 'Grupo creado correctamente');
    }

    public function edit(FileGroup $group)
    {
        return view('admin.files.groups.edit', compact('group'));
    }

    public function update(Request $request, FileGroup $group)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $group->update($data);

        return redirect()->route('files.groups.index')
            ->with('success', 'Grupo actualizado correctamente');
    }

    public function destroy(FileGroup $group)
    {
        $group->delete();

        return redirect()->route('files.groups.index')
            ->with('success', 'Grupo eliminado');
    }

    // Mostrar categorías del grupo
    public function show(FileGroup $group)
    {
        $categories = $group->categories()
            ->withCount('privateFiles')
            ->orderBy('name')
            ->get();

        $group->load([
            'categories' => function ($query) {
                $query->withCount('privateFiles');
            }
        ]);

        return view('admin.files.groups.show', compact('group', 'categories'));
    }
}
