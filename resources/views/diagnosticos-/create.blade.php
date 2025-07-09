@extends('layouts.argon')

@section('content')
<div class="container">
   
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('diagnosticos.store') }}">
        @csrf

          <div class="col-12 col-md-6 mb-3">
            <label for="tratamiento_id" class="form-label">Tratamiento (opcional)</label>
            <select name="tratamiento_id" id="tratamiento_id"
                class="form-select @error('tratamiento_id') is-invalid @enderror">
                <option value="">Sin tratamiento</option>
                @foreach($tratamientos as $id => $nombre)
                    <option value="{{ $id }}" {{ old('tratamiento_id', $cita->tratamiento_id ?? '') == $id ? 'selected' : '' }}>
                        {{ $nombre }}
                    </option>
                @endforeach
            </select>
            @error('tratamiento_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="cod_diagnostico" class="form-label">Código del Diagnóstico</label>
            <input type="text" name="cod_diagnostico" id="cod_diagnostico" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="fecha_diagnostico" class="form-label">Fecha</label>
            <input type="date" name="fecha_diagnostico" id="fecha_diagnostico" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" id="estado" class="form-control">
                <option value="activo" selected>Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="observacion" class="form-label">Observación</label>
            <textarea name="observacion" id="observacion" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Guardar Diagnóstico</button>
        <a href="{{ route('diagnosticos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
