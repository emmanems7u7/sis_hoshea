@extends('layouts.argon')

@section('content')

    <div class="card">
        <div class="card-body">
            <h1>Editar Tratamiento</h1>

        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('tratamientos.update', $tratamiento) }}">
                @csrf
                @method('PUT')
                @include('tratamientos._form')
                <input type="hidden" id="citas_json" name="citas_json" value="{{ old('citas_json', $citasJson) }}">
        </div>
    </div>
    <div class="card mt-2 mb-2 shadow-sm">
        <div class="card-header">
            <h5>Agregar citas al tratamiento</h5>
        </div>
        <div class="card-body">

            @include('citas._form')
            <a onclick="agregar_cita()" class="btn btn-primary">Agregar Cita</a>

        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div id="citas"></div>
        </div>
    </div>
    <div class="d-flex justify-content-center gap-3 mt-4">


        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('tratamientos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script>
        const usuariosMap = {
            @foreach($usuarios as $id => $nombre)
                '{{ $id }}': '{{ addslashes($nombre) }}',
            @endforeach
                                                                                                                                                                                                                                                                                                                };
    </script>

    <script src="{{ asset('js/citas.js') }}"></script>

@endsection