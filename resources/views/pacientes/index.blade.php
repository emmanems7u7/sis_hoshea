@extends('layouts.argon')

@section('content')


    <div class="row">
        <!-- Columna de botones -->
        <div class="col-md-5 shadow-sm mb-2">
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Módulo de Pacientes</h5>
                    <a href="{{ route('pacientes.create') }}" class="btn btn-primary mb-1">Nuevo Paciente</a>
                </div>
            </div>
        </div>

        <!-- Columna de descripción -->
        <div class="col-md-7 shadow-sm mb-2">
            <div class="card">
                <div class="card-body">
                    <h5>Información sobre Pacientes</h5>
                    <small>- En este módulo puedes gestionar la información de los pacientes registrados en el
                        sistema.</small><br>
                    <small>- Puedes registrar nuevos pacientes con el botón <strong>"Nuevo Paciente"</strong>.</small><br>
                    <small>- Desde el listado puedes <strong>ver, editar o eliminar</strong> los datos de un paciente
                        fácilmente.</small><br>
                    <small>- El estado <strong>"Activo"</strong> indica que el paciente puede tener citas o tratamientos
                        activos.</small>
                </div>
            </div>
        </div>
    </div>




    <div class="card mt-3">
        <div class="card-body">

            @if($pacientes->count())
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-dark bg-green">
                            <tr>
                                <th>Nombre Completo</th>
                                <th>Documento</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pacientes as $paciente)
                                <tr>
                                    <td>
                                        {{ $paciente->nombres }} {{ $paciente->apellido_paterno }} {{ $paciente->apellido_materno }}
                                    </td>
                                    <td>{{ $paciente->numero_documento }}</td>
                                    <td>{{ $paciente->telefono_movil ?? $paciente->telefono_fijo }}</td>
                                    <td>{{ $paciente->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $paciente->activo ? 'success' : 'secondary' }}">
                                            {{ $paciente->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('pacientes.show', $paciente) }}" class="btn btn-sm btn-info">Ver</a>
                                        <a href="{{ route('pacientes.edit', $paciente) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('¿Desea eliminar este paciente?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $pacientes->links('pagination::bootstrap-4') }}
                </div>
            @else
                <p class="text-muted">No hay pacientes registrados.</p>
            @endif
        </div>
    </div>

@endsection