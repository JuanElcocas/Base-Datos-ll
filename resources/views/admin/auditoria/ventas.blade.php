@extends('layouts.admin')
@section('title', 'Auditoría Ventas')
@section('page-title', 'Auditoría — Ventas')

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('admin.auditoria.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Acción</th><th>Venta ID</th><th>Fecha ant.</th><th>Fecha nueva</th><th>Total ant.</th><th>Total nuevo</th><th>Registro</th></tr>
            </thead>
            <tbody>
                @forelse($registros as $r)
                <tr>
                    <td><span class="chip chip-{{ strtolower($r->accion) }}">{{ $r->accion }}</span></td>
                    <td>{{ $r->venta_id }}</td>
                    <td style="color:var(--muted)">{{ $r->fecha_viejo ?? '—' }}</td>
                    <td>{{ $r->fecha_nuevo ?? '—' }}</td>
                    <td style="color:var(--muted)">{{ $r->total_viejo ? 'Bs '.$r->total_viejo : '—' }}</td>
                    <td style="color:var(--gold)">{{ $r->total_nuevo ? 'Bs '.$r->total_nuevo : '—' }}</td>
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