<div class="row">
    <!-- Columna izquierda -->
    <div class="col-12 col-md-6">

        <div class="mb-3">
            <label for="name">Nombre de Usuario</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                placeholder="Nombre de usuario" value="{{ old('name', $user->name ?? '') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                placeholder="Email" value="{{ old('email', $user->email ?? '') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="usuario_nombres">Nombres</label>
            <input type="text" class="form-control @error('usuario_nombres') is-invalid @enderror" id="usuario_nombres"
                name="usuario_nombres" placeholder="Nombre(s)"
                value="{{ old('usuario_nombres', $user->usuario_nombres ?? '') }}" required>
            @error('usuario_nombres')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="usuario_app">Apellido Paterno</label>
            <input type="text" class="form-control @error('usuario_app') is-invalid @enderror" id="usuario_app"
                name="usuario_app" placeholder="Apellido Paterno"
                value="{{ old('usuario_app', $user->usuario_app ?? '') }}" required>
            @error('usuario_app')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="usuario_apm">Apellido Materno</label>
            <input type="text" class="form-control @error('usuario_apm') is-invalid @enderror" id="usuario_apm"
                name="usuario_apm" placeholder="Apellido Materno"
                value="{{ old('usuario_apm', $user->usuario_apm ?? '') }}" required>
            @error('usuario_apm')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="usuario_telefono">Teléfono</label>
            <input type="tel" class="form-control @error('usuario_telefono') is-invalid @enderror" id="usuario_telefono"
                name="usuario_telefono" placeholder="Teléfono"
                value="{{ old('usuario_telefono', $user->usuario_telefono ?? '') }}" required>
            @error('usuario_telefono')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="usuario_direccion">Dirección</label>
            <input type="text" class="form-control @error('usuario_direccion') is-invalid @enderror"
                id="usuario_direccion" name="usuario_direccion" placeholder="Dirección"
                value="{{ old('usuario_direccion', $user->usuario_direccion ?? '') }}" required>
            @error('usuario_direccion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

    </div>

    <!-- Columna derecha -->
    <div class="col-12 col-md-6">

        <div class="mb-3">
            <label for="fecha_nacimiento">Fecha de nacimiento</label>
            <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                id="fecha_nacimiento" name="fecha_nacimiento"
                value="{{ old('fecha_nacimiento', $user->fecha_nacimiento ?? '') }}">
            @error('fecha_nacimiento')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="genero">Género</label>
            <select class="form-select @error('genero') is-invalid @enderror" id="genero" name="genero">
                <option value="" {{ old('genero', $user->genero ?? '') == '' ? 'selected' : '' }}>Seleccione</option>
                <option value="M" {{ old('genero', $user->genero ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
                <option value="F" {{ old('genero', $user->genero ?? '') == 'F' ? 'selected' : '' }}>Femenino</option>
                <option value="O" {{ old('genero', $user->genero ?? '') == 'O' ? 'selected' : '' }}>Otro</option>
            </select>
            @error('genero')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="documento_identidad">Documento de identidad</label>
            <input type="text" class="form-control @error('documento_identidad') is-invalid @enderror"
                id="documento_identidad" name="documento_identidad" placeholder="Número de documento"
                value="{{ old('documento_identidad', $user->documento_identidad ?? '') }}">
            @error('documento_identidad')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="pais">País</label>
            <input type="text" class="form-control @error('pais') is-invalid @enderror" id="pais" name="pais"
                placeholder="Código país" value="{{ old('pais', $user->pais ?? '') }}">
            @error('pais')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="ciudad">Ciudad</label>
            <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad"
                placeholder="Código ciudad" value="{{ old('ciudad', $user->ciudad ?? '') }}">
            @error('ciudad')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="role">Rol</label>
            <select name="role" id="role" class="form-control" required>
                @foreach(\Spatie\Permission\Models\Role::all() as $role)
                    <option value="{{ $role->name }}" {{ (isset($user) && $user->getRoleNames()->first() === $role->name) ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    <div class="col-12 text-center">
        <button type="submit" class="btn btn-round bg-gradient-info btn-lg w-100 mt-4 mb-0">
            {{ $btnText ?? 'Registrar Usuario' }}
        </button>
    </div>
</div>