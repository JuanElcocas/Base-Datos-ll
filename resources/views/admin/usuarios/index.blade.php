@extends('layouts.admin')
@section('title', 'Usuarios')
@section('page-title', 'Usuarios')

@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-title">🔐 Lista de Usuarios</span>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">+ Nuevo Usuario</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Usuario</th><th>Rol</th><th>Empleado</th><th style="text-align:right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $u)
                <tr>
                    <td style="color:var(--muted)">{{ $u->id_usuario }}</td>
                    <td><strong>{{ $u->usuario }}</strong></td>
                    <td>
                        <span class="chip @if($u->rol==='SUPER') chip-pelicula @elseif($u->rol==='ADMIN') chip-evento @else chip-insert @endif">
                            {{ $u->rol }}
                        </span>
                    </td>
                    <td>{{ $u->nombre_empleado }}</td>
                    <td style="text-align:right; white-space:nowrap;">
                        <a href="{{ route('admin.usuarios.edit', $u->id_usuario) }}" class="btn btn-secondary btn-sm">✏️ Editar</a>
                        <form action="{{ route('admin.usuarios.destroy', $u->id_usuario) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar usuario {{ $u->usuario }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">🗑</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center; color:var(--muted); padding:32px;">No hay usuarios registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $usuarios->links('shared.pagination') }}
</div>
@endsection