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
            <h1 style="margin: 0;">- Lista de activos en clínica - </h1>
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

<h4 style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 10px;">
    Inventario Clínica
</h4>
<table cellpadding="5" cellspacing="0" border="0"
    style="margin-bottom: 5500px !important; border-collapse: collapse;  width: 100%;">
    <thead>
        <tr>
            <th style=" font-size: 12px; text-align: left; width: 5%; border: 1px solid black;">Nº</th>
            <th style=" font-size: 12px; text-align: left; width: 5%; border: 1px solid black;">Categoría</th>
            <th style=" font-size: 12px; text-align: left; width: 5%; border: 1px solid black;">Nombre</th>
            <th style=" font-size: 12px; text-align: left; width: 5%; border: 1px solid black;">Descripción</th>
            <th style=" font-size: 12px; text-align: left; width: 5%; border: 1px solid black;">Cant.</th>
            <th style=" font-size: 12px; text-align: left; width: 5%; border: 1px solid black;">Ubicación</th>
            <th style=" font-size: 12px; text-align: left; width: 5%; border: 1px solid black;">Fecha adquisición</th>
            <th style=" font-size: 12px; text-align: left; width: 5%; border: 1px solid black;">Valor adquisición</th>
        </tr>
    </thead>
    <tbody>
        @foreach($biens as $index => $bien)
            <tr>
                <td style="border: 1px solid black;">{{ $index + 1 }}</td>
                <td style="border: 1px solid black;">{{ $bien->categoria->nombre ?? 'Sin categoría' }}</td>
                <td style="border: 1px solid black;">{{ $bien->nombre }}</td>
                <td style="border: 1px solid black;">{{ $bien->descripcion ?? '-' }}</td>
                <td style="border: 1px solid black;">{{ $bien->cantidad }}</td>
                <td style="border: 1px solid black;">{{ $bien->ubicacion ?? '-' }}</td>
                <td style="border: 1px solid black;">
                    {{ $bien->fecha_adquisicion ? \Carbon\Carbon::parse($bien->fecha_adquisicion)->format('d/m/Y') : '-' }}
                </td>
                <td style="border: 1px solid black;">
                    {{ $bien->valor_adquisicion ? number_format($bien->valor_adquisicion, 2) : '-' }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@include('tratamientos.firma')