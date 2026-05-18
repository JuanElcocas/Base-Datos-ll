@extends('layouts.admin')
@section('title', 'Funciones')
@section('page-title', 'Funciones')

@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-title">📅 Programación de Funciones</span>
        <a href="{{ route('admin.funciones.create') }}" class="btn btn-primary">+ Nueva Función</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Fecha</th><th>Hora</th><th>Tipo</th><th>Película / Evento</th><th>Capacidad</th><th>Precio</th><th style="text-align:right">Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($funciones as $f)
                <tr>
                    <td>{{ $f->fecha }}</td>
                    <td>{{ substr($f->hora,0,5) }}</td>
                    <td>
                        <span class="chip @if($f->tipo==='PELICULA') chip-pelicula @else chip-evento @endif">
                            {{ $f->tipo }}
                        </span>
                    </td>
                    <td>
                        @if($f->tipo === 'PELICULA')
                            🎥 {{ $f->titulo_pelicula }}
                        @else
                            🎪 {{ $f->nombre_evento }}
                        @endif
                    </td>
                    <td>{{ $f->capacidad }}</td>
                    <td style="color:var(--gold); font-weight:600">Bs {{ number_format($f->precio,2) }}</td>
                    <td style="text-align:right; white-space:nowrap;">
                        <a href="{{ route('admin.funciones.historial', $f->id_funcion) }}" class="btn btn-gold btn-sm">💰</a>
                        <a href="{{ route('admin.funciones.edit', $f->id_funcion) }}" class="btn btn-secondary btn-sm">✏️</a>
                        <form action="{{ route('admin.funciones.destroy', $f->id_funcion) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar función?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">🗑</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; color:var(--muted); padding:32px;">No hay funciones registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $funciones->links('shared.pagination') }}
</div>
@endsection