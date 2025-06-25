@extends('layouts.argon')

@section('content')

    <div class="card">
        <div class="card-body">
            <h5>Crear Paciente</h5>

        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">

            <form action="{{ route('pacientes.store') }}" method="POST">
                @csrf
                @include('pacientes._form')
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>


@endsection