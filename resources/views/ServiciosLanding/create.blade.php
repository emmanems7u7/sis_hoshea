@extends('layouts.argon')

@section('content')


    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Crear Servicio para landing page</h5>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            @include('ServiciosLanding._form')
        </div>
    </div>

@endsection