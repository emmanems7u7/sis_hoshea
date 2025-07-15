
function getNombreUsuarioById(id) {
    return usuariosMap[id] || 'Desconocido';
}
function agregar_cita() {

    const yaExistePrimeraCita = citasData.some(cita => cita.primera_cita === 1);

    const checkPrimeraCita = document.getElementById('primera_cita').checked;

    if (yaExistePrimeraCita && checkPrimeraCita) {
        alertify.error('Solo puede haber una primera cita.');
        return; 
    }
    const checkbox = document.getElementById('primera_cita');
    checkbox.checked = false;   
    checkbox.disabled = true;   
    // Capturar datos básicos
    let nuevaCita = {

        fecha_hora: $('#fecha_hora').val(),
        duracion: $('#duracion').val(),
        estado: $('#estado').val(),
        observaciones: $('#observaciones').val(),
        primera_cita:  checkPrimeraCita ? 1 : 0,
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
        const partes = finTratamiento.split('-');
        const dFin = new Date(partes[0], partes[1] - 1, partes[2], 23, 59, 59, 999); // Local, sin problemas de zona horaria
    
        const fechaCita = new Date($('#fecha_hora').val());
    
        console.log(fechaCita);
        console.log(dFin);
    
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
                                     <th>Primera Cita</th>
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
        const partes = cita.fecha_hora.split('T'); // ["2025-07-13", "21:20"]
        const fecha = `${partes[0]} ${partes[1]}`;
        html += `
                            <tr data-index="${i}">

                                <td>${fecha}</td>
                                <td>${cita.duracion || '-'}</td>
                                <td>${cita.estado}</td>
                                <td>${cita.observaciones || '-'}</td>
                                <td>${cita.primera_cita == 1 ? 'Primera Cita' : '' || '-'}</td>

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


