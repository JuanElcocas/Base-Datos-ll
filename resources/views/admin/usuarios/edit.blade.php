@extends('layouts.admin')
@section('title', 'Editar Usuario')
@section('page-title', 'Editar Usuario')

@section('content')
<div class="card" style="max-width:560px;">
    <div class="card-header">
        <span class="card-title">✏️ Editar Usuario</span>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary btn-sm">← Volver</a>
    </div>
    <form action="{{ route('admin.usuarios.update', $usuario->id_usuario) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Usuario *</label>
            <input type="text" name="usuario" value="{{ old('usuario', $usuario->usuario) }}" required>
            @error('usuario')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Contraseña *</label>
            <input type="text" name="contrasena" value="{{ old('contrasena', $usuario->contrasena) }}" required>
            @error('contrasena')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Rol *</label>
            <select name="rol" required>
                @foreach(['SUPER','ADMIN','TAQUILLERO'] as $r)
                    <option value="{{ $r }}" @selected(old('rol', $usuario->rol)===$r)>{{ $r }}</option>
                @endforeach
            </select>
            @error('rol')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Empleado *</label>
            <select name="id_empleado" required>
                @foreach($empleados as $emp)
                    <option value="{{ $emp->id_empleado }}" @selected(old('id_empleado', $usuario->id_empleado)==$emp->id_empleado)>
                        {{ $emp->nombre }}
                    </option>
                @endforeach
            </select>
            @error('id_empleado')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection