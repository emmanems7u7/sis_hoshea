@extends('layouts.argon')

@section('content')


    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('diagnosticos.create') }}" class="btn btn-primary mb-3">
        + Nuevo Diagnóstico
        </a>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
@if($diagnosticos->isEmpty())
        <p>No hay diagnósticos registrados para este tratamiento.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Tratamiento</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Observación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($diagnosticos as $d)
                    <tr>
                        <td>{{ $d->cod_diagnostico }}</td>
                        <td>{{ $d->tratamiento->nombre ??'sin tratamiento asignado' }}</td>
                        <td>{{ \Carbon\Carbon::parse($d->fecha_diagnostico)->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($d->estado) }}</td>
                        <td>{{ $d->observacion ?? '—' }}</td>
                        <td>
                           
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
        </div>
    </div>

@endsection