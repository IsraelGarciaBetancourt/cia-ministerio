<?php

namespace App\Http\Controllers;

use App\Models\PrivateFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PrivateFileController extends Controller
{
    // Listar archivos privados
    public function index()
    {
        $files = PrivateFile::latest()->paginate(15);
        return view('admin.private-files.index', compact('files'));
    }

    // Subir archivo
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $file = $request->file('file');

        $path = $file->store('private/files', 'local'); // almacenamiento privado

        PrivateFile::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => Auth::id(),
        ]);

        return back()->with('success', 'Archivo subido correctamente');
    }

    // Descargar desde zona privada
    public function download(PrivateFile $privateFile)
    {
        return Storage::download($privateFile->file_path, $privateFile->file_name);
    }

    // Borrar archivo privado
    public function destroy(PrivateFile $privateFile)
    {
        $privateFile->delete(); // El modelo ya borra el archivo automáticamente
        return back()->with('success', 'Archivo eliminado');
    }

}
