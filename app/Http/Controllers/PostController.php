<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function publicIndex()
    {
        $posts = Post::latest()->paginate(10);
        return view('public.blog.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $media = $post->media()->orderBy('id')->get();
        return view('public.blog.show', compact('post', 'media'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|string',
            'content'   => 'required|string',
            'post_date' => 'nullable|date',
            'cover'     => 'nullable|image|max:5120', // max 5MB
        ]);

        $post = new Post();
        $post->title      = $request->title;
        $post->content    = $request->content;
        $post->post_date  = $request->post_date;
        $post->created_by = Auth::id();

        // Convertir portada a WebP
        if ($request->hasFile('cover')) {
            $post->cover_image = $this->convertToWebP($request->file('cover'), 'covers');
        }

        $post->save();

        return redirect()->route('posts.index')
            ->with('success', 'Post creado correctamente');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title'     => 'required|string',
            'content'   => 'required|string',
            'post_date' => 'nullable|date',
            'cover'     => 'nullable|image|max:5120',
        ]);

        $post->title     = $request->title;
        $post->content   = $request->content;
        $post->post_date = $request->post_date;

        if ($request->hasFile('cover')) {
            // Borrar portada anterior
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }

            // Guardar nueva imagen
            $post->cover_image = $this->convertToWebP($request->file('cover'), 'covers');
        }

        $post->save();

        return redirect()->route('posts.index')
            ->with('success', 'Post actualizado correctamente');
    }

    public function destroy(Post $post)
    {
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }

        $post->delete();
        return back()->with('success', 'Post eliminado correctamente');
    }

    /**
     * Convierte una imagen a WebP usando GD nativo de PHP
     */
    private function convertToWebP($uploadedFile, $folder = 'images', $quality = 85)
    {
        // Generar nombre único
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

        // Cargar imagen según su tipo
        try {
            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/jpg':
                    $sourceImage = imagecreatefromjpeg($uploadedFile->getRealPath());
                    break;
                    
                case 'image/png':
                    $sourceImage = imagecreatefrompng($uploadedFile->getRealPath());
                    // Preservar transparencia
                    imagealphablending($sourceImage, true);
                    imagesavealpha($sourceImage, true);
                    break;
                    
                case 'image/gif':
                    $sourceImage = imagecreatefromgif($uploadedFile->getRealPath());
                    break;
                    
                case 'image/webp':
                    // Si ya es WebP, solo copiarlo
                    $sourceImage = imagecreatefromwebp($uploadedFile->getRealPath());
                    break;
                    
                default:
                    throw new \Exception('Formato de imagen no soportado: ' . $mimeType);
            }

            if ($sourceImage === false) {
                throw new \Exception('No se pudo procesar la imagen');
            }

            // Obtener dimensiones originales
            $width = imagesx($sourceImage);
            $height = imagesy($sourceImage);

            // Redimensionar si es muy grande (opcional)
            $maxWidth = 1920;
            $maxHeight = 1920;

            if ($width > $maxWidth || $height > $maxHeight) {
                $ratio = min($maxWidth / $width, $maxHeight / $height);
                $newWidth = (int)($width * $ratio);
                $newHeight = (int)($height * $ratio);

                $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                
                // Preservar transparencia en la imagen redimensionada
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                
                imagecopyresampled(
                    $resizedImage, 
                    $sourceImage, 
                    0, 0, 0, 0, 
                    $newWidth, 
                    $newHeight, 
                    $width, 
                    $height
                );
                
                imagedestroy($sourceImage);
                $sourceImage = $resizedImage;
            }

            // Convertir a WebP
            imagewebp($sourceImage, $fullPath, $quality);
            
            // Liberar memoria
            imagedestroy($sourceImage);

            return $relativePath;

        } catch (\Exception $e) {
            // Limpiar memoria en caso de error
            if ($sourceImage !== null && is_resource($sourceImage)) {
                imagedestroy($sourceImage);
            }
            throw $e;
        }
    }
}