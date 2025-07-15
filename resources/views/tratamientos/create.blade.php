@extends('layouts.argon')

@section('content')


    <div class="card">
        <div class="card-body ">

            <h5 class="">Crear Tratamiento</h5>
            <small>- En esta sección puedes crear un tratamiento asociado a un paciente.</small>
            <br>
            <small>- Es <strong>Importante</strong> que agregues al menos una cita al tratamiento de otra forma no podras
                guardar el tratamiento</small>
            <small>- Cada cita que agregues se visualizará en el contenedor que esta al final del formulario, puedes
                eliminar una cita si lo requieres</small>
            <br>

            <small>- Las observaciones son opcionales tanto como para el tratamiento y las citas que vayas a crear</small>
            <small>- Los tratamientos tienen una fecha de inicio y de fin las citas que agregue deben estar dentro de ese
                rango de fechas</small>

        </div>
    </div>

    <div class="card mt-3 ">
        <div class="card-header">
            <h5>Agregar Datos del Tratamiento</h5>
        </div>
        <div class="card-body">

            <form method="POST" action="{{ route('tratamientos.store') }}" id="form-tratamiento">
                @csrf
                @include('tratamientos._form')

                <input type="hidden" id="citas_json" name="citas_json" value="{{ old('citas_json') }}">
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
        <a href="{{ route('tratamientos.index') }}" class="btn btn-secondary" style="min-width: 140px;">Cancelar</a>
        <button type="submit" class="btn btn-primary" style="min-width: 140px;">Crear</button>

    </div>

    </form>
    <script>
        const usuariosMap = {
            @foreach($usuarios as $id => $nombre)
                '{{ $id }}': '{{ addslashes($nombre) }}',
            @endforeach 
            };
    </script>

    <script src="{{ asset('js/citas.js') }}"></script>


@endsection