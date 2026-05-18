@extends('layouts.taquillero')
@section('title', 'Nueva Venta')

@section('content')
<div class="venta-wrap">
    <div style="margin-bottom:20px;">
        <a href="{{ route('taquillero.funciones') }}" class="btn btn-secondary" style="width:auto;">← Volver a funciones</a>
    </div>

    <div class="page-title">🎟 Nueva Venta</div>

    {{-- Resumen de la función --}}
    <div class="venta-resumen">
        <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:var(--muted); margin-bottom:8px;">Función seleccionada</div>
        <div style="font-size:20px; font-weight:700; margin-bottom:6px;">
            @if($funcion->tipo === 'PELICULA') 🎥 @else 🎪 @endif
            {{ $funcion->nombre_display }}
        </div>
        <div style="display:flex; gap:20px; flex-wrap:wrap; font-size:14px; color:var(--muted);">
            <span>📅 {{ $funcion->fecha }}</span>
            <span>🕐 {{ substr($funcion->hora,0,5) }}</span>
            <span>🪑 {{ $funcion->disponibles }} lugares disponibles</span>
        </div>
    </div>

    {{-- Formulario de venta --}}
    <div class="card">
        <form action="{{ route('taquillero.venta.confirmar') }}" method="POST" id="form-venta">
            @csrf
            <input type="hidden" name="id_funcion" value="{{ $funcion->id_funcion }}">

            <div class="form-group">
                <label>Cantidad de entradas *</label>
                <input type="number" id="cantidad" name="cantidad"
                       min="1" max="{{ $funcion->disponibles }}"
                       value="{{ old('cantidad', 1) }}"
                       placeholder="Ej: 2" required
                       oninput="calcTotal()">
                @error('cantidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div style="font-size:12px; color:var(--muted); margin-top:4px;">
                    Máximo: {{ $funcion->disponibles }} entradas disponibles
                </div>
            </div>

            <div class="form-group">
                <label>Precio por entrada (Bs) *</label>
                <input type="number" id="precio" name="precio_unitario"
                       min="0.01" step="0.01"
                       value="{{ old('precio_unitario', $funcion->precio) }}"
                       required oninput="calcTotal()">
                @error('precio_unitario')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div style="font-size:12px; color:var(--muted); margin-top:4px;">
                    Precio base: Bs {{ number_format($funcion->precio, 2) }} — puedes modificarlo
                </div>
            </div>

            {{-- Total dinámico --}}
            <div style="background:var(--surface2); border-radius:10px; padding:20px; margin:20px 0; text-align:center;">
                <div class="venta-label">TOTAL A COBRAR</div>
                <div class="venta-total" id="total-display">
                    Bs {{ number_format($funcion->precio, 2) }}
                </div>
            </div>

            <button type="submit" class="btn btn-comprar" style="font-size:17px; padding:16px;">
                ✅ Confirmar Venta
            </button>
        </form>
    </div>
</div>

<script>
function calcTotal() {
    const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
    const precio   = parseFloat(document.getElementById('precio').value) || 0;
    const total    = (cantidad * precio).toFixed(2);
    document.getElementById('total-display').textContent = 'Bs ' + total;
}
document.addEventListener('DOMContentLoaded', calcTotal);
</script>
@endsection