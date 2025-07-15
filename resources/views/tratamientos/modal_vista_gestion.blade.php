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
                        <h6> Diagnostico Identificado en la cita</h6>
                        <strong>
                            <p id="vista_cod_diagnostico" class="form-control-plaintext"></p>
                        </strong>
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

                    <div id="vista_planes" class="list-group"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function verGestion(citaId) {
        const urlVerGestionBase = "{{ route('citas.ver_gestion', ['cita' => '__ID__']) }}";
        const url = urlVerGestionBase.replace('__ID__', citaId);

        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                // Mostrar valores en texto
                $('#vista_cod_diagnostico').text(data.cod_diagnostico || '-');
                $('#vista_criterio_clinico').text(data.criterio_clinico || '-');
                $('#vista_evolucion_diagnostico').text(data.evolucion_diagnostico || '-');

                // Datos simples
                const contenedorDatos = $('#vista_datos');
                contenedorDatos.empty();



                (data.datos || []).forEach(d => {
                    contenedorDatos.append(`
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-2">
            <div class="border rounded bg-green_tarjetas_claro px-3 py-2 shadow-sm small text-muted">
                ${d}
            </div>
        </div>
 `);
                });



                const listaObjetivos = $('#vista_objetivos');
                listaObjetivos.empty();

                (data.objetivos || []).forEach(obj => {
                    const tarjeta = $(`
        <div class="col-12 col-sm-6 col-lg-4">
        
            <div class="border rounded px-3 py-2 mb-2 bg-green_tarjetas_claro shadow-sm">
            <div class="d-flex justify-content-between align-items-center small">
                    <span class="fw-semibold text-muted">${obj.nombre}</span>
                    <span class="text-dark">${obj.valor}</span>
                </div>
            </div>
        </div>
    `);

                    listaObjetivos.append(tarjeta);
                });

                const listaPlanes = $('#vista_planes');
                listaPlanes.empty();

                const gruposPlanes = {};

                (data.planes || []).forEach(plan => {
                    if (!gruposPlanes[plan.tipoNombre]) {
                        gruposPlanes[plan.tipoNombre] = [];
                    }
                    gruposPlanes[plan.tipoNombre].push(plan.descripcion);
                });

                // Contenedor general en filas
                const fila = $('<div class="row g-2"></div>');

                for (const tipoNombre in gruposPlanes) {
                    const descripciones = gruposPlanes[tipoNombre];

                    const tarjeta = $(`
        <div class="col-12 col-md-6 col-lg-4">
            <div class="border rounded bg-green_tarjetas_claro shadow-sm p-2 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="small text-muted">${tipoNombre}</strong>
                    <span class="badge bg-light text-dark">${descripciones.length}</span>
                </div>
                <div class="d-flex flex-column gap-1 contenido-descripciones"></div>
            </div>
        </div>
    `);

                    const contenedorDescripciones = tarjeta.find('.contenido-descripciones');

                    descripciones.forEach(descripcion => {
                        contenedorDescripciones.append(`
            <div class="small border rounded bg-light px-2 py-1">
                ${descripcion}
            </div>
        `);
                    });

                    fila.append(tarjeta);
                }

                listaPlanes.append(fila);

                // Mostrar el modal de solo visualización
                const modalEl = document.getElementById('modal_gestion_vista');
                let modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalEl);
                }
                modalInstance.show();
            },
            error: function () {
                alertify.error('Error cargando la gestión para visualizar.');
            }
        });
    }
</script>