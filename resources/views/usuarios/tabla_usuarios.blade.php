<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-uppercase text-xxs font-weight-bolder opacity-7">Usuario</th>
            <th class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2">Nombres</th>
            <th class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2">Rol</th>

            <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">
                Telefono</th>
            <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Ultimo
                acceso
            </th>

            <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">
                Acciones
            </th>

        </tr>
    </thead>
    <tbody>

        @foreach ($users as $usuario)
            <tr>
                <td>
                    <div class="d-flex px-2 py-1">
                        <div>
                            <img src="{{ asset($usuario->foto_perfil)}}" class="avatar avatar-sm me-3">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-xs">{{ $usuario->name }}
                            </h6>
                            <p class="text-xs mb-0">{{ $usuario->email }}</p>
                        </div>
                    </div>
                </td>

                <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $usuario->usuario_nombres }}
                        {{ $usuario->usuario_app }}
                        {{ $usuario->usuario_apm }}
                    </p>
                </td>
                <td>
                    <p class="text-xs font-weight-bold mb-0 ">
                        {{ $usuario->getRoleNames()->first() ?? 'Sin Rol Asignado' }}
                    </p>

                </td>
                <td class="align-middle text-center text-sm">
                    <p class="text-xs font-weight-bold mb-0">{{ $usuario->usuario_telefono }}

                    </p>
                </td>

                <td class="align-middle text-center">
                    <span class="text-black text-xs font-weight-bold">{{ $usuario->usuario_fecha_ultimo_acceso }}</span>
                </td>


                <td class="align-middle">
                    @can('usuarios.editar')
                        <a href="{{ route('users.edit', ['id' => $usuario->id]) }}" class="btn btn-sm btn-warning"
                            id="modal_edit_usuario_button">Editar
                            Usuario</a>
                    @endcan
                    @can('usuarios.eliminar')
                        <a type="button" class="btn btn-sm btn-danger" id="modal_edit_usuario_button"
                            onclick="confirmarEliminacion('eliminarUsuarioForm', '¿Estás seguro de que deseas eliminar este usuario?')">Eliminar
                            Usuario</a>

                        <form id="eliminarUsuarioForm" method="POST"
                            action="{{ route('users.destroy', ['user' => $usuario->id]) }}" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endcan
                </td>

            </tr>
        @endforeach

    </tbody>
</table>