<div class="accordion-item mb-3">
    <h5 class="accordion-header" id="headingFour">
        <button class="accordion-button border-bottom font-weight-bold bg-green_tarjetas_claro" type="button"
            data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
            P/ (Plan / Tratamiento):
            <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
            <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
        </button>
    </h5>
    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
        data-bs-parent="#accordionRental">
        <div class="accordion-body text-sm opacity-8">
            <span>[Tratamiento indicado, estudios complementarios, seguimiento, recomendaciones]</span>
            <label class="text-primary mb-0" data-bs-toggle="tooltip" data-bs-placement="right" title="Selecciona un plan del catálogo y proporciona una descripción relacionada.
¿Necesitas más opciones? Puedes crear nuevos planes desde el módulo de Catálogo.">
                <i class="fas fa-info-circle" style="cursor: pointer;"></i>
            </label>
            <div id="plan-tratamiento-contenedor" class="mt-2 mb-2">

            </div>

            <select id="tipo-plan" class="form-select me-2" name="cod_plan">
                <option value="-1">Seleccione un plan</option>
                @foreach ($planes as $plan)
                    <option value="{{ $plan->catalogo_codigo }}" {{ old('cod_plan', $tratamiento->cod_plan ?? '') == $plan->catalogo_codigo ? 'selected' : '' }}>
                        {{ $plan->catalogo_descripcion }}
                    </option>
                @endforeach
            </select>

            <div class="d-flex mb-3 mt-2">
                <textarea id="descripcion-plan" class="form-control me-2" placeholder="Descripción" rows="3"></textarea>
                <div class="d-flex align-items-start">
                    <button type="button" class="btn btn-sm btn-primary" id="btn-agregar-plan">Agregar</button>
                </div>
            </div>


        </div>
    </div>
</div>

<script>
    var planes = [];

    document.getElementById('btn-agregar-plan').addEventListener('click', () => {

        const select = document.getElementById('tipo-plan');

        const tipo = select.value;
        const tipoNombre = select.options[select.selectedIndex].text;

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
                <div class="flex-grow-1">
                    <strong>${plan.tipoNombre}:</strong> <span class="descripcion-plan">${plan.descripcion}</span>
                </div>
                <i class="fas fa-edit text-primary me-3" style="cursor:pointer;"></i>
                <i class="fas fa-trash text-danger" style="cursor:pointer;"></i>
            </div>
        `);

            // Evento para eliminar con animación
            div.find('.fa-trash').on('click', function () {
                div.fadeOut(300, function () {
                    planes.splice(index, 1);
                    renderPlanes();
                });
            });

            // Evento para editar descripción inline con textarea
            div.find('.fa-edit').on('click', function () {
                const icon = $(this);
                const descripcionSpan = div.find('.descripcion-plan');

                // Evitar abrir textarea si ya está en modo edición
                if (div.find('textarea').length > 0) return;

                const descripcionActual = descripcionSpan.text();

                // Crear textarea en vez de input
                const textarea = $(`<textarea class="form-control form-control-sm" rows="4">${descripcionActual}</textarea>`);
                descripcionSpan.replaceWith(textarea);
                textarea.focus();

                // Cambiar icono editar a check (guardar)
                icon.removeClass('fa-edit text-primary').addClass('fa-check text-success');

                // Cambiar evento a guardar
                icon.off('click').on('click', function () {
                    const nuevoDescripcion = textarea.val().trim();
                    if (nuevoDescripcion === '') {
                        alertify.error('La descripción no puede estar vacía.');
                        textarea.focus();
                        return;
                    }
                    // Actualizar array
                    planes[index].descripcion = nuevoDescripcion;

                    // Restaurar span con nuevo texto
                    textarea.replaceWith(`<span class="descripcion-plan">${nuevoDescripcion}</span>`);

                    // Cambiar icono a editar
                    icon.removeClass('fa-check text-success').addClass('fa-edit text-primary');

                    // Reasignar evento editar
                    icon.off('click').on('click', arguments.callee);

                    // Actualizar input oculto JSON
                    document.getElementById('planes_json').value = JSON.stringify(planes);
                });
            });

            // Actualizar input oculto en cada render
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