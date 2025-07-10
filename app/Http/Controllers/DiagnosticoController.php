<?php

namespace App\Http\Controllers;

use App\Models\Tratamiento;
use App\Models\Diagnostico;
use Illuminate\Http\Request;
use App\Models\Catalogo;
use App\Interfaces\CatalogoInterface;


class DiagnosticoController extends Controller
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
            ['name' => 'Diagnosticos', 'url' => route('home')],
        ];
        $diagnosticos = Diagnostico::with('tratamiento')->get();
        $tratamiento = [];
        return view('diagnosticos.index', compact('breadcrumb', 'tratamiento', 'diagnosticos'));
    }

    public function create()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Diagnosticos', 'url' => route('home')],
            ['name' => 'Crear', 'url' => route('home')],

        ];
        $tratamientos = Tratamiento::all();
        $diagnosticos = Catalogo::where('categoria_id', 6)->get();
        return view('diagnosticos.create', compact('breadcrumb', 'tratamientos', 'diagnosticos'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'tratamiento_id' => 'required|exists:tratamientos,id',
            'cod_diagnostico' => 'required|string|max:50|unique:diagnosticos,cod_diagnostico',
            'fecha_diagnostico' => 'required|date',
            'estado' => 'required|in:activo,inactivo',
            'observacion' => 'nullable|string|max:1000',
        ]);

        Diagnostico::create([
            'tratamiento_id' => $request->tratamiento_id,
            'cod_diagnostico' => $request->cod_diagnostico,
            'fecha_diagnostico' => $request->fecha_diagnostico,
            'estado' => $request->estado,
            'observacion' => $request->observacion,
        ]);

        return redirect()->route('diagnosticos.index')
            ->with('status', 'Diagnóstico creado correctamente.');
    }

    public function destroy($tratamiento_id, $id)
    {
        $diagnostico = Diagnostico::where('tratamiento_id', $tratamiento_id)->findOrFail($id);
        $diagnostico->delete();

        return redirect()->route('diagnosticos.index', $tratamiento_id)
            ->with('success', 'Diagnóstico eliminado.');
    }
    public function edit($tratamiento_id, $diagnostico_id)
    {
        $tratamiento = Tratamiento::findOrFail($tratamiento_id);
        $diagnostico = Diagnostico::where('tratamiento_id', $tratamiento_id)->findOrFail($diagnostico_id);

        return view('diagnosticos.edit', compact('tratamiento', 'diagnostico'));
    }

    public function update(Request $request, $tratamiento_id, $diagnostico_id)
    {
        $request->validate([
            'cod_diagnostico' => 'required|string|max:50',
            'fecha_diagnostico' => 'required|date',
            'estado' => 'required|in:activo,inactivo',
            'observacion' => 'nullable|string',
        ]);

        $diagnostico = Diagnostico::where('tratamiento_id', $tratamiento_id)->findOrFail($diagnostico_id);

        $diagnostico->update($request->all());

        return redirect()->route('diagnosticos.index', $tratamiento_id)
            ->with('success', 'Diagnóstico actualizado correctamente.');
    }


    public function store_ajax(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $codigo_catalogo = $this->CatalogoRepository->generarNuevoCodigoCatalogo(6);

        $data = [
            'categoria' => 6,
            'catalogo_parent' => null,
            'catalogo_codigo' => $codigo_catalogo,
            'catalogo_descripcion' => $request->input('descripcion'),
            'catalogo_estado' => 1,
        ];
        $nuevoRequest = new Request($data);

        $diagnostico = $this->CatalogoRepository->GuardarCatalogo($nuevoRequest);

        return response()->json([
            'success' => true,
            'id' => $diagnostico->catalogo_codigo,
            'descripcion' => $diagnostico->catalogo_descripcion
        ]);
    }

}

