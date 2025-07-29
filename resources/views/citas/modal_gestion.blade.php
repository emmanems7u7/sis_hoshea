<div class="modal fade" id="modal_gestion" tabindex="-1" role="dialog" aria-labelledby="modal_gestionLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

        <form id="form-gestion-cita" method="POST">
            @csrf
            <input type="hidden" name="planes_json" id="planes_json">
            <input type="hidden" name="datos_json" id="datos_json">
            <input type="hidden" name="objetivos_json" id="objetivos_json">
            <div
                class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">

                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="modal_gestionLabel">Gestionar Cita</h5>
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('logo.png') }}" alt="Logo" style="height: 40px; width: auto;">
                        <span class="ms-2 fw-bold">{{ config('app.name', 'Laravel') }}</span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-end mb-3 gap-3">
                        <small class="text-muted"><strong>Fecha:</strong> {{ now()->format('d-m-Y') }}</small>
                        <small class="text-muted"><strong>Hora:</strong> {{ now()->format('H:i') }}</small>
                    </div>


                    <div class="row mb-2">
                        <h6 class="fw-bold border-bottom pb-1">Datos del personal asignado</h6>
                        <div class="col-md-6"><strong>Médico/Personal:</strong> {{ Auth::user()->nombre_completo }}
                        </div>
                        <div class="col-md-6"><strong>Especialidad:</strong> {{ Auth::user()->name }}</div>
                    </div>

                    <div class="row mb-2">
                        <h6 class="fw-bold border-bottom pb-1">Datos del paciente</h6>
                        <div class="col-md-6"><strong>Nombre del paciente:</strong>
                            {{ $paciente->nombre_completo }}</div>
                        <div class="col-md-3"><strong>Edad:</strong>
                            {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años</div>
                        <div class="col-md-3"><strong>Género:</strong>
                            {{ $paciente->genero == 'M' ? 'Masculino' : 'Femenino' }}</div>
                    </div>

                    <div class="accordion-1">
                        <div class="d-flex align-items-center">
                            <h5> Evolución Medica (SOAP)</h5> <label class="text-primary" data-bs-toggle="tooltip"
                                data-bs-placement="right"
                                title="Registra por pasos la evaluación siguiendo el orden presentado debajo">
                                <i class="fas fa-info-circle" style="cursor: pointer; font-size: 18px;"></i>
                            </label>

                        </div>
                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                <div class="accordion" id="accordionRental">

                                    @include('tratamientos.acordion1')
                                    @include('tratamientos.acordion2')
                                    @include('tratamientos.acordion3')
                                    @include('tratamientos.acordion4')

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" id="btnCerrarModal">Cerrar</button>
                    <button type="submit" id="btn_accion" class="btn bg-gradient-primary">Guardar Gestión</button>
                </div>
            </div>
    </div>
    </form>
</div>