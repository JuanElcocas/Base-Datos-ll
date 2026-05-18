@extends('layouts.admin')
@section('title', 'Nuevo Evento')
@section('page-title', 'Nuevo Evento')

@section('content')
<div class="card" style="max-width:560px;">
    <div class="card-header">
        <span class="card-title">🎪 Crear Evento</span>
        <a href="{{ route('admin.eventos.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>
    <form action="{{ route('admin.eventos.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nombre del evento *</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Show de Payasitos Pompón" required>
            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Descripción *</label>
            <textarea name="descripcion" rows="3" style="resize:vertical" placeholder="Describe el evento..." required>{{ old('descripcion') }}</textarea>
            @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('admin.eventos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection