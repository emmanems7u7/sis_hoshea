@extends('layouts.argon')

@section('content')
    <div class="container mt-5">
        <div class="card border-warning shadow">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">Verificación de Seguridad</h4>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('artisan.verificar') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="clave_segura" class="form-label">Contraseña del Panel</label>
                        <input type="password" name="clave_segura" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Verificar</button>
                </form>
            </div>
        </div>
    </div>
@endsection