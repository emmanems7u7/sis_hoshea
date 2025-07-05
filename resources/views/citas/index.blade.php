@extends('layouts.argon')

@section('content')
    <div class="row">
    <!-- Columna de botones -->
    <div class="col-md-5 shadow-sm mb-2">
        <div class="card mb-3">
            <div class="card-body">
                <h5>Módulo de Citas</h5>
                <a href="{{ route('citas.create') }}" class="btn btn-primary mb-1">Crear Cita</a>
                {{-- Puedes añadir más botones si es necesario --}}
            </div>
        </div>
    </div>

    <!-- Columna de descripción -->
    <div class="col-md-7 shadow-sm mb-2">
        <div class="card">
            <div class="card-body">
                <h5>Información sobre Citas</h5>
                <small>- En esta sección puedes gestionar las citas agendadas para tus pacientes.</small><br>
                <small>- Utiliza el botón <strong>"Crear Cita"</strong> para programar una nueva.</small><br>
                <small>- Cada cita puede tener varios usuarios asignados (como médicos o asistentes), y está asociada a un paciente y un tratamiento.</small><br>
                <small>- Puedes editar o eliminar citas según corresponda desde el listado inferior.</small>
            </div>
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
                            <th>Tratamiento</th>
                            <th>Fecha y Hora</th>
                            <th>Duración (min)</th>
                            <th>Estado</th>
                            <th>Usuarios asignados</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($citas as $cita)
                            <tr>
                                <td>{{ $cita->id }}</td>
                                <td>{{ $cita->paciente->nombres }} {{ $cita->paciente->apellidos }}</td>
                                <td>{{ $cita->tratamiento ? $cita->tratamiento->nombre : '-' }}</td>
                                <td>{{ $cita->fecha_hora->format('Y-m-d H:i') }}</td>
                                <td>{{ $cita->duracion ?? '-' }}</td>
                                <td>{{ ucfirst($cita->estado) }}</td>
                                <td>
                                    @foreach($cita->usuarios as $usuario)
                                        <span class="badge bg-secondary">{{ $usuario->name }}
                                            ({{ $usuario->pivot->rol_en_cita ?? 'N/A' }})</span><br>
                                    @endforeach
                                </td>
                                <td>
                                    <a href="{{ route('citas.edit', $cita) }}" class="btn btn-sm btn-primary">Editar</a>

                                    <a type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmarEliminacion('eliminarCitaForm{{ $cita->id }}', '¿Seguro que deseas eliminar esta cita?')">
                                        Eliminar
                                    </a>
                                    <form id="eliminarCitaForm{{ $cita->id }}" method="POST"
                                        action="{{ route('citas.destroy', $cita) }}" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $citas->links() }}
        </div>
    </div>

@endsection