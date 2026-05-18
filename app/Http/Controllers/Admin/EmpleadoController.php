<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpleadoController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'empleados' => DB::table('empleado')->count(),
            'peliculas' => DB::table('pelicula')->count(),
            'funciones' => DB::table('funcion')->whereDate('fecha', '>=', today())->count(),
            'ventas_hoy'=> DB::table('venta')->whereDate('fecha', today())->sum('total'),
        ];
        return view('admin.dashboard', compact('stats'));
    }

    public function index()
    {
        $empleados = DB::table('empleado')->orderBy('id_empleado')->paginate(15);
        return view('admin.empleados.index', compact('empleados'));
    }

    public function create()
    {
        return view('admin.empleados.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:100']);
        DB::table('empleado')->insert(['nombre' => $request->nombre]);
        return redirect()->route('admin.empleados.index')->with('success', 'Empleado creado correctamente.');
    }

    public function edit($id)
    {
        $empleado = DB::table('empleado')->where('id_empleado', $id)->first();
        abort_if(!$empleado, 404);
        return view('admin.empleados.edit', compact('empleado'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nombre' => 'required|string|max:100']);
        DB::table('empleado')->where('id_empleado', $id)->update(['nombre' => $request->nombre]);
        return redirect()->route('admin.empleados.index')->with('success', 'Empleado actualizado.');
    }

    public function destroy($id)
    {
        // Verificar si tiene usuarios asociados
        $tieneUsuario = DB::table('usuario')->where('id_empleado', $id)->exists();
        if ($tieneUsuario) {
            return back()->with('error', 'No se puede eliminar: el empleado tiene usuarios asociados.');
        }
        DB::table('empleado')->where('id_empleado', $id)->delete();
        return redirect()->route('admin.empleados.index')->with('success', 'Empleado eliminado.');
    }

    public function show($id)
    {
        return redirect()->route('admin.empleados.index');
    }
}