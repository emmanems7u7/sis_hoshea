<?php
namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Tratamiento;
use App\Models\Paciente;
use App\Models\user;
use App\Models\Catalogo;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarNotificacionPaciente;
use App\Exports\ExportExcel;
use App\Exports\ExportPDF;
use App\Models\Categoria;
use App\Notifications\CitaTratamientoAsignada;
use App\Notifications\NotificacionTratamiento;
use App\Notifications\TratamientoFinalizado;
use Maatwebsite\Excel\Facades\Excel;
class TratamientoController extends Controller
{
    public function index(Request $request)
    {
        $hoy = Carbon::today();

        $estado = $request->get('estado', 'activo');

        $tratamientos = Tratamiento::with(['paciente', 'citas.examenes'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $texto = strtolower($request->q);

                $query->where('nombre', 'like', "%{$texto}%")
                    ->orWhereHas('paciente', function ($paciente) use ($texto) {
                        $paciente->whereRaw(
                            "LOWER(CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno)) LIKE ?",
                            ['%' . $texto . '%']
                        );
                    });
            })
            // Solo aplicar filtro por estado si NO hay filtro 'anteriores'
            ->when(!$request->has('anteriores'), function ($query) use ($estado) {
                $query->when($estado === 'activo', function ($q) {
                    $q->where('estado', 'activo');
                })
                    ->when($estado === 'pendiente', function ($q) {
                        $q->where('estado', 'pendiente');
                    })
                    ->when($estado === 'finalizado', function ($q) {
                        $q->where('estado', 'finalizado');
                    });
            })
            // Filtro para tratamientos anteriores (sin filtrar por estado)
            ->when($request->has('anteriores'), function ($query) use ($hoy) {
                $query->whereNotNull('fecha_fin')
                    ->whereDate('fecha_fin', '<=', $hoy);
            })
            ->orderByDesc('fecha_inicio')
            ->paginate(10)
            ->withQueryString();


        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Tratamientos', 'url' => route('tratamientos.index')],
        ];
        return view('tratamientos.index', compact('tratamientos', 'breadcrumb'));
    }

    public function finalizarTratamientosVencidos()
    {
        $hoy = Carbon::today();

        $tratamientosVencidos = Tratamiento::whereNotNull('fecha_fin')
            ->whereDate('fecha_fin', '<', $hoy)
            ->where('estado', '!=', 'finalizado')
            ->get();

        foreach ($tratamientosVencidos as $tratamiento) {
            $tratamiento->estado = 'finalizado';

            if (!empty($tratamiento->observaciones)) {
                $tratamiento->observaciones .= ' | Finalizado automáticamente el ' . now()->format('d/m/Y');
            } else {
                $tratamiento->observaciones = 'Finalizado automáticamente el ' . now()->format('d/m/Y');
            }


            $tratamiento->save();
        }
    }

    public function create()
    {
        $pacientes = Paciente::all()->pluck('nombre_completo', 'id');
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Tratamientos', 'url' => route('tratamientos.index')],
            ['name' => 'Crear tratamiento', 'url' => '#'],
        ];
        $pac = 0;
        $usuarios = User::whereHas('roles') // Solo usuarios que tienen al menos un rol
            ->orderBy('name')->get();
        return view('tratamientos.create', compact('usuarios', 'pac', 'pacientes', 'breadcrumb'));
    }

    public function store(Request $request)
    {


        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'observaciones_tratamiento' => 'nullable|string',
        ]);

        $tratamiento = Tratamiento::create([
            'paciente_id' => $validated['paciente_id'],
            'nombre' => $validated['nombre'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'] ?? null,
            'estado' => 'activo',
            'observaciones' => $validated['observaciones_tratamiento'] ?? null,
        ]);

        $fechaInicio = Carbon::parse($validated['fecha_inicio']);
        $fechaFin = $validated['fecha_fin'] ? Carbon::parse($validated['fecha_fin']) : null;



        $citasArray = json_decode($request->input('citas_json'), true);


        if (is_null($citasArray) || !is_array($citasArray) || empty($citasArray)) {
            return redirect()->back()
                ->withInput()                // <-- mantiene todos los campos, incluido citas_json
                ->with('error', 'Debe agregar al menos una cita');
        }

        foreach ($citasArray as $citaData) {
            $fechaCita = Carbon::parse($citaData['fecha_hora']);

            // Validar fecha mínima
            if ($fechaCita->lt($fechaInicio)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'La fecha de la cita (' . $fechaCita->format('d/m/Y H:i') . ') no puede ser anterior al inicio del tratamiento.');
            }

            // Validar fecha máxima (si existe)
            if ($fechaFin && $fechaCita->gt($fechaFin)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'La fecha de la cita (' . $fechaCita->format('d/m/Y H:i') . ') no puede ser posterior al fin del tratamiento.');
            }
        }

        $tratamientoInicio = Carbon::parse($tratamiento->fecha_inicio);

        // Paso 1: Verificar si ya hay una marcada como primera cita
        $yaTienePrimeraCita = collect($citasArray)->contains(fn($cita) => !empty($cita['primera_cita']));

        // Paso 2: Si NO hay una, encontrar la más cercana a la fecha de inicio
        if (!$yaTienePrimeraCita) {
            $citasArray = collect($citasArray)
                ->map(function ($cita) use ($tratamientoInicio) {
                    $cita['fecha_obj'] = Carbon::parse($cita['fecha_hora']);
                    $cita['diff_inicio'] = abs($cita['fecha_obj']->diffInSeconds($tratamientoInicio));
                    return $cita;
                })
                ->sortBy('diff_inicio') // más cercana a fecha_inicio
                ->values()
                ->map(function ($cita, $index) {
                    $cita['primera_cita'] = $index === 0; // solo la primera
                    return $cita;
                })
                ->toArray();
        }


        foreach ($citasArray as $citaData) {





            $cita = Cita::create([
                'paciente_id' => $tratamiento->paciente_id,
                'tratamiento_id' => $tratamiento->id,
                'fecha_hora' => $citaData['fecha_hora'],
                'duracion' => $citaData['duracion'] ?? null,
                'estado' => $citaData['estado'],
                'observaciones' => $citaData['observaciones'] ?? null,
                'primera_cita' => $citaData['primera_cita'] ?? false,
            ]);

            if (!empty($citaData['usuarios'])) {
                $syncData = [];

                foreach ($citaData['usuarios'] as $usuario) {
                    $userId = $usuario['id'] ?? null;
                    $rol = $usuario['rol'] ?? null;

                    if ($userId) {
                        $syncData[$userId] = ['rol_en_cita' => $rol];

                        $usuarioModel = User::find($userId);
                        if ($usuarioModel) {
                            $usuarioModel->notify(new NotificacionTratamiento($tratamiento));
                            $usuarioModel->notify(new CitaTratamientoAsignada($cita));
                        }
                    }
                }

                $cita->usuarios()->sync($syncData);
            }
        }


        return redirect()->route('tratamientos.index')->with('status', 'Tratamiento creado correctamente.');
    }

    public function edit(Tratamiento $tratamiento)
    {
        // Listado de pacientes (select)
        $pacientes = Paciente::all()->pluck('nombre_completo', 'id');

        // Usuarios (médicos y enfermeros) para asignar a cada cita
        $usuarios = User::whereHas('roles') // Solo usuarios que tienen al menos un rol
            ->orderBy('name')->get();

        // Citas del tratamiento serializadas para precargar el JS
        $citasJson = $tratamiento->citas
            ->map(function ($cita) {
                return [
                    'fecha_hora' => $cita->fecha_hora->format('Y-m-d\TH:i'),
                    'duracion' => $cita->duracion,
                    'estado' => $cita->estado,
                    'observaciones' => $cita->observaciones,
                    'primera_cita' => $cita->primera_cita,
                    'usuarios' => $cita->usuarios->map(function ($usuario) {
                        return [
                            'id' => $usuario->id,
                            'nombre' => $usuario->nombre_completo,
                            'rol' => $usuario->pivot->rol_en_cita,
                        ];
                    }),
                ];
            })
            ->values()
            ->toJson();

        // Breadcrumb
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Tratamientos', 'url' => route('tratamientos.index')],
            ['name' => 'Editar tratamiento', 'url' => '#'],
        ];


        $pac = 0;

        return view('tratamientos.edit', compact(
            'tratamiento',
            'pacientes',
            'usuarios',
            'citasJson',
            'breadcrumb',
            'pac'
        ));
    }


    public function update(Request $request, Tratamiento $tratamiento)
    {


        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado_tratamiento' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $tratamiento->update([
            'paciente_id' => $validated['paciente_id'],
            'nombre' => $validated['nombre'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'] ?? null,
            'estado' => $validated['estado_tratamiento'],
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        $fechaInicio = Carbon::parse($validated['fecha_inicio']);
        $fechaFin = $validated['fecha_fin'] ? Carbon::parse($validated['fecha_fin']) : null;

        $citasArray = json_decode($request->input('citas_json'), true);

        if (is_null($citasArray) || !is_array($citasArray) || empty($citasArray)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Debe agregar al menos una cita');
        }


        $tratamiento->citas()->delete();

        foreach ($citasArray as $citaData) {
            $fechaCita = Carbon::parse($citaData['fecha_hora']);

            if ($fechaCita->lt($fechaInicio)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'La fecha de la cita (' . $fechaCita->format('d/m/Y H:i') . ') no puede ser anterior al inicio del tratamiento.');
            }

            if ($fechaFin && $fechaCita->toDateString() > $fechaFin->toDateString()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'La fecha de la cita (' . $fechaCita->format('d/m/Y H:i') . ') no puede ser posterior al fin del tratamiento.');
            }



            $cita = Cita::create([
                'paciente_id' => $tratamiento->paciente_id,
                'tratamiento_id' => $tratamiento->id,
                'fecha_hora' => $citaData['fecha_hora'],
                'duracion' => $citaData['duracion'] ?? null,
                'estado' => $citaData['estado'],
                'observaciones' => $citaData['observaciones'] ?? null,
                'primera_cita' => $citaData['primera_cita'] ?? 0,
                'gestionado' => 0,
            ]);

            if (!empty($citaData['usuarios'])) {
                $syncData = [];

                foreach ($citaData['usuarios'] as $usuario) {
                    $userId = $usuario['id'] ?? null;
                    $rol = $usuario['rol'] ?? null;

                    if ($userId) {
                        $syncData[$userId] = ['rol_en_cita' => $rol];
                    }


                    $usuario = User::find($userId);

                    $usuario->notify(new NotificacionTratamiento($tratamiento));
                    $usuario->notify(new CitaTratamientoAsignada($cita));
                }

                $cita->usuarios()->sync($syncData);
            }
        }

        return redirect()->route('tratamientos.index')->with('status', 'Tratamiento actualizado correctamente.');
    }



    public function destroy(Tratamiento $tratamiento)
    {
        $tratamiento->delete();
        return redirect()->route('tratamientos.index')->with('status', 'Tratamiento eliminado correctamente.');
    }


    public function tratamientosFechaHoy()
    {

        $hoy = Carbon::today();

        $tratamientos = Tratamiento::whereDate('fecha_inicio', $hoy)
            ->with([
                'paciente',
                'citas.usuarios'
            ])
            ->get();

        foreach ($tratamientos as $tratamiento) {
            $email_paciente = $tratamiento->paciente->email ?? null;

            if ($email_paciente != null) {

            }

        }

        return $tratamientos;
    }


    public function exportExcel()
    {
        $export = new ExportExcel('tratamientos.export_usuarios', ['users' => User::all(), 'export' => 'Usuarios'], 'usuarios');
        return Excel::download($export, $export->getFileName());
    }

    public function exportPDF()
    {
        $tratamientos = Tratamiento::with(['paciente', 'citas'])->get();

        $user = auth()->user();

        $fecha = Carbon::now()->format('d-m-Y H:i:s');

        return ExportPDF::exportPdf(
            'tratamientos.export_tratamientos',
            [
                'tratamientos' => $tratamientos,
                'user' => $user,
                'fecha' => $fecha,
                'export' => 'tratamientos'
            ]
            ,
            'tratamientos',
            false
        );
    }


    public function finalizar(Tratamiento $tratamiento)
    {
        $tratamiento->estado = 'finalizado';
        $tratamiento->save();

        $adminUsers = User::role('admin')->get();
        foreach ($adminUsers as $adminUser) {
            $adminUser->notify(new TratamientoFinalizado($tratamiento));
        }

        return redirect()->back()->with('status', 'Tratamiento Finalizado correctamente');
    }

    function Gestion(Cita $cita, $tipo)
    {
        // Breadcrumb

        $tratamiento = Tratamiento::find($cita->tratamiento_id);
        if ($tipo == 1) {
            $breadcrumb = [
                ['name' => 'Inicio', 'url' => route('home')],

                ['name' => 'Tratamientos', 'url' => route('tratamientos.index')],
                ['name' => 'Gestión Cita', 'url' => ''],

            ];
        } else {
            $breadcrumb = [
                ['name' => 'Inicio', 'url' => route('home')],

                ['name' => 'Citas', 'url' => route('citas.index')],
                ['name' => 'Gestión Cita', 'url' => ''],

            ];
        }



        $objetivos = Catalogo::where('categoria_id', 9)->get();
        $diagnosticos = Catalogo::where('categoria_id', 6)->get();
        $planes = Catalogo::where('categoria_id', 10)->get();
        $examenes = Catalogo::where('categoria_id', 14)->get();
        $paciente = Paciente::find($cita->paciente_id);

        $antecedente = Categoria::where('nombre', 'Diagnosticos')->first();

        $antecedentes = Catalogo::where('categoria_id', $antecedente->id)->where('catalogo_estado', 1)->get();

        $familia = Categoria::where('nombre', 'Familiar')->first();


        $familiares = Catalogo::where('categoria_id', $familia->id)->where('catalogo_estado', 1)->get();


        return view('tratamientos.gestion_cita_t', compact('paciente', 'familiares', 'antecedentes', 'cita', 'examenes', 'planes', 'diagnosticos', 'objetivos', 'breadcrumb', 'tratamiento'));
    }

    public function exportPDFGestion(Tratamiento $tratamiento)
    {

        $objetivos = Catalogo::where('categoria_id', 9)->get();
        $diagnosticos = Catalogo::where('categoria_id', 6)->get();
        $planes = Catalogo::where('categoria_id', 10)->get();

        return ExportPDF::exportPdf('tratamientos.exportar_resumen', [
            'objetivos' => $objetivos,
            'diagnosticos' => $diagnosticos,
            'planes' => $planes,
            'tratamiento' => $tratamiento,

            'export' => 'gestion_tratamiento'
        ], 'tratamiento', false);
    }

    public function guardarObservacion(Request $request, Tratamiento $tratamiento)
    {
        $request->validate([
            'observaciones' => 'nullable|string',
        ]);

        $tratamiento->observaciones = $request->observaciones;
        $tratamiento->save();

        return response()->json(['mensaje' => 'Observación guardada']);
    }

}
