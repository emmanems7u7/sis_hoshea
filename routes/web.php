<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ConfCorreoController;
use App\Http\Controllers\CorreoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ConfiguracionCredencialesController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BienController;

use App\Http\Controllers\UserPersonalizacionController;
use App\Http\Controllers\ServicioController;
//Artisan 



Route::middleware(['auth', 'can:ejecutar-artisan'])->group(function () {

    Route::get('/artisan-panel', [ArtisanController::class, 'verificacion'])->name('artisan.index');

    Route::post('/artisan-panel', [ArtisanController::class, 'index'])->name('artisan.verificar');

    Route::post('/artisan/run', [ArtisanController::class, 'run'])->name('artisan.run');
});

Route::get('/', function () {
    return redirect('/login');
});

//notificaciones


Route::get('/notification/{notification}/markAsRead', [NotificationController::class, 'markAsRead'])->name('notification.markAsRead');


Route::post('/guardar-color-sidebar', [UserPersonalizacionController::class, 'guardarSidebarColor'])->middleware('auth');
Route::post('/user/personalizacion/sidebar-type', [UserPersonalizacionController::class, 'updateSidebarType'])->middleware('auth');
Route::post('/user/preferences', [UserPersonalizacionController::class, 'updateDark'])->middleware('auth');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::middleware(['auth', 'can:Administración de Usuarios'])->group(function () {

    Route::get('/usuarios', [UserController::class, 'index'])
        ->name('users.index')
        ->middleware('can:usuarios.ver');

    Route::get('/usuarios/crear', [UserController::class, 'create'])
        ->name('users.create')
        ->middleware('can:usuarios.crear');

    Route::post('/usuarios', [UserController::class, 'store'])
        ->name('users.store')
        ->middleware('can:usuarios.crear');

    Route::get('/usuarios/{user}', [UserController::class, 'show'])
        ->name('users.show')
        ->middleware('can:usuarios.ver');

    Route::get('/usuarios/edit/{id}', [UserController::class, 'edit'])
        ->name('users.edit')
        ->middleware('can:usuarios.editar');

    Route::put('/usuarios/{id}/{perfil}', [UserController::class, 'update'])
        ->name('users.update')
        ->middleware('can:usuarios.editar');

    Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('can:usuarios.eliminar');

    Route::get('/datos/usuario/{id}', [UserController::class, 'GetUsuario'])
        ->name('users.get')
        ->middleware('can:usuarios.ver');

    Route::get('/usuarios/exportar/excel', [UserController::class, 'exportExcel'])->name('usuarios.exportar_excel')->middleware(middleware: 'can:usuarios.exportar_excel');
    Route::get('/usuarios/exportar/pdf', [UserController::class, 'exportPDF'])->name('usuarios.exportar_pdf')->middleware('can:usuarios.exportar_pdf');


});



//Rutas para secciones
Route::resource('secciones', SeccionController::class)->except([
    'show',




])->middleware(['auth', 'role:admin']);

Route::post('/api/sugerir-icono', [SeccionController::class, 'SugerirIcono']);

Route::post('obtener/dato/menu', [SeccionController::class, 'cambiarSeccion'])->middleware(['auth', 'role:admin']);
//Rutas para Menus
Route::resource('menus', MenuController::class)->except([
    'show',
])->middleware(['auth', 'role:admin']);


// Rutas para la configuracion de correo

Route::middleware(['auth', 'can:Configuración'])->group(function () {

    Route::get('/configuracion/correo', [ConfCorreoController::class, 'index'])
        ->name('configuracion.correo.index')
        ->middleware('can:configuracion_correo.ver');

    Route::post('/configuracion/correo/guardar', [ConfCorreoController::class, 'store'])
        ->name('configuracion.correo.store')
        ->middleware('can:configuracion_correo.actualizar');

    Route::get('/correo/prueba', [ConfCorreoController::class, 'enviarPrueba'])
        ->name('correo.prueba');

    Route::get('/correos/plantillas', [CorreoController::class, 'index'])
        ->name('correos.index')
        ->middleware('can:plantillas.ver');

    Route::put('/editar/plantilla/{id}', [CorreoController::class, 'update_plantilla'])
        ->name('plantilla.update')
        ->middleware('can:plantillas.actualizar');

    Route::get('/obtener/plantilla/{id}', [CorreoController::class, 'GetPlantilla'])
        ->name('obtener.correo');

});

Route::get('configuracion_correo', [ConfCorreoController::class, 'index'])->name('configuracion_correo.index');
Route::put('configuracion_correo', [ConfCorreoController::class, 'update'])->name('configuracion_correo.update');


//cambio de contraseña
Route::middleware(['auth'])->group(function () {

    Route::get('/usuario/contraseña', [PasswordController::class, 'ActualizarContraseña'])->name('user.actualizar.contraseña');
    Route::put('password/update', [PasswordController::class, 'update'])->name('password.actualizar');

    Route::get('/usuario/perfil', [UserController::class, 'Perfil'])
        ->name('perfil');
});



Route::middleware(['auth'])->group(function () {

    Route::get('/roles', [RoleController::class, 'index'])
        ->name('roles.index')
        ->middleware('can:roles.inicio');

    Route::get('/roles/create', [RoleController::class, 'create'])
        ->name('roles.create')
        ->middleware('can:roles.crear');

    Route::post('/roles', [RoleController::class, 'store'])
        ->name('roles.store')
        ->middleware('can:roles.guardar');

    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])
        ->name('roles.edit')
        ->middleware('can:roles.editar');

    Route::put('/roles/{id}', [RoleController::class, 'update'])
        ->name('roles.update')
        ->middleware('can:roles.actualizar');

    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])
        ->name('roles.destroy')
        ->middleware('can:roles.eliminar');

    Route::get('/permissions', [PermissionController::class, 'index'])
        ->name('permissions.index')
        ->middleware('can:permisos.inicio');

    Route::get('/permissions/create', [PermissionController::class, 'create'])
        ->name('permissions.create')
        ->middleware('can:permisos.crear');

    Route::post('/permissions', [PermissionController::class, 'store'])
        ->name('permissions.store')
        ->middleware('can:permisos.guardar');

    Route::get('/permissions/edit/{id}', [PermissionController::class, 'edit'])
        ->name('permissions.edit')
        ->middleware('can:permisos.editar');

    Route::put('/permissions/{id}', [PermissionController::class, 'update'])
        ->name('permissions.update')
        ->middleware('can:permisos.actualizar');

    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])
        ->name('permissions.destroy')
        ->middleware('can:permisos.eliminar');

    Route::get('/permissions/cargar/menu/{id}/{rol_id}', [RoleController::class, 'get_permisos_menu'])
        ->name('permissions.menu');

});




//Rutas configuracion general

Route::middleware(['auth', 'can:Configuración General'])->group(function () {

    Route::get('/admin/configuracion', [ConfiguracionController::class, 'edit'])
        ->name('admin.configuracion.edit')
        ->middleware('can:configuracion.inicio');

    Route::put('/admin/configuracion', [ConfiguracionController::class, 'update'])
        ->name('admin.configuracion.update')
        ->middleware('can:configuracion.actualizar');

});

Route::middleware(['auth'])->group(function () {

    Route::get('/configuracion/credenciales', [ConfiguracionCredencialesController::class, 'index'])->name('configuracion.credenciales.index')->middleware('can:configuracion.credenciales_ver');
    Route::post('/configuracion/credenciales/actualizar', [ConfiguracionCredencialesController::class, 'actualizar'])->name('configuracion.credenciales.actualizar')->middleware('can:configuracion.credenciales_actualizar');

});


//doble factor de autenticacion
Route::get('/2fa/verify', [TwoFactorController::class, 'index'])->name('verify.index');
Route::post('/2fa/verify', [TwoFactorController::class, 'store'])->name('verify.store');
Route::post('/2fa/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');

//Catalogo


Route::middleware(['auth'])->group(function () {


    Route::post('/secciones/ordenar', [SeccionController::class, 'ordenar'])->name('secciones.ordenar');

});
Route::middleware(['auth', 'can:Administración y Parametrización'])->group(function () {

    // Rutas para catalogos
    Route::get('/catalogos', [CatalogoController::class, 'index'])->name('catalogos.index')->middleware('can:catalogo.ver');
    Route::get('/catalogos/create', [CatalogoController::class, 'create'])->name('catalogos.create')->middleware('can:catalogo.crear');
    Route::post('/catalogos', [CatalogoController::class, 'store'])->name('catalogos.store')->middleware('can:catalogo.guardar');
    Route::get('/catalogos/{id}', [CatalogoController::class, 'show'])->name('catalogos.show')->middleware('can:catalogo.ver_detalle');
    Route::get('/catalogos/{id}/edit', [CatalogoController::class, 'edit'])->name('catalogos.edit')->middleware('can:catalogo.editar');
    Route::put('/catalogos/{id}', [CatalogoController::class, 'update'])->name('catalogos.update')->middleware('can:catalogo.actualizar');
    Route::delete('/catalogos/{id}', [CatalogoController::class, 'destroy'])->name('catalogos.destroy')->middleware('can:catalogo.eliminar');

    // Rutas para categorias

    Route::get('cat/categorias/create', [CategoriaController::class, 'create'])->name('cat_categorias.create')->middleware('can:categoria.crear');
    Route::post('cat/categorias', [CategoriaController::class, 'store'])->name('cat_categorias.store')->middleware('can:categoria.guardar');
    Route::get('cat/categorias/{id}', [CategoriaController::class, 'show'])->name('cat_categorias.show')->middleware('can:categoria.ver_detalle');
    Route::get('cat/categorias/{id}/edit', [CategoriaController::class, 'edit'])->name('cat_categorias.edit')->middleware('can:categoria.editar');
    Route::put('cat/categorias/{id}', [CategoriaController::class, 'update'])->name('cat_categorias.update')->middleware('can:categoria.actualizar');
    Route::delete('cat/categorias/{id}', [CategoriaController::class, 'destroy'])->name('cat_categorias.destroy')->middleware('can:categoria.eliminar');

    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index')->middleware('can:categoria.ver');
    Route::get('/categorias/create', [CategoriaController::class, 'create_'])->name('categorias.create')->middleware('can:categoria.crear');
    Route::post('/categorias', [CategoriaController::class, 'store_'])->name('categorias.store')->middleware('can:categoria.guardar');
    Route::get('/categorias/{id}', [CategoriaController::class, 'show_'])->name('categorias.show')->middleware('can:categoria.ver_detalle');
    Route::get('/categorias/{id}/edit', [CategoriaController::class, 'edit_'])->name('categorias.edit')->middleware('can:categoria.editar');
    Route::put('/categorias/{id}', [CategoriaController::class, 'update_'])->name('categorias.update')->middleware('can:categoria.actualizar');
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy_'])->name('categorias.destroy')->middleware('can:categoria.eliminar');

});



//------------------------LOGICA DE NEGOCIO--------------------------------

/* -------------------- PACIENTES -------------------- */
Route::get('/pacientes', [PacienteController::class, 'index'])->name('pacientes.index')->middleware('can:pacientes.ver');
Route::get('/pacientes/create', [PacienteController::class, 'create'])->name('pacientes.create')->middleware('can:pacientes.crear');
Route::post('/pacientes', [PacienteController::class, 'store'])->name('pacientes.store')->middleware('can:pacientes.guardar');
Route::get('/pacientes/{paciente}', [PacienteController::class, 'show'])->name('pacientes.show')->middleware('can:pacientes.ver_detalle');
Route::get('/pacientes/{paciente}/edit', [PacienteController::class, 'edit'])->name('pacientes.edit')->middleware('can:pacientes.editar');
Route::put('/pacientes/{paciente}', [PacienteController::class, 'update'])->name('pacientes.update')->middleware('can:pacientes.actualizar');
Route::delete('/pacientes/{paciente}', [PacienteController::class, 'destroy'])->name('pacientes.destroy')->middleware('can:pacientes.eliminar');
Route::get('/pacientes/{paciente}/datos', [PacienteController::class, 'datos'])->name('pacientes.datos');

/* -------------------- INVENTARIO -------------------- */
Route::prefix('inventario')->group(function () {
    Route::get('/', [InventarioController::class, 'index'])->name('inventario.index')->middleware('can:inventario.ver');
    Route::get('/crear', [InventarioController::class, 'create'])->name('inventario.create')->middleware('can:inventario.crear');
    Route::post('/', [InventarioController::class, 'store'])->name('inventario.store')->middleware('can:inventario.guardar');
    Route::get('/{inventario}/editar', [InventarioController::class, 'edit'])->name('inventario.edit')->middleware('can:inventario.editar');
    Route::put('/{inventario}', [InventarioController::class, 'update'])->name('inventario.update')->middleware('can:inventario.actualizar');
    Route::delete('/{inventario}', [InventarioController::class, 'destroy'])->name('inventario.destroy')->middleware('can:inventario.eliminar');
});


/* -------------------- TRATAMIENTOS -------------------- */
Route::prefix('tratamientos')->name('tratamientos.')->group(function () {
    Route::get('/', [TratamientoController::class, 'index'])->name('index')->middleware('can:tratamientos.ver');
    Route::get('/crear', [TratamientoController::class, 'create'])->name('create')->middleware('can:tratamientos.crear');
    Route::post('/', [TratamientoController::class, 'store'])->name('store')->middleware('can:tratamientos.guardar');
    Route::get('/{tratamiento}/editar', [TratamientoController::class, 'edit'])->name('edit')->middleware('can:tratamientos.editar');
    Route::put('/{tratamiento}', [TratamientoController::class, 'update'])->name('update')->middleware('can:tratamientos.actualizar');
    Route::delete('/{tratamiento}', [TratamientoController::class, 'destroy'])->name('destroy')->middleware('can:tratamientos.eliminar');
    Route::get('/actuales', [TratamientoController::class, 'tratamientosFechaHoy'])->name('actuales');

    Route::get('/export/pdf', [TratamientoController::class, 'exportPDF'])->name('exportPDF');

    Route::get('/{cita}/gestion/cita/{tipo}', [TratamientoController::class, 'Gestion'])->name('gestion_cita');
    Route::get('/{tratamiento}/finalizar', [TratamientoController::class, 'finalizar'])->name('finalizar')->middleware('can:tratamientos.finalizar');

    Route::post('/tratamientos/{tratamiento}/observaciones', [TratamientoController::class, 'guardarObservacion'])
        ->name('guardarObservacion')->middleware('can:tratamientos.agregar_observacion');



});

/* -------------------- CITAS -------------------- */
Route::prefix('citas')->name('citas.')->group(function () {
    Route::get('/', [CitaController::class, 'index'])->name('index')->middleware('can:citas.ver');
    Route::get('/crear', [CitaController::class, 'create'])->name('create')->middleware('can:citas.crear');
    Route::post('/', [CitaController::class, 'store'])->name('store')->middleware('can:citas.guardar');
    Route::get('/{cita}/editar', [CitaController::class, 'edit'])->name('edit')->middleware('can:citas.editar');
    Route::put('/{cita}', [CitaController::class, 'update'])->name('update')->middleware('can:citas.actualizar');
    Route::delete('/{cita}', [CitaController::class, 'destroy'])->name('destroy')->middleware('can:citas.eliminar');
    Route::put('/', [CitaController::class, 'cambiar_estado'])->name('cambiarEstado')->middleware('can:citas.cambiar_estado');

    Route::post('gestion/{cita}', [CitaController::class, 'store_gestion'])->name('gestion')->middleware('can:citas.guardar_gestion');

    // Ruta para obtener los datos de una cita para edición (AJAX)
    Route::get('editar-gestion/{cita}', [CitaController::class, 'edit_gestion'])->name('editar_gestion');

    Route::put('update-gestion/{cita}', [CitaController::class, 'update_gestion'])->name('update_gestion');

    Route::get('ver-gestion/{cita}', [CitaController::class, 'ver_gestion'])->name('ver_gestion');
    Route::post('hoja/{cita}', [CitaController::class, 'store_hoja'])->name('hoja');


    Route::put('update-hoja/{cita}', [CitaController::class, 'update_hoja'])->name('update_hoja');

    Route::get('/cita/examenes/{id}', [CitaController::class, 'getExamenes'])->name('examenes');

    Route::get('/cita/exportar-gestion/{cita}', [CitaController::class, 'exportPDFCita'])->name('export_gestion');

    Route::get('/cita/exportar-hoja/{cita}', [CitaController::class, 'exportPDF_Hoja_lab'])->name('export_hoja');

    Route::get('/{cita}/gestionar/cita', [CitaController::class, 'Gestion'])->name('gestion_cita');


    Route::post('/validar-cita', [CitaController::class, 'validarConflictoAjax'])->name('validar.ajax');

});

Route::prefix('tratamientos')->group(function () {
    Route::get('diagnosticos', [DiagnosticoController::class, 'index'])->name('diagnosticos.index');
    Route::get('diagnosticos/create', [DiagnosticoController::class, 'create'])->name('diagnosticos.create');
    Route::post('diagnosticos', [DiagnosticoController::class, 'store'])->name('diagnosticos.store');
    Route::delete('diagnosticos/{diagnostico}', [DiagnosticoController::class, 'destroy'])->name('diagnosticos.destroy');
    Route::post('/diagnostico/guardar_ajax', [DiagnosticoController::class, 'store_ajax'])->name('diagnostico.store_ajax');


});



Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
Route::get('/servicios/create', [ServicioController::class, 'create'])->name('servicios.create');
Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');
Route::get('/servicios/{servicio}/edit', [ServicioController::class, 'edit'])->name('servicios.edit');
Route::put('/servicios/{servicio}', [ServicioController::class, 'update'])->name('servicios.update');
Route::delete('/servicios/{servicio}', [ServicioController::class, 'destroy'])->name('servicios.destroy');

Route::get('/servicios/{servicio}/edit', [ServicioController::class, 'edit'])->name('servicios.edit');
Route::get('/servicios/asignar/{cita}/', [ServicioController::class, 'asignar'])->name('servicios.asignar');
Route::post('/guardar/asignacion/{cita}', [ServicioController::class, 'guardar_asignacion'])->name('servicios.guardar_asignacion');
Route::get('/servicios/show/{cita}', [ServicioController::class, 'show'])->name('servicios.show');

Route::get('/servicios/recibo/{cita}', [ServicioController::class, 'recibo'])->name('servicios.recibo');


Route::get('/departamentos/{paisCodigo}', function ($paisCodigo) {
    return \App\Models\Catalogo::where('catalogo_parent', $paisCodigo)
        ->where('catalogo_estado', 1)
        ->get(['catalogo_codigo', 'catalogo_descripcion']);
});

Route::get('/ciudades/{departamentoCodigo}', function ($departamentoCodigo) {
    return \App\Models\Catalogo::where('catalogo_parent', $departamentoCodigo)
        ->where('catalogo_estado', 1)
        ->get(['catalogo_codigo', 'catalogo_descripcion']);
});

Route::get('tratamientos/{tratamiento}/diagnosticos/{diagnostico}/edit', [DiagnosticoController::class, 'edit'])->name('diagnosticos.edit');
Route::put('tratamientos/{tratamiento}/diagnosticos/{diagnostico}', [DiagnosticoController::class, 'update'])->name('diagnosticos.update');



Route::prefix('biens')->name('biens.')->group(function () {
    Route::get('/', [BienController::class, 'index'])->name('index');
    Route::get('/create', [BienController::class, 'create'])->name('create');
    Route::post('/', [BienController::class, 'store'])->name('store');
    Route::get('/{bien}/edit', [BienController::class, 'edit'])->name('edit');
    Route::put('/{bien}', [BienController::class, 'update'])->name('update');
    Route::delete('/{bien}', [BienController::class, 'destroy'])->name('destroy');
    Route::get('/export', [BienController::class, 'exportPDFBienes'])->name('export_pdf');


});

