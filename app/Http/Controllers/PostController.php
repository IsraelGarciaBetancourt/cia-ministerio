<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::latest(); // Empezamos a construir la query

        // Filtro por título (la "lupa")
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Ejecutamos la paginación, conservando los parámetros de la URL (el 'search')
        $posts = $query->paginate(10)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function publicIndex(Request $request)
    {
        $query = Post::query();

        // Filtro por rango de fechas
        if ($request->filled('from')) {
            $query->whereDate('post_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('post_date', '<=', $request->to);
        }

        // Filtro por texto
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Filtro por año
        if ($request->filled('year')) {
            $query->whereYear('post_date', $request->year);
        }

        $posts = $query->latest()->paginate(10)->withQueryString();

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
        // Eliminar portada
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }

        // NUEVO: Eliminar TODOS los archivos multimedia asociados
        foreach ($post->media as $media) {
            if ($media->file_path) {
                Storage::disk('public')->delete($media->file_path);
            }
            $media->delete();
        }

        $post->delete();
        
        return back()->with('success', 'Post y todos sus archivos eliminados correctamente');
    }

    /**
     * [OPTIMIZADO] Convierte una imagen a WebP usando Intervention Image.
     * Reemplaza la versión original basada en GD.
     */
    private function convertToWebP($uploadedFile, $folder = 'images', $quality = 85)
    {
        // Generar nombre único
        $filename = uniqid() . '.webp';
        $relativePath = $folder . '/' . $filename;

        // 1. Cargar la imagen usando Intervention Image (Gestión de memoria eficiente)
        $image = Image::make($uploadedFile);

        // 2. Definir dimensiones máximas
        $maxWidth = 1920;
        $maxHeight = 1920;
        
        // 3. Redimensionar si es más grande (Manteniendo el aspecto)
        if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
            $image->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); // No agranda imágenes pequeñas
            });
        }

        // 4. Codificar a WebP y guardar en el disco 'public'
        // Intervention se encarga de la conversión de PNG con transparencia.
        Storage::disk('public')->put($relativePath, (string) $image->encode('webp', $quality));
        
        // 5. Devolver el path relativo
        return $relativePath;
    }
}