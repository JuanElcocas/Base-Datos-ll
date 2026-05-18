@extends('layouts.admin')
@section('title', 'Reportes')
@section('page-title', 'Reportes y Estadísticas')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
.report-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
.chart-wrap { position: relative; height: 280px; }
@media(max-width:900px){ .report-grid{ grid-template-columns:1fr; } }
</style>
@endpush

@section('content')

{{-- KPIs --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);">
    <div class="stat-card">
        <div class="stat-label">Ingresos Totales</div>
        <div class="stat-value" style="font-size:22px;">Bs {{ number_format($totales['ingresos_total'],2) }}</div>
        <div class="stat-sub">histórico</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Ingresos este Mes</div>
        <div class="stat-value" style="font-size:22px;">Bs {{ number_format($totales['ingresos_mes'],2) }}</div>
        <div class="stat-sub">{{ now()->locale('es')->monthName }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Ventas Realizadas</div>
        <div class="stat-value">{{ $totales['ventas_total'] }}</div>
        <div class="stat-sub">transacciones</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Entradas Vendidas</div>
        <div class="stat-value">{{ $totales['entradas_total'] }}</div>
        <div class="stat-sub">tickets totales</div>
    </div>
</div>

{{-- Export Button --}}
<div style="margin-bottom:20px; display:flex; justify-content:flex-end;">
    <a href="{{ route('admin.reportes.exportar') }}" class="btn btn-gold">
        📥 Exportar Ventas CSV
    </a>
</div>

<div class="report-grid">
    {{-- Ventas por día --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📈 Ingresos últimos 30 días</span>
        </div>
        <div class="chart-wrap">
            <canvas id="chartDia"></canvas>
        </div>
    </div>

    {{-- Ventas por taquillero --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">👤 Ventas por Taquillero</span>
        </div>
        <div class="chart-wrap">
            <canvas id="chartTaq"></canvas>
        </div>
    </div>
</div>

{{-- Top películas/eventos --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">🎬 Top 10 Funciones por Ingresos</span>
    </div>
    <div class="chart-wrap" style="height:320px;">
        <canvas id="chartFuncion"></canvas>
    </div>
</div>

{{-- Tabla detalle --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">📋 Detalle por Función</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Función</th><th>Tipo</th><th>Entradas</th><th>Ingresos</th></tr>
            </thead>
            <tbody>
                @forelse($ventasPorFuncion as $item)
                <tr>
                    <td>{{ $item->nombre }}</td>
                    <td><span class="chip chip-{{ strtolower($item->tipo) }}">{{ $item->tipo }}</span></td>
                    <td>{{ $item->entradas }}</td>
                    <td style="color:var(--gold); font-weight:600">Bs {{ number_format($item->ingresos,2) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center; color:var(--muted); padding:24px;">Sin datos aún.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
const GOLD  = '#f5a623';
const RED   = '#e53935';
const BLUE  = '#64b5f6';
const GREEN = '#66bb6a';
const BG    = '#1a1a1a';
const MUTED = '#555';

Chart.defaults.color = '#888';
Chart.defaults.borderColor = '#2e2e2e';
Chart.defaults.font.family = 'Inter, sans-serif';

// ── Ventas por día ───────────────────────────────────────────
const diasData = @json($ventasPorDia);
new Chart(document.getElementById('chartDia'), {
    type: 'line',
    data: {
        labels: diasData.map(d => d.dia),
        datasets: [{
            label: 'Ingresos (Bs)',
            data: diasData.map(d => d.total),
            borderColor: RED,
            backgroundColor: 'rgba(229,57,53,.15)',
            fill: true,
            tension: .4,
            pointBackgroundColor: RED,
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: '#222' } },
            y: { grid: { color: '#222' }, beginAtZero: true }
        }
    }
});

// ── Ventas por taquillero ────────────────────────────────────
const taqData = @json($ventasPorTaquillero);
new Chart(document.getElementById('chartTaq'), {
    type: 'doughnut',
    data: {
        labels: taqData.map(t => t.nombre),
        datasets: [{
            data: taqData.map(t => t.total),
            backgroundColor: [RED, GOLD, BLUE, GREEN, '#ab47bc', '#26c6da'],
            borderColor: BG,
            borderWidth: 3,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: ctx => ` Bs ${ctx.parsed.toFixed(2)} (${ctx.label})`
                }
            }
        }
    }
});

// ── Top funciones ────────────────────────────────────────────
const funcData = @json($ventasPorFuncion);
new Chart(document.getElementById('chartFuncion'), {
    type: 'bar',
    data: {
        labels: funcData.map(f => f.nombre),
        datasets: [{
            label: 'Ingresos Bs',
            data: funcData.map(f => f.ingresos),
            backgroundColor: funcData.map(f => f.tipo === 'PELICULA' ? RED : GREEN),
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false, indexAxis: 'y',
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: '#222' }, beginAtZero: true },
            y: { grid: { color: '#222' } }
        }
    }
});
</script>
@endpush
