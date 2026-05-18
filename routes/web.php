<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\EmpleadoController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\PeliculaController;
use App\Http\Controllers\Admin\EventoController;
use App\Http\Controllers\Admin\FuncionController;
use App\Http\Controllers\Admin\AuditoriaController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Taquillero\VentaController;

// ─── Raíz → login ───────────────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'));

// ─── Autenticación ───────────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');


// ─── ADMIN ───────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('role:ADMIN,SUPER')->group(function () {

    Route::get('/dashboard', [EmpleadoController::class, 'dashboard'])->name('dashboard');

    Route::resource('empleados', EmpleadoController::class);
    Route::resource('usuarios',  UsuarioController::class);
    Route::resource('peliculas', PeliculaController::class);
    Route::resource('eventos',   EventoController::class);
    Route::resource('funciones', FuncionController::class);

    Route::get('funciones/{id}/historial-precios', [FuncionController::class, 'historialPrecios'])
         ->name('funciones.historial');

    // Auditoría
    Route::prefix('auditoria')->name('auditoria.')->group(function () {
        Route::get('/',          [AuditoriaController::class, 'index'])->name('index');
        Route::get('/empleados', [AuditoriaController::class, 'empleados'])->name('empleados');
        Route::get('/usuarios',  [AuditoriaController::class, 'usuarios'])->name('usuarios');
        Route::get('/peliculas', [AuditoriaController::class, 'peliculas'])->name('peliculas');
        Route::get('/funciones', [AuditoriaController::class, 'funciones'])->name('funciones');
        Route::get('/ventas',    [AuditoriaController::class, 'ventas'])->name('ventas');
    });

    // Reportes
    Route::get('reportes',          [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/exportar', [ReporteController::class, 'exportarVentas'])->name('reportes.exportar');
});


// ─── TAQUILLERO ──────────────────────────────────────────────────────────────
Route::prefix('taquillero')->name('taquillero.')->middleware('role:TAQUILLERO,ADMIN,SUPER')->group(function () {

    Route::get('/dashboard', [VentaController::class, 'dashboard'])->name('dashboard');
    Route::get('/funciones', [VentaController::class, 'funciones'])->name('funciones');
    Route::get('/venta/{funcion}', [VentaController::class, 'nuevaVenta'])->name('venta.nueva');
    Route::post('/venta', [VentaController::class, 'confirmarVenta'])->name('venta.confirmar');
    Route::get('/historial', [VentaController::class, 'historial'])->name('historial');
});
