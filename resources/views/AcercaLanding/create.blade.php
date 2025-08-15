@extends('layouts.argon')

@section('content')


    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Crear Acerca de para Landing</h5>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('acerca_landings.store') }}" method="POST">
                @include('AcercaLanding._form')
            </form>
        </div>
    </div>

@endsection