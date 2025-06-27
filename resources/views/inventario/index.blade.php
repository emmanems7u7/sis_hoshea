@extends('layouts.argon')

@section('content')

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h4 mb-0">Inventario</h1>
                <a class="btn btn-primary" href="{{ route('inventario.create') }}">Nuevo artículo</a>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Stock</th>
                            <th>Unidad</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            <tr>
                                <td>{{$index + 1 }}</td>
                                <td> @if($item->imagen != null)
                                    <img src="{{ asset($item->imagen)}}" class="avatar e-3">
                                @else
                                        <p>S/N</p>
                                    @endif
                                </td>
                                <td>{{ $item->nombre }}</td>
                                <td>{{ $item->categoria->nombre ?? '—' }}</td>
                                <td>{{ $item->stock_actual }}</td>
                                <td>{{ $item->unidad_medida }}</td>
                                <td class="text-end">
                                    <a href="{{ route('inventario.edit', $item) }}" class="btn btn-sm btn-secondary">Editar</a>


                                    <a type="button" class="btn btn-sm btn-danger" id="modal_edit_usuario_button"
                                        onclick="confirmarEliminacion('eliminarProductoForm', '¿Estás seguro de que deseas eliminar este producto?')">Eliminar
                                    </a>

                                    <form id="eliminarProductoForm" method="POST"
                                        action="{{ route('inventario.destroy', $item) }}" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Sin registros.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $items->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>


@endsection