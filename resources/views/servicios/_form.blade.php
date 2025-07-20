<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
        value="{{ old('nombre', $servicio->nombre ?? '') }}" required>
    @error('nombre')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="precio" class="form-label">Precio</label>
    <input type="number" step="0.01" name="precio" id="precio"
        class="form-control @error('precio') is-invalid @enderror" value="{{ old('precio', $servicio->precio ?? '') }}">
    @error('precio')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="descripcion" class="form-label">Descripción</label>
    <textarea name="descripcion" id="descripcion" rows="3"
        class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $servicio->descripcion ?? '') }}</textarea>
    @error('descripcion')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-check mb-3">
    <input type="hidden" name="activo" value="0">
    <input type="checkbox" name="activo" id="activo" class="form-check-input" value="1" {{ old('activo', $servicio->activo ?? true) ? 'checked' : '' }}>
    <label for="activo" class="form-check-label">Activo</label>
    @error('activo')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>