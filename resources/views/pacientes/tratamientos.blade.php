{{-- Tratamientos --}}
<div id="paciente_tratamientos">
    @if($tratamientos->isEmpty())
        <p>No hay tratamientos.</p>
    @else
        <div class="row g-2">
            @foreach($tratamientos as $t)
                <div class="col-4">
                    <div class="card p-2 shadow-sm h-100">
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1 small"><strong>Inicio:</strong>
                                    {{ \Carbon\Carbon::parse($t->fecha_inicio)->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1 small"><strong>Fin:</strong>
                                    {{ \Carbon\Carbon::parse($t->fecha_fin)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <p class="mb-1 small"><strong>Nombre:</strong> {{ $t->nombre }}</p>
                        <p class="mb-0 small"><strong>Obs:</strong> {{ $t->observaciones ?? '-' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>