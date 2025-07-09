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

                        @php
                            $estadoClass = match ($cita->estado) {
                                'pendiente' => 'bg-warning text-white',
                                'confirmada' => 'bg-primary',
                                'cancelada' => 'bg-danger',
                                'completada' => 'bg-success',
                                default => 'bg-secondary'
                            };
                        @endphp

                        <span class="badge {{ $estadoClass }} position-absolute top-0 end-0 m-2" data-bs-toggle="modal"
                            data-bs-target="#estadoModal" data-cita="{{ $cita->id }}" data-estado="{{ $cita->estado }}">
                            {{ ucfirst($cita->estado) }}
                        </span>
                        <div class=" row">
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


                            <a href="#" data-bs-toggle="modal" data-bs-target="#modal_gestion"
                                class="btn btn-sm btn-warning {{  $cita->fecha_hora->toDateString() === Carbon::today()->toDateString() ? '' : 'disableds' }}">
                                Gestionar
                            </a>

                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@else
    <p class="text-green">No hay citas asociadas.</p>
@endif