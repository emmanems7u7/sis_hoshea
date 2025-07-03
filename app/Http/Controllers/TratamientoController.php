<?php
namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Tratamiento;
use App\Models\Paciente;
use App\Models\user;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarNotificacionPaciente;
use App\Exports\ExportExcel;
use App\Exports\ExportPDF;

use Maatwebsite\Excel\Facades\Excel;
class TratamientoController extends Controller
{
    public function index(Request $request)
    {
        $hoy = Carbon::today();

        $tratamientos = Tratamiento::with(['paciente', 'citas'])
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
            ->when($request->has('anteriores'), function ($query) use ($hoy) {
                $query->whereNotNull('fecha_fin')
                    ->whereDate('fecha_fin', '<=', $hoy);
            })
            ->orderByDesc('fecha_inicio')
            ->paginate(15)
            ->withQueryString();



        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Tratamientos', 'url' => route('tratamientos.index')],
        ];
        return view('tratamientos.index', compact('tratamientos', 'breadcrumb'));
    }

    public function create()
    {
        $pacientes = Paciente::all()->pluck('nombre_completo', 'id');
        $breadcrumb = [
            ['name' => 'Tratamientos', 'url' => route('tratamientos.index')],
            ['name' => 'Crear tratamiento', 'url' => '#'],
        ];
        $pac = 0;
        $usuarios = User::role(['medico', 'enfermero'])->orderBy('name')->pluck('name', 'id');
        return view('tratamientos.create', compact('usuarios', 'pac', 'pacientes', 'breadcrumb'));
    }

    public function store(Request $request)
    {


        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado_tratamiento' => 'required|string|max:50',
            'observaciones_tratamiento' => 'nullable|string',
        ]);

        $tratamiento = Tratamiento::create([
            'paciente_id' => $validated['paciente_id'],
            'nombre' => $validated['nombre'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'] ?? null,
            'estado' => $validated['estado_tratamiento'],
            'observaciones' => $validated['observaciones_tratamiento'] ?? null,
        ]);


        $citasArray = json_decode($request->input('citas_json'), true);

        if (is_null($citasArray) || !is_array($citasArray) || empty($citasArray)) {
            return redirect()->back()->with('error', 'Debe agregar al menos una cita');
        }
        // Iterar y crear cada cita
        foreach ($citasArray as $citaData) {
            $cita = Cita::create([
                'paciente_id' => $tratamiento->paciente_id,
                'tratamiento_id' => $tratamiento->id,
                'fecha_hora' => $citaData['fecha_hora'],
                'duracion' => $citaData['duracion'] ?? null,
                'estado' => $citaData['estado'],
                'observaciones' => $citaData['observaciones'] ?? null,
            ]);


            if (!empty($citaData['usuarios']) && !empty($citaData['roles'])) {
                $syncData = [];
                foreach ($citaData['usuarios'] as $index => $userId) {
                    $rol = $citaData['roles'][$index] ?? null;
                    $syncData[$userId] = ['rol_en_cita' => $rol];
                }
                $cita->usuarios()->sync($syncData);
            }
        }


        return redirect()->route('tratamientos.index')->with('status', 'Tratamiento creado correctamente.');
    }

    public function edit(Tratamiento $tratamiento)
    {
        $pacientes = Paciente::all()->pluck('nombre_completo', 'id');

        $breadcrumb = [
            ['name' => 'Tratamientos', 'url' => route('tratamientos.index')],
            ['name' => 'Editar tratamiento', 'url' => '#'],
        ];
        $cita = Cita::where('tratamiento_id', $tratamiento->id)->first();

        $usuariosAsignados = $cita->usuarios->pluck('pivot.rol_en_cita', 'id')->toArray();
        $pac = 0;
        return view('tratamientos.edit', compact('pac', 'tratamiento', 'pacientes', 'breadcrumb'));
    }

    public function update(Request $request, Tratamiento $tratamiento)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'estado' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $tratamiento->update($validated);

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
        dd($tratamientos);
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
}
