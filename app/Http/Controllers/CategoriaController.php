<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Interfaces\CatalogoInterface;

class CategoriaController extends Controller
{

    protected $CatalogoRepository;
    public function __construct(CatalogoInterface $CatalogoInterface)
    {

        $this->CatalogoRepository = $CatalogoInterface;
    }
    public function index()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Categoria', 'url' => route('categorias.index')],


        ];
        $categorias = Categoria::paginate(10);
        return view('categorias.index', compact('breadcrumb', 'categorias'));
    }

    public function create()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Catalogo', 'url' => route('catalogos.index')],
            ['name' => 'Crear Categoria', 'url' => route('categorias.index')],

        ];

        return view('catalogo.createCategoria', compact('breadcrumb'));
    }

    public function create_()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Categoria', 'url' => route('categorias.index')],
            ['name' => 'Crear Categoria', 'url' => route('categorias.index')],

        ];

        return view('categorias.createCategoria', compact('breadcrumb'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'required|in:0,1',
        ]);

        $this->CatalogoRepository->GuardarCategoria($request);


        return redirect()->route('cat_catalogos.index')->with('status', 'Categoría creada correctamente.');
    }

    public function store_(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'required|in:0,1',
        ]);

        $this->CatalogoRepository->GuardarCategoria($request);


        return redirect()->route('categorias.index')->with('status', 'Categoría creada correctamente.');
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Categorias', 'url' => route('categorias.index')],
            ['name' => 'Editar Categoria', 'url' => route('categorias.index')],

        ];
        return view('catalogo.editCategoria', compact('id', 'categoria', 'breadcrumb'));
    }

    public function edit_($id)
    {
        $categoria = Categoria::findOrFail($id);
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Categorias', 'url' => route('categorias.index')],
            ['name' => 'Editar Categoria', 'url' => route('categorias.index')],

        ];
        return view('categorias.editCategoria', compact('id', 'categoria', 'breadcrumb'));
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'required|in:0,1',
        ]);

        $this->CatalogoRepository->EditarCategoria($request, $categoria);


        return redirect()->route('catalogo.index')->with('status', 'Categoría actualizada.');
    }

    public function update_(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'required|in:0,1',
        ]);

        $this->CatalogoRepository->EditarCategoria($request, $categoria);


        return redirect()->route('categorias.index')->with('status', 'Categoría actualizada.');
    }

    public function destroy_($id)
    {
        $categoria = Categoria::findOrFail($id);

        $categoria->delete();

        return redirect()->route('categorias.index')->with('status', 'Categoría eliminada.');
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);

        $categoria->delete();

        return redirect()->route('cat_catalogo.index')->with('status', 'Categoría eliminada.');
    }
}
