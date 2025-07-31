<div class="modal fade" id="modal_gestion_vista" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div
            class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }} p-3">
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

                        @if($tratamiento)
                            @include('tratamientos.datos_tratamiento')
                        @else
                            @include('citas.datos_cita')

                        @endif
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
                            <div class="col-md-6"><label class="text-dark"><strong>Criterio Clínico:</strong></label>
                                <p id="vista_criterio_clinico" class="form-control-plaintext text-black"
                                    style="color:black !important"></p>
                            </div>
                            <div class="col-md-6"><label class="text-dark"><strong>Evolución
                                        Diagnóstico:</strong></label>
                                <p id="vista_evolucion_diagnostico" class="form-control-plaintext text-black"
                                    style="color:black !important"></p>
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

        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Mostrar valores en texto
                document.getElementById('vista_cod_diagnostico').textContent = data.cod_diagnostico || '-';
                document.getElementById('vista_criterio_clinico').textContent = data.criterio_clinico || '-';
                document.getElementById('vista_evolucion_diagnostico').textContent = data.evolucion_diagnostico || '-';

                // Datos simples
                const contenedorDatos = document.getElementById('vista_datos');
                contenedorDatos.innerHTML = '';

                (data.datos || []).forEach(d => {
                    const divCol = document.createElement('div');
                    divCol.className = 'col-12 col-sm-6 col-md-4 col-lg-3 mb-2';

                    const divInner = document.createElement('div');
                    divInner.className = 'border rounded bg-green_tarjetas_claro px-3 py-2 shadow-sm small text-muted';
                    divInner.textContent = d;

                    divCol.appendChild(divInner);
                    contenedorDatos.appendChild(divCol);
                });

                // Lista de objetivos
                const listaObjetivos = document.getElementById('vista_objetivos');
                listaObjetivos.innerHTML = '';

                (data.objetivos || []).forEach(obj => {
                    const divCol = document.createElement('div');
                    divCol.className = 'col-12 col-sm-6 col-lg-4';

                    const divCard = document.createElement('div');
                    divCard.className = 'border rounded px-3 py-2 mb-2 bg-green_tarjetas_claro shadow-sm';

                    const divFlex = document.createElement('div');
                    divFlex.className = 'd-flex justify-content-between align-items-center small';

                    const spanNombre = document.createElement('span');
                    spanNombre.className = 'fw-semibold text-muted';
                    spanNombre.textContent = obj.nombre;

                    const spanValor = document.createElement('span');
                    spanValor.className = 'text-dark';
                    spanValor.textContent = obj.valor;

                    divFlex.appendChild(spanNombre);
                    divFlex.appendChild(spanValor);

                    divCard.appendChild(divFlex);
                    divCol.appendChild(divCard);
                    listaObjetivos.appendChild(divCol);
                });

                // Lista de planes
                const listaPlanes = document.getElementById('vista_planes');
                listaPlanes.innerHTML = '';

                const gruposPlanes = {};
                (data.planes || []).forEach(plan => {
                    if (!gruposPlanes[plan.tipoNombre]) {
                        gruposPlanes[plan.tipoNombre] = [];
                    }
                    gruposPlanes[plan.tipoNombre].push(plan.descripcion);
                });

                // Contenedor general en filas
                const fila = document.createElement('div');
                fila.className = 'row g-2';

                for (const tipoNombre in gruposPlanes) {
                    const descripciones = gruposPlanes[tipoNombre];

                    const divCol = document.createElement('div');
                    divCol.className = 'col-12 col-md-6 col-lg-4';

                    const divCard = document.createElement('div');
                    divCard.className = 'border rounded bg-green_tarjetas_claro shadow-sm p-2 h-100';

                    const divHeader = document.createElement('div');
                    divHeader.className = 'd-flex justify-content-between align-items-center mb-2';

                    const strong = document.createElement('strong');
                    strong.className = 'small text-muted';
                    strong.textContent = tipoNombre;

                    const spanBadge = document.createElement('span');
                    spanBadge.className = 'badge text-dark';
                    spanBadge.textContent = descripciones.length;

                    divHeader.appendChild(strong);
                    divHeader.appendChild(spanBadge);

                    const divContenido = document.createElement('div');
                    divContenido.className = 'd-flex flex-column gap-1 contenido-descripciones';

                    descripciones.forEach(descripcion => {
                        const descDiv = document.createElement('div');
                        descDiv.className = 'small border rounded bg-light px-2 py-1 text-dark';
                        descDiv.textContent = descripcion;
                        divContenido.appendChild(descDiv);
                    });

                    divCard.appendChild(divHeader);
                    divCard.appendChild(divContenido);
                    divCol.appendChild(divCard);
                    fila.appendChild(divCol);
                }

                listaPlanes.appendChild(fila);

                // Mostrar el modal de solo visualización
                const modalEl = document.getElementById('modal_gestion_vista');
                let modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalEl);
                }
                modalInstance.show();
            })
            .catch(() => {
                alertify.error('Error cargando la gestión para visualizar.');
            });
    }

</script>