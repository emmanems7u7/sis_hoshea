<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Reporte Paciente - {{ $paciente->nombre_completo }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            /* fuente para mPDF */
            font-size: 12px;
            margin: 20px;
            color: #2c3e50;
        }

        h1 {
            color: #4e6b48;
            border-bottom: 2px solid #4e6b48;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }

        /* Solo tablas con esta clase tienen estilos */
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: fixed;
            word-wrap: break-word;
        }

        table.report-table th,
        table.report-table td {
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #ccc;
            vertical-align: top;
        }

        table.report-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            width: 30%;
            color: #555;
        }

        .badge {
            display: inline-block;
            background-color: #6c757d;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            margin-left: 5px;
        }

        .card-header,
        .card-body {
            padding: 8px;
        }

        .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .small {
            font-size: 11px;
        }

        /* Estilos nuevos para cuadro con líneas */
        .cuadro {
            width: 30px;
            height: 30px;
            border: 2px solid black;
            position: relative;
            margin: 10px 0;
        }

        .linea {
            position: absolute;
            width: 22px;
            height: 3px;
            background-color: black;
            top: 13px;
            left: 4px;
            transform-origin: center;
        }

        .linea1 {
            transform: rotate(45deg);
        }

        .linea2 {
            transform: rotate(-45deg);
        }
    </style>
</head>

<body>




    <table width="100%" style="border-collapse: collapse; margin-bottom:20px">
        <tr>
            <td style="text-align: center;">
                <h1 style="margin: 0;">Reporte Paciente: {{ $paciente->nombre_completo }} </h1>
            </td>
            <td style="text-align: right;" width="100">
                @if($logo_base64)
                    <img src="{{ $logo_base64 }}" alt="Logo" style="height: 40px; width: auto;">
                @endif
            </td>
        </tr>
    </table>

    <div class="info-header">
        <p><strong>Generado por:</strong> {{ $user->nombre_completo }}</p>
        <p><strong>Fecha:</strong> {{ $fecha }}</p>



    </div>


    @include('tratamientos.tabla_datos_paciente')
    <h4 style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 10px;">
        Antecedentes Failiares</h4>

    <div class="card">
        <div class="card-body small">
            @if($paciente->antecedentes->isNotEmpty())
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Antecedente</th>
                            <th>Familiar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paciente->antecedentes as $antecedente)
                            <tr>
                                <td>{{ $antecedente->catalogoAntecedente->catalogo_descripcion }}</td>
                                <td>{{ $antecedente->catalogoFamiliar->catalogo_descripcion }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No se registraron antecedentes familiares.</p>
            @endif
        </div>
    </div>
    <h4 style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 10px;">
        Diagnósticos</h4>
    @if($diagnosticos->isEmpty())
        <p>No hay diagnósticos.</p>
    @else
        @php $agrupados = $diagnosticos->groupBy('cod_diagnostico'); @endphp

        @foreach($agrupados as $codigo => $grupo)
            <div class="card">
                <div class="card-header">
                    Diagnóstico: {{ $grupo[0]['nombre_diagnostico'] ?? 'Sin nombre' }}
                    <span class="badge" title="Cantidad de veces">{{ count($grupo) }} veces</span>
                </div>
                <div class="card-body small">
                    <table class="report-table" style="margin-bottom:10px; width: 100%;">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Criterio Clínico</th>
                                <th>Evolución</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grupo as $diagnostico)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($diagnostico['fecha_diagnostico'])->format('d/m/Y') }}</td>
                                    <td>{{ $diagnostico['criterio_clinico'] ?? '-' }}</td>
                                    <td>{{ $diagnostico['evolucion_diagnostico'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif

    <h4 style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 10px;">
        Tratamientos</h4>
    @if($tratamientos->isEmpty())
        <p>No hay tratamientos.</p>
    @else
        @foreach($tratamientos as $t)
            <div class="card">
                <div class="card-body small">
                    <table class="report-table" style="margin-bottom: 10px;">


                        <tr>
                            <th><strong>Nombre del Tratamiento</strong> </th>
                            <td>{{ $t->nombre }}</td>
                            <th>Citas</th>
                            <td>{{ $t->citas->count() }}</td>
                        </tr>

                        <tr>
                            <th>Inicio</th>
                            <td>{{ $t->fecha_inicio->format('d/m/Y') }}</td>
                            <th>Fin</th>
                            <td>{{ $t->fecha_fin->format('d/m/Y') }}</td>
                        </tr>

                        <tr>
                            <th>Observaciones</th>
                            <td colspan="3">{{ $t->observaciones ?? '-' }}</td>
                        </tr>
                    </table>

                    @if($t->citas->isNotEmpty())
                        <strong>Citas asociadas:</strong>
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Duración (min)</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($t->citas as $cita)
                                    <tr>
                                        <td>{{ $cita->fecha_hora->format('d/m/Y H:i') }}</td>
                                        <td>{{ $cita->duracion ?? '-' }}</td>
                                        <td>{{ ucfirst($cita->estado) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No hay citas para este tratamiento.</p>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

    @include('tratamientos.firma')
</body>

</html>