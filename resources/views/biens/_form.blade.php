@php
    $isEdit = isset($bien);
@endphp

<div class="mb-3">
    <label for="categoria_id" class="form-label">Categoría</label>
    <select name="categoria_id" id="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror"
        required>
        <option value="">Seleccione...</option>
        @foreach($categorias as $categoria)
            <option value="{{ $categoria->id }}" {{ old('categoria_id', $isEdit ? $bien->categoria_id : '') == $categoria->id ? 'selected' : '' }}>
                {{ $categoria->nombre }}
            </option>
        @endforeach
    </select>
    @error('categoria_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
        value="{{ old('nombre', $isEdit ? $bien->nombre : '') }}" required>
    @error('nombre')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="foto" class="form-label">Foto</label>
    <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" {{ $isEdit ? '' : 'required' }}>
    @error('foto')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if($isEdit && $bien->foto)
        <img src="{{ asset('fotos_bienes/' . $bien->foto) }}" alt="Foto actual" style="max-width: 150px; margin-top: 10px;">
    @endif
</div>

<div class="mb-3">
    <label for="descripcion" class="form-label">Descripción</label>
    <textarea name="descripcion" id="descripcion" rows="3"
        class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $isEdit ? $bien->descripcion : '') }}</textarea>
    @error('descripcion')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="cantidad" class="form-label">Cantidad</label>
    <input type="number" name="cantidad" id="cantidad" min="1"
        class="form-control @error('cantidad') is-invalid @enderror"
        value="{{ old('cantidad', $isEdit ? $bien->cantidad : 1) }}" required>
    @error('cantidad')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="ubicacion" class="form-label">Ubicación</label>
    <input type="text" name="ubicacion" id="ubicacion" class="form-control @error('ubicacion') is-invalid @enderror"
        value="{{ old('ubicacion', $isEdit ? $bien->ubicacion : '') }}">
    @error('ubicacion')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="fecha_adquisicion" class="form-label">Fecha de adquisición</label>
    <input type="date" name="fecha_adquisicion" id="fecha_adquisicion"
        class="form-control @error('fecha_adquisicion') is-invalid @enderror"
        value="{{ old('fecha_adquisicion', $isEdit ? $bien->fecha_adquisicion : '') }}">
    @error('fecha_adquisicion')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="valor_adquisicion" class="form-label">Valor de adquisición</label>
    <input type="number" step="0.01" name="valor_adquisicion" id="valor_adquisicion"
        class="form-control @error('valor_adquisicion') is-invalid @enderror"
        value="{{ old('valor_adquisicion', $isEdit ? $bien->valor_adquisicion : '') }}">
    @error('valor_adquisicion')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        new TomSelect('#categoria_id', {
            placeholder: 'Seleccione una categoria',
            allowEmptyOption: true
        });
    });
</script>