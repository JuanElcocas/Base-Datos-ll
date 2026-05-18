<?php

namespace App\Http\Controllers\Taquillero;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /** Panel principal del taquillero */
    public function dashboard()
    {
        $idEmpleado = session('usuario')['id_empleado'];
        $ventasHoy  = DB::table('venta')
            ->whereDate('fecha', today())
            ->where('id_empleado', $idEmpleado)
            ->sum('total');

        $ultimasVentas = DB::table('venta')
            ->where('id_empleado', $idEmpleado)
            ->orderBy('fecha','desc')->orderBy('hora','desc')
            ->limit(5)->get();

        return view('taquillero.dashboard', compact('ventasHoy', 'ultimasVentas'));
    }

    /** Lista de funciones disponibles (futuras) */
    public function funciones()
    {
        $funciones = DB::table('funcion')
            ->leftJoin('pelicula', 'funcion.id_pelicula', '=', 'pelicula.id_pelicula')
            ->leftJoin('evento_especial', 'funcion.id_evento', '=', 'evento_especial.id_evento')
            ->whereDate('funcion.fecha', '>=', today())
            ->select(
                'funcion.*',
                'pelicula.titulo as titulo_pelicula',
                'pelicula.genero',
                'evento_especial.nombre as nombre_evento',
                DB::raw('COALESCE(SUM(detalle_venta.cantidad),0) as vendidos')
            )
            ->leftJoin('detalle_venta', 'funcion.id_funcion', '=', 'detalle_venta.id_funcion')
            ->groupBy(
                'funcion.id_funcion','funcion.fecha','funcion.hora','funcion.tipo',
                'funcion.id_pelicula','funcion.id_evento','funcion.capacidad','funcion.precio',
                'pelicula.titulo','pelicula.genero','evento_especial.nombre'
            )
            ->orderBy('funcion.fecha')->orderBy('funcion.hora')
            ->get()
            ->map(function($f) {
                $f->disponibles = $f->capacidad - $f->vendidos;
                $f->estado = match(true) {
                    $f->disponibles <= 0          => 'lleno',
                    $f->disponibles <= ($f->capacidad * 0.2) => 'pocos',
                    default                        => 'disponible',
                };
                $f->nombre_display = $f->tipo === 'PELICULA'
                    ? $f->titulo_pelicula
                    : $f->nombre_evento;
                return $f;
            });

        return view('taquillero.funciones', compact('funciones'));
    }

    /** Formulario de nueva venta */
    public function nuevaVenta($idFuncion)
    {
        $funcion = DB::table('funcion')
            ->leftJoin('pelicula', 'funcion.id_pelicula', '=', 'pelicula.id_pelicula')
            ->leftJoin('evento_especial', 'funcion.id_evento', '=', 'evento_especial.id_evento')
            ->where('funcion.id_funcion', $idFuncion)
            ->select(
                'funcion.*',
                'pelicula.titulo as titulo_pelicula',
                'pelicula.genero',
                'evento_especial.nombre as nombre_evento'
            )
            ->first();

        abort_if(!$funcion, 404);

        // Calcular disponibilidad
        $vendidos   = DB::table('detalle_venta')->where('id_funcion', $idFuncion)->sum('cantidad');
        $disponibles = $funcion->capacidad - $vendidos;

        if ($disponibles <= 0) {
            return redirect()->route('taquillero.funciones')->with('error', 'Esta función está llena.');
        }

        $funcion->disponibles   = $disponibles;
        $funcion->nombre_display = $funcion->tipo === 'PELICULA'
            ? $funcion->titulo_pelicula
            : $funcion->nombre_evento;

        return view('taquillero.nueva_venta', compact('funcion'));
    }

    /** Confirmar y guardar venta */
    public function confirmarVenta(Request $request)
    {
        $request->validate([
            'id_funcion'      => 'required|exists:funcion,id_funcion',
            'cantidad'        => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0.01',
        ]);

        $idFuncion      = $request->id_funcion;
        $cantidad       = $request->cantidad;
        $precioUnitario = $request->precio_unitario;
        $subtotal       = $cantidad * $precioUnitario;
        $idEmpleado     = session('usuario')['id_empleado'];

        // Verificar capacidad disponible
        $funcion  = DB::table('funcion')->where('id_funcion', $idFuncion)->first();
        $vendidos = DB::table('detalle_venta')->where('id_funcion', $idFuncion)->sum('cantidad');
        $disponibles = $funcion->capacidad - $vendidos;

        if ($cantidad > $disponibles) {
            return back()->with('error', "Solo quedan {$disponibles} lugar(es) disponibles.");
        }

        DB::transaction(function() use ($idEmpleado, $idFuncion, $cantidad, $precioUnitario, $subtotal) {
            // Crear venta (total=0, el trigger lo actualiza al insertar detalle)
            $idVenta = DB::table('venta')->insertGetId([
                'fecha'       => today(),
                'hora'        => now()->format('H:i:s'),
                'total'       => 0,
                'id_empleado' => $idEmpleado,
            ]);

            // Crear detalle (el trigger tr_actualizar_total_venta actualizará el total)
            DB::table('detalle_venta')->insert([
                'id_venta'        => $idVenta,
                'id_funcion'      => $idFuncion,
                'cantidad'        => $cantidad,
                'precio_unitario' => $precioUnitario,
                'subtotal'        => $subtotal,
            ]);
        });

        return redirect()->route('taquillero.funciones')
            ->with('success', "✅ Venta confirmada: {$cantidad} entrada(s) por Bs {$subtotal}");
    }

    /** Historial de ventas del taquillero */
    public function historial()
    {
        $idEmpleado = session('usuario')['id_empleado'];

        $ventas = DB::table('venta')
            ->join('detalle_venta', 'venta.id_venta', '=', 'detalle_venta.id_venta')
            ->join('funcion', 'detalle_venta.id_funcion', '=', 'funcion.id_funcion')
            ->leftJoin('pelicula', 'funcion.id_pelicula', '=', 'pelicula.id_pelicula')
            ->leftJoin('evento_especial', 'funcion.id_evento', '=', 'evento_especial.id_evento')
            ->where('venta.id_empleado', $idEmpleado)
            ->select(
                'venta.*',
                'detalle_venta.cantidad',
                'detalle_venta.precio_unitario',
                'detalle_venta.subtotal',
                'funcion.tipo',
                'funcion.fecha as fecha_funcion',
                'funcion.hora as hora_funcion',
                'pelicula.titulo as titulo_pelicula',
                'evento_especial.nombre as nombre_evento'
            )
            ->orderBy('venta.fecha','desc')
            ->orderBy('venta.hora','desc')
            ->paginate(20);

        return view('taquillero.historial', compact('ventas'));
    }
}