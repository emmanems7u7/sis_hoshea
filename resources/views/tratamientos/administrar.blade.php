@extends('layouts.argon')

@section('content')
    <style>
        .fade-in {
            opacity: 1;
            transition: opacity 0.4s ease-in;
        }

        .fade-out {
            opacity: 0;
            transition: opacity 0.4s ease-out;
        }

        .fade-element {
            opacity: 0;
        }
    </style>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h5>Administración de tratamiento <strong>{{ $tratamiento->nombre }}</strong></h5>

                    <a href="" class="btn-primary btn btn-sm">Finalizar Gestión</a>

                    <a href="" class="btn-dark btn btn-sm">Añadir observación</a>


                </div>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="">Información</h5>
                    <small>- En esta sección puedes gestionar el tratamiento de un paciente. Debes administrar las citas que
                        corresponden a la fecha actual <strong>{{ now()->format('d-m-Y') }}</strong> y las siguientes en su
                        fecha correspondiente</small>


                    <br>

                    <small>- Utiliza el botón <strong>"Finalizar Gestión"</strong> para finalizar la administración del
                        tratamiento, Esto marcará el tratamiento como completado, <strong>Se recomienda hacerlo cuando se
                            gestione todas las citas asignadas</strong>
                    </small>
                    <br>

                </div>
            </div>
        </div>
    </div>



    <div class="row mb-3">

        <!-- DATOS DE CITAS -->
        <div class="col-md-6 mb-3 order-2 order-md-1">
            <div class="card">
                <div class="card-body">


                    @include('tratamientos.tarjetas_citas', ['botones' => true])

                </div>
            </div>
        </div>

        <!-- DATOS DEL TRATAMIENTO -->
        <div class="col-md-6 order-1 order-md-2 mb-2">
            <div class="card shadow-sm">
                <div class="card-body py-2">
                    @include('tratamientos.datos_tratamiento')
                </div>
            </div>
            <div class="card shadow-sm mt-3">
                <div class="card-body py-2">
                    @include('tratamientos.datos_paciente')
                </div>
            </div>

        </div>


    </div>




    <!-- Modal -->
    @include('tratamientos.modal_gestion')


    @include('tratamientos.modal_vista_gestion')

    @include('tratamientos.modal_hoja_lab')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Botón "Gestionar"
            document.querySelectorAll('.btn-gestionar').forEach(boton => {
                boton.addEventListener('click', function () {
                    const citaId = this.dataset.citaId;
                    document.getElementById('btn_accion').textContent = 'Guardar Gestión';
                    const form = document.getElementById('form-gestion-cita');
                    const baseRoute = @json(route('citas.gestion', ['cita' => 'ID_REEMPLAZO']));
                    form.action = baseRoute.replace('ID_REEMPLAZO', citaId);

                    let methodInput = form.querySelector('input[name="_method"]');
                    if (methodInput) {
                        methodInput.remove();
                    }
                });
            });

            // Botones "Editar"
            const btnEditarList = document.querySelectorAll('.btn-editar-gestion');

            btnEditarList.forEach(btnEditar => {
                btnEditar.addEventListener('click', function (e) {
                    e.preventDefault();

                    const citaId = this.dataset.citaId;

                    if (citaId) {

                        cargarGestion(citaId);

                        const modalEl = document.getElementById('modal_gestion');
                        let modalInstance = bootstrap.Modal.getInstance(modalEl);

                        if (!modalInstance) {
                            modalInstance = new bootstrap.Modal(modalEl);
                        }
                        modalInstance.show();

                    }

                    // Cambia texto del botón de acción si existe
                    const btnAccion = document.getElementById('btn_accion');
                    if (btnAccion) {
                        btnAccion.textContent = 'Actualizar Gestión';
                    }

                    // Genera la ruta con citaId
                    const editarRouteTemplate = @json(route('citas.update_gestion', ['cita' => 'ID_REEMPLAZO']));
                    const editarRoute = editarRouteTemplate.replace('ID_REEMPLAZO', citaId);

                    // Actualiza el formulario
                    const form = document.getElementById('form-gestion-cita');
                    if (form) {
                        form.action = editarRoute;

                        // Crea o actualiza el input _method a PUT
                        let methodInput = form.querySelector('input[name="_method"]');
                        if (!methodInput) {
                            methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            form.appendChild(methodInput);
                        }
                        methodInput.value = 'PUT';
                    }
                });
            });
        });
    </script>


    <script>





        function cargarGestion(citaId) {
            const urlEditarGestionBase = "{{ route('citas.editar_gestion', ['cita' => '__ID__']) }}";
            const url = urlEditarGestionBase.replace('__ID__', citaId);
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    // Asignar valores simples
                    $('#cod_diagnostico').val(data.cod_diagnostico).trigger('change'); // si usas Select2
                    $('#criterio_clinico').val(data.criterio_clinico);
                    $('#evolucion_diagnostico').val(data.evolucion_diagnostico);

                    // Asignar arrays a las variables globales
                    datos = data.datos || [];
                    datosObjetivos = data.objetivos || [];
                    planes = data.planes || [];

                    // Renderizar con tus funciones existentes
                    renderizarDatos();
                    renderResumen();
                    renderPlanes();

                    // Mostrar modal edición
                    const modalEl = document.getElementById('modal_gestion');
                    let modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (!modalInstance) {
                        modalInstance = new bootstrap.Modal(modalEl);
                    }
                    modalInstance.show();

                    // Opcional: si usas validación o algún plugin, reinícialo aquí
                },
                error: function () {
                    alertify.error('Error cargando datos de la cita.');
                }
            });
        }
    </script>
    <script>
        $(document).ready(function () {
            $('.btn-gestionar').on('click', function (e) {
                e.preventDefault();

                const citaId = $(this).data('cita-id');

                if (!citaId) return;

                const modalEl = document.getElementById('modal_gestion');
                let modalInstance = bootstrap.Modal.getInstance(modalEl);

                if (!modalInstance) {
                    modalInstance = new bootstrap.Modal(modalEl);
                }
                modalInstance.show();


            });
        });
        document.getElementById('btnCerrarModal').addEventListener('click', function () {
            alertify.confirm(
                'Confirmación',
                '¿Estás seguro? <br> <strong>Los cambios no se guardarán.</strong>',
                function () {
                    datos = [];
                    datosObjetivos = [];
                    planes = [];

                    renderizarDatos();
                    renderResumen();
                    renderPlanes();
                    $('#modal_gestion').modal('hide');

                },
                function () {
                    // Cancelado: no hacer nada
                }
            ).set('labels', { ok: 'Sí, cerrar', cancel: 'Cancelar' });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Seleccione un diagnostico',
                dropdownParent: $('#modal_gestion') // Reemplaza con el ID real del modal
            });
        });
    </script>




@endsection