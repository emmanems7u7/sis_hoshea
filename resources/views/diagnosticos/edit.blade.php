@extends('layouts.argon')

@section('content')


    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">

            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
        <form action="{{ route('diagnosticos.update', [$tratamiento->id, $diagnostico->id]) }}" method="POST">
        @csrf
        @method('PUT')
        @include('diagnosticos._form')

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('diagnosticos.index', $tratamiento->id) }}" class="btn btn-secondary">Cancelar</a>
    </form>
        </div>
    </div>

@endsection