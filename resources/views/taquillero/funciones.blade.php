@extends('layouts.taquillero')
@section('title', 'Funciones Disponibles')

@section('content')
<div class="page-title">🎬 Funciones Disponibles</div>

@if($funciones->isEmpty())
    <div class="card" style="text-align:center; padding:60px;">
        <div style="font-size:48px; margin-bottom:16px;">🎭</div>
        <div style="color:var(--muted)">No hay funciones programadas próximamente.</div>
    </div>
@else

{{-- Agrupar por fecha --}}
@php $porFecha = $funciones->groupBy('fecha'); @endphp

@foreach($porFecha as $fecha => $items)
<div style="margin-bottom:32px;">
    <div class="section-label">
        📅 {{ \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd, D [de] MMMM') }}
    </div>
    <div class="funciones-grid">
        @foreach($items as $f)
        <div class="funcion-card {{ $f->estado }}">
            <div class="fc-tipo">{{ $f->tipo }}</div>
            <div class="fc-nombre">
                @if($f->tipo === 'PELICULA') 🎥 @else 🎪 @endif
                {{ $f->nombre_display }}
            </div>
            @if($f->tipo === 'PELICULA' && $f->genero)
                <div class="fc-info">🏷 {{ $f->genero }}</div>
            @endif
            <div class="fc-info">🕐 {{ substr($f->hora,0,5) }}</div>
            <div class="fc-precio">Bs {{ number_format($f->precio,2) }}</div>

            <div class="fc-estado {{ $f->estado }}">
                @if($f->estado === 'disponible') 🟢 Disponible
                @elseif($f->estado === 'pocos')  🟡 Pocos espacios
                @else                            🔴 Agotado @endif
            </div>
            <div class="fc-espacios">
                {{ $f->disponibles }} / {{ $f->capacidad }} lugares disponibles
            </div>

            @if($f->estado !== 'lleno')
                <a href="{{ route('taquillero.venta.nueva', $f->id_funcion) }}" class="btn btn-comprar">
                    🎟 Comprar entradas
                </a>
            @else
                <span class="btn btn-disabled">Sin disponibilidad</span>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endforeach

@endif
@endsection