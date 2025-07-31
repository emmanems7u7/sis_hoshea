{{-- Tratamientos --}}
<div id="paciente_tratamientos">
    @if($tratamientos->isEmpty())
        <p>No hay tratamientos.</p>
    @else
        <div class="row g-2">
        @foreach($tratamientos as $t)
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card p-2 shadow-sm h-100">
            <div class="row">
                <div class="col-6">
                    <p class="mb-1 small"><strong>Inicio:</strong> {{ $t->fecha_inicio->format('d/m/Y') }}</p>
                </div>
                <div class="col-6">
                    <p class="mb-1 small"><strong>Fin:</strong> {{ $t->fecha_fin->format('d/m/Y') }}</p>
                </div>
            </div>
            <p class="mb-1 small"><strong>Nombre:</strong> {{ $t->nombre }}</p>
            <p class="mb-1 small"><strong>Citas:</strong> {{ $t->citas->count() }}</p>
            <p class="mb-0 small"><strong>Obs:</strong> {{ $t->observaciones ?? '-' }}</p>

            <hr class="my-2">

            <!-- Botón de acordeón -->
            <button class="btn btn-sm btn-outline-success w-100 text-start" type="button"
                data-bs-toggle="collapse" data-bs-target="#citasTratamiento{{ $t->id }}"
                aria-expanded="false" aria-controls="citasTratamiento{{ $t->id }}">
                Ver citas asociadas
            </button>

            <!-- Contenido del acordeón -->
            <div class="collapse mt-2" id="citasTratamiento{{ $t->id }}">
            @forelse($t->citas as $cita)
            <div class="border rounded p-2 mb-2 small">
                <div><strong>Fecha:</strong> {{ $cita->fecha_hora->format('d/m/Y H:i') }}</div>
                <div><strong>Duración:</strong> {{ $cita->duracion ?? '-' }} min</div>
                <div><strong>Estado:</strong> 
                    <span class="badge 
                        @switch($cita->estado)
                            @case('pendiente') bg-warning text-dark @break
                            @case('confirmada') bg-primary @break
                            @case('cancelada') bg-danger @break
                            @case('completada') bg-success @break
                            @default bg-secondary
                        @endswitch">
                        {{ ucfirst($cita->estado) }}
                    </span>
                </div>

                @if($cita->gestionado)
                <div class="mt-2">
                    <span class="fw-bold d-block mb-1">Opciones para exportar</span>
                    
                    <div class="d-flex flex-wrap gap-2">
                        <a target="_blank" href="{{ route('citas.export_gestion', $cita) }}"
                            class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1 px-2 py-1">
                            <i class="fas fa-file-pdf small"></i>
                            <span class="small">Hoja de Evolución</span>
                        </a>

                        @if($cita->examenes && $cita->examenes->count() > 0)
                            <a target="_blank" href="{{ route('citas.export_hoja', $cita) }}"
                                class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1 px-2 py-1">
                                <i class="fas fa-flask small"></i>
                                <span class="small">Hoja de Laboratorio</span>
                            </a>
                        @endif
                    </div>
                </div>


                @endif
            </div>
            @empty
                <div class="text-muted small">No hay citas para este tratamiento.</div>
            @endforelse

            </div>
        </div>
    </div>
@endforeach

        </div>
    @endif
</div>