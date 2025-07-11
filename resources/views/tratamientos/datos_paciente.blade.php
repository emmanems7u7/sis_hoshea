<h6 class="fw-bold border-bottom pb-1 mb-2">Datos del Paciente</h6>
<div class="row mb-1 small">
    <div class="col-5 text-muted">Nombre:</div>
    <div class="col-7 fw-semibold">{{ $tratamiento->paciente->nombre_completo }}</div>
</div>
<div class="row mb-1 small">
    <div class="col-5 text-muted">Género:</div>
    <div class="col-7 fw-semibold">
        {{ $tratamiento->paciente->genero == 'M' ? 'Masculino' : 'Femenino' }}
    </div>
</div>
<div class="row mb-1 small">
    <div class="col-5 text-muted">Teléfono:</div>
    <div class="col-7 fw-semibold">{{ $tratamiento->paciente->telefono ?? 'No tiene teléfono' }}
    </div>
</div>
<div class="row mb-1 small">
    <div class="col-5 text-muted">Celular:</div>
    <div class="col-7 fw-semibold">{{ $tratamiento->paciente->telefono_movil }}</div>
</div>
<div class="row mb-1 small">
    <div class="col-5 text-muted">Email:</div>
    <div class="col-7 fw-semibold">{{ $tratamiento->paciente->email }}</div>
</div>
<div class="row mb-1 small">
    <div class="col-5 text-muted">Dirección:</div>
    <div class="col-7 fw-semibold">{{ $tratamiento->paciente->direccion }}</div>
</div>
<div class="row mb-1 small">
    <div class="col-5 text-muted">{{ $tratamiento->paciente->tipo_documento }}:</div>
    <div class="col-7 fw-semibold">{{ $tratamiento->paciente->numero_documento }}</div>
</div>