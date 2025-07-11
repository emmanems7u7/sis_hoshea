<h6 class="fw-bold border-bottom pb-1 mb-2">Datos del Tratamiento</h6>
<div class="row mb-1 small">
    <div class="col-5 text-muted">Inicio:</div>
    <div class="col-7 fw-semibold">{{ $tratamiento->fecha_inicio->format('d-m-Y') }}</div>
</div>
<div class="row mb-1 small">
    <div class="col-5 text-muted">Fin:</div>
    <div class="col-7 fw-semibold">{{ $tratamiento->fecha_fin->format('d-m-Y') }}</div>
</div>
<div class="row mb-1 small">
    <div class="col-5 text-muted">Observaciones:</div>
    <div class="col-7 fw-semibold">{{ $tratamiento->observaciones ?? 'No tiene observación' }}
    </div>
</div>
<div class="row mb-1 small">
    <div class="col-5 text-muted">Estado:</div>
    <div class="col-7 fw-semibold">{{ $tratamiento->estado }}</div>
</div>