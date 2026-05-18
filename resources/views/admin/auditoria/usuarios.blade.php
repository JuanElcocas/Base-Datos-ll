@extends('layouts.admin')
@section('title', 'Auditoría Usuarios')
@section('page-title', 'Auditoría — Usuarios')

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('admin.auditoria.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Acción</th><th>Usuario ID</th><th>Usuario anterior</th><th>Usuario nuevo</th><th>Rol anterior</th><th>Rol nuevo</th><th>Fecha</th></tr>
            </thead>
            <tbody>
                @forelse($registros as $r)
                <tr>
                    <td><span class="chip chip-{{ strtolower($r->accion) }}">{{ $r->accion }}</span></td>
                    <td>{{ $r->usuario_id }}</td>
                    <td style="color:var(--muted)">{{ $r->usuario_viejo ?? '—' }}</td>
                    <td>{{ $r->usuario_nuevo ?? '—' }}</td>
                    <td style="color:var(--muted)">{{ $r->rol_viejo ?? '—' }}</td>
                    <td>{{ $r->rol_nuevo ?? '—' }}</td>
                    <td style="font-size:12px; color:var(--muted)">{{ $r->fecha_cambio }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; color:var(--muted); padding:32px;">Sin registros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $registros->links('shared.pagination') }}
</div>
@endsection