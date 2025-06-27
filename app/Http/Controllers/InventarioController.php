<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Categoria;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    // Listado
    public function index()
    {
        $breadcrumb = [
            ['name' => 'Inventario', 'url' => route('inventario.index')],

        ];
        $items = Inventario::with('categoria')->paginate(15);
        return view('inventario.index', compact('items', 'breadcrumb'));
    }

    // Formulario de alta
    public function create()
    {
        $breadcrumb = [
            ['name' => 'Inventario', 'url' => route('inventario.index')],
            ['name' => 'Crear', 'url' => '#'],
        ];
        $categorias = Categoria::pluck('nombre', 'id');
        return view('inventario.create', compact('categorias', 'breadcrumb'));
    }

    // Almacenar registro
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'codigo' => 'nullable|string|max:100',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'unidad_medida' => 'required|string|max:50',
            'precio_unitario' => 'required|numeric|min:0',
            'ubicacion' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|max:2048',
        ]);

        // Subir imagen si viene en la petición
        if ($request->hasFile('imagen')) {
            $data['imagen'] = FileUploadService::upload($request->file('imagen'), 'imagenes');
        }

        Inventario::create($data);

        return redirect()->route('inventario.index')
            ->with('success', 'Artículo creado correctamente.');
    }

    // Formulario de edición
    public function edit(Inventario $inventario)
    {
        $breadcrumb = [
            ['name' => 'Inventario', 'url' => route('inventario.index')],
            ['name' => 'Editar ' . $inventario->nombre, 'url' => '#'],
        ];
        $categorias = Categoria::pluck('nombre', 'id');
        return view('inventario.edit', compact('inventario', 'categorias', 'breadcrumb'));
    }

    // Actualizar registro
    public function update(Request $request, Inventario $inventario)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'codigo' => 'nullable|string|max:100',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'unidad_medida' => 'required|string|max:50',
            'precio_unitario' => 'required|numeric|min:0',
            'ubicacion' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = FileUploadService::upload($request->file('imagen'), 'imagenes');
        }

        $inventario->update($data);

        return redirect()->route('inventario.index')
            ->with('success', 'Artículo actualizado correctamente.');
    }

    // Eliminar
    public function destroy(Inventario $inventario)
    {
        $inventario->delete();
        return back()->with('success', 'Artículo eliminado.');
    }
}
