<?php

namespace App\Http\Controllers;

use App\Models\FileCategory;
use App\Models\FileGroup;
use App\Models\PrivateFile;
use App\Models\PrivateFileAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
            'description' => 'nullable|string|max:5000',
            'attachments' => 'nullable|array|max:50',
            'attachments.*' => 'file|max:51200',
        ]);

        $file = PrivateFile::create([
            'file_category_id' => $category->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'uploaded_by' => Auth::id(),
        ]);

        $uploadedCount = 0;
        $errors = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $uploaded) {
                try {
                    $path = $uploaded->store('files', 'local');

                    PrivateFileAttachment::create([
                        'private_file_id' => $file->id,
                        'file_path' => $path,
                        'original_filename' => $uploaded->getClientOriginalName(),
                        'mime_type' => $uploaded->getMimeType(),
                        'size' => $uploaded->getSize(),
                    ]);

                    $uploadedCount++;
                } catch (\Exception $e) {
                    $errors[] = $uploaded->getClientOriginalName();
                    Log::error('Error subiendo archivo: ' . $e->getMessage());
                }
            }
        }

        $message = "Documento creado con {$uploadedCount} archivo(s)";
        if (!empty($errors)) {
            $message .= ". Errores con: " . implode(', ', $errors);
        }

        return redirect()
            ->route('files.groups.categories.show', [$group, $category])
            ->with('success', $message);
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'attachments' => 'nullable|array|max:50',
            'attachments.*' => 'nullable|file|max:51200',
            'delete_attachments' => 'nullable|array',
        ]);

        $file->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        $deletedCount = 0;
        if (!empty($data['delete_attachments'])) {
            foreach ($data['delete_attachments'] as $id) {
                $attachment = PrivateFileAttachment::find($id);
                if ($attachment && $attachment->private_file_id === $file->id) {
                    Storage::delete($attachment->file_path);
                    $attachment->delete();
                    $deletedCount++;
                }
            }
        }

        $uploadedCount = 0;
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $uploaded) {
                try {
                    $path = $uploaded->store('files', 'local');

                    $file->attachments()->create([
                        'original_filename' => $uploaded->getClientOriginalName(),
                        'file_path' => $path,
                        'mime_type' => $uploaded->getMimeType(),
                        'size' => $uploaded->getSize(),
                    ]);

                    $uploadedCount++;
                } catch (\Exception $e) {
                    Log::error('Error subiendo archivo: ' . $e->getMessage());
                }
            }
        }

        $message = "Archivo actualizado";
        if ($uploadedCount > 0) $message .= " (+{$uploadedCount} nuevos)";
        if ($deletedCount > 0) $message .= " (-{$deletedCount} eliminados)";

        return redirect()
            ->route('files.groups.categories.files.show', [$group, $category, $file])
            ->with('success', $message);
    }

    // =========================
    // ELIMINAR ARCHIVO COMPLETO
    // =========================
    public function destroy(FileGroup $group, FileCategory $category, PrivateFile $file)
    {
        $attachmentCount = $file->attachments->count();

        foreach ($file->attachments as $att) {
            try {
                Storage::delete($att->file_path);
                $att->delete();
            } catch (\Exception $e) {
                Log::error('Error eliminando adjunto: ' . $e->getMessage());
            }
        }

        $file->delete();

        return redirect()
            ->route('files.groups.categories.show', [$group, $category])
            ->with('success', "Documento y {$attachmentCount} archivo(s) eliminados");
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
    // VER ARCHIVO (Imagen, PDF, etc) - CON RUTAS MÚLTIPLES
    // =========================
    public function viewAttachment(PrivateFileAttachment $attachment)
    {
        // Intentar múltiples rutas posibles (NECESARIO para compatibilidad)
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
            Log::error('Archivo no encontrado', [
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
    // DESCARGAR ADJUNTO - CON RUTAS MÚLTIPLES
    // =========================
    public function downloadAttachment(PrivateFileAttachment $attachment)
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
            Log::error('Archivo no encontrado para descarga', [
                'file_path' => $attachment->file_path,
                'tried_paths' => $possiblePaths
            ]);
            abort(404, 'Archivo no encontrado');
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

        try {
            Storage::delete($attachment->file_path);
            $attachment->delete();
            $message = 'Adjunto eliminado correctamente';
        } catch (\Exception $e) {
            Log::error('Error eliminando adjunto: ' . $e->getMessage());
            $message = 'Error al eliminar el adjunto';
        }

        return redirect()
            ->route('files.groups.categories.files.show', [$group, $category, $file])
            ->with('success', $message);
    }
}