@php
    use Carbon\Carbon;

@endphp
@if($tratamiento->citas->count())
    <div class="mt-3">
        <h6 class="text-green">Citas Asociadas:</h6>
        <div class="row text-black">
            @foreach($tratamiento->citas as $cita)
                <div class="col-md-12 mb-3">

                    <div class="border bg-green_tarjetas_claro  rounded p-3 bg-light position-relative">
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


                        @if($botones)
                            <span class="badge {{ $estadoClass }} position-absolute top-0 end-0 m-2">
                                {{ ucfirst($cita->estado) }}
                            </span>
                        @else
                            <span style="cursor: pointer;" class="badge {{ $estadoClass }} position-absolute top-0 end-0 m-2"
                                data-bs-toggle="modal" data-bs-target="#estadoModal" data-cita="{{ $cita->id }}"
                                data-estado="{{ $cita->estado }}">
                                {{ ucfirst($cita->estado) }}
                            </span>
                        @endif
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
                        <div class="d-flex flex-wrap gap-1 mb-1">
                            @foreach ($cita->usuarios as $usuario)
                                <span class="badge bg-secondary">
                                    {{ $usuario->name }} ({{ $usuario->pivot->rol_en_cita ?? 'N/A' }})
                                </span>
                            @endforeach
                        </div>

                        @if($botones)
                            <div data-cita-id="{{ $cita->id }}" class="contador-tiempo-restante text-danger fw-bold mb-2"
                                data-updated-at="{{ $cita->fecha_gestion?->timestamp ? $cita->fecha_gestion->timestamp * 1000 : '' }}"
                                data-created-at="{{ $cita->created_at->timestamp * 1000 }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <span>Opciones Disponibles </span>
                                    @php
                                        $fechaCita = \Carbon\Carbon::parse($cita->fecha_hora)->toDateString();
                                        $fechaHoy = \Carbon\Carbon::today()->toDateString();
                                        $habilitado = ($fechaCita === $fechaHoy && $cita->gestionado != 1);
                                    @endphp

                                    <div class="row">
                                        <div class="col-6">
                                            <a href="#" 
                                            class="btn btn-sm btn-warning btn-gestionar w-100" 
                                            @if($habilitado) data-cita-id="{{ $cita->id }}" data-gestionado="{{ $cita->gestionado }}" @endif 
                                            {{ !$habilitado ? 'disabled' : '' }}>
                                               </i> Gestionar
                                            </a>
                                        </div>

                                        <div class="col-6">
                                            <a href="#" 
                                            class="btn btn-sm btn-danger btn-gestionar w-100" 
                                            data-cita-id="{{ $cita->id }}" data-gestionado="{{ $cita->gestionado }}" 
                                            {{ !$habilitado ? 'disabled' : '' }}>
                                                 Gestionar
                                            </a>
                                        </div>

                                        @if($cita->gestionado == 1)
                                            <div class="col-6 mt-2">
                                                <a href="" 
                                                id="btn-editar_{{ $cita->id }}" 
                                                class="btn-editar-gestion btn btn-sm btn-warning w-100"
                                                data-cita-id="{{ $cita->id }}">
                                                   </i> Editar
                                                </a>
                                            </div>

                                            <div class="col-6 mt-2">
                                                <button 
                                                    class="btn btn-sm btn-info w-100" 
                                                    onclick="verGestion({{ $cita->id }})">
                                                    Resumen
                                                </button>
                                            </div>

                                            @if($cita->examenes->isNotEmpty())
                                                <div class="col-md-12 mt-2">
                                                    <button 
                                                        class="btn btn-sm btn-warning w-100" 
                                                        onclick="EditarHoja({{ $cita->id }})">
                                                       Editar Hoja de Laboratorio
                                                    </button>
                                                </div>
                                            @else
                                                <div class="col-md-12 mt-2">
                                                    <button 
                                                        class="btn btn-sm btn-primary w-100" 
                                                        onclick="CrearHoja({{ $cita->id }})">
                                                       Crear Hoja de Laboratorio
                                                    </button>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <span>Opciones Para Exportar </span>
                                    @if($cita->gestionado == 1)
                                        <div class="row">
                                            <div class="col-6">
                                                <a target="_blank" href="{{ route('citas.export_gestion', $cita) }}" class="btn btn-sm btn-danger w-100">
                                                    Hoja de Evolución
                                                </a>
                                            </div>

                                            @if($cita->examenes)
                                                <div class="col-6">
                                                    <a href="#" class="btn btn-sm btn-danger w-100">
                                                         Hoja de Laboratorio
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>


                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@else
    <p class="text-green">No hay citas asociadas.</p>
@endif


<script>

    document.addEventListener('DOMContentLoaded', () => {
        // Selecciona todos los contadores
        const contadores = document.querySelectorAll('.contador-tiempo-restante');

        contadores.forEach(contador => {
            const updatedAtTimestamp = Number(contador.dataset.updatedAt);
            const createdAtTimestamp = Number(contador.dataset.createdAt);

            // Comparar si hubo edición (updatedAt > createdAt)
            if (updatedAtTimestamp <= createdAtTimestamp) {
                // No hubo edición, mostrar mensaje y deshabilitar botón editar
                contador.style.display = 'none';

                const citaId = contador.closest('[data-cita-id]')?.dataset.citaId || null;

                const btnEditar = document.getElementById('btn-editar_' + citaId);
                console.log(citaId)
                console.log(btnEditar)
                if (btnEditar) {
                    btnEditar.disabled = true;
                    btnEditar.classList.add('disabled');
                }

                return; // Salir para este contador sin iniciar timer
            }

            // Si sí hubo edición, sigue con el contador normal
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