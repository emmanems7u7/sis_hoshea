@extends('layouts.argon')

@section('content')
    @if($tiempo_cambio_contraseña != 1)

        <div class="row">
            {{-- Pacientes registrados --}}
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Pacientes</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $totalPacientes }}
                                    </h5>
                                    <p class="mb-0 text-muted">Total en el sistema</p>
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
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Tratamientos activos</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $tratamientosActivos }}
                                    </h5>
                                    <p class="mb-0 text-muted">En curso actualmente</p>
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
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Citas</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $citasActivas }}
                                    </h5>
                                    <p class="mb-0 text-muted">Confirmadas</p>
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
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Personal activo</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $personalActivo }}
                                    </h5>
                                    <p class="mb-0 text-muted">Usuarios con acceso</p>
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

        <div class="row">
            <div class="col-md-6">
                <div class="card mt-3">
                    <div class="card-body">
                        Proximas Citas
                        <div class="row g-3">
                            @foreach ($citas as $cita)
                                <div class="col-md-12">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-body d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 class="mb-1">
                                                    {{ $cita->paciente->nombre_completo }}
                                                </h5>

                                                <p class="mb-0">
                                                    <strong>Tratamiento:</strong>
                                                    {{ $cita->tratamiento->nombre ?? '-' }}
                                                </p>

                                                <p class="mb-0">
                                                    <strong>Duración:</strong>
                                                    {{ $cita->duracion ?? '-' }}
                                                </p>

                                                <p class="mb-2">
                                                    <strong>Estado:</strong>
                                                    {{ ucfirst($cita->estado) }}
                                                </p>
                                                <p class="text-muted small ms-2">Personal Asignado</p>

                                                @foreach ($cita->usuarios as $usuario)
                                                    <span class="badge bg-secondary me-1 mb-1">
                                                        {{ $usuario->name }} ({{ $usuario->pivot->rol_en_cita ?? 'N/A' }})
                                                    </span>
                                                @endforeach
                                            </div>

                                            <span class="text-muted small ms-2">
                                                {{ $cita->fecha_hora->format('Y-m-d H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <div class="card">
                    <div class="card-body">
                        Tratamientos Activos
                        <div class="row g-3">
                            @foreach ($tratamientos as $tratamiento)
                                <div class="col-md-6">
                                    <div class="card h-100 position-relative shadow-sm">


                                        <span class="badge bg-primary position-absolute top-0 end-0 m-2">
                                            {{ ucfirst($tratamiento->estado) }}
                                        </span>

                                        <div class="card-body">
                                            <h5 class="mb-1">
                                                Paciente: {{ $tratamiento->paciente->nombre_completo }}
                                            </h5>

                                            <p class="mb-0">
                                                <strong>Tratamiento:</strong> {{ $tratamiento->nombre }}
                                            </p>

                                            <p class="mb-0">
                                                <strong>Inicio:</strong> {{ $tratamiento->fecha_inicio->format('Y-m-d') }}
                                            </p>

                                            <p class="mb-0">
                                                <strong>Fin:</strong>
                                                {{ $tratamiento->fecha_fin ? $tratamiento->fecha_fin->format('Y-m-d') : '-' }}
                                            </p>
                                            <p class="mb-0">
                                                <strong>Citas:</strong>
                                                {{ $tratamiento->citas->count() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

        </div>


    @else

        <div class="alert alert-warning" role="alert">
            <strong>!Alerta!</strong> Debes actualizar tu contraseña
        </div>

    @endif
@endsection