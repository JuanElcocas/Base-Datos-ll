@extends('layouts.admin')
@section('title', 'Auditoría Empleados')
@section('page-title', 'Auditoría — Empleados')

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('admin.auditoria.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>ID Log</th><th>Acción</th><th>Empleado ID</th><th>Nombre anterior</th><th>Nombre nuevo</th><th>Fecha</th></tr>
            </thead>
            <tbody>
                @forelse($registros as $r)
                <tr>
                    <td style="color:var(--muted)">{{ $r->log_id }}</td>
                    <td><span class="chip chip-{{ strtolower($r->accion) }}">{{ $r->accion }}</span></td>
                    <td>{{ $r->empleado_id }}</td>
                    <td style="color:var(--muted)">{{ $r->nombre_viejo ?? '—' }}</td>
                    <td>{{ $r->nombre_nuevo ?? '—' }}</td>
                    <td style="font-size:12px; color:var(--muted)">{{ $r->fecha_cambio }}</td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; color:var(--muted); padding:32px;">Sin registros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $registros->links('shared.pagination') }}
</div>
@endsection