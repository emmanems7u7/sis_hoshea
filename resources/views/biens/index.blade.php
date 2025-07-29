@extends('layouts.argon')

@section('content')


    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('biens.create') }}" class="btn btn-primary mb-3">Agregar Bien</a>
                <a href="{{ route('biens.export_pdf') }}" class="btn btn-danger mb-3">Exportar en PDF</a>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <h6>Inventario Clinica</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Categoría</th>
                            <th>Nombre</th>
                            <th>Foto</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Ubicación</th>
                            <th>Fecha adquisición</th>
                            <th>Valor adquisición</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($biens as $index => $bien)
                            <tr>
                                <td>{{ $index + 1}}</td>
                                <td>{{ $bien->categoria->nombre ?? 'Sin categoría' }}</td>
                                <td>{{ $bien->nombre }}</td>
                                <td>
                                    @if($bien->foto)
                                        <img src="{{ asset('fotos_bienes/' . $bien->foto) }}" alt="{{ $bien->nombre }}"
                                            style="max-width: 60px;">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $bien->descripcion ?? '-' }}</td>
                                <td>{{ $bien->cantidad }}</td>
                                <td>{{ $bien->ubicacion ?? '-' }}</td>
                                <td>{{ $bien->fecha_adquisicion ? \Carbon\Carbon::parse($bien->fecha_adquisicion)->format('d/m/Y') : '-' }}
                                </td>
                                <td>{{ $bien->valor_adquisicion ? number_format($bien->valor_adquisicion, 2) : '-' }}</td>
                                <td>
                                    <a href="{{ route('biens.edit', $bien) }}" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="{{ route('biens.destroy', $bien) }}" method="POST"
                                        style="display:inline-block" onsubmit="return confirm('¿Eliminar bien?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No hay bienes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
            <div class="d-flex justify-content-center">
                {{ $biens->links('pagination::bootstrap-4') }}

            </div>

        </div>
    </div>

@endsection