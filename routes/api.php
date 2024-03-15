<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\PuestosController;
use App\Http\Controllers\SucursalesController;
use App\Http\Controllers\CatalogoBancoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'prefix' => 'areas',
], function ($router) {
    Route::post('crear-area', [AreasController::class, 'crearAreas']);
    Route::post('actualizar-area', [AreasController::class, 'actualizarArea']);
    Route::get('consultar-areas', [AreasController::class, 'consultarAreas']);
    Route::delete('eliminar-area/{id_area}', [AreasController::class, 'eliminarArea']);
});

Route::group([
    'prefix' => 'puestos',
], function ($router) {
    Route::post('crear-puesto', [PuestosController::class, 'crearPuesto']);
    Route::post('actualizar-puesto', [PuestosController::class, 'actualizarPuesto']);
    Route::get('consultar-puestos', [PuestosController::class, 'consultarPuestos']);
    Route::get('consultar-puestoByArea/{id_area}', [PuestosController::class, 'consultarPuestosPorArea']);
    Route::delete('eliminar-puesto/{id_puesto}', [PuestosController::class, 'eliminarPuesto']);
});

Route::group([
    'prefix' => 'sucursales',
], function ($router) {
    Route::get('consultar-sucursales', [SucursalesController::class, 'consultarSucursales']);
    Route::delete('eliminar-sucursal/{id}', [SucursalesController::class, 'eliminarSucursal']);
    Route::post('crear-sucursal', [SucursalesController::class, 'crearSucursal']);
    Route::post('crear-sucursal-suscripcion', [SucursalesController::class, 'crearSucursalSuscripcion']);
    Route::put('actualizar-sucursal', [SucursalesController::class, 'actualizarSucursal']);
    Route::post('agregar-almacen-sucursal', [SucursalesController::class, 'agregarAlmacenSucursal']);
    Route::put('actualizar-almacen-sucursal', [SucursalesController::class, 'actualizarAlmacenSucursal']);
});

Route::group([
    'prefix' => 'catalogo-bancos',
], function ($router) {
    Route::get('consultar', [CatalogoBancoController::class, 'consultarCatalogo']);
});