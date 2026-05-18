@extends('layouts.admin')
@section('title', 'Nuevo Usuario')
@section('page-title', 'Nuevo Usuario')

@section('content')
<div class="card" style="max-width:560px;">
    <div class="card-header">
        <span class="card-title">🔐 Crear Usuario</span>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>
    <form action="{{ route('admin.usuarios.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Usuario *</label>
            <input type="text" name="usuario" value="{{ old('usuario') }}" placeholder="ej: jcmamani" required>
            @error('usuario')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Contraseña *</label>
            <input type="text" name="contrasena" value="{{ old('contrasena') }}" placeholder="Contraseña" required>
            @error('contrasena')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Rol *</label>
            <select name="rol" required>
                <option value="">-- Seleccionar --</option>
                @foreach(['SUPER','ADMIN','TAQUILLERO'] as $r)
                    <option value="{{ $r }}" @selected(old('rol')===$r)>{{ $r }}</option>
                @endforeach
            </select>
            @error('rol')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Empleado *</label>
            <select name="id_empleado" required>
                <option value="">-- Seleccionar empleado --</option>
                @foreach($empleados as $emp)
                    <option value="{{ $emp->id_empleado }}" @selected(old('id_empleado')==$emp->id_empleado)>
                        {{ $emp->nombre }}
                    </option>
                @endforeach
            </select>
            @error('id_empleado')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection