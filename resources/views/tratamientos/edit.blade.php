@extends('layouts.argon')

@section('content')

    <div class="card">
        <div class="card-body">
            <h1>Editar Tratamiento</h1>

        </div>
    </div>

    <div class="row">
        <div class="col-md-6 order-2 order-md-1">
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
        </div>
        <div class="col-md-6 order-1 order-md-2">

            <div class="card mt-3">
                <div class="card-body">
                    <h6>Historial del Paciente Seleccionado</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6>Tratamientos</h6>
                            <div id="paciente_tratamientos"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mt-2">
                        <div class="card-body">
                            <h6>Diagnosticos/Antecedentes</h6>
                            <div id="paciente_diagnosticos"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card mt-3">
        <div class="card-body">
            <div id="citas"></div>
        </div>
    </div>


    <div class="d-flex justify-content-center gap-3 mt-4">

        <a href="{{ route('tratamientos.index') }}" class="btn btn-secondary">Cancelar</a>


        <button type="submit" class="btn btn-success">Actualizar</button>
        </form>
    </div>
    <script>
        const usuariosMap = {
            @foreach($usuarios as $id => $nombre)
                '{{ $id }}': '{{ addslashes($nombre) }}',
            @endforeach 
                                                                    };

        async function validar(nuevaCita) {
            try {
                const response = await fetch('{{ route("citas.validar.ajax") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(nuevaCita)
                });

                const data = await response.json();
                return data;

            } catch (error) {
                alertify.error('Error en la validación.');
                return { status: false, mensaje: 'Error en la validación.' };
            }
        }


    </script>
    <script>
        const usuariosMap = {
            @foreach($usuarios as $id => $nombre)
                '{{ $id }}': '{{ addslashes($nombre) }}',
            @endforeach
                                                    };
    </script>

    <script src="{{ asset('js/citas.js') }}"></script>

    <script>
        citasData = {!! $citasJson !!};

    </script>

@endsection