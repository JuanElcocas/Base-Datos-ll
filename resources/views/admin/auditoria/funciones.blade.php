@extends('layouts.admin')
@section('title', 'Auditoría Funciones')
@section('page-title', 'Auditoría — Funciones')

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('admin.auditoria.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Acción</th><th>Función ID</th><th>Fecha ant.</th><th>Fecha nueva</th><th>Precio ant.</th><th>Precio nuevo</th><th>Tipo</th><th>Registro</th></tr>
            </thead>
            <tbody>
                @forelse($registros as $r)
                <tr>
                    <td><span class="chip chip-{{ strtolower($r->accion) }}">{{ $r->accion }}</span></td>
                    <td>{{ $r->funcion_id }}</td>
                    <td style="color:var(--muted)">{{ $r->fecha_viejo ?? '—' }}</td>
                    <td>{{ $r->fecha_nuevo ?? '—' }}</td>
                    <td style="color:var(--muted)">{{ $r->precio_viejo ? 'Bs '.$r->precio_viejo : '—' }}</td>
                    <td style="color:var(--gold)">{{ $r->precio_nuevo ? 'Bs '.$r->precio_nuevo : '—' }}</td>
                    <td>{{ $r->tipo_nuevo ?? $r->tipo_viejo ?? '—' }}</td>
                    <td style="font-size:12px; color:var(--muted)">{{ $r->fecha_cambio }}</td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center; color:var(--muted); padding:32px;">Sin registros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $registros->links('shared.pagination') }}
</div>
@endsection