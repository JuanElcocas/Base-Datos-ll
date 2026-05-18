<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        // Ventas por día (últimos 30 días)
        $ventasPorDia = DB::table('venta')
            ->selectRaw('DATE(fecha) as dia, SUM(total) as total, COUNT(*) as cantidad')
            ->where('fecha', '>=', now()->subDays(30))
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        // Ventas por película/evento
        $ventasPorFuncion = DB::table('detalle_venta')
            ->join('funcion', 'detalle_venta.id_funcion', '=', 'funcion.id_funcion')
            ->leftJoin('pelicula', 'funcion.id_pelicula', '=', 'pelicula.id_pelicula')
            ->leftJoin('evento_especial', 'funcion.id_evento', '=', 'evento_especial.id_evento')
            ->selectRaw('
                COALESCE(pelicula.titulo, evento_especial.nombre) as nombre,
                funcion.tipo,
                SUM(detalle_venta.cantidad) as entradas,
                SUM(detalle_venta.subtotal) as ingresos
            ')
            ->groupBy('nombre', 'funcion.tipo')
            ->orderByDesc('ingresos')
            ->limit(10)
            ->get();

        // Ventas por taquillero
        $ventasPorTaquillero = DB::table('venta')
            ->join('empleado', 'venta.id_empleado', '=', 'empleado.id_empleado')
            ->selectRaw('empleado.nombre, SUM(venta.total) as total, COUNT(*) as cantidad')
            ->groupBy('empleado.id_empleado', 'empleado.nombre')
            ->orderByDesc('total')
            ->get();

        // Totales generales
        $totales = [
            'ingresos_total' => DB::table('venta')->sum('total'),
            'ventas_total'   => DB::table('venta')->count(),
            'entradas_total' => DB::table('detalle_venta')->sum('cantidad'),
            'ingresos_mes'   => DB::table('venta')->whereMonth('fecha', now()->month)->sum('total'),
        ];

        return view('admin.reportes.index', compact(
            'ventasPorDia', 'ventasPorFuncion', 'ventasPorTaquillero', 'totales'
        ));
    }

    public function exportarVentas()
    {
        $ventas = DB::table('venta')
            ->join('detalle_venta', 'venta.id_venta', '=', 'detalle_venta.id_venta')
            ->join('funcion', 'detalle_venta.id_funcion', '=', 'funcion.id_funcion')
            ->leftJoin('pelicula', 'funcion.id_pelicula', '=', 'pelicula.id_pelicula')
            ->leftJoin('evento_especial', 'funcion.id_evento', '=', 'evento_especial.id_evento')
            ->join('empleado', 'venta.id_empleado', '=', 'empleado.id_empleado')
            ->select(
                'venta.id_venta', 'venta.fecha', 'venta.hora', 'venta.total',
                'empleado.nombre as taquillero',
                'detalle_venta.cantidad', 'detalle_venta.precio_unitario', 'detalle_venta.subtotal',
                'funcion.tipo', 'funcion.fecha as fecha_funcion', 'funcion.hora as hora_funcion',
                DB::raw('COALESCE(pelicula.titulo, evento_especial.nombre) as funcion_nombre')
            )
            ->orderByDesc('venta.fecha')
            ->get();

        $filename = 'ventas_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($ventas) {
            $fh = fopen('php://output', 'w');
            // BOM para Excel
            fputs($fh, "\xEF\xBB\xBF");
            fputcsv($fh, [
                'ID Venta', 'Fecha Venta', 'Hora Venta', 'Total Bs',
                'Taquillero', 'Entradas', 'Precio Unitario', 'Subtotal',
                'Tipo', 'Fecha Función', 'Hora Función', 'Función',
            ]);
            foreach ($ventas as $v) {
                fputcsv($fh, [
                    $v->id_venta, $v->fecha, substr($v->hora, 0, 5), $v->total,
                    $v->taquillero, $v->cantidad, $v->precio_unitario, $v->subtotal,
                    $v->tipo, $v->fecha_funcion, substr($v->hora_funcion, 0, 5), $v->funcion_nombre,
                ]);
            }
            fclose($fh);
        };

        return response()->stream($callback, 200, $headers);
    }
}
