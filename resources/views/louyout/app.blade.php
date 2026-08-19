<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Gestão de estoque @yield('title')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/fonts/ionicons.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/fonts/linearicons.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/fonts/open-iconic.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/bootstrap-material.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/shreerang-material.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/uikit.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link href="{{ asset('public/assets/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('public/assets/img/logo.png') }}">

    <style>
        /* ══════════════════════════════════════
           LOADING SCREEN
        ══════════════════════════════════════ */
        #kifica-loader {
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: linear-gradient(145deg, #0f3d1e 0%, #1a6b2f 60%, #2d9e4a 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 32px;
            transition: opacity .5s ease, visibility .5s ease;
        }

        #kifica-loader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        /* Logo + nome */
        .loader-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            animation: loaderFadeDown .6s ease both;
        }

        .loader-brand img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.12);
            padding: 10px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .25);
        }

        .loader-brand h1 {
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 2px;
            margin: 0;
        }

        .loader-brand p {
            color: rgba(255, 255, 255, .6);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            margin: 0;
        }

        /* Barra de progresso */
        .loader-bar-wrap {
            width: 220px;
            height: 4px;
            background: rgba(255, 255, 255, .15);
            border-radius: 99px;
            overflow: hidden;
            animation: loaderFadeUp .6s ease .2s both;
        }

        .loader-bar {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #5dd87e, #a7f3c0);
            border-radius: 99px;
            animation: loaderProgress 1.6s cubic-bezier(.4, 0, .2, 1) .3s forwards;
            box-shadow: 0 0 12px rgba(93, 216, 126, .6);
        }

        /* Texto de estado */
        .loader-status {
            color: rgba(255, 255, 255, .55);
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            letter-spacing: .6px;
            animation: loaderFadeUp .6s ease .4s both;
        }

        /* Círculos decorativos de fundo */
        .loader-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .04);
            animation: loaderPulse 3s ease-in-out infinite;
        }

        .loader-circle:nth-child(1) {
            width: 400px;
            height: 400px;
            top: -120px;
            left: -120px;
            animation-delay: 0s;
        }

        .loader-circle:nth-child(2) {
            width: 300px;
            height: 300px;
            bottom: -80px;
            right: -80px;
            animation-delay: 1s;
        }

        .loader-circle:nth-child(3) {
            width: 180px;
            height: 180px;
            top: 40%;
            right: 10%;
            animation-delay: .5s;
        }

        /* Pontos animados */
        .loader-dots {
            display: flex;
            gap: 8px;
            animation: loaderFadeUp .6s ease .5s both;
        }

        .loader-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .4);
            animation: loaderDot 1.2s ease-in-out infinite;
        }

        .loader-dot:nth-child(1) {
            animation-delay: 0s;
        }

        .loader-dot:nth-child(2) {
            animation-delay: .2s;
        }

        .loader-dot:nth-child(3) {
            animation-delay: .4s;
        }

        @keyframes loaderProgress {
            0% {
                width: 0%;
            }

            30% {
                width: 45%;
            }

            70% {
                width: 75%;
            }

            100% {
                width: 100%;
            }
        }

        @keyframes loaderFadeDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes loaderFadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes loaderPulse {

            0%,
            100% {
                transform: scale(1);
                opacity: .04;
            }

            50% {
                transform: scale(1.08);
                opacity: .08;
            }
        }

        @keyframes loaderDot {

            0%,
            80%,
            100% {
                transform: scale(.7);
                opacity: .3;
            }

            40% {
                transform: scale(1.2);
                opacity: 1;
                background: rgba(255, 255, 255, .9);
            }
        }

        /* ── VARIÁVEIS VERDES ── */
        :root {
            --green-dark: #1a6b2f;
            --green-mid: #2d9e4a;
            --green-light: #3aad5e;
            --green-pale: #f0faf2;
            --green-border: #d1fae5;
            --sidebar-bg: #0f3d1e;
            --sidebar-hover: rgba(255, 255, 255, 0.08);
            --sidebar-active: rgba(58, 173, 94, 0.25);
            --topbar-bg: #1a6b2f;
            --text-main: #1a2e1a;
            --text-muted: #6b7280;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f7f4;
            color: var(--text-main);
        }

        /* ── SIDEBAR ── */
        #layout-sidenav.sidenav {
            background: var(--sidebar-bg) !important;
            border-right: none !important;
        }

        .app-brand {
            background: var(--green-dark) !important;
            padding: 16px 20px !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        .app-brand-text {
            color: #fff !important;
            font-weight: 700 !important;
            font-size: 16px !important;
            letter-spacing: 1px;
        }

        /* Links do sidenav */
        .sidenav-inner .sidenav-link {
            color: rgba(255, 255, 255, 0.75) !important;
            border-radius: 8px !important;
            margin: 2px 10px !important;
            padding: 10px 14px !important;
            transition: background .2s, color .2s !important;
            font-size: 13px !important;
            font-weight: 500 !important;
        }

        .sidenav-inner .sidenav-link:hover {
            background: var(--sidebar-hover) !important;
            color: #fff !important;
        }

        .sidenav-inner .sidenav-item.active>.sidenav-link,
        .sidenav-inner .sidenav-link.active {
            background: var(--sidebar-active) !important;
            color: #5dd87e !important;
        }

        .sidenav-icon {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        .sidenav-inner .sidenav-item.active .sidenav-icon {
            color: #5dd87e !important;
        }

        .sidenav-divider {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        /* Sub-menu */
        .sidenav-menu .sidenav-link {
            font-size: 12px !important;
            padding-left: 28px !important;
            color: rgba(255, 255, 255, 0.6) !important;
        }

        /* Badge de requisições */
        .badge-req {
            display: inline-block;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 20px;
            margin-left: 6px;
            vertical-align: middle;
        }

        /* ── TOPBAR ── */
        #layout-navbar {
            background: var(--topbar-bg) !important;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .15) !important;
            padding: 0 24px !important;
            min-height: 60px;
        }

        #layout-navbar .navbar-brand,
        #layout-navbar .nav-link,
        #layout-navbar .ion,
        #layout-navbar .feather {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        .user-info-text {
            color: rgba(255, 255, 255, 0.9) !important;
            font-size: 13px;
        }

        .dropdown-menu {
            border-radius: 12px !important;
            border: 1px solid var(--green-border) !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12) !important;
            padding: 6px !important;
        }

        .dropdown-item {
            border-radius: 8px !important;
            font-size: 13px !important;
            padding: 8px 14px !important;
        }

        .dropdown-item:hover {
            background: var(--green-pale) !important;
            color: var(--green-dark) !important;
        }

        /* ── CONTEÚDO ── */
        .layout-container {
            background: #f4f7f4 !important;
        }

        .layout-container>.container {
            padding: 24px 20px;
            max-width: 100%;
        }

        /* ── BOTÕES GLOBAIS ── */
        .btn-primary,
        .btn-success {
            background: var(--green-dark) !important;
            border-color: var(--green-dark) !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            font-size: 13px !important;
        }

        .btn-primary:hover,
        .btn-success:hover {
            background: var(--green-mid) !important;
            border-color: var(--green-mid) !important;
        }

        .btn-danger {
            border-radius: 8px !important;
            font-weight: 600 !important;
            font-size: 13px !important;
        }

        .btn-secondary,
        .btn-info {
            border-radius: 8px !important;
            font-weight: 600 !important;
            font-size: 13px !important;
        }

        /* ── CARDS ── */
        .card {
            border-radius: 14px !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05) !important;
        }

        .card-header {
            background: var(--green-pale) !important;
            border-bottom: 1px solid var(--green-border) !important;
            border-radius: 14px 14px 0 0 !important;
            font-weight: 600 !important;
            color: var(--green-dark) !important;
        }

        /* ── TABELAS ── */
        .table thead th {
            background: var(--green-pale) !important;
            color: var(--green-dark) !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            letter-spacing: .5px;
            border-bottom: 2px solid var(--green-border) !important;
        }

        .table tbody tr:hover td {
            background: #f0faf2 !important;
        }

        /* ── FORMS ── */
        .form-control {
            border-radius: 9px !important;
            border: 1.5px solid #e5e7eb !important;
            font-size: 13px !important;
        }

        .form-control:focus {
            border-color: var(--green-mid) !important;
            box-shadow: 0 0 0 3px rgba(45, 158, 74, 0.12) !important;
        }

        /* ── PRELOADER ── */
        .page-loader .bg-primary {
            background: var(--green-dark) !important;
        }

        /* ── RESPONSIVO ── */
        @media (max-width: 768px) {
            .layout-container>.container {
                padding: 16px 12px;
            }
        }
    </style>
</head>

<body>

    <!-- ══ LOADING SCREEN ══ -->
    <div id="kifica-loader">
        <div class="loader-circle"></div>
        <div class="loader-circle"></div>
        <div class="loader-circle"></div>

        <div class="loader-brand">
            <img src="{{ asset('public/assets/img/logo2.JPG') }}" alt="Kifica">
            <h1>KIFICA</h1>
            <p>Sistema de Gestão de Estoque</p>
        </div>

        <div class="loader-bar-wrap">
            <div class="loader-bar"></div>
        </div>

        <div class="loader-dots">
            <div class="loader-dot"></div>
            <div class="loader-dot"></div>
            <div class="loader-dot"></div>
        </div>

        <span class="loader-status">A carregar...</span>
    </div>

    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <!-- SIDEBAR -->
            <div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical logo-dark">
                <div class="app-brand demo">

                    <a href="{{ route('home.index') }}"
                        class="app-brand-text demo sidenav-text font-weight-normal ml-2">SGE - KIFICA</a>
                    <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
                        <i class="ion ion-md-menu align-middle" style="color:rgba(255,255,255,0.7)"></i>
                    </a>
                </div>
                <div class="sidenav-divider mt-0"></div>

                @php
                    // Determina qual menu mostrar com base no departamento do utilizador
                    $depAtualNome =
                        \DB::table('departamento')
                            ->where('id', Auth::user()->departamento_id)
                            ->value('departamento') ?? '';
                    $dn = mb_strtolower($depAtualNome);
                    $dn = strtr($dn, [
                        'á' => 'a',
                        'à' => 'a',
                        'â' => 'a',
                        'ã' => 'a',
                        'é' => 'e',
                        'è' => 'e',
                        'ê' => 'e',
                        'í' => 'i',
                        'ó' => 'o',
                        'ô' => 'o',
                        'õ' => 'o',
                        'ú' => 'u',
                        'ç' => 'c',
                        'ñ' => 'n',
                    ]);

                    $menuTriagem =
                        str_contains($dn, 'catalogac') || str_contains($dn, 'consultas') || str_contains($dn, 'triag');
                    $menuMedico =
                        str_contains($dn, 'banco') ||
                        str_contains($dn, 'medic') ||
                        str_contains($dn, 'pediatr') ||
                        str_contains($dn, 'intern') ||
                        str_contains($dn, 'cirurg') ||
                        str_contains($dn, 'puerp') ||
                        str_contains($dn, 'odont') ||
                        str_contains($dn, 'tisiolog');
                    $menuLaboratorio = str_contains($dn, 'lab') || str_contains($dn, 'raio') || str_contains($dn, 'hemot') || str_contains($dn, 'cada');
                    $menuEnfermeiro  = str_contains($dn, 's.o') || str_contains($dn, 'observa') || str_contains($dn, 'enferm') || str_contains($dn, 'p.a.v') || str_contains($dn, 'pav') || (str_contains($dn, 's.a.t') && !$menuTriagem);
                    $menuFarmacia    = str_contains($dn, 'farm') && Auth::user()->tipo !== 'admin';
                    $menuAdmin       = Auth::user()->tipo === 'admin';
                @endphp

                @if ($menuTriagem)
                    @include('louyout.triagem')
                @elseif($menuEnfermeiro)
                    @include('louyout.enfermeiro')
                @elseif($menuMedico)
                    @include('louyout.medico')
                @elseif($menuLaboratorio)
                    @include('louyout.laboratorio')
                @elseif($menuFarmacia)
                    @include('louyout.farmacia')
                @elseif($menuAdmin)
                    @include('louyout.admin')
                @else
                    @include('louyout.user')
                @endif
            </div>

            <!-- CONTEÚDO -->
            <div class="layout-container">

                <!-- TOPBAR -->
                <nav class="layout-navbar navbar navbar-expand-lg align-items-lg-center container-p-x"
                    id="layout-navbar">

                    <div class="layout-sidenav-toggle navbar-nav d-lg-none align-items-lg-center mr-auto">
                        <a class="nav-item nav-link px-0" href="javascript:">
                            <i class="ion ion-md-menu text-large align-middle"></i>
                        </a>
                    </div>

                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#layout-navbar-collapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="navbar-collapse collapse" id="layout-navbar-collapse">
                        <div class="navbar-nav align-items-lg-center ml-auto">

                            <!-- Alerta de requisições pendentes -->
                            @php $pendentes = \DB::table('requisicao')->where('statos','Pendente')->count(); @endphp
                            @if ($pendentes > 0 && Auth::user()->tipo == 'admin')
                                <a href="{{ route('verrequisicao.showr') }}" class="nav-item nav-link mr-2"
                                    title="Requisições pendentes">
                                    <i class="feather icon-bell" style="color:#fbbf24;font-size:18px;"></i>
                                    <span class="badge-req">{{ $pendentes }}</span>
                                </a>
                            @endif

                            <div
                                class="nav-item d-none d-lg-block text-big font-weight-light line-height-1 opacity-25 mr-3 ml-1">
                                |</div>

                            <!-- Dropdown do utilizador -->
                            <div class="demo-navbar-user nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                                    <span class="d-inline-flex flex-lg-row-reverse align-items-center align-middle">
                                        <span
                                            style="width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;">
                                            <i class="feather icon-user" style="color:#fff;font-size:16px;"></i>
                                        </span>
                                        <span class="px-1 mr-lg-2 ml-2 ml-lg-0 user-info-text">
                                            {{ Auth::user()->name }}<br>
                                            <small style="opacity:.7;font-size:11px;">
                                                {{ Auth::user()->tipo }}
                                                @php
                                                    $depUser = \DB::table('departamento')
                                                        ->join('users', 'departamento.id', '=', 'users.departamento_id')
                                                        ->where('users.id', Auth::id())
                                                        ->value('departamento.departamento');
                                                @endphp
                                                @if ($depUser)
                                                    &mdash; {{ $depUser }}
                                                @endif
                                            </small>
                                        </span>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <div
                                        style="padding:10px 14px 8px;border-bottom:1px solid #f3f4f6;margin-bottom:4px;">
                                        <div style="font-weight:600;font-size:13px;color:#1a2e1a;">
                                            {{ Auth::user()->name }}</div>
                                        <div style="font-size:11px;color:#6b7280;">{{ Auth::user()->email }}</div>
                                    </div>
                                    <a href="{{ url('sair') }}" class="dropdown-item text-danger">
                                        <i class="feather icon-power"></i> &nbsp; Sair
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </nav>

                <!-- PÁGINA -->
                <div class="container">
                    @yield('conteodo')
                </div>

            </div><!-- /layout-container -->
        </div>
    </div>

    <script src="{{ asset('public/assets/js/pace.js') }}"></script>
    <script src="{{ asset('public/assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('public/assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('public/assets/js/sidenav.js') }}"></script>
    <script src="{{ asset('public/assets/js/layout-helpers.js') }}"></script>
    <script src="{{ asset('public/assets/js/material-ripple.js') }}"></script>
    <script src="{{ asset('public/assets/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('public/assets/libs/flot/flot.js') }}"></script>
    <script src="{{ asset('public/assets/js/demo.js') }}"></script>
    <script src="{{ asset('public/assets/js/pages/dashboards_index.js') }}"></script>
    <script src="{{ asset('public/assets/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/assets/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/datatables-demo.js') }}"></script>
    <script src="{{ asset('public/assets/js/main.js') }}"></script>

    <script>
        // Esconde o loader quando a página terminar de carregar
        window.addEventListener('load', function() {
            const loader = document.getElementById('kifica-loader');
            setTimeout(function() {
                loader.classList.add('hidden');
            }, 1900);
        });

        // Esconde o loader ao navegar para trás/frente (bfcache)
        window.addEventListener('pageshow', function(e) {
            const loader = document.getElementById('kifica-loader');
            loader.classList.add('hidden');
        });

        // Mostra o loader ao navegar para outra página
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (
                link &&
                link.href &&
                link.getAttribute('href') !== '#' &&
                !link.getAttribute('href').startsWith('#') &&
                !link.href.startsWith('javascript') &&
                link.target !== '_blank' &&
                link.hostname === window.location.hostname &&
                !link.closest('.dropdown-menu') &&
                !link.hasAttribute('data-toggle') &&
                !link.classList.contains('dropdown-toggle')
            ) {
                const loader = document.getElementById('kifica-loader');
                loader.classList.remove('hidden');
                const bar = loader.querySelector('.loader-bar');
                bar.style.animation = 'none';
                bar.offsetHeight;
                bar.style.animation = 'loaderProgress 1.6s cubic-bezier(.4,0,.2,1) forwards';
            }
        });

        // Mostra o loader ao submeter formulários
        document.addEventListener('submit', function(e) {
            // Não activar se o form tiver onsubmit que pode retornar false
            // Deixa o onsubmit correr primeiro via setTimeout
            const form = e.target;
            if (form.hasAttribute('data-no-loader')) return;
            const loader = document.getElementById('kifica-loader');
            loader.classList.remove('hidden');
            const bar = loader.querySelector('.loader-bar');
            bar.style.animation = 'none';
            bar.offsetHeight;
            bar.style.animation = 'loaderProgress 1.6s cubic-bezier(.4,0,.2,1) forwards';
        });

        // ══════════════════════════════════════════════════════════════════════════
        //  SISTEMA DE NOTIFICAÇÕES — Server-Sent Events (tempo real)
        // ══════════════════════════════════════════════════════════════════════════

        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        let audioCtx = null;

        function getAudioCtx() {
            if (!audioCtx) audioCtx = new AudioCtx();
            return audioCtx;
        }

        function tocarSom(urgente) {
            try {
                const ctx = getAudioCtx();
                var p = ctx.state === 'suspended' ? ctx.resume() : Promise.resolve();
                p.then(function() {
                    if (urgente) {
                        [0, 0.25, 0.5].forEach(function(d) {
                            var o = ctx.createOscillator(),
                                g = ctx.createGain();
                            o.connect(g);
                            g.connect(ctx.destination);
                            o.type = 'square';
                            o.frequency.setValueAtTime(880, ctx.currentTime + d);
                            g.gain.setValueAtTime(0.6, ctx.currentTime + d);
                            g.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + d + 0.18);
                            o.start(ctx.currentTime + d);
                            o.stop(ctx.currentTime + d + 0.2);
                        });
                    } else {
                        [{
                            freq: 660,
                            t: 0
                        }, {
                            freq: 528,
                            t: 0.22
                        }].forEach(function(n) {
                            var o = ctx.createOscillator(),
                                g = ctx.createGain();
                            o.connect(g);
                            g.connect(ctx.destination);
                            o.type = 'sine';
                            o.frequency.setValueAtTime(n.freq, ctx.currentTime + n.t);
                            g.gain.setValueAtTime(0.5, ctx.currentTime + n.t);
                            g.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + n.t + 0.5);
                            o.start(ctx.currentTime + n.t);
                            o.stop(ctx.currentTime + n.t + 0.55);
                        });
                    }
                });
            } catch (e) {}
        }

        var toastWrap = document.createElement('div');
        toastWrap.style.cssText =
            'position:fixed;bottom:20px;right:20px;z-index:99998;display:flex;flex-direction:column;gap:10px;max-width:340px;pointer-events:none;';
        document.body.appendChild(toastWrap);

        var s = document.createElement('style');
        s.textContent =
            '@keyframes toastIn{from{opacity:0;transform:translateX(60px)}to{opacity:1;transform:translateX(0)}}@keyframes toastOut{from{opacity:1}to{opacity:0;transform:translateX(60px)}}@keyframes badgePulse{0%,100%{transform:scale(1)}50%{transform:scale(1.5)}}';
        document.head.appendChild(s);

        function mostrarToast(item) {
            var t = document.createElement('div');
            t.style.cssText = 'background:' + (item.urgente ? 'linear-gradient(135deg,#7f1d1d,#dc2626)' :
                    'linear-gradient(135deg,#0f3d1e,#1a6b2f)') +
                ';color:#fff;border-radius:14px;padding:14px 16px;box-shadow:0 8px 24px rgba(0,0,0,.3);display:flex;align-items:center;gap:12px;cursor:pointer;animation:toastIn .4s ease;pointer-events:all;';
            t.innerHTML = '<span style="font-size:26px;flex-shrink:0;">' + item.icone +
                '</span><div style="flex:1;"><div style="font-size:13px;font-weight:700;">' + item.texto +
                '</div><div style="font-size:11px;opacity:.75;margin-top:3px;">Clique para ver →</div></div><button onclick="event.stopPropagation();this.parentElement.style.animation=\'toastOut .3s ease forwards\';setTimeout(()=>this.parentElement.remove(),300);" style="background:rgba(255,255,255,.2);border:none;color:#fff;width:26px;height:26px;border-radius:50%;cursor:pointer;font-size:16px;flex-shrink:0;">×</button>';
            t.addEventListener('click', function() {
                window.location.href = item.url;
            });
            toastWrap.appendChild(t);
            setTimeout(function() {
                if (t.parentElement) {
                    t.style.animation = 'toastOut .4s ease forwards';
                    setTimeout(function() {
                        if (t.parentElement) t.remove();
                    }, 400);
                }
            }, 8000);
        }

        function processarNotificacoes(notifs) {
            notifs.forEach(function(item) {
                tocarSom(item.urgente);
                mostrarToast(item);
                document.querySelectorAll('.badge-req').forEach(function(b) {
                    b.style.animation = 'none';
                    b.offsetHeight;
                    b.style.animation = 'badgePulse .6s ease 3';
                });
            });
        }

        // ── SSE — ligação persistente em tempo real ───────────────────────────
        function iniciarSSE() {
            if (!window.EventSource) {
                iniciarPolling();
                return;
            }
            var sse = new EventSource('{{ url('api/sse') }}');
            sse.onmessage = function(e) {
                try {
                    var d = JSON.parse(e.data);
                    if (d.reconectar) {
                        sse.close();
                        setTimeout(iniciarSSE, 2000);
                        return;
                    }
                    if (d.notificacoes && d.notificacoes.length) processarNotificacoes(d.notificacoes);
                } catch (ex) {}
            };
            sse.onerror = function() {
                sse.close();
                setTimeout(iniciarSSE, 5000);
            };
        }

        // ── Fallback polling ──────────────────────────────────────────────────
        var _estado = {},
            _primeira = true;

        function iniciarPolling() {
            setTimeout(function() {
                _poll();
                setInterval(_poll, 15000);
            }, 5000);
        }

        function _poll() {
            fetch('{{ url('api/notificacoes') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(data) {
                    if (!data || !data.items) return;
                    var novas = [];
                    data.items.forEach(function(item) {
                        // Se é primeira vez que vemos este tipo, inicializa a 0 (não a item.total)
                        // para que uma subida de 0→N seja detectada
                        if (_primeira) {
                            _estado[item.tipo] = item.total;
                        } else {
                            var ant = _estado[item.tipo] !== undefined ? _estado[item.tipo] : 0;
                            if (item.total > ant) novas.push(item);
                            _estado[item.tipo] = item.total;
                        }
                    });
                    if (!_primeira && novas.length) processarNotificacoes(novas);
                    _primeira = false;
                }).catch(function() {});
        }

        // Inicia SSE 2 segundos após o loader
        setTimeout(iniciarSSE, 2000);

        // Activa AudioContext na primeira interacção
        document.addEventListener('click', function activarAudio() {
            try {
                getAudioCtx();
            } catch (e) {}
            document.removeEventListener('click', activarAudio);
        }, {
            once: true
        });
    </script>

</body>

</html>
