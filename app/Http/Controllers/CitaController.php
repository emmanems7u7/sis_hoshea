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


use App\Exports\ExportPDF;

use Illuminate\Http\Request;
use App\Interfaces\CatalogoInterface;

class CitaController extends Controller
{
    protected $CatalogoRepository;
    public function __construct(CatalogoInterface $CatalogoInterface)
    {

        $this->CatalogoRepository = $CatalogoInterface;
    }

    public function index()
    {
        $citas = Cita::with(['paciente', 'tratamiento', 'usuarios'])->orderBy('fecha_hora', 'desc')->paginate(15);
        $breadcrumb = [
            ['name' => 'Citas', 'url' => route('citas.index')],
        ];
        return view('citas.index', compact('citas', 'breadcrumb'));
    }

    public function create()
    {
        $pacientes = Paciente::all()->pluck('nombre_completo', 'id');
        $tratamientos = Tratamiento::orderByDesc('fecha_inicio')->pluck('nombre', 'id');
        $usuarios = User::role(['medico', 'enfermero'])->orderBy('name')->pluck('name', 'id');
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
            'usuarios.*' => 'exists:users,id',
            'roles' => 'required|array|min:1',
            'roles.*' => 'string',
        ]);

        // Validar que el paciente del tratamiento coincida con el paciente_id (si hay tratamiento)
        if ($validated['tratamiento_id']) {
            $tratamiento = Tratamiento::findOrFail($validated['tratamiento_id']);
            if ($tratamiento->paciente_id != $validated['paciente_id']) {
                return back()->withErrors(['tratamiento_id' => 'El tratamiento no corresponde al paciente seleccionado.'])->withInput();
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
        }
        $cita->usuarios()->sync($syncData);

        return redirect()->route('citas.index')->with('success', 'Cita creada correctamente.');
    }

    public function edit(Cita $cita)
    {
        $pacientes = Paciente::all()->pluck('nombre_completo', 'id');
        $tratamientos = Tratamiento::orderByDesc('fecha_inicio')->pluck('nombre', 'id');
        $usuarios = User::role(['medico', 'enfermero'])->orderBy('name')->pluck('name', 'id');

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
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'tratamiento_id' => 'nullable|exists:tratamientos,id',
            'fecha_hora' => 'required|date',
            'duracion' => 'nullable|integer|min:1',
            'estado' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
            'usuarios' => 'required|array|min:1',
            'usuarios.*' => 'exists:users,id',
            'roles' => 'required|array|min:1',
            'roles.*' => 'string',
        ]);

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

            switch ($cita->estado) {
                case 'pendiente':

                    break;
                case 'confirmada':

                    break;
                case 'cancelada':
                    break;
                case 'completada':
                    break;
                default:
                    break;
            }

        }

        return redirect()->back()->with('status', 'Estado de la cita actualizado correctamente.');
    }

    function store_gestion(Request $request, $cita)
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

        $tratamiento = Tratamiento::findOrFail($cita_->tratamiento_id);


        $diagnostico = Diagnostico::create([
            'tratamiento_id' => $tratamiento->id,
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

        $cita_->estado = 'completada';
        $cita_->gestionado = 1;
        $cita_->fecha_gestion = now();
        $cita_->save();

        return redirect()->back()->with('status', 'La información de la cita se guardó correctamente. Ahora puede generar el PDF.
        Además, se ha enviado una notificación al paciente y al personal asociado a la cita.');

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
        $diagnostico = Diagnostico::where('tratamiento_id', $tratamiento->id)->first();
        if ($diagnostico) {
            $diagnostico->update([
                'cod_diagnostico' => $request->input('cod_diagnostico'),
                'criterio_clinico' => $request->input('criterio_clinico'),
                'evolucion_diagnostico' => $request->input('evolucion_diagnostico'),
            ]);
        } else {
            Diagnostico::create([
                'tratamiento_id' => $tratamiento->id,
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

        $tratamiento = Tratamiento::with('paciente', 'citas.examenes')->find($cita->tratamiento_id);


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

                'export' => 'gestion_tratamiento'
            ],
            'tratamiento',
            false,
            [
                'margin_bottom' => 25,
            ]
        );
    }
}
