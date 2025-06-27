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
                <option value="">Seleccione un paciente</option>
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
            @foreach($usuarios as $id => $nombre)
                @php
                    $checked = $isEdit && isset($usuariosAsignados[$id]);
                    $rolAsignado = $checked ? $usuariosAsignados[$id] : '';
                @endphp
                <div class="col-6 d-flex align-items-center gap-2 mb-2">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" value="{{ $id }}" id="usuario_{{ $id }}"
                            name="usuarios[]" {{ $checked ? 'checked' : '' }}>
                        <label class="form-check-label" for="usuario_{{ $id }}">{{ $nombre }}</label>
                    </div>
                    <input type="text" name="roles[]" value="{{ old('roles.' . $loop->index, $rolAsignado) }}"
                        placeholder="Rol en cita" class="form-control form-control-sm" style="max-width: 150px;">
                </div>
            @endforeach
        </div>
        @error('usuarios') <div class="text-danger">{{ $message }}</div> @enderror
        @error('roles') <div class="text-danger">{{ $message }}</div> @enderror
    </div>
</div>