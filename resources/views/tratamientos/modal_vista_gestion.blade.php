<div class="modal fade" id="modal_gestion_vista" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content p-3">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title mb-0">Detalle de la Cita Completada</h5>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('logo.png') }}" alt="Logo" style="height: 40px; width: auto;">
                    <span class="ms-2 fw-bold">{{ config('app.name', 'Laravel') }}</span>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">


                    <div class="col-md-6 mb-2">


                        @include('tratamientos.datos_paciente')
                    </div>

                    <div class="col-md-6 mb-2">

                        @include('tratamientos.datos_tratamiento')
                    </div>


                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <h6> Diagnosticos Identificados en el tratamiento</h6>
                        <p id="vista_cod_diagnostico" class="form-control-plaintext"></p>
                    </div>
                </div>


                <div class="card shadow-sm mb-2 bg-green_tarjetas_claro">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-md-6"><label><strong>Criterio Clínico:</strong></label>
                                <p id="vista_criterio_clinico" class="form-control-plaintext"></p>
                            </div>
                            <div class="col-md-6"><label><strong>Evolución Diagnóstico:</strong></label>
                                <p id="vista_evolucion_diagnostico" class="form-control-plaintext"></p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mb-2">
                    <h5> Sintomas:</h5>
                    <div class="row" id="vista_datos"></div>
                </div>
                <div class="mb-2">
                    <h5> Objetivos:</h5>
                    <div class="row" id="vista_objetivos"></div>
                </div>
                <div class="mb-2">
                    <h5> Planes:</h5>
                    <ul id="vista_planes" class="list-group"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>