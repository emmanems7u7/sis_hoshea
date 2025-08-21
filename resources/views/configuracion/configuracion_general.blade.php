@extends('layouts.argon')

@section('content')

<form method="POST" action="{{ route('admin.configuracion.update') }}" enctype="multipart/form-data">

        <div class="card shadow-lg mx-4  text-black">
            <div class="card-body p-3">
                <p>Configuración General del Sistema</p>

                @can('configuracion.actualizar')
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mt-3">Guardar cambios</button>
                    </div>
                @endcan
            </div>
        </div>

        <div class="container py-4">
            <div class="row">
                <div class="col-md-12">
                <div class="accordion" id="configuracionAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingConfig">
                        <button class="accordion-button border-bottom font-weight-bold collapsed bg-green_tarjetas_claro" type="button" data-bs-toggle="collapse" data-bs-target="#collapseConfig" aria-expanded="true" aria-controls="collapseConfig">
                            Configuración del sistema
                        </button>
                    </h2>
                    <div id="collapseConfig" class="accordion-collapse collapse show" aria-labelledby="headingConfig" >
                        <div class="accordion-body text-black">
                            <form method="POST" action="{{ route('admin.configuracion.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- API KEY IA GROQ -->
                                <div class="mb-3">
                                    <label for="GROQ_API_KEY" class="form-label text-black">API KEY IA GROQ</label>
                                    <input type="text" class="form-control" id="GROQ_API_KEY"
                                        name="GROQ_API_KEY" value="{{ old('GROQ_API_KEY', $config->GROQ_API_KEY) }}">
                                </div>

                                <!-- Activación de 2FA -->
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="2faSwitch"
                                        name="doble_factor_autenticacion" {{ $config->doble_factor_autenticacion ? 'checked' : '' }}>
                                    <label class="form-check-label text-black" for="2faSwitch">
                                        Activar verificación en dos pasos (2FA)
                                    </label>
                                </div>

                                <!-- Modo mantenimiento -->
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="mant"
                                        name="mantenimiento" {{ $config->mantenimiento ? 'checked' : '' }}>
                                    <label class="form-check-label text-black" for="mant">
                                        Activar modo Mantenimiento
                                    </label>
                                </div>

                                <!-- Reportes con firma -->
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="firmaSwitch"
                                        name="firma" {{ $config->firma ? 'checked' : '' }}>
                                    <label class="form-check-label text-black" for="firmaSwitch">
                                        Los reportes tienen Firma
                                    </label>
                                </div>

                                <!-- Límite de sesiones -->
                                <div class="mb-3">
                                    <label for="limite_de_sesiones" class="form-label text-black">Límite de sesiones</label>
                                    <input type="number" class="form-control" id="limite_de_sesiones"
                                        name="limite_de_sesiones" value="{{ old('limite_de_sesiones', $config->limite_de_sesiones) }}">
                                </div>

                                <!-- Tamaño de hoja -->
                                <div class="mb-3">
                                    <label for="hoja_export" class="form-label">Tamaño de hoja para reportes</label>
                                    <select class="form-control" name="hoja_export" id="hoja_export">
                                        @foreach(['A4','Letter','Legal','A5','A3','B5','Folio','Executive'] as $hoja)
                                            <option value="{{ $hoja }}" {{ $config->hoja_export == $hoja ? 'selected' : '' }}>
                                                {{ $hoja }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                          
                        </div>
                    </div>
                </div>
                <h5 class="mt-3"> Configuración Landing Page</h5>
                <div class="accordion-item mt-3">
                        <button class="accordion-button border-bottom font-weight-bold collapsed bg-green_tarjetas_claro" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePresentacion" aria-expanded="false" aria-controls="collapsePresentacion">
                        Imagenes y texto de presentación Landing Page
                        </button>

                        <div id="collapsePresentacion" class="accordion-collapse collapse hide" aria-labelledby="headingConfig" >
                            <div class="row">
                            <!-- Columna izquierda (IMÁGENES) -->
                            <div class="col-md-6">

                                <!-- Imagen de fondo -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Imagen de fondo</label>
                                    <input type="file" name="imagen_fondo" id="imagen_fondo" 
                                        class="form-control" accept="image/*" 
                                        onchange="previewImage(event, 'preview_fondo')">

                                    <div class="mt-3 d-flex justify-content-center">
                                        <div class="border rounded-3 shadow-sm p-3 bg-light" 
                                            style="width: 100%; max-width: 400px; height: 220px; display:flex; align-items:center; justify-content:center;">
                                            <img id="preview_fondo" 
                                                src="{{ $config->imagen_fondo ? asset($config->imagen_fondo) : '#' }}" 
                                                alt="Previsualización" 
                                                class="img-fluid rounded {{ $config->imagen_fondo ? '' : 'd-none' }}" 
                                                style="max-height: 100%; max-width: 100%; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Logo empresa -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Logo de la empresa</label>
                                    <input type="file" name="logo_empresa" id="logo_empresa" 
                                        class="form-control" accept="image/*" 
                                        onchange="previewImage(event, 'preview_logo')">

                                    <div class="mt-3 d-flex justify-content-center">
                                        <div class="border rounded-3 shadow-sm p-3 bg-light" style="width: 200px; height: 200px; display:flex; align-items:center; justify-content:center;">
                                            <img id="preview_logo" 
                                                src="{{ $config->logo_empresa ? asset($config->logo_empresa) : '#' }}" 
                                                alt="Previsualización" 
                                                class="img-fluid rounded-circle {{ $config->logo_empresa ? '' : 'd-none' }}" 
                                                style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                        </div>
                                    </div>
                                </div>

                            
                            </div>

                            <!-- Columna derecha (TEXTOS) -->
                            <div class="col-md-6">

                             <!-- Imagen cabecera -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Imagen cabecera</label>
                                <input type="file" name="imagen_cabecera" id="imagen_cabecera" 
                                    class="form-control" accept="image/*" 
                                    onchange="previewImage(event, 'preview_cabecera')">

                                <div class="mt-3 d-flex justify-content-center">
                                    <div class="border rounded-3 shadow-sm p-3 bg-light" 
                                        style="width: 100%; max-width: 400px; height: 220px; display:flex; align-items:center; justify-content:center;">
                                        <img id="preview_cabecera" 
                                            src="{{ $config->imagen_cabecera ? asset($config->imagen_cabecera) : '#' }}" 
                                            alt="Previsualización" 
                                            class="img-fluid rounded {{ $config->imagen_cabecera ? '' : 'd-none' }}" 
                                            style="max-height: 100%; max-width: 100%; object-fit: cover;">
                                    </div>
                                </div>
                            </div>

                            
                            <!-- Título cabecera -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Título cabecera</label>
                                    <input type="text" 
                                        name="titulo_cabecera" 
                                        class="form-control" 
                                        placeholder="Ingrese título de la cabecera" 
                                        value="{{ old('titulo_cabecera', $config->titulo_cabecera ?? '') }}">
                                </div>

                              

                            </div>

                            <div class="col-md-12">
                                  <!-- Descripción cabecera -->
                                  <div class="mb-4">
                                  <label for="descripcion_cabecera" class="form-label">Descripción cabecera</label>
                                <textarea name="descripcion_cabecera" id="descripcion_cabecera" rows="5"
                                    class="form-control @error('descripcion_cabecera') is-invalid @enderror">{{ old('descripcion_cabecera', $config->descripcion_cabecera) }}</textarea>
                                @error('descripcion_cabecera')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                </div>


                           
                            </div>
                            </div>
                        </div>
                </div>
              
                <div class="accordion-item mt-3">
                        <button class="accordion-button border-bottom font-weight-bold collapsed bg-green_tarjetas_claro" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEmergencias" aria-expanded="false" aria-controls="collapseEmergencias">
                        Texto para Emergencias
                        </button>

                        <div id="collapseEmergencias" class="accordion-collapse collapse hide" aria-labelledby="headingConfig" >
                        <div class="mb-3">
                                <label for="titulo_emergencia" class="form-label">Título Emergencia</label>
                                <input type="text" name="titulo_emergencia" id="titulo_emergencia" class="form-control @error('titulo_emergencia') is-invalid @enderror"
                                    value="{{ old('titulo_emergencia',  $config->titulo_emergencia ) }}" required maxlength="255">
                                @error('titulo_emergencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="descripcion_emergencia" class="form-label">Descripción Emergencia</label>
                                <textarea name="descripcion_emergencia" id="descripcion_emergencia" rows="5"
                                    class="form-control @error('descripcion_emergencia') is-invalid @enderror">{{ old('descripcion_emergencia', $config->descripcion_emergencia) }}</textarea>
                                @error('descripcion_emergencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                </div>

                <div class="accordion-item mt-3">
                        <button class="accordion-button border-bottom font-weight-bold collapsed bg-green_tarjetas_claro" type="button" data-bs-toggle="collapse" data-bs-target="#collapseContacto" aria-expanded="false" aria-controls="collapseContacto">
                        Información de contacto
                        </button>

                        <div id="collapseContacto" class="accordion-collapse collapse hide" aria-labelledby="headingConfig" >
                        <div class="mb-3">
                                <label for="direccion" class="form-label text-black">Direccion Clínica</label>
                                <input type="text" class="form-control" id="direccion"
                                                            name="direccion" value="{{ $config->direccion }}">
                            </div>

                            <div class="mb-3">
                                <label for="celular" class="form-label text-black">Telefono Contacto</label>
                                <input type="number" class="form-control" id="celular"
                                                            name="celular" value="{{ $config->celular }}">
                            </div>

                            <div class="mb-3">
                                <label for="geolocalizacion" class="form-label">Geolocalización</label>
                                <input type="text" 
                                    id="geolocalizacion" 
                                    name="geolocalizacion" 
                                    class="form-control" 
                                    value="{{ $config->geolocalizacion }}"
                                    readonly 
                                    placeholder="Seleccione en el mapa">
                            </div>
                        </div>
                </div>

                <div class="accordion-item mt-3">
                        <button class="accordion-button border-bottom font-weight-bold collapsed bg-green_tarjetas_claro" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAtencion" aria-expanded="false" aria-controls="collapseAtencion">
                        Dias de atención 
                        </button>

                        <div id="collapseAtencion" class="accordion-collapse collapse hide" aria-labelledby="headingConfig" >
                                @php
                                    $dias = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
                                @endphp

                                @foreach ($dias as $dia)
                                    @php
                                        $diaActivo = isset($diasGuardados[$dia]) ? $diasGuardados[$dia]['activo'] : false;
                                        $horaInicio = $diaActivo ? $diasGuardados[$dia]['inicio'] : '';
                                        $horaFin = $diaActivo ? $diasGuardados[$dia]['fin'] : '';
                                    @endphp

                                    <div class="form-check mb-2">
                                        <input 
                                            class="form-check-input dia-checkbox" 
                                            type="checkbox" 
                                            id="check_{{ $dia }}" 
                                            name="dias[{{ $dia }}][activo]"
                                            value="1"
                                            {{ $diaActivo ? 'checked' : '' }}>
                                        <label class="form-check-label" for="check_{{ $dia }}">
                                            {{ ucfirst($dia) }}
                                        </label>
                                    </div>

                                    <div class="row mb-3 horario-container" id="horario_{{ $dia }}" style="{{ $diaActivo ? '' : 'display:none;' }}">
                                        <div class="col-6">
                                            <label for="inicio_{{ $dia }}" class="form-label">Hora Inicio</label>
                                            <input type="time" class="form-control" id="inicio_{{ $dia }}" name="dias[{{ $dia }}][inicio]" value="{{ $horaInicio }}">
                                        </div>
                                        <div class="col-6">
                                            <label for="fin_{{ $dia }}" class="form-label">Hora Fin</label>
                                            <input type="time" class="form-control" id="fin_{{ $dia }}" name="dias[{{ $dia }}][fin]" value="{{ $horaFin }}">
                                        </div>
                                    </div>
                                @endforeach
                        </div>
                </div>

                <div class="accordion-item mt-3">
                        <button class="accordion-button border-bottom font-weight-bold collapsed bg-green_tarjetas_claro" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRoles" aria-expanded="false" aria-controls="collapseRoles">
                        Roles que se visualizan en Landing Page
                        </button>

                        <div id="collapseRoles" class="accordion-collapse collapse hide" aria-labelledby="headingConfig" >
                                @foreach ($roles as $rol)
                                    <div class="form-check mb-2">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="roles_landing[]" 
                                            id="check_rol_{{ $rol->id }}"
                                            value="{{ $rol->name }}" 
                                            {{ in_array($rol->name, $rolesSeleccionados) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="check_rol_{{ $rol->id }}">
                                            {{ ucfirst($rol->name) }}
                                        </label>
                                    </div>
                                @endforeach
                        </div>
                </div>

                <div class="accordion-item mt-3">
                        <button class="accordion-button border-bottom font-weight-bold collapsed bg-green_tarjetas_claro" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSobre" aria-expanded="false" aria-controls="collapseSobre">
                        Texto Sobre Nosotros
                        </button>

                        <div id="collapseSobre" class="accordion-collapse collapse hide" aria-labelledby="headingConfig">
                        <div class="mb-3">
                                <label for="titulo_presentacion" class="form-label">Título</label>
                                <input type="text" name="titulo_presentacion" id="titulo_presentacion" class="form-control @error('titulo_presentacion') is-invalid @enderror"
                                    value="{{ old('titulo_presentacion',  $config->titulo_presentacion ) }}" required maxlength="255">
                                @error('titulo_presentacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="descripcion_presentacion" class="form-label">Descripción</label>
                                <textarea name="descripcion_presentacion" id="descripcion_presentacion" rows="5"
                                    class="form-control @error('descripcion_presentacion') is-invalid @enderror">{{ old('descripcion_presentacion', $config->descripcion_presentacion) }}</textarea>
                                @error('descripcion_presentacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                </div>
            </div>

                  
                </div>
            </div>

        </div>

         
        </form>


<!-- Modal -->

<div class="modal fade" id="modalMapa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 90%;">
        <div class="modal-content {{ auth()->user()->preferences && auth()->user()->preferences->dark_mode ? 'bg-dark text-white' : 'bg-white text-dark' }}">
            <div class="modal-header">
                <h5 class="modal-title">Seleccionar ubicación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Buscador con botón -->
                <div class="input-group mb-2">
                    <input type="text" id="buscadorMapa" class="form-control" placeholder="Buscar dirección...">
                    <button class="btn btn-primary" id="btnBuscarMapa" type="button">Buscar</button>
                </div>
                <div id="mapa" style="height: 500px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnAceptarMapa">Aceptar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
 <!-- Script para previsualización -->
 <script>
                            function previewImage(event, idPreview) {
                                const input = event.target;
                                const reader = new FileReader();

                                reader.onload = function(){
                                    const preview = document.getElementById(idPreview);
                                    preview.src = reader.result;
                                    preview.classList.remove('d-none');
                                };
                                if(input.files[0]){
                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        </script>
<script>
    ClassicEditor
        .create(document.querySelector('#descripcion_cabecera'))
        .then(editor => {
            // Accede al editable y cambia estilos
            const editable = editor.ui.view.editable.element;

            editable.style.minHeight = '150px';
            editable.style.color = '#000';
            editable.style.backgroundColor = '#fff';
        })
        .catch(error => {
            console.error(error);
        });


        ClassicEditor
        .create(document.querySelector('#descripcion_emergencia'))
        .then(editor => {
            // Accede al editable y cambia estilos
            const editable = editor.ui.view.editable.element;

            editable.style.minHeight = '150px';
            editable.style.color = '#000';
            editable.style.backgroundColor = '#fff';
        })
        .catch(error => {
            console.error(error);
        });
</script>
                            <script>
                                ClassicEditor
                                    .create(document.querySelector('#descripcion_presentacion'))
                                    .then(editor => {
                                        // Accede al editable y cambia estilos
                                        const editable = editor.ui.view.editable.element;

                                        editable.style.minHeight = '150px';
                                        editable.style.color = '#000';
                                        editable.style.backgroundColor = '#fff';
                                    })
                                    .catch(error => {
                                        console.error(error);
                                    });
                            </script>
                                <script>
                                    document.querySelectorAll('.dia-checkbox').forEach(chk => {
                                        chk.addEventListener('change', function() {
                                            const dia = this.id.replace('check_', '');
                                            const container = document.getElementById('horario_' + dia);
                                            if (this.checked) {
                                                container.style.display = '';
                                            } else {
                                                container.style.display = 'none';
                                                // Opcional: limpiar inputs si se desmarca
                                                container.querySelectorAll('input[type="time"]').forEach(i => i.value = '');
                                            }
                                        });
                                    });
                                </script>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
let mapa, marcador, ubicacionSeleccionada = null;

// Abrir modal y cargar mapa
document.getElementById('geolocalizacion').addEventListener('click', function () {
    const modal = new bootstrap.Modal(document.getElementById('modalMapa'));
    modal.show();

    setTimeout(() => {
        if (!mapa) {
            mapa = L.map('mapa').setView([-16.5, -68.15], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(mapa);

            // Clic en el mapa
            mapa.on('click', function (e) {
                const { lat, lng } = e.latlng;
                ubicacionSeleccionada = { lat, lng };

                if (marcador) {
                    marcador.setLatLng(e.latlng);
                } else {
                    marcador = L.marker(e.latlng).addTo(mapa);
                }
            });
        } else {
            mapa.invalidateSize();
        }
    }, 300);
});

// Búsqueda al presionar el botón
document.getElementById('btnBuscarMapa').addEventListener('click', function () {
    const query = document.getElementById('buscadorMapa').value;
    if (!query) return alert('Ingrese una dirección a buscar');

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => {
            if (data && data.length > 0) {
                const { lat, lon } = data[0];
                const latLng = [parseFloat(lat), parseFloat(lon)];
                mapa.setView(latLng, 16);

                ubicacionSeleccionada = { lat: parseFloat(lat), lng: parseFloat(lon) };

                if (marcador) {
                    marcador.setLatLng(latLng);
                } else {
                    marcador = L.marker(latLng).addTo(mapa);
                }
            } else {
                alertify.error('No se encontró la dirección');
            }
        });
});

// Botón Aceptar
document.getElementById('btnAceptarMapa').addEventListener('click', function () {
    if (ubicacionSeleccionada) {
        document.getElementById('geolocalizacion').value = 
            `${ubicacionSeleccionada.lat.toFixed(6)}, ${ubicacionSeleccionada.lng.toFixed(6)}`;
        const modalEl = document.getElementById('modalMapa');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
    } else {
        alertify.warning('Debe seleccionar una ubicación primero');
    }
});
</script>

@endsection