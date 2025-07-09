@extends('layouts.argon')

@section('content')

    @php

        $estados = [
            'pendiente' => 'Pendiente',
            'confirmada' => 'Confirmada',
            'cancelada' => 'Cancelada',
            'completada' => 'Completada'
        ];
        $hoy = \Illuminate\Support\Carbon::today();
    @endphp
    <div class="row">
        <div class="col-md-5 ">
            <div class="card mb-3 shadow-sm mb-2">
                <div class="card-body">
                    <h5 class="text-green">Modulo de Tratamientos</h5>
                    <a href="{{ route('tratamientos.create') }}" class="btn btn-primary">Crear tratamiento</a>
                    <a href="{{ route('tratamientos.exportPDF') }}" class="btn btn-danger" target="_blank">Exportar en
                        PDF</a>


                    <a href="{{ route('tratamientos.actuales') }}" class="btn btn-primary">Actuales</a>


                </div>
            </div>
        </div>
        <div class="col-md-7 ">
            <div class="card shadow-sm mb-2 ">
                <div class="card-body  text-black">
                    <h5 class="">Información de Tratamientos</h5>
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

    <div class="card mb-3 ">
        <div class="card-body ">
            <h5 class="mb-3 text-black">Filtrar tratamientos</h5>


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
                <div class="card mb-4 shadow-sm ">
                    <div class="card-header  text-black d-flex justify-content-between align-items-center rounded-top">
                        <div>
                            <h4 class="mb-0 fw-bold text-black">Tratamiento: {{ $tratamiento->nombre }}</h4>
                            <small>Paciente: <strong>{{ $tratamiento->paciente->nombre_completo }}
                                </strong></small>
                        </div>

                    </div>

                    <div class="card-body  rounded-bottom  text-black">
                        <div class="mb-2">
                            <span
                                class="me-3
                                                                                                                                            {{ $tratamiento->fecha_inicio->isSameDay($hoy) ? 'text-warning rounded px-2' : '' }}">
                                <strong>Fecha Inicio:</strong> {{ $tratamiento->fecha_inicio->format('Y-m-d') }}
                            </span>

                            <span
                                class="me-3
                                                                                                                                            {{ $tratamiento->fecha_fin && $tratamiento->fecha_fin->isSameDay($hoy) ? 'text-warning rounded px-2' : '' }}">
                                <strong>Fecha Fin:</strong>
                                {{ $tratamiento->fecha_fin ? $tratamiento->fecha_fin->format('Y-m-d') : '-' }}
                            </span>
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

                        @include('tratamientos.tarjetas_citas', ['botones' => false])
                    </div>

                    <div class="card-footer">
                        <div>
                            <a href="{{ route('tratamientos.administrar', $tratamiento) }}"
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
                </div>
            </div>
        @endforeach


    </div>

    <div class="mt-4">
        {{ $tratamientos->links('pagination::bootstrap-4') }}
    </div>

    <div class="modal fade" id="estadoModal" tabindex="-1" aria-hidden="true" id="estadoModal" tabindex="-1"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formEstado" method="POST" action="{{ route('citas.cambiarEstado') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="cita_id" id="cita_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cambiar estado de la cita</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nuevo_estado" class="form-label">Estado</label>
                            <select class="form-select" name="nuevo_estado" id="nuevo_estado" required>
                                <option value="-1" selected disabled>--Seleccione </option>

                                @foreach($estados as $valor => $texto)
                                    <option value="{{ $valor }}">{{ $texto }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="notificar" name="notificar" value="1">
                            <label class="form-check-label" for="notificar">
                                Enviar notificación al paciente
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>

        document.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;
            const modal = event.target;

            modal.querySelector('#cita_id').value = trigger.dataset.cita;
            modal.querySelector('#nuevo_estado').value = trigger.dataset.estado;
        });

    </script>

@endsection