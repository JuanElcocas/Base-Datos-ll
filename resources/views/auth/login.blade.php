<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineSystem — Iniciar Sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --red: #e53935; --red-dark: #b71c1c; --bg: #0a0a0a; --surface: #161616; --border: #2a2a2a; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif; background: var(--bg); color: #f0f0f0;
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background-image: radial-gradient(circle at 20% 50%, #1a0000 0%, transparent 50%),
                              radial-gradient(circle at 80% 50%, #0d0d1a 0%, transparent 50%);
        }
        .login-wrap { width: 100%; max-width: 400px; padding: 20px; }
        .login-brand { text-align: center; margin-bottom: 32px; }
        .login-brand .icon { font-size: 48px; margin-bottom: 8px; }
        .login-brand h1 { font-size: 32px; font-weight: 800; color: var(--red); letter-spacing: 3px; }
        .login-brand p  { font-size: 13px; color: #666; margin-top: 4px; }
        .login-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 14px; padding: 36px; box-shadow: 0 20px 60px #00000088;
        }
        .login-card h2 { font-size: 18px; font-weight: 600; margin-bottom: 24px; color: #ddd; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 12px; color: #777; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 8px; }
        input {
            width: 100%; padding: 12px 16px; background: #1f1f1f;
            border: 1px solid var(--border); border-radius: 8px;
            color: #f0f0f0; font-size: 15px; font-family: inherit; transition: border .2s;
        }
        input:focus { outline: none; border-color: var(--red); }
        .btn-login {
            width: 100%; padding: 14px; background: var(--red); color: #fff;
            border: none; border-radius: 8px; font-size: 15px; font-weight: 600;
            cursor: pointer; transition: all .2s; margin-top: 8px; letter-spacing: .5px;
        }
        .btn-login:hover { background: var(--red-dark); transform: translateY(-1px); box-shadow: 0 6px 20px #e5393544; }
        .alert-error { background: #2a0e0e; border: 1px solid #6b1a1a; color: #f87171; padding: 12px 14px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; }
        .invalid-feedback { color: #f87171; font-size: 12px; margin-top: 4px; }
        .footer-note { text-align: center; margin-top: 20px; font-size: 12px; color: #444; }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-brand">
        <div class="icon">🎬</div>
        <h1>CINESYS</h1>
        <p>Sistema de Gestión de Cine</p>
    </div>
    <div class="login-card">
        <h2>Iniciar sesión</h2>

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" value="{{ old('usuario') }}"
                       placeholder="Ingresa tu usuario" autocomplete="username">
                @error('usuario')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena"
                       placeholder="••••••••" autocomplete="current-password">
                @error('contrasena')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn-login">Entrar →</button>
        </form>
    </div>
    <p class="footer-note">bd2_grupo06 · Sistema académico</p>
</div>
</body>
</html>