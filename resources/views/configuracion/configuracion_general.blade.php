@extends('layouts.argon')

@section('content')

        <div class="card shadow-lg mx-4 card-profile-bottom text-black">
            <div class="card-body p-3">
                <p>Configuración General del Sistema</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.configuracion.update') }}">

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card text-black">

                        <div class="card-body">

                                @csrf
                                @method('PUT')
                                  <!-- API KEY IA GROQ -->
                                  <div class="mb-3">
                                    <label for="GROQ_API_KEY" class="form-label text-black"> API KEY IA GROQ</label>
                                    <input type="text" class="form-control" id="GROQ_API_KEY"
                                        name="GROQ_API_KEY" value="{{ $config->GROQ_API_KEY }}">
                                </div>

                                <!-- Activación de 2FA -->
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="2faSwitch"
                                        name="doble_factor_autenticacion" {{ $config->doble_factor_autenticacion ? 'checked' : '' }}>
                                    <label class="form-check-label text-black" for="2faSwitch">
                                        Activar verificación en dos pasos (2FA)
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="mant"
                                        name="mantenimiento" {{ $config->mantenimiento ? 'checked' : '' }}>
                                    <label class="form-check-label text-black" for="mant">
                                        Activar modo Mantenimiento
                                    </label>
                                </div>

                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="mant"
                                        name="firma" {{ $config->firma ? 'checked' : '' }}>
                                    <label class="form-check-label text-black" for="mant">
                                        Los reportes tienen Firma
                                    </label>
                                </div>
                                
                                <!-- Límite de sesiones -->
                                <div class="mb-3">
                                    <label for="limite_de_sesiones" class="form-label text-black">Límite de sesiones</label>
                                    <input type="number" class="form-control" id="limite_de_sesiones"
                                        name="limite_de_sesiones" value="{{ $config->limite_de_sesiones }}">
                                </div>

                                <div class="form-group">
                                    <label for="hoja_export">Tamaño de hoja para reportes</label>
                                    <select class="form-control" name="hoja_export" id="hoja_export">
                                        <option value="A4" {{ $config->hoja_export == 'A4' ? 'selected' : '' }}>A4 (210 x 297 mm)</option>
                                        <option value="Letter" {{ $config->hoja_export == 'Letter' ? 'selected' : '' }}>Letter (216 x 279 mm)</option>
                                        <option value="Legal" {{ $config->hoja_export == 'Legal' ? 'selected' : '' }}>Legal (216 x 356 mm)</option>
                                        <option value="A5" {{ $config->hoja_export == 'A5' ? 'selected' : '' }}>A5 (148 x 210 mm)</option>
                                        <option value="A3" {{ $config->hoja_export == 'A3' ? 'selected' : '' }}>A3 (297 x 420 mm)</option>
                                        <option value="B5" {{ $config->hoja_export == 'B5' ? 'selected' : '' }}>B5 (176 x 250 mm)</option>
                                        <option value="Folio" {{ $config->hoja_export == 'Folio' ? 'selected' : '' }}>Folio (210 x 330 mm)</option>
                                        <option value="Executive" {{ $config->hoja_export == 'Executive' ? 'selected' : '' }}>Executive (184 x 267 mm)</option>
                                    </select>
                                </div>
                        </div>

                    </div>

<div class="card mt-3">
    <div class="card-body">

    <h5>Informacion de contacto</h5>
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

                    <div class="card mt-3">
                        <div class="card-body">
                            
                        <h5>Dias de atencion </h5>
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

                    <div class="card mt-3">
                        <div class="card-body">
                        <h5>Roles que se visualizan en Landing</h5>
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

                    <div class="card mt-3">
                        <div class="card-body">
                        <h5>Texto Presentación</h5>
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

         
        @can('configuracion.actualizar')
                                <button type="submit" class="btn btn-primary mt-3">Guardar cambios</button>
                                @endcan

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