<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('usuario')) {
            return $this->redirigirSegunRol(session('usuario')['rol']);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario'    => 'required|string',
            'contrasena' => 'required|string',
        ]);

        $user = DB::table('usuario')
            ->join('empleado', 'usuario.id_empleado', '=', 'empleado.id_empleado')
            ->where('usuario.usuario', $request->usuario)
            ->where('usuario.contrasena', $request->contrasena)
            ->select('usuario.*', 'empleado.nombre as nombre_empleado')
            ->first();

        if (!$user) {
            return back()->with('error', 'Usuario o contraseña incorrectos.')->withInput();
        }

        session([
            'usuario' => [
                'id'              => $user->id_usuario,
                'usuario'         => $user->usuario,
                'rol'             => $user->rol,
                'nombre_empleado' => $user->nombre_empleado,
                'id_empleado'     => $user->id_empleado,
            ]
        ]);

        return $this->redirigirSegunRol($user->rol);
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }

    private function redirigirSegunRol(string $rol)
    {
        return match($rol) {
            'SUPER', 'ADMIN' => redirect()->route('admin.dashboard'),
            'TAQUILLERO'     => redirect()->route('taquillero.dashboard'),
            default          => redirect()->route('login')->with('error', 'Rol no reconocido.'),
        };
    }
}