<?php

namespace App\Http\Controllers;

use App\Models\Catalogo;
use App\Models\Categoria;
use App\Models\Paciente;
use App\Models\Diagnostico;
use App\Models\PacienteAntecedente;
use App\Models\Tratamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PacienteController extends Controller
{
    // Mostrar listado paginado
    public function index()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Pacientes', 'url' => route('pacientes.index')],
        ];
        $pacientes = Paciente::orderBy('created_at', 'desc')->paginate(15);
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

        $antecedente = Categoria::where('nombre', 'Diagnosticos')->first();

        $antecedentes = Catalogo::where('categoria_id', $antecedente->id)->where('catalogo_estado', 1)->get();

        $familia = Categoria::where('nombre', 'Familiar')->first();


        $familiares = Catalogo::where('categoria_id', $familia->id)->where('catalogo_estado', 1)->get();

        return view('pacientes.create', compact('familiares', 'antecedentes', 'paises', 'breadcrumb', 'paciente'));
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

        $paciente = Paciente::create($validated);

        //logica Antecedente

        // Validar que venga el campo y sea JSON válido
        $validator = Validator::make($request->all(), [
            'antecedentes_json' => ['required', 'json'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $antecedentes = json_decode($request->input('antecedentes_json'), true);

        if (!is_array($antecedentes) || empty($antecedentes)) {
            return back()->withErrors(['antecedentes_json' => 'El contenido de antecedentes es inválido o está vacío'])->withInput();
        }

        // Validar cada elemento del array
        foreach ($antecedentes as $index => $item) {
            if (!isset($item['antecedenteCodigo']) || !isset($item['familiarCodigo'])) {
                return back()->withErrors(['antecedentes_json' => "El antecedente o familiar en la posición {$index} no es válido."])->withInput();
            }

            // Validar que los códigos existan en la tabla catalogos
            $antecedenteExiste = Catalogo::where('catalogo_codigo', $item['antecedenteCodigo'])->exists();
            $familiarExiste = Catalogo::where('catalogo_codigo', $item['familiarCodigo'])->exists();

            if (!$antecedenteExiste) {
                return back()->withErrors(['antecedentes_json' => "El código de antecedente '{$item['antecedenteCodigo']}' no existe en catálogo."])->withInput();
            }
            if (!$familiarExiste) {
                return back()->withErrors(['antecedentes_json' => "El código de familiar '{$item['familiarCodigo']}' no existe en catálogo."])->withInput();
            }
        }

        // Si todo está OK, borramos antecedentes anteriores y guardamos los nuevos
        PacienteAntecedente::where('paciente_id', $paciente->id)->delete();

        foreach ($antecedentes as $item) {
            PacienteAntecedente::create([
                'paciente_id' => $paciente->id,
                'antecedente' => $item['antecedenteCodigo'],
                'familiar' => $item['familiarCodigo'],
            ]);
        }

        //logica Antecedente

        return redirect()->route('pacientes.index')
            ->with('status', 'Paciente creado correctamente.');
    }

    // Mostrar detalles de un paciente
    public function show(Paciente $paciente)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Pacientes', 'url' => route('pacientes.index')],
            ['name' => 'Ver Paciente', 'url' => route('pacientes.index')],

        ];
        $tratamientos = $paciente->citas()
            ->with('tratamiento')
            ->get()
            ->pluck('tratamiento')
            ->filter()
            ->unique('id')
            ->values();

        $citas = $paciente->citas()->get();

        $citaIds = $citas->pluck('id')->toArray();

        $diagnosticos = Diagnostico::whereIn('cita_id', $citaIds)
            ->with('catalogo') // aseguramos eager loading para eficiencia
            ->get()
            ->map(function ($diagnostico) {
                return [
                    'id' => $diagnostico->id,
                    'tratamiento_id' => $diagnostico->cita_id,
                    'cod_diagnostico' => $diagnostico->cod_diagnostico,
                    'criterio_clinico' => $diagnostico->criterio_clinico,
                    'evolucion_diagnostico' => $diagnostico->evolucion_diagnostico,
                    'fecha_diagnostico' => $diagnostico->fecha_diagnostico,
                    'nombre_diagnostico' => $diagnostico->catalogo->catalogo_descripcion,
                ];
            });

        return view('pacientes.show', compact('paciente', 'breadcrumb', 'diagnosticos', 'tratamientos'));
    }

    // Mostrar formulario edición
    public function edit(Paciente $paciente)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Pacientes', 'url' => route('pacientes.index')],
            ['name' => 'Ver Paciente', 'url' => route('pacientes.index')],

        ];

        $paciente = Paciente::with(['antecedentes.catalogoAntecedente', 'antecedentes.catalogoFamiliar'])->findOrFail($paciente->id);

        $paises = Catalogo::where('categoria_id', 5)->get();


        $antecedente = Categoria::where('nombre', 'Diagnosticos')->first();

        $antecedentes = Catalogo::where('categoria_id', $antecedente->id)->where('catalogo_estado', 1)->get();

        $familia = Categoria::where('nombre', 'Familiar')->first();


        $familiares = Catalogo::where('categoria_id', $familia->id)->where('catalogo_estado', 1)->get();


        return view('pacientes.edit', compact('familiares', 'antecedentes', 'paises', 'paciente', 'breadcrumb'));
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

        //logica Antecedente

        // Validar que venga el campo y sea JSON válido
        $validator = Validator::make($request->all(), [
            'antecedentes_json' => ['required', 'json'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $antecedentes = json_decode($request->input('antecedentes_json'), true);

        if (!is_array($antecedentes) || empty($antecedentes)) {
            return back()->withErrors(['antecedentes_json' => 'El contenido de antecedentes es inválido o está vacío'])->withInput();
        }

        // Validar cada elemento del array
        foreach ($antecedentes as $index => $item) {
            if (!isset($item['antecedenteCodigo']) || !isset($item['familiarCodigo'])) {
                return back()->withErrors(['antecedentes_json' => "El antecedente o familiar en la posición {$index} no es válido."])->withInput();
            }

            // Validar que los códigos existan en la tabla catalogos
            $antecedenteExiste = Catalogo::where('catalogo_codigo', $item['antecedenteCodigo'])->exists();
            $familiarExiste = Catalogo::where('catalogo_codigo', $item['familiarCodigo'])->exists();

            if (!$antecedenteExiste) {
                return back()->withErrors(['antecedentes_json' => "El código de antecedente '{$item['antecedenteCodigo']}' no existe en catálogo."])->withInput();
            }
            if (!$familiarExiste) {
                return back()->withErrors(['antecedentes_json' => "El código de familiar '{$item['familiarCodigo']}' no existe en catálogo."])->withInput();
            }
        }

        // Si todo está OK, borramos antecedentes anteriores y guardamos los nuevos
        PacienteAntecedente::where('paciente_id', $paciente->id)->delete();

        foreach ($antecedentes as $item) {
            PacienteAntecedente::create([
                'paciente_id' => $paciente->id,
                'antecedente' => $item['antecedenteCodigo'],
                'familiar' => $item['familiarCodigo'],
            ]);
        }

        //logica Antecedente


        return redirect()->route('pacientes.index')
            ->with('status', 'Paciente actualizado correctamente.');
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
        $tratamientos = $paciente->citas()
            ->with('tratamiento')
            ->get()
            ->pluck('tratamiento')
            ->filter()
            ->unique('id')
            ->values();

        $citas = $paciente->citas()->get();

        $citaIds = $citas->pluck('id')->toArray();

        $diagnosticos = Diagnostico::whereIn('cita_id', $citaIds)
            ->with('catalogo') // aseguramos eager loading para eficiencia
            ->get()
            ->map(function ($diagnostico) {
                return [
                    'id' => $diagnostico->id,
                    'tratamiento_id' => $diagnostico->cita_id,
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
