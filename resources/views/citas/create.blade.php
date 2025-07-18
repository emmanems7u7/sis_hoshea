@extends('layouts.argon')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Crear Cita</h5>

                </div>
            </div>
        </div>
        <div class="col-md-6"></div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mt-3">
                <div class="card-body">

                    <form method="POST" action="{{ route('citas.store') }}">
                        @csrf
                        @include('citas._form')
                        <button type="submit" class="btn btn-success">Crear</button>
                        <a href="{{ route('citas.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-6">
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


@endsection