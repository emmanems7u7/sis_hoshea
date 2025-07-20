@php
    use Carbon\Carbon;

@endphp
@if($tratamiento->citas->count())
    <div class="accordion mt-3" id="accordionCitas-{{ $tratamiento->id }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-{{ $tratamiento->id }}">
                <button class="accordion-button collapsed  bg-green_tarjetas_claro text-green fw-bold" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapse-{{ $tratamiento->id }}" aria-expanded="false"
                    aria-controls="collapse-{{ $tratamiento->id }}">
                    <i class="fas fa-calendar-check me-2"></i> Citas Asociadas ({{ $tratamiento->citas->count() }})
                </button>
            </h2>
            <div id="collapse-{{ $tratamiento->id }}" class="accordion-collapse collapse"
                aria-labelledby="heading-{{ $tratamiento->id }}" data-bs-parent="#accordionCitas-{{ $tratamiento->id }}">
                <div class="accordion-body ">

                    @foreach($tratamiento->citas as $cita)
                        <div class="border bg-green_tarjetas_claro rounded p-3 mb-3 position-relative">

                            <div>
                                <span class="text-info">{{ $cita->primera_cita == 1 ? 'Primera Cita' : '' }}</span>
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

                            <span style="cursor: pointer;" class="badge {{ $estadoClass }} position-absolute top-0 end-0 m-2"
                                data-bs-toggle="modal" data-bs-target="#estadoModal" data-cita="{{ $cita->id }}"
                                data-estado="{{ $cita->estado }}">
                                {{ ucfirst($cita->estado) }}
                            </span>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <small><strong>Fecha y hora:</strong>
                                        {{ $cita->fecha_hora->format('Y-m-d H:i') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <small><strong>Duración:</strong>
                                        {{ $cita->duracion ?? '-' }} min.</small>
                                </div>
                            </div>

                            <small><strong>Personal asignado a la cita:</strong></small>
                            <div class="d-flex flex-wrap gap-1 mb-2">
                                @foreach ($cita->usuarios as $usuario)
                                    <span class="badge bg-secondary">
                                        {{ $usuario->name }} ({{ $usuario->pivot->rol_en_cita ?? 'N/A' }})
                                    </span>
                                @endforeach
                            </div>

                            <a href="{{ route('tratamientos.gestion_cita', $cita) }}" class="btn btn-sm btn-dark">
                                <i class="fas fa-pencil-alt me-1"></i> Empezar Gestión
                            </a>

                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

@else
    <p class="text-green">No hay citas asociadas.</p>
@endif