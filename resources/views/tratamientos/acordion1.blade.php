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
            @if($cita->primera_cita)
                @include('pacientes.antecedentes')

            @endif
        </div>
    </div>
</div>

<script>
    var datos = JSON.parse(document.getElementById('datos_json').value || '[]');

    renderizarDatos()
    function renderizarDatos() {
        const contenedor = document.getElementById('contenido-1');
        contenedor.innerHTML = ''; // Limpia

        datos.forEach((item, index) => {
            const p = document.createElement('p');
            p.className = 'mb-2 mt-2 d-flex justify-content-between align-items-center border rounded p-2';

            const span = document.createElement('span');
            span.className = 'texto-item flex-grow-1';
            span.textContent = item;

            const iconEdit = document.createElement('i');
            iconEdit.className = 'fas fa-edit text-primary me-3';
            iconEdit.style.cursor = 'pointer';

            const iconDelete = document.createElement('i');
            iconDelete.className = 'fas fa-trash text-danger';
            iconDelete.style.cursor = 'pointer';

            p.appendChild(span);
            p.appendChild(iconEdit);
            p.appendChild(iconDelete);
            contenedor.appendChild(p);

            // Función para manejar edición
            function activarEdicion() {
                if (p.querySelector('input')) return; // Evita duplicados

                const textoActual = span.textContent;
                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'form-control form-control-sm';
                input.value = textoActual;

                p.replaceChild(input, span);
                input.focus();

                iconEdit.classList.remove('fa-edit', 'text-primary');
                iconEdit.classList.add('fa-check', 'text-success');
                iconEdit.onclick = guardarEdicion;
            }

            // Función para guardar edición
            function guardarEdicion() {
                const input = p.querySelector('input');
                const nuevoTexto = input.value.trim();

                if (nuevoTexto === '') {
                    alertify.error('El texto no puede estar vacío.');
                    input.focus();
                    return;
                }

                datos[index] = nuevoTexto;

                const nuevoSpan = document.createElement('span');
                nuevoSpan.className = 'texto-item flex-grow-1';
                nuevoSpan.textContent = nuevoTexto;

                p.replaceChild(nuevoSpan, input);

                iconEdit.classList.remove('fa-check', 'text-success');
                iconEdit.classList.add('fa-edit', 'text-primary');
                iconEdit.onclick = activarEdicion;

                document.getElementById('datos_json').value = JSON.stringify(datos);
            }

            // Evento eliminar
            iconDelete.onclick = function () {
                p.style.opacity = '1';
                let fadeOut = setInterval(() => {
                    if (p.style.opacity > 0) {
                        p.style.opacity -= 0.1;
                    } else {
                        clearInterval(fadeOut);
                        p.remove();
                        datos.splice(index, 1);
                        renderizarDatos(); // Re-render
                    }
                }, 30);
            };

            // Evento editar inicial
            iconEdit.onclick = activarEdicion;

            // Actualizar input oculto
            document.getElementById('datos_json').value = JSON.stringify(datos);
        });
    }

    document.getElementById('btn-agregar').addEventListener('click', () => {
        const input = document.getElementById('input-text');
        const valor = input.value.trim();

        if (valor === '') {
            alertify.error('Por favor ingrese un síntoma');
            return;
        }

        datos.push(valor);
        renderizarDatos(); // Asegúrate de tener esta función en JS puro

        input.value = '';
        input.focus();

    });




</script>