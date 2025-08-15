<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\ServicioLanding;
use Illuminate\Http\Request;

class ServicioLandingController extends Controller
{
    public function index()
    {

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Servicios Landing', 'url' => route('servicios_landing.index')],
        ];
        $servicios = ServicioLanding::paginate(10);
        $config = Configuracion::first();
        return view('ServiciosLanding.index', compact('config', 'servicios', 'breadcrumb'));
    }

    public function create()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Servicios Landing', 'url' => route('servicios_landing.index')],
            ['name' => 'Crear Servicios Landing', 'url' => route('servicios_landing.index')],
        ];
        return view('ServiciosLanding.create', compact('breadcrumb'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'icono' => 'nullable|string|max:100',
            'estado' => 'required|boolean',
        ]);


        ServicioLanding::create($validated);

        return redirect()->route('servicios_landing.index')->with('status', 'Servicio creado correctamente.');
    }

    public function edit(ServicioLanding $servicio)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Servicios Landing', 'url' => route('servicios_landing.index')],
            ['name' => 'Editar Servicio Landing', 'url' => route('servicios_landing.index')],
        ];
        return view('ServiciosLanding.edit', compact('servicio', 'breadcrumb'));
    }

    public function update(Request $request, ServicioLanding $servicio)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'icono' => 'nullable|string|max:100',
            'estado' => 'required|boolean',
        ]);

        $servicio->update($validated);

        return redirect()->route('servicios_landing.index')->with('status', 'Servicio actualizado correctamente.');
    }

    public function destroy(ServicioLanding $servicio)
    {
        $servicio->delete();
        return redirect()->route('servicios_landing.index')->with('status', 'Servicio eliminado correctamente.');
    }
}
