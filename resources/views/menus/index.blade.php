@extends('layouts.argon')

@section('content')

    @include('menus.create_seccion')
    @include('menus.create_menu')
    <!-- Sección de Secciones -->

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6>Módulo de Administración de Menú</h6>

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#crearSeccionModal">
                        Crear Sección
                    </button>

                    <button type="button" class="btn btn-primary mb-3" id="btn-crea-menu" data-bs-toggle="modal"
                        data-bs-target="#crearMenuModal">
                        Crear Menú
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm mb-2">
                <div class="card-body">
                    <h5 class="fw-bold">Información sobre la Administración del Menú</h5>

                    <small>- Este módulo te permite gestionar las <strong>secciones</strong> y sus <strong>menús
                            hijos</strong>, que componen la estructura del sistema de navegación.</small><br>

                    <small>- Para crear una nueva sección, utiliza el botón <strong>"Crear Sección"</strong>. Solo necesitas
                        asignar un nombre y el sistema sugerirá automáticamente un icono representativo mediante
                        IA.</small><br>

                    <small>- Para agregar un nuevo menú, haz clic en el botón <strong>"Crear Menú"</strong>. Se abrirá una
                        pantalla flotante donde deberás:</small><br>
                    <ul class="mb-2 mt-1" style="padding-left: 1.25rem;">
                        <li><small>Seleccionar la sección a la que pertenecerá el menú.</small></li>
                        <li><small>Escribir el título del menú.</small></li>
                        <li><small>Definir el orden (se sugiere un número según los menús ya existentes en la
                                sección).</small></li>
                        <li><small>Indicar la ruta de destino, por ejemplo: <code>tratamientos.index</code>.</small></li>
                    </ul>

                    <small>- Este módulo es <strong>totalmente personalizable</strong>. Sin embargo, se recomienda
                        <strong>no eliminar secciones existentes</strong>, ya que esto también eliminará sus menús
                        asociados.</small><br>

                    <small>- Por temas de seguridad y funcionamiento unicamente esta habilitada la opción de eliminar los
                        registros</small>
                    <br>
                    <small>- Si se crean nuevos menús, recuerda que <strong>debes asignar los permisos
                            correspondientes</strong> desde el módulo de <strong>Roles</strong>. Edita el rol del usuario y
                        busca la sección de "Menús" para dar acceso al nuevo elemento.</small>
                </div>

            </div>
        </div>
    </div>


    <div class="card mt-3 text-black">

        <div class="card-body text-black">

            @if($secciones->isEmpty())
                <p>No hay secciones disponibles.</p>
            @else
                <p>Lista de Secciones disponibles en el sistema.</p>
                <div class="row">
                    @foreach($secciones as $seccion)
                        <div class="col-md-4 mb-3">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body p-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <p class="text-green mb-2"><strong>Título:</strong> {{ $seccion->titulo }}</p>
                                    </div>

                                    <div class="mt-auto">
                                        <form action="{{ route('secciones.destroy', $seccion->id) }}" method="POST"
                                            id="delete-form-{{ $seccion->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger w-100"
                                                onclick="confirmarEliminacion('delete-form-{{ $seccion->id }}', '¿Estás seguro de eliminar esta sección?')">
                                                <i class="fas fa-trash-alt me-1"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @endif
        </div>
    </div>
    <hr>
    <div class="card text-black">

        <div class="card-body">
            <p>Lista de menus disponibles en el sistema.</p>


            @if($menus->isEmpty())
                <p>No hay menús disponibles.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered text-black">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Sección</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                                <tr>
                                    <td>{{ $menu->nombre }}</td>
                                    <td>{{ $menu->seccion->titulo }}</td>
                                    <td>

                                        <form action="{{ route('menus.destroy', $menu->id) }}" method="POST"
                                            id="delete-form-{{ $menu->id }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmarEliminacion('delete-form-{{ $menu->id }}', '¿Estás seguro de eliminar este menú?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="d-flex justify-content-center">
                    {{ $menus->links('pagination::bootstrap-4') }} <!-- Enlaces de paginación para los menús -->
                </div>
            @endif
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Verifica si hay errores de validación
            let hasErrors = @json($errors->any());

            if (hasErrors) {

                document.getElementById('btn-crea-menu').click();


            }

        });
    </script>


@endsection