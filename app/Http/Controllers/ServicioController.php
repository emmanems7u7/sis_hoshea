<?php

namespace App\Http\Controllers;

use App\Models\Catalogo;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Tratamiento;
use App\Models\Inventario;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;
use App\Exports\ExportPDF;
use Carbon\Carbon;

class ServicioController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Servicios', 'url' => route('servicios.index')],
        ];

        $servicios = Servicio::all();
        return view('servicios.index', compact('servicios', 'breadcrumb'));
    }
    public function show(Cita $cita)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Tratamientos', 'url' => route('tratamientos.index')],
            ['name' => 'Gestión Cita', 'url' => ''],

        ];
        $tratamiento = Tratamiento::find($cita->tratamiento_id);

        $objetivos = Catalogo::where('categoria_id', 9)->get();
        $diagnosticos = Catalogo::where('categoria_id', 6)->get();
        $planes = Catalogo::where('categoria_id', 10)->get();
        $examenes = Catalogo::where('categoria_id', 14)->get();

        $servicios = Servicio::where('activo', 1)->pluck('nombre', 'id');
        $serviciosDetalles = Servicio::where('activo', 1)->get();


        $inventarios = Inventario::where('stock_actual', '>', 0)->pluck('nombre', 'id');
        $inventarioDetalles = Inventario::where('stock_actual', '>', 0)->get();

        return view('servicios.ver_asignacion', compact('inventarios', 'inventarioDetalles', 'serviciosDetalles', 'servicios', 'breadcrumb', 'cita', 'tratamiento', 'examenes', 'planes', 'diagnosticos', 'objetivos', ));
    }
    public function recibo(Cita $cita)
    {

        $cita = Cita::with('servicios', 'inventarios')->findOrFail($cita->id);

        $tratamiento = Tratamiento::with('paciente', 'citas.examenes')->find($cita->tratamiento_id);


        $path = public_path('logo.png');

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            $base64 = null;
        }
        $user = auth()->user();

        $fecha = Carbon::now()->format('d-m-Y H:i:s');
        return ExportPDF::exportPdf(
            'servicios.recibo',
            [
                'cita' => $cita,
                'fecha' => $fecha,
                'user' => $user,
                'tratamiento' => $tratamiento,
                'base64' => $base64,
                'export' => 'Recibo'
            ],
            'Recibo',
            false
        );

    }
    public function create()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Servicios', 'url' => route('servicios.index')],
            ['name' => 'Crear Servicio', 'url' => route('servicios.create')],
        ];

        return view('servicios.create', compact('breadcrumb'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'nullable|numeric',
            'descripcion' => 'nullable|string',
            'activo' => 'required|boolean',
        ]);

        Servicio::create($request->all());

        return redirect()->route('servicios.index')->with('status', 'Servicio creado correctamente.');
    }

    public function edit(Servicio $servicio)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Servicios', 'url' => route('servicios.index')],
            ['name' => 'Editar Servicio', 'url' => route('servicios.edit', $servicio)],
        ];

        return view('servicios.edit', compact('servicio', 'breadcrumb'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'nullable|numeric',
            'descripcion' => 'nullable|string',
            'activo' => 'required|boolean',
        ]);

        $servicio->update($request->all());

        return redirect()->route('servicios.index')->with('status', 'Servicio actualizado correctamente.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('servicios.index')->with('status', 'Servicio eliminado correctamente.');
    }

    public function asignar(Cita $cita)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Tratamientos', 'url' => route('tratamientos.index')],
            ['name' => 'Gestión Cita', 'url' => ''],

        ];
        $tratamiento = Tratamiento::find($cita->tratamiento_id);

        $objetivos = Catalogo::where('categoria_id', 9)->get();
        $diagnosticos = Catalogo::where('categoria_id', 6)->get();
        $planes = Catalogo::where('categoria_id', 10)->get();
        $examenes = Catalogo::where('categoria_id', 14)->get();

        $servicios = Servicio::where('activo', 1)->pluck('nombre', 'id');
        $serviciosDetalles = Servicio::where('activo', 1)->get();

        $inventarios = Inventario::where('stock_actual', '>', 0)->pluck('nombre', 'id');
        $inventarioDetalles = Inventario::where('stock_actual', '>', 0)->get();

        return view('servicios.asignar', compact('inventarios', 'inventarioDetalles', 'serviciosDetalles', 'servicios', 'breadcrumb', 'cita', 'tratamiento', 'examenes', 'planes', 'diagnosticos', 'objetivos', ));
    }

    public function guardar_asignacion(Request $request, $id)
    {
        // Validaciones
        $request->validate([
            'servicios_seleccionados' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value !== null) {
                        $decoded = json_decode($value, true);
                        if (!is_array($decoded)) {
                            $fail('El campo servicios seleccionados debe ser un arreglo JSON válido.');
                        }
                    }
                }
            ],
            'inventario_utilizado' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value !== null) {
                        $decoded = json_decode($value, true);
                        if (!is_array($decoded)) {
                            $fail('El campo inventario utilizado debe ser un arreglo JSON válido.');
                        }
                    }
                }
            ]
        ]);

        $cita = Cita::findOrFail($id);

        // === LÓGICA DE SERVICIOS ===
        $serviciosIds = [];

        if ($request->filled('servicios_seleccionados')) {
            $serviciosIds = json_decode($request->input('servicios_seleccionados'), true);
            $existen = Servicio::whereIn('id', $serviciosIds)->pluck('id')->toArray();
            $serviciosIds = array_intersect($serviciosIds, $existen);
        }

        $cita->servicios()->sync($serviciosIds);

        // === LÓGICA DE INVENTARIO ===
        if ($request->filled('inventario_utilizado')) {
            $inventarios = json_decode($request->input('inventario_utilizado'), true);

            $dataSincronizada = [];

            foreach ($inventarios as $item) {
                // Validar que exista el inventario
                $inventario = Inventario::find($item['id']);
                if ($inventario) {
                    $dataSincronizada[$item['id']] = [
                        'cantidad' => $item['cantidad'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Guardar la relación en la tabla pivote cita_inventario
            $cita->inventarios()->sync($dataSincronizada);
        }

        return redirect()->back()->with('status', 'Servicios e inventario asignados correctamente.');
    }


}
