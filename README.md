# 🎬 CineAStor — Sistema de Gestión de Cine

Sistema completo para gestión de cine con roles de **ADMIN**, **SUPER** y **TAQUILLERO**.

## Credenciales de prueba

| Usuario | Contraseña | Rol |
|---------|-----------|-----|
| admin   | 1234       | SUPER |
| jefe    | 1234       | ADMIN |
| taq1    | 1234       | TAQUILLERO |

## Instalación

```bash
# 1. Instalar dependencias
composer install

# 2. Copiar entorno
cp .env.example .env
php artisan key:generate

# 3. Configurar .env con tu base de datos MySQL

# 4. Migrar y sembrar
php artisan migrate --seed

# 5. Ejecutar
php artisan serve
```

## Características

### Taquillero
- Ver funciones disponibles agrupadas por fecha
- Disponibilidad en tiempo real (verde/amarillo/rojo)
- Formulario de venta con cálculo de total dinámico
- Límite automático por capacidad de sala
- Historial personal de ventas

### Admin
- Dashboard con KPIs: empleados, películas, funciones, ingresos del día
- CRUD completo: empleados, usuarios, películas, eventos, funciones
- Historial de cambios de precio por función
- Centro de auditoría (triggers de BD registran todos los cambios)
- **Reportes con gráficas** (Chart.js): ingresos por día, por taquillero, por función
- **Exportar CSV** de todas las ventas (compatible con Excel)

## Errores corregidos
- `SESSION_DRIVER` cambiado de `database` a `file` (la tabla sessions no existía)
- Carpeta `layouts/` creada (vistas buscaban `layouts.admin` y `layouts.taquillero`)
- Vistas de taquillero movidas a `resources/views/taquillero/`
- Vista `shared/pagination` creada
- Vista `admin/funciones/historial` correctamente ubicada
- Migración completa con todas las tablas y triggers MySQL
