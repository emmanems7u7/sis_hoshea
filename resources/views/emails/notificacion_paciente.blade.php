@extends('emails.layout')

@section('title', 'Inicio de tratamiento')

@php
    $paciente = $tratamiento->paciente;
    $nombre = $tratamiento->nombre;
    $fechaInicio = $tratamiento->fecha_inicio->format('d/m/Y');
    $fechaFin = $tratamiento->fecha_fin ? $tratamiento->fecha_fin->format('d/m/Y') : '—';
@endphp

@section('content')
    <h1>Notificación de inicio de tratamiento</h1>

    <p>
        Estimado/a <strong>{{ $paciente->nombre_completo }}</strong>,
    </p>

    <p>
        Se informa que el tratamiento <strong>«{{ $nombre }}»</strong> dará inicio en la fecha indicada a
        continuación. Se ruega tomar las previsiones correspondientes y presentarse puntualmente.
    </p>

    <div class="details">
        <span><strong>Fecha de inicio:</strong> {{ $fechaInicio }}</span>
        <span><strong>Fecha estimada de finalización:</strong> {{ $fechaFin }}</span>
        <span><strong>Estado actual:</strong> {{ ucfirst($tratamiento->estado) }}</span>
    </div>

    @if($tratamiento->citas->count())
        <p><strong>Próximas citas asociadas:</strong></p>
        <ul>
            @foreach($tratamiento->citas as $cita)
                <li>{{ $cita->fecha_hora->format('d/m/Y H:i') }} ({{ $cita->estado }})</li>
            @endforeach
        </ul>
    @endif

    <p>
        Ante cualquier duda o requerimiento adicional, sírvase comunicarse con nuestro personal de atención. al numero
    </p>
@endsection