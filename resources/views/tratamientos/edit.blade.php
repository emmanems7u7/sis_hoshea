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
                <button type="submit" class="btn btn-success">Actualizar</button>
                <a href="{{ route('tratamientos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection