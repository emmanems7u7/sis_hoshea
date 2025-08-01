<?php
namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Exports\ExportPDF;
use App\Models\Configuracion;
use Carbon\Carbon;

class BienController extends Controller
{
    public function index()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Inventario', 'url' => route('biens.index')],
        ];

        $biens = Bien::with('categoria')->paginate(5);
        return view('biens.index', compact('biens', 'breadcrumb'));
    }
    public function create()
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Inventario', 'url' => route('biens.index')],
            ['name' => 'Agregar bien', 'url' => ''],
        ];

        $categorias = Categoria::all();
        return view('biens.create', compact('categorias', 'breadcrumb'));
    }

    public function store(Request $request)
    {


        $data = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'descripcion' => 'nullable|string',
            'cantidad' => 'required|integer|min:1',
            'ubicacion' => 'nullable|string|max:255',
            'fecha_adquisicion' => 'nullable|date',
            'valor_adquisicion' => 'nullable|numeric',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('fotos_bienes'), $filename);
            $data['foto'] = $filename;
        }


        Bien::create($data);

        return redirect()->route('biens.index')->with('status', 'Bien creado correctamente.');
    }

    public function edit(Bien $bien)
    {
        $breadcrumb = [
            ['name' => 'Inicio', 'url' => route('home')],
            ['name' => 'Inventario', 'url' => route('biens.index')],
            ['name' => 'Editar bien', 'url' => ''],
        ];

        $categorias = Categoria::all();
        return view('biens.edit', compact('bien', 'categorias', 'breadcrumb'));
    }

    public function update(Request $request, Bien $bien)
    {
        $data = $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'descripcion' => 'nullable|string',
            'cantidad' => 'required|integer|min:1',
            'ubicacion' => 'nullable|string|max:255',
            'fecha_adquisicion' => 'nullable|date',
            'valor_adquisicion' => 'nullable|numeric',
        ]);

        if ($request->hasFile('foto')) {
            // Opcional: eliminar foto antigua si existe
            if ($bien->foto && file_exists(public_path('fotos_bienes/' . $bien->foto))) {
                unlink(public_path('fotos_bienes/' . $bien->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('fotos_bienes'), $filename);
            $data['foto'] = $filename;
        }
        $bien->update($data);

        return redirect()->route('biens.index')->with('status', 'Bien actualizado correctamente.');
    }

    public function destroy(Bien $bien)
    {
        $bien->delete();

        return redirect()->route('biens.index')->with('status', 'Bien eliminado correctamente.');
    }
    public function exportPDFBienes()
    {

        $user = auth()->user();
        $fecha = Carbon::now()->format('d-m-Y H:i:s');
        $path = public_path('logo.png');

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            $base64 = null;
        }
        $config = Configuracion::first();
        $firma = $config->firma;


        $biens = Bien::with('categoria')->get();

        return ExportPDF::exportPdf('biens.export_bien', [
            'biens' => $biens,
            'export' => 'export_bien',
            'user' => $user,
            'fecha' => $fecha,
            'logo_base64' => $base64,
            'firma' => $firma,
        ], 'bienes', false);

    }
}
