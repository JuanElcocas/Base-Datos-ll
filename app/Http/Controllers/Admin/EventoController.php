<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = DB::table('evento_especial')->orderBy('id_evento')->paginate(15);
        return view('admin.eventos.index', compact('eventos'));
    }

    public function create()
    {
        return view('admin.eventos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'required|string|max:200',
        ]);
        DB::table('evento_especial')->insert($request->only(['nombre','descripcion']));
        return redirect()->route('admin.eventos.index')->with('success', 'Evento creado correctamente.');
    }

    public function edit($id)
    {
        $evento = DB::table('evento_especial')->where('id_evento', $id)->first();
        abort_if(!$evento, 404);
        return view('admin.eventos.edit', compact('evento'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'required|string|max:200',
        ]);
        DB::table('evento_especial')->where('id_evento', $id)
            ->update($request->only(['nombre','descripcion']));
        return redirect()->route('admin.eventos.index')->with('success', 'Evento actualizado.');
    }

    public function destroy($id)
    {
        $tieneFunciones = DB::table('funcion')->where('id_evento', $id)->exists();
        if ($tieneFunciones) {
            return back()->with('error', 'No se puede eliminar: el evento tiene funciones asociadas.');
        }
        DB::table('evento_especial')->where('id_evento', $id)->delete();
        return redirect()->route('admin.eventos.index')->with('success', 'Evento eliminado.');
    }

    public function show($id)
    {
        return redirect()->route('admin.eventos.index');
    }
}