<div class="accordion-item mb-3">
    <h5 class="accordion-header" id="headingOne">
        <button class="accordion-button border-bottom font-weight-bold collapsed bg-green_tarjetas_claro" type="button"
            data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
            S/ (Subjetivo)
            <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
            <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
        </button>
    </h5>
    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
        data-bs-parent="#accordionRental" style="">
        <div class="accordion-body ">

            <span>[Sintomas referidos por el paciente, molestias, evolución desde la
                ultima visita] </span> <label class="text-primary" data-bs-toggle="tooltip" data-bs-placement="right"
                title="Agrega dinámicamente los síntomas que te indique el paciente. Todos los síntomas que agregues se asociarán a la cita que estás gestionando.">
                <i class="fas fa-info-circle" style="cursor: pointer;"></i>
            </label>
            <div id="contenido-1" class="mb-3"></div>
            <div class="d-flex mb-3">
                <input type="text" id="input-text" class="form-control me-2" placeholder="Escribe un sintoma">
                <button type="button" class="btn btn-sm btn-primary" id="btn-agregar">Agregar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var datos = [];


    function renderizarDatos() {
        const contenedor = $('#contenido-1');
        contenedor.empty();

        datos.forEach((item, index) => {
            const p = $(`
            <p class="mb-2 mt-2 d-flex justify-content-between align-items-center border rounded p-2">
              <span class="texto-item flex-grow-1">${item}</span>
              <i class="fas fa-edit text-primary me-3" style="cursor:pointer;"></i>
              <i class="fas fa-trash text-danger" style="cursor:pointer;"></i>
            </p>
        `);

            // Función para manejar edición
            function activarEdicion() {
                const icon = p.find('.fa-edit');
                const span = p.find('.texto-item');
                const textoActual = span.text();

                if (p.find('input').length > 0) return;

                const input = $(`<input type="text" class="form-control form-control-sm" value="${textoActual}">`);
                span.replaceWith(input);
                input.focus();

                icon.removeClass('fa-edit text-primary').addClass('fa-check text-success');
                icon.off('click').on('click', guardarEdicion);
            }

            // Función para guardar edición
            function guardarEdicion() {
                const icon = p.find('.fa-check');
                const input = p.find('input');
                const nuevoTexto = input.val().trim();

                if (nuevoTexto === '') {
                    alertify.error('El texto no puede estar vacío.');
                    input.focus();
                    return;
                }

                datos[index] = nuevoTexto;
                input.replaceWith(`<span class="texto-item flex-grow-1">${nuevoTexto}</span>`);
                icon.removeClass('fa-check text-success').addClass('fa-edit text-primary');
                icon.off('click').on('click', activarEdicion);

                document.getElementById('datos_json').value = JSON.stringify(datos);
            }

            // Evento eliminar
            p.find('.fa-trash').on('click', function () {
                p.fadeOut(400, function () {
                    datos.splice(index, 1);
                    renderizarDatos();
                });
            });

            // Evento editar inicial
            p.find('.fa-edit').on('click', activarEdicion);

            // Actualizar input oculto en cada render
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