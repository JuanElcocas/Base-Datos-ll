@extends('layouts.admin')
@section('title', 'Empleados')
@section('page-title', 'Empleados')

@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-title">👤 Lista de Empleados</span>
        <a href="{{ route('admin.empleados.create') }}" class="btn btn-primary">+ Nuevo Empleado</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th style="text-align:right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($empleados as $e)
                <tr>
                    <td style="color:var(--muted)">{{ $e->id_empleado }}</td>
                    <td>{{ $e->nombre }}</td>
                    <td style="text-align:right; white-space:nowrap;">
                        <a href="{{ route('admin.empleados.edit', $e->id_empleado) }}" class="btn btn-secondary btn-sm">✏️ Editar</a>
                        <form action="{{ route('admin.empleados.destroy', $e->id_empleado) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar este empleado?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">🗑 Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center; color:var(--muted); padding:32px;">No hay empleados registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">
        {{ $empleados->links('shared.pagination') }}
    </div>
</div>
@endsection