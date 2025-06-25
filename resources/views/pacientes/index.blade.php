@extends('layouts.argon')

@section('content')

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Listado de Pacientes</h1>
                <a href="{{ route('pacientes.create') }}" class="btn btn-primary">Nuevo Paciente</a>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">

            @if($pacientes->count())
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
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

                <div class="mt-3">
                    {{ $pacientes->links() }}
                </div>
            @else
                <p class="text-muted">No hay pacientes registrados.</p>
            @endif
        </div>
    </div>

@endsection