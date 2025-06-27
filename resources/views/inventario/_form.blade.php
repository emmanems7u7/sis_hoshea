<div class="row g-3 mb-3">

    {{-- Nombre --}}
    <div class="col-md-6">
        <label for="nombre" class="form-label">Nombre *</label>
        <input type="text" id="nombre" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $inventario->nombre ?? '') }}" required>
        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Categoría --}}
    <div class="col-md-6">
        <label for="categoria_id" class="form-label">Categoría *</label>
        <select id="categoria_id" name="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror"
            required>
            <option value="">Seleccione</option>
            @foreach ($categorias as $id => $nombre)
                <option value="{{ $id }}" {{ old('categoria_id', $inventario->categoria_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $nombre }}
                </option>
            @endforeach
        </select>
        @error('categoria_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Descripción --}}
    <div class="col-12">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea id="descripcion" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
            rows="3">{{ old('descripcion', $inventario->descripcion ?? '') }}</textarea>
        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Código --}}
    <div class="col-md-4">
        <label for="codigo" class="form-label">Código</label>
        <input type="text" id="codigo" name="codigo" maxlength="100"
            class="form-control @error('codigo') is-invalid @enderror"
            value="{{ old('codigo', $inventario->codigo ?? '') }}">
        @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Unidad de medida --}}
    <div class="col-md-4">
        <label for="unidad_medida" class="form-label">Unidad *</label>
        <input type="text" id="unidad_medida" name="unidad_medida" maxlength="50"
            class="form-control @error('unidad_medida') is-invalid @enderror"
            value="{{ old('unidad_medida', $inventario->unidad_medida ?? '') }}" required>
        @error('unidad_medida') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Precio unitario --}}
    <div class="col-md-4">
        <label for="precio_unitario" class="form-label">Precio unitario *</label>
        <input type="number" step="0.01" id="precio_unitario" name="precio_unitario"
            class="form-control @error('precio_unitario') is-invalid @enderror"
            value="{{ old('precio_unitario', $inventario->precio_unitario ?? 0) }}" required>
        @error('precio_unitario') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Stock actual --}}
    <div class="col-md-4">
        <label for="stock_actual" class="form-label">Stock actual *</label>
        <input type="number" id="stock_actual" name="stock_actual" min="0"
            class="form-control @error('stock_actual') is-invalid @enderror"
            value="{{ old('stock_actual', $inventario->stock_actual ?? 0) }}" required>
        @error('stock_actual') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Stock mínimo --}}
    <div class="col-md-4">
        <label for="stock_minimo" class="form-label">Stock mínimo *</label>
        <input type="number" id="stock_minimo" name="stock_minimo" min="0"
            class="form-control @error('stock_minimo') is-invalid @enderror"
            value="{{ old('stock_minimo', $inventario->stock_minimo ?? 0) }}" required>
        @error('stock_minimo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Ubicación --}}
    <div class="col-md-4">
        <label for="ubicacion" class="form-label">Ubicación</label>
        <input type="text" id="ubicacion" name="ubicacion" class="form-control @error('ubicacion') is-invalid @enderror"
            value="{{ old('ubicacion', $inventario->ubicacion ?? '') }}">
        @error('ubicacion') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Imagen --}}
    <div class="col-md-6">
        <label for="imagen" class="form-label">Imagen</label>
        <input type="file" id="imagen" name="imagen" class="form-control @error('imagen') is-invalid @enderror"
            accept="image/*" onchange="vistaPreviaImagen(event)">
        @error('imagen')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        {{-- Vista previa existente --}}
        @isset($inventario->imagen)
            <div class="mt-2">
                <img src="{{ asset($inventario->imagen) }}" alt="Imagen actual" style="max-height: 120px"
                    class="img-thumbnail d-block">
            </div>
        @endisset

        {{-- Vista previa nueva (oculta hasta seleccionar archivo) --}}
        <div class="mt-2" id="preview-wrapper" style="display:none;">
            <img id="preview-img" style="max-height: 120px" class="img-thumbnail d-block"
                alt="Nueva imagen seleccionada">
        </div>
    </div>

</div>

<script>

    function vistaPreviaImagen(event) {
        const input = event.target;
        const file = input.files && input.files[0];
        const output = document.getElementById('preview-img');
        const wrap = document.getElementById('preview-wrapper');

        if (file) {
            const reader = new FileReader();

            reader.onload = e => {
                output.src = e.target.result;
                wrap.style.display = 'block';
            };

            reader.readAsDataURL(file);
        } else {
            // Si se cancela la selección, ocultar la vista previa
            wrap.style.display = 'none';
            output.src = '';
        }
    }
</script>