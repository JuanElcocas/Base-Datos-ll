<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuncionController extends Controller
{
    public function index()
    {
        $funciones = DB::table('funcion')
            ->leftJoin('pelicula', 'funcion.id_pelicula', '=', 'pelicula.id_pelicula')
            ->leftJoin('evento_especial', 'funcion.id_evento', '=', 'evento_especial.id_evento')
            ->select(
                'funcion.*',
                'pelicula.titulo as titulo_pelicula',
                'evento_especial.nombre as nombre_evento'
            )
            ->orderBy('funcion.fecha', 'desc')
            ->orderBy('funcion.hora', 'desc')
            ->paginate(15);

        return view('admin.funciones.index', compact('funciones'));
    }

    public function create()
    {
        $peliculas = DB::table('pelicula')->orderBy('titulo')->get();
        $eventos   = DB::table('evento_especial')->orderBy('nombre')->get();
        return view('admin.funciones.create', compact('peliculas', 'eventos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha'    => 'required|date',
            'hora'     => 'required',
            'tipo'     => 'required|in:PELICULA,EVENTO',
            'capacidad'=> 'required|integer|min:1',
            'precio'   => 'required|numeric|min:0.01',
        ]);

        $data = [
            'fecha'       => $request->fecha,
            'hora'        => $request->hora,
            'tipo'        => $request->tipo,
            'capacidad'   => $request->capacidad,
            'precio'      => $request->precio,
            'id_pelicula' => null,
            'id_evento'   => null,
        ];

        if ($request->tipo === 'PELICULA') {
            $request->validate(['id_pelicula' => 'required|exists:pelicula,id_pelicula']);
            $data['id_pelicula'] = $request->id_pelicula;
        } else {
            $request->validate(['id_evento' => 'required|exists:evento_especial,id_evento']);
            $data['id_evento'] = $request->id_evento;
        }

        DB::table('funcion')->insert($data);
        return redirect()->route('admin.funciones.index')->with('success', 'Función creada correctamente.');
    }

    public function edit($id)
    {
        $funcion   = DB::table('funcion')->where('id_funcion', $id)->first();
        $peliculas = DB::table('pelicula')->orderBy('titulo')->get();
        $eventos   = DB::table('evento_especial')->orderBy('nombre')->get();
        abort_if(!$funcion, 404);
        return view('admin.funciones.edit', compact('funcion', 'peliculas', 'eventos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha'    => 'required|date',
            'hora'     => 'required',
            'tipo'     => 'required|in:PELICULA,EVENTO',
            'capacidad'=> 'required|integer|min:1',
            'precio'   => 'required|numeric|min:0.01',
        ]);

        $data = [
            'fecha'       => $request->fecha,
            'hora'        => $request->hora,
            'tipo'        => $request->tipo,
            'capacidad'   => $request->capacidad,
            'precio'      => $request->precio,
            'id_pelicula' => null,
            'id_evento'   => null,
        ];

        if ($request->tipo === 'PELICULA') {
            $request->validate(['id_pelicula' => 'required|exists:pelicula,id_pelicula']);
            $data['id_pelicula'] = $request->id_pelicula;
        } else {
            $request->validate(['id_evento' => 'required|exists:evento_especial,id_evento']);
            $data['id_evento'] = $request->id_evento;
        }

        DB::table('funcion')->where('id_funcion', $id)->update($data);
        return redirect()->route('admin.funciones.index')->with('success', 'Función actualizada.');
    }

    public function destroy($id)
    {
        $tieneVentas = DB::table('detalle_venta')->where('id_funcion', $id)->exists();
        if ($tieneVentas) {
            return back()->with('error', 'No se puede eliminar: la función tiene ventas registradas.');
        }
        DB::table('funcion')->where('id_funcion', $id)->delete();
        return redirect()->route('admin.funciones.index')->with('success', 'Función eliminada.');
    }

    public function show($id)
    {
        return redirect()->route('admin.funciones.index');
    }

    public function historialPrecios($id)
    {
        $funcion = DB::table('funcion')
            ->leftJoin('pelicula', 'funcion.id_pelicula', '=', 'pelicula.id_pelicula')
            ->leftJoin('evento_especial', 'funcion.id_evento', '=', 'evento_especial.id_evento')
            ->select('funcion.*', 'pelicula.titulo', 'evento_especial.nombre as nombre_evento')
            ->where('funcion.id_funcion', $id)->first();

        $historial = DB::table('hist_precio_funcion')
            ->where('id_funcion', $id)
            ->orderBy('fecha_cambio', 'desc')
            ->get();

        return view('admin.funciones.historial', compact('funcion', 'historial'));
    }
}