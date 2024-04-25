<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fontawesome 6 cdn -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css'
        integrity='sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=='
        crossorigin='anonymous' referrerpolicy='no-referrer' />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Usando Vite -->
    @vite(['resources/js/app.js'])
</head>

<body>
    <div id="app">

        <header class="navbar navbar-dark sticky-top shadow blue-bg">
            <div class="container">
                
                <a class="navbar-brand d-flex align-items-center px-2 m-0" href="{{ url('/') }}">
                    
                    <img src="{{ asset('images/logo_test.png') }}" alt="logo_test" class="logo">
                    <span class="fw-bold text-light">BoolBnB</span>
                    
                </a>                

                <button class="navbar-toggler d-md-none collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

            <div class="nav-item dropdown appear-768">
                <a id="navbarDropdown" class="nav-link dropdown-toggle text-light" href="#" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Bentornato/a {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" data-bs-toggle="collapse" data-bs-target="#user-info-popup" aria-expanded="false" aria-controls="user-info-popup">{{ __('Account') }}</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Esci') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        </header>

        <div class="container-fluid vh-100">
            <div class="row h-100">
                <!-- Definire solo parte del menu di navigazione inizialmente per poi
        aggiungere i link necessari giorno per giorno
        -->
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block gray-bg navbar-dark sidebar collapse">
                    <div class="position-sticky pt-3">
                        <ul class="nav flex-column">

                            <li class="nav-item mb-2 disappear-768">
                                <a class="nav-link red-hover border rounded-pill" data-bs-toggle="collapse" data-bs-target="#user-info-popup" aria-expanded="false" aria-controls="user-info-popup">
                                <i class="fa-solid fa-user"></i>
                                Account di {{ Auth::user()->name }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                        </li>

                            <li class="nav-item mb-2 disappear-768">
                                    <a class="nav-link red-hover border rounded-pill" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                        {{ __('Esci') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                            </li>

                            <li class="nav-item mb-2">
                                <a class="nav-link {{ Route::currentRouteName() == 'admin.dashboard' ? 'red-bg border rounded-pill text-white' : 'red-hover border rounded-pill' }}"
                                    href="{{ route('admin.dashboard') }}">
                                    <i class="fa-solid fa-list pe-2"></i> DashBoard
                                </a>
                            </li>

                            <li class="nav-item mb-2">
                                <a class="nav-link {{ Route::currentRouteName() == 'admin.apartments.index' ? 'red-bg border rounded-pill text-white' : 'red-hover border rounded-pill' }}"
                                    href="{{ route('admin.apartments.index') }}"> 
                                    {{-- mettere admin index qua sopra --}}
                                    <i class="fa-solid fa-house pe-2"></i> I tuoi Appartamenti
                                </a>
                            </li>
                            
                            <li class="nav-item mb-2">
                                <a class="nav-link {{ Route::currentRouteName() == 'admin.sponsor.create' ? 'red-bg border rounded-pill text-white' : 'red-hover border rounded-pill' }}"
                                    href="{{ route('admin.sponsor.create') }}"> 
                                    {{-- mettere admin index qua sopra --}}
                                    <i class="fa-solid fa-money-check-dollar pe-2"></i> Sponsorizzazioni
                                </a>
                            </li>
                        </ul>


                    </div>
                </nav>

                {{-- <div id="user-info-popup">
                    
                </div> --}}

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    @yield('content')
                </main>

                <div class="collapse" id="user-info-popup">
                    <div id="popup-block">
                        <a class="dropdown-item" data-bs-toggle="collapse" data-bs-target="#user-info-popup" aria-expanded="false" aria-controls="user-info-popup"><i class="fa-solid fa-circle-xmark"></i></a>
        
                    <div>
                        <h5>Nome:</h5>
                        <span>{{ Auth::user()->name }}</span>
                    </div>
                    <div>
                        <h5>E-mail:</h5>
                        <span>{{ Auth::user()->email }}</span>
                    </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
