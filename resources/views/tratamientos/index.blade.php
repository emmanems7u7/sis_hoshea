@extends('layouts.argon')

@section('content')


    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Tratamientos</h1>
                <a href="{{ route('tratamientos.create') }}" class="btn btn-primary">Crear Tratamiento</a>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Nombre</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tratamientos as $tratamiento)
                            <tr>
                                <td>{{ $tratamiento->id }}</td>
                                <td>{{ $tratamiento->paciente->nombres }} {{ $tratamiento->paciente->apellidos }}</td>
                                <td>{{ $tratamiento->nombre }}</td>
                                <td>{{ $tratamiento->fecha_inicio->format('Y-m-d') }}</td>
                                <td>{{ $tratamiento->fecha_fin ? $tratamiento->fecha_fin->format('Y-m-d') : '-' }}</td>
                                <td>{{ ucfirst($tratamiento->estado) }}</td>
                                <td>
                                    <a href="{{ route('tratamientos.edit', $tratamiento) }}"
                                        class="btn btn-sm btn-primary">Editar</a>

                                    <a type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmarEliminacion('eliminarTratamientoForm{{ $tratamiento->id }}', '¿Seguro que deseas eliminar este tratamiento?')">
                                        Eliminar
                                    </a>
                                    <form id="eliminarTratamientoForm{{ $tratamiento->id }}" method="POST"
                                        action="{{ route('tratamientos.destroy', $tratamiento) }}" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $tratamientos->links('pagination::bootstrap-4') }}
        </div>
    </div>

@endsection