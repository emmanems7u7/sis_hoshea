@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar Diagnóstico: {{ $diagnostico->cod_diagnostico }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('diagnosticos.update', [$tratamiento->id, $diagnostico->id]) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="cod_diagnostico" class="form-label">Código</label>
            <input type="text" name="cod_diagnostico" value="{{ old('cod_diagnostico', $diagnostico->cod_diagnostico) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="fecha_diagnostico" class="form-label">Fecha</label>
            <input type="date" name="fecha_diagnostico" value="{{ old('fecha_diagnostico', $diagnostico->fecha_diagnostico) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" class="form-control">
                <option value="activo" {{ $diagnostico->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ $diagnostico->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="observacion" class="form-label">Observación</label>
            <textarea name="observacion" class="form-control">{{ old('observacion', $diagnostico->observacion) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('diagnosticos.index', $tratamiento->id) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
