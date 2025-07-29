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


                <a href="{{ route('tratamientos.index', ['anteriores' => 1]) }}" class="btn btn-sm btn-primary">
                    Tratamientos anteriores
                </a>
                <a href="{{ route('tratamientos.index', ['estado' => 'activo']) }}" class="btn btn-sm btn-primary ">
                    Activos
                </a>

                <a href="{{ route('tratamientos.index', ['estado' => 'pendiente']) }}" class="btn btn-sm btn-warning">
                    Pendientes
                </a>

                <a href="{{ route('tratamientos.index', ['estado' => 'finalizado']) }}" class="btn btn-sm btn-secondary">
                    Finalizados
                </a>

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


                    <div class="card-body  rounded-bottom  text-black">
                        <div>
                            <h4 class="mb-0 fw-bold text-black">Tratamiento: {{ $tratamiento->nombre }}</h4>
                            <small>Paciente: <strong>{{ $tratamiento->paciente->nombre_completo }}
                                </strong></small>
                        </div>
                        <div class="mb-2">
                            <span
                                class="me-3
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            {{ $tratamiento->fecha_inicio->isSameDay($hoy) ? 'text-warning rounded px-2' : '' }}">
                                <strong>Fecha Inicio:</strong> {{ $tratamiento->fecha_inicio->format('d-m-Y') }}
                            </span>

                            <span
                                class="me-3
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            {{ $tratamiento->fecha_fin && $tratamiento->fecha_fin->isSameDay($hoy) ? 'text-warning rounded px-2' : '' }}">
                                <strong>Fecha Fin:</strong>
                                {{ $tratamiento->fecha_fin ? $tratamiento->fecha_fin->format('d-m-Y') : '-' }}
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
                            <span id="observacion_{{ $tratamiento->id }}">observaciónes:
                                {{ $tratamiento->observaciones }}</span>
                        </div>

                        @include('tratamientos.tarjetas_citas', ['botones' => false])
                    </div>

                    <div class="card-footer">
                        <div>
                            <a href="{{ route('tratamientos.finalizar', $tratamiento) }}" class="btn-primary btn btn-sm"
                                onclick="confirmarFinalizacion(event, this)">Finalizar Gestión</a>


                            <a href="#" class="btn-dark btn btn-sm btn-abrir-modal" data-id="{{ $tratamiento->id }}"
                                data-observaciones="{{ e($tratamiento->observaciones) }}">
                                Editar observación
                            </a>


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

        <div class="modal fade" id="modalObservacion" tabindex="-1" aria-labelledby="modalObservacionLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form id="formObservacion">
                    @csrf
                    <input type="hidden" name="tratamiento_id" id="tratamiento_id">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalObservacionLabel">Editar Observación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <textarea name="observaciones" id="observaciones" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

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
                <div
                    class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
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
    <script>document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('estadoModal');
            const modal = new bootstrap.Modal(modalEl);

            document.querySelectorAll('.btn-abrir-modal_estado').forEach(span => {
                span.addEventListener('click', function () {
                    const fechaHoraStr = this.dataset.fechaHora; // formato: "YYYY-MM-DD HH:mm:ss"
                    const citaFechaHora = new Date(fechaHoraStr.replace(' ', 'T')); // convertir a formato ISO

                    const ahora = new Date();
                    const diffMs = citaFechaHora - ahora; // diferencia en ms
                    const diffHoras = diffMs / (1000 * 60 * 60); // convertir a horas

                    if (diffHoras >= 24) {
                        // Abre el modal
                        modal.show();
                        document.getElementById('cita_id').value = this.dataset.cita;;
                        modalEl.querySelector('#inputCitaId').value = this.dataset.cita;
                        modalEl.querySelector('#inputEstado').value = this.dataset.estado;
                    } else {

                        alertify.error('La cita debe ser con al menos 24 horas de anticipación para modificar el estado.');
                    }
                });
            });
        });</script>
    <script>



        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(document.getElementById('modalObservacion'));
            const form = document.getElementById('formObservacion');

            document.querySelectorAll('.btn-abrir-modal').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    const tratamientoId = this.dataset.id;
                    const observaciones = this.dataset.observaciones || '';

                    document.getElementById('tratamiento_id').value = tratamientoId;
                    document.getElementById('observaciones').value = observaciones;

                    modal.show();
                });
            });

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const tratamientoId = document.getElementById('tratamiento_id').value;
                const observaciones = document.getElementById('observaciones').value;
                const token = document.querySelector('input[name="_token"]').value;


                const urlGuardarObservacion = "{{ route('tratamientos.guardarObservacion', ['tratamiento' => ':id']) }}";
                const url = urlGuardarObservacion.replace(':id', tratamientoId);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ observaciones }),
                })
                    .then(response => {
                        if (!response.ok) throw new Error('Error al guardar');
                        return response.json();
                    })
                    .then(data => {
                        alertify.success('Observación guardada correctamente');
                        modal.hide();


                        const btn = document.querySelector(`.btn-abrir-modal[data-id="${tratamientoId}"]`);

                        var observacion = document.getElementById('observacion_' + tratamientoId);
                        if (observacion) {
                            observacion.textContent = observaciones;
                        }


                        if (btn) btn.dataset.observaciones = observaciones;
                    })
                    .catch(() => alert('Error al guardar la observación'));
            });
        });


        document.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;
            const modal = event.target;

            modal.querySelector('#cita_id').value = trigger.dataset.cita;
            modal.querySelector('#nuevo_estado').value = trigger.dataset.estado;
        });


        function confirmarFinalizacion(event, element) {
            event.preventDefault();

            alertify.confirm('¿Estás seguro?', 'Esta acción finalizará la gestión del tratamiento.',
                function () {
                    window.location.href = element.href;
                },
                function () {

                }
            ).set('labels', { cancel: 'Cancelar', ok: 'Sí, finalizar' });
        }

    </script>

@endsection