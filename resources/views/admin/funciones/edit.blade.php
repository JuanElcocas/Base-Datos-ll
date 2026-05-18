@extends('layouts.admin')
@section('title', 'Editar Función')
@section('page-title', 'Editar Función')

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header">
        <span class="card-title">✏️ Editar Función</span>
        <a href="{{ route('admin.funciones.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>
    <form action="{{ route('admin.funciones.update', $funcion->id_funcion) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Tipo *</label>
            <select name="tipo" id="select-tipo" required onchange="toggleTipo()">
                <option value="PELICULA" @selected(old('tipo',$funcion->tipo)==='PELICULA')>🎥 Película</option>
                <option value="EVENTO"   @selected(old('tipo',$funcion->tipo)==='EVENTO')>🎪 Evento</option>
            </select>
        </div>

        <div id="campo-pelicula">
            <div class="form-group">
                <label>Película</label>
                <select name="id_pelicula">
                    <option value="">-- Seleccionar --</option>
                    @foreach($peliculas as $p)
                        <option value="{{ $p->id_pelicula }}" @selected(old('id_pelicula',$funcion->id_pelicula)==$p->id_pelicula)>
                            {{ $p->titulo }} ({{ $p->anio }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="campo-evento" style="display:none">
            <div class="form-group">
                <label>Evento</label>
                <select name="id_evento">
                    <option value="">-- Seleccionar --</option>
                    @foreach($eventos as $ev)
                        <option value="{{ $ev->id_evento }}" @selected(old('id_evento',$funcion->id_evento)==$ev->id_evento)>
                            {{ $ev->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Fecha</label>
                <input type="date" name="fecha" value="{{ old('fecha', $funcion->fecha) }}" required>
            </div>
            <div class="form-group">
                <label>Hora</label>
                <input type="time" name="hora" value="{{ old('hora', substr($funcion->hora,0,5)) }}" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Capacidad</label>
                <input type="number" name="capacidad" value="{{ old('capacidad', $funcion->capacidad) }}" min="1" required>
            </div>
            <div class="form-group">
                <label>Precio (Bs)</label>
                <input type="number" name="precio" value="{{ old('precio', $funcion->precio) }}" min="0.01" step="0.01" required>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('admin.funciones.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function toggleTipo() {
    const tipo = document.getElementById('select-tipo').value;
    document.getElementById('campo-pelicula').style.display = tipo === 'PELICULA' ? 'block' : 'none';
    document.getElementById('campo-evento').style.display   = tipo === 'EVENTO'   ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', toggleTipo);
</script>
@endpush