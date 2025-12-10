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
            'files.*'      => 'file|max:51200', // 50MB por archivo (ajusta según necesites)
            'external_url' => 'nullable|url',
        ]);

        // Debug - Eliminar después de probar
        \Log::info('Media Store Request', [
            'type' => $request->type,
            'has_files' => $request->hasFile('files'),
            'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0,
            'external_url' => $request->external_url,
        ]);

        // VIDEO EXTERNO (YouTube, Vimeo, etc.)
        if ($request->filled('external_url')) {
            $media = new Media();
            $media->post_id = $post->id;
            $media->type = 'video';
            $media->is_external = true;
            $media->external_url = $request->external_url;
            $media->save();

            return back()->with('success', 'Video externo agregado correctamente');
        }

        // ARCHIVOS LOCALES (múltiples)
        if ($request->hasFile('files')) {
            $uploadedCount = 0;
            $errors = [];

            foreach ($request->file('files') as $file) {
                try {
                    $media = new Media();
                    $media->post_id = $post->id;
                    $media->type = $request->type;
                    $media->is_external = false;
                    $media->original_filename = $file->getClientOriginalName();

                    \Log::info('Procesando archivo', [
                        'filename' => $file->getClientOriginalName(),
                        'type' => $request->type,
                        'size' => $file->getSize(),
                    ]);

                    // IMAGEN - Convertir a WebP
                    if ($request->type === 'image') {
                        $path = $this->convertToWebP($file, 'media');
                        $media->file_path = $path;
                        $media->mime_type = 'image/webp';
                        
                        \Log::info('Imagen guardada', ['path' => $path]);
                    }

                    // VIDEO - Guardar directamente
                    if ($request->type === 'video') {
                        $extension = $file->getClientOriginalExtension();
                        $fileName = uniqid() . '.' . $extension;
                        $path = $file->storeAs('videos', $fileName, 'public');
                        
                        $media->file_path = $path;
                        $media->mime_type = $file->getMimeType();
                        
                        \Log::info('Video guardado', ['path' => $path]);
                    }

                    $media->save();
                    $uploadedCount++;

                } catch (\Exception $e) {
                    $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
                    \Log::error('Error subiendo archivo', [
                        'file' => $file->getClientOriginalName(),
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }

            if ($uploadedCount > 0) {
                $message = "$uploadedCount archivo(s) agregado(s) correctamente";
                if (!empty($errors)) {
                    $message .= '. Errores: ' . implode(', ', $errors);
                }
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'No se pudo subir ningún archivo. Errores: ' . implode(', ', $errors));
            }
        }

        return back()->with('error', 'Debes seleccionar archivos o una URL externa');
    }

    /**
     * Eliminar archivo multimedia
     */
    public function destroy(Media $media)
    {
        // Eliminar archivo físico si existe
        if (!$media->is_external && $media->file_path) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        return back()->with('success', 'Elemento eliminado correctamente');
    }

    /**
     * Convierte imagen a WebP (mismo método que PostController)
     */
    private function convertToWebP($uploadedFile, $folder = 'images', $quality = 85)
    {
        $filename = uniqid() . '.webp';
        $relativePath = $folder . '/' . $filename;
        $fullPath = storage_path('app/public/' . $relativePath);

        // Crear directorio si no existe
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Obtener información de la imagen
        $imageInfo = getimagesize($uploadedFile->getRealPath());
        
        if ($imageInfo === false) {
            throw new \Exception('Archivo no es una imagen válida');
        }

        $mimeType = $imageInfo['mime'];
        $sourceImage = null;

        try {
            // Cargar imagen según su tipo
            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/jpg':
                    $sourceImage = imagecreatefromjpeg($uploadedFile->getRealPath());
                    break;
                    
                case 'image/png':
                    $sourceImage = imagecreatefrompng($uploadedFile->getRealPath());
                    imagealphablending($sourceImage, true);
                    imagesavealpha($sourceImage, true);
                    break;
                    
                case 'image/gif':
                    $sourceImage = imagecreatefromgif($uploadedFile->getRealPath());
                    break;
                    
                case 'image/webp':
                    $sourceImage = imagecreatefromwebp($uploadedFile->getRealPath());
                    break;
                    
                default:
                    throw new \Exception('Formato no soportado: ' . $mimeType);
            }

            if ($sourceImage === false) {
                throw new \Exception('No se pudo procesar la imagen');
            }

            // Obtener dimensiones
            $width = imagesx($sourceImage);
            $height = imagesy($sourceImage);

            // Redimensionar si es muy grande
            $maxWidth = 1920;
            $maxHeight = 1920;

            if ($width > $maxWidth || $height > $maxHeight) {
                $ratio = min($maxWidth / $width, $maxHeight / $height);
                $newWidth = (int)($width * $ratio);
                $newHeight = (int)($height * $ratio);

                $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                
                imagecopyresampled(
                    $resizedImage, $sourceImage, 
                    0, 0, 0, 0, 
                    $newWidth, $newHeight, 
                    $width, $height
                );
                
                imagedestroy($sourceImage);
                $sourceImage = $resizedImage;
            }

            // Convertir a WebP
            imagewebp($sourceImage, $fullPath, $quality);
            imagedestroy($sourceImage);

            return $relativePath;

        } catch (\Exception $e) {
            if ($sourceImage !== null && is_resource($sourceImage)) {
                imagedestroy($sourceImage);
            }
            throw $e;
        }
    }
}