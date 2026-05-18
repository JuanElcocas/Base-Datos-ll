@extends('layouts.admin')
@section('title', 'Auditoría Películas')
@section('page-title', 'Auditoría — Películas')

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('admin.auditoria.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Acción</th><th>Película ID</th><th>Título anterior</th><th>Título nuevo</th><th>Género</th><th>Fecha</th></tr>
            </thead>
            <tbody>
                @forelse($registros as $r)
                <tr>
                    <td><span class="chip chip-{{ strtolower($r->accion) }}">{{ $r->accion }}</span></td>
                    <td>{{ $r->pelicula_id }}</td>
                    <td style="color:var(--muted)">{{ $r->titulo_viejo ?? '—' }}</td>
                    <td>{{ $r->titulo_nuevo ?? '—' }}</td>
                    <td style="color:var(--muted)">{{ $r->genero_nuevo ?? $r->genero_viejo ?? '—' }}</td>
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