<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #2c3e50;
        }

        h1 {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }

        .info-header {
            margin-bottom: 25px;
        }

        .info-header p {
            margin: 2px 0;
        }

        .treatment-card {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 6px;
            background-color: #f8f9fa;
        }

        .treatment-title {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #0d6efd;
        }

        .treatment-details p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            padding: 6px 8px;
            border: 1px solid #bbb;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 11px;
        }

        .bg-success { background: #198754; color: #fff; }
        .bg-secondary { background: #6c757d; color: #fff; }
        .bg-danger { background: #dc3545; color: #fff; }
        .bg-warning { background: #ffc107; color: #212529; }
        .bg-primary { background: #0d6efd; color: #fff; }

        .no-citas {
            font-style: italic;
            color: #6c757d;
            margin-top: 10px;
        }

    </style>
</head>
<body>

    <h1>Reporte de Tratamientos</h1>

    <div class="info-header">
        <p><strong>Generado por:</strong> {{ $user->nombre_completo }}</p>
        <p><strong>Fecha:</strong> {{ $fecha }}</p>
    </div>

    @foreach ($tratamientos as $tratamiento)
        <div class="treatment-card">
            <div class="treatment-title">Tratamiento: {{ $tratamiento->nombre }}</div>

            <div class="treatment-details">
                <p><strong>Paciente:</strong> {{ $tratamiento->paciente->nombres }} {{ $tratamiento->paciente->apellidos }}</p>
                <p><strong>Fecha de inicio:</strong> {{ $tratamiento->fecha_inicio->format('Y-m-d') }}</p>
                <p><strong>Fecha de fin:</strong> {{ $tratamiento->fecha_fin ? $tratamiento->fecha_fin->format('Y-m-d') : '-' }}</p>
                <p><strong>Estado:</strong>
                    @php
                        $estadoColor = match($tratamiento->estado) {
                            'activo' => 'bg-success',
                            'finalizado' => 'bg-secondary',
                            'cancelado' => 'bg-danger',
                            default => 'bg-secondary',
                        };
                    @endphp
                    <span class="badge {{ $estadoColor }}">{{ ucfirst($tratamiento->estado) }}</span>
                </p>
            </div>

            @if ($tratamiento->citas->count())
                <table>
                    <thead>
                        <tr>
                            <th style="width: 30%">Fecha y Hora</th>
                            <th style="width: 15%">Duración</th>
                            <th style="width: 15%">Estado</th>
                            <th style="width: 40%">Personal Asignado (Rol)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tratamiento->citas as $cita)
                            @php
                                $citaColor = match($cita->estado) {
                                    'pendiente' => 'bg-warning',
                                    'confirmada' => 'bg-primary',
                                    'cancelada' => 'bg-danger',
                                    'completada' => 'bg-success',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <tr>
                                <td>{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
                                <td>{{ $cita->duracion ?? '-' }} min</td>
                                <td><span class="badge {{ $citaColor }}">{{ ucfirst($cita->estado) }}</span></td>
                                <td>
                                    @foreach ($cita->usuarios as $u)
                                        {{ $u->name }} ({{ $u->pivot->rol_en_cita ?? 'N/A' }})@if (!$loop->last), @endif
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-citas">No hay citas asociadas a este tratamiento.</p>
            @endif
        </div>
    @endforeach

</body>
</html>
