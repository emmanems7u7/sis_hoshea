<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Tratamiento;
use App\Models\Paciente;

use App\Models\User;



use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\ConfiguracionCredenciales;
use App\Models\Inventario;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $config = ConfiguracionCredenciales::first();
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
        ];

        if (Auth::user()->usuario_fecha_ultimo_password) {
            $ultimoCambio = Carbon::parse(Auth::user()->usuario_fecha_ultimo_password);

            $diferenciaDias = (int) $ultimoCambio->diffInDays(Carbon::now());

            if ($diferenciaDias >= $config->conf_duracion_max) {
                $tiempo_cambio_contraseña = 1;
            } else {
                $tiempo_cambio_contraseña = 2;
            }
        } else {
            $tiempo_cambio_contraseña = 1;
        }

        $totalPacientes = Paciente::all()->count();
        $tratamientosActivos = Tratamiento::where('estado', 'Activo')->count();
        $citasActivas = Cita::where('estado', 'confirmada')->count();
        $personalActivo = User::all()->count();

        if (Auth::user()->hasRole('admin')) {

            $citas = Cita::with(['paciente', 'tratamiento', 'usuarios'])
                ->where('estado', '!=', 'completada')
                ->where('tratamiento_id', null)
                ->orderBy('fecha_hora', 'desc')
                ->paginate(15);

            $tratamientos = Tratamiento::with('paciente', 'citas')->orderByDesc('fecha_inicio')->paginate(15);
            return view('home', compact(
                'tratamientos',
                'citas',
                'breadcrumb',
                'tiempo_cambio_contraseña',
                'totalPacientes',
                'tratamientosActivos',
                'citasActivas',
                'personalActivo',
            ));
        } else {
            $citas = Cita::with(['paciente', 'tratamiento', 'usuarios'])
                ->where('estado', '!=', 'completada')
                ->where('tratamiento_id', null)
                ->whereHas('usuarios', function ($query) {
                    $query->where('users.id', Auth::id());
                })
                ->orderBy('fecha_hora', 'desc')
                ->paginate(15);


            $citasConTratamientos = Auth::user()->citas()->with('tratamiento')->get();


            $tratamientos = $citasConTratamientos
                ->pluck('tratamiento')
                ->filter()
                ->unique('id')
                ->values();

            return view('home', compact(
                'tratamientos',
                'citas',
                'breadcrumb',
                'tiempo_cambio_contraseña',
                'totalPacientes',
                'tratamientosActivos',
                'citasActivas',
                'personalActivo',
            ));
        }








    }

}
