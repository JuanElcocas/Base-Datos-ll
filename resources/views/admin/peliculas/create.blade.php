@extends('layouts.admin')
@section('title', 'Nueva Película')
@section('page-title', 'Nueva Película')

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header">
        <span class="card-title">🎥 Crear Película</span>
        <a href="{{ route('admin.peliculas.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>
    <form action="{{ route('admin.peliculas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Título *</label>
            <input type="text" name="titulo" value="{{ old('titulo') }}" placeholder="Ej: Dune: Parte Dos" required>
            @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Año *</label>
                <input type="number" name="anio" value="{{ old('anio', date('Y')) }}" min="1900" max="2100" required>
                @error('anio')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Duración (min) *</label>
                <input type="number" name="duracion" value="{{ old('duracion') }}" min="1" placeholder="120" required>
                @error('duracion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-group">
            <label>Género *</label>
            <select name="genero" required>
                <option value="">-- Seleccionar --</option>
                @foreach(['Acción','Animación','Aventura','Ciencia Ficción','Comedia','Drama','Musical','Romance','Terror','Thriller'] as $g)
                    <option value="{{ $g }}" @selected(old('genero')===$g)>{{ $g }}</option>
                @endforeach
            </select>
            @error('genero')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Fecha Inicio *</label>
                <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
                @error('fecha_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Fecha Fin *</label>
                <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
                @error('fecha_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('admin.peliculas.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection