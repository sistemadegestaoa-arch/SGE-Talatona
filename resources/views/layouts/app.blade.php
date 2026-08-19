<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistema de Gestão de estoque - @yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('public/js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="public/assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="public/assets/fonts/ionicons.css">
    <link rel="stylesheet" href="public/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="public/assets/fonts/open-iconic.css">
    <link rel="stylesheet" href="public/assets/fonts/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="public/assets/fonts/feather.css">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="public/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="public/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="public/assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="public/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="public/assets/libs/flot/flot.css">
    <link href="public/assets/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="assets/img/logo.jpg">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light  shadow-sm"  style="background: #06529b;">
            <div class="container">
                <span class="app-brand-logo demo ">
                    <img src="public/assets/img/logo.JPG" alt="Brand Logo" class="img-fluid" width="70" height="30">
                </span>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="public/assets/js/pace.js"></script>
    <script src="public/assets/js/jquery-3.3.1.min.js"></script>
    <script src="public/assets/libs/popper/popper.js"></script>
    <script src="public/assets/js/bootstrap.js"></script>
    <script src="public/assets/js/sidenav.js"></script>
    <script src="public/assets/js/layout-helpers.js"></script>
    <script src="public/assets/js/material-ripple.js"></script>

    <!-- Libs -->
    <script src="public/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="public/assets/libs/eve/eve.js"></script>
    <script src="public/assets/libs/flot/flot.js"></script>
    <script src="public/assets/libs/flot/curvedLines.js"></script>
    <script src="public/assets/libs/chart-am4/core.js"></script>
    <script src="public/assets/libs/chart-am4/charts.js"></script>
    <script src="public/assets/libs/chart-am4/animated.js"></script>

    <!-- Demo -->

    <script src="public/assets/js/pages/dashboards_index.js"></script>
</body>
</html>
