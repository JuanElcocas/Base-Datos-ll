@extends('layouts.admin')
@section('title', 'Editar Empleado')
@section('page-title', 'Editar Empleado')

@section('content')
<div class="card" style="max-width:520px;">
    <div class="card-header">
        <span class="card-title">✏️ Editar Empleado #{{ $empleado->id_empleado }}</span>
        <a href="{{ route('admin.empleados.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>
    <form action="{{ route('admin.empleados.update', $empleado->id_empleado) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label for="nombre">Nombre completo *</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $empleado->nombre) }}" required>
            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('admin.empleados.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection