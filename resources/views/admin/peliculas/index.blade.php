@extends('layouts.admin')
@section('title', 'Películas')
@section('page-title', 'Películas')

@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-title">🎥 Cartelera de Películas</span>
        <a href="{{ route('admin.peliculas.create') }}" class="btn btn-primary">+ Nueva Película</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Título</th><th>Año</th><th>Duración</th><th>Género</th><th>Inicio</th><th>Fin</th><th style="text-align:right">Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($peliculas as $p)
                <tr>
                    <td style="color:var(--muted)">{{ $p->id_pelicula }}</td>
                    <td><strong>{{ $p->titulo }}</strong></td>
                    <td>{{ $p->anio }}</td>
                    <td>{{ $p->duracion }} min</td>
                    <td><span class="chip chip-pelicula">{{ $p->genero }}</span></td>
                    <td>{{ $p->fecha_inicio }}</td>
                    <td>{{ $p->fecha_fin }}</td>
                    <td style="text-align:right; white-space:nowrap;">
                        <a href="{{ route('admin.peliculas.edit', $p->id_pelicula) }}" class="btn btn-secondary btn-sm">✏️</a>
                        <form action="{{ route('admin.peliculas.destroy', $p->id_pelicula) }}" method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar película?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">🗑</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center; color:var(--muted); padding:32px;">No hay películas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $peliculas->links('shared.pagination') }}
</div>
@endsection