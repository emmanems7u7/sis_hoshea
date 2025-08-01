@extends('layouts.argon')

@section('content')

    

        <div class="card text-black shadow-lg mx-4 card-profile-bottom">
            <div class="card-body p-3">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                            @if ($user->foto_perfil)
                                <img src="{{ asset($user->foto_perfil) }}" alt="profile_image"
                                    class="w-100 border-radius-lg shadow-sm">
                            @else
                                <img src="{{ asset('update/imagenes/user.jpg') }}" alt="profile_image"
                                class="w-100 border-radius-lg shadow-sm">
                            @endif
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1 text-black">
                                {{ $user->usuario_nombres }} {{ $user->usuario_app }} {{ $user->usuario_apm }}
                            </h5>
                            @foreach($user->roles as $role) 
                            <p class="mb-0 font-weight-bold text-sm">
                              
                                {{$role->name;}}
                             
                               
                            </p>
                            @endforeach
                        </div>
                    </div>
                    <!--
                    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0 text-black">
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center" data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                                    <i class="fas fa-mobile-alt"></i> 
                                    <span class="ms-2">Aplicación</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center" data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                    <i class="fas fa-envelope"></i> 
                                    <span class="ms-2">Mensajes</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center" data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                    <i class="fas fa-cogs"></i>
                                    <span class="ms-2">Configuración</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    </div>-->
                </div>
            </div>
        </div>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card text-black">
                    <form action="{{ route('users.update',['id'=>  Auth::user()->id, 'perfil' => 1]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                        <div class="card-header text-black pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Editar Perfil</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Actualizar Datos</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">Informacion de Usuario</p>
                           
                            <div class="row">
    {{-- Fila 1: Foto, usuario, email --}}
    <div class="col-md-4 mb-3">
        <label for="profile_picture" class="form-label">Foto de Perfil</label>
        <div class="d-flex align-items-center">
            <input type="file" id="profile_picture" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*" onchange="previewImage(event)">
            <div class="ms-3" id="preview-container">
                <img id="preview-img" src="#" alt="Previsualización" style="display: none; width: 80px; height: 80px; border-radius: 10%; object-fit: cover;">
            </div>
            <button type="button" id="remove-img" class="btn btn-danger ms-2" style="display: none;" onclick="removeImage()">Eliminar</button>
        </div>
        @error('profile_picture') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="name" class="form-label">Nombre de usuario</label>
        <input id="name" class="form-control @error('name') is-invalid @enderror" name="name" type="text" value="{{ old('name', $user->name) }}">
        @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email', $user->email) }}">
        @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    {{-- Fila 2: Nombre y apellidos --}}
    <div class="col-md-4 mb-3">
        <label for="usuario_nombres" class="form-label">Nombre</label>
        <input id="usuario_nombres" class="form-control @error('usuario_nombres') is-invalid @enderror" type="text" name="usuario_nombres" value="{{ old('usuario_nombres', $user->usuario_nombres) }}">
        @error('usuario_nombres') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="usuario_app" class="form-label">Apellido paterno</label>
        <input id="usuario_app" class="form-control @error('usuario_app') is-invalid @enderror" name="usuario_app" type="text" value="{{ old('usuario_app', $user->usuario_app) }}">
        @error('usuario_app') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="usuario_apm" class="form-label">Apellido materno</label>
        <input id="usuario_apm" class="form-control @error('usuario_apm') is-invalid @enderror" name="usuario_apm" type="text" value="{{ old('usuario_apm', $user->usuario_apm) }}">
        @error('usuario_apm') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    {{-- Fila 3: Fecha nacimiento, género, CI --}}
    <div class="col-md-4 mb-3">
        <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
        <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $user->fecha_nacimiento ?? '') }}">
        @error('fecha_nacimiento') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="genero" class="form-label">Género</label>
        <select class="form-select @error('genero') is-invalid @enderror" id="genero" name="genero">
            <option value="" {{ old('genero', $user->genero ?? '') == '' ? 'selected' : '' }}>Seleccione</option>
            <option value="M" {{ old('genero', $user->genero ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
            <option value="F" {{ old('genero', $user->genero ?? '') == 'F' ? 'selected' : '' }}>Femenino</option>
            <option value="O" {{ old('genero', $user->genero ?? '') == 'O' ? 'selected' : '' }}>Otro</option>
        </select>
        @error('genero') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="documento_identidad" class="form-label">Documento de identidad</label>
        <input type="text" class="form-control @error('documento_identidad') is-invalid @enderror" id="documento_identidad" name="documento_identidad" value="{{ old('documento_identidad', $user->documento_identidad ?? '') }}">
        @error('documento_identidad') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    {{-- Fila 4: Teléfono, dirección, rol --}}
    <div class="col-md-4 mb-3">
        <label for="usuario_telefono" class="form-label">Teléfono</label>
        <input id="usuario_telefono" class="form-control @error('usuario_telefono') is-invalid @enderror" name="usuario_telefono" type="text" value="{{ old('usuario_telefono', $user->usuario_telefono) }}">
        @error('usuario_telefono') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="usuario_direccion" class="form-label">Dirección</label>
        <input id="usuario_direccion" class="form-control @error('usuario_direccion') is-invalid @enderror" name="usuario_direccion" type="text" value="{{ old('usuario_direccion', $user->usuario_direccion) }}">
        @error('usuario_direccion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    

    {{-- Fila 5: País, departamento, ciudad --}}
    <div class="col-md-4 mb-3">
        <label for="pais" class="form-label">País</label>
        <select class="form-select @error('pais') is-invalid @enderror" id="pais" name="pais">
            <option value="" selected>Seleccione un país</option>
            @foreach ($paises as $pais)
                <option value="{{ $pais->catalogo_codigo }}" {{ old('pais', $user?->pais ?? '') == $pais->catalogo_codigo ? 'selected' : '' }}>
                    {{ $pais->catalogo_descripcion }}
                </option>
            @endforeach
        </select>
        @error('pais') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="departamento" class="form-label">Departamento</label>
        <select class="form-select @error('departamento') is-invalid @enderror" id="departamento" name="departamento">
            <option value="" selected>Seleccione un departamento</option>
        </select>
        @error('departamento') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="ciudad" class="form-label">Ciudad</label>
        <select class="form-select @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad">
            <option value="" selected>Seleccione una ciudad</option>
        </select>
        @error('ciudad') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>
</div>


</div>

                    </form>
                          
                           
                        </div>
                    </div>
                </div>
               
            </div>
          
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
        const departamentoInicial = '{{ old('departamento', $user?->departamento ?? '') }}';
        const ciudadInicial = '{{ old('ciudad', $user?->ciudad ?? '') }}';

        if (paisInicial) {
            loadOptions(`/departamentos/${paisInicial}`, departamentoSelect, departamentoInicial);
        }
        if (departamentoInicial) {
            loadOptions(`/ciudades/${departamentoInicial}`, ciudadSelect, ciudadInicial);
        }
    });
</script>


    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }

       
        function previewImage(event) {
            const file = event.target.files[0];
            const previewImg = document.getElementById("preview-img");
            const removeBtn = document.getElementById("remove-img");
            const previewContainer = document.getElementById("preview-container");

          
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;  
                    previewImg.style.display = "block";  
                    removeBtn.style.display = "inline-block"; 
                }
                
                reader.readAsDataURL(file);  
            }
        }

        
        function removeImage() {
            const previewImg = document.getElementById("preview-img");
            const removeBtn = document.getElementById("remove-img");
            const inputFile = document.getElementById("profile_picture");
            
         
            previewImg.style.display = "none";
            removeBtn.style.display = "none";
            inputFile.value = ""; 
        }

    </script>


@endsection