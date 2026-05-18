@extends('layouts.taquillero')
@section('title', 'Mis Ventas')

@section('content')
<div class="page-title">📋 Historial de Mis Ventas</div>

<div class="card">
    @if($ventas->isEmpty())
        <div style="text-align:center; padding:60px; color:var(--muted);">
            <div style="font-size:48px; margin-bottom:16px;">📭</div>
            <div>No has realizado ventas aún.</div>
            <div style="margin-top:16px;">
                <a href="{{ route('taquillero.funciones') }}" class="btn btn-comprar" style="width:auto; padding:12px 24px;">
                    🎟 Ir a vender
                </a>
            </div>
        </div>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Venta #</th>
                    <th>Fecha venta</th>
                    <th>Función</th>
                    <th>Fecha función</th>
                    <th>Entradas</th>
                    <th>Precio unit.</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $v)
                <tr>
                    <td style="color:var(--muted)">#{{ $v->id_venta }}</td>
                    <td>{{ $v->fecha }} {{ substr($v->hora,0,5) }}</td>
                    <td>
                        @if($v->tipo === 'PELICULA') 🎥 {{ $v->titulo_pelicula }}
                        @else 🎪 {{ $v->nombre_evento }} @endif
                    </td>
                    <td style="color:var(--muted)">{{ $v->fecha_funcion }} {{ substr($v->hora_funcion,0,5) }}</td>
                    <td style="text-align:center">{{ $v->cantidad }}</td>
                    <td style="color:var(--muted)">Bs {{ number_format($v->precio_unitario,2) }}</td>
                    <td style="color:var(--gold); font-weight:700">Bs {{ number_format($v->total,2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $ventas->links('shared.pagination') }}
    @endif
</div>
@endsection