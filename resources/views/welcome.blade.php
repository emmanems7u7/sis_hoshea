<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="Free Bootstrap Theme by BootstrapMade.com">
    <meta name="keywords"
        content="free website templates, free bootstrap themes, free template, free bootstrap, free website template">

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway|Candal">
    <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('landing/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

@php
    use App\Models\ServicioLanding;
    use App\Models\AcercaLanding;
    use App\Models\User;
    use App\Models\Configuracion;
    use App\Models\ConfCorreo;

    $config_correo = ConfCorreo::first();

    $acercaLanding = AcercaLanding::get();
    $serviciosLanding = ServicioLanding::where('estado', 1)->get();
    $config = Configuracion::first();
    $diasAtencion = json_decode($config->dias_atencion, true) ?? [];

    $diasSemana = [
        'lunes' => 'Lunes',
        'martes' => 'Martes',
        'miercoles' => 'Miércoles',
        'jueves' => 'Jueves',
        'viernes' => 'Viernes',
        'sabado' => 'Sábado',
        'domingo' => 'Domingo',
    ];
    $roles = is_array($config->roles_landing)
        ? $config->roles_landing
        : json_decode($config->roles_landing, true);

    $usuarios = User::role($roles)->get();


    $coords = explode(',', $config->geolocalizacion ?? '');

    $lat = isset($coords[0]) ? trim($coords[0]) : null;
    $lng = isset($coords[1]) ? trim($coords[1]) : null;

    // Opcional: asignar valores por defecto si no existen
    if ($lat === null || $lng === null) {
        // Manejo de error, log o valores predeterminados
        $lat = '0';
        $lng = '0';
    }


@endphp

<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
    <!--banner-->
    <section id="banner" class="banner">
        <div class="bg-color">
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="container">
                    <div class="col-md-12">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#"><img src="" class="img-responsive"
                                    style="width: 150px; margin-top: -16px;"></a>
                        </div>
                        <div class="collapse navbar-collapse navbar-right" id="myNavbar">
                            <ul class="nav navbar-nav">
                                <li class="active"><a href="#banner">INICIO</a></li>
                                <li class=""><a href="#service">SERVICIO</a></li>
                                <li class=""><a href="#about">ACERCA DE NOSOTROS </a></li>
                                <!--li class=""><a href="#testimonial">TESTIMONIO</a></li-->
                                <li class=""><a href="#contact">CONTACTO</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="container">
                <div class="row">
                    <div class="banner-info">
                        <div class="banner-logo text-center">
                            <img src="{{ asset('landing/img/logo1.png') }}" width="400" height="200"
                                class="img-responsive">
                        </div>
                        <div class="banner-text text-center">
                            <h1 class="white">"TU BIENESTAR ES NUESTRA PRIORIDAD, CON CALIDEZ Y COMPROMISO"</h1>
                            <p>"La salud es la riqueza real."</p>
                        </div>
                        <div class="banner-logo text-center">
                            <img src="{{ asset('landing/img/hospital.png') }}" width="50" height="50"
                                class="img-responsive">
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--/ banner-->
    <!--service-->
    <section id="service" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3" style="    padding-bottom: 20px;">
                    <div class="col-md-12">
                        <h2 class="ser-title">{{ $config->titulo_servicio ?? '' }}</h2>
                        <hr class="botm-line">
                        {!! $config->descripcion_servicio ?? '' !!}
                    </div>
                </div>
                <div class="col-md-7 mt-3">
                    <div class="row">

                        @foreach ($serviciosLanding as $servicio)
                            <div class="col-md-6 ">


                                <div class="service-info" style=" ">
                                    <div class="icon">
                                        <i class="{{ $servicio->icono }}"></i>
                                    </div>
                                    <div class="icon-info">
                                        <h4>{{ $servicio->titulo }}</h4>
                                        {!!  $servicio->descripcion  !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const servicios = Array.from(document.querySelectorAll('.service-info'));
                            const total = servicios.length;

                            // Tomamos todos excepto los dos últimos
                            const serviciosParaIgualar = servicios.slice(0, total - 2);

                            let maxHeight = 0;

                            serviciosParaIgualar.forEach(el => {
                                el.style.minHeight = 'unset'; // reset para medición exacta
                                const height = el.offsetHeight;
                                if (height > maxHeight) maxHeight = height + 20;
                            });

                            serviciosParaIgualar.forEach(el => {
                                el.style.minHeight = maxHeight + 'px';
                            });

                            // Los 2 últimos quedan con su altura natural, no se les toca
                        });
                    </script>
                </div>

            </div>
        </div>
    </section>
    <!--/ service-->
    <!--cta-->
    <section id="cta-1" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="schedule-tab">
                    <div class="col-md-4 col-sm-4 bor-left">
                        <div class="mt-boxy-color"></div>
                        <div class="medi-info">
                            <h3>Caso de emergencia</h3>
                            <p>EN CASOS DE EMERGENCIA, ES CRUCIAL COMUNICAR INFORMACIÓN DE MANERA CLARA Y CONCISA.
                                LLAMAR AL NÚMERO DE EMERGENCIAS O LLAMAR AL {{ $config->celular }} .
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4 mt-boxy-3">
                        <div class="mt-boxy-color"></div>
                        <div class="time-info">
                            <h3>Horario</h3>
                            <table border="1" style="border-collapse: collapse; width: 100%;">
                                <tbody>
                                    @foreach ($diasSemana as $clave => $nombre)
                                        @php
                                            $activo = isset($diasAtencion[$clave]) && $diasAtencion[$clave]['activo'] === true;
                                            $inicio = $activo ? $diasAtencion[$clave]['inicio'] ?? 'Sin hora' : null;
                                            $fin = $activo ? $diasAtencion[$clave]['fin'] ?? 'Sin hora' : null;
                                        @endphp
                                        <tr>
                                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $nombre }}</td>
                                            <td style="border: 1px solid #ddd; padding: 8px;">
                                                @if ($activo)
                                                    {{ $inicio }} - {{ $fin }}
                                                @else
                                                    SOLO EMERGENCIAS
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--cta-->
    <!--about-->
    <section id="about" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="section-title">
                        <h2 class="head-title lg-line">
                            {{ $config->titulo_acercade }}

                        </h2>
                        <hr class="botm-line">

                        {!!  $config->descripcion_acercade !!}
                        <!--a href="" style="color: #0cb8b6; padding-top:10px;">Know more..</a-->
                    </div>
                </div>
                <div class="col-md-9 col-sm-8 col-xs-12">
                    <div style="visibility: visible;" class="col-sm-9 more-features-box">
                        @foreach ($acercaLanding as $acerca)
                            <div class="more-features-box-text">
                                <div class="more-features-box-text-icon"> <i class="{{ $acerca->icono }}"
                                        aria-hidden="true"></i> </div>
                                <div class="more-features-box-text-description">

                                    {!! $acerca->descripcion !!}
                                </div>
                            </div>

                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ about-->
    <!--doctor team-->
    <section id="doctor-team" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="ser-title">¡Conozca a nuestros médicos!</h2>
                    <hr class="botm-line">
                </div>


                @foreach ($usuarios as $user)
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="thumbnail">

                            @if ($user->foto_perfil)
                                <img src="{{ asset($user->foto_perfil) }}" alt="profile_image" class="team-img">
                            @else
                                <img src="{{ asset('update/imagenes/user.jpg') }}" alt="profile_image" class="team-img">
                            @endif


                            <div class="caption">
                                <h3>{{ $user->nombre_completo }}</h3>
                                <p>{{ $user->roles->first()?->name }}</p>

                            </div>
                        </div>
                    </div>
                @endforeach



                <!--div class="col-md-3 col-sm-3 col-xs-6">
          <div class="thumbnail">
            <img src="img/doctor2.jpg" alt="..." class="team-img">
            <div class="caption">
              <h3>HISTORIAL MEDICO </h3>
              <p>Doctor</p>
             
              <p>Estudio en la unibercidad publica del alto "UPA"</p>
            
              <!--ul class="list-inline">
                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
              </ul-->
                <!--/div>
          </div>
        </div>
    
        <div class="col-md-3 col-sm-3 col-xs-6">
          <!--div class="thumbnail">
            <img src="img/doctor3.jpg" alt="..." class="team-img">
            <div class="caption">
              <h3>Amanda Denyl</h3>
              <p>Doctor</p>
              <ul class="list-inline">
                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
              </ul>
            </div>
          </div-->
                <!--/div>
        <div class="col-md-3 col-sm-3 col-xs-6">
          <!--div class="thumbnail">
            <img src="img/doctor4.jpg" alt="..." class="team-img">
            <div class="caption">
              <h3>Jason Davis</h3>
              <p>Doctor</p>
              <ul class="list-inline">
                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
              </ul>
            </div>
          </div>
        </div-->
            </div>

        </div>
    </section>
    <!--/ doctor team-->
    <!--testimonial-->
    <!--section id="testimonial" class="section-padding">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2 class="ser-title">see what patients are saying?</h2>
          <hr class="botm-line">
        </div>
        <div class="col-md-4 col-sm-4">
          <div class="testi-details">
            <!-- Paragraph -->
    <!--p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          </div>
          <div class="testi-info">
            <!-- User Image -->
    <!--a href="#"><img src="img/thumb.png" alt="" class="img-responsive"></a>
            <!-- User Name -->
    <!--h3>Alex<span>Texas</span></h3>
          </div>
        </div>
        <div class="col-md-4 col-sm-4">
          <div class="testi-details">
            <!-- Paragraph -->
    <!--p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          </div>
          <div class="testi-info">
            <!-- User Image -->
    <!--a href="#"><img src="img/thumb.png" alt="" class="img-responsive"></a>
            <!-- User Name -->
    <!--h3>Alex<span>Texas</span></h3>
          </div>
        </div>
        <div class="col-md-4 col-sm-4">
          <div class="testi-details">
            <!-- Paragraph -->
    <!--p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          </div>
          <div class="testi-info">
            <!-- User Image -->
    <!--a href="#"><img src="img/thumb.png" alt="" class="img-responsive"></a>
            <!-- User Name -->
    <!--h3>Alex<span>Texas</span></h3>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--/ testimonial-->
    <!--cta 2-->
    <section id="cta-2" class="section-padding">
        <div class="container">
            <div class=" row">
                <div class="col-md-2"></div>
                <div class="text-right-md col-md-4 col-sm-4">
                    <h2 class="section-title white lg-line">{{ $config->titulo_presentacion }} </h2>
                </div>
                <br>
                <div class="col-md-4 col-sm-5">
                    {!! $config->descripcion_presentacion !!}
                </div>
            </div>
        </div>
    </section>
    <!--cta-->
    <!--contact-->
    <section id="contact" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="ser-title">Contáctenos</h2>
                    <hr class="botm-line">
                </div>
                <div class="col-md-4 col-sm-4">
                    <h3>Información de contacto</h3>
                    <div class="space"></div>
                    <p>
                        <a href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}" target="_blank">


                            <i class="fa fa-map-marker fa-fw pull-left fa-2x"></i>
                        </a>
                        {{ $config->direccion }}
                    </p>
                    <div class="space"></div>
                    <p><i class="fa fa-envelope-o fa-fw pull-left fa-2x"></i>{{   $config_correo->from_address }}</p>
                    <div class="space"></div>
                    <p><i class="fa fa-phone fa-fw pull-left fa-2x"></i>{{ $config->celular }}</p>
                </div>


                <div class="col-md-8 col-sm-8 marb20">
                    <div class="contact-info">
                        <h3 class="cnt-ttl">¡Tienes alguna consulta! O reservar una cita</h3>
                        <div class="space"></div>
                        <div id="errormessage"></div>

                        <form id="whatsappForm" role="form" class="contactForm" onsubmit="sendToWhatsApp(event)">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control br-radius-zero" id="name"
                                    placeholder="Te llamas" data-rule="minlen:4"
                                    data-msg="Please enter at least 4 chars" required />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control br-radius-zero" name="email" id="email"
                                    placeholder="Su correo electrónico" data-rule="email"
                                    data-msg="Please enter a valid email" required />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control br-radius-zero" name="subject" id="subject"
                                    placeholder="Asunto" data-rule="minlen:4"
                                    data-msg="Please enter at least 8 chars of subject" required />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control br-radius-zero" name="message" rows="5"
                                    data-rule="required" data-msg="Please write something for us" placeholder="Mensaje"
                                    required></textarea>
                                <div class="validation"></div>
                            </div>

                            <div class="form-action">
                                <button type="submit" class="btn btn-form">Enviar mensaje</button>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    function sendToWhatsApp(event) {
                        event.preventDefault();

                        // Obtener los valores del formulario
                        const name = document.getElementById('name').value;
                        const email = document.getElementById('email').value;
                        const subject = document.getElementById('subject').value;
                        const message = document.querySelector('textarea[name="message"]').value;

                        // Formatear el mensaje para WhatsApp
                        const whatsappMessage = `*Nuevo mensaje de contacto*%0A%0A` +
                            `*Nombre:* ${name}%0A` +
                            `*Email:* ${email}%0A` +
                            `*Asunto:* ${subject}%0A` +
                            `*Mensaje:*%0A${message}`;

                        // Reemplaza con tu número de WhatsApp (con código de país, sin + ni espacios)
                        const phoneNumber = '59164156932'; // Ejemplo: Argentina (54) 9 11 1234-5678

                        // Abrir WhatsApp con el mensaje formateado
                        window.open(`https://wa.me/${phoneNumber}?text=${whatsappMessage}`, '_blank');
                    }
                </script>


            </div>
        </div>
    </section>
    <!--/ contact-->
    <!--footer-->
    <footer id="footer">
        <div class="top-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-4 marb20">
                        <div class="ftr-tle">

                        </div>

                    </div>


                </div>
            </div>
        </div>
        <div class="footer-line">
            <div class="container">
                <div class="row">

                </div>
            </div>
        </div>
        </div>
    </footer>
    <!--/ footer-->


    <script src="{{ asset('landing/js/jquery.min.js') }}"></script>
    <script src="{{ asset('landing/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('landing/js/custom.js') }}"></script>
    <script src="{{ asset('landing/contactform/contactform.js') }}"></script>

</body>

</html>