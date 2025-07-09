@extends('layouts.argon')

@section('content')

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h5>Administración de tratamiento <strong>{{ $tratamiento->nombre }}</strong></h5>

                    <a href="" class="btn-primary btn btn-sm">Finalizar Gestión</a>
                    <a href="" class="btn-warning btn btn-sm">Guardar Avance</a>
                    <a href="" class="btn-dark btn btn-sm">Añadir observación</a>


                </div>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="">Información</h5>
                    <small>- En esta sección puedes gestionar el tratamiento de un paciente. Debes administrar las citas que
                        corresponden a la fecha actual <strong>{{ now()->format('d-m-Y') }}</strong> y las siguientes en su
                        fecha correspondiente</small>


                    <br>

                    <small>- Utiliza el botón <strong>"Finalizar Gestión"</strong> para finalizar la administración del
                        tratamiento, Esto marcará el tratamiento como completado, <strong>Se recomienda hacerlo cuando se
                            gestione todas las citas asignadas</strong>
                    </small>
                    <br>

                </div>
            </div>
        </div>
    </div>



    <div class="row mb-3">

        <!-- DATOS DE CITAS -->
        <div class="col-md-6 mb-3 order-2 order-md-1">
            <div class="card">
                <div class="card-body">


                    @include('tratamientos.tarjetas_citas', ['botones' => true])

                </div>
            </div>
        </div>

        <!-- DATOS DEL TRATAMIENTO -->
        <div class="col-md-6 order-1 order-md-2 mb-2">
            <div class="card shadow-sm">
                <div class="card-body py-2">
                    <h6 class="fw-bold border-bottom pb-1 mb-2">Datos del Tratamiento</h6>
                    <div class="row mb-1 small">
                        <div class="col-5 text-muted">Inicio:</div>
                        <div class="col-7 fw-semibold">{{ $tratamiento->fecha_inicio->format('d-m-Y') }}</div>
                    </div>
                    <div class="row mb-1 small">
                        <div class="col-5 text-muted">Fin:</div>
                        <div class="col-7 fw-semibold">{{ $tratamiento->fecha_fin->format('d-m-Y') }}</div>
                    </div>
                    <div class="row mb-1 small">
                        <div class="col-5 text-muted">Observaciones:</div>
                        <div class="col-7 fw-semibold">{{ $tratamiento->observaciones ?? 'No tiene observación' }}
                        </div>
                    </div>
                    <div class="row mb-1 small">
                        <div class="col-5 text-muted">Estado:</div>
                        <div class="col-7 fw-semibold">{{ $tratamiento->estado }}</div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm mt-3">
                <div class="card-body py-2">
                    <h6 class="fw-bold border-bottom pb-1 mb-2">Datos del Paciente</h6>
                    <div class="row mb-1 small">
                        <div class="col-5 text-muted">Nombre:</div>
                        <div class="col-7 fw-semibold">{{ $tratamiento->paciente->nombre_completo }}</div>
                    </div>
                    <div class="row mb-1 small">
                        <div class="col-5 text-muted">Género:</div>
                        <div class="col-7 fw-semibold">
                            {{ $tratamiento->paciente->genero == 'M' ? 'Masculino' : 'Femenino' }}
                        </div>
                    </div>
                    <div class="row mb-1 small">
                        <div class="col-5 text-muted">Teléfono:</div>
                        <div class="col-7 fw-semibold">{{ $tratamiento->paciente->telefono ?? 'No tiene teléfono' }}
                        </div>
                    </div>
                    <div class="row mb-1 small">
                        <div class="col-5 text-muted">Celular:</div>
                        <div class="col-7 fw-semibold">{{ $tratamiento->paciente->telefono_movil }}</div>
                    </div>
                    <div class="row mb-1 small">
                        <div class="col-5 text-muted">Email:</div>
                        <div class="col-7 fw-semibold">{{ $tratamiento->paciente->email }}</div>
                    </div>
                    <div class="row mb-1 small">
                        <div class="col-5 text-muted">Dirección:</div>
                        <div class="col-7 fw-semibold">{{ $tratamiento->paciente->direccion }}</div>
                    </div>
                    <div class="row mb-1 small">
                        <div class="col-5 text-muted">{{ $tratamiento->paciente->tipo_documento }}:</div>
                        <div class="col-7 fw-semibold">{{ $tratamiento->paciente->numero_documento }}</div>
                    </div>
                </div>
            </div>
        </div>


    </div>




    <!-- Modal -->
    <div class="modal fade" id="modal_gestion" tabindex="-1" role="dialog" aria-labelledby="modal_gestionLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_gestionLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn bg-gradient-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>
@endsection