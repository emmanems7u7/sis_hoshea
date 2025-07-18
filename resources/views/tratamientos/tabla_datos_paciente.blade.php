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