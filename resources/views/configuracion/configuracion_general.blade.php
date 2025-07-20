@extends('layouts.argon')

@section('content')

        <div class="card shadow-lg mx-4 card-profile-bottom text-black">
            <div class="card-body p-3">
                <p>Configuración General del Sistema</p>
            </div>
        </div>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card text-black">

                        <div class="card-body">

                            <form method="POST" action="{{ route('admin.configuracion.update') }}">
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

                                
                                @can('configuracion.actualizar')
                                <button type="submit" class="btn btn-primary mt-3">Guardar cambios</button>
                                @endcan

                            </form>

                        </div>

                    </div>


                </div>
            </div>

        </div>

   

@endsection