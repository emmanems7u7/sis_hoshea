@extends('layouts.argon')

@section('content')

    <div class="card">
        <div class="card-body">

            <h1 class="h4 mb-3">Editar artículo</h1>

        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('inventario.update', $inventario) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('inventario._form', compact('inventario', 'categorias'))
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('inventario.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

@endsection