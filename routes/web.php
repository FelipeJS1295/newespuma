<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Importa la clase Auth correctamente
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DespachoController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ConciliacionBancariaController;
use App\Http\Controllers\MovimientoProduccionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TicketController;

// Ruta para el login predeterminado
Route::get('/', function () {
    if (Auth::check()) { // Utiliza la clase Auth importada
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login'); // Redirige al usuario a la página de login o cualquier otra página que desees
})->name('logout');

// Autenticación
Auth::routes(); // Incluye todas las rutas de autenticación necesarias

// Proteger rutas de acceso si el usuario no está autenticado
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::resource('clientes', ClienteController::class)->middleware('auth');
Route::resource('productos', ProductoController::class)->middleware('auth');
Route::resource('produccion', ProduccionController::class)->middleware('auth');
Route::get('inventarios', [InventarioController::class, 'index'])->name('inventarios.index')->middleware('auth');
Route::resource('inventarios', InventarioController::class);
Route::delete('/producciones/{id}', [ProduccionController::class, 'destroy'])->name('producciones.destroy');
Route::resource('ventas', VentaController::class)->middleware('auth');
Route::get('despacho', [DespachoController::class, 'index'])->name('despacho.index')->middleware('auth');
Route::get('reportes', [ReportesController::class, 'index'])->name('reportes.index')->middleware('auth');
Route::get('caja', [CajaController::class, 'index'])->name('caja.index')->middleware('auth');
Route::resource('usuarios', UsuarioController::class)->middleware('auth');
Route::get('produccion/create/{id}', [ProduccionController::class, 'create'])->name('produccion.create');
Route::post('produccion/store/{id}', [ProduccionController::class, 'store'])->name('produccion.store');

Route::get('ventas/{id}/agregar-pago', [VentaController::class, 'addPayment'])->name('ventas.addPayment');
Route::post('ventas/{id}/agregar-pago', [VentaController::class, 'storePayment'])->name('ventas.storePayment');

Route::get('ventas/{id}/agregar-documento', [VentaController::class, 'addDocument'])->name('ventas.addDocument');
Route::post('ventas/{id}/agregar-documento', [VentaController::class, 'storeDocument'])->name('ventas.storeDocument');

Route::get('/ventas/{id}', [VentaController::class, 'show'])->name('ventas.show');
Route::get('/ventas/{id}/pagos', [VentaController::class, 'showPayments'])->name('ventas.showPayments');

Route::get('/despacho/{venta}/create', [DespachoController::class, 'create'])->name('despacho.create');
Route::post('/despacho/{venta}/store', [DespachoController::class, 'store'])->name('despacho.store');

Route::get('/ventas/cuadrar/{id}', [VentaController::class, 'cuadrar'])->name('ventas.cuadrar');


Route::prefix('conciliacion-bancaria')->group(function () {
    Route::get('/', [ConciliacionBancariaController::class, 'index'])->name('conciliacion.index');
    Route::get('/create', [ConciliacionBancariaController::class, 'create'])->name('conciliacion.create');
    Route::post('/store', [ConciliacionBancariaController::class, 'store'])->name('conciliacion.store');
    Route::get('/{id}/edit', [ConciliacionBancariaController::class, 'edit'])->name('conciliacion.edit');
    Route::put('/{id}', [ConciliacionBancariaController::class, 'update'])->name('conciliacion.update');
    Route::delete('/{id}', [ConciliacionBancariaController::class, 'destroy'])->name('conciliacion.destroy');
    Route::post('/conciliacion/salida', [ConciliacionBancariaController::class, 'storeSalida'])->name('conciliacion.storeSalida');
    Route::post('/conciliacion/salida/store', [ConciliacionBancariaController::class, 'storeSalida'])->name('conciliacion.storeSalida');

    Route::get('/conciliacion/salida/create', [ConciliacionBancariaController::class, 'createSalida'])->name('conciliacion.createSalida');
    Route::post('/conciliacion/salida/store', [ConciliacionBancariaController::class, 'storeSalida'])->name('conciliacion.storeSalida');
});

Route::get('/eliminar-duplicados', [MovimientoProduccionController::class, 'eliminarDuplicados'])->name('eliminar.duplicados');

Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
Route::get('reportes/produccion', [ReporteController::class, 'reporteProduccion'])->name('reportes.produccion');
Route::get('reportes/movimientos', [ReporteController::class, 'reporteMovimientos'])->name('reportes.movimientos');
Route::get('reportes/ventas', [ReporteController::class, 'reporteVentas'])->name('reportes.ventas');
Route::get('reportes/despachos', [ReporteController::class, 'reporteDespachos'])->name('reportes.despachos');
Route::get('reportes/financiero', [ReporteController::class, 'reporteFinanciero'])->name('reportes.financiero');
Route::get('/reporte-ventas', [ReporteController::class, 'reporteVentas'])->name('reporte.ventas');

Route::get('/ventas/excel', [VentaController::class, 'exportExcel'])->name('ventas.excel');

Route::get('/despacho/{venta}/create', [DespachoController::class, 'create'])->name('despacho.create');


Route::resource('tickets', TicketController::class);
Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index'); // Ver todos los tickets
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create'); // Formulario para crear un nuevo ticket
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store'); // Guardar un nuevo ticket
Route::get('/tickets/{ticket}/respuesta', [TicketController::class, 'respuesta'])->name('tickets.respuesta'); // Formulario para responder a un ticket
Route::post('/tickets/{ticket}/respuesta', [TicketController::class, 'guardarRespuesta'])->name('tickets.guardarRespuesta'); // Guardar la respuesta del ticket

// Ruta para mostrar el formulario de respuesta
Route::get('/tickets/{id}/respuesta', [TicketController::class, 'respuesta'])->name('tickets.respuesta');

// Ruta para guardar la respuesta del ticket
Route::post('/tickets/{id}/guardar-respuesta', [TicketController::class, 'guardarRespuesta'])->name('tickets.respond');