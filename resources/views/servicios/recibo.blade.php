<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recibo de Servicios - Cita #{{ $cita->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #2c3e50;
        }


        .titulo {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .info {
            margin-bottom: 15px;
        }

        .info p {
            margin: 2px 0;
        }

        .tabla_servicios {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .tabla_servicios th,
        .tabla_servicios td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: left;
        }

        .tabla_servicios th {
            background-color: #f0f0f0;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }

        .info-header {
            margin-bottom: 25px;
        }

        .info-header p {
            margin: 2px 0;
        }

        h1 {
            color: #4e6b48;
            border-bottom: 2px solid #4e6b48;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <table width="100%" style="border-collapse: collapse; margin-bottom:20px">

        <tr>
            <td style="text-align: center;">
                <h1 style="margin: 0;">- Recibo Médico - </h1>
            </td>
            <td style="text-align: right;" width="100">
                @if($base64)
                    <img src="{{ $base64 }}" alt="Logo" style="height: 40px; width: auto;">
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
                @include('tratamientos.tabla_datos_paciente')

            </td>
            <td width="50%" valign="top">
                @include('tratamientos.tabla_datos_cita')

            </td>
        </tr>
    </table>




    <h4>Servicios asignados en la cita</h4>
    <table class="tabla_servicios">
        <thead>
            <tr>
                <th>#</th>
                <th>Servicio</th>
                <th>Descripción</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($cita->servicios as $index => $servicio)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $servicio->nombre }}</td>
                    <td>{{ $servicio->descripcion }}</td>
                    <td>Bs {{ number_format($servicio->precio, 2, ',', '.') }}</td>
                </tr>
                @php $total += $servicio->precio; @endphp
            @endforeach
        </tbody>
    </table>

    <h4>Productos asignados en la cita</h4>
    <table class="tabla_servicios">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Descripción</th>
                <th>cantidad</th>
                <th>unidad</th>
                <th>Precio_unitario</th>

                <th>Subtotal</th>

            </tr>
        </thead>
        <tbody>

            @foreach ($cita->inventarios as $index => $inventario)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $inventario->nombre }}</td>
                    <td>{{ $inventario->descripcion }}</td>

                    <td>{{ $inventario->pivot->cantidad }}</td>
                    <td>{{ $inventario->unidad_medida }}</td>
                    <td>Bs {{ number_format($inventario->precio_unitario, 2, ',', '.') }}</td>
                    <td>Bs {{ number_format($inventario->precio_unitario * $inventario->pivot->cantidad, 2, ',', '.') }}
                    </td>


                </tr>
                @php $total += $inventario->precio_unitario * $inventario->pivot->cantidad; @endphp
            @endforeach
        </tbody>
    </table>

    <p class="total">Total: Bs {{ number_format($total, 2, ',', '.') }}</p>


    @include('tratamientos.firma')
</body>


</html>