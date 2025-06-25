@extends('layouts.argon')

@section('content')

    <div class="card">
        <div class="card-body">

            <h1>Editar Paciente</h1>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('pacientes.update', $paciente) }}" method="POST">
                @csrf
                @method('PUT')
                @include('pacientes._form')
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

@endsection