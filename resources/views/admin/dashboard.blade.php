@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Empleados</div>
        <div class="stat-value">{{ $stats['empleados'] }}</div>
        <div class="stat-sub">registrados en el sistema</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Películas</div>
        <div class="stat-value">{{ $stats['peliculas'] }}</div>
        <div class="stat-sub">en cartelera</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Funciones Activas</div>
        <div class="stat-value">{{ $stats['funciones'] }}</div>
        <div class="stat-sub">desde hoy en adelante</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Ingresos Hoy</div>
        <div class="stat-value" style="font-size:24px;">Bs {{ number_format($stats['ventas_hoy'], 2) }}</div>
        <div class="stat-sub">ventas del día</div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">⚡ Accesos rápidos</span>
        </div>
        <div style="display:flex; flex-direction:column; gap:10px;">
            <a href="{{ route('admin.funciones.create') }}" class="btn btn-primary">📅 Nueva Función</a>
            <a href="{{ route('admin.peliculas.create') }}" class="btn btn-secondary">🎥 Nueva Película</a>
            <a href="{{ route('admin.eventos.create') }}"  class="btn btn-secondary">🎪 Nuevo Evento</a>
            <a href="{{ route('admin.empleados.create') }}" class="btn btn-secondary">👤 Nuevo Empleado</a>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <span class="card-title">🔍 Últimas auditorías</span>
            <a href="{{ route('admin.auditoria.index') }}" class="btn btn-secondary btn-sm">Ver todo</a>
        </div>
        <p style="color:var(--muted); font-size:13px;">
            El sistema registra automáticamente todos los cambios realizados sobre las tablas principales.
            <br><br>
            Accede a <strong style="color:var(--text)">Auditoría</strong> en el menú lateral para ver el historial completo.
        </p>
    </div>
</div>
@endsection