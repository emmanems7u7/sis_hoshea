@php
    $isEdit = isset($cita);
    $estados = ['pendiente' => 'Pendiente', 'confirmada' => 'Confirmada', 'cancelada' => 'Cancelada', 'completada' => 'Completada'];
@endphp

<div class="row">
    @if($pac == 1)
    <div class="col-12 col-md-6 mb-3">
        <label for="paciente_id" class="form-label">Paciente</label>
        <select name="paciente_id" id="paciente_id" class="form-select @error('paciente_id') is-invalid @enderror"
            @if($pac == 1) required @endif>
            <option value="" disabled selected>Seleccione un paciente</option>
            @foreach($pacientes as $id => $nombre)
                <option value="{{ $id }}" {{ old('paciente_id', $cita->paciente_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $nombre }}
                </option>
            @endforeach
        </select>
        @error('paciente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    

        <div class="col-12 col-md-6 mb-3">
            <label for="tratamiento_id" class="form-label">Tratamiento (opcional)</label>
            <select name="tratamiento_id" id="tratamiento_id"
                class="form-select @error('tratamiento_id') is-invalid @enderror">
                <option value="">Sin tratamiento</option>
                @foreach($tratamientos as $id => $nombre)
                    <option value="{{ $id }}" {{ old('tratamiento_id', $cita->tratamiento_id ?? '') == $id ? 'selected' : '' }}>
                        {{ $nombre }}
                    </option>
                @endforeach
            </select>
            @error('tratamiento_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endif

    @if($pac != 1)
    <div class="col-12">
        <div class="form-check">
            <!-- Este input hidden asegura que siempre se envíe algo (0 por defecto) -->
            <input type="hidden" name="primera_cita" value="0">

            <!-- Este checkbox solo sobrescribe si está marcado -->
            <input class="form-check-input" type="checkbox"
                id="primera_cita"
                name="primera_cita"
                value="1"
                {{ old('primera_cita', isset($cita) && $cita->primera_cita == 1 ? 'checked' : '') }}>

            <label class="form-check-label" for="primera_cita">¿Es primera cita?</label>

            <label class="text-primary" data-bs-toggle="tooltip" data-bs-placement="right"
                title="Marca esta casilla si es la primera cita del tratamiento. Si es la única cita, marcarla es opcional.">
                <i class="fas fa-info-circle" style="cursor: pointer;"></i>
            </label>
        </div>
    </div>

    @endif
    <div class="col-12 col-md-6 mb-3">
        <label for="fecha_hora" class="form-label">Fecha y hora</label>
        <input type="datetime-local" name="fecha_hora" id="fecha_hora"
            value="{{ old('fecha_hora', isset($cita) ? $cita->fecha_hora->format('Y-m-d\TH:i') : '') }}"
            class="form-control @error('fecha_hora') is-invalid @enderror" @if($pac == 1) required @endif>
        @error('fecha_hora') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12 col-md-6 mb-3">
        <label for="duracion" class="form-label">Duración (minutos, opcional)</label>
        <input type="number" name="duracion" id="duracion" min="1" value="{{ old('duracion', $cita->duracion ?? '') }}"
            class="form-control @error('duracion') is-invalid @enderror">
        @error('duracion') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12 col-md-6 mb-3">
        <label for="estado" class="form-label">Estado</label>
        <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" @if($pac == 1)
        required @endif>
            @foreach($estados as $key => $label)
                <option value="{{ $key }}" {{ old('estado', $cita->estado ?? '') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12 col-md-6 mb-3">
        <label for="observaciones" class="form-label">Observaciones (opcional)</label>
        <textarea name="observaciones" id="observaciones" rows="3"
            class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $cita->observaciones ?? '') }}</textarea>
        @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12 mb-3">
        <label class="form-label">Usuarios asignados</label>
        <div class="row">
        @foreach($usuarios as $usuario)
        @php
            $id = $usuario->id;
            $nombre = $usuario->nombre_completo;
            $checked = $isEdit && isset($usuariosAsignados[$id]);
            $rolAsignado = $checked ? $usuariosAsignados[$id] : '';
            $roles = $usuario->getRoleNames();
        @endphp
        <div class="col-12 col-md-6 col-lg-4 mb-3">
            <div class="d-flex align-items-start gap-2">
            <div class="form-check mb-0 w-100">
                <input class="form-check-input" type="checkbox" value="{{ $id }}" id="usuario_{{ $id }}" name="usuarios[{{ $id }}]" {{ $checked ? 'checked' : '' }}>
                <label class="form-check-label" for="usuario_{{ $id }}">
                <strong>{{ $nombre }}</strong>
                @if($roles->isNotEmpty())
                    <small class="text-muted">({{ $roles->join(', ') }})</small>
                @endif
                </label>
                <input type="text" name="roles[{{ $id }}]" value="{{ old('roles.' . $id, $rolAsignado) }}" placeholder="Responsabilidad en cita" class="form-control form-control-sm mt-1">
            </div>
            </div>
        </div>
        @endforeach

        </div>
        @error('usuarios') <div class="text-danger">{{ $message }}</div> @enderror
        @error('roles') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
</div>
<script>
   
   document.addEventListener('DOMContentLoaded', function () {
        new TomSelect('#paciente_id', {
            placeholder: 'Seleccione un paciente o escriba para buscar',
            allowEmptyOption: true
        });
    });
</script>

<script>
       function formatoFecha(fecha) {
                if (!fecha) return '-';
                const d = new Date(fecha);
                if (isNaN(d)) return '-';
                return new Intl.DateTimeFormat('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }).format(d);
            }
function cargarDatosPaciente(pacienteId) {
    if (!pacienteId) {
        document.getElementById('paciente_tratamientos').innerHTML = '';
        document.getElementById('paciente_diagnosticos').innerHTML = '';
        return;
    }

    fetch(`/pacientes/${pacienteId}/datos`)
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta');
            return response.json();
        })
        .then(data => {
               // Mostrar tratamientos
               const tratamientosDiv = document.getElementById('paciente_tratamientos');
                    if (data.tratamientos.length === 0) {
                        tratamientosDiv.innerHTML = '<p>No hay tratamientos.</p>';
                    } else {
                        tratamientosDiv.innerHTML = `
              <div class="row g-2">
                ${data.tratamientos.map(t => `
                  <div class="col-12">
                    <div class="card p-2 shadow-sm h-100">
                      <div class="row">
                        <div class="col-6">
                          <p class="mb-1 small"><strong>Inicio:</strong> ${formatoFecha(t.fecha_inicio)}</p>
                        </div>
                        <div class="col-6">
                          <p class="mb-1 small"><strong>Fin:</strong> ${formatoFecha(t.fecha_fin)}</p>
                        </div>
                      </div>
                      <p class="mb-1 small"><strong>Nombre:</strong> ${t.nombre}</p>
                      <p class="mb-0 small"><strong>Obs:</strong> ${t.observaciones ?? '-'}</p>
                    </div>
                  </div>
                `).join('')}
              </div>
            `;
                    }

                    // Mostrar diagnósticos
                    const diagnosticosDiv = document.getElementById('paciente_diagnosticos');

                    if (data.diagnosticos.length === 0) {
                        diagnosticosDiv.innerHTML = '<p>No hay diagnósticos.</p>';
                    } else {
                        // Agrupar por cod_diagnostico
                        const agrupados = {};
                        data.diagnosticos.forEach(d => {
                            if (!agrupados[d.cod_diagnostico]) {
                                agrupados[d.cod_diagnostico] = [];
                            }
                            agrupados[d.cod_diagnostico].push(d);
                        });

                        let html = `<div class="row g-2">`;

                        Object.entries(agrupados).forEach(([codigo, lista]) => {
                            const cantidad = lista.length;
                            const ultimo = lista[lista.length - 1]; // último diagnóstico registrado
                            html += `
                <div class="col-12">
                    <div class="card p-2 shadow-sm h-100">
                        <div class="row">
                            <div class="col-6 small fw-semibold">
                                ${ultimo.nombre_diagnostico} 
                                <span class="badge bg-secondary ms-2" data-bs-toggle="tooltip" data-bs-placement="right" title="Veces que el paciente ha recibido este diagnóstico.">${cantidad}</span>
                            </div>
                            <div class="col-6 small"><strong>Fecha:</strong> ${formatoFecha(ultimo.fecha_diagnostico)}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6 small">
                                <strong>Criterio Clínico:</strong><br> ${ultimo.criterio_clinico}
                            </div>
                            <div class="col-6 small">
                                <strong>Evolución:</strong><br> ${ultimo.evolucion_diagnostico}
                            </div>
                        </div>
                    </div>
                </div>
            `;
                        });

                        html += `</div>`;
                        diagnosticosDiv.innerHTML = html;
                    }
        })
        .catch(error => {
            console.error('Error al obtener datos:', error);
            document.getElementById('paciente_tratamientos').innerHTML = '<p>Error al cargar tratamientos.</p>';
            document.getElementById('paciente_diagnosticos').innerHTML = '<p>Error al cargar diagnósticos.</p>';
        });
}
// Listener para cambios en el select
document.getElementById('paciente_id').addEventListener('change', function () {
    cargarDatosPaciente(this.value);
});

// Ejecutar al cargar la página en modo edición si ya hay paciente seleccionado
document.addEventListener('DOMContentLoaded', () => {
    const pacienteSelect = document.getElementById('paciente_id');
    if (pacienteSelect.value) {
        cargarDatosPaciente(pacienteSelect.value);
    }
});

    
    </script>