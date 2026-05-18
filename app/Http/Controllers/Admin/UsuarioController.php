<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = DB::table('usuario')
            ->join('empleado', 'usuario.id_empleado', '=', 'empleado.id_empleado')
            ->select('usuario.*', 'empleado.nombre as nombre_empleado')
            ->orderBy('usuario.id_usuario')
            ->paginate(15);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $empleados = DB::table('empleado')->orderBy('nombre')->get();
        return view('admin.usuarios.create', compact('empleados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario'    => 'required|string|max:50|unique:usuario,usuario',
            'contrasena' => 'required|string|max:50',
            'rol'        => 'required|in:SUPER,ADMIN,TAQUILLERO',
            'id_empleado'=> 'required|exists:empleado,id_empleado',
        ]);

        DB::table('usuario')->insert([
            'usuario'    => $request->usuario,
            'contrasena' => $request->contrasena,
            'rol'        => $request->rol,
            'id_empleado'=> $request->id_empleado,
        ]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit($id)
    {
        $usuario   = DB::table('usuario')->where('id_usuario', $id)->first();
        $empleados = DB::table('empleado')->orderBy('nombre')->get();
        abort_if(!$usuario, 404);
        return view('admin.usuarios.edit', compact('usuario', 'empleados'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'usuario'    => 'required|string|max:50|unique:usuario,usuario,'.$id.',id_usuario',
            'contrasena' => 'required|string|max:50',
            'rol'        => 'required|in:SUPER,ADMIN,TAQUILLERO',
            'id_empleado'=> 'required|exists:empleado,id_empleado',
        ]);

        DB::table('usuario')->where('id_usuario', $id)->update([
            'usuario'    => $request->usuario,
            'contrasena' => $request->contrasena,
            'rol'        => $request->rol,
            'id_empleado'=> $request->id_empleado,
        ]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy($id)
    {
        DB::table('usuario')->where('id_usuario', $id)->delete();
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado.');
    }

    public function show($id)
    {
        return redirect()->route('admin.usuarios.index');
    }
}