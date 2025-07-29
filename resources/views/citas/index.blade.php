@extends('layouts.argon')

@section('content')
    <div class="row">
        <!-- Columna de botones -->
        <div class="col-md-5 ">
            <div class="card mb-3 shadow-sm mb-2">
                <div class="card-body">
                    <h5>Módulo de Citas</h5>
                    <a href="{{ route('citas.create') }}" class="btn btn-primary ">Crear Cita</a>
                    <a href="{{ route('citas.index', ['ver_todos' => 1]) }}" class="btn btn-info">Ver Todos</a>
                    <a href="{{ route('citas.index', ['ver_todos' => 0]) }}" class="btn btn-dark">Ver Citas sin
                        tratamiento</a>
                </div>
            </div>
        </div>

        <!-- Columna de descripción -->
        <div class="col-md-7 ">
            <div class="card shadow-sm mb-2">
                <div class="card-body">
                    <h5>Información sobre Citas</h5>
                    <small>- En esta sección puedes gestionar las citas agendadas para tus pacientes.</small><br>
                    <small>- Utiliza el botón <strong>"Crear Cita"</strong> para programar una nueva.</small><br>
                    <small>- Cada cita puede tener varios usuarios asignados (como médicos o asistentes), y está asociada a
                        un paciente y un tratamiento.</small><br>
                    <small>- Puedes editar o eliminar citas según corresponda desde el listado inferior.</small>
                    <br> <small>- Recuerda que en este apartado verás solamente el <strong>listado de Citas que no estan
                            asociados a
                            un
                            tratamiento</strong> Si deseas ver el listado completo puedes verlo presionando el botón
                        <strong> Ver todos
                        </strong> </small>

                </div>
            </div>
        </div>
    </div>


    <div class="card mt-3">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <form method="GET" action="{{ route('citas.index') }}" class="d-flex" style="gap: 8px;">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar paciente..."
                        value="{{ request('buscar') }}" style="max-width: 250px;">

                    <button type="submit" class="btn btn-primary">Buscar</button>


                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nº</th>
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
                        @foreach($citas as $index => $cita)
                            <tr>
                                <td>{{ $index + 1 }}</td>
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



                                    <a href="{{ route('tratamientos.gestion_cita', ['cita' => $cita, 'tipo' => 2]) }}"
                                        class="btn btn-sm btn-dark mt-1">
                                        <i class="fas fa-pencil-alt me-1"></i> Empezar Gestión
                                    </a>

                                    <a href="{{ route('citas.edit', $cita) }}" class="btn btn-sm btn-warning">Editar</a>

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