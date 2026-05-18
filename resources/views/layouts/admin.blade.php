<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CineSystem') — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:       #0d0d0d;
            --surface:  #1a1a1a;
            --surface2: #242424;
            --border:   #2e2e2e;
            --red:      #e53935;
            --red-dark: #b71c1c;
            --gold:     #f5a623;
            --text:     #f0f0f0;
            --muted:    #888;
            --success:  #2e7d32;
            --radius:   8px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }

        /* ── Sidebar ── */
        .sidebar {
            width: 240px; min-height: 100vh; background: var(--surface);
            border-right: 1px solid var(--border); display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; height: 100%;
        }
        .sidebar-brand {
            padding: 20px 16px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-brand .logo { font-size: 24px; }
        .sidebar-brand .name { font-size: 18px; font-weight: 700; color: var(--red); letter-spacing: 1px; }
        .sidebar-brand .sub  { font-size: 11px; color: var(--muted); }
        .sidebar-nav { flex: 1; padding: 12px 0; overflow-y: auto; }
        .nav-section { padding: 8px 16px 4px; font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); }
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 16px; color: #ccc; text-decoration: none;
            font-size: 14px; transition: all .2s; border-left: 3px solid transparent;
        }
        .nav-link:hover, .nav-link.active {
            background: var(--surface2); color: var(--text);
            border-left-color: var(--red);
        }
        .nav-link .icon { font-size: 16px; width: 20px; text-align: center; }
        .sidebar-footer {
            padding: 16px; border-top: 1px solid var(--border);
        }
        .user-info { font-size: 12px; color: var(--muted); margin-bottom: 8px; }
        .user-name { font-weight: 600; color: var(--text); font-size: 14px; }
        .btn-logout {
            display: block; width: 100%; padding: 8px; text-align: center;
            background: var(--surface2); color: #ccc; border: 1px solid var(--border);
            border-radius: var(--radius); text-decoration: none; font-size: 13px;
            cursor: pointer; transition: all .2s;
        }
        .btn-logout:hover { background: var(--red-dark); color: #fff; border-color: var(--red-dark); }

        /* ── Main ── */
        .main { margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar {
            padding: 16px 28px; border-bottom: 1px solid var(--border);
            background: var(--surface); display: flex; align-items: center; justify-content: space-between;
        }
        .topbar h1 { font-size: 20px; font-weight: 600; }
        .badge-rol {
            padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600;
            background: var(--red); color: #fff; letter-spacing: 1px;
        }
        .content { padding: 28px; flex: 1; }

        /* ── Alerts ── */
        .alert { padding: 12px 16px; border-radius: var(--radius); margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: #1b3a1c; border: 1px solid #2e7d32; color: #81c784; }
        .alert-error   { background: #3a1b1b; border: 1px solid #c62828; color: #ef9a9a; }

        /* ── Cards ── */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; margin-bottom: 24px; }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border); }
        .card-title  { font-size: 16px; font-weight: 600; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { background: var(--surface2); color: var(--muted); padding: 10px 14px; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; }
        td { padding: 12px 14px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        tr:hover td { background: var(--surface2); }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: var(--radius); font-size: 13px; font-weight: 500;
            text-decoration: none; border: none; cursor: pointer; transition: all .2s;
        }
        .btn-primary  { background: var(--red);     color: #fff; }
        .btn-primary:hover  { background: var(--red-dark); }
        .btn-secondary{ background: var(--surface2); color: #ccc; border: 1px solid var(--border); }
        .btn-secondary:hover{ background: var(--border); color: var(--text); }
        .btn-danger   { background: #3a1b1b; color: #ef9a9a; border: 1px solid #c62828; }
        .btn-danger:hover   { background: var(--red-dark); color: #fff; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-gold { background: var(--gold); color: #111; }
        .btn-gold:hover { background: #d4901f; }

        /* ── Forms ── */
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 13px; color: var(--muted); margin-bottom: 6px; }
        input[type=text], input[type=number], input[type=date], input[type=time],
        input[type=password], select, textarea {
            width: 100%; padding: 10px 14px; background: var(--surface2);
            border: 1px solid var(--border); border-radius: var(--radius);
            color: var(--text); font-size: 14px; font-family: inherit; transition: border .2s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none; border-color: var(--red);
        }
        .invalid-feedback { color: #ef9a9a; font-size: 12px; margin-top: 4px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-actions { display: flex; gap: 10px; margin-top: 24px; }

        /* ── Stats ── */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; }
        .stat-label { font-size: 12px; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; }
        .stat-value { font-size: 32px; font-weight: 700; color: var(--red); margin: 4px 0; }
        .stat-sub   { font-size: 12px; color: var(--muted); }

        /* ── Pagination ── */
        .pagination { display: flex; gap: 6px; margin-top: 20px; flex-wrap: wrap; }
        .pagination a, .pagination span {
            padding: 6px 12px; border-radius: 6px; font-size: 13px; text-decoration: none;
            background: var(--surface2); color: var(--text); border: 1px solid var(--border);
        }
        .pagination .active { background: var(--red); border-color: var(--red); }
        .pagination a:hover { background: var(--border); }

        /* ── Chips ── */
        .chip { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .chip-pelicula { background: #1a237e22; color: #7986cb; border: 1px solid #3949ab44; }
        .chip-evento   { background: #1b5e2022; color: #66bb6a; border: 1px solid #2e7d3244; }
        .chip-insert   { background: #1b5e2022; color: #66bb6a; }
        .chip-update   { background: #0d47a122; color: #64b5f6; }
        .chip-delete   { background: #b71c1c22; color: #ef9a9a; }
    </style>
    @stack('styles')
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="logo">🎬</span>
        <div>
            <div class="name">CINE</div>
            <div class="sub">Panel Administrativo</div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Principal</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif">
            <span class="icon">📊</span> Dashboard
        </a>

        <div class="nav-section">Gestión</div>
        <a href="{{ route('admin.empleados.index') }}" class="nav-link @if(request()->routeIs('admin.empleados.*')) active @endif">
            <span class="icon">👤</span> Empleados
        </a>
        <a href="{{ route('admin.usuarios.index') }}" class="nav-link @if(request()->routeIs('admin.usuarios.*')) active @endif">
            <span class="icon">🔐</span> Usuarios
        </a>
        <a href="{{ route('admin.peliculas.index') }}" class="nav-link @if(request()->routeIs('admin.peliculas.*')) active @endif">
            <span class="icon">🎥</span> Películas
        </a>
        <a href="{{ route('admin.eventos.index') }}" class="nav-link @if(request()->routeIs('admin.eventos.*')) active @endif">
            <span class="icon">🎪</span> Eventos
        </a>
        <a href="{{ route('admin.funciones.index') }}" class="nav-link @if(request()->routeIs('admin.funciones.*')) active @endif">
            <span class="icon">📅</span> Funciones
        </a>

        <div class="nav-section">Sistema</div>
        <a href="{{ route('admin.reportes.index')}}" class="nav-link @if(request()->routeIs('admin.reportes.*')) active @endif">
            <span class="icon">📊</span> Reportes
        </a>
        <a href="{{ route('admin.auditoria.index') }}" class="nav-link @if(request()->routeIs('admin.auditoria.*')) active @endif">
            <span class="icon">🔍</span> Auditoría
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-name">{{ session('usuario')['nombre_empleado'] }}</div>
        <div class="user-info">{{ session('usuario')['rol'] }}</div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <h1>@yield('page-title', 'Dashboard')</h1>
        <span class="badge-rol">{{ session('usuario')['rol'] }}</span>
    </div>
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">❌ {{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</div>
@stack('scripts')
</body>
</html>