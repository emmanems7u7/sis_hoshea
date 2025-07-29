@php
    // $paciente puede ser null en create
@endphp

<div class="row">

    <div class="col-12 col-md-6 mb-3">
        <label for="nombres">Nombres</label>
        <input type="text" class="form-control @error('nombres') is-invalid @enderror" id="nombres" name="nombres"
            value="{{ old('nombres', $paciente?->nombres ?? '') }}" required>
        @error('nombres')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-6 mb-3">
        <label for="apellido_paterno">Apellido Paterno</label>
        <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror" id="apellido_paterno"
            name="apellido_paterno" value="{{ old('apellido_paterno', $paciente?->apellido_paterno ?? '') }}" required>
        @error('apellido_paterno')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-6 mb-3">
        <label for="apellido_materno">Apellido Materno</label>
        <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror" id="apellido_materno"
            name="apellido_materno" value="{{ old('apellido_materno', $paciente?->apellido_materno ?? '') }}">
        @error('apellido_materno')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-6 mb-3">
        <label for="fecha_nacimiento">Fecha de nacimiento</label>
        <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" id="fecha_nacimiento"
            name="fecha_nacimiento"
            value="{{ old('fecha_nacimiento', $paciente?->fecha_nacimiento?->format('Y-m-d') ?? '') }}">
        @error('fecha_nacimiento')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-6 mb-3">
        <label for="genero">Género</label>
        <select class="form-select @error('genero') is-invalid @enderror" id="genero" name="genero">
            <option value="" {{ old('genero', $paciente?->genero ?? '') == '' ? 'selected' : '' }}>Seleccione</option>
            <option value="M" {{ old('genero', $paciente?->genero ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
            <option value="F" {{ old('genero', $paciente?->genero ?? '') == 'F' ? 'selected' : '' }}>Femenino</option>
            <option value="O" {{ old('genero', $paciente?->genero ?? '') == 'O' ? 'selected' : '' }}>Otro</option>
        </select>
        @error('genero')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-6 mb-3">
        <label for="telefono_fijo">Teléfono fijo</label>
        <input type="tel" class="form-control @error('telefono_fijo') is-invalid @enderror" id="telefono_fijo"
            name="telefono_fijo" value="{{ old('telefono_fijo', $paciente?->telefono_fijo ?? '') }}">
        @error('telefono_fijo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-6 mb-3">
        <label for="telefono_movil">Teléfono móvil</label>
        <input type="tel" class="form-control @error('telefono_movil') is-invalid @enderror" id="telefono_movil"
            name="telefono_movil" value="{{ old('telefono_movil', $paciente?->telefono_movil ?? '') }}">
        @error('telefono_movil')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12  col-md-6 mb-3">
        <label for="email">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
            value="{{ old('email', $paciente?->email ?? '') }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    <div class="col-12 col-md-6 mb-3">
        <label for="tipo_documento">Tipo de documento</label>
        <select class="form-control @error('tipo_documento') is-invalid @enderror" name="tipo_documento"
            id="tipo_documento">
            <option value="">Seleccione una opción</option>
            @php
                $tiposDocumento = ['CI', 'Pasaporte', 'RUC', 'NIT', 'Licencia de conducir', 'DNI extranjero'];
                $tipoSeleccionado = old('tipo_documento', $paciente?->tipo_documento ?? '');
            @endphp
            @foreach ($tiposDocumento as $tipo)
                <option value="{{ $tipo }}" {{ $tipo == $tipoSeleccionado ? 'selected' : '' }}>
                    {{ $tipo }}
                </option>
            @endforeach
        </select>
        @error('tipo_documento')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    <div class="col-12 col-md-6 mb-3">
        <label for="numero_documento">Número de documento</label>
        <input type="text" class="form-control @error('numero_documento') is-invalid @enderror" id="numero_documento"
            name="numero_documento" value="{{ old('numero_documento', $paciente?->numero_documento ?? '') }}">
        @error('numero_documento')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-6 mb-3">
        <label for="pais">País</label>
        <select class="form-select @error('pais') is-invalid @enderror" id="pais" name="pais">
            <option value="" selected>Seleccione un pais</option>
            @foreach ($paises as $pais)
                <option value="{{ $pais->catalogo_codigo }}" {{ old('pais', $paciente?->pais ?? '') == $pais->catalogo_codigo ? 'selected' : '' }}>
                    {{ $pais->catalogo_descripcion }}
                </option>
            @endforeach
        </select>
        @error('pais')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-6 mb-3">
        <label for="departamento">Departamento</label>
        <select class="form-select @error('departamento') is-invalid @enderror" id="departamento" name="departamento">
            <option value="" selected>Seleccione un departamento</option>

        </select>
        @error('departamento')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    <div class="col-12 col-md-6 mb-3">
        <label for="ciudad">Ciudad</label>
        <select class="form-select @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad">
            <option value="" selected>Seleccione una ciudad</option>
        </select>
        @error('ciudad')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 col-md-6 mb-3">
        <label for="direccion">Dirección</label>
        <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion"
            value="{{ old('direccion', $paciente?->direccion ?? '') }}">
        @error('direccion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mb-3 form-check">
        <input type="checkbox" class="form-check-input @error('activo') is-invalid @enderror" id="activo" name="activo"
            value="1" {{ old('activo', $paciente?->activo ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="activo">Activo</label>
        @error('activo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

@include('pacientes.antecedentes')


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paisSelect = document.getElementById('pais');
        const departamentoSelect = document.getElementById('departamento');
        const ciudadSelect = document.getElementById('ciudad');

        function clearOptions(selectElement, defaultText) {
            selectElement.innerHTML = `<option value="" selected>${defaultText}</option>`;
        }

        function loadOptions(url, selectElement, selectedValue = '') {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    clearOptions(selectElement, selectElement.options[0].text);
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.catalogo_codigo;
                        option.textContent = item.catalogo_descripcion;
                        if (item.catalogo_codigo === selectedValue) {
                            option.selected = true;
                        }
                        selectElement.appendChild(option);
                    });
                })
                .catch(() => clearOptions(selectElement, selectElement.options[0].text));
        }

        // Al cambiar país, cargar departamentos y limpiar ciudad
        paisSelect.addEventListener('change', function () {
            const paisCodigo = this.value;
            if (!paisCodigo) {
                clearOptions(departamentoSelect, 'Seleccione un departamento');
                clearOptions(ciudadSelect, 'Seleccione una ciudad');
                return;
            }
            loadOptions(`/departamentos/${paisCodigo}`, departamentoSelect);
            clearOptions(ciudadSelect, 'Seleccione una ciudad');
        });

        // Al cambiar departamento, cargar ciudades
        departamentoSelect.addEventListener('change', function () {
            const departamentoCodigo = this.value;
            if (!departamentoCodigo) {
                clearOptions(ciudadSelect, 'Seleccione una ciudad');
                return;
            }
            loadOptions(`/ciudades/${departamentoCodigo}`, ciudadSelect);
        });

        // Al cargar la página, cargar departamentos y ciudades si hay valores previos (editar)
        const paisInicial = paisSelect.value;
        const departamentoInicial = '{{ old('departamento', $paciente?->departamento ?? '') }}';
        const ciudadInicial = '{{ old('ciudad', $paciente?->ciudad ?? '') }}';

        if (paisInicial) {
            loadOptions(`/departamentos/${paisInicial}`, departamentoSelect, departamentoInicial);
        }
        if (departamentoInicial) {
            loadOptions(`/ciudades/${departamentoInicial}`, ciudadSelect, ciudadInicial);
        }
    });
</script>