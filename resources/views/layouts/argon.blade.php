<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png')  }}">
    <link rel="icon" type="image/png" href="{{ asset('logo.png')  }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Nucleo Icons -->
    <link href="{{ asset('argon/css/nucleo-icons.css')  }}" rel="stylesheet" />
    <link href="{{ asset('argon/css/nucleo-svg.css')  }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('argon/css/argon-dashboard.css?v=2.1.0')  }}" rel="stylesheet" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" crossorigin="" />
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery (si aún no está incluido) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>


    @vite(['resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    @php
        use App\Models\Seccion;
        use Carbon\Carbon;
        use App\Models\ConfiguracionCredenciales;
        use App\Models\Configuracion;

        $secciones = Seccion::with('menus')->orderBy('posicion')->get();
        $config = ConfiguracionCredenciales::first();
        $configuracion = Configuracion::first();
       
        if (Auth::user()->usuario_fecha_ultimo_password) {
            $ultimoCambio = Carbon::parse(Auth::user()->usuario_fecha_ultimo_password);

            $diferenciaDias = (int) $ultimoCambio->diffInDays(Carbon::now());

            if ($diferenciaDias >= $config->conf_duracion_max) {
                $tiempo_cambio_contraseña = 1;
            } else {
                $tiempo_cambio_contraseña = 2;
            }
        } else {
            $tiempo_cambio_contraseña = 1;
        }

    @endphp
</head>

<body class="g-sidenav-show  bg-white2">
    <div class="min-height-300 bg-green_fondo  text-black position-absolute w-100"></div>
    <aside
        class="sidenav bg-white  text-black navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="{{ route('home') }}">
             <img src="{{ asset('logo.png')}}" style="" alt="">
                <span class="ms-1 font-weight-bold">{{ config('app.name', 'Laravel') }}</span>
            </a>
        </div>
        
        <div class="collapse  text-black navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
            <li class="nav-item d-flex flex-column align-items-center">

            @if (Auth::user()->foto_perfil)
            <img src="{{ asset(Auth::user()->foto_perfil) }}" alt="Foto de perfil" class="rounded-circle" style="width: 115px; height: 115px; object-fit: cover;">
            @else
            <img src="{{ asset('update/imagenes/user.jpg') }}" alt="Foto de perfil" class="rounded-circle" style="width: 115px; height: 115px; object-fit: cover;">             
            @endif

            <p class="ps-3 ms-3 nav-link-text ms-1" style="font-size: 14px; text-align: center;">
                    {{ Auth::user()->usuario_nombres }} {{ Auth::user()->usuario_app }} {{ Auth::user()->usuario_apm }}
            </p>
            </li>
                @foreach(Auth::user()->roles as $role) 
                            <p class="ps-3 ms-3 nav-link-text ms-1" style="font-size: 12px;">
                                {{$role->name;}}

                            </p>
                 @endforeach

        
            <li class="nav-item  text-black">
                    <a class="nav-link active" href="{{ route('home') }}">

                        <span class="ps-3 ms-3 nav-link-text ms-1 text-black">Inicio</span>
                    </a>
                </li>
                
                <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.actualizar.contraseña') }}">

                            <span class="ps-3 ms-3 nav-link-text ms-1  text-black">Actualizar contraseña</span>
                        </a>
                </li>
            @if( $tiempo_cambio_contraseña != 1)
     
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('perfil') }}">
                        <span class="ps-3 ms-3 nav-link-text ms-1  text-black">Perfil</span>
                    </a>
                </li>
                @role('admin')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('menus.index') }}">

                        <span class="ps-3 ms-3 nav-link-text ms-1  text-black">Gestión de menus</span>
                    </a>
                </li>
                @endrole

                <ul id="secciones-list" class="list-unstyled" {{ $configuracion->mantenimiento ? 'data-draggable="false"' : 'data-draggable="true"' }}>
                    @foreach ($secciones as $seccion)
                        @can($seccion->titulo)
                            <li class="seccion-item mb-3 p-2 text-black" data-id="{{ $seccion->id }}">
                                <div class=" text-black d-flex align-items-center {{ $configuracion->mantenimiento ? 'text-warning' : '' }}">
                                    <i class="{{ $seccion->icono }} me-2"></i>
                                    <h6 class=" text-black m-0 text-uppercase text-xs font-weight-bolder  {{ $configuracion->mantenimiento ? 'text-warning' : '' }}">{{ $seccion->titulo }}</h6>
                                </div>

                                <ul class="list-unstyled ms-4 mt-2">
                                    @foreach ($seccion->menus as $menu)
                                        @can($menu->nombre)
                                            <li class="nav-item text-black">
                                                <a class="nav-link" href="{{ route($menu->ruta) }}">
                                                    <span class="text-black nav-link-text">{{ $menu->nombre }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    @endforeach
                                </ul>
                            </li>
                        @endcan
                    @endforeach
                </ul>
        @endif

        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <div
                                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-sign-out-alt text-dark text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1 text-blackv">Salir</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
        </li>
           
</ul>
        </div>
<!-- CDN de SortableJS -->
@if($configuracion->mantenimiento == 1)
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    const lista = document.getElementById('secciones-list');

    new Sortable(lista, {
        animation: 150,
        onEnd: function () {
            const orden = Array.from(document.querySelectorAll('.seccion-item'))
                .map((el, index) => ({
                    id: el.dataset.id,
                    posicion: index + 1
                }));

            fetch('{{ route("secciones.ordenar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ orden })
            }).then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alertify.success(data.message);
                } else {
                    alertify.error(data.message || 'Ocurrió un error al ordenar');
                }
            })
        }
    });


</script>
@endif
    </aside>
    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        @foreach ($breadcrumb as $key => $crumb)
                            @if ($key == count($breadcrumb) - 1)
                                
                                <li class="breadcrumb-item text-sm text-white active" aria-current="page">{{ $crumb['name'] }}</li>
                            @else
                             
                                <li class="breadcrumb-item text-sm">
                                    <a class="opacity-5 text-white" href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ol>
                    
                </nav>

            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container">
        <div class="main-content position-relative max-height-vh-100 h-100">
        @foreach (['status' => 'success', 'error' => 'error', 'warning' => 'warning'] as $msg => $type)
            @if(session($msg))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        alertify.set('notifier','position', 'top-right');
                        alertify.{{ $type }}(@json(session($msg)));
                    });
                </script>
            @endif
        @endforeach

        @yield('content')
          </div>
           
        </div>

        
    </main>
    
 
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" crossorigin=""></script>
    <!--   Core JS Files   -->
    <script src="{{asset('argon/js/core/popper.min.js')}}"></script>
    <script src="{{asset('argon/js/core/bootstrap.min.js')}}"></script>

    <script src="{{asset('argon/js/plugins/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('argon/js/plugins/smooth-scrollbar.min.js')}}"></script>
    <script src="{{asset('argon/js/plugins/chartjs.min.js')}}"></script>

<style>
    .alertify .ajs-modal {
    display: flex !important;
    justify-content: center;
    align-items: center;
}

.alertify .ajs-dialog {
    margin: 0 auto !important;

    transform: translateY(-40%) !important;
}
</style>
    <script>

        alertify.defaults.theme.ok = "btn btn-danger";  
        alertify.defaults.theme.cancel = "btn btn-secondary";
        alertify.defaults.theme.input = "form-control";  
        alertify.defaults.glossary.title = "Confirmar acción"; 
        alertify.defaults.transition = "zoom";             
      
        
        function confirmarEliminacion(formId, mensaje = '¿Estás seguro de que deseas eliminar este elemento?') {
            alertify.confirm(
                'Confirmar eliminación',
                mensaje,
                function () {
                    document.getElementById(formId).submit();
                },
                function () {
                    alertify.error('Eliminación cancelada');
                }
            ).set('labels', { ok: 'Eliminar', cancel: 'Cancelar' });
        }

        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }

       
    </script>

    <script>
        var ctx1 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
        new Chart(ctx1, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Mobile apps",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#5e72e4",
                    backgroundColor: gradientStroke1,
                    borderWidth: 3,
                    fill: true,
                    data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                    maxBarThickness: 6

                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#fbfbfb',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#ccc',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    </script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{asset('argon/js/argon-dashboard.min.js?v=2.1.0')}}"></script>
</body>

</html>