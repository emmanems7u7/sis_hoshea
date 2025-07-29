<!-- Modal -->
<div class="modal fade" id="modal_hoja" tabindex="-1" role="dialog" aria-labelledby="modal_hojaLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div
            class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }} ">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title mb-0">Hoja de laboratorio</h5>
                <div class="d-flex align-items-center">
                    <img src="{{ asset('logo.png') }}" alt="Logo" style="height: 40px; width: auto;">
                    <span class="ms-2 fw-bold">{{ config('app.name', 'Laravel') }}</span>
                </div>
            </div>
            <form id="form_examenes" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold border-bottom pb-1 mb-2">Medico Solicitante:
                                {{ Auth::user()->nombre_completo }}
                            </h6>
                        </div>
                        <div class="col-md-6 text-end">
                            <h6>{{ now() }}</h6>
                        </div>
                    </div>
                    <div class="row">


                        <div class="col-md-8 mb-2">


                            @include('pacientes.datos_paciente')
                        </div>

                        <div class="col-md-4 mb-2">

                            @include('citas.datos_cita')
                        </div>

                    </div>

                    <div class="row">
                        <h6>EXÁMENES SOLICITADOS (Marcar los requeridos)</h6>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="check-todos">
                            <label class="form-check-label fw-bold" for="check-todos">Seleccionar Todos</label>
                        </div>

                        @php
                            $examenesSeleccionados = old('examenes', isset($user) && $user->examenes ? json_decode($user->examenes, true) : []);
                            if (!is_array($examenesSeleccionados)) {
                                $examenesSeleccionados = [];
                            }
                        @endphp

                        <!-- Contenedor del checkbox -->
                        @foreach ($examenes as $examen)
                            <div class="form-check ">
                                <input class="form-check-input check-examen" type="checkbox" name="examenes[]"
                                    id="examen_{{ $examen->catalogo_codigo }}" value="{{ $examen->catalogo_codigo }}"
                                    data-nombre="{{ $examen->catalogo_descripcion }}">
                                <label class="form-check-label" for="examen_{{ $examen->catalogo_codigo }}">
                                    {{ $examen->catalogo_descripcion }}
                                </label>
                            </div>
                        @endforeach
                        <div class="form-check d-flex align-items-center gap-2 mt-2">
                            <input class="form-check-input" type="checkbox" id="examen_otro" name="examen_otro_check"
                                value="otro">

                            <label class="form-check-label" for="examen_otro">Otro:</label>

                            <input type="text" class="form-control form-control-sm" name="examen_otro_texto"
                                id="examen_otro_texto" placeholder="Especifique otro examen" disabled>
                        </div>

                        @error('examenes')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <h6>Vista previa tabla de RESULTADOS DEL LABORATORIO</h6>
                        <div class="mt-3">
                            <table id="tabla-referencia" class="table table-bordered table-sm d-none">
                                <thead>
                                    <tr>
                                        <th>Parámetro</th>
                                        <th>Resultado</th>
                                        <th>Valores de Referencia</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-referencia-body">

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn bg-gradient-primary" id="btn_accion_ex">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function CrearHoja(cita) {
        // Mostrar el modal de solo visualización
        const modalEl = document.getElementById('modal_hoja');
        let modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(modalEl);
        }
        modalInstance.show();

        document.getElementById('btn_accion_ex').textContent = 'Guardar Hoja';
        const form = document.getElementById('form_examenes');
        const baseRoute = @json(route('citas.hoja', ['cita' => 'ID_REEMPLAZO']));
        form.action = baseRoute.replace('ID_REEMPLAZO', cita);

        let methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) {
            methodInput.remove();
        }

    }

    function EditarHoja(cita_id) {

        const rutaExamenes = @json(route('citas.examenes', ['id' => 'ID_TEMP']));

        const url = rutaExamenes.replace('ID_TEMP', cita_id);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Limpiar checkboxes primero
                document.querySelectorAll('.check-examen').forEach(chk => chk.checked = false);

                // Marcar los exámenes seleccionados
                if (Array.isArray(data.examenes)) {
                    data.examenes.forEach(id => {
                        const checkbox = document.querySelector(`#examen_${id}`);
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    });
                }

                // Manejar "Otro"
                const checkOtro = document.getElementById('examen_otro');
                const inputOtro = document.getElementById('examen_otro_texto');

                if (data.examen_otro && data.examen_otro.trim() !== '') {
                    checkOtro.checked = true;
                    inputOtro.disabled = false;
                    inputOtro.value = data.examen_otro;
                } else {
                    checkOtro.checked = false;
                    inputOtro.disabled = true;
                    inputOtro.value = '';
                }
                const modalEl = document.getElementById('modal_hoja');
                let modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalEl);
                }
                modalInstance.show();


                // Cambia texto del botón de acción si existe
                const btnAccion = document.getElementById('btn_accion_ex');
                if (btnAccion) {
                    btnAccion.textContent = 'Actualizar Hoja';
                }

                // Genera la ruta con citaId
                const editarRouteTemplate = @json(route('citas.update_hoja', ['cita' => 'ID_REEMPLAZO']));
                const editarRoute = editarRouteTemplate.replace('ID_REEMPLAZO', cita_id);

                // Actualiza el formulario
                const form = document.getElementById('form_examenes');
                if (form) {
                    form.action = editarRoute;

                    // Crea o actualiza el input _method a PUT
                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        form.appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                }
            })
            .catch(error => {
                console.error('Error al obtener los exámenes:', error);
                alertify.error('No se pudo cargar la hoja de exámenes.');
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkTodos = document.getElementById('check-todos');
        const checks = document.querySelectorAll('.check-examen');

        // Función para actualizar el estado del checkbox "Todos"
        function actualizarCheckTodos() {
            const total = checks.length;
            const checkedCount = Array.from(checks).filter(chk => chk.checked).length;
            checkTodos.checked = (checkedCount === total);
        }

        // Al cargar la página, inicializa el estado del checkbox "Todos"
        actualizarCheckTodos();

        // Evento click en "Seleccionar Todos"
        checkTodos.addEventListener('change', function () {
            checks.forEach(chk => chk.checked = checkTodos.checked);
        });

        // Evento en cada checkbox individual
        checks.forEach(chk => {
            chk.addEventListener('change', function () {
                if (!this.checked) {
                    checkTodos.checked = false;
                } else {
                    // Si todos están marcados, marcar "Todos"
                    actualizarCheckTodos();
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkTodos = document.getElementById('check-todos');
        const checkboxes = document.querySelectorAll('.check-examen');
        const tabla = document.getElementById('tabla-referencia');
        const cuerpoTabla = document.getElementById('tabla-referencia-body');

        function agregarFila(id, nombre) {
            const filaId = `fila_examen_${id}`;
            if (!document.getElementById(filaId)) {
                const fila = document.createElement('tr');
                fila.id = filaId;
                fila.innerHTML = `
                <td>${nombre}</td>
                <td></td>
                <td></td>
            `;
                cuerpoTabla.appendChild(fila);
            }
        }

        function eliminarFila(id) {
            const fila = document.getElementById(`fila_examen_${id}`);
            if (fila) fila.remove();
        }

        function actualizarVisibilidadTabla() {
            tabla.classList.toggle('d-none', cuerpoTabla.children.length === 0);
        }

        // Evento: Seleccionar Todos
        checkTodos.addEventListener('change', function () {
            if (this.checked) {
                checkboxes.forEach(chk => {
                    chk.checked = true;
                    agregarFila(chk.value, chk.dataset.nombre);
                });
            } else {
                checkboxes.forEach(chk => {
                    chk.checked = false;
                    eliminarFila(chk.value);
                });
            }
            actualizarVisibilidadTabla();
        });

        // Evento: Checkbox individual
        checkboxes.forEach(chk => {
            chk.addEventListener('change', function () {
                if (this.checked) {
                    agregarFila(this.value, this.dataset.nombre);
                } else {
                    eliminarFila(this.value);
                }

                // Actualizar estado del checkbox general
                checkTodos.checked = [...checkboxes].every(ch => ch.checked);

                actualizarVisibilidadTabla();
            });
        });

        // Marcar "Seleccionar Todos" si todos ya vienen seleccionados
        checkTodos.checked = [...checkboxes].every(ch => ch.checked);

        // Cargar tabla con valores seleccionados desde backend (si hay)
        checkboxes.forEach(chk => {
            if (chk.checked) {
                agregarFila(chk.value, chk.dataset.nombre);
            }
        });
        actualizarVisibilidadTabla();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkOtro = document.getElementById('examen_otro');
        const inputOtro = document.getElementById('examen_otro_texto');

        checkOtro.addEventListener('change', function () {
            inputOtro.disabled = !this.checked;
            if (!this.checked) {
                inputOtro.value = '';
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkOtro = document.getElementById('examen_otro');
        const inputOtro = document.getElementById('examen_otro_texto');
        const tabla = document.getElementById('tabla-referencia');
        const cuerpoTabla = document.getElementById('tabla-referencia-body');
        const filaOtroId = 'fila_examen_otro';

        // Habilitar/deshabilitar input al marcar "otro"
        checkOtro.addEventListener('change', function () {
            if (this.checked) {
                inputOtro.disabled = false;
                inputOtro.focus();
                if (inputOtro.value.trim() !== '') {
                    agregarFilaOtro(inputOtro.value.trim());
                }
            } else {
                inputOtro.disabled = true;
                inputOtro.value = '';
                eliminarFila(filaOtroId);
                actualizarTablaVisible();
            }
        });

        // Escuchar cuando escriba en el input y ya esté marcado
        inputOtro.addEventListener('input', function () {
            if (checkOtro.checked) {
                eliminarFila(filaOtroId);
                if (this.value.trim() !== '') {
                    agregarFilaOtro(this.value.trim());
                }
                actualizarTablaVisible();
            }
        });

        function agregarFilaOtro(nombre) {
            if (!document.getElementById(filaOtroId)) {
                const fila = document.createElement('tr');
                fila.id = filaOtroId;
                fila.innerHTML = `
                <td>${nombre}</td>
                <td></td>
                <td></td>
            `;
                cuerpoTabla.appendChild(fila);
            }
        }

        function eliminarFila(id) {
            const fila = document.getElementById(id);
            if (fila) fila.remove();
        }

        function actualizarTablaVisible() {
            tabla.classList.toggle('d-none', cuerpoTabla.children.length === 0);
        }
    });
</script>