@extends('layouts.argon')

@section('content')

    <div class="card">
        <div class="card-body">
            <h1>Editar Tratamiento</h1>

        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <form method="POST" action="{{ route('tratamientos.update', $tratamiento) }}">
                @csrf
                @method('PUT')
                @include('tratamientos._form')
                <input type="hidden" id="citas_json" name="citas_json" value="{{ old('citas_json', $citasJson) }}">
        </div>
    </div>
    <div class="card mt-2 mb-2 shadow-sm">
        <div class="card-header">
            <h5>Agregar citas al tratamiento</h5>
        </div>
        <div class="card-body">

            @include('citas._form')
            <a onclick="agregar_cita()" class="btn btn-primary">Agregar Cita</a>

        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div id="citas"></div>
        </div>
    </div>
    <div class="d-flex justify-content-center gap-3 mt-4">


        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('tratamientos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script>
        // Genera un objeto JS con id => nombre para todos los usuarios de la lista
        const usuariosMap = {
            @foreach($usuarios as $id => $nombre)
                '{{ $id }}': '{{ addslashes($nombre) }}',
            @endforeach
                                                                                                                                                                                                                                                                            };
    </script>

    <script>

        function getNombreUsuarioById(id) {
            return usuariosMap[id] || 'Desconocido';
        }
        function agregar_cita() {


            // Capturar datos básicos
            let nuevaCita = {

                fecha_hora: $('#fecha_hora').val(),
                duracion: $('#duracion').val(),
                estado: $('#estado').val(),
                observaciones: $('#observaciones').val(),
                usuarios: [],
                roles: []
            };

            // Capturar usuarios asignados y sus roles
            // El checkbox y el input rol están en el mismo índice (orden en DOM)
            nuevaCita.usuarios = [];
            nuevaCita.roles = [];

            $('input[name="usuarios[]"]').each(function (index) {
                if ($(this).is(':checked')) {
                    nuevaCita.usuarios.push($(this).val());

                    // Capturamos el rol en la misma posición
                    let rolVal = $('input[name="roles[]"]').eq(index).val();
                    nuevaCita.roles.push(rolVal);
                }
            });



            // Validar fecha y hora
            if (!nuevaCita.fecha_hora) {
                alertify.error('La fecha y hora es requerida.');
                return;
            }

            // Validar estado
            if (!nuevaCita.estado) {
                alertify.error('El estado es requerido.');
                return;
            }

            // Validar duración (opcional, pero si se ingresa debe ser número positivo)
            if (nuevaCita.duracion && (isNaN(nuevaCita.duracion) || nuevaCita.duracion <= 0)) {
                alertify.error('La duración debe ser un número positivo.');
                return;
            }
            // Validaciones usuario asignado y rol
            if (nuevaCita.usuarios.length === 0) {
                alertify.error('Debe seleccionar al menos un usuario asignado.');
                return;
            }

            for (let i = 0; i < nuevaCita.roles.length; i++) {
                if (!nuevaCita.roles[i] || nuevaCita.roles[i].trim() === '') {
                    alertify.error('Debe ingresar el rol para cada usuario asignado.');
                    return;
                }
            }


            // --- Validar que la cita esté dentro del rango del tratamiento ---
            const inicioTratamiento = $('#fecha_inicio').val();
            const finTratamiento = $('#fecha_fin').val();

            const fechaCita = new Date(nuevaCita.fecha_hora);

            // 1) Debe ser >= fecha_inicio
            if (inicioTratamiento) {
                const dInicio = new Date(inicioTratamiento);
                if (fechaCita < dInicio) {
                    alertify.error('La cita no puede ser anterior al inicio del tratamiento.');
                    return;
                }
            }

            // 2) Debe ser <= fecha_fin (si existe)
            if (finTratamiento) {
                const dFin = new Date(finTratamiento);
                // agregar 23:59:59 para incluir todo el día fin
                dFin.setHours(23, 59, 59, 999);

                if (fechaCita > dFin) {
                    alertify.error('La cita no puede ser posterior al fin del tratamiento.');
                    return;
                }
            }
            // --- Fin de validación de rango ---

            citasData.push(nuevaCita);
            alertify.success('Cita Agregada Correctamente');

            renderTabla();

            limpiarCampos();
        }


        let citasData = [];


        const oldCitas = document.getElementById('citas_json').value;
        if (oldCitas) {
            try { citasData = JSON.parse(oldCitas); } catch (e) { citasData = []; }
        }


        renderTabla();

        // Render tabla
        function renderTabla() {
            if (citasData.length === 0) {
                $('#citas').html('<p>No hay citas agregadas.</p>');
                return;
            }

            let html = `
                                                                                                                                                                                                                                                                                        <div class="table-responsive">
                                                                                                                                                                                                                                                                                            <table class="table table-striped table-bordered align-middle">
                                                                                                                                                                                                                                                                                                <thead class="table-dark">
                                                                                                                                                                                                                                                                                                    <tr>

                                                                                                                                                                                                                                                                                                        <th>Fecha y Hora</th>
                                                                                                                                                                                                                                                                                                        <th>Duración</th>
                                                                                                                                                                                                                                                                                                        <th>Estado</th>
                                                                                                                                                                                                                                                                                                        <th>Observaciones</th>
                                                                                                                                                                                                                                                                                                        <th>Usuarios asignados</th>
                                                                                                                                                                                                                                                                                                    <th>Acciones</th>
                                                                                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                                                                                </thead>
                                                                                                                                                                                                                                                                                                <tbody>
                                                                                                                                                                                                                                                                                    `;

            citasData.forEach((cita, i) => {
                // Usuarios y roles concatenados
                // Iterar usuarios con roles
                let usuariosConRoles = cita.usuarios.map((usuarioId, index) => {
                    const nombre = usuariosMap[usuarioId] || 'Desconocido';
                    const rol = cita.roles[index] || '-';
                    return `${nombre} (${rol})`;
                }).join(', ');

                html += `
                                                                                                                                                                                                                                                                                            <tr data-index="${i}">

                                                                                                                                                                                                                                                                                                <td>${cita.fecha_hora}</td>
                                                                                                                                                                                                                                                                                                <td>${cita.duracion || '-'}</td>
                                                                                                                                                                                                                                                                                                <td>${cita.estado}</td>
                                                                                                                                                                                                                                                                                                <td>${cita.observaciones || '-'}</td>
                                                                                                                                                                                                                                                                                                <td>${usuariosConRoles}</td>

                                                                                                                                                                                                                                                                                                <td><button class="btn btn-danger btn-sm btn-eliminar" data-index="${i}">Eliminar</button></td>
                                                                                                                                                                                                                                                                                            </tr>
                                                                                                                                                                                                                                                                                        `;
            });

            html += `
                                                                                                                                                                                                                                                                                                </tbody>
                                                                                                                                                                                                                                                                                            </table>
                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                    `;

            $('#citas').html(html);
            $('#citas_json').val(JSON.stringify(citasData));
            console.log(JSON.stringify(citasData))
            console.log($('#citas_json').val())
        }
        // Limpiar campos que se van a agregar al array
        function limpiarCampos() {
            $('#fecha_hora').val('');
            $('#duracion').val('');
            $('#estado').val('pendiente');
            $('#observaciones').val('');
        }




        // Eliminar fila del array y refrescar tabla
        $('#citas').on('click', '.btn-eliminar', function () {
            let index = $(this).data('index');
            alertify.confirm('Confirmación', '¿Deseas eliminar esta cita?',
                function () {

                    citasData.splice(index, 1);
                    renderTabla();
                    alertify.success('Cita eliminada.');
                },
                function () {

                    alertify.error('Eliminación cancelada.');
                }
            );
        });

        // Inicializar tabla vacía
        renderTabla();

    </script>
@endsection