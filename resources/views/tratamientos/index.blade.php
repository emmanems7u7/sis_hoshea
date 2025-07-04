@extends('layouts.argon')

@section('content')

    <div class="row">
        <div class="col-md-5 shadow-sm mb-2">
            <div class="card mb-3 bg-green_tarjetas ">
                <div class="card-body">
                    <h5 class="text-green">Modulo de Tratamientos</h5>
                    <a href="{{ route('tratamientos.create') }}" class="btn btn-primary">Crear tratamiento</a>
                    <a href="{{ route('tratamientos.exportPDF') }}" class="btn btn-danger" target="_blank">Exportar en
                        PDF</a>


                    <a href="{{ route('tratamientos.actuales') }}" class="btn btn-primary">Actuales</a>


                </div>
            </div>
        </div>
        <div class="col-md-7 shadow-sm mb-2">
            <div class="card bg-green_tarjetas ">
                <div class="card-body text-green">
                    <h5 class="text-green">Información de Tratamientos</h5>
                    <small>- En esta sección puedes gestionar los tratamientos de los pacientes. Puedes crear, editar y
                        eliminar
                        tratamientos, así como ver las citas asociadas a cada tratamiento.</small>
                    <br>
                    <small>- Utiliza el botón <strong>"Crear tratamiento"</strong> para iniciar un nuevo tratamiento y el
                        botón <strong>"Administrar"</strong>
                        para
                        gestionar citas y detalles específicos de cada tratamiento.</small>
                    <br>
                    <small>- Filtra tambien de acuerdo a tu necesidad con los botones o el buscador</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3 bg-green_tarjetas">
        <div class="card-body ">
            <h5 class="mb-3 text-green">Filtrar tratamientos</h5>


            <div class="d-flex flex-wrap align-items-end gap-2">


                <a href="{{ route('tratamientos.index', ['anteriores' => 1]) }}" class="btn btn-primary">
                    Ver tratamientos anteriores
                </a>
                <a href="{{ route('tratamientos.index') }}" class="btn btn-primary">Ver tratamientos activos</a>

                <form action="{{ route('tratamientos.index') }}" method="GET" class="d-flex gap-2 ms-auto">

                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por tratamiento o paciente…"
                        class="form-control" />

                    <button type="submit" class="btn btn-primary">
                        Buscar
                    </button>
                </form>

            </div>
        </div>


    </div>
    <div class="row">

        @foreach($tratamientos as $tratamiento)
            <div class="col-md-6 tratamiento-item">
                <div class="card mb-4  bg-green_tarjetas">
                    <div
                        class="card-header bg-green_tarjetas text-green d-flex justify-content-between align-items-center rounded-top">
                        <div>
                            <h4 class="mb-0 fw-bold text-green">Tratamiento: {{ $tratamiento->nombre }}</h4>
                            <small>Paciente: <strong>{{ $tratamiento->paciente->nombres }}
                                    {{ $tratamiento->paciente->apellidos }}</strong></small>
                        </div>
                        <div>
                            <a href="{{ route('tratamientos.edit', $tratamiento) }}"
                                class="btn btn-sm btn-dark me-2">Administrar</a>
                            <a href="{{ route('tratamientos.edit', $tratamiento) }}"
                                class="btn btn-sm btn-warning me-2">Editar</a>
                            <button class="btn btn-sm btn-danger fw-bold"
                                onclick="confirmarEliminacion('eliminarTratamientoForm{{ $tratamiento->id }}', '¿Seguro que deseas eliminar este tratamiento?')">
                                Eliminar
                            </button>
                            <form id="eliminarTratamientoForm{{ $tratamiento->id }}" method="POST"
                                action="{{ route('tratamientos.destroy', $tratamiento) }}" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>

                    <div class="card-body  rounded-bottom text-green">
                        <div class="mb-2">
                            <span class="me-3"><strong>Fecha Inicio:</strong>
                                {{ $tratamiento->fecha_inicio->format('Y-m-d') }}</span>
                            <span class="me-3"><strong>Fecha Fin:</strong>
                                {{ $tratamiento->fecha_fin ? $tratamiento->fecha_fin->format('Y-m-d') : '-' }}</span>
                            <span><strong>Estado:</strong>
                                @php
                                    $estadoColor = match ($tratamiento->estado) {
                                        'activo' => 'success',
                                        'finalizado' => 'secondary',
                                        'cancelado' => 'danger',
                                        default => 'dark'
                                    };
                                @endphp
                                <span class="badge bg-{{ $estadoColor }}">{{ ucfirst($tratamiento->estado) }}</span>
                            </span>
                        </div>

                        @if($tratamiento->citas->count())
                            <div class="mt-3">
                                <h6 class="text-green">Citas Asociadas:</h6>
                                <div class="row text-green">
                                    @foreach($tratamiento->citas as $cita)
                                        <div class="col-md-12 mb-3">
                                            <div class="border bg-green_tarjetas_claro  rounded p-3 bg-light position-relative">

                                                @php
                                                    $estadoClass = match ($cita->estado) {
                                                        'pendiente' => 'bg-warning text-white',
                                                        'confirmada' => 'bg-primary',
                                                        'cancelada' => 'bg-danger',
                                                        'completada' => 'bg-success',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp

                                                <span class="badge {{ $estadoClass }} position-absolute top-0 end-0 m-2">
                                                    {{ ucfirst($cita->estado) }}
                                                </span>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <small class="mb-1"><strong>Fecha y hora:</strong>
                                                            {{ $cita->fecha_hora->format('Y-m-d H:i') }}</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="mb-1"><strong>Duración:</strong>
                                                            {{ $cita->duracion ?? '-' }} min.</small>

                                                    </div>
                                                </div>



                                                <small class="mb-1"><strong>Personal asignado a la cita:</strong></small>
                                                <div class="row mb-1">
                                                    @foreach($cita->usuarios as $usuario)
                                                        <div class="col-md-3">
                                                            <span class="badge bg-secondary me-1">{{ $usuario->name }}
                                                                ({{ $usuario->pivot->rol_en_cita ?? 'N/A' }})</span>
                                                        </div>
                                                    @endforeach

                                                </div>

                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        @else
                            <p class="text-green">No hay citas asociadas.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach


    </div>

    <div class="mt-4">
        {{ $tratamientos->links('pagination::bootstrap-4') }}
    </div>

@endsection