@extends('layouts.argon')

@section('content')


    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6>Agregar al inventario</h6>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">

            <form action="{{ route('biens.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('biens._form')

                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('biens.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

@endsection