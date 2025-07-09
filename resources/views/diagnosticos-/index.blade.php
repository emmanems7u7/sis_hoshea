@extends('layouts.argon')

@section('content')
<div class="container">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('diagnosticos.create') }}" class="btn btn-primary mb-3">
        + Nuevo Diagnóstico
    </a>

    @if($diagnosticos->isEmpty())
        <p>No hay diagnósticos registrados para este tratamiento.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Código</th>
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
@endsection
