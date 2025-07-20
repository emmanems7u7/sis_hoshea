@extends('tratamientos.gestion_cita_t')

@section('contenido_cita')

    <a target="_blank" href="{{ route('servicios.recibo', $cita) }}" class="btn-sm btn btn-danger">Generar Recibo</a>

    <!-- TABLA DE SERVICIOS AGREGADOS -->
    <div class="table-responsive mt-4">
        <h6>Servicios de la cita</h6>
        <table class="table table-bordered" id="tabla_servicios">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div> <!-- CONTENEDOR A LA DERECHA -->
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

    <div class="table-responsive mt-4">
        <h6>Productos usados en la cita</h6>

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


    <!-- CARD PARA MOSTRAR EL TOTAL -->
    <div id="total_servicios_card" class="card mt-3 d-none">
        <div class="card-body">
            <h5>Total Servicios</h5>
            <p class="h4">Bs. <span id="total_precio">0.00</span></p>
        </div>
    </div>
    <input type="hidden" name="servicios_seleccionados" id="servicios_seleccionados">


    <script>
        const servicios = @json($serviciosDetalles);
        const serviciosAgregados = [];
        const inventario = @json($inventarioDetalles); // array con todos los detalles
        const inventarioUsado = [];


        @if ($cita->servicios->isNotEmpty())
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


                if (inventarioUsado.length > 0) {
                    document.getElementById('total_inventario_card').classList.remove('d-none');
                } else {
                    document.getElementById('total_inventario_card').classList.add('d-none');
                }
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
        actualizarTabla();
        actualizarInventarioTabla();
        actualizarTotal();
        actualizarInventarioTotal();
        actualizarTotalGeneral();


        function actualizarTabla() {
            const tbody = document.querySelector('#tabla_servicios tbody');
            tbody.innerHTML = '';

            serviciosAgregados.forEach((servicio, index) => {
                const fila = document.createElement('tr');
                fila.innerHTML = `
                                                                                                                                                                                        <td>${servicio.nombre}</td>
                                                                                                                                                                                        <td>Bs. ${Number(servicio.precio).toFixed(2)}</td>

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



        function actualizarTotal() {
            const total = serviciosAgregados.reduce((acc, servicio) => acc + Number(servicio.precio), 0);
            document.getElementById('total_precio').innerText = total.toFixed(2);
        }
    </script>
@endsection