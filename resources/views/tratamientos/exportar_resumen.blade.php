<style>
    body {
        font-family: DejaVu Sans, sans-serif;
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

    .info-header {
        margin-bottom: 25px;
    }

    .info-header p {
        margin: 2px 0;
    }
</style>
<table width="100%" style="border-collapse: collapse; margin-bottom:50px">
    <tr>
        <td style="text-align: center;">
            <h1 style="margin: 0;">Hoja de evolución</h1>
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

    <p><strong>Nº De Historia Clinica:</strong> C-{{ $cita->id }}</p>

</div>

<table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
        <td width="50%" valign="top">
            <h4 style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 10px;">
                Datos del Paciente</h4>

            <table width="100%" style="font-size: 11px; border-collapse: collapse;">
                <tr>
                    <td width="35%" style="color: #6c757d;">Nombre:</td>
                    <td width="65%" style="font-weight: bold;">{{ $tratamiento->paciente->nombre_completo }}</td>
                </tr>
                <tr>
                    <td style="color: #6c757d;">{{ $tratamiento->paciente->tipo_documento }}:</td>
                    <td style="font-weight: bold;">{{ $tratamiento->paciente->numero_documento }}</td>
                </tr>
                <tr>
                    <td style="color: #6c757d;">Género:</td>
                    <td style="font-weight: bold;">
                        {{ $tratamiento->paciente->genero == 'M' ? 'Masculino' : 'Femenino' }}
                    </td>
                </tr>
                <tr>
                    <td style="color: #6c757d;">Teléfono:</td>
                    <td style="font-weight: bold;">
                        {{ $tratamiento->paciente->telefono ?? 'No tiene teléfono' }}
                    </td>
                </tr>
                <tr>
                    <td style="color: #6c757d;">Celular:</td>
                    <td style="font-weight: bold;">{{ $tratamiento->paciente->telefono_movil }}</td>
                </tr>
                <tr>
                    <td style="color: #6c757d;">Email:</td>
                    <td style="font-weight: bold;">{{ $tratamiento->paciente->email }}</td>
                </tr>
                <tr>
                    <td style="color: #6c757d;">Dirección:</td>
                    <td style="font-weight: bold;">{{ $tratamiento->paciente->direccion }}</td>
                </tr>

            </table>
        </td>
        <td width="50%" valign="top">
            <h4 style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 10px;">
                Datos del Tratamiento</h4>

            <table width="100%" style="font-size: 11px; border-collapse: collapse;">
                <tr>
                    <td width="35%" style="color: #6c757d;">Inicio:</td>
                    <td width="65%" style="font-weight: bold;">
                        {{ $tratamiento->fecha_inicio->format('d-m-Y') }}
                    </td>
                </tr>
                <tr>
                    <td style="color: #6c757d;">Fin:</td>
                    <td style="font-weight: bold;">
                        {{ $tratamiento->fecha_fin->format('d-m-Y') }}
                    </td>
                </tr>

                <tr>
                    <td style="color: #6c757d;">Estado:</td>
                    <td style="font-weight: bold;">
                        {{ $tratamiento->estado }}
                    </td>
                </tr>
                <tr>
                    <td style="color: #6c757d;">Observaciones:</td>
                    <td style="font-weight: bold;">
                        {{ $tratamiento->observaciones ?? 'No tiene observación' }}
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
<h4
    style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 10px; text-align: center; font-size: 15px;">
    Evaluación Medica (SOAP)
</h4>
<h4
    style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 4px; margin-top: 20px; margin-bottom: 10px;">
    S/(Subjetivo):</h4>
<span style="margin-bottom: 20px;">[Sintomas referidos por el paciente, molestias, evolución desde la ultima visita]
</span>
<table width="100%" style="font-size: 11px; border-collapse: collapse; margin-top: 20px;">
    @foreach ($cita->datosCita as $dato)
        <tr>
            <td style="padding: 4px; border: 1px solid #ddd;">{{ $dato->descripcion }}</td>
        </tr>
    @endforeach
</table>

<h4 style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 10px;">O/(Objetivos)
</h4>
<span style="margin-bottom: 20px;">[Signos vitales, hallazgos del examen físico, resultados de laboratorio, imágenes,
    etc.]
</span>
<table width="100%" style="font-size: 11px; border-collapse: collapse; margin-top: 20px;">
    @foreach ($cita->objetivosCita as $obj)
        <tr>
            <td width="40%" style="font-weight: bold; padding: 5px 10px; border-bottom: 1px solid #ccc;">
                {{ $obj->catalogo->catalogo_descripcion ?? 'Sin descripción' }}
            </td>
            <td width="60%" style="color: #6c757d; padding: 5px 10px; border-bottom: 1px solid #ccc;">
                {{ $obj->valor }}
            </td>
        </tr>
    @endforeach
</table>

<h4
    style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 4px; margin-top: 20px; margin-bottom: 10px;">
    A/(Análisis/Diagnóstico)</h4>
<span style="margin-bottom: 20px;">[Diagnóstico médico actual, evolución del diagnóstico previo, criterios clínicos]
</span>

@php

    $diagnosticosAgrupados = $cita->diagnosticos->groupBy(function ($plan) {
        return $plan->catalogo->catalogo_descripcion ?? 'Sin descripción';
    });
@endphp

@foreach ($diagnosticosAgrupados as $codigo => $grupo)
    <h5 style="font-weight: bold;">Diagnostico: {{ $codigo }} ({{ $grupo->count() }})</h5>

    <table width="100%" style="font-size: 11px; border-collapse: collapse; margin-bottom: 15px;">
        <thead>
            <tr style="background-color: #f0f0f0;">
                <th style="border: 1px solid #ccc; padding: 5px; width: 15%;">Fecha</th>
                <th style="border: 1px solid #ccc; padding: 5px; width: 40%;">Criterio Clínico</th>
                <th style="border: 1px solid #ccc; padding: 5px; width: 30%;">Evolución</th>
                <th style="border: 1px solid #ccc; padding: 5px; width: 15%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($grupo as $diagnostico)
                <tr>
                    <td style="border: 1px solid #ccc; padding: 5px;">{{ $diagnostico->fecha_diagnostico }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px;">{{ $diagnostico->criterio_clinico }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px;">{{ $diagnostico->evolucion_diagnostico }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px;">
                        {{ $diagnostico->estado == '1' ? 'Activo' : 'Inactivo' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach



<h4
    style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 4px; margin-top: 20px; margin-bottom: 10px;">
    P/(Plan/Tratamiento)</h4>
<span style="margin-bottom: 20px;">[Tratamiento indicado, estudios complementarios, seguimiento, recomendaciones]
</span>


@php
    // Agrupamos los planes por la descripción del catálogo (tipo)
    $planesPorTipo = $cita->planes->groupBy(function ($plan) {
        return $plan->catalogo->catalogo_descripcion ?? 'Sin descripción';
    });
@endphp
<div style="margin-top: 20px;">
    @foreach ($planesPorTipo as $descripcion => $planesGrupo)
        <table width="100%"
            style="font-size: 11px; border: 1px solid #ccc; border-collapse: collapse; margin-bottom: 15px;">
            <tr style="background-color: #f0f0f0;">
                <td colspan="2" style="font-weight: bold; padding: 5px;">
                    {{ $descripcion }} ({{ $planesGrupo->count() }})
                </td>
            </tr>
            @foreach ($planesGrupo as $plan)
                <tr>
                    <td style="padding: 5px;">{{ $plan->descripcion }}</td>
                </tr>
            @endforeach
        </table>
    @endforeach
</div>

<p style="font-family: Arial, sans-serif; font-size: 12px; color: #333; margin-top: 10px;">
    <strong>Próxima cita:</strong> {{ $fechaProxima }}
</p>


@include('tratamientos.firma')