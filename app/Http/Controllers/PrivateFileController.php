<?php

namespace App\Http\Controllers;

use App\Models\FileCategory;
use App\Models\FileGroup;
use App\Models\PrivateFile;
use App\Models\PrivateFileAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PrivateFileController extends Controller
{
    // =========================
    // CREAR ARCHIVO
    // =========================
    public function create(FileGroup $group, FileCategory $category)
    {
        return view('admin.files.files.create', compact('group', 'category'));
    }

    public function store(Request $request, FileGroup $group, FileCategory $category)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:51200',
        ]);

        $file = PrivateFile::create([
            'file_category_id' => $category->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'uploaded_by' => Auth::id(),
        ]);

        // Guardar adjuntos
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $uploaded) {
                $path = $uploaded->store('files', 'local'); // Sin 'private/' porque ya está en local

                PrivateFileAttachment::create([
                    'private_file_id' => $file->id,
                    'file_path' => $path,
                    'original_filename' => $uploaded->getClientOriginalName(),
                    'mime_type' => $uploaded->getMimeType(),
                    'size' => $uploaded->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('files.groups.categories.show', [$group, $category])
            ->with('success', 'Documento creado correctamente');
    }

    // =========================
    // VER ARCHIVO
    // =========================
    public function show(FileGroup $group, FileCategory $category, PrivateFile $file)
    {
        $file->load('attachments');
        return view('admin.files.files.show', compact('group', 'category', 'file'));
    }

    // =========================
    // EDITAR ARCHIVO
    // =========================
    public function edit(FileGroup $group, FileCategory $category, PrivateFile $file)
    {
        $file->load('attachments');
        return view('admin.files.files.edit', compact('group', 'category', 'file'));
    }

    public function update(Request $request, FileGroup $group, FileCategory $category, PrivateFile $file)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'attachments.*' => 'nullable|file',
            'delete_attachments' => 'nullable|array',
        ]);

        // Actualizar campos
        $file->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        // Eliminar adjuntos si el usuario los marcó
        if (!empty($data['delete_attachments'])) {
            foreach ($data['delete_attachments'] as $id) {
                $attachment = PrivateFileAttachment::find($id);
                if ($attachment) {
                    Storage::delete($attachment->file_path);
                    $attachment->delete();
                }
            }
        }

        // Subir nuevos adjuntos
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $uploaded) {
                $path = $uploaded->store('files', 'local'); // Sin 'private/'

                $file->attachments()->create([
                    'original_filename' => $uploaded->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $uploaded->getMimeType(),
                    'size' => $uploaded->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('files.groups.categories.files.show', [$group, $category, $file])
            ->with('success', 'Archivo actualizado correctamente');
    }

    // =========================
    // ELIMINAR ARCHIVO COMPLETO
    // =========================
    public function destroy(FileGroup $group, FileCategory $category, PrivateFile $file)
    {
        foreach ($file->attachments as $att) {
            Storage::delete($att->file_path);
            $att->delete();
        }

        $file->delete();

        return redirect()
            ->route('files.groups.categories.show', [$group, $category])
            ->with('success', 'Documento eliminado');
    }

    // =========================
    // PREVIEW DE PDF
    // =========================
    public function preview(PrivateFileAttachment $attachment)
    {
        if ($attachment->mime_type !== 'application/pdf') {
            abort(404);
        }

        $file = Storage::disk('local')->get($attachment->file_path);

        return response($file)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="'.$attachment->original_filename.'"');
    }

    // =========================
    // VER ARCHIVO (Imagen, PDF, etc)
    // =========================
    public function viewAttachment(PrivateFileAttachment $attachment)
    {
        // Intentar múltiples rutas posibles
        $possiblePaths = [
            storage_path('app/' . $attachment->file_path),
            storage_path('app/private/' . $attachment->file_path),
        ];

        $path = null;
        foreach ($possiblePaths as $possiblePath) {
            if (file_exists($possiblePath)) {
                $path = $possiblePath;
                break;
            }
        }

        if (!$path) {
            \Log::error('Archivo no encontrado', [
                'file_path' => $attachment->file_path,
                'tried_paths' => $possiblePaths
            ]);
            abort(404, 'Archivo no encontrado en el servidor');
        }

        return response()->file($path, [
            'Content-Type' => $attachment->mime_type,
            'Content-Disposition' => 'inline; filename="' . $attachment->original_filename . '"'
        ]);
    }

    // =========================
    // DESCARGAR ADJUNTO
    // =========================
    public function downloadAttachment(PrivateFileAttachment $attachment)
    {
        $path = storage_path('app/' . $attachment->file_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path, $attachment->original_filename);
    }

    // =========================
    // ELIMINAR ADJUNTO INDIVIDUAL
    // =========================
    public function destroyAttachment(PrivateFileAttachment $attachment)
    {
        $file = $attachment->privateFile;
        $group = $file->category->group;
        $category = $file->category;

        Storage::delete($attachment->file_path);
        $attachment->delete();

        return redirect()
            ->route('files.groups.categories.files.show', [$group, $category, $file])
            ->with('success', 'Adjunto eliminado correctamente');
    }
}