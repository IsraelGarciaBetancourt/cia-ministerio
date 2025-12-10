<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Subir múltiples imágenes o videos asociados a un post
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'type'         => 'required|in:image,video',
            'files'        => 'nullable|array',
            'files.*'      => 'file|max:51200', // 50MB
            'external_url' => 'nullable|url',
        ]);

        // ----- VIDEO EXTERNO -----
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

        // ----- ARCHIVOS LOCALES -----
        if ($request->hasFile('files')) {

            $uploadedCount = 0;
            $errors = [];

            foreach ($request->file('files') as $file) {
                try {
                    $media = new Media();
                    $media->post_id  = $post->id;
                    $media->type     = $request->type;
                    $media->is_external = false;
                    $media->original_filename = $file->getClientOriginalName();

                    // IMÁGENES → convertir a WebP
                    if ($request->type === 'image') {
                        $path = $this->convertToWebP($file, 'media');
                        $media->file_path = $path;
                        $media->mime_type = 'image/webp';
                    }

                    // VIDEOS → guardar sin conversión
                    if ($request->type === 'video') {
                        $extension = $file->getClientOriginalExtension();
                        $fileName = uniqid() . '.' . $extension;
                        $path = $file->storeAs('videos', $fileName, 'public');

                        $media->file_path = $path;
                        $media->mime_type = $file->getMimeType();
                    }

                    $media->save();
                    $uploadedCount++;

                } catch (\Exception $e) {
                    $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
                }
            }

            if ($uploadedCount > 0) {
                $msg = "$uploadedCount archivo(s) agregado(s) correctamente";
                if (!empty($errors)) $msg .= ". Errores: " . implode(', ', $errors);
                return back()->with('success', $msg);
            }

            return back()->with('error', 'No se pudo subir ningún archivo.');
        }

        return back()->with('error', 'Debes seleccionar archivos o una URL externa');
    }

    /**
     * Eliminar archivo multimedia
     */
    public function destroy(Media $media)
    {
        if (!$media->is_external && $media->file_path) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        return back()->with('success', 'Elemento eliminado correctamente');
    }

    /**
     * Normaliza CUALQUIER URL de YouTube a formato embed limpio
     */
    private function cleanYoutubeUrl($url)
    {
        $videoId = null;

        // youtu.be/ID
        if (preg_match('/youtu\.be\/([^?&]+)/', $url, $m)) {
            $videoId = $m[1];
        }

        // youtube.com/watch?v=ID
        elseif (preg_match('/v=([^?&]+)/', $url, $m)) {
            $videoId = $m[1];
        }

        // youtube.com/embed/ID
        elseif (preg_match('/embed\/([^?&]+)/', $url, $m)) {
            $videoId = $m[1];
        }

        // youtube.com/shorts/ID
        elseif (preg_match('/shorts\/([^?&]+)/', $url, $m)) {
            $videoId = $m[1];
        }

        if ($videoId) {
            return "https://www.youtube.com/embed/" . $videoId;
        }

        // Si no se detectó un ID, se devuelve original
        return $url;
    }

    /**
     * Convierte imagen a WebP
     */
    private function convertToWebP($uploadedFile, $folder = 'images', $quality = 85)
    {
        $filename = uniqid() . '.webp';
        $relativePath = $folder . '/' . $filename;
        $fullPath = storage_path('app/public/' . $relativePath);

        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $imageInfo = getimagesize($uploadedFile->getRealPath());
        if ($imageInfo === false) {
            throw new \Exception('Archivo no es una imagen válida');
        }

        $mime = $imageInfo['mime'];

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $source = imagecreatefromjpeg($uploadedFile->getRealPath());
                break;
            case 'image/png':
                $source = imagecreatefrompng($uploadedFile->getRealPath());
                imagealphablending($source, true);
                imagesavealpha($source, true);
                break;
            case 'image/gif':
                $source = imagecreatefromgif($uploadedFile->getRealPath());
                break;
            case 'image/webp':
                $source = imagecreatefromwebp($uploadedFile->getRealPath());
                break;
            default:
                throw new \Exception('Formato no soportado: ' . $mime);
        }

        imagewebp($source, $fullPath, $quality);
        imagedestroy($source);

        return $relativePath;
    }
}
