<?php

namespace App\Interfaces;

interface CatalogoInterface
{
    public function GuardarCatalogo($request);
    public function EditarCatalogo($request, $catalogo);

    public function GuardarCategoria($request);
    public function EditarCategoria($request, $categoria);
    function generarNuevoCodigoCatalogo(int $categoriaId, string $codigoInicial = 'diag-0020');

}
