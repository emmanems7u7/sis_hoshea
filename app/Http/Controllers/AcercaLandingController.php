<?php

namespace App\Http\Controllers;

use App\Models\AcercaLanding;
use App\Models\Configuracion;
use Illuminate\Http\Request;

class AcercaLandingController extends Controller
{
    public function index()
    {

        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Acerca de Landing', 'url' => route('servicios_landing.index')],
        ];
        $config = Configuracion::first();
        $items = AcercaLanding::paginate(10);
        return view('AcercaLanding.index', compact('config', 'items', 'breadcrumb'));
    }

    public function create()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Acerca de Landing', 'url' => route('acerca_landings.index')],
            ['name' => 'Crear Acerca de', 'url' => route('acerca_landings.index')],

        ];

        return view('AcercaLanding.create', compact('breadcrumb'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'icono' => 'required|string|max:100',
        ]);

        AcercaLanding::create($request->only('descripcion', 'icono'));

        return redirect()->route('acerca_landings.index')->with('success', 'Registro creado correctamente.');
    }

    public function edit($id)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Acerca de Landing', 'url' => route('acerca_landings.index')],
            ['name' => 'Editar Acerca de', 'url' => route('acerca_landings.index')],

        ];
        $item = AcercaLanding::findOrFail($id);
        return view('AcercaLanding.edit', compact('item', 'breadcrumb'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'icono' => 'required|string|max:100',
        ]);

        $item = AcercaLanding::findOrFail($id);
        $item->update($request->only('descripcion', 'icono'));

        return redirect()->route('acerca_landings.index')->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $item = AcercaLanding::findOrFail($id);
        $item->delete();

        return redirect()->route('acerca_landings.index')->with('success', 'Registro eliminado correctamente.');
    }
}
