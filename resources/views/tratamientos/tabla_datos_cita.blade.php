<h4 style="font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 10px;">
    Datos de la cita</h4>



<table width="100%" style="font-size: 11px; border-collapse: collapse;">
    <tr>
        <td width="35%" style="color: #6c757d;">Fecha de la cita:</td>
        <td width="65%" style="font-weight: bold;">{{ $cita->fecha_hora->format('d-m-Y, H:i') }}</td>
    </tr>
    <tr>
        <td style="color: #6c757d;">Estado:</td>
        <td style="font-weight: bold;">{{ ucfirst($cita->estado) }}</td>
    </tr>

    <tr>
        <td style="color: #6c757d;">Duración:</td>
        <td style="font-weight: bold;">
            {{ $cita->duracion }} minutos
        </td>
    </tr>
    <tr>
        <td style="color: #6c757d;">Tratamiento:</td>
        <td style="font-weight: bold;">{{ $tratamiento->nombre ?? 'Sin tratamiento' }}</td>
    </tr>
    <tr>
        <td style="color: #6c757d;">Observaciones:</td>
        <td style="font-weight: bold;">
            {{ $cita->observaciones ?? 'Ninguna' }}
        </td>
    </tr>



</table>