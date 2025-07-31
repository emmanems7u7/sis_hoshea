<div class="accordion-item mb-3">
                                        <h5 class="accordion-header" id="headingThree">
                                            <button
                                                class="accordion-button border-bottom font-weight-bold bg-green_tarjetas_claro"
                                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                aria-expanded="false" aria-controls="collapseThree">
                                                A/ (Análisis / Diagnóstico):
                                                <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3"
                                                    aria-hidden="true"></i>
                                                <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3"
                                                    aria-hidden="true"></i>

                                            </button>
                                        </h5>
                                        <div id="collapseThree" class="accordion-collapse collapse"
                                            aria-labelledby="headingThree" data-bs-parent="#accordionRental">
                                            <div class="accordion-body text-sm opacity-8">
                                                <span>[Diagnóstico médico actual, evolución del diagnóstico previo, criterios clínicos]</span>
                                                <label class="text-primary mb-0" data-bs-toggle="tooltip"
                                                            data-bs-placement="right"
                                                            title="Ingresa tu valoración médica abajo. Selecciona un diagnostico existente o crea uno nuevo.">
                                                            <i class="fas fa-info-circle" style="cursor: pointer;"></i>
                                                        </label>
                                                <div class="row mt-2">
                                                    <div class="col-md-4">
                                                    <span class="mb-3">Selecciona un diagnostico</span>

                                                    <select class="form-select mt-3 @error('cod_diagnostico') is-invalid @enderror"
                                                            id="cod_diagnostico" name="cod_diagnostico" >
                                                        <option value="" selected>Seleccione un diagnóstico</option>
                                                        @foreach ($diagnosticos as $diagnostico)
                                                            <option value="{{ $diagnostico->catalogo_codigo }}"
                                                                {{ old('cod_diagnostico', $tratamiento->cod_diagnostico ?? '') == $diagnostico->catalogo_codigo ? 'selected' : '' }}>
                                                                {{ $diagnostico->catalogo_descripcion }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    </div>
                                                    <div class="col-md-8">
                                                    <div class="d-flex align-items-center mb-3">

                                                    <span>No encuentras el diagnostico? puedes crearlo aqui!</span>
                                                    <label class="text-primary mb-0" data-bs-toggle="tooltip"
                                                        data-bs-placement="right"
                                                        title="Los diagnósticos que registres estarán disponibles para reutilizar en futuras citas. Si necesitas eliminar alguno, podrás hacerlo desde el módulo de catálogos">
                                                        <i class="fas fa-info-circle" style="cursor: pointer;"></i>
                                                    </label>
                                                    
                                                    </div>

                                                        <div class="d-flex mb-3">
                                                        <input type="text" id="diagnostico" class="form-control me-2"
                                                            placeholder="Escribe un diagnostico">
                                                        <button type="button" class="btn btn-sm btn-primary" id="btn-agregar_diagnostico">Agregar</button>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                           
                                                <div class="row mt-2">
                                                    <h6>Agrega tu criterio clínico y la evolución de tu diagnóstico</h6>

                                                    <div class="col-md-12 mb-3">
                                                        <label for="criterio_clinico" class="form-label">Criterio clínico</label>
                                                        <textarea class="form-control" id="criterio_clinico" name="criterio_clinico" rows="3"
                                                            placeholder="Describe el criterio clínico con base en signos, síntomas y hallazgos.">{{ old('criterio_clinico') }}</textarea>
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <label for="evolucion_diagnostico" class="form-label">Evolución del diagnóstico</label>
                                                        <textarea class="form-control" id="evolucion_diagnostico" name="evolucion_diagnostico" rows="3"
                                                            placeholder="Describe la evolución clínica del paciente.">{{ old('evolucion_diagnostico') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <script>

let tomSelectDiagnostico;

document.addEventListener('DOMContentLoaded', function () {
    tomSelectDiagnostico = new TomSelect('#cod_diagnostico', {
        placeholder: 'Seleccione un diagnóstico',
        allowEmptyOption: true
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const btnAgregar = document.getElementById('btn-agregar_diagnostico');
    const inputDiagnostico = document.getElementById('diagnostico');

    btnAgregar.addEventListener('click', function (e) {
        e.preventDefault();

        const descripcion = inputDiagnostico.value.trim();
        if (descripcion === '') {
            alertify.error('El campo no puede estar vacío.');
            return;
        }

        fetch('{{ route("diagnostico.store_ajax") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ descripcion: descripcion })
        })
        .then(response => {
            if (!response.ok) throw new Error('Error en la solicitud');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                tomSelectDiagnostico.addOption({ value: data.id, text: data.descripcion });
                tomSelectDiagnostico.setValue(data.id);
                inputDiagnostico.value = '';
                alertify.success('Diagnóstico agregado con éxito.');
            }
        })
        .catch(() => {
            alertify.error('Error al guardar el diagnóstico.');
        });
    });
});

                                 
</script>