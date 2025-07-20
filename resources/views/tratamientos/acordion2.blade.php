<div class="accordion-item mb-3">
    <h5 class="accordion-header" id="headingTwo">
        <button class="accordion-button border-bottom font-weight-bold bg-green_tarjetas_claro" type="button"
            data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            O/ (Objetivo):
            <i class="collapse-close fa fa-plus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
            <i class="collapse-open fa fa-minus text-xs pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
        </button>
    </h5>
    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
        data-bs-parent="#accordionRental">
        <div class="accordion-body opacity-8">
            <div id="objetivos-container" class="mb-4">

                <div class="d-flex align-items-center mb-3">

                    <span>[Signos vitales, hallazgos del examen físico, resultados de
                        laboratorio, imágenes, etc.]</span>
                    <label class="text-primary mb-0" data-bs-toggle="tooltip" data-bs-placement="right"
                        title="Haz clic en uno de los botones para ingresar el valor correspondiente del paciente. Puedes añadir varios datos haciendo clic en diferentes botones.">
                        <i class="fas fa-info-circle" style="cursor: pointer;"></i>
                    </label>
                </div>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($objetivos as $obj)
                            <button type="button" class="btn btn-sm btn-primary btn-objetivo px-2 py-1"
                                data-codigo="{{ $obj->catalogo_codigo }}" data-nombre="{{ $obj->catalogo_descripcion }}">
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
<script>
    var datosObjetivos = [];

    const botones = document.querySelectorAll('.btn-objetivo');
    const inputContainer = document.getElementById('input-container');
    const resumenContainer = document.getElementById('resumen-container');

    function renderResumen() {
        resumenContainer.innerHTML = '';

        datosObjetivos.forEach((item, index) => {
            const div = document.createElement('div');
            div.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'border', 'rounded', 'p-2', 'mb-2');

            div.innerHTML = `
          <div class="flex-grow-1">
            <strong class="nombre-objetivo">${item.nombre}</strong>: <span class="valor-objetivo">${item.valor}</span>
          </div>
          <i class="fas fa-edit text-primary me-3" style="cursor:pointer;"></i>
          <i class="fas fa-trash text-danger" style="cursor:pointer;"></i>
        `;

            // Eliminar con animación
            div.querySelector('.fa-trash').addEventListener('click', () => {
                $(div).fadeOut(300, () => {
                    datosObjetivos.splice(index, 1);
                    renderResumen();
                });
            });

            const icon = div.querySelector('.fa-edit');

            function activarEdicion() {
                const flexDiv = div.querySelector('div.flex-grow-1');
                const nombreSpan = flexDiv.querySelector('.nombre-objetivo');
                const valorSpan = flexDiv.querySelector('.valor-objetivo');

                if (div.querySelector('input')) return; // ya en edición

                const valorActual = valorSpan.textContent;

                const inputValor = document.createElement('input');
                inputValor.type = 'text';
                inputValor.className = 'form-control form-control-sm';
                inputValor.value = valorActual;
                inputValor.style.width = '120px';

                valorSpan.replaceWith(inputValor);
                inputValor.focus();

                icon.classList.remove('fa-edit', 'text-primary');
                icon.classList.add('fa-check', 'text-success');
                icon.removeEventListener('click', activarEdicion);
                icon.addEventListener('click', guardarEdicion);
            }

            function guardarEdicion() {
                const inputValor = div.querySelector('input');
                const nuevoValor = inputValor.value.trim();

                if (!nuevoValor) {
                    alertify.error('El valor no puede estar vacío.');
                    inputValor.focus();
                    return;
                }

                datosObjetivos[index].valor = nuevoValor;

                const nuevoSpan = document.createElement('span');
                nuevoSpan.classList.add('valor-objetivo');
                nuevoSpan.textContent = nuevoValor;
                inputValor.replaceWith(nuevoSpan);

                icon.classList.remove('fa-check', 'text-success');
                icon.classList.add('fa-edit', 'text-primary');
                icon.removeEventListener('click', guardarEdicion);
                icon.addEventListener('click', activarEdicion);

                document.getElementById('objetivos_json').value = JSON.stringify(datosObjetivos);
            }

            icon.addEventListener('click', activarEdicion);

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