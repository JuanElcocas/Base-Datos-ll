@extends('layouts.admin')
@section('title', 'Editar Película')
@section('page-title', 'Editar Película')

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header">
        <span class="card-title">✏️ Editar: {{ $pelicula->titulo }}</span>
        <a href="{{ route('admin.peliculas.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>
    <form action="{{ route('admin.peliculas.update', $pelicula->id_pelicula) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Título *</label>
            <input type="text" name="titulo" value="{{ old('titulo', $pelicula->titulo) }}" required>
            @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Año *</label>
                <input type="number" name="anio" value="{{ old('anio', $pelicula->anio) }}" min="1900" max="2100" required>
            </div>
            <div class="form-group">
                <label>Duración (min) *</label>
                <input type="number" name="duracion" value="{{ old('duracion', $pelicula->duracion) }}" min="1" required>
            </div>
        </div>
        <div class="form-group">
            <label>Género *</label>
            <select name="genero" required>
                @foreach(['Acción','Animación','Aventura','Ciencia Ficción','Comedia','Drama','Musical','Romance','Terror','Thriller'] as $g)
                    <option value="{{ $g }}" @selected(old('genero', $pelicula->genero)===$g)>{{ $g }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Fecha Inicio *</label>
                <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $pelicula->fecha_inicio) }}" required>
            </div>
            <div class="form-group">
                <label>Fecha Fin *</label>
                <input type="date" name="fecha_fin" value="{{ old('fecha_fin', $pelicula->fecha_fin) }}" required>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('admin.peliculas.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection