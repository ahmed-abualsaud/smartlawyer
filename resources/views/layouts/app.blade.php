<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{__('dashboard.smart_lawyer')}}</title>

    <!-- Scripts -->
    <script src="{{asset('assets/vendor_components/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('js/parsley.min.js')}}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        html{
            height: 100vh!important;
        }
        body {
            background-image: url("{{asset('/images/gavel.jpg')}}")!important;
            background-size: cover!important;
            height: 90%;
            overflow: scroll;
        }
        .ml-auto{
            margin-left: 0 !important;
        }
        nav a{
            text-decoration: none;
            background-color: transparent;
            color: gold!important;
            padding: 2px 6px 2px 6px;
            margin-left: 2vw;
            border: 3px solid gold;
            font-family: 'Cortoba', Sans-Serif;
            border-radius: 0.5rem;
            transition: transform 0.3s ease-in-out;
            box-shadow: 7px 7px 10px black;
        }
        nav a:hover{
            text-decoration: none;
            background-color: gold;
            color: black!important;
            transform: scale(0.96);
            border: 1px solid black;
            box-shadow: 7px 7px 10px gold;
        }

        .card-body form label, .card-header h4{
            font-family: 'Cortoba',Sans-Serif;
            font-size: 25px;
            -webkit-text-fill-color: gold;
            color: gold;
            text-shadow: 5px 5px 3px black;
            -webkit-text-stroke: 1px black;
            transition: transform 0.4s ease-in-out;
        }
        .card-body form label:hover{
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div id="app" dir="rtl">
        <nav class="navbar navbar-expand-md navbar-light">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{__('dashboard.smart_lawyer')}}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon">jakbvdjkvb</span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{__('dashboard.login')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{__('dashboard.register')}}</a>
                            </li>
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
</body>
</html>
