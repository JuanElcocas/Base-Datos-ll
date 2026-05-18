@extends('layouts.admin')
@section('title', 'Auditoría')
@section('page-title', 'Centro de Auditoría')

@section('content')
<div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:24px;">
    <a href="{{ route('admin.auditoria.empleados') }}" class="btn btn-secondary">👤 Empleados</a>
    <a href="{{ route('admin.auditoria.usuarios') }}"  class="btn btn-secondary">🔐 Usuarios</a>
    <a href="{{ route('admin.auditoria.peliculas') }}" class="btn btn-secondary">🎥 Películas</a>
    <a href="{{ route('admin.auditoria.funciones') }}" class="btn btn-secondary">📅 Funciones</a>
    <a href="{{ route('admin.auditoria.ventas') }}"    class="btn btn-secondary">🎟 Ventas</a>
</div>

@foreach($recientes as $tabla => $registros)
<div class="card" style="margin-bottom:16px;">
    <div class="card-header">
        <span class="card-title">{{ $tabla }}</span>
        <span style="font-size:12px; color:var(--muted)">últimos 5 registros</span>
    </div>
    @if($registros->isEmpty())
        <p style="color:var(--muted); font-size:13px;">Sin registros.</p>
    @else
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Acción</th><th>ID afectado</th><th>Fecha</th></tr>
            </thead>
            <tbody>
                @foreach($registros as $r)
                <tr>
                    <td>
                        <span class="chip chip-{{ strtolower($r->accion) }}">{{ $r->accion }}</span>
                    </td>
                    <td style="color:var(--muted)">
                        {{ $r->empleado_id ?? $r->usuario_id ?? $r->pelicula_id ?? $r->funcion_id ?? $r->venta_id ?? '—' }}
                    </td>
                    <td style="font-size:13px; color:var(--muted)">{{ $r->fecha_cambio }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endforeach
@endsection