@extends('layouts.argon')

@section('content')
    <div class="row">
        <!-- Columna de botones -->
        <div class="col-md-5">
            <div class="card mb-3  shadow-sm mb-2">
                <div class="card-body">
                    <h5>Módulo de Roles</h5>
                    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-1">Crear Nuevo Rol</a>
                </div>
            </div>
        </div>

        <!-- Columna de descripción -->
        <div class="col-md-7 ">
            <div class="card shadow-sm mb-2">
                <div class="card-body">
                    <h5>Información sobre Roles</h5>
                    <small>- En esta sección puedes gestionar los <strong>roles del sistema</strong>, cada uno con sus
                        respectivos permisos.</small><br>
                    <small>- Los roles determinan qué acciones puede realizar un usuario dentro de la
                        plataforma.</small><br>
                    <small>- Puedes asignar múltiples permisos a un rol, editar sus configuraciones o eliminarlo si ya no es
                        necesario.</small><br>
                    <small>- Para agregar un nuevo rol, utiliza el botón <strong>"Crear Nuevo Rol"</strong> y selecciona los
                        permisos adecuados.</small><br>
                    <small class="text-danger">- Se recomienda <strong>no eliminar ni modificar el nombre de los roles ya
                            existentes</strong>, ya que estan asociados a funciones críticas del sistema.</small>

                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        @foreach($roles as $role)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border  text-black">
                    <div class="card-body">
                        <h5 class="card-title text-black">
                            <i class="fas fa-user-tag me-2"></i> {{ $role->name }}
                        </h5>

                        <p class="mb-2 text-black"><strong>Permisos:</strong></p>
                        <div style="max-height: 122px; overflow-y: auto; padding-right: 10px;">
                            @foreach($role->permissions as $permission)
                                <span class="badge bg-dark me-1 mb-1">{{ $permission->name }}</span>
                            @endforeach
                        </div>


                    </div>
                    <div class="card-footer">


                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;"
                            id="delete-form-{{ $role->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="confirmarEliminacion('delete-form-{{ $role->id }}' , '¿Estás seguro de eliminar este rol?')">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </form>
                        <a href="{{ route('roles.edit', ['id' => $role->id]) }}" class="btn btn-sm btn-warning me-2">
                            <i class="fas fa-edit"></i> Editar
                        </a>

                    </div>
                </div>
            </div>
        @endforeach
    </div>


    <script>
        /*
        function confirmDelete(roleId) {
            alertify.confirm(
                'Confirmar Eliminación',
                '¿Estás seguro de eliminar este rol?',
                function () {

                    document.getElementById('delete-form-' + roleId).submit();
                },
                function () {

                    alertify.error('Eliminación cancelada');
                }
            ).set('labels', { ok: 'Eliminar', cancel: 'Cancelar' }); // Opcional: Cambia los textos de los botones
        }*/
    </script>

@endsection