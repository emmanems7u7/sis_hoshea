<?php

// app/Http/Controllers/ArtisanController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Exception\CommandNotFoundException;
class ArtisanController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Panel de Artisan', 'url' => route('artisan.index')],
        ];

        $request->validate([
            'clave_segura' => 'required|string',
        ]);

        if ($request->clave_segura !== env('ARTISAN_PANEL_PASSWORD')) {
            return back()->with('error', 'Contraseña incorrecta');
        }
        $clave_segura = $request->clave_segura;
        return view('admin.artisan-panel', compact('clave_segura', 'breadcrumb'));
    }

    public function run(Request $request)
    {
        $request->validate([
            'comando' => 'required|string',
        ]);

        $comando = $request->input('comando');

        try {
            Artisan::call($comando);
            $output = Artisan::output();

            return back()->with('output', $output);
        } catch (CommandNotFoundException $e) {
            return back()->with('error', 'Comando no reconocido.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al ejecutar: ' . $e->getMessage());
        }
    }
    public function verificacion()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Panel de Artisan', 'url' => route('artisan.index')],
        ];
        return view('admin.artisan-verificacion', compact('breadcrumb'));
    }
    public function verificar(Request $request)
    {
        $request->validate([
            'clave_segura' => 'required|string',
        ]);

        if ($request->clave_segura === env('ARTISAN_PANEL_PASSWORD')) {
            session(['artisan_access_granted' => true]);
            return redirect()->route('artisan.index');
        }

        return back()->with('error', 'Contraseña incorrecta');
    }
}
