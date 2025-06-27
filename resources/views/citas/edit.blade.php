@extends('layouts.argon')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1>Editar Cita</h1>

        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('citas.update', $cita) }}">
                @csrf
                @method('PUT')
                @include('citas._form')
                <button type="submit" class="btn btn-success">Actualizar</button>
                <a href="{{ route('citas.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>

        </div>
    </div>
@endsection