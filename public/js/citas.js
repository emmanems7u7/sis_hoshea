
function agregar_cita() {

    const yaExistePrimeraCita = citasData.some(cita => cita.primera_cita === 1);

    const checkPrimeraCita = document.getElementById('primera_cita').checked;

    if (yaExistePrimeraCita && checkPrimeraCita) {
        alertify.error('Solo puede haber una primera cita.');
        return; 
    }

    const fecha_hora = document.getElementById('fecha_hora').value;
    const duracion = document.getElementById('duracion').value;
    const estado = document.getElementById('estado').value;
    const observaciones = document.getElementById('observaciones').value;
    // Capturar datos básicos
    let nuevaCita = {

        fecha_hora,
        duracion,
        estado,
        observaciones,
        primera_cita:  checkPrimeraCita ? 1 : 0,
        usuarios: [],
      
    };

    // Capturar usuarios asignados y sus roles
    // El checkbox y el input rol están en el mismo índice (orden en DOM)


   nuevaCita.usuarios = [];

   let validacionCorrecta = true;

   const inputsUsuarios = document.querySelectorAll('input[name^="usuarios["]');
   nuevaCita.usuarios = [];
   
   for (const input of inputsUsuarios) {
     if (input.checked) {
       const id = input.value;
       const label = document.querySelector('label[for="' + input.id + '"]');
       const nombre = label ? label.textContent.trim() : '';
       const rolInput = document.querySelector('input[name="roles[' + id + ']"]');
       const rol = rolInput ? rolInput.value.trim() : '';
   
       if (rol === '') {
         if (rolInput) rolInput.classList.add('is-invalid');
         alertify.error('Por favor, completa la responsabilidad de ' + nombre);
         validacionCorrecta = false;
         break; // Sale del loop
       } else {
         if (rolInput) rolInput.classList.remove('is-invalid');
       }
   
       nuevaCita.usuarios.push({
         id: id,
         nombre: nombre,
         rol: rol
       });
     }
   }
   
   
  
   if (!validacionCorrecta) {
   
       return;
   }
   
  

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


    // --- Validar que la cita esté dentro del rango del tratamiento ---
    const inicioTratamiento = document.getElementById('fecha_inicio').value;
    const finTratamiento = document.getElementById('fecha_fin').value;
    

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
    
        const fechaCita = new Date(document.getElementById('fecha_hora').value);
        if (fechaCita > dFin) {
            alertify.error('La cita no puede ser posterior al fin del tratamiento.');
            return;
        }
    }


(async () => {
    const result = await validar(nuevaCita);

    if (!result.status) {
        alertify.error(result.mensaje);
        return;
    }
    else
    {
        citasData.push(nuevaCita);
        alertify.success('Cita Agregada Correctamente');
    
        renderTabla();
    
        limpiarCampos();
    
        const checkbox = document.getElementById('primera_cita');
        checkbox.checked = false;   
        checkbox.disabled = true;  
    }

})();
    // --- Fin de validación de rango ---

   
}


let citasData = [];


const oldCitas = document.getElementById('citas_json').value;
if (oldCitas) {
    try { citasData = JSON.parse(oldCitas); } catch (e) { citasData = []; }
}


renderTabla();

// Render tabla
function renderTabla() {
    const citasContainer = document.getElementById('citas');
    const citasJsonInput = document.getElementById('citas_json');

    if (!citasContainer || !citasJsonInput) {
        console.error('No se encontraron los elementos #citas o #citas_json');
        return;
    }

    if (citasData.length === 0) {
        citasContainer.innerHTML = '<p>No hay citas agregadas.</p>';
        return;
    }

    let html = `
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Duración (Min.)</th>
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
        let usuariosConRoles = cita.usuarios.map(usuario => {
            const nombre = usuario.nombre || 'Desconocido';
            const rol = usuario.rol || '-';
            return `- ${nombre} (${rol})`;
        }).join('<br>');

        const partes = cita.fecha_hora.split('T'); // ["2025-07-13", "21:20"]
        const fecha = `${partes[0]} ${partes[1]}`;

        html += `
            <tr data-index="${i}">
                <td>${fecha}</td>
                <td>${cita.duracion || '-'}</td>
                <td>${cita.estado}</td>
                <td>${cita.observaciones || '-'}</td>
                <td>${cita.primera_cita == 1 ? 'Primera Cita' : '-'}</td>
                <td>${usuariosConRoles}</td>
                <td>
                    <button class="btn btn-danger btn-sm btn-eliminar" data-index="${i}">
                        Eliminar
                    </button>
                </td>
            </tr>
        `;
    });

    html += `
                </tbody>
            </table>
        </div>
    `;

    citasContainer.innerHTML = html;
    citasJsonInput.value = JSON.stringify(citasData);

    // Añadir evento click a los botones eliminar, ya que no se puede usar jQuery
    const btnsEliminar = citasContainer.querySelectorAll('.btn-eliminar');
    btnsEliminar.forEach(btn => {
        btn.addEventListener('click', function () {
            const index = this.getAttribute('data-index');
            if (index !== null) {
                // Aquí puedes manejar la eliminación, por ejemplo:
                citasData.splice(index, 1);
                renderTabla(); // Volver a renderizar la tabla tras eliminar
            }
        });
    });
}

// Limpiar campos que se van a agregar al array
function limpiarCampos() {
    document.getElementById('fecha_hora').value = '';
    document.getElementById('duracion').value = '';
    document.getElementById('estado').value = 'pendiente';
    document.getElementById('observaciones').value = '';
}



document.getElementById('citas').addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-eliminar')) {
        const index = e.target.getAttribute('data-index');

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
    }
});

// Inicializar tabla vacía
renderTabla();


