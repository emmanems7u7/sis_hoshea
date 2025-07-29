<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #2c3e50;
        }

        .cuadro {
            width: 30px;
            height: 30px;
            border: 2px solid black;
            position: relative;
            margin: 10px;
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

        h1 {
            color: #4e6b48;
            border-bottom: 2px solid #4e6b48;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
    </style>


    <table width="100%" style="border-collapse: collapse; margin-bottom:20px">

        <tr>
            <td style="text-align: center;">
                <h1 style="margin: 0;">- Hoja de laboratorio - </h1>
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
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
        <tr>
            <td width="50%" valign="top">
                @include('tratamientos.tabla_datos_paciente')
            </td>

            <td width="50%" valign="top">
                @if($tratamiento)
                    @include('tratamientos.tabla_datos_tratamiento')
                @else
                    @include('tratamientos.tabla_datos_cita')

                @endif

            </td>
        </tr>
    </table>



    <h4 style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 10px;">
        EXÁMENES SOLICITADOS
    </h4>
    @php
        $codigosMarcados = $cita->examenes->pluck('examen')->toArray();
    @endphp

    <table width="100%" style="font-size: 12px;">
        @foreach ($examenes->chunk(3) as $grupo)
            <tr>
                @foreach ($grupo as $examen)
                    <td width="33%">
                        @if(in_array($examen->catalogo_codigo, $codigosMarcados))
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjE2IiBoZWlnaHQ9IjE2IiBzdHJva2U9ImJsYWNrIiBmaWxsPSJub25lIiBzdHJva2Utd2lkdGg9IjIiLz48bGluZSB4MT0iNCIgeTE9IjQiIHgyPSIxMiIgeTI9IjEyIiBzdHJva2U9ImJsYWNrIiBzdHJva2Utd2lkdGg9IjIiLz48bGluZSB4MT0iMTIiIHkxPSI0IiB4Mj0iNCIgeTI9IjEyIiBzdHJva2U9ImJsYWNrIiBzdHJva2Utd2lkdGg9IjIiLz48L3N2Zz4="
                                alt="X" style="width:10px; height:10px;">
                        @else
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjE2IiBoZWlnaHQ9IjE2IiBzdHJva2U9ImJsYWNrIiBmaWxsPSJub25lIiBzdHJva2Utd2lkdGg9IjIiLz48L3N2Zz4="
                                alt="Cuadro" style="width:10px; height:10px;">
                        @endif
                        {{ $examen->catalogo_descripcion }}
                    </td>
                @endforeach

                {{-- Rellenar las celdas faltantes si hay menos de 3 en la fila --}}
                @for ($i = $grupo->count(); $i < 3; $i++)
                    <td></td>
                @endfor
            </tr>
        @endforeach

        {{-- Mostrar "Otro" si fue llenado --}}
        @foreach ($cita->examenes as $ex)
            @if (!empty($ex->examen_otro))
                <tr>
                    <td colspan="3">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjE2IiBoZWlnaHQ9IjE2IiBzdHJva2U9ImJsYWNrIiBmaWxsPSJub25lIiBzdHJva2Utd2lkdGg9IjIiLz48bGluZSB4MT0iNCIgeTE9IjQiIHgyPSIxMiIgeTI9IjEyIiBzdHJva2U9ImJsYWNrIiBzdHJva2Utd2lkdGg9IjIiLz48bGluZSB4MT0iMTIiIHkxPSI0IiB4Mj0iNCIgeTI9IjEyIiBzdHJva2U9ImJsYWNrIiBzdHJva2Utd2lkdGg9IjIiLz48L3N2Zz4="
                            alt="X" style="width:10px; height:10px;">
                        <strong>Otro:</strong> {{ $ex->examen_otro }}
                    </td>
                </tr>
            @endif
        @endforeach
    </table>




    <h4 style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 10px;">
        RESULTADOS DE LABORATORIO
    </h4>
    <table cellpadding="5" cellspacing="0" border="0"
        style="margin-bottom: 5500px !important; border-collapse: collapse;  width: 100%;">
        <thead>
            <tr>
                <th style=" font-size: 12px; text-align: left; width: 5%; border: 1px solid black;">Nª</th>
                <th style=" font-size: 12px; text-align: left; width: 45%; border: 1px solid black;">Parametro</th>
                <th style=" font-size: 12px; text-align: left; width: 20%; border: 1px solid black;">Resultado</th>
                <th style=" font-size: 12px; text-align: left; width: 30%; border: 1px solid black;">Valores de
                    Referencia</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cita->examenes as $index => $examen)
                <tr>

                    <td style="border: 1px solid black;">
                        {{ $index + 1 }}
                    </td>
                    <td style="border: 1px solid black;">
                        @if($examen->examen_otro == null)

                                {{ $examen->catalogo->catalogo_descripcion ?? '' }}
                            </td>
                        @else
                        {{ $examen->examen_otro }}</td>

                    @endif

                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"></td>


                </tr>
            @endforeach


        </tbody>
    </table>

    @include('tratamientos.firma')
</body>

</html>