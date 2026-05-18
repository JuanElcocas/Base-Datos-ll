@extends('layouts.admin')
@section('title', 'Editar Evento')
@section('page-title', 'Editar Evento')

@section('content')
<div class="card" style="max-width:560px;">
    <div class="card-header">
        <span class="card-title">✏️ Editar Evento</span>
        <a href="{{ route('admin.eventos.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>
    <form action="{{ route('admin.eventos.update', $evento->id_evento) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="nombre" value="{{ old('nombre', $evento->nombre) }}" required>
            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Descripción *</label>
            <textarea name="descripcion" rows="3" style="resize:vertical" required>{{ old('descripcion', $evento->descripcion) }}</textarea>
            @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('admin.eventos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection