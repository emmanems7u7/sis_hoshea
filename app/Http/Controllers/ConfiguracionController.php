<?php

namespace App\Http\Controllers;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
class ConfiguracionController extends Controller
{
    public function edit()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Configuracion', 'url' => route('admin.configuracion.edit')],
        ];
        $config = Configuracion::first();
        $roles = Role::all();
        $diasGuardados = json_decode($config->dias_atencion, true) ?? [];
        $rolesSeleccionados = old('roles_landing', json_decode($config->roles_landing ?? '[]', true));
        return view('configuracion.configuracion_general', compact('rolesSeleccionados', 'roles', 'diasGuardados', 'config', 'breadcrumb'));
    }

    public function update(Request $request)
    {

        $diasInput = $request->input('dias', []);

        $diasCompletos = [];
        foreach (['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia) {
            $activo = isset($diasInput[$dia]['activo']) && $diasInput[$dia]['activo'] == '1';
            $inicio = $activo ? ($diasInput[$dia]['inicio'] ?? null) : null;
            $fin = $activo ? ($diasInput[$dia]['fin'] ?? null) : null;

            $diasCompletos[$dia] = [
                'activo' => $activo,
                'inicio' => $inicio,
                'fin' => $fin,
            ];
        }
        $config = Configuracion::first();

        $config->update([
            'doble_factor_autenticacion' => $request->has('doble_factor_autenticacion'),
            'limite_de_sesiones' => $request->input('limite_de_sesiones'),
            'GROQ_API_KEY' => $request->input('GROQ_API_KEY'),
            'mantenimiento' => $request->has('mantenimiento'),
            'firma' => $request->has('firma'),
            'hoja_export' => $request->input('hoja_export'),
            'dias_atencion' => json_encode($diasCompletos),
            'roles_landing' => json_encode($request->roles_landing ?? []),
            'titulo_presentacion' => $request->input('titulo_presentacion'),
            'descripcion_presentacion' => $request->input('descripcion_presentacion'),
            'direccion' => $request->input('direccion'),
            'celular' => $request->input('celular'),
            'geolocalizacion' => $request->input('geolocalizacion')


        ]);


        return redirect()->back()->with('status', 'Configuración actualizada.');
    }
    public function update_servicio(Request $request)
    {


        $config = Configuracion::first();

        $config->update([
            'titulo_servicio' => $request->input('titulo_servicio'),
            'descripcion_servicio' => $request->input('descripcion_servicio')
        ]);

        return redirect()->back()->with('status', 'Configuración actualizada.');
    }

    public function update_acercade(Request $request)
    {


        $config = Configuracion::first();

        $config->update([
            'titulo_acercade' => $request->input('titulo_acercade'),
            'descripcion_acercade' => $request->input('descripcion_acercade')
        ]);

        return redirect()->back()->with('status', 'Configuración actualizada.');
    }
}
