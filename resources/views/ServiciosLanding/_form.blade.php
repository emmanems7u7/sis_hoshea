<style>
    .ck-editor__editable_inline {
        min-height: 150px !important;
        color: #000 !important;
        background-color: #fff !important;
    }

    .ck-content {
        color: #000 !important;
        background-color: #fff !important;
    }
</style>




@php
    $isEdit = isset($servicio);
@endphp

<form method="POST"
    action="{{ $isEdit ? route('servicios_landing.update', $servicio) : route('servicios_landing.store') }}">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror"
            value="{{ old('titulo', $isEdit ? $servicio->titulo : '') }}" required maxlength="255">
        @error('titulo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" id="descripcion" rows="5"
            class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $isEdit ? $servicio->descripcion : '') }}</textarea>
        @error('descripcion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    <div class="mb-3">
        <label for="icono" class="form-label  text-black @error('icono') is-invalid @enderror">Icono</label>
        <div class="input-group">
            <input type="text" name="icono" id="icono" value="{{ old('icono') }}" class="form-control" required
                placeholder="fas fa-user">
            <span class="input-group-text">
                <i id="preview-icono" class="{{ old('icono') }}"></i>
            </span>
        </div>
        @error('icono')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="estado" class="form-label">Estado</label>
        <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
            <option value="1" {{ old('estado', $isEdit ? $servicio->estado : '') == 1 ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ old('estado', $isEdit ? $servicio->estado : '') == 0 ? 'selected' : '' }}>Inactivo
            </option>
        </select>
        @error('estado')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Actualizar' : 'Crear' }}</button>
</form>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#descripcion'))
        .then(editor => {
            // Accede al editable y cambia estilos
            const editable = editor.ui.view.editable.element;

            editable.style.minHeight = '150px';
            editable.style.color = '#000';
            editable.style.backgroundColor = '#fff';
        })
        .catch(error => {
            console.error(error);
        });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputIcono = document.getElementById('icono');
        const previewIcono = document.getElementById('preview-icono');

        inputIcono.addEventListener('input', function () {
            const valor = inputIcono.value.trim();
            previewIcono.className = valor;
        });
    });


</script>


<script>
    let debounceTimer;

    document.getElementById('titulo').addEventListener('input', function () {
        clearTimeout(debounceTimer);

        const input = this;

        debounceTimer = setTimeout(() => {
            const titulo = input.value.trim();

            if (titulo.length < 3) return;

            fetch("/api/sugerir-icono", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ titulo })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.icono) {
                        document.getElementById('icono').value = data.icono;
                        document.getElementById('preview-icono').className = data.icono;
                    }
                })
                .catch(err => console.error(err));
        }, 500); // espera 500ms después de la última tecla
    });
</script>