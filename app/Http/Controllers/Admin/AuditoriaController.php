<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AuditoriaController extends Controller
{
    public function index()
    {
        // Últimos 10 registros de cada tabla de auditoría
        $recientes = collect([
            'Empleados' => DB::table('auditoria_empleado')->orderBy('fecha_cambio','desc')->limit(5)->get(),
            'Usuarios'  => DB::table('auditoria_usuario')->orderBy('fecha_cambio','desc')->limit(5)->get(),
            'Películas' => DB::table('auditoria_pelicula')->orderBy('fecha_cambio','desc')->limit(5)->get(),
            'Funciones' => DB::table('auditoria_funcion')->orderBy('fecha_cambio','desc')->limit(5)->get(),
            'Ventas'    => DB::table('auditoria_venta')->orderBy('fecha_cambio','desc')->limit(5)->get(),
        ]);
        return view('admin.auditoria.index', compact('recientes'));
    }

    public function empleados()
    {
        $registros = DB::table('auditoria_empleado')->orderBy('fecha_cambio','desc')->paginate(20);
        return view('admin.auditoria.empleados', compact('registros'));
    }

    public function usuarios()
    {
        $registros = DB::table('auditoria_usuario')->orderBy('fecha_cambio','desc')->paginate(20);
        return view('admin.auditoria.usuarios', compact('registros'));
    }

    public function peliculas()
    {
        $registros = DB::table('auditoria_pelicula')->orderBy('fecha_cambio','desc')->paginate(20);
        return view('admin.auditoria.peliculas', compact('registros'));
    }

    public function funciones()
    {
        $registros = DB::table('auditoria_funcion')->orderBy('fecha_cambio','desc')->paginate(20);
        return view('admin.auditoria.funciones', compact('registros'));
    }

    public function ventas()
    {
        $registros = DB::table('auditoria_venta')->orderBy('fecha_cambio','desc')->paginate(20);
        return view('admin.auditoria.ventas', compact('registros'));
    }
}