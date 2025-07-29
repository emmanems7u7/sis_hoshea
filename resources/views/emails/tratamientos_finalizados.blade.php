<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Notificación de tratamientos finalizados</title>
</head>

<body style="background-color: #f4f6f8; margin: 0; padding: 0; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
        style="background-color: #f4f6f8; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" role="presentation"
                    style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 30px;">
                    <tr>
                        <td style="text-align: center; padding-bottom: 20px;">
                            <h1 style="color: #2c3e50; margin: 0; font-size: 24px;">Tratamientos Finalizados</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="color: #34495e; font-size: 16px; line-height: 1.5; padding-bottom: 20px;">
                            <p>Se finalizaron automáticamente <strong style="color: #27ae60;">{{ $cantidad }}</strong>
                                tratamientos cuya fecha de finalización ya había pasado.</p>
                            <p>Este es un mensaje generado automáticamente, por favor no respondas a este correo.</p>
                        </td>
                    </tr>

                    <tr>
                        <td
                            style="text-align: center; font-size: 14px; color: #95a5a6; padding-top: 20px; border-top: 1px solid #ecf0f1;">
                            <p>© {{ date('Y') }} {{ env('APP_NAME') }}. Todos los derechos reservados.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>