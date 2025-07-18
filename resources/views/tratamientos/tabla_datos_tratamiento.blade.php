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