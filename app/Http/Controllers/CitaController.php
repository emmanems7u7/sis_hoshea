<?php
namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Tratamiento;
use App\Models\User;
use Illuminate\Http\Request;

class CitaController extends Controller
{
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
}
