<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cita actualizada</title>
</head>
<body>
    <p>Hola {{ $cita->paciente->nombre_completo ?? 'Paciente' }},</p>

    @switch($cita->estado)
        @case('pendiente')
            <p>Tu cita ha sido registrada y está en estado <strong>pendiente</strong>. Te contactaremos para confirmar.</p>
            @break

        @case('confirmada')
            <p>¡Tu cita ha sido <strong>confirmada</strong>! Te esperamos en la fecha y hora indicada.</p>
            @break

        @case('cancelada')
            <p>Lamentamos informarte que tu cita ha sido <strong>cancelada</strong>. Puedes reprogramarla si lo deseas, contactandote con la clínica</p>
            @break

        @case('completada')
            <p>Tu cita ha sido <strong>completada</strong>. Gracias por tu asistencia.</p>
            @break

        @default
            <p>El estado de tu cita ha sido actualizado.</p>
    @endswitch

    <hr>

    <ul>
        <li><strong>Estado actual:</strong> {{ ucfirst($cita->estado) }}</li>
        <li><strong>Fecha y hora:</strong> {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y H:i') }}</li>
        <li><strong>Duración:</strong> {{ $cita->duracion ?? 'N/D' }} minutos</li>
        @if($cita->observaciones)
            <li><strong>Observaciones:</strong> {{ $cita->observaciones }}</li>
        @endif
    </ul>

    <p>© {{ date('Y') }} {{ env('APP_NAME') }}. Todos los derechos reservados.</p>
</body>
</html>
