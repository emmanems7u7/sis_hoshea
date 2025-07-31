@extends('layouts.argon')

@section('content')
    <style>
        .fade-in {
            opacity: 1;
            transition: opacity 0.4s ease-in;
        }

        .fade-out {
            opacity: 0;
            transition: opacity 0.4s ease-out;
        }

        .fade-element {
            opacity: 0;
        }
    </style>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card bg-green_tarjetas_claro">
                <div class="card-body">
                    Gestión de cita
                    <div class="row">
                        <div class="col-md-12">
                            <span class="text-info">{{ $cita->primera_cita == 1 ? 'Primera Cita' : '' }}</span>
                        </div>
                    </div>
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
                    <div class=" flex-wrap gap-1 mb-1">
                        @foreach ($cita->usuarios as $usuario)
                            <span class="badge bg-secondary">
                                {{ $usuario->name }} ({{ $usuario->pivot->rol_en_cita ?? 'N/A' }})
                            </span>
                        @endforeach
                    </div>



                </div>
            </div>
        </div>

        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-body py-2">
                    @include('tratamientos.datos_paciente')
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-2">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div data-cita-id="{{ $cita->id }}" class="contador-tiempo-restante text-danger fw-bold mb-2"
                        data-updated-at="{{ $cita->fecha_gestion?->timestamp ? $cita->fecha_gestion->timestamp * 1000 : '' }}"
                        data-created-at="{{ $cita->created_at->timestamp * 1000 }}">
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <span class="fw-bold">Opciones disponibles</span>
                            @php
                                $fechaCita = \Carbon\Carbon::parse($cita->fecha_hora)->toDateString();
                                $fechaHoy = \Carbon\Carbon::today()->toDateString();
                                $habilitado = ($fechaCita === $fechaHoy && $cita->gestionado != 1);
                            @endphp

                            <div class="row g-2 mt-2 text-center">

                                @if ($fechaCita === $fechaHoy)
                                    <div class="col-12">
                                        <a href="{{ route('servicios.asignar', $cita) }}"
                                            class="btn btn-sm btn-dark w-100 flex-column align-items-center py-3">
                                            <i class="fas fa-stethoscope fa-2x mb-2"></i>
                                            Agregar Servicios y medicamentos suministrados
                                        </a>
                                    </div>
                                @endif

                                <div class="col-12">
                                    <a href="{{ route('servicios.show', $cita) }}"
                                        class="btn btn-sm btn-dark w-100  flex-column align-items-center py-3">
                                        <i class="fas fa-eye fa-2x mb-2"></i>
                                        Ver Servicios y medicamentos suministrados
                                    </a>
                                </div>


                                <div class="col-12">
                                    <a href="#"
                                        class="btn btn-sm btn-warning btn-gestionar w-100  flex-column align-items-center py-3"
                                        @if($habilitado) data-cita-id="{{ $cita->id }}"
                                        data-gestionado="{{ $cita->gestionado }}" @endif {{ !$habilitado ? 'disabled' : '' }}>
                                        <i class="fas fa-tasks fa-2x mb-2"></i>
                                        Gestionar Cita
                                    </a>
                                </div>




                                @if($cita->gestionado == 1)

                                    <div class="col-12">
                                        <a href="#" id="btn-editar_{{ $cita->id }}"
                                            class="btn-editar-gestion btn btn-sm btn-warning w-100  flex-column align-items-center py-3"
                                            data-cita-id="{{ $cita->id }}">
                                            <i class="fas fa-edit fa-2x mb-2"></i>
                                            Editar Gestión
                                        </a>
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-sm btn-info w-100  flex-column align-items-center py-3"
                                            onclick="verGestion({{ $cita->id }})">
                                            <i class="fas fa-list-alt fa-2x mb-2"></i>
                                            Resumen de Gestión
                                        </button>
                                    </div>

                                    @if($cita->examenes->isNotEmpty())
                                        <div class="col-12">
                                            <button class="btn btn-sm btn-warning w-100  flex-column align-items-center py-3"
                                                onclick="EditarHoja({{ $cita->id }})">
                                                <i class="fas fa-vials fa-2x mb-2"></i>
                                                Editar Hoja Laboratorio
                                            </button>
                                        </div>
                                    @else
                                        <div class="col-12">
                                            <button class="btn btn-sm btn-primary w-100  flex-column align-items-center py-3"
                                                onclick="CrearHoja({{ $cita->id }})">
                                                <i class="fas fa-file-medical fa-2x mb-2"></i>
                                                Crear Hoja Laboratorio
                                            </button>
                                        </div>
                                    @endif
                                    <span class="fw-bold">Opciones para exportar</span>
                                    <div class="col-12">
                                        <a target="_blank" href="{{ route('citas.export_gestion', $cita) }}"
                                            class="btn btn-sm btn-danger w-100  flex-column align-items-center py-3">
                                            <i class="fas fa-file-pdf fa-2x mb-2"></i>
                                            Hoja Evolución
                                        </a>
                                    </div>

                                    @if($cita->examenes)
                                        <div class="col-12">
                                            <a target="_blank" href="{{ route('citas.export_hoja', $cita) }}"
                                                class="btn btn-sm btn-danger w-100  flex-column align-items-center py-3">
                                                <i class="fas fa-flask fa-2x mb-2"></i>
                                                Hoja Laboratorio
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h6>Administración</h6>

                    @yield('contenido_cita')

                </div>
            </div>
        </div>
    </div>


    @unlessrole('admin')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const contadores = document.querySelectorAll('.contador-tiempo-restante');

            contadores.forEach(contador => {
                const updatedAtTimestamp = Number(contador.dataset.updatedAt);
                const createdAtTimestamp = Number(contador.dataset.createdAt);

                if (updatedAtTimestamp <= createdAtTimestamp) {
                    contador.style.display = 'none';

                    const citaId = contador.closest('[data-cita-id]')?.dataset.citaId || null;
                    const btnEditar = document.getElementById('btn-editar_' + citaId);

                    if (btnEditar) {
                        btnEditar.disabled = true;
                        btnEditar.classList.add('disabled');
                    }

                    return;
                }

                const updatedAt = new Date(updatedAtTimestamp);
                const limiteEdicion = new Date(updatedAt.getTime() + 5 * 60 * 1000);

                const citaId = contador.closest('[data-cita-id]')?.dataset.citaId || null;
                const btnEditar = document.querySelector(`.btn-editar-gestion[data-cita-id="${citaId}"]`);

                let timer;

                function actualizarContador() {
                    const ahora = new Date();
                    const diff = limiteEdicion - ahora;

                    if (diff <= 0) {
                        contador.textContent = "Tiempo de edición expirado.";
                        if (btnEditar) {
                            btnEditar.disabled = true;
                            btnEditar.classList.add('disabled');
                        }
                        clearInterval(timer);
                        return;
                    }

                    const minutos = Math.floor(diff / 60000);
                    const segundos = Math.floor((diff % 60000) / 1000);
                    contador.textContent = `Tiempo restante para editar: ${minutos}:${segundos.toString().padStart(2, '0')}`;
                }

                actualizarContador();
                timer = setInterval(actualizarContador, 1000);
            });
        });
    </script>
    @endunlessrole

    <script>
        document.querySelectorAll('.btn-gestionar').forEach(btn => {
            btn.addEventListener('click', e => {
                if (btn.hasAttribute('disabled')) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (btn.dataset.gestionado == '1') {
                        alertify.warning('Esta cita ya fue gestionada.');
                    } else {
                        alertify.warning('No puede gestionar esta cita en esta fecha.');
                    }
                }
            });
        });
    </script>
    <!-- Modal -->
    @include('tratamientos.modal_gestion')


    @include('tratamientos.modal_vista_gestion')

    @include('tratamientos.modal_hoja_lab')

    <script>
        function post_gestion(citaId) {
            document.getElementById('btn_accion').textContent = 'Guardar Gestión';
            const form = document.getElementById('form-gestion-cita');
            const baseRoute = @json(route('citas.gestion', ['cita' => 'ID_REEMPLAZO']));
            form.action = baseRoute.replace('ID_REEMPLAZO', citaId);

            let methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.remove();
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            // Botón "Gestionar"
            document.querySelectorAll('.btn-gestionar').forEach(boton => {
                boton.addEventListener('click', function () {
                    const citaId = this.dataset.citaId;
                    post_gestion(citaId);
                });
            });

            // Botones "Editar"
            const btnEditarList = document.querySelectorAll('.btn-editar-gestion');

            btnEditarList.forEach(btnEditar => {
                btnEditar.addEventListener('click', function (e) {
                    e.preventDefault();

                    const citaId = this.dataset.citaId;

                    if (citaId) {

                        cargarGestion(citaId);

                        const modalEl = document.getElementById('modal_gestion');
                        let modalInstance = bootstrap.Modal.getInstance(modalEl);

                        if (!modalInstance) {
                            modalInstance = new bootstrap.Modal(modalEl);
                        }
                        modalInstance.show();

                    }

                    // Cambia texto del botón de acción si existe
                    const btnAccion = document.getElementById('btn_accion');
                    if (btnAccion) {
                        btnAccion.textContent = 'Actualizar Gestión';
                    }

                    // Genera la ruta con citaId
                    const editarRouteTemplate = @json(route('citas.update_gestion', ['cita' => 'ID_REEMPLAZO']));
                    const editarRoute = editarRouteTemplate.replace('ID_REEMPLAZO', citaId);

                    // Actualiza el formulario
                    const form = document.getElementById('form-gestion-cita');
                    if (form) {
                        form.action = editarRoute;

                        // Crea o actualiza el input _method a PUT
                        let methodInput = form.querySelector('input[name="_method"]');
                        if (!methodInput) {
                            methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            form.appendChild(methodInput);
                        }
                        methodInput.value = 'PUT';
                    }
                });
            });
        });
    </script>


    <script>




        function cargarGestion(citaId) {
            const urlEditarGestionBase = "{{ route('citas.editar_gestion', ['cita' => '__ID__']) }}";
            const url = urlEditarGestionBase.replace('__ID__', citaId);

            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    // Asignar valores simples
                    const codDiagnosticoInput = document.getElementById('cod_diagnostico');
                    if (codDiagnosticoInput) {
                        codDiagnosticoInput.value = data.cod_diagnostico || '';
                        // Si usas Select2, dispara el evento change manualmente:
                        const event = new Event('change');
                        codDiagnosticoInput.dispatchEvent(event);
                    }

                    const criterioInput = document.getElementById('criterio_clinico');
                    if (criterioInput) criterioInput.value = data.criterio_clinico || '';

                    const evolucionInput = document.getElementById('evolucion_diagnostico');
                    if (evolucionInput) evolucionInput.value = data.evolucion_diagnostico || '';

                    // Asignar arrays a variables globales
                    datos = data.datos || [];
                    datosObjetivos = data.objetivos || [];
                    planes = data.planes || [];

                    // Renderizar con tus funciones existentes
                    renderizarDatos();
                    renderResumen();
                    renderPlanes();

                    // Mostrar modal edición
                    const modalEl = document.getElementById('modal_gestion');
                    let modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (!modalInstance) {
                        modalInstance = new bootstrap.Modal(modalEl);
                    }
                    modalInstance.show();

                    // Opcional: reiniciar validación o plugins si aplica
                })
                .catch(() => {
                    alertify.error('Error cargando datos de la cita.');
                });
        }

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-gestionar').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();

                    const citaId = btn.getAttribute('data-cita-id');
                    if (!citaId) return;

                    const modalEl = document.getElementById('modal_gestion');
                    let modalInstance = bootstrap.Modal.getInstance(modalEl);

                    if (!modalInstance) {
                        modalInstance = new bootstrap.Modal(modalEl);
                    }
                    modalInstance.show();
                });
            });
        });

        document.getElementById('btnCerrarModal').addEventListener('click', function () {
            alertify.confirm(
                'Confirmación',
                '¿Estás seguro? <br> <strong>Los cambios no se guardarán.</strong>',
                function () {
                    datos = [];
                    datosObjetivos = [];
                    planes = [];

                    renderizarDatos();
                    renderResumen();
                    renderPlanes();

                    // Cerrar modal con Bootstrap 5 JS puro
                    const modalEl = document.getElementById('modal_gestion');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                },
                function () {
                    // Cancelado: no hacer nada
                }
            ).set('labels', { ok: 'Sí, cerrar', cancel: 'Cancelar' });
        });

    </script>



@endsection