<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"> --}}
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('css/data-table.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select-2.min.css') }}">



    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config(
                        'app.name',
                        '
                                                                                                    ',
                    ) }}
                </a>
                @auth
                    @if (auth()->user()->roles->contains('name', 'admin'))
                        <a class="navbar-brand" href="{{ url('/users') }}">
                            Users
                        </a>
                    @endif
                @endauth

                @auth
                    @if (auth()->user()->roles->contains('name', 'user'))
                        <a class="navbar-brand" href="{{ url('/categories') }}">
                            Categories
                        </a>
                    @endif
                @endauth
                @auth
                    @if (auth()->user()->roles->contains('name', 'user'))
                        <a class="navbar-brand" href="{{ url('/products') }}">
                            Products
                        </a>
                    @endif
                @endauth
                @auth
                    @if (auth()->user()->roles->contains('name', 'user'))
                        <a class="navbar-brand" href="{{ url('/customers') }}">
                            Customers
                        </a>
                    @endif
                @endauth
                 @auth
                    @if (auth()->user()->roles->contains('name', 'user'))
                        <a class="navbar-brand" href="{{ url('/sales') }}">
                            Sales
                        </a>
                    @endif
                @endauth
                 @auth
                    @if (auth()->user()->roles->contains('name', 'user'))
                        <a class="navbar-brand" href="{{ url('/suppliers') }}">
                            Suppliers
                        </a>
                    @endif
                @endauth

                 @auth
                    @if (auth()->user()->roles->contains('name', 'user'))
                        <a class="navbar-brand" href="{{ url('/purchases') }}">
                            Purchases
                        </a>
                    @endif
                @endauth
                 @auth
                    @if (auth()->user()->roles->contains('name', 'admin'))
                        <a class="navbar-brand" href="{{ url('/purchaseledger') }}">
                            Purchase Ledger
                        </a>
                    @endif
                @endauth
                @auth
                    @if (auth()->user()->roles->contains('name', 'admin'))
                        <a class="navbar-brand" href="{{ url('/saleledger') }}">
                            Sale Ledger
                        </a>
                    @endif
                @endauth
                @auth
                    @if (auth()->user()->roles->contains('name', 'admin'))
                        <a class="navbar-brand" href="{{ url('/reports') }}">
                            Report
                        </a>
                    @endif
                @endauth
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

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
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
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




    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="{{ asset('js/data-table.min.js') }}"></script>
    <script src="{{ asset('js/select-2.min.js') }}"></script>


    @stack('scripts')
     <script>
        $(document).ready(function() {
            $('.sel-2').select2();
        });
    </script>
</body>

</html>
