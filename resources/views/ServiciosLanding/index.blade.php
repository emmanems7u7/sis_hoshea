@extends('layouts.argon')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h5>Texto para Landing Page</h5>
                        <form action="{{ route('configuracion.servicio') }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="mb-3">
                                <label for="titulo_servicio" class="form-label">Título</label>
                                <input type="text" name="titulo_servicio" id="titulo_servicio"
                                    class="form-control @error('titulo_servicio') is-invalid @enderror"
                                    value="{{ old('titulo_servicio', $config->titulo_servicio) }}" required maxlength="255">
                                @error('titulo_servicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="descripcion_servicio" class="form-label">Descripción</label>
                                <textarea name="descripcion_servicio" id="descripcion_servicio" rows="5"
                                    class="form-control @error('descripcion_servicio') is-invalid @enderror">{{ old('descripcion_servicio', $config->descripcion_servicio) }}</textarea>
                                @error('descripcion_servicio')
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
                        <h5>Servicios para landing Page</h5>


                        <a href="{{ route('servicios_landing.create') }}" class="btn btn-primary mb-3">Crear Nuevo
                            Servicio</a>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card mt-3">
        <div class="card-body">
            @if($servicios->count())
                <div class="table-responsiver">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Título</th>
                                <th>Descripción</th>
                                <th>Icono</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($servicios as $index => $servicio)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $servicio->titulo }}</td>
                                    <td>{{ Str::limit($servicio->descripcion, 50) }}</td>
                                    <td>{{ $servicio->icono }}</td>
                                    <td>{{ $servicio->estado ? 'Activo' : 'Inactivo' }}</td>
                                    <td>
                                        <a href="{{ route('servicios_landing.edit', $servicio) }}"
                                            class="btn btn-sm btn-warning">Editar</a>

                                        <form action="{{ route('servicios_landing.destroy', $servicio) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('¿Seguro que quieres eliminar este servicio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $servicios->links('pagination::bootstrap-4') }}
                    </div>

                </div>
            @else
                <p>No hay servicios registrados.</p>
            @endif
        </div>
    </div>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#descripcion_servicio'))
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