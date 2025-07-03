@php
    //  --- CONFIGURABLES RÁPIDOS ---
    $brandColor = '#0d6efd';              // color primario
    $logoURL = asset('img/logo.png');  // ruta del logotipo (300 × 80 máx.)
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Notificación')</title>

    <!--[if mso]>
      <style type="text/css">
        body, table, td {font-family: Arial, Helvetica, sans-serif !important;}
      </style>
    <![endif]-->
</head>

<body style="margin:0;padding:0;background:#f4f4f4">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f4f4f4">
        <tr>
            <td align="center">

                <!-- ======= HEADER ======= -->
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px">
                    <tr>
                        <td style="background:{{ $brandColor }};padding:24px;text-align:center">
                            <img src="{{ $logoURL }}" alt="Logo"
                                style="max-width:200px;height:auto;display:block;margin:auto">
                        </td>
                    </tr>
                </table>

                <!-- ======= BODY WRAPPER ======= -->
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;background:#ffffff;border-radius:8px;
                          box-shadow:0 0 6px rgba(0,0,0,.08);overflow:hidden">
                    <tr>
                        <td style="padding:32px">

                            @yield('content')

                        </td>
                    </tr>
                </table>

                <!-- ======= FOOTER ======= -->
                <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                    style="max-width:600px;margin-top:24px">
                    <tr>
                        <td style="font-size:12px;color:#999999;text-align:center;padding:16px">
                            © {{ now()->year }} {{ config('app.name') }} •
                            <a href="{{ config('app.url') }}" style="color:#999;text-decoration:none">Sitio web</a> •
                            <a href="mailto:soporte@example.com" style="color:#999;text-decoration:none">Contacto</a>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>

</html>