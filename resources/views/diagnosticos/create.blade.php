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
        <form action="{{ route('diagnosticos.store') }}" method="POST">
        @csrf
        @include('diagnosticos._form')

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('diagnosticos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
        </div>
    </div>

@endsection