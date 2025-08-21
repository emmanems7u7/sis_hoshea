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

        $request->validate([
            'doble_factor_autenticacion' => 'nullable|boolean',
            'limite_de_sesiones' => 'nullable|integer|min:1',
            'GROQ_API_KEY' => 'nullable|string|max:255',

            'firma' => 'nullable|boolean',
            'hoja_export' => 'nullable|string|max:255',
            'dias_atencion' => 'nullable|array',
            'dias_atencion.*' => 'string|max:20',
            'roles_landing' => 'nullable|array',
            'roles_landing.*' => 'string|max:50',
            'titulo_presentacion' => 'required|string|max:255',
            'descripcion_presentacion' => 'required|string',
            'direccion' => 'required|string|max:255',
            'celular' => 'required|string|regex:/^[0-9+\-\s]{6,20}$/',
            'geolocalizacion' => [
                'required',
                'regex:/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/'
            ],
            'imagen_fondo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'logo_empresa' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'titulo_cabecera' => 'required|string|max:255',
            'descripcion_cabecera' => 'nullable|string',
            'imagen_cabecera' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'titulo_emergencia' => 'required|string|max:255',
            'descripcion_emergencia' => 'nullable|string',
        ]);



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


        // Update campos simples
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
            'geolocalizacion' => $request->input('geolocalizacion'),
            'titulo_cabecera' => $request->input('titulo_cabecera'),
            'descripcion_cabecera' => $request->input('descripcion_cabecera'),
            'titulo_emergencia' => $request->input('titulo_emergencia'),
            'descripcion_emergencia' => $request->input('descripcion_emergencia'),

        ]);

        // Carpeta destino en public
        $uploadPath = public_path('storage/config/');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Imagen de fondo
        if ($request->hasFile('imagen_fondo')) {
            // eliminar imagen anterior si existe
            if ($config->imagen_fondo && file_exists(public_path($config->imagen_fondo))) {
                unlink(public_path($config->imagen_fondo));
            }

            $file = $request->file('imagen_fondo');
            $filename = time() . '_fondo.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $config->imagen_fondo = 'storage/config/' . $filename;
        }

        // Logo empresa
        if ($request->hasFile('logo_empresa')) {
            if ($config->logo_empresa && file_exists(public_path($config->logo_empresa))) {
                unlink(public_path($config->logo_empresa));
            }

            $file = $request->file('logo_empresa');
            $filename = time() . '_logo.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $config->logo_empresa = 'storage/config/' . $filename;
        }

        // Imagen cabecera
        if ($request->hasFile('imagen_cabecera')) {
            if ($config->imagen_cabecera && file_exists(public_path($config->imagen_cabecera))) {
                unlink(public_path($config->imagen_cabecera));
            }

            $file = $request->file('imagen_cabecera');
            $filename = time() . '_cabecera.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $config->imagen_cabecera = 'storage/config/' . $filename;
        }

        // Guardar cambios
        $config->save();

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
