@extends('layouts.argon')

@section('content')
    <style>
        .fade-in {
            opacity: 1;
            transition: opacity 0.4s ease-in;
        }

        .fade-out {
            opacity: 0;
            transition: opacity 0.4s ease-out;
        }

        .fade-element {
            opacity: 0;
        }
    </style>

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
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form id="form-gestion-cita" method="POST">
        @csrf
        <input type="hidden" name="planes_json" id="planes_json">
        <input type="hidden" name="datos_json" id="datos_json">
        <input type="hidden" name="objetivos_json" id="objetivos_json">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_gestionLabel">Gestionar Cita</h5>
                    <button type="button" class="btn-close txt-black" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> <i class="fas fa-times t"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-end mb-3 gap-3">
                        <small class="text-muted"><strong>Fecha:</strong> {{ now()->format('d-m-Y') }}</small>
                        <small class="text-muted"><strong>Hora:</strong> {{ now()->format('H:i') }}</small>
                    </div>


                    <div class="row mb-2">
                        <h5>Datos del personal asignado</h5>
                        <div class="col-md-6"><strong>Médico/Personal:</strong> {{ Auth::user()->nombre_completo }}</div>
                        <div class="col-md-6"><strong>Especialidad:</strong> {{ Auth::user()->name }}</div>
                    </div>

                    <div class="row mb-2">
                        <h5>Datos del paciente</h5>
                        <div class="col-md-6"><strong>Nombre del paciente:</strong>
                            {{ $tratamiento->paciente->nombre_completo }}</div>
                        <div class="col-md-3"><strong>Edad:</strong>
                            {{ \Carbon\Carbon::parse($tratamiento->paciente->fecha_nacimiento)->age }} años</div>
                        <div class="col-md-3"><strong>Género:</strong>
                            {{ $tratamiento->paciente->genero == 'M' ? 'Masculino' : 'Femenino' }}</div>
                    </div>

                    <div class="accordion-1">

                        <h5> Evolución Medica (SOAP)</h5>
                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                <div class="accordion" id="accordionRental">
                                    <div class="accordion-item mb-3">
                                        <h5 class="accordion-header" id="headingOne">
                                            <button
                                                class="accordion-button border-bottom font-weight-bold collapsed bg-green_tarjetas_claro"
                                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                aria-expanded="false" aria-controls="collapseOne">
                                                S/ (Subjetivo)
                                                <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3"
                                                    aria-hidden="true"></i>
                                                <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3"
                                                    aria-hidden="true"></i>
                                            </button>
                                        </h5>
                                        <div id="collapseOne" class="accordion-collapse collapse"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionRental" style="">
                                            <div class="accordion-body ">

                                                <span>[Sintomas referidos por el paciente, molestias, evolución desde la
                                                    ultima visita] </span> <label class="text-primary"
                                                    data-bs-toggle="tooltip" data-bs-placement="right"
                                                    title="Agrega dinámicamente los síntomas que te indique el paciente. Todos los síntomas que agregues se asociarán a la cita que estás gestionando.">
                                                    <i class="fas fa-info-circle" style="cursor: pointer;"></i>
                                                </label>
                                                <div id="contenido-1" class="mb-3"></div>
                                                <div class="d-flex mb-3">
                                                    <input type="text" id="input-text" class="form-control me-2"
                                                        placeholder="Escribe un sintoma">
                                                    <button type="button" class="btn btn-sm btn-primary" id="btn-agregar">Agregar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item mb-3">
                                        <h5 class="accordion-header" id="headingTwo">
                                            <button
                                                class="accordion-button border-bottom font-weight-bold bg-green_tarjetas_claro"
                                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                aria-expanded="false" aria-controls="collapseTwo">
                                                O/ (Objetivo):
                                                <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3"
                                                    aria-hidden="true"></i>
                                                <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3"
                                                    aria-hidden="true"></i>
                                            </button>
                                        </h5>
                                        <div id="collapseTwo" class="accordion-collapse collapse"
                                            aria-labelledby="headingTwo" data-bs-parent="#accordionRental">
                                            <div class="accordion-body opacity-8">
                                                <div id="objetivos-container" class="mb-4">

                                                    <div class="d-flex align-items-center mb-3">

                                                        <span>[Signos vitales, hallazgos del examen físico, resultados de
                                                            laboratorio, imágenes, etc.]</span>
                                                        <label class="text-primary mb-0" data-bs-toggle="tooltip"
                                                            data-bs-placement="right"
                                                            title="Haz clic en uno de los botones para ingresar el valor correspondiente del paciente. Puedes añadir varios datos haciendo clic en diferentes botones.">
                                                            <i class="fas fa-info-circle" style="cursor: pointer;"></i>
                                                        </label>
                                                    </div>
                                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @foreach ($objetivos as $obj)
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-primary btn-objetivo px-2 py-1"
                                                                    data-codigo="{{ $obj->catalogo_codigo }}"
                                                                    data-nombre="{{ $obj->catalogo_descripcion }}">
                                                                    {{ $obj->catalogo_descripcion }}
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <h6 class="text-muted">Objetivos agregados:</h6>
                                                    <div id="resumen-container"></div>

                                                    <div id="input-container"></div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                                            title="Ingresa tu valoración médica en los campos que se muestran a continuación. Esta información será clave para el seguimiento del diagnóstico del paciente.">
                                                            <i class="fas fa-info-circle" style="cursor: pointer;"></i>
                                                        </label>
                                                <div class="row mt-2">
                                                    <div class="col-md-4">
                                                    <select class="form-select select2 @error('cod_diagnostico') is-invalid @enderror"
                                                            id="cod_diagnostico" name="cod_diagnostico">
                                                        <option value="" selected>Seleccione un diagnostico</option>
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
                                    <div class="accordion-item mb-3">
                                        <h5 class="accordion-header" id="headingFour">
                                            <button
                                                class="accordion-button border-bottom font-weight-bold bg-green_tarjetas_claro"
                                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                                aria-expanded="false" aria-controls="collapseFour">
                                                P/ (Plan / Tratamiento):
                                                <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3"
                                                    aria-hidden="true"></i>
                                                <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3"
                                                    aria-hidden="true"></i>
                                            </button>
                                        </h5>
                                        <div id="collapseFour" class="accordion-collapse collapse"
                                            aria-labelledby="headingFour" data-bs-parent="#accordionRental">
                                            <div class="accordion-body text-sm opacity-8">
                                           
                                            <div id="plan-tratamiento-contenedor" class="mt-2 mb-2">
                                               
                                            </div>
                                           
                                            <select id="tipo-plan" class="form-select me-2" name="cod_plan">
                                                  <option value="-1">Seleccione un plan</option>
                                            @foreach ($planes as $plan)
                                                        <option value="{{ $plan->catalogo_codigo }}"
                                                            {{ old('cod_plan', $tratamiento->cod_plan ?? '') == $plan->catalogo_codigo ? 'selected' : '' }}>
                                                            {{ $plan->catalogo_descripcion }}
                                                        </option>
                                                    @endforeach
                                            </select>

                                            <div class="d-flex mb-3 mt-2">
                                            <input type="text" id="descripcion-plan" class="form-control me-2" placeholder="Descripción">
                                            <button type="button" class="btn btn-sm btn-primary" id="btn-agregar-plan">Agregar</button>
                                            </div>


                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn bg-gradient-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-gestionar').forEach(boton => {
            boton.addEventListener('click', function () {
                const citaId = this.dataset.citaId;

                // Actualizar acción del formulario con el ID dinámico
                const form = document.getElementById('form-gestion-cita');
                const baseRoute = "{{ route('citas.gestion', ['cita' => 'ID_REEMPLAZO']) }}";
                form.action = baseRoute.replace('ID_REEMPLAZO', citaId);
            });
        });
    });
</script>
    <script>
const planes = [];

document.getElementById('btn-agregar-plan').addEventListener('click', () => {
  
    const select = document.getElementById('tipo-plan');

const tipo = select.value; // obtiene el value
const tipoNombre = select.options[select.selectedIndex].text; // obtiene el texto visible

const descripcion = document.getElementById('descripcion-plan').value.trim();

if (tipo === '-1') {
    alertify.error('Por favor, seleccione un tipo de plan.');
    return;
}
if (descripcion === '') {
    alertify.error('Por favor, agregue una descripción.');
    return;
}
if (descripcion !== '') {
    planes.push({ tipo, descripcion, tipoNombre });
    renderPlanes();
    document.getElementById('descripcion-plan').value = '';
}
});

function renderPlanes() {
    const contenedor = $('#plan-tratamiento-contenedor');
    contenedor.empty();

    planes.forEach((plan, index) => {
        const div = $(`
            <div class="mb-2 border p-2 rounded d-flex justify-content-between align-items-center">
                <div><strong>${plan.tipoNombre}:</strong> ${plan.descripcion}</div>
                <i class="fas fa-trash text-danger" style="cursor:pointer;"></i>
            </div>
        `);

        // Evento para eliminar con animación
        div.find('i').on('click', function () {
            div.fadeOut(300, function () {
                planes.splice(index, 1);
                renderPlanes();
            });
        });

        document.getElementById('planes_json').value = JSON.stringify(planes);

        contenedor.append(div.hide().fadeIn(300));
    });
}

function eliminarPlan(index) {
    planes.splice(index, 1);
    renderPlanes();
}

    $(document).ready(function () {
       
        $('#btn-agregar_diagnostico').on('click', function (e) {
            e.preventDefault();
            const descripcion = $('#diagnostico').val().trim();

            if (descripcion === '') {
                alertify.error('El campo no puede estar vacío.');
                return;
            }

            $.ajax({
                url: '{{ route("diagnostico.store_ajax") }}',
                method: 'POST',
                data: {
                    descripcion: descripcion,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        // Agregar nueva opción al select
                        const newOption = new Option(response.descripcion, response.id, true, true);
                        $('#cod_diagnostico').append(newOption).trigger('change');

                        $('#diagnostico').val('');
                        alertify.success('Diagnóstico agregado con éxito.');
                    }
                },
                error: function (xhr) {
                    alertify.error('Error al guardar el diagnóstico.');
                }
            });
        });
    });
</script>
    <script>
    $(document).ready(function () {
    $('.select2').select2({
        width: '100%',
        placeholder: 'Seleccione un diagnostico',
        dropdownParent: $('#modal_gestion') // Reemplaza con el ID real del modal
    });
});
</script>

    <script>
        const datos = [];

        function renderizarDatos() {
            const contenedor = $('#contenido-1');
            contenedor.empty();

            datos.forEach((item, index) => {
                const p = $(`
                                                                                                                                                                                      <p class="mb-2 mt-2 d-flex justify-content-between align-items-center border rounded p-2">
                                                                                                                                                                                        <span>${item}</span>
                                                                                                                                                                                        <i class="fas fa-trash text-danger" style="cursor:pointer;"></i>
                                                                                                                                                                                      </p>
                                                                                                                                                                                    `);

                // Evento para borrar con fadeOut
                p.find('i').on('click', function () {
                    p.fadeOut(400, function () {
                        datos.splice(index, 1);
                        renderizarDatos();
                    });
                });
                document.getElementById('datos_json').value = JSON.stringify(datos);
                contenedor.append(p.hide().fadeIn(400));
            });
        }

        $('#btn-agregar').on('click', () => {

            const valor = $('#input-text').val().trim();
            if (valor === '') {
                alertify.error('Por favor ingrese un sintoma');
                return;
            };

            datos.push(valor);
            renderizarDatos();

            $('#input-text').val('').focus();
            console.log('Array actual:', datos);
        });



    </script>

    <script>
        const botones = document.querySelectorAll('.btn-objetivo');
        const inputContainer = document.getElementById('input-container');
        const resumenContainer = document.getElementById('resumen-container');

        const datosObjetivos = [];

        function renderResumen() {
            resumenContainer.innerHTML = '';

            datosObjetivos.forEach((item, index) => {
                const div = document.createElement('div');
                div.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'border', 'rounded', 'p-2', 'mb-2');

                div.innerHTML = `
                                                    <div><strong>${item.nombre}</strong>: ${item.valor}</div>
                                                    <i class="fas fa-trash text-danger" style="cursor:pointer;"></i>
                                                `;

                // Agregar animación al borrar
                div.querySelector('i').addEventListener('click', () => {
                    $(div).fadeOut(300, () => {
                        datosObjetivos.splice(index, 1);
                        renderResumen();
                    });
                });

                // Ocultar y luego mostrar con fadeIn
                $(div).hide();
                resumenContainer.appendChild(div);
                $(div).fadeIn(300);
            });

            document.getElementById('objetivos_json').value = JSON.stringify(datosObjetivos);  
        }

        botones.forEach(btn => {
            btn.addEventListener('click', () => {
                const codigo = btn.dataset.codigo;
                const nombre = btn.dataset.nombre;
                const existe = datosObjetivos.some(d => d.codigo === codigo);

                // Si ya fue agregado, confirmar antes de mostrar el input
                if (existe) {
                    alertify.confirm(
                        'Editar valor',
                        `Ya ingresaste un valor para "${nombre}". ¿Deseas reemplazarlo?`,
                        function () {
                            mostrarInput(codigo, nombre);
                        },
                        function () {
                            // Cancelado, no hacer nada
                        }
                    );
                } else {
                    mostrarInput(codigo, nombre);
                }
            });
        });

        function mostrarInput(codigo, nombre) {
            inputContainer.innerHTML = '';

            const label = document.createElement('label');
            label.textContent = `Valor para ${nombre}:`;
            label.classList.add('form-label');

            const input = document.createElement('input');
            input.type = 'text';
            input.classList.add('form-control', 'mb-2');
            input.placeholder = `Ingrese valor para ${nombre}`;

            // Si ya existe, precarga el valor actual
            const existente = datosObjetivos.find(d => d.codigo === codigo);
            if (existente) input.value = existente.valor;

            const btnAgregar = document.createElement('button');
            btnAgregar.textContent = 'Agregar';
            btnAgregar.classList.add('btn', 'btn-primary');

            inputContainer.appendChild(label);
            inputContainer.appendChild(input);
            inputContainer.appendChild(btnAgregar);

            btnAgregar.addEventListener('click', () => {
                const valor = input.value.trim();
                if (valor === '') {
                    alertify.error('Por favor ingrese un valor')
                    return;
                }

                const index = datosObjetivos.findIndex(d => d.codigo === codigo);
                if (index >= 0) {
                    datosObjetivos[index].valor = valor;
                } else {
                    datosObjetivos.push({ codigo, nombre, valor });
                }

                inputContainer.innerHTML = '';
                renderResumen();
            });
        }


    </script>
@endsection