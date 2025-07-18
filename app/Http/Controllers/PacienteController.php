<?php

namespace App\Http\Controllers;

use App\Models\Catalogo;
use App\Models\Paciente;
use App\Models\Diagnostico;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    // Mostrar listado paginado
    public function index()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Pacientes', 'url' => route('pacientes.index')],
        ];
        $pacientes = Paciente::orderBy('apellido_paterno')->paginate(15);
        return view('pacientes.index', compact('breadcrumb', 'pacientes'));
    }

    // Mostrar formulario creación
    public function create()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Pacientes', 'url' => route('pacientes.index')],
            ['name' => 'Crear Paciente', 'url' => route('pacientes.index')],

        ];
        $paciente = null;



        $paises = Catalogo::where('categoria_id', 5)->get();


        return view('pacientes.create', compact('paises', 'breadcrumb', 'paciente'));
    }

    // Guardar nuevo paciente
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombres' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:50',
            'apellido_materno' => 'nullable|string|max:50',
            'fecha_nacimiento' => 'nullable|date|before_or_equal:today',
            'genero' => 'nullable|in:M,F,O',
            'telefono_fijo' => 'nullable|string|max:20',
            'telefono_movil' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:255',
            'tipo_documento' => 'nullable|string|max:50',
            'numero_documento' => 'nullable|string|max:50|unique:pacientes',
            'pais' => 'nullable|string|exists:catalogos,catalogo_codigo',
            'departamento' => 'nullable|string|exists:catalogos,catalogo_codigo',
            'ciudad' => 'nullable|string|exists:catalogos,catalogo_codigo',
            'activo' => 'boolean',
        ]);

        Paciente::create($validated);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente creado correctamente.');
    }

    // Mostrar detalles de un paciente
    public function show(Paciente $paciente)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Pacientes', 'url' => route('pacientes.index')],
            ['name' => 'Ver Paciente', 'url' => route('pacientes.index')],

        ];

        return view('pacientes.show', compact('paciente', 'breadcrumb'));
    }

    // Mostrar formulario edición
    public function edit(Paciente $paciente)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Pacientes', 'url' => route('pacientes.index')],
            ['name' => 'Ver Paciente', 'url' => route('pacientes.index')],

        ];

        $paises = Catalogo::where('categoria_id', 5)->get();

        return view('pacientes.edit', compact('paises', 'paciente', 'breadcrumb'));
    }

    // Actualizar paciente
    public function update(Request $request, Paciente $paciente)
    {
        $validated = $request->validate([
            'nombres' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:50',
            'apellido_materno' => 'nullable|string|max:50',
            'fecha_nacimiento' => 'nullable|date|before_or_equal:today',
            'genero' => 'nullable|in:M,F,O',
            'telefono_fijo' => 'nullable|string|max:20',
            'telefono_movil' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:255',
            'tipo_documento' => 'nullable|string|max:50',
            'numero_documento' => 'nullable|string|max:50|unique:pacientes,numero_documento,' . $paciente->id,
            'pais' => 'nullable|string|exists:catalogos,catalogo_codigo',
            'departamento' => 'nullable|string|exists:catalogos,catalogo_codigo',
            'ciudad' => 'nullable|string|exists:catalogos,catalogo_codigo',
            'activo' => 'boolean',
        ]);

        $paciente->update($validated);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente actualizado correctamente.');
    }

    // Eliminar paciente
    public function destroy(Paciente $paciente)
    {
        $paciente->delete();

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente eliminado correctamente.');
    }

    public function datos(Paciente $paciente)
    {
        // Obtener tratamientos activos o todos los tratamientos relacionados a las citas del paciente
        // Asumiendo que en Cita el tratamiento está relacionado y quieres solo tratamientos activos o vigentes:
        $tratamientos = $paciente->citas()
            ->with('tratamiento')
            ->get()
            ->pluck('tratamiento')
            ->filter()
            ->unique('id')
            ->values();

        $tratamientoIds = $tratamientos->pluck('id')->toArray();

        $diagnosticos = Diagnostico::whereIn('tratamiento_id', $tratamientoIds)
            ->with('catalogo') // aseguramos eager loading para eficiencia
            ->get()
            ->map(function ($diagnostico) {
                return [
                    'id' => $diagnostico->id,
                    'tratamiento_id' => $diagnostico->tratamiento_id,
                    'cod_diagnostico' => $diagnostico->cod_diagnostico,
                    'criterio_clinico' => $diagnostico->criterio_clinico,
                    'evolucion_diagnostico' => $diagnostico->evolucion_diagnostico,
                    'fecha_diagnostico' => $diagnostico->fecha_diagnostico,
                    'nombre_diagnostico' => $diagnostico->catalogo->catalogo_descripcion,
                ];
            });

        return response()->json([
            'tratamientos' => $tratamientos,
            'diagnosticos' => $diagnosticos,
        ]);
    }
}
