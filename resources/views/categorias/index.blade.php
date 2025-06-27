@extends('layouts.argon')

@section('content')


    <div class="card">
        <div class="card-body">
            <h5>Lista de categorias disponibles</h5>
            <h6>Visualice y cree categorias que corresponderan a los servicios</h6>
            <a href="{{ route('categorias.create', 1) }}" class="btn btn-primary">{{ __('ui.new_f_text') }}
                {{ __('lo.categoria') }}</a>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categorias as $index => $categoria)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $categoria->nombre }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $categoria->estado ? 'success' : 'secondary' }}">
                                        {{ $categoria->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>{{ $categoria->descripcion }}</td>
                                <td>
                                    <a href="{{ route('categorias.edit', [$categoria->id, 1]) }}"
                                        class="btn btn-sm btn-warning">
                                        {!! __('ui.edit_icon') !!}
                                    </a>
                                    <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST"
                                        class="d-inline" id="delete-form-{{ $categoria->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmarEliminacion('delete-form-{{ $categoria->id }}', '¿Estás seguro de eliminar esta categoría?')">
                                            {!! __('ui.delete_icon') !!}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $categorias->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

@endsection