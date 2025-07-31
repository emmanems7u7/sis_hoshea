<div id="paciente_diagnosticos">
    @if($diagnosticos->isEmpty())
        <p>No hay diagnósticos.</p>
    @else
        @php
            $agrupados = $diagnosticos->groupBy('cod_diagnostico');
        @endphp

        <div class="row g-2">
            @foreach($agrupados as $codigo => $grupo)
                <div class="col-12 col-md-6">
                    <div class="card p-2 shadow-sm h-100">
                        <div class="card-header ">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="">diagnostico:
                                    {{ $grupo[0]['nombre_diagnostico'] ?? 'Sin nombre' }}</strong>
                                <span class="badge bg-secondary" title="Cantidad de veces">
                                    {{ count($grupo) }} veces
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-2" style="max-height: 300px; overflow-y: auto;">
                            @foreach($grupo as $diagnostico)
                                <div class="border rounded p-2 mb-2 small">
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>Fecha:</strong>
                                            {{ \Carbon\Carbon::parse($diagnostico['fecha_diagnostico'])->format('d/m/Y') }}
                                        </div>

                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-6">
                                            <strong>Criterio Clínico:</strong><br> {{ $diagnostico['criterio_clinico'] ?? '-' }}
                                        </div>
                                        <div class="col-6">
                                            <strong>Evolución:</strong><br> {{ $diagnostico['evolucion_diagnostico'] ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


    @endif
</div>