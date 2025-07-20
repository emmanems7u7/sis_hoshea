@extends('layouts.argon')

@section('content')


    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6>Servicios</h6>
                <a href="{{ route('servicios.create') }}" class="btn btn-primary mb-3">Crear Servicio</a>

            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            @if($servicios->isEmpty())
                <p>No hay servicios registrados.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Activo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($servicios as $servicio)
                                <tr>
                                    <td>{{ $servicio->nombre }}</td>
                                    <td>{{ $servicio->precio ? number_format($servicio->precio, 2) : '-' }}</td>
                                    <td>{{ $servicio->activo ? 'Sí' : 'No' }}</td>
                                    <td>
                                        <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('servicios.destroy', $servicio) }}" method="POST"
                                            style="display:inline-block;" onsubmit="return confirm('¿Eliminar servicio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection