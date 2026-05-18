<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── empleado ──────────────────────────────────────
        Schema::create('empleado', function (Blueprint $table) {
            $table->increments('id_empleado');
            $table->string('nombre', 100);
        });

        // ── usuario ───────────────────────────────────────
        Schema::create('usuario', function (Blueprint $table) {
            $table->increments('id_usuario');
            $table->string('usuario', 50)->unique();
            $table->string('contrasena', 255);
            $table->enum('rol', ['SUPER', 'ADMIN', 'TAQUILLERO']);
            $table->unsignedInteger('id_empleado');
            $table->foreign('id_empleado')->references('id_empleado')->on('empleado');
        });

        // ── pelicula ──────────────────────────────────────
        Schema::create('pelicula', function (Blueprint $table) {
            $table->increments('id_pelicula');
            $table->string('titulo', 100);
            $table->unsignedSmallInteger('anio');
            $table->unsignedSmallInteger('duracion'); // minutos
            $table->string('genero', 50);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
        });

        // ── evento_especial ───────────────────────────────
        Schema::create('evento_especial', function (Blueprint $table) {
            $table->increments('id_evento');
            $table->string('nombre', 100);
            $table->string('descripcion', 200)->nullable();
        });

        // ── funcion ───────────────────────────────────────
        Schema::create('funcion', function (Blueprint $table) {
            $table->increments('id_funcion');
            $table->date('fecha');
            $table->time('hora');
            $table->enum('tipo', ['PELICULA', 'EVENTO']);
            $table->unsignedSmallInteger('capacidad');
            $table->decimal('precio', 8, 2);
            $table->unsignedInteger('id_pelicula')->nullable();
            $table->unsignedInteger('id_evento')->nullable();
            $table->foreign('id_pelicula')->references('id_pelicula')->on('pelicula');
            $table->foreign('id_evento')->references('id_evento')->on('evento_especial');
        });

        // ── venta ─────────────────────────────────────────
        Schema::create('venta', function (Blueprint $table) {
            $table->increments('id_venta');
            $table->date('fecha');
            $table->time('hora');
            $table->decimal('total', 10, 2)->default(0);
            $table->unsignedInteger('id_empleado');
            $table->foreign('id_empleado')->references('id_empleado')->on('empleado');
        });

        // ── detalle_venta ─────────────────────────────────
        Schema::create('detalle_venta', function (Blueprint $table) {
            $table->increments('id_detalle');
            $table->unsignedInteger('id_venta');
            $table->unsignedInteger('id_funcion');
            $table->unsignedSmallInteger('cantidad');
            $table->decimal('precio_unitario', 8, 2);
            $table->decimal('subtotal', 10, 2);
            $table->foreign('id_venta')->references('id_venta')->on('venta')->onDelete('cascade');
            $table->foreign('id_funcion')->references('id_funcion')->on('funcion');
        });

        // ── auditoria_empleado ────────────────────────────
        Schema::create('auditoria_empleado', function (Blueprint $table) {
            $table->increments('log_id');
            $table->enum('accion', ['INSERT', 'UPDATE', 'DELETE']);
            $table->unsignedInteger('empleado_id');
            $table->string('nombre_viejo', 100)->nullable();
            $table->string('nombre_nuevo', 100)->nullable();
            $table->timestamp('fecha_cambio')->useCurrent();
        });

        // ── auditoria_usuario ─────────────────────────────
        Schema::create('auditoria_usuario', function (Blueprint $table) {
            $table->increments('log_id');
            $table->enum('accion', ['INSERT', 'UPDATE', 'DELETE']);
            $table->unsignedInteger('usuario_id');
            $table->string('usuario_viejo', 50)->nullable();
            $table->string('usuario_nuevo', 50)->nullable();
            $table->string('rol_viejo', 20)->nullable();
            $table->string('rol_nuevo', 20)->nullable();
            $table->timestamp('fecha_cambio')->useCurrent();
        });

        // ── auditoria_pelicula ────────────────────────────
        Schema::create('auditoria_pelicula', function (Blueprint $table) {
            $table->increments('log_id');
            $table->enum('accion', ['INSERT', 'UPDATE', 'DELETE']);
            $table->unsignedInteger('pelicula_id');
            $table->string('titulo_viejo', 100)->nullable();
            $table->string('titulo_nuevo', 100)->nullable();
            $table->timestamp('fecha_cambio')->useCurrent();
        });

        // ── auditoria_funcion ─────────────────────────────
        Schema::create('auditoria_funcion', function (Blueprint $table) {
            $table->increments('log_id');
            $table->enum('accion', ['INSERT', 'UPDATE', 'DELETE']);
            $table->unsignedInteger('funcion_id');
            $table->decimal('precio_viejo', 8, 2)->nullable();
            $table->decimal('precio_nuevo', 8, 2)->nullable();
            $table->timestamp('fecha_cambio')->useCurrent();
        });

        // ── auditoria_venta ───────────────────────────────
        Schema::create('auditoria_venta', function (Blueprint $table) {
            $table->increments('log_id');
            $table->enum('accion', ['INSERT', 'DELETE']);
            $table->unsignedInteger('venta_id');
            $table->decimal('total', 10, 2)->nullable();
            $table->timestamp('fecha_cambio')->useCurrent();
        });

        // ── hist_precio_funcion ───────────────────────────
        Schema::create('hist_precio_funcion', function (Blueprint $table) {
            $table->increments('id_hist');
            $table->unsignedInteger('id_funcion');
            $table->decimal('precio_anterior', 8, 2);
            $table->decimal('precio_nuevo', 8, 2);
            $table->timestamp('fecha_cambio')->useCurrent();
            $table->string('usuario_db', 100)->nullable();
            $table->foreign('id_funcion')->references('id_funcion')->on('funcion');
        });

        // ── TRIGGERS (MySQL only) ──────────────────────────
        $this->createTriggers();
    }

    private function createTriggers(): void
    {
        // Trigger: actualizar total de venta al insertar detalle
        DB::unprepared('
            CREATE TRIGGER tr_actualizar_total_venta
            AFTER INSERT ON detalle_venta
            FOR EACH ROW
            BEGIN
                UPDATE venta SET total = total + NEW.subtotal WHERE id_venta = NEW.id_venta;
            END
        ');

        // Trigger: historial de precios de funciones
        DB::unprepared('
            CREATE TRIGGER tr_hist_precio_funcion
            AFTER UPDATE ON funcion
            FOR EACH ROW
            BEGIN
                IF OLD.precio <> NEW.precio THEN
                    INSERT INTO hist_precio_funcion (id_funcion, precio_anterior, precio_nuevo, usuario_db)
                    VALUES (NEW.id_funcion, OLD.precio, NEW.precio, USER());
                END IF;
            END
        ');

        // Triggers de auditoría: empleado
        DB::unprepared('
            CREATE TRIGGER tr_audit_empleado_insert
            AFTER INSERT ON empleado FOR EACH ROW
            BEGIN
                INSERT INTO auditoria_empleado (accion, empleado_id, nombre_nuevo)
                VALUES ("INSERT", NEW.id_empleado, NEW.nombre);
            END
        ');
        DB::unprepared('
            CREATE TRIGGER tr_audit_empleado_update
            AFTER UPDATE ON empleado FOR EACH ROW
            BEGIN
                INSERT INTO auditoria_empleado (accion, empleado_id, nombre_viejo, nombre_nuevo)
                VALUES ("UPDATE", NEW.id_empleado, OLD.nombre, NEW.nombre);
            END
        ');
        DB::unprepared('
            CREATE TRIGGER tr_audit_empleado_delete
            BEFORE DELETE ON empleado FOR EACH ROW
            BEGIN
                INSERT INTO auditoria_empleado (accion, empleado_id, nombre_viejo)
                VALUES ("DELETE", OLD.id_empleado, OLD.nombre);
            END
        ');

        // Triggers de auditoría: usuario
        DB::unprepared('
            CREATE TRIGGER tr_audit_usuario_insert
            AFTER INSERT ON usuario FOR EACH ROW
            BEGIN
                INSERT INTO auditoria_usuario (accion, usuario_id, usuario_nuevo, rol_nuevo)
                VALUES ("INSERT", NEW.id_usuario, NEW.usuario, NEW.rol);
            END
        ');
        DB::unprepared('
            CREATE TRIGGER tr_audit_usuario_update
            AFTER UPDATE ON usuario FOR EACH ROW
            BEGIN
                INSERT INTO auditoria_usuario (accion, usuario_id, usuario_viejo, usuario_nuevo, rol_viejo, rol_nuevo)
                VALUES ("UPDATE", NEW.id_usuario, OLD.usuario, NEW.usuario, OLD.rol, NEW.rol);
            END
        ');
        DB::unprepared('
            CREATE TRIGGER tr_audit_usuario_delete
            BEFORE DELETE ON usuario FOR EACH ROW
            BEGIN
                INSERT INTO auditoria_usuario (accion, usuario_id, usuario_viejo, rol_viejo)
                VALUES ("DELETE", OLD.id_usuario, OLD.usuario, OLD.rol);
            END
        ');

        // Triggers de auditoría: pelicula
        DB::unprepared('
            CREATE TRIGGER tr_audit_pelicula_insert
            AFTER INSERT ON pelicula FOR EACH ROW
            BEGIN
                INSERT INTO auditoria_pelicula (accion, pelicula_id, titulo_nuevo)
                VALUES ("INSERT", NEW.id_pelicula, NEW.titulo);
            END
        ');
        DB::unprepared('
            CREATE TRIGGER tr_audit_pelicula_update
            AFTER UPDATE ON pelicula FOR EACH ROW
            BEGIN
                INSERT INTO auditoria_pelicula (accion, pelicula_id, titulo_viejo, titulo_nuevo)
                VALUES ("UPDATE", NEW.id_pelicula, OLD.titulo, NEW.titulo);
            END
        ');
        DB::unprepared('
            CREATE TRIGGER tr_audit_pelicula_delete
            BEFORE DELETE ON pelicula FOR EACH ROW
            BEGIN
                INSERT INTO auditoria_pelicula (accion, pelicula_id, titulo_viejo)
                VALUES ("DELETE", OLD.id_pelicula, OLD.titulo);
            END
        ');

        // Triggers de auditoría: venta
        DB::unprepared('
            CREATE TRIGGER tr_audit_venta_insert
            AFTER INSERT ON venta FOR EACH ROW
            BEGIN
                INSERT INTO auditoria_venta (accion, venta_id, total)
                VALUES ("INSERT", NEW.id_venta, NEW.total);
            END
        ');
    }

    public function down(): void
    {
        // Drop triggers first
        DB::unprepared('DROP TRIGGER IF EXISTS tr_actualizar_total_venta');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_hist_precio_funcion');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_audit_empleado_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_audit_empleado_update');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_audit_empleado_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_audit_usuario_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_audit_usuario_update');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_audit_usuario_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_audit_pelicula_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_audit_pelicula_update');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_audit_pelicula_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_audit_venta_insert');

        Schema::dropIfExists('hist_precio_funcion');
        Schema::dropIfExists('auditoria_venta');
        Schema::dropIfExists('auditoria_funcion');
        Schema::dropIfExists('auditoria_pelicula');
        Schema::dropIfExists('auditoria_usuario');
        Schema::dropIfExists('auditoria_empleado');
        Schema::dropIfExists('detalle_venta');
        Schema::dropIfExists('venta');
        Schema::dropIfExists('funcion');
        Schema::dropIfExists('evento_especial');
        Schema::dropIfExists('pelicula');
        Schema::dropIfExists('usuario');
        Schema::dropIfExists('empleado');
    }
};
