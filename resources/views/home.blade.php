@extends('layouts.argon')

@section('content')

    @if($tiempo_cambio_contraseña != 1)

        <div class="row">
            {{-- Pacientes registrados --}}
            <div class="col-md-3 mb-4  ">
                <div class="card text-black">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Pacientes</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $totalPacientes }}
                                    </h5>
                                    <p class="mb-0 text-black">Total en el sistema</p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center rounded-circle">
                                    <i class="fas fa-user-injured text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tratamientos activos --}}
            <div class="col-md-3 mb-4">
                <div class="card text-black">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Tratamientos activos</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $tratamientosActivos }}
                                    </h5>
                                    <p class="mb-0 text-black">En curso actualmente</p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center rounded-circle">
                                    <i class="fas fa-notes-medical text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Citas activas --}}
            <div class="col-md-3 mb-4">
                <div class="card text-black">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Citas</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $citasActivas }}
                                    </h5>
                                    <p class="mb-0 text-black">Confirmadas</p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center rounded-circle">
                                    <i class="fas fa-calendar-check text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Personal activo --}}
            <div class="col-md-3 mb-4">
                <div class="card text-black">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Personal activo</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $personalActivo }}
                                    </h5>
                                    <p class="mb-0 text-black">Usuarios con acceso</p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow text-center rounded-circle">
                                    <i class="fas fa-user-md text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>



        @include('home_admin')



    @else

        <div class="alert alert-warning" role="alert">
            <strong>!Alerta!</strong> Debes actualizar tu contraseña
        </div>

    @endif
@endsection