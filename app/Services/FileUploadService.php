<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Sube un archivo a /public/uploads/{folder} y devuelve la ruta relativa.
     *
     * @param  UploadedFile  $file     Instancia del archivo.
     * @param  string        $folder   Carpeta de destino (ej. 'imagenes', 'documentos').
     * @return string                  Ruta relativa (ej. 'uploads/imagenes/abc.jpg').
     */
    public static function upload(UploadedFile $file, string $folder): string
    {
        // Crear nombre seguro y único
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Ruta física absoluta
        $destination = public_path("uploads/{$folder}");

        // Garantizar que la carpeta exista
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        // Mover archivo
        $file->move($destination, $filename);

        // Ruta relativa a usar en la BD
        return "uploads/{$folder}/{$filename}";
    }
}
