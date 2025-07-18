<div class="row mt-2">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <div class="accordion" id="accordionCalendarios">

                    <!-- Acordeón: Citas -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingCitas">
                            <button class="accordion-button collapsed border-bottom" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseCitas" aria-expanded="false"
                                aria-controls="collapseCitas">
                                Citas Activas
                                <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3"
                                    aria-hidden="true"></i>
                                <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3"
                                    aria-hidden="true"></i>
                            </button>

                        </h2>
                        <div id="collapseCitas" class="accordion-collapse collapse hide" aria-labelledby="headingCitas"
                            data-bs-parent="#accordionCalendarios">
                            <div class="accordion-body">
                                <div id="calendarioCitas"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Acordeón: Tratamientos -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTratamientos">
                            <button class="accordion-button collapsed border-bottom" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseTratamientos" aria-expanded="false"
                                aria-controls="collapseTratamientos">
                                Tratamientos Activos
                                <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3"
                                    aria-hidden="true"></i>
                                <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3"
                                    aria-hidden="true"></i>
                            </button>
                        </h2>
                        <div id="collapseTratamientos" class="accordion-collapse collapse"
                            aria-labelledby="headingTratamientos" data-bs-parent="#accordionCalendarios">
                            <div class="accordion-body">
                                <div id="calendarioTratamientos"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>
</div>


<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modalDetalleBody">
                <!-- Aquí se carga el detalle del evento -->
            </div>
        </div>
    </div>
</div>

<script>
    function getToolbarConfig() {
        if (window.innerWidth < 576) {
            // Móvil: toolbar simple
            return {
                left: 'prev,next',
                center: 'title',
                right: 'today'
            };
        } else {
            // Escritorio: toolbar completo
            return {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            };
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // CALENDARIO DE CITAS
        const calendarioCitas = new FullCalendar.Calendar(document.getElementById('calendarioCitas'), {
            initialView: 'timeGridWeek',
            locale: 'es',
            initialView: 'dayGridMonth',
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            headerToolbar: getToolbarConfig(),
            aspectRatio: 1.35,

            events: [
                @foreach ($citas as $cita)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    {
                        title: '{{ $cita->paciente->nombre_completo }}',
                        start: '{{ $cita->fecha_hora }}',
                        extendedProps: {
                            tratamiento: '{{ $cita->tratamiento->nombre ?? "Sin tratamiento" }}',
                            duracion: '{{ $cita->duracion ?? "N/A" }}',
                            estado: '{{ ucfirst($cita->estado) }}',
                            usuarios: `{!! implode(', ', $cita->usuarios->map(fn($u) => $u->name . ' (' . ($u->pivot->rol_en_cita ?? "N/A") . ')')->toArray()) !!}`
                        }
                    },
                @endforeach
                                                                                                                                                                                                                                                                                                                                                            ],
            eventClick: function (info) {
                const e = info.event;
                const detalle = `
                                                                                                                                                                                                                                                                                                                                                                    <strong>Paciente:</strong> ${e.title}<br>
                                                                                                                                                                                                                                                                                                                                                                    <strong>Inicio:</strong> ${e.start.toLocaleString()}<br>
                                                                                                                                                                                                                                                                                                                                                                    <strong>Tratamiento:</strong> ${e.extendedProps.tratamiento}<br>
                                                                                                                                                                                                                                                                                                                                                                    <strong>Duración (min):</strong> ${e.extendedProps.duracion}<br>
                                                                                                                                                                                                                                                                                                                                                                    <strong>Estado:</strong> ${e.extendedProps.estado}<br>
                                                                                                                                                                                                                                                                                                                                                                    <strong>Personal:</strong> ${e.extendedProps.usuarios}
                                                                                                                                                                                                                                                                                                                                                                `;
                document.getElementById('modalDetalleBody').innerHTML = detalle;
                new bootstrap.Modal(document.getElementById('modalDetalle')).show();
            }
        });
        calendarioCitas.render();

        // CALENDARIO DE TRATAMIENTOS
        const calendarioTratamientos = new FullCalendar.Calendar(document.getElementById('calendarioTratamientos'), {
            initialView: 'dayGridMonth',
            locale: 'es',
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            headerToolbar: getToolbarConfig(),
            aspectRatio: 1.35,
            events: [
                @foreach ($tratamientos as $tratamiento)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    {
                        title: '{{ $tratamiento->paciente->nombre_completo }} - {{ $tratamiento->nombre }}',
                        start: '{{ $tratamiento->fecha_inicio->format('Y-m-d') }}',
                        end: '{{ $tratamiento->fecha_fin ? $tratamiento->fecha_fin->addDay()->format('Y-m-d') : $tratamiento->fecha_inicio->format('Y-m-d') }}',
                        color: '{{ $tratamiento->estado == "activo" ? "#28a745" : "#6c757d" }}',
                        extendedProps: {
                            estado: '{{ $tratamiento->estado }}',
                            citas: '{{ $tratamiento->citas->count() }}'
                        }
                    },
                @endforeach
                                                                                                                                                                                                                                                                                                                                                            ],
            eventClick: function (info) {
                const t = info.event;
                const detalle = `
                                                                                                                                                                                                                                                                                                                                                                    <strong>Tratamiento:</strong> ${t.title}<br>
                                                                                                                                                                                                                                                                                                                                                                    <strong>Inicio:</strong> ${t.start.toLocaleDateString()}<br>
                                                                                                                                                                                                                                                                                                                                                                    <strong>Fin:</strong> ${t.end ? t.end.toLocaleDateString() : "N/A"}<br>
                                                                                                                                                                                                                                                                                                                                                                    <strong>Estado:</strong> ${t.extendedProps.estado}<br>
                                                                                                                                                                                                                                                                                                                                                                    <strong>N° Citas:</strong> ${t.extendedProps.citas}
                                                                                                                                                                                                                                                                                                                                                                `;
                document.getElementById('modalDetalleBody').innerHTML = detalle;
                new bootstrap.Modal(document.getElementById('modalDetalle')).show();
            }
        });

        // Renderizar al abrir el acordeón de tratamientos
        document.getElementById('collapseTratamientos').addEventListener('shown.bs.collapse', function () {
            calendarioTratamientos.render();
        });

        // Opcional: re-render de citas al volver a abrir su acordeón
        document.getElementById('collapseCitas').addEventListener('shown.bs.collapse', function () {
            calendarioCitas.render();
        });
    });
</script>