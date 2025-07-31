@extends('layouts.argon')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Detalles del Paciente {{ $paciente->nombre_completo }}</h5>
                    <a target="_blank" href="{{ route('pacientes.exportar', $paciente) }}" class="btn btn-sm btn-danger">
                        Exportar en PDF</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-user-injured me-2"></i>Información del Paciente</h5>

                    <small class="d-block mb-1">
                        <i class="fas fa-address-card text-success me-1"></i>
                        Estás en la sección de vista detallada del <strong>paciente</strong>. Aquí puedes revisar todos los
                        datos relacionados a su historial clínico.
                    </small>

                    <small class="d-block mb-1">
                        <i class="fas fa-notes-medical text-primary me-1"></i>
                        Se muestra la información personal del paciente, junto con sus <strong>antecedentes
                            familiares</strong>, <strong>diagnósticos</strong> y el <strong>criterio clínico</strong>
                        registrado.
                    </small>

                    <small class="d-block mb-1">
                        <i class="fas fa-stethoscope text-info me-1"></i>
                        También se visualiza el <strong>listado completo de tratamientos</strong> que ha tenido el paciente,
                        cada uno con sus respectivas <strong>citas asociadas</strong>.
                    </small>

                    <small class="d-block mb-1">
                        <i class="fas fa-calendar-check text-warning me-1"></i>
                        Si una <strong>cita ha sido gestionada</strong>, se habilitan botones de exportación para obtener:
                        <ul class="mb-1 mt-1 ps-4">
                            <li><i class="fas fa-file-pdf text-danger me-1"></i><strong>Hoja de Evolución</strong></li>
                            <li><i class="fas fa-flask text-danger me-1"></i><strong>Hoja de Laboratorio</strong> (si hay
                                exámenes)</li>
                        </ul>
                    </small>

                    <small class="d-block">
                        <i class="fas fa-info-circle text-secondary me-1"></i>
                        Esta vista es útil para tener un panorama general del paciente y tomar decisiones clínicas basadas
                        en el historial registrado.
                    </small>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-md-6">
            <div class="card mt-3">
                <div class="card-body">

                    @include('pacientes.datos_paciente')
                </div>
            </div>
        </div>

        <div class="col-md-6 mt-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold border-bottom pb-1 mb-2">Antecedentes Familiares</h6>

                    @if($paciente->antecedentes->isNotEmpty())
                        <div class="list-group">
                            @foreach ($paciente->antecedentes as $antecedente)
                                <div class="list-group-item list-group-item-action mb-1 shadow-sm rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><strong>Antecedente:</strong>
                                            {{ $antecedente->catalogoAntecedente->catalogo_descripcion }}</span>
                                        <span class="badge bg-secondary">
                                            {{ $antecedente->catalogoFamiliar->catalogo_descripcion }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No se registraron antecedentes familiares.</p>
                    @endif


                </div>
            </div>
        </div>

        <div class="col-md-12 mt-3">

            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold border-bottom pb-1 mb-2">Diagnosticos</h6>
                    @include('pacientes.diagnosticos')

                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold border-bottom pb-1 mb-2">Tratamientos</h6>
                    @include('pacientes.tratamientos')

                </div>
            </div>
        </div>
    </div>



@endsection