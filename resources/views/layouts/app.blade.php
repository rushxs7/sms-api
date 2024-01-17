<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="icon" href="{{ asset('cropped-Datasur-Favicon-32x32.png') }}" sizes="32x32">
  <link rel="icon" href="{{ asset('cropped-Datasur-Favicon-192x192.png') }}" sizes="192x192">
  <link rel="apple-touch-icon" href="{{ asset('cropped-Datasur-Favicon-180x180.png') }}">
  <meta name="msapplication-TileImage" content="{{ asset('cropped-Datasur-Favicon-270x270.png') }}">

  @livewireStyles

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
  <div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
          {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side Of Navbar -->
          @auth
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link @if(Route::is('dashboard')) active @endif" aria-current="page" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link @if(Route::is('smsservice*')) active @endif" aria-current="page" href="{{ route('smsservice.index') }}">SMS Center</a>
            </li>
            {{-- <li class="nav-item">
              <a class="nav-link @if(Route::is('mycredits')) active @endif" aria-current="page" href="{{ route('mycredits') }}">My Credits</a>
            </li> --}}
          </ul>
          @endauth

          <!-- Right Side Of Navbar -->
          <ul class="navbar-nav ms-auto">
            <!-- Authentication Links -->
            @guest
            @if (Route::has('login'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            @endif

            @if (Route::has('register'))
            <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
            @endif
            @else
            {{-- <li class="nav-item me-2">
              <a href="#" style="text-decoration: none;">
                <div class="text-dark">Balance:</div>
                <strong class="text-danger">$50,000.00</strong>
              </a>
            </li> --}}
            <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <i class="fa-solid fa-user"></i>
              </a>

              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('myaccount') }}">
                  {{ __('My Account') }}
                </a>
                @role('superadmin')
                <a class="dropdown-item" href="{{ route('users.index') }}">
                  {{ __('User Management') }}
                </a>
                <a class="dropdown-item" href="{{ route('organizations.index') }}">
                  {{ __('Organization Management') }}
                </a>
                @endrole
                <hr class="dropdown-divider">
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
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

    <main class="py-4">
      @yield('content')
    </main>
  </div>
  @livewireScripts

  @yield('scripts')
</body>
</html>
