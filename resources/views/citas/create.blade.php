@extends('layouts.argon')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5>Crear Cita</h5>

        </div>
    </div>
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

@endsection