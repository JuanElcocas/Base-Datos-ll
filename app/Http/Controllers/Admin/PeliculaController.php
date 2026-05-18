<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeliculaController extends Controller
{
    public function index()
    {
        $peliculas = DB::table('pelicula')->orderBy('id_pelicula')->paginate(15);
        return view('admin.peliculas.index', compact('peliculas'));
    }

    public function create()
    {
        return view('admin.peliculas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'       => 'required|string|max:100',
            'anio'         => 'required|integer|min:1900|max:2100',
            'duracion'     => 'required|integer|min:1',
            'genero'       => 'required|string|max:50',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ]);

        DB::table('pelicula')->insert($request->only(['titulo','anio','duracion','genero','fecha_inicio','fecha_fin']));
        return redirect()->route('admin.peliculas.index')->with('success', 'Película creada correctamente.');
    }

    public function edit($id)
    {
        $pelicula = DB::table('pelicula')->where('id_pelicula', $id)->first();
        abort_if(!$pelicula, 404);
        return view('admin.peliculas.edit', compact('pelicula'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo'       => 'required|string|max:100',
            'anio'         => 'required|integer|min:1900|max:2100',
            'duracion'     => 'required|integer|min:1',
            'genero'       => 'required|string|max:50',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ]);

        DB::table('pelicula')->where('id_pelicula', $id)
            ->update($request->only(['titulo','anio','duracion','genero','fecha_inicio','fecha_fin']));
        return redirect()->route('admin.peliculas.index')->with('success', 'Película actualizada.');
    }

    public function destroy($id)
    {
        $tieneFunciones = DB::table('funcion')->where('id_pelicula', $id)->exists();
        if ($tieneFunciones) {
            return back()->with('error', 'No se puede eliminar: la película tiene funciones asociadas.');
        }
        DB::table('pelicula')->where('id_pelicula', $id)->delete();
        return redirect()->route('admin.peliculas.index')->with('success', 'Película eliminada.');
    }

    public function show($id)
    {
        return redirect()->route('admin.peliculas.index');
    }
}