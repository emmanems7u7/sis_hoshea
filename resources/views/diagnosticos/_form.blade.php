<div class="col-12 col-md-6 mb-3">
    <label for="tratamiento_id" class="form-label">Tratamiento (opcional)</label>
    <select name="tratamiento_id" id="tratamiento_id"
        class="form-select @error('tratamiento_id') is-invalid @enderror">
        <option value="">Sin tratamiento</option>
        @foreach($tratamientos as  $tratamiento)
            <option value="{{ $tratamiento->id }}"
                {{ old('tratamiento_id', $diagnostico->tratamiento_id ?? '') == $tratamiento->id ? 'selected' : '' }}>
                {{ $tratamiento->nombre }}
            </option>
        @endforeach
    </select>
    @error('tratamiento_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
            <label for="cod_diagnostico">Diagnostico</label>
            <select class="form-select @error('cod_diagnostico') is-invalid @enderror" id="cod_diagnostico" name="cod_diagnostico">
                <option value="" selected>Seleccione un cod_diagnostico</option>
                @foreach ($diagnosticos as $diagnostico)
                    <option value="{{ $diagnostico->catalogo_codigo }}" {{ old('cod_diagnostico', $tratamiento->cod_diagnostico ?? '') == $diagnostico->catalogo_codigo ? 'selected' : '' }}>
                        {{ $diagnostico->catalogo_descripcion }}
                    </option>
                @endforeach
            </select>
            @error('cod_diagnostico')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

<div class="mb-3">
    <label for="fecha_diagnostico" class="form-label">Fecha</label>
    <input type="date" name="fecha_diagnostico" id="fecha_diagnostico"
        class="form-control @error('fecha_diagnostico') is-invalid @enderror"
        value="{{ old('fecha_diagnostico', isset($diagnostico->fecha_diagnostico) ? \Carbon\Carbon::parse($diagnostico->fecha_diagnostico)->format('Y-m-d') : '') }}"
        required>
    @error('fecha_diagnostico')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="estado" class="form-label">Estado</label>
    <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror">
        <option value="activo" {{ old('estado', $diagnostico->estado ?? 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
        <option value="inactivo" {{ old('estado', $diagnostico->estado ?? '') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
    </select>
    @error('estado')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="observacion" class="form-label">Observación</label>
    <textarea name="observacion" id="observacion"
        class="form-control @error('observacion') is-invalid @enderror"
        rows="3">{{ old('observacion', $diagnostico->observacion ?? '') }}</textarea>
    @error('observacion')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
