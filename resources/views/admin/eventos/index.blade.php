@extends('layouts.admin')
@section('title', 'Eventos Especiales')
@section('page-title', 'Eventos Especiales')

@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-title">🎪 Eventos Especiales</span>
        <a href="{{ route('admin.eventos.create') }}" class="btn btn-primary">+ Nuevo Evento</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Nombre</th><th>Descripción</th><th style="text-align:right">Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($eventos as $ev)
                <tr>
                    <td style="color:var(--muted)">{{ $ev->id_evento }}</td>
                    <td><strong>{{ $ev->nombre }}</strong></td>
                    <td style="color:var(--muted); font-size:13px; max-width:320px;">{{ Str::limit($ev->descripcion, 80) }}</td>
                    <td style="text-align:right; white-space:nowrap;">
                        <a href="{{ route('admin.eventos.edit', $ev->id_evento) }}" class="btn btn-secondary btn-sm">✏️</a>
                        <form action="{{ route('admin.eventos.destroy', $ev->id_evento) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar evento?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">🗑</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center; color:var(--muted); padding:32px;">No hay eventos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $eventos->links('shared.pagination') }}
</div>
@endsection