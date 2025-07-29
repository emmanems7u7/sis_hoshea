<?php
namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\DatoCita;
use App\Models\Paciente;
use App\Models\Plan;
use App\Models\Tratamiento;
use App\Models\Diagnostico;
use App\Models\ObjetivoCita;
use App\Models\User;
use App\Models\CitaExamen;
use Carbon\Carbon;
use App\Models\Catalogo;
use App\Models\Configuracion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Mail;
use App\Mail\EstadoCitaCambiadoMail;

use App\Exports\ExportPDF;

use Illuminate\Http\Request;
use App\Interfaces\CatalogoInterface;
use App\Models\Categoria;
use App\Models\PacienteAntecedente;
use App\Notifications\CitaAsignada;
use App\Notifications\NotificacionTratamiento;

class CitaController extends Controller
{
    protected $CatalogoRepository;
    public function __construct(CatalogoInterface $CatalogoInterface)
    {

        $this->CatalogoRepository = $CatalogoInterface;
    }

    public function index(Request $request)
    {
        $query = Cita::with(['paciente', 'tratamiento', 'usuarios']);

        $verTodos = $request->input('ver_todos', 0); // por defecto 0 si no viene

        if ($verTodos != 1) {

            $query->whereNull('tratamiento_id');
        }


        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->whereHas('paciente', function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%$buscar%")
                    ->orWhere('apellido_paterno', 'like', "%$buscar%")
                    ->orWhere('apellido_materno', 'like', "%$buscar%");
            });
        }

        $citas = $query->orderBy('fecha_hora', 'desc')->paginate(15);

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],

            ['name' => 'Citas', 'url' => route('citas.index')],
        ];

        return view('citas.index', compact('citas', 'breadcrumb'));
    }


    public function create()
    {
        $pacientes = Paciente::all()->pluck('nombre_completo', 'id');
        $tratamientos = Tratamiento::orderByDesc('fecha_inicio')->pluck('nombre', 'id');


        $usuarios = User::whereHas('roles') // Solo usuarios que tienen al menos un rol
            ->orderBy('name')->get();

        $breadcrumb = [
            ['name' => 'Citas', 'url' => route('citas.index')],
            ['name' => 'Crear cita', 'url' => '#'],
        ];
        $pac = 1;
        return view('citas.create', compact('pac', 'pacientes', 'tratamientos', 'usuarios', 'breadcrumb'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'tratamiento_id' => 'nullable|exists:tratamientos,id',
            'fecha_hora' => 'required|date',
            'duracion' => 'nullable|integer|min:1',
            'estado' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
            'usuarios' => 'required|array|min:1',
            'roles' => 'required|array',
        ]);


        foreach ($validated['usuarios'] as $userId => $valor) {
            if (empty($validated['roles'][$userId])) {
                return back()
                    ->withErrors(['roles' => "Debe asignar un rol para el usuario o usuarios seleccionados."])
                    ->withInput();
            }
        }
        // Validar que el paciente del tratamiento coincida con el paciente_id (si hay tratamiento)
        if ($validated['tratamiento_id']) {
            $tratamiento = Tratamiento::findOrFail($validated['tratamiento_id']);
            if ($tratamiento->paciente_id != $validated['paciente_id']) {
                return back()->withErrors(['tratamiento_id' => 'El tratamiento no corresponde al paciente seleccionado.'])->withInput();
            }
        }

        // Validar conflicto de horarios antes de crear la cita


        $inicioNuevo = Carbon::parse($validated['fecha_hora']);
        $duracion = (int) ($validated['duracion'] ?? 0);
        $finNuevo = $inicioNuevo->copy()->addMinutes($duracion);

        foreach ($validated['usuarios'] as $userId) {
            $conflicto = DB::table('citas')
                ->join('cita_user', 'citas.id', '=', 'cita_user.cita_id')
                ->where('cita_user.user_id', $userId)
                ->where(function ($query) use ($inicioNuevo, $finNuevo) {
                    $query->where('citas.fecha_hora', '<', $finNuevo)
                        ->whereRaw("DATE_ADD(citas.fecha_hora, INTERVAL citas.duracion MINUTE) > ?", [$inicioNuevo]);
                })
                ->exists();

            if ($conflicto) {
                $usuario = User::find($userId);
                return back()
                    ->withInput()
                    ->withErrors([
                        'usuarios' => "El usuario {$usuario->name} ya tiene una cita que se superpone en el rango de tiempo indicado.",
                    ]);
            }
        }

        // Crear cita
        $cita = Cita::create([
            'paciente_id' => $validated['paciente_id'],
            'tratamiento_id' => $validated['tratamiento_id'] ?? null,
            'fecha_hora' => $validated['fecha_hora'],
            'duracion' => $validated['duracion'] ?? null,
            'estado' => $validated['estado'],
            'observaciones' => $validated['observaciones'] ?? null,
            'primera_cita' => 1,
            'gestionado' => 0,
        ]);


        // Sincronizar usuarios asignados con rol en pivot
        $syncData = [];
        foreach ($validated['usuarios'] as $index => $userId) {
            $rol = $validated['roles'][$index] ?? null;
            $syncData[$userId] = ['rol_en_cita' => $rol];

            $usuario = User::find($userId);
            $usuario->notify(new CitaAsignada($cita));


        }
        $cita->usuarios()->sync($syncData);

        return redirect()->route('citas.index')->with('success', 'Cita creada correctamente.');
    }

    public function edit(Cita $cita)
    {
        $pacientes = Paciente::all()->pluck('nombre_completo', 'id');
        $tratamientos = Tratamiento::orderByDesc('fecha_inicio')->pluck('nombre', 'id');
        $usuariosConRolAdmin = User::role('admin')->pluck('id');

        $usuarios = User::whereHas('roles') // Solo usuarios que tienen al menos un rol
            ->orderBy('name')->get();

        // Obtener datos actuales de usuarios y roles asignados para editar
        $usuariosAsignados = $cita->usuarios->pluck('pivot.rol_en_cita', 'id')->toArray();

        $breadcrumb = [
            ['name' => 'Citas', 'url' => route('citas.index')],
            ['name' => 'Editar cita', 'url' => '#'],
        ];
        $pac = 1;
        return view('citas.edit', compact(
            'pac',
            'cita',
            'pacientes',
            'tratamientos',
            'usuarios',
            'usuariosAsignados',
            'breadcrumb'
        ));
    }

    public function update(Request $request, Cita $cita)
    {

        // dd($request);
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'tratamiento_id' => 'nullable|exists:tratamientos,id',
            'fecha_hora' => 'required|date',
            'duracion' => 'nullable|integer|min:1',
            'estado' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
            'usuarios' => 'required|array|min:1',
            'roles' => 'required|array',
        ]);



        foreach ($validated['usuarios'] as $userId => $valor) {
            if (empty($validated['roles'][$userId])) {
                return back()
                    ->withErrors(['roles' => "Debe asignar un rol para el usuario o usuarios seleccionados."])
                    ->withInput();
            }
        }


        if ($validated['tratamiento_id']) {
            $tratamiento = Tratamiento::findOrFail($validated['tratamiento_id']);
            if ($tratamiento->paciente_id != $validated['paciente_id']) {
                return back()->withErrors(['tratamiento_id' => 'El tratamiento no corresponde al paciente seleccionado.'])->withInput();
            }
        }

        $cita->update([
            'paciente_id' => $validated['paciente_id'],
            'tratamiento_id' => $validated['tratamiento_id'] ?? null,
            'fecha_hora' => $validated['fecha_hora'],
            'duracion' => $validated['duracion'] ?? null,
            'estado' => $validated['estado'],
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        $syncData = [];
        foreach ($validated['usuarios'] as $index => $userId) {
            $rol = $validated['roles'][$index] ?? null;
            $syncData[$userId] = ['rol_en_cita' => $rol];


            $usuario = User::find($userId);
            $usuario->notify(new CitaAsignada($cita));
        }
        $cita->usuarios()->sync($syncData);

        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(Cita $cita)
    {
        $cita->usuarios()->detach();
        $cita->delete();

        return redirect()->route('citas.index')->with('success', 'Cita eliminada correctamente.');
    }

    function cambiar_estado(Request $request)
    {

        $validated = $request->validate([
            'cita_id' => 'required|exists:citas,id',
            'nuevo_estado' => 'required|in:pendiente,confirmada,cancelada,completada',
            'notificar' => 'nullable|boolean',
        ]);


        $cita = Cita::findOrFail($validated['cita_id']);


        $cita->estado = $validated['nuevo_estado'];
        $cita->save();


        if ($request->has('notificar') && $request->boolean('notificar')) {

            Mail::to($cita->paciente->email)->send(new EstadoCitaCambiadoMail($cita));

        }

        return redirect()->back()->with('status', 'Estado de la cita actualizado correctamente.');
    }

    function store_gestion(Request $request, $cita)
    {
        session()->flash('modal_abierto', $request->input('modal'));
        session()->flash('cita', $cita);

        $request->validate([
            'cod_diagnostico' => 'required|string|max:255',
            'criterio_clinico' => 'nullable|string|max:1000',
            'evolucion_diagnostico' => 'nullable|string|max:1000',
            'planes_json' => 'required|string',
            'datos_json' => 'required|string',
            'objetivos_json' => 'required|string',
        ]);

        $planes = json_decode($request->input('planes_json'), true);
        $datos = json_decode($request->input('datos_json'), true);
        $objetivos = json_decode($request->input('objetivos_json'), true);

        if ($planes === null || !is_array($planes)) {
            return redirect()->back()
                ->with('error', 'Los datos de planes no son válidos.')
                ->withInput();
        }
        if ($datos === null || !is_array($datos)) {
            return redirect()->back()
                ->with('error', 'Los datos ingresados no son válidos.')
                ->withInput();
        }
        if ($objetivos === null || !is_array($objetivos)) {
            return redirect()->back()
                ->with('error', 'Los datos de objetivos no son válidos.')
                ->withInput();
        }
        foreach ($planes as $plan) {
            if (
                !isset($plan['tipo'], $plan['descripcion'], $plan['tipoNombre']) ||
                !is_string($plan['tipo']) || !is_string($plan['descripcion']) || !is_string($plan['tipoNombre'])
            ) {
                return redirect()->back()
                    ->with('error', 'Formato inválido en planes.')
                    ->withInput();
            }
        }

        // Validar estructura interna de datos (array de strings)
        foreach ($datos as $dato) {
            if (!is_string($dato)) {
                return redirect()->back()
                    ->with('error', 'Formato inválido en datos.')
                    ->withInput();
            }
        }

        // Validar estructura interna de objetivos
        foreach ($objetivos as $objetivo) {
            if (
                !isset($objetivo['codigo'], $objetivo['nombre'], $objetivo['valor']) ||
                !is_string($objetivo['codigo']) || !is_string($objetivo['nombre']) || !is_string($objetivo['valor'])
            ) {
                return redirect()->back()
                    ->with('error', 'Formato inválido en objetivos.')
                    ->withInput();
            }
        }

        $cita_ = Cita::findOrFail($cita);



        $diagnostico = Diagnostico::create([
            'cita_id' => $cita_->id,
            'cod_diagnostico' => $request->input('cod_diagnostico'),
            'fecha_diagnostico' => now(),
            'estado' => 1,
            'criterio_clinico' => $request->input('criterio_clinico'),
            'evolucion_diagnostico' => $request->input('evolucion_diagnostico'),
        ]);

        foreach ($planes as $planData) {
            $planes_ = Plan::create([
                'cita_id' => $cita,
                'tipo' => $planData['tipo'],
                'descripcion' => $planData['descripcion'],
            ]);
        }
        foreach ($datos as $dato) {
            $dato_cita = DatoCita::create([
                'cita_id' => $cita,
                'descripcion' => $dato,
            ]);
        }

        foreach ($objetivos as $obj) {
            $obj = ObjetivoCita::create([
                'cita_id' => $cita,
                'codigo' => $obj['codigo'],
                'valor' => $obj['valor'],
            ]);
        }



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
        $paciente = Paciente::findOrFail($cita_->paciente_id);
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

        $cita_->estado = 'completada';
        $cita_->gestionado = 1;
        $cita_->fecha_gestion = now();
        $cita_->save();


        return redirect()->back()->with('status', 'La información de la cita se guardó correctamente. Ahora puede generar el PDF.');

    }

    public function update_gestion(Request $request, $cita)
    {
        $request->validate([
            'cod_diagnostico' => 'required|string|max:255',
            'criterio_clinico' => 'nullable|string|max:1000',
            'evolucion_diagnostico' => 'nullable|string|max:1000',
            'planes_json' => 'required|string',
            'datos_json' => 'required|string',
            'objetivos_json' => 'required|string',
        ]);

        $planes = json_decode($request->input('planes_json'), true);
        $datos = json_decode($request->input('datos_json'), true);
        $objetivos = json_decode($request->input('objetivos_json'), true);

        // Validaciones como en el store
        if ($planes === null || !is_array($planes)) {
            return redirect()->back()->with('error', 'Los datos de planes no son válidos.')->withInput();
        }

        if ($datos === null || !is_array($datos)) {
            return redirect()->back()->with('error', 'Los datos ingresados no son válidos.')->withInput();
        }

        if ($objetivos === null || !is_array($objetivos)) {
            return redirect()->back()->with('error', 'Los datos de objetivos no son válidos.')->withInput();
        }

        foreach ($planes as $plan) {
            if (
                !isset($plan['tipo'], $plan['descripcion'], $plan['tipoNombre']) ||
                !is_string($plan['tipo']) || !is_string($plan['descripcion']) || !is_string($plan['tipoNombre'])
            ) {
                return redirect()->back()->with('error', 'Formato inválido en planes.')->withInput();
            }
        }

        foreach ($datos as $dato) {
            if (!is_string($dato)) {
                return redirect()->back()->with('error', 'Formato inválido en datos.')->withInput();
            }
        }

        foreach ($objetivos as $objetivo) {
            if (
                !isset($objetivo['codigo'], $objetivo['nombre'], $objetivo['valor']) ||
                !is_string($objetivo['codigo']) || !is_string($objetivo['nombre']) || !is_string($objetivo['valor'])
            ) {
                return redirect()->back()->with('error', 'Formato inválido en objetivos.')->withInput();
            }
        }

        $cita_ = Cita::findOrFail($cita);
        $tratamiento = Tratamiento::findOrFail($cita_->tratamiento_id);

        // Actualizar diagnóstico
        $diagnostico = Diagnostico::where('cita_id', $cita_->id)->first();
        if ($diagnostico) {
            $diagnostico->update([
                'cod_diagnostico' => $request->input('cod_diagnostico'),
                'criterio_clinico' => $request->input('criterio_clinico'),
                'evolucion_diagnostico' => $request->input('evolucion_diagnostico'),
            ]);
        } else {
            Diagnostico::create([
                'cita_id' => $cita->id,
                'cod_diagnostico' => $request->input('cod_diagnostico'),
                'fecha_diagnostico' => now(),
                'estado' => 1,
                'criterio_clinico' => $request->input('criterio_clinico'),
                'evolucion_diagnostico' => $request->input('evolucion_diagnostico'),
            ]);
        }

        Plan::where('cita_id', $cita)->delete();
        DatoCita::where('cita_id', $cita)->delete();
        ObjetivoCita::where('cita_id', $cita)->delete();

        // Insertar nuevos
        foreach ($planes as $planData) {
            Plan::create([
                'cita_id' => $cita,
                'tipo' => $planData['tipo'],
                'descripcion' => $planData['descripcion'],
            ]);
        }

        foreach ($datos as $dato) {
            DatoCita::create([
                'cita_id' => $cita,
                'descripcion' => $dato,
            ]);
        }

        foreach ($objetivos as $obj) {
            ObjetivoCita::create([
                'cita_id' => $cita,
                'codigo' => $obj['codigo'],
                'valor' => $obj['valor'],
            ]);
        }

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
        $paciente = Paciente::findOrFail($cita_->paciente_id);

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

        $cita_->estado = 'completada';
        $cita_->gestionado = 1;
        $cita_->save();

        return redirect()->back()->with('status', 'La gestión de la cita fue actualizada correctamente.');
    }


    public function edit_gestion($cita)
    {
        $cita_ = Cita::with(['diagnostico', 'planes', 'datosCita', 'objetivosCita'])->findOrFail($cita);

        return response()->json([
            'cod_diagnostico' => $cita_->diagnostico->cod_diagnostico ?? '',
            'criterio_clinico' => $cita_->diagnostico->criterio_clinico ?? '',
            'evolucion_diagnostico' => $cita_->diagnostico->evolucion_diagnostico ?? '',
            'planes' => $cita_->planes->map(function ($plan) {
                return [
                    'tipo' => $plan->tipo,
                    'descripcion' => $plan->descripcion,
                    'tipoNombre' => $this->CatalogoRepository->getNombreCatalogo($plan->tipo),
                ];
            }),
            'datos' => $cita_->datosCita->pluck('descripcion'),
            'objetivos' => $cita_->objetivosCita->map(function ($obj) {
                return [
                    'codigo' => $obj->codigo,
                    'nombre' => $this->CatalogoRepository->getNombreCatalogo($obj->codigo),
                    'valor' => $obj->valor,
                ];
            }),
        ]);
    }

    public function ver_gestion($cita)
    {
        $cita_ = Cita::with(['diagnostico', 'planes', 'datosCita', 'objetivosCita'])->findOrFail($cita);

        return response()->json([
            'cod_diagnostico' => $this->CatalogoRepository->getNombreCatalogo($cita_->diagnostico->cod_diagnostico) ?? '',
            'criterio_clinico' => $cita_->diagnostico->criterio_clinico ?? '',
            'evolucion_diagnostico' => $cita_->diagnostico->evolucion_diagnostico ?? '',
            'planes' => $cita_->planes->map(function ($plan) {
                return [
                    'tipo' => $plan->tipo,
                    'descripcion' => $plan->descripcion,
                    'tipoNombre' => $this->CatalogoRepository->getNombreCatalogo($plan->tipo),
                ];
            }),
            'datos' => $cita_->datosCita->pluck('descripcion'),
            'objetivos' => $cita_->objetivosCita->map(function ($obj) {
                return [
                    'codigo' => $obj->codigo,
                    'nombre' => $this->CatalogoRepository->getNombreCatalogo($obj->codigo),
                    'valor' => $obj->valor,
                ];
            }),
        ]);
    }

    public function store_hoja(Request $request, $cita)
    {
        // dd($request);
        $cita = Cita::findOrFail($cita);

        $cita->examenes()->delete();


        $examenesSeleccionados = $request->input('examenes', []);

        foreach ($examenesSeleccionados as $examenId) {

            $examen = CitaExamen::create([
                'cita_id' => $cita->id,
                'examen' => $examenId,
                'examen_otro' => null,
            ]);
        }

        if ($request->filled('examen_otro_check') && $request->filled('examen_otro_texto')) {

            $examen = CitaExamen::create([
                'cita_id' => $cita->id,
                'examen_id' => null,
                'examen_otro' => $request->input('examen_otro_texto'),
            ]);
        }
        return redirect()->back()->with('status', 'Se guardó la hoja correctamente.');

    }
    public function getExamenes($id)
    {
        $cita = Cita::with('examenes')->findOrFail($id);

        $examenesSeleccionados = $cita->examenes
            ->whereNotNull('examen')
            ->pluck('examen');

        $examenOtro = $cita->examenes
            ->whereNotNull('examen_otro')
            ->pluck('examen_otro')
            ->first();

        return response()->json([
            'examenes' => $examenesSeleccionados,
            'examen_otro' => $examenOtro
        ]);
    }
    public function update_hoja(Request $request, $cita)
    {
        $cita = Cita::findOrFail($cita);


        $cita->examenes()->delete();


        $examenesSeleccionados = $request->input('examenes', []);
        foreach ($examenesSeleccionados as $examenId) {
            CitaExamen::create([
                'cita_id' => $cita->id,
                'examen' => $examenId,
                'examen_otro' => null,
            ]);
        }


        if ($request->filled('examen_otro_check') && $request->filled('examen_otro_texto')) {
            CitaExamen::create([
                'cita_id' => $cita->id,
                'examen' => null,
                'examen_otro' => $request->input('examen_otro_texto'),
            ]);
        }

        return redirect()->back()->with('status', 'La hoja fue actualizada correctamente.');
    }
    public function exportPDFCita(cita $cita)
    {

        $user = auth()->user();

        $fecha = Carbon::now()->format('d-m-Y H:i:s');

        $tratamiento = Tratamiento::with('citas.examenes')->find($cita->tratamiento_id);

        $paciente = Paciente::find($cita->paciente_id);

        $proximaCita = Cita::where('tratamiento_id', $cita->tratamiento_id)
            ->where('fecha_hora', '>', $cita->fecha_hora)
            ->orderBy('fecha_hora', 'asc')
            ->first();

        if ($proximaCita) {
            // Convertir la fecha a formato literal en español
            Carbon::setLocale('es');
            $fechaProxima = Carbon::parse($proximaCita->fecha_hora)
                ->isoFormat('dddd, D [de] MMMM [de] YYYY, [Hora ] HH:mm');
        } else {
            $fechaProxima = 'No hay próxima cita agendada';
        }


        $path = public_path('logo.png');

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            $base64 = null;
        }

        $cita = Cita::with(
            'diagnosticos',
            'planes',
            'datosCita',
            'objetivosCita',
            'examenes'
        )->find($cita->id);
        $config = Configuracion::first();
        $firma = $config->firma;
        //dd($cita);
        return ExportPDF::exportPdf(
            'tratamientos.exportar_resumen',
            [
                'fechaProxima' => $fechaProxima,
                'tratamiento' => $tratamiento,
                'cita' => $cita,
                'user' => $user,
                'fecha' => $fecha,
                'logo_base64' => $base64,
                'firma' => $firma,
                'paciente' => $paciente,

                'export' => 'gestion_tratamiento'
            ],
            'tratamiento',
            false,
            [
                'margin_bottom' => 25,
            ]
        );
    }

    public function exportPDF_Hoja_lab(cita $cita)
    {

        $user = auth()->user();

        $fecha = Carbon::now()->format('d-m-Y H:i:s');

        $tratamiento = Tratamiento::find($cita->tratamiento_id);
        $paciente = Paciente::find($cita->paciente_id);
        $examenes = Catalogo::where('categoria_id', 14)->get();

        $path = public_path('logo.png');

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            $base64 = null;
        }

        $cita = Cita::with(

            'examenes.catalogo'
        )->find($cita->id);
        $config = Configuracion::first();
        $firma = $config->firma;
        //dd($cita);

        return ExportPDF::exportPdf(
            'tratamientos.export_hoja_laboratorio',
            [
                'tratamiento' => $tratamiento,
                'cita' => $cita,
                'examenes' => $examenes,
                'user' => $user,
                'fecha' => $fecha,
                'logo_base64' => $base64,
                'firma' => $firma,
                'export' => 'gestion_tratamiento',
                'paciente' => $paciente,

            ],
            'tratamiento',
            false,
            [
                'margin_bottom' => 25,
            ]
        );
    }

    function Gestion(Cita $cita)
    {
        // Breadcrumb


        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Citas', 'url' => route('citas.index')],
            ['name' => 'Gestión Cita', 'url' => ''],

        ];

        $paciente = Paciente::find($cita->paciente_id);
        $objetivos = Catalogo::where('categoria_id', 9)->get();
        $diagnosticos = Catalogo::where('categoria_id', 6)->get();
        $planes = Catalogo::where('categoria_id', 10)->get();
        $examenes = Catalogo::where('categoria_id', 14)->get();

        $antecedente = Categoria::where('nombre', 'Diagnosticos')->first();

        $antecedentes = Catalogo::where('categoria_id', $antecedente->id)->where('catalogo_estado', 1)->get();

        $familia = Categoria::where('nombre', 'Familiar')->first();


        $familiares = Catalogo::where('categoria_id', $familia->id)->where('catalogo_estado', 1)->get();


        return view('citas.gestion_cita', compact('paciente', 'familiares', 'antecedentes', 'cita', 'examenes', 'planes', 'diagnosticos', 'objetivos', 'breadcrumb'));
    }
    public function validarConflictoCita($fechaHora, $duracion, $usuarios)
    {
        $inicioNuevo = Carbon::parse($fechaHora);
        $duracion = (int) ($duracion ?? 0);
        $finNuevo = $inicioNuevo->copy()->addMinutes($duracion);

        foreach ($usuarios as $usuario) {
            $userId = $usuario['id'] ?? null;
            if (!$userId)
                continue;

            $conflicto = DB::table('citas')
                ->join('cita_user', 'citas.id', '=', 'cita_user.cita_id')
                ->where('cita_user.user_id', $userId)
                ->where(function ($query) use ($inicioNuevo, $finNuevo) {
                    $query->where('citas.fecha_hora', '<', $finNuevo)
                        ->whereRaw("DATE_ADD(citas.fecha_hora, INTERVAL citas.duracion MINUTE) > ?", [$inicioNuevo]);
                })
                ->exists();

            if ($conflicto) {
                $usuarioModel = User::find($userId);
                return [
                    'status' => false,
                    'mensaje' => "El usuario {$usuarioModel->name} ya tiene una cita que se superpone en el rango de tiempo indicado.",
                ];
            }
        }

        return ['status' => true];
    }
    public function validarConflictoAjax(Request $request)
    {
        $request->validate([
            'fecha_hora' => 'required|date',
            'duracion' => 'nullable|integer|min:1',
            'usuarios' => 'required|array',
            'usuarios.*.id' => 'required|integer|exists:users,id',
        ]);

        $resultado = $this->validarConflictoCita(
            $request->input('fecha_hora'),
            $request->input('duracion'),
            $request->input('usuarios')
        );

        return response()->json($resultado);
    }

}
