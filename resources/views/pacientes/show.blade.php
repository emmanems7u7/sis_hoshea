@extends('layouts.argon')

@section('content')

    <div class="card">
        <div class="card-body">
            <h5>Detalles del Paciente {{ $paciente->nombre_completo }}</h5>
            <a href="" class="btn btn-sm btn-danger"> Exportar en PDF</a>
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