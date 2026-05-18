@extends('layouts.admin')
@section('title', 'Nuevo Empleado')
@section('page-title', 'Nuevo Empleado')

@section('content')
<div class="card" style="max-width:520px;">
    <div class="card-header">
        <span class="card-title">👤 Crear Empleado</span>
        <a href="{{ route('admin.empleados.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>
    <form action="{{ route('admin.empleados.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre completo *</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Juan Carlos Mamani" required>
            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('admin.empleados.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection