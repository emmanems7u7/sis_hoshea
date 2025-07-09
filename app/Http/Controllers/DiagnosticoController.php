<?php

namespace App\Http\Controllers;

use App\Models\Tratamiento;
use App\Models\Diagnostico;
use Illuminate\Http\Request;

class DiagnosticoController extends Controller
{
    public function index()
    {
         $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Diagnosticos', 'url' => route('home')],
        ];
        $diagnosticos = Diagnostico::all();
        $tratamiento = [];
        return view('diagnosticos.index', compact('breadcrumb','tratamiento', 'diagnosticos'));
    }

    public function create()
    { $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Diagnosticos', 'url' => route('home')],
            ['name' => 'Crear', 'url' => route('home')],

        ];
        $tratamientos = Tratamiento::all();
        return view('diagnosticos.create', compact('breadcrumb','tratamientos'));
    }

    public function store(Request $request, $tratamiento_id)
    {
        $request->validate([
            'cod_diagnostico' => 'required|string|max:50',
            'fecha_diagnostico' => 'required|date',
            'estado' => 'required|in:activo,inactivo',
            'observacion' => 'nullable|string',
        ]);

        Diagnostico::create([
            'tratamiento_id' => $tratamiento_id,
            'cod_diagnostico' => $request->cod_diagnostico,
            'fecha_diagnostico' => $request->fecha_diagnostico,
            'estado' => $request->estado,
            'observacion' => $request->observacion,
        ]);

        return redirect()->route('diagnosticos.index', $tratamiento_id)
                         ->with('success', 'Diagnóstico creado correctamente.');
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

}

