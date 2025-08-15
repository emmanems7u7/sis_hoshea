@csrf

<div class="mb-3">
    <label for="descripcion" class="form-label">Descripción</label>
    <textarea name="descripcion" id="descripcion" rows="5"
        class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $item->descripcion ?? '') }}</textarea>
    @error('descripcion')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="icono" class="form-label  text-black @error('icono') is-invalid @enderror">Icono</label>
    <div class="input-group">
        <input type="text" name="icono" id="icono" value="{{ old('icono', $item->icono ?? '') }}" class="form-control"
            required placeholder="fas fa-user">
        <span class="input-group-text">
            <i id="preview-icono" class="{{ old('icono', $item->icono ?? '') }}"></i>
        </span>
    </div>
    @error('icono')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-primary">Guardar</button>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    let debounceTimer;

    ClassicEditor
        .create(document.querySelector('#descripcion'))
        .then(editor => {
            editor.model.document.on('change:data', () => {
                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(() => {
                    // Obtener texto plano del editor
                    const titulo = editor.getData().replace(/<[^>]*>?/gm, '').trim();

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
                }, 500);
            });
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