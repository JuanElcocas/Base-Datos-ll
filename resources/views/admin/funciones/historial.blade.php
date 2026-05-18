@extends('layouts.admin')
@section('title', 'Historial de Precios')
@section('page-title', 'Historial de Precios')

@section('content')
<div class="card" style="max-width:800px;">
    <div class="card-header">
        <div>
            <span class="card-title">💰 Historial de Precios</span>
            <div style="font-size:13px; color:var(--muted); margin-top:4px;">
                Función #{{ $funcion->id_funcion }} —
                @if($funcion->tipo === 'PELICULA') 🎥 {{ $funcion->titulo }}
                @else 🎪 {{ $funcion->nombre_evento }} @endif
                · {{ $funcion->fecha }} {{ substr($funcion->hora,0,5) }}
            </div>
        </div>
        <a href="{{ route('admin.funciones.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>

    <div style="margin-bottom:16px; padding:16px; background:var(--surface2); border-radius:8px; display:flex; gap:24px;">
        <div>
            <div style="font-size:11px; color:var(--muted); text-transform:uppercase; letter-spacing:.5px">Precio actual</div>
            <div style="font-size:24px; font-weight:700; color:var(--gold)">Bs {{ number_format($funcion->precio,2) }}</div>
        </div>
        <div>
            <div style="font-size:11px; color:var(--muted); text-transform:uppercase; letter-spacing:.5px">Cambios registrados</div>
            <div style="font-size:24px; font-weight:700; color:var(--text)">{{ $historial->count() }}</div>
        </div>
    </div>

    @if($historial->isEmpty())
        <p style="color:var(--muted); text-align:center; padding:40px;">No hay cambios de precio registrados para esta función.</p>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Fecha del cambio</th><th>Precio anterior</th><th>Precio nuevo</th><th>Variación</th><th>Usuario DB</th></tr>
            </thead>
            <tbody>
                @foreach($historial as $h)
                <tr>
                    <td>{{ $h->fecha_cambio }}</td>
                    <td style="color:var(--muted)">Bs {{ number_format($h->precio_anterior,2) }}</td>
                    <td style="color:var(--gold); font-weight:600">Bs {{ number_format($h->precio_nuevo,2) }}</td>
                    <td>
                        @php $diff = $h->precio_nuevo - $h->precio_anterior; @endphp
                        <span style="color: {{ $diff > 0 ? '#66bb6a' : '#ef9a9a' }}">
                            {{ $diff > 0 ? '+' : '' }}Bs {{ number_format($diff,2) }}
                        </span>
                    </td>
                    <td style="font-size:12px; color:var(--muted)">{{ $h->usuario_db }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection