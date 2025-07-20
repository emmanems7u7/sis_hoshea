@extends('tratamientos.gestion_cita_t')

@section('contenido_cita')

    <span>Seleccione los servicios y medicamentos del inventario que fueron aplicados en la cita que está gestionando</span>
    <div class="row">
        <div class="col-md-6 mb-3">

            <!-- SELECTOR DE SERVICIOS -->

            <label for="servicio_id" class="form-label">Servicios</label>
            <select id="servicio_id" class="form-select">
                <option value="" selected disabled>Seleccione un servicio</option>
                @foreach($servicios as $id => $nombre)
                    <option value="{{ $id }}">{{ $nombre }}</option>
                @endforeach
            </select>

        </div>
        <div class="col-md-6 mb-3">
            <!-- SELECTOR DE INVENTARIO -->
            <label for="inventario_id" class="form-label">Inventario</label>
            <select id="inventario_id" class="form-select">
                <option value="" selected disabled>Seleccione un ítem</option>
                @foreach($inventarios as $id => $nombre)
                    <option value="{{ $id }}">{{ $nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    </div>


    <div class="row">
        <div class="col-md-6"> <!-- CARD PARA MOSTRAR DETALLES DEL SERVICIO -->
            <div id="servicio_detalle_card" class="card d-none mb-3">
                <div class="card-body">
                    <h5 class="card-title" id="servicio_nombre"></h5>
                    <p class="card-text" id="servicio_descripcion"></p>
                    <p class="card-text"><strong>Precio por servicio:</strong> Bs. <span id="servicio_precio"></span></p>
                    <button type="button" class="btn btn-success" onclick="agregarServicio()">Agregar</button>
                </div>
            </div>
        </div>
        <div class="col-md-6"> <!-- CARD PARA MOSTRAR DETALLES DEL INVENTARIO -->
            <div id="inventario_detalle_card" class="card d-none mb-3">
                <div class="card-body">
                    <h5 class="card-title" id="inv_nombre"></h5>
                    <p class="card-text" id="inv_descripcion"></p>
                    <p class="card-text"><strong>Precio Unitario:</strong> Bs. <span id="inv_precio"></span></p>
                    <p class="card-text"><strong>Stock Actual:</strong> <span id="inv_stock"></span> <span
                            id="inv_unidad"></span>
                    </p>
                    <div class="mb-2">
                        <label for="inv_cantidad" class="form-label">Cantidad a usar</label>
                        <input type="number" min="1" class="form-control" id="inv_cantidad">
                    </div>
                    <button type="button" class="btn btn-success" onclick="agregarInventario()">Agregar</button>
                </div>
            </div>
        </div>

    </div>







    <!-- TABLA DE SERVICIOS AGREGADOS -->
    <div class="table-responsive mt-4">
        <table class="table table-bordered" id="tabla_servicios">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- CONTENEDOR A LA DERECHA -->
    <div class="d-flex justify-content-end">
        <div style="width: 300px;">

            <!-- CARD TOTAL SERVICIOS -->
            <div id="total_servicios_card" class="card border-bottom mb-2 d-none">
                <div class="card-body py-2">
                    <h6 class="mb-1 text-muted">Total Servicios</h6>
                    <p class="h5 mb-0">Bs. <span id="total_precio">0.00</span></p>
                </div>
            </div>

        </div>
    </div>



    <!-- TABLA INVENTARIO -->
    <div class="table-responsive mt-4">
        <table class="table table-bordered" id="tabla_inventario">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <!-- CONTENEDOR A LA DERECHA -->
    <div class="d-flex justify-content-end">
        <div style="width: 300px;">



            <!-- CARD TOTAL INVENTARIO -->
            <div id="total_inventario_card" class="card border-bottom mb-2 d-none">
                <div class="card-body py-2">
                    <h6 class="mb-1 text-muted">Total Inventario</h6>
                    <p class="h5 mb-0">Bs. <span id="total_inventario">0.00</span></p>
                </div>
            </div>

            <!-- CARD TOTAL GENERAL -->
            <div id="total_general_card" class="card border-bottom border-dark mb-2 d-none">
                <div class="card-body py-2">
                    <h5 class="mb-1 fw-bold">Total General</h5>
                    <p class="h4 mb-0 fw-bold text-success">Bs. <span id="total_general">0.00</span></p>
                </div>
            </div>

        </div>
    </div>






    <form action="{{ route('servicios.guardar_asignacion', $cita) }}" method="POST">
        @csrf
        <!-- INPUT OCULTO PARA ENVIAR ARRAY AL BACKEND -->
        <input type="hidden" name="servicios_seleccionados" id="servicios_seleccionados">

        <!-- INPUT OCULTO -->
        <input type="hidden" name="inventario_utilizado" id="inventario_utilizado">
        <button type="button" id="btnCancelar" class="btn btn-sm btn-danger">Cancelar</button>

        <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
    </form>

    <script>
        document.getElementById('btnCancelar').addEventListener('click', function (e) {
            alertify.confirm(
                '¿Estás seguro?',
                'Si tienes cambios, no se guardarán. ¿Deseas continuar?',
                function () {
                    // Redirigir manualmente si el usuario acepta
                    window.location.href = "{{ route('tratamientos.gestion_cita', $cita) }}";
                },
                function () {
                    // Cancelado, no hacer nada
                }
            ).set('labels', { ok: 'Sí, continuar', cancel: 'Cancelar' });
        });
    </script>


    <script>
        new TomSelect('#servicio_id', {
            placeholder: 'Seleccione un paciente',
            allowEmptyOption: true,
        });
        new TomSelect('#inventario_id', {
            placeholder: 'Seleccione un paciente',
            allowEmptyOption: true,
        });


        function actualizarTotal() {
            const total = serviciosAgregados.reduce((acc, servicio) => acc + Number(servicio.precio), 0);
            document.getElementById('total_precio').innerText = total.toFixed(2);
        }
        function actualizarInventarioTotal() {
            const total = inventarioUsado.reduce((acc, item) => acc + Number(item.subtotal), 0);
            document.getElementById('total_inventario').innerText = total.toFixed(2);
        }
        //inventario
        function actualizarTotalGeneral() {
            const totalServicios = parseFloat(document.getElementById('total_precio').innerText);
            console.log(totalServicios)
            const totalInventario = parseFloat(document.getElementById('total_inventario').innerText);
            const totalGeneral = (totalServicios + totalInventario).toFixed(2);
            console.log(totalInventario)

            document.getElementById('total_general').innerText = totalGeneral;
            document.getElementById('total_general_card').classList.remove('d-none');
        }
        function actualizarInventarioTabla() {

            const tbody = document.querySelector('#tabla_inventario tbody');
            tbody.innerHTML = '';

            inventarioUsado.forEach((item, index) => {
                const fila = document.createElement('tr');
                fila.innerHTML = `
                                                                                                                                                                                                                    <td>${item.nombre}</td>
                                                                                                                                                                                                                    <td>${item.cantidad}</td>
                                                                                                                                                                                                                    <td>${item.unidad_medida}</td>
                                                                                                                                                                                                                    <td>Bs. ${Number(item.precio_unitario).toFixed(2)}</td>
                                                                                                                                                                                                                    <td>Bs. ${Number(item.subtotal).toFixed(2)}</td>
                                                                                                                                                                                                                    <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarInventario(${index})">Eliminar</button></td>
                                                                                                                                                                                                                `;
                tbody.appendChild(fila);
            });

            document.getElementById('inventario_utilizado').value = JSON.stringify(inventarioUsado);

            if (inventarioUsado.length > 0) {
                document.getElementById('total_inventario_card').classList.remove('d-none');
            } else {
                document.getElementById('total_inventario_card').classList.add('d-none');
            }
        }
    </script>

    <script>
        const servicios = @json($serviciosDetalles);
        const serviciosAgregados = [];

        const inventario = @json($inventarioDetalles); // array con todos los detalles
        const inventarioUsado = [];

        @if($cita->servicios->isNotEmpty())
            @foreach($cita->servicios as $servicio)
                serviciosAgregados.push({
                    id: {{ $servicio->id }},
                    nombre: "{{ $servicio->nombre }}",
                    precio: {{ $servicio->precio }}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            });
            @endforeach
        @endif

        @if($cita->inventarios->isNotEmpty())
            @foreach($cita->inventarios as $item)
                inventarioUsado.push({
                    id: {{ $item->id }},
                    nombre: "{{ $item->nombre }}",
                    precio_unitario: {{ $item->precio_unitario }},
                    unidad_medida: "{{ $item->unidad_medida }}",
                    cantidad: {{ $item->pivot->cantidad }},
                    subtotal: ({{ $item->precio_unitario }} * {{ $item->pivot->cantidad }})

                });
            @endforeach
        @endif

        actualizarTabla();
        actualizarInventarioTabla();
        actualizarTotal();
        actualizarInventarioTotal();
        actualizarTotalGeneral();


        document.getElementById('servicio_id').addEventListener('change', function () {
            const servicio = servicios.find(s => s.id == this.value);
            if (servicio) {
                document.getElementById('servicio_nombre').innerText = servicio.nombre;
                document.getElementById('servicio_descripcion').innerText = servicio.descripcion;
                document.getElementById('servicio_precio').innerText = Number(servicio.precio).toFixed(2)
                document.getElementById('servicio_detalle_card').classList.remove('d-none');
                document.getElementById('servicio_detalle_card').dataset.id = servicio.id;
            }
        });

        function agregarServicio() {
            const card = document.getElementById('servicio_detalle_card');
            const servicioId = card.dataset.id;
            const servicio = servicios.find(s => s.id == servicioId);

            if (!servicio) return;

            // Evitar agregar repetidos
            if (serviciosAgregados.find(s => s.id == servicio.id)) {
                alertify.error('Este servicio ya fue agregado.');
                return;
            }

            serviciosAgregados.push(servicio);
            actualizarTabla();
            actualizarTotalGeneral();
        }

        function actualizarTabla() {
            const tbody = document.querySelector('#tabla_servicios tbody');
            tbody.innerHTML = '';

            serviciosAgregados.forEach((servicio, index) => {
                const fila = document.createElement('tr');
                fila.innerHTML = `
                                                                                                                                                                                                                                                                                                                                                    <td>${servicio.nombre}</td>
                                                                                                                                                                                                                                                                                                                                                    <td>Bs. ${Number(servicio.precio).toFixed(2)}</td>
                                                                                                                                                                                                                                                                                                                                                    <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarServicio(${index})">Eliminar</button></td>
                                                                                                                                                                                                                                                                                                                                                `;
                tbody.appendChild(fila);
            });

            document.getElementById('servicios_seleccionados').value = JSON.stringify(serviciosAgregados.map(s => s.id));

            // Mostrar u ocultar la card de total
            if (serviciosAgregados.length > 0) {
                document.getElementById('total_servicios_card').classList.remove('d-none');
            } else {
                document.getElementById('total_servicios_card').classList.add('d-none');
            }
        }

        function eliminarServicio(index) {
            serviciosAgregados.splice(index, 1);
            actualizarTabla();
            actualizarTotalGeneral();
            actualizarTotal();
        }




    </script>
    <script>


        document.getElementById('inventario_id').addEventListener('change', function () {
            const item = inventario.find(i => i.id == this.value);
            if (item) {
                document.getElementById('inv_nombre').innerText = item.nombre;
                document.getElementById('inv_descripcion').innerText = item.descripcion;
                document.getElementById('inv_precio').innerText = Number(item.precio_unitario).toFixed(2);
                document.getElementById('inv_stock').innerText = item.stock_actual;
                document.getElementById('inv_unidad').innerText = item.unidad_medida;
                document.getElementById('inv_cantidad').value = 1;
                document.getElementById('inventario_detalle_card').classList.remove('d-none');
                document.getElementById('inventario_detalle_card').dataset.id = item.id;
            }
        });

        function agregarInventario() {
            const card = document.getElementById('inventario_detalle_card');
            const id = card.dataset.id;
            const item = inventario.find(i => i.id == id);
            const cantidad = parseFloat(document.getElementById('inv_cantidad').value);

            if (!item || isNaN(cantidad) || cantidad <= 0) {
                alertify.error('Cantidad inválida.');
                return;
            }

            if (cantidad > item.stock_actual) {
                alertify.error('La cantidad supera el stock disponible.');
                return;
            }

            if (inventarioUsado.find(i => i.id == item.id)) {
                alertify.error('Este ítem ya fue agregado.');
                return;
            }

            inventarioUsado.push({
                id: item.id,
                nombre: item.nombre,
                precio_unitario: item.precio_unitario,
                unidad_medida: item.unidad_medida,
                cantidad: cantidad,
                subtotal: (item.precio_unitario * cantidad)
            });

            actualizarInventarioTabla();
            actualizarInventarioTotal();
            actualizarTotalGeneral();
            actualizarTotal();

        }



        function eliminarInventario(index) {
            inventarioUsado.splice(index, 1);
            actualizarInventarioTabla();
            actualizarInventarioTotal();
            actualizarTotal();
            actualizarTotalGeneral();
        }


    </script>

@endsection