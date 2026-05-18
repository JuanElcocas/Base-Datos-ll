@extends('layouts.taquillero')
@section('title', 'Mi Panel')

@section('content')
<div class="page-title">
    Hola, {{ session('usuario')['nombre_empleado'] }} 👋
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:28px;">
    <div class="card" style="text-align:center; padding:32px;">
        <div style="font-size:13px; text-transform:uppercase; letter-spacing:1px; color:var(--muted); margin-bottom:8px;">Mis ventas de hoy</div>
        <div style="font-size:42px; font-weight:800; color:var(--gold)">Bs {{ number_format($ventasHoy, 2) }}</div>
    </div>
    <div class="card" style="display:flex; align-items:center; justify-content:center;">
        <a href="{{ route('taquillero.funciones') }}" class="btn btn-comprar" style="font-size:18px; padding:18px 32px; width:auto;">
            🎟 Vender Entradas
        </a>
    </div>
</div>

<div class="card">
    <div class="card-title">📋 Mis últimas ventas</div>
    @if($ultimasVentas->isEmpty())
        <p style="color:var(--muted); text-align:center; padding:32px;">No has realizado ventas aún.</p>
    @else
    <table>
        <thead>
            <tr><th>ID</th><th>Fecha</th><th>Hora</th><th>Total</th></tr>
        </thead>
        <tbody>
            @foreach($ultimasVentas as $v)
            <tr>
                <td style="color:var(--muted)">#{{ $v->id_venta }}</td>
                <td>{{ $v->fecha }}</td>
                <td>{{ substr($v->hora,0,5) }}</td>
                <td style="color:var(--gold); font-weight:600">Bs {{ number_format($v->total,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:14px;">
        <a href="{{ route('taquillero.historial') }}" class="btn btn-secondary" style="width:auto; font-size:13px;">Ver historial completo →</a>
    </div>
    @endif
</div>
@endsection