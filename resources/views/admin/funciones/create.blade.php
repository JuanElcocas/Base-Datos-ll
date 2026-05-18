@extends('layouts.admin')
@section('title', 'Nueva Función')
@section('page-title', 'Nueva Función')

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header">
        <span class="card-title">📅 Crear Función</span>
        <a href="{{ route('admin.funciones.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>
    <form action="{{ route('admin.funciones.store') }}" method="POST" id="form-funcion">
        @csrf
        <div class="form-group">
            <label>Tipo de función *</label>
            <select name="tipo" id="select-tipo" required onchange="toggleTipo()">
                <option value="">-- Seleccionar --</option>
                <option value="PELICULA" @selected(old('tipo')==='PELICULA')>🎥 Película</option>
                <option value="EVENTO"   @selected(old('tipo')==='EVENTO')>🎪 Evento Especial</option>
            </select>
            @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div id="campo-pelicula" style="display:none">
            <div class="form-group">
                <label>Película *</label>
                <select name="id_pelicula">
                    <option value="">-- Seleccionar película --</option>
                    @foreach($peliculas as $p)
                        <option value="{{ $p->id_pelicula }}" @selected(old('id_pelicula')==$p->id_pelicula)>
                            {{ $p->titulo }} ({{ $p->anio }})
                        </option>
                    @endforeach
                </select>
                @error('id_pelicula')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div id="campo-evento" style="display:none">
            <div class="form-group">
                <label>Evento Especial *</label>
                <select name="id_evento">
                    <option value="">-- Seleccionar evento --</option>
                    @foreach($eventos as $ev)
                        <option value="{{ $ev->id_evento }}" @selected(old('id_evento')==$ev->id_evento)>
                            {{ $ev->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('id_evento')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Fecha *</label>
                <input type="date" name="fecha" value="{{ old('fecha') }}" required>
                @error('fecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Hora *</label>
                <input type="time" name="hora" value="{{ old('hora') }}" required>
                @error('hora')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Capacidad *</label>
                <input type="number" name="capacidad" value="{{ old('capacidad', 272) }}" min="1" required>
                @error('capacidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Precio (Bs) *</label>
                <input type="number" name="precio" value="{{ old('precio', '20.00') }}" min="0.01" step="0.01" required>
                @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar Función</button>
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
// Restaurar estado al cargar si hay old values
document.addEventListener('DOMContentLoaded', toggleTipo);
</script>
@endpush