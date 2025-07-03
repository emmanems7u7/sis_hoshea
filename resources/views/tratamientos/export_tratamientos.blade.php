<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h2 {
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 4px;
            vertical-align: top;
        }

        th {
            background: #f0f0f0;
            text-align: left;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
        }

        .bg-success {
            background: #28a745;
            color: #fff;
        }

        .bg-secondary {
            background: #6c757d;
            color: #fff;
        }

        .bg-danger {
            background: #dc3545;
            color: #fff;
        }

        .bg-warning {
            background: #ffc107;
            color: #212529;
        }

        .bg-primary {
            background: #0d6efd;
            color: #fff;
        }
    </style>
</head>

<body>

    <p>Generado por: {{ $user->nombre_completo }}</p>
    <p>fecha: {{ $fecha }}</p>
    @foreach ($tratamientos as $tratamiento)
        <h2>Tratamiento: {{ $tratamiento->nombre }}</h2>
        <p>
            Paciente: <strong>{{ $tratamiento->paciente->nombres }} {{ $tratamiento->paciente->apellidos }}</strong><br>
            Fecha inicio: {{ $tratamiento->fecha_inicio->format('Y-m-d') }}<br>
            Fecha fin: {{ $tratamiento->fecha_fin ? $tratamiento->fecha_fin->format('Y-m-d') : '-' }}<br>
            Estado:
            @php
                $color = match ($tratamiento->estado) {
                    'activo' => 'bg-success',
                    'finalizado' => 'bg-secondary',
                    'cancelado' => 'bg-danger',
                    default => 'bg-secondary',
                };
            @endphp
            <span class="badge {{ $color }}">{{ ucfirst($tratamiento->estado) }}</span>
        </p>

        @if ($tratamiento->citas->count())
            <table>
                <thead>
                    <tr>
                        <th style="width: 30%">Fecha y hora</th>
                        <th style="width: 15%">Duración</th>
                        <th style="width: 15%">Estado</th>
                        <th style="width: 40%">Personal (rol)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tratamiento->citas as $cita)
                        @php
                            $citaColor = match ($cita->estado) {
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
                                    {{ $u->name }} ({{ $u->pivot->rol_en_cita ?? 'N/A' }})@if(!$loop->last), @endif
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
</body>

</html>