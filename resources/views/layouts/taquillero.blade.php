<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Taquilla') — CineSystem</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:       #0a0a0a;
            --surface:  #161616;
            --surface2: #1f1f1f;
            --border:   #2a2a2a;
            --red:      #e53935;
            --red-dark: #b71c1c;
            --gold:     #f5a623;
            --green:    #43a047;
            --yellow:   #fdd835;
            --text:     #f0f0f0;
            --muted:    #777;
            --radius:   10px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

        /* ── Topbar ── */
        .topbar {
            background: var(--surface); border-bottom: 1px solid var(--border);
            padding: 0 24px; display: flex; align-items: center; justify-content: space-between;
            height: 60px; position: sticky; top: 0; z-index: 100;
        }
        .brand { display: flex; align-items: center; gap: 10px; font-size: 18px; font-weight: 700; color: var(--red); }
        .topbar-nav { display: flex; gap: 4px; }
        .nav-link {
            padding: 8px 14px; border-radius: 8px; text-decoration: none;
            color: #aaa; font-size: 14px; transition: all .2s;
        }
        .nav-link:hover, .nav-link.active { background: var(--surface2); color: var(--text); }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .user-badge { font-size: 13px; color: var(--muted); }
        .btn-logout {
            padding: 6px 14px; background: var(--surface2); color: #ccc;
            border: 1px solid var(--border); border-radius: 8px; cursor: pointer;
            font-size: 13px; text-decoration: none; transition: all .2s;
        }
        .btn-logout:hover { background: var(--red-dark); color: #fff; border-color: var(--red-dark); }

        /* ── Content ── */
        .content { padding: 28px; max-width: 1200px; margin: 0 auto; }

        /* ── Alerts ── */
        .alert { padding: 14px 18px; border-radius: var(--radius); margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: #1b3a1c; border: 1px solid #2e7d32; color: #81c784; }
        .alert-error   { background: #3a1b1b; border: 1px solid #c62828; color: #ef9a9a; }

        /* ── Cards ── */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; margin-bottom: 20px; }
        .card-title { font-size: 16px; font-weight: 600; margin-bottom: 16px; }

        /* ── Funcion Cards ── */
        .funciones-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
        .funcion-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 20px; transition: all .25s;
            position: relative; overflow: hidden;
        }
        .funcion-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        }
        .funcion-card.disponible::before { background: var(--green); }
        .funcion-card.pocos::before      { background: var(--yellow); }
        .funcion-card.lleno::before      { background: var(--red); opacity: .5; }
        .funcion-card.disponible:hover   { border-color: var(--green); box-shadow: 0 4px 20px #43a04722; }
        .funcion-card.pocos:hover        { border-color: var(--yellow); }
        .funcion-card.lleno             { opacity: .6; }
        .fc-tipo { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); margin-bottom: 6px; }
        .fc-nombre { font-size: 17px; font-weight: 700; margin-bottom: 8px; line-height: 1.3; }
        .fc-info   { font-size: 13px; color: var(--muted); margin-bottom: 4px; }
        .fc-precio { font-size: 22px; font-weight: 700; color: var(--gold); margin: 12px 0 8px; }
        .fc-estado { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 20px; margin-bottom: 14px; }
        .fc-estado.disponible { background: #1b3a1c; color: #81c784; }
        .fc-estado.pocos      { background: #3a3000; color: #fdd835; }
        .fc-estado.lleno      { background: #2a1010; color: #ef9a9a; }
        .fc-espacios { font-size: 12px; color: var(--muted); margin-bottom: 14px; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 20px; border-radius: var(--radius); font-size: 14px; font-weight: 600;
            text-decoration: none; border: none; cursor: pointer; transition: all .2s; width: 100%;
            justify-content: center;
        }
        .btn-comprar { background: var(--red); color: #fff; font-size: 15px; padding: 12px; }
        .btn-comprar:hover { background: var(--red-dark); transform: translateY(-1px); }
        .btn-disabled { background: var(--surface2); color: var(--muted); cursor: not-allowed; }
        .btn-secondary { background: var(--surface2); color: #ccc; border: 1px solid var(--border); width: auto; }
        .btn-secondary:hover { background: var(--border); }
        .btn-gold { background: var(--gold); color: #111; width: auto; }
        .btn-gold:hover { background: #d4901f; }

        /* ── Forms ── */
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 13px; color: var(--muted); margin-bottom: 6px; }
        input[type=number], input[type=text] {
            width: 100%; padding: 12px 16px; background: var(--surface2);
            border: 1px solid var(--border); border-radius: var(--radius);
            color: var(--text); font-size: 16px; font-family: inherit; transition: border .2s;
        }
        input:focus { outline: none; border-color: var(--red); }
        .invalid-feedback { color: #ef9a9a; font-size: 12px; margin-top: 4px; }

        /* ── Stat mini ── */
        .stat-mini { font-size: 12px; color: var(--muted); }
        .stat-mini strong { color: var(--gold); font-size: 20px; display: block; }

        /* ── Table ── */
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { background: var(--surface2); color: var(--muted); padding: 10px 14px; text-align: left; font-size: 12px; text-transform: uppercase; }
        td { padding: 12px 14px; border-bottom: 1px solid var(--border); }
        tr:hover td { background: var(--surface2); }

        /* ── Venta Form ── */
        .venta-wrap { max-width: 520px; margin: 0 auto; }
        .venta-resumen { background: var(--surface2); border-radius: var(--radius); padding: 20px; margin-bottom: 24px; }
        .venta-total { font-size: 32px; font-weight: 700; color: var(--gold); text-align: center; margin-top: 12px; }
        .venta-label { font-size: 12px; color: var(--muted); text-align: center; }
        .page-title { font-size: 22px; font-weight: 700; margin-bottom: 24px; }
        .section-label { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); margin-bottom: 16px; }

        .pagination { display: flex; gap: 6px; margin-top: 20px; flex-wrap: wrap; }
        .pagination a, .pagination span { padding: 6px 12px; border-radius: 6px; font-size: 13px; text-decoration: none; background: var(--surface2); color: var(--text); border: 1px solid var(--border); }
        .pagination .active { background: var(--red); border-color: var(--red); }
    </style>
</head>
<body>
<nav class="topbar">
    <div class="brand">🎬 CINE</div>
    <div class="topbar-nav">
        <a href="{{ route('taquillero.dashboard') }}" class="nav-link @if(request()->routeIs('taquillero.dashboard')) active @endif">Inicio</a>
        <a href="{{ route('taquillero.funciones') }}" class="nav-link @if(request()->routeIs('taquillero.funciones')) active @endif">🎟 Vender</a>
        <a href="{{ route('taquillero.historial') }}" class="nav-link @if(request()->routeIs('taquillero.historial')) active @endif">📋 Mis Ventas</a>
    </div>
    <div class="topbar-right">
        <span class="user-badge">👤 {{ session('usuario')['nombre_empleado'] }}</span>
        <form action="{{ route('logout') }}" method="POST" style="display:inline">
            @csrf
            <button type="submit" class="btn-logout">Salir</button>
        </form>
    </div>
</nav>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @yield('content')
</div>
</body>
</html>