<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CineSeeder extends Seeder
{
    public function run(): void
    {
        // Empleados
        $emp1 = DB::table('empleado')->insertGetId(['nombre' => 'Carlos Mamani']);
        $emp2 = DB::table('empleado')->insertGetId(['nombre' => 'Ana Torres']);
        $emp3 = DB::table('empleado')->insertGetId(['nombre' => 'Pedro Quispe']);

        // Usuarios
        DB::table('usuario')->insert([
            ['usuario' => 'admin', 'contrasena' => '1234', 'rol' => 'SUPER', 'id_empleado' => $emp1],
            ['usuario' => 'jefe',  'contrasena' => '1234', 'rol' => 'ADMIN', 'id_empleado' => $emp2],
            ['usuario' => 'taq1',  'contrasena' => '1234', 'rol' => 'TAQUILLERO', 'id_empleado' => $emp3],
        ]);

        // Películas
        $p1 = DB::table('pelicula')->insertGetId([
            'titulo' => 'Avengers: Secret Wars', 'anio' => 2026, 'duracion' => 150,
            'genero' => 'Acción', 'fecha_inicio' => today(), 'fecha_fin' => today()->addDays(30),
        ]);
        $p2 = DB::table('pelicula')->insertGetId([
            'titulo' => 'Dune: Messiah', 'anio' => 2026, 'duracion' => 165,
            'genero' => 'Ciencia Ficción', 'fecha_inicio' => today(), 'fecha_fin' => today()->addDays(20),
        ]);

        // Evento
        $e1 = DB::table('evento_especial')->insertGetId([
            'nombre' => 'Concierto Clásico Orquesta Nacional', 'descripcion' => 'Noche de música clásica'
        ]);

        // Funciones
        DB::table('funcion')->insert([
            ['fecha' => today()->format('Y-m-d'), 'hora' => '18:00:00', 'tipo' => 'PELICULA', 'capacidad' => 100, 'precio' => 25.00, 'id_pelicula' => $p1, 'id_evento' => null],
            ['fecha' => today()->format('Y-m-d'), 'hora' => '21:00:00', 'tipo' => 'PELICULA', 'capacidad' => 80,  'precio' => 25.00, 'id_pelicula' => $p2, 'id_evento' => null],
            ['fecha' => today()->addDay()->format('Y-m-d'), 'hora' => '19:30:00', 'tipo' => 'EVENTO', 'capacidad' => 200, 'precio' => 50.00, 'id_pelicula' => null, 'id_evento' => $e1],
            ['fecha' => today()->addDays(2)->format('Y-m-d'), 'hora' => '17:00:00', 'tipo' => 'PELICULA', 'capacidad' => 100, 'precio' => 25.00, 'id_pelicula' => $p1, 'id_evento' => null],
        ]);
    }
}
