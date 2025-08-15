@extends('layouts.argon')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5>Texto para Landing Page</h5>
                        <form action="{{ route('configuracion.acercade') }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="mb-3">
                                <label for="titulo_acercade" class="form-label">Título</label>
                                <input type="text" name="titulo_acercade" id="titulo_acercade"
                                    class="form-control @error('titulo_acercade') is-invalid @enderror"
                                    value="{{ old('titulo_acercade', $config->titulo_acercade) }}" required maxlength="255">
                                @error('titulo_acercade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="descripcion_acercade" class="form-label">Descripción</label>
                                <textarea name="descripcion_acercade" id="descripcion_acercade" rows="5"
                                    class="form-control @error('descripcion_acercade') is-invalid @enderror">{{ old('descripcion_acercade', $config->descripcion_acercade) }}</textarea>
                                @error('descripcion_acercade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-sm btn-info">Actualizar información</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Items Acerda de para Landing Page</h5>
                        <a href="{{ route('acerca_landings.create') }}" class="btn btn-success mb-3">Crear Nuevo</a>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card mt-3">
        <div class="card-body">
            @if($items->count())

                <div class="table-responsive">


                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descripción</th>
                                <th>Icono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->descripcion }}</td>
                                    <td><i class="{{ $item->icono }}"></i> {{ $item->icono }}</td>
                                    <td>
                                        <a href="{{ route('acerca_landings.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('acerca_landings.destroy', $item->id) }}" method="POST"
                                            style="display:inline-block" onsubmit="return confirm('¿Eliminar este registro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $items->links('pagination::bootstrap-4') }}
                    </div>
                </div>

            @else
                <p>No hay registros.</p>
            @endif
        </div>
    </div>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#descripcion_acercade'))
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
@endsection