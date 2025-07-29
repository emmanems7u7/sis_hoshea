<div class="card mb-3">
    <div class="card-body">

        <div class="d-flex align-items-center gap-2">
            <h6 class="mb-0">Antecedentes Familiares</h6>
            <label class="text-primary mb-0" data-bs-toggle="tooltip" data-bs-placement="right"
                title="Agrega dinámicamente el antecedente médico y asócialo a un familiar del paciente. Una vez que completes ambos campos, presiona 'Agregar' para ver el registro en la tabla de previsualización. Podrás eliminar cualquier antecedente antes de guardar definitivamente.">
                <i class="fas fa-info-circle" style="cursor: pointer;"></i>
            </label>
        </div>

        <div class="row">
            <div class="col-md-6">

                <label for="antecedente">Antecedente</label>
                <select class="form-select @error('antecedente') is-invalid @enderror" id="antecedente"
                    name="antecedente">
                    <option value="" selected>Seleccione un antecedente</option>
                    @foreach ($antecedentes as $antecedente)
                        <option value="{{ $antecedente->catalogo_codigo }}" {{ old('antecedente', $paciente?->antecedente ?? '') == $antecedente->catalogo_codigo ? 'selected' : '' }}>
                            {{ $antecedente->catalogo_descripcion }}
                        </option>
                    @endforeach
                </select>
                @error('antecedente')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>
            <div class="col-md-6">
                <label for="familiar">Familiar</label>
                <select class="form-select @error('familiar') is-invalid @enderror" id="familiar" name="familiar">
                    <option value="" selected>Seleccione un familiar</option>
                    @foreach ($familiares as $familiar)
                        <option value="{{ $familiar->catalogo_codigo }}" {{ old('familiar', $paciente?->familiar ?? '') == $familiar->catalogo_codigo ? 'selected' : '' }}>
                            {{ $familiar->catalogo_descripcion }}
                        </option>
                    @endforeach
                </select>
                @error('familiar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="button" class="btn btn-sm btn-primary mt-2" id="btn_agregar_fam">Agregar</button>
        <div class="table-responsive">
            <table class="table mt-3" id="tablaAntecedentes" style="display:none;">
                <thead>
                    <tr>
                        <th>Antecedente</th>
                        <th>Familiar</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>


    </div>
</div>

@php
    $antecedentesJson = old('antecedentes_json', isset($paciente) && isset($paciente->antecedentes)
        ? json_encode($paciente->antecedentes->map(function ($a) {
            return [
                'antecedenteCodigo' => $a->antecedente,
                'antecedenteTexto' => $a->catalogoAntecedente->catalogo_descripcion ?? '',
                'familiarCodigo' => $a->familiar,
                'familiarTexto' => $a->catalogoFamiliar->catalogo_descripcion ?? '',
            ];
        })) : json_encode([]));
@endphp

<input type="hidden" name="antecedentes_json" id="antecedentes_json" value="{{ $antecedentesJson }}">



<script>


    const tsAntecedente = new TomSelect('#antecedente', {
        placeholder: 'Seleccione un antecedente',
        allowEmptyOption: true,
    });
    const tsFamiliar = new TomSelect('#familiar', {
        placeholder: 'Seleccione un familiar',
        allowEmptyOption: true,
    });




    // Obtener referencias a elementos
    const selectAntecedente = document.getElementById('antecedente');
    const selectFamiliar = document.getElementById('familiar');
    const btnAgregar = document.getElementById('btn_agregar_fam');
    const tabla = document.getElementById('tablaAntecedentes');
    const tbody = tabla.querySelector('tbody');

    btnAgregar.addEventListener('click', () => {
        const antecedenteCodigo = selectAntecedente.value;
        const familiarCodigo = selectFamiliar.value;
        const antecedenteTexto = selectAntecedente.options[selectAntecedente.selectedIndex].text;
        const familiarTexto = selectFamiliar.options[selectFamiliar.selectedIndex].text;

        // Validar selección
        if (!antecedenteCodigo) {
            alertify.error('Por favor seleccione un antecedente.');
            return;
        }
        if (!familiarCodigo) {
            alertify.error('Por favor seleccione un familiar.');
            return;
        }

        // Verificar si ya existe la combinación para evitar duplicados
        const existe = antecedentesAgregados.some(item =>
            item.antecedenteCodigo === antecedenteCodigo && item.familiarCodigo === familiarCodigo
        );

        if (existe) {
            alertify.error('Este antecedente ya está asociado a este familiar.');
            return;
        }

        // Agregar al array
        antecedentesAgregados.push({
            antecedenteCodigo,
            antecedenteTexto,
            familiarCodigo,
            familiarTexto
        });

        // Mostrar en la tabla
        renderTabla();

        // Opcional: resetear selects
        tsAntecedente.clear();
        tsFamiliar.clear();
    });

    // Función para renderizar la tabla con los antecedentes agregados
    function renderTabla() {
        // Mostrar tabla si hay elementos
        if (antecedentesAgregados.length > 0) {
            tabla.style.display = '';
        } else {
            tabla.style.display = 'none';
        }

        // Limpiar cuerpo de la tabla
        tbody.innerHTML = '';

        // Crear filas
        antecedentesAgregados.forEach((item, index) => {
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${item.antecedenteTexto}</td>
                <td>${item.familiarTexto}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarAntecedente(${index})">
                        Eliminar
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        });
        const inputJson = document.getElementById('antecedentes_json');
        inputJson.value = JSON.stringify(antecedentesAgregados);
    }

    function eliminarAntecedente(index) {
        alertify.confirm(
            'Confirmar eliminación',
            '¿Seguro que deseas eliminar este antecedente?',
            function () {
                antecedentesAgregados.splice(index, 1);
                renderTabla(); // Redibuja la tabla
                alertify.success('Registro eliminado');
            },
            function () {
                alertify.error('Eliminación cancelada');
            }
        );
    }
</script>

<script>
    var antecedentesAgregados = JSON.parse(document.getElementById('antecedentes_json').value || '[]');

    document.addEventListener('DOMContentLoaded', function () {
        const antecedentesInput = document.getElementById('antecedentes_json');

        try {
            antecedentesAgregados = JSON.parse(antecedentesInput.value);
        } catch (e) {
            antecedentesAgregados = [];
        }

        renderTabla();
    });
</script>