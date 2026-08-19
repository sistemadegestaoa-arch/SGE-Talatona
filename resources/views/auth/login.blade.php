<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KIFICA - Entrar</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/bootstrap-material.css') }}">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            height: 100vh;
            overflow: hidden;
            background: #f0f4f8;
        }

        .wrapper {
            display: flex;
            height: 100vh;
        }

        /* ── LADO ESQUERDO ── */
        .left-panel {
            flex: 1;
            background: linear-gradient(145deg, #1a6b2f 0%, #0f3d1e 60%, #072010 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            top: -100px; left: -100px;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            bottom: -80px; right: -80px;
        }

        .left-panel .brand {
            text-align: center;
            z-index: 1;
            margin-bottom: 40px;
        }

        .left-panel .brand img {
            width: 90px;
            height: 90px;
            object-fit: contain;
            border-radius: 16px;
            background: rgba(255,255,255,0.1);
            padding: 10px;
            margin-bottom: 16px;
        }

        .left-panel .brand h1 {
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .left-panel .brand p {
            color: rgba(255,255,255,0.65);
            font-size: 14px;
            margin-top: 6px;
        }

        .left-panel .illustration {
            z-index: 1;
            text-align: center;
        }

        .left-panel .illustration svg {
            width: 280px;
            opacity: 0.85;
        }

        .left-panel .tagline {
            z-index: 1;
            margin-top: 36px;
            text-align: center;
        }

        .left-panel .tagline h2 {
            color: #fff;
            font-size: 20px;
            font-weight: 600;
            line-height: 1.4;
        }

        .left-panel .tagline p {
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            margin-top: 8px;
        }

        /* ── LADO DIREITO ── */
        .right-panel {
            width: 480px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 44px;
            overflow-y: auto;
        }

        .right-panel .form-header {
            margin-bottom: 32px;
        }

        .right-panel .form-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1a2332;
        }

        .right-panel .form-header p {
            color: #6b7280;
            font-size: 14px;
            margin-top: 6px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-group .input-icon {
            position: relative;
        }

        .form-group .input-icon i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
        }

        .form-group .input-icon input {
            padding-left: 40px;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            color: #1a2332;
            background: #f9fafb;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        .form-control:focus {
            border-color: #1a6b2f;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26,107,47,0.1);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .remember-row label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #06529b;
            cursor: pointer;
        }

        .remember-row a {
            font-size: 13px;
            color: #1a6b2f;
            text-decoration: none;
            font-weight: 500;
        }

        .remember-row a:hover { text-decoration: underline; }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #1a6b2f, #2d9e4a);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity .2s, transform .1s;
            letter-spacing: .3px;
        }

        .btn-submit:hover { opacity: .92; transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }

        .setup-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #6b7280;
        }

        .setup-link a {
            color: #1a6b2f;
            font-weight: 600;
            text-decoration: none;
        }

        .setup-link a:hover { text-decoration: underline; }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 32px 24px; }
        }
    </style>
</head>
<body>

<div class="wrapper">

    <!-- LADO ESQUERDO -->
    <div class="left-panel">
        <div class="brand">
            <img src="{{ asset('public/assets/img/insignia.png') }}" alt="Logo Kifica">
            <h1>CENTRO DE REFERÊNCIA DO KIFICA</h1>
            <p>Sistema de Gestão de Estoque</p>
        </div>

        <div class="illustration">
            <svg viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Prateleiras -->
                <rect x="40" y="200" width="320" height="12" rx="4" fill="rgba(255,255,255,0.2)"/>
                <rect x="40" y="140" width="320" height="12" rx="4" fill="rgba(255,255,255,0.2)"/>
                <rect x="40" y="80" width="320" height="12" rx="4" fill="rgba(255,255,255,0.2)"/>
                <!-- Colunas -->
                <rect x="40" y="70" width="10" height="150" rx="3" fill="rgba(255,255,255,0.15)"/>
                <rect x="350" y="70" width="10" height="150" rx="3" fill="rgba(255,255,255,0.15)"/>
                <!-- Caixas prateleira 1 -->
                <rect x="60" y="155" width="45" height="40" rx="5" fill="rgba(255,255,255,0.3)"/>
                <rect x="115" y="160" width="35" height="35" rx="5" fill="rgba(255,255,255,0.2)"/>
                <rect x="160" y="155" width="50" height="40" rx="5" fill="rgba(255,255,255,0.25)"/>
                <rect x="220" y="162" width="30" height="33" rx="5" fill="rgba(255,255,255,0.2)"/>
                <rect x="260" y="155" width="45" height="40" rx="5" fill="rgba(255,255,255,0.3)"/>
                <rect x="315" y="158" width="30" height="37" rx="5" fill="rgba(255,255,255,0.2)"/>
                <!-- Caixas prateleira 2 -->
                <rect x="60" y="95" width="40" height="40" rx="5" fill="rgba(255,255,255,0.25)"/>
                <rect x="110" y="98" width="55" height="37" rx="5" fill="rgba(255,255,255,0.3)"/>
                <rect x="175" y="95" width="35" height="40" rx="5" fill="rgba(255,255,255,0.2)"/>
                <rect x="220" y="98" width="50" height="37" rx="5" fill="rgba(255,255,255,0.25)"/>
                <rect x="280" y="95" width="40" height="40" rx="5" fill="rgba(255,255,255,0.3)"/>
                <rect x="330" y="98" width="25" height="37" rx="5" fill="rgba(255,255,255,0.2)"/>
                <!-- Pessoa -->
                <circle cx="200" cy="42" r="16" fill="rgba(255,255,255,0.4)"/>
                <rect x="185" y="60" width="30" height="35" rx="8" fill="rgba(255,255,255,0.3)"/>
                <rect x="175" y="65" width="12" height="22" rx="5" fill="rgba(255,255,255,0.25)"/>
                <rect x="213" y="65" width="12" height="22" rx="5" fill="rgba(255,255,255,0.25)"/>
                <rect x="188" y="93" width="10" height="25" rx="4" fill="rgba(255,255,255,0.25)"/>
                <rect x="202" y="93" width="10" height="25" rx="4" fill="rgba(255,255,255,0.25)"/>
            </svg>
        </div>

        <div class="tagline">
            <h2>Bem-vindo de volta</h2>
            <p>Acesse o sistema para gerir o seu estoque.</p>
        </div>
    </div>

    <!-- LADO DIREITO -->
    <div class="right-panel">
        <div class="form-header">
            <h2>Entrar no sistema</h2>
            <p>Insira as suas credenciais para continuar.</p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                ✗ Email ou senha incorretos. Tente novamente.
            </div>
        @endif

        @if(session('admin_exists'))
            <div class="alert-error">
                ⚠ {{ session('admin_exists') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="email@exemplo.com"
                           required autofocus autocomplete="email">
                </div>
                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>Senha</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="A sua senha"
                           required autocomplete="current-password">
                </div>
                @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Lembrar-me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Esqueceu a senha?</a>
                @endif
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-sign-in-alt" style="margin-right:8px;"></i> Entrar
            </button>
        </form>

        @php
            $direcao = \App\Departamento::where('departamento', 'Direcção')->first();
            $adminExiste = $direcao
                ? \App\User::where('tipo', 'admin')->where('departamento_id', $direcao->id)->exists()
                : false;
        @endphp

        @if(!$adminExiste)
        <div class="setup-link">
            Ainda não tem conta? <a href="{{ route('setup.index') }}">Criar conta de administrador</a>
        </div>
        @endif
    </div>

</div>

</body>
</html>
