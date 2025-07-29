@php
    $isEdit = isset($tratamiento);
    $estados = ['activo' => 'Activo', 'finalizado' => 'Finalizado', 'cancelado' => 'Cancelado'];
@endphp

<div class="row">
    <div class="col-12 col-md-6 mb-3">
        <label for="paciente_id" class="form-label">Paciente</label>
        <select name="paciente_id" id="paciente_id" class="form-select @error('paciente_id') is-invalid @enderror"
            @if($pac == 1) required @endif>
            <option value="" disabled {{ old('paciente_id', $tratamiento->paciente_id ?? '') == '' ? 'selected' : '' }}>
                Seleccione un paciente</option>
            @foreach($pacientes as $id => $nombre)
                <option value="{{ $id }}" {{ old('paciente_id', $tratamiento->paciente_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $nombre }}
                </option>
            @endforeach
        </select>
        @error('paciente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>


    <div class="col-md-6 mb-3">
        <label for="nombre" class="form-label">Nombre del tratamiento</label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $tratamiento->nombre ?? '') }}"
            class="form-control @error('nombre') is-invalid @enderror" required>
        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio"
            value="{{ old('fecha_inicio', isset($tratamiento) ? $tratamiento->fecha_inicio->format('Y-m-d') : '') }}"
            class="form-control @error('fecha_inicio') is-invalid @enderror" required>
        @error('fecha_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="fecha_fin" class="form-label">Fecha de fin (opcional)</label>
        <input type="date" name="fecha_fin" id="fecha_fin"
            value="{{ old('fecha_fin', isset($tratamiento) && $tratamiento->fecha_fin ? $tratamiento->fecha_fin->format('Y-m-d') : '') }}"
            class="form-control @error('fecha_fin') is-invalid @enderror">
        @error('fecha_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="fecha_fin" class="form-label">Estado</label>

        <select class="form-select" id="estado_tratamiento" name="estado_tratamiento" required>
            <option value="">Seleccione estado</option>
            <option value="activo" {{ old('estado_tratamiento', $tratamiento->estado ?? '') == 'activo' ? 'selected' : '' }}>
                Activo</option>
            <option value="finalizado" {{ old('estado_tratamiento', $tratamiento->estado ?? '') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
            <option value="cancelado" {{ old('estado_tratamiento', $tratamiento->estado ?? '') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            <option value="pendiente" {{ old('estado_tratamiento', $tratamiento->estado ?? '') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
        </select>
    </div>
    <div class="col-md-12 mb-3">
        <label for="observaciones_tratamiento" class="form-label">Observaciones</label>
        <textarea name="observaciones_tratamiento" id="observaciones_tratamiento" rows="3"
            class="form-control @error('observaciones_tratamiento') is-invalid @enderror">{{ old('observaciones_tratamiento', $tratamiento->observaciones ?? '') }}</textarea>
        @error('observaciones_tratamiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>