@extends('layouts.argon')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Tratamientos</h1>
    <a href="{{ route('tratamientos.create') }}" class="btn btn-success">Crear Tratamiento</a>
</div>

@foreach($tratamientos as $tratamiento)
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
            <div>
                <h4 class="mb-0 fw-bold text-white">{{ $tratamiento->nombre }}</h4>
                <small>Paciente: <strong>{{ $tratamiento->paciente->nombres }} {{ $tratamiento->paciente->apellidos }}</strong></small>
            </div>
            <div>
                <a href="{{ route('tratamientos.edit', $tratamiento) }}" class="btn btn-sm btn-outline-light me-2">Editar</a>
                <button class="btn btn-sm btn-light text-danger fw-bold"
                        onclick="confirmarEliminacion('eliminarTratamientoForm{{ $tratamiento->id }}', '¿Seguro que deseas eliminar este tratamiento?')">
                    Eliminar
                </button>
                <form id="eliminarTratamientoForm{{ $tratamiento->id }}" method="POST"
                      action="{{ route('tratamientos.destroy', $tratamiento) }}" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>

        <div class="card-body bg-white rounded-bottom">
            <div class="mb-2">
                <span class="me-3"><strong>Fecha Inicio:</strong> {{ $tratamiento->fecha_inicio->format('Y-m-d') }}</span>
                <span class="me-3"><strong>Fecha Fin:</strong> {{ $tratamiento->fecha_fin ? $tratamiento->fecha_fin->format('Y-m-d') : '-' }}</span>
                <span><strong>Estado:</strong> 
                    @php
                        $estadoColor = match($tratamiento->estado) {
                            'activo' => 'success',
                            'finalizado' => 'secondary',
                            'cancelado' => 'danger',
                            default => 'dark'
                        };
                    @endphp
                    <span class="badge bg-{{ $estadoColor }}">{{ ucfirst($tratamiento->estado) }}</span>
                </span>
            </div>

            @if($tratamiento->citas->count())
                <div class="mt-3">
                    <h6 class="text-muted">Citas Asociadas:</h6>
                    <div class="row">
                        @foreach($tratamiento->citas as $cita)
                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <p class="mb-1"><strong>Fecha y Hora:</strong> {{ $cita->fecha_hora->format('Y-m-d H:i') }}</p>
                                    <p class="mb-1"><strong>Duración:</strong> {{ $cita->duracion ?? '-' }}</p>
                                    <p class="mb-1"><strong>Estado:</strong> 
                                        <span class="badge bg-info text-dark">{{ ucfirst($cita->estado) }}</span>
                                    </p>
                                    <p class="mb-1"><strong>Profesionales:</strong></p>
                                    @foreach($cita->usuarios as $usuario)
                                        <span class="badge bg-secondary me-1">{{ $usuario->name }} ({{ $usuario->pivot->rol_en_cita ?? 'N/A' }})</span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-muted">No hay citas asociadas.</p>
            @endif
        </div>
    </div>
@endforeach

<div class="mt-4">
    {{ $tratamientos->links('pagination::bootstrap-4') }}
</div>

@endsection
