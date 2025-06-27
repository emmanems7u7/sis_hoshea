@extends('layouts.argon')

@section('content')


    <div class="card">
        <div class="card-body">
            <h1 class="h4 mb-3">Crear artículo</h1>

        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('inventario.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('inventario._form', ['inventario' => null, 'categorias' => $categorias])



                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('inventario.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

@endsection