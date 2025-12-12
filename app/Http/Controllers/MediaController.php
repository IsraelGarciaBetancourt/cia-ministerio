<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;

class MediaController extends Controller
{
    /**
     * Subir UN SOLO archivo a la vez (llamado por AJAX)
     */
    public function storeSingle(Request $request, Post $post)
    {
        // Aumentar límites para este proceso
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '120');
        
        $request->validate([
            'type' => 'required|in:image,video',
            'file' => 'required|file|max:10240', // 10MB por archivo
        ]);

        try {
            $file = $request->file('file');
            
            $media = new Media();
            $media->post_id = $post->id;
            $media->type = $request->type;
            $media->original_filename = $file->getClientOriginalName();

            // IMAGEN → WebP
            if ($request->type === 'image') {
                $path = $this->convertToWebP($file, 'media', 75); // Calidad 75 para ahorrar más
                $media->file_path = $path;
                $media->mime_type = 'image/webp';
            } 
            // VIDEO → guardar directo
            elseif ($request->type === 'video') {
                $path = $file->store('media', 'public');
                $media->file_path = $path;
                $media->mime_type = $file->getClientMimeType();
            }

            $media->save();

            // Liberar memoria
            gc_collect_cycles();

            return response()->json([
                'success' => true,
                'message' => 'Archivo procesado correctamente',
                'filename' => $file->getClientOriginalName(),
                'size' => round($file->getSize() / 1024, 2) . ' KB'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en storeSingle: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'filename' => $request->file('file')->getClientOriginalName()
            ], 500);
        }
    }

    /**
     * Método original para compatibilidad (videos externos)
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'type'         => 'required|in:image,video',
            'external_url' => 'nullable|url',
        ]);

        // Solo para videos externos
        if ($request->filled('external_url')) {
            $cleanUrl = $this->cleanYoutubeUrl($request->external_url);

            Media::create([
                'post_id'      => $post->id,
                'type'         => 'video',
                'is_external'  => true,
                'external_url' => $cleanUrl,
            ]);

            return back()->with('success', 'Video externo agregado correctamente');
        }

        return back()->with('error', 'Use la subida por archivos individuales para imágenes y videos locales');
    }

    /**
     * Eliminar archivo multimedia
     */
    public function destroy(Media $media)
    {
        if ($media->file_path) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();
        return back()->with('success', 'Archivo multimedia eliminado correctamente');
    }

    /**
     * Normaliza URLs de YouTube
     */
    private function cleanYoutubeUrl($url)
    {
        $videoId = null;

        if (preg_match('/(?:youtube\\.com\\/(?:embed\\/|v\\/|watch\\?v=|watch\\?.+&v=|shorts\\/)|youtu\\.be\\/)([^"&?\\/ ]{11})/i', $url, $m)) {
            $videoId = $m[1];
        }

        if ($videoId) {
            return "https://www.youtube.com/embed/" . $videoId;
        }

        return $url;
    }

    /**
     * [SUPER OPTIMIZADO] Convierte imagen a WebP
     */
    private function convertToWebP($uploadedFile, $folder = 'images', $quality = 75)
    {
        $filename = uniqid() . '.webp';
        $relativePath = $folder . '/' . $filename;

        try {
            // Cargar y optimizar
            $image = Image::make($uploadedFile);
            
            $width = $image->width();
            $height = $image->height();

            // Límites más agresivos
            $maxWidth = 1600;
            $maxHeight = 1600;

            // Imágenes muy grandes → reducir aún más
            if ($width > 3000 || $height > 3000) {
                $maxWidth = 1200;
                $maxHeight = 1200;
            }

            if ($width > $maxWidth || $height > $maxHeight) {
                $image->resize($maxWidth, $maxHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Codificar y guardar
            $encoded = $image->encode('webp', $quality);
            Storage::disk('public')->put($relativePath, (string) $encoded);
            
            // CRÍTICO: Liberar memoria inmediatamente
            $image->destroy();
            unset($image, $encoded);
            gc_collect_cycles();

            return $relativePath;

        } catch (\Exception $e) {
            \Log::error("Error convirtiendo a WebP: " . $e->getMessage());
            throw $e;
        }
    }
}