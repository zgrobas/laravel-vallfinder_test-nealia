<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{ asset('build/assets/app-CYfONqwl.css') }}">
    <style>
        @font-face {
                font-family: 'Noteworthy Light';
                font-style: normal;
                font-weight: normal;
                src: url("{{ asset('json/Noteworthy-Lt.woff') }}") format('woff');
            }

            .message-slogan {
                font-family: 'Noteworthy Light';
                font-weight: normal;
            }
    </style>
    @stack('css')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm p-3" style="height: 100px">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{-- {{ config('app.name', 'Laravel') }} --}}
                    <img height="48" src="{{ asset('images/Logo_GV_color.svg') }}" alt="VallCompanys" srcset="{{ asset('images/Logo_GV_color.svg') }}">
                </a>
                <div>
                    <span class="d-none d-md-block message-slogan" style="font-size: 1.5rem;color:#1c4175;">Nuestros productos, siempre cerca de ti.</span>
                </div>
                <div class="" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        {{-- @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif --}}
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle user-info" href="#"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu position-absolute" style="z-index: 1401" aria-labelledby="navbarDropdown">
                                @if (Auth::user()->super_admin)
                                    @if (Route::is('reg_usuario'))
                                        <a class="dropdown-item" href="{{ route('home') }}">Ver Mapa</a>
                                        <a class="dropdown-item" href="{{ route('update_usuario') }}">Modificar Permisos</a>
                                    @endif
                                    @if (Route::is('update_usuario'))
                                        <a class="dropdown-item" href="{{ route('home') }}">Ver Mapa</a>
                                        {{-- <a class="dropdown-item" href="{{ route('reg_usuario') }}">Registrar Usuario</a> --}}
                                    @endif
                                    @if (Route::is('home'))
                                        {{-- <a class="dropdown-item" href="{{ route('reg_usuario') }}">Registrar Usuario</a> --}}
                                        <a class="dropdown-item" href="{{ route('update_usuario') }}">Modificar Permisos</a>
                                    @endif
                                    <div class="dropdown-divider"></div>
                                @elseif (Auth::user()->rol == 2)
                                        @if (Route::is('update_usuario'))
                                            <a class="dropdown-item" href="{{ route('home') }}">Ver Mapa</a>
                                        @endif
                                        @if (Route::is('home'))
                                            <a class="dropdown-item" href="{{ route('update_usuario') }}">Modificar Accesos</a>
                                        @endif
                                        <div class="dropdown-divider"></div>
                                @endif
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); closeSession();">
                                    Cerrar Sesión
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div id="loading-page" class="position-absolute">
            <div class="spinner-border text-secondary" role="status"></div>
        </div>
        <main class="position-relative">
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('build/assets/app-BkDPDVeP.js') }}"></script>
    <script>
        function closeSession(){
            if (typeof(Storage) !== 'undefined') {
                sessionStorage.clear();
            }
            document.getElementById('logout-form').submit();
        }
    </script>
    @stack('js')
</body>

</html>
