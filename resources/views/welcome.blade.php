<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{asset('images/fav.jpeg')}}">
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@800&display=swap" rel="stylesheet">

    <title>{{__('dashboard.smart_lawyer')}}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- Styles -->
    <style>

        html, body {
			background-image: url("{{ asset('/images/gavel.jpg') }}");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100%;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            margin: 0;
        }

        .full-height {
            height: 100%;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 1vw;
            top: 2vw;
        }

        .content{
            text-align: center;
            position: absolute;
            top: 39%;
            left: 3.5%;
        }

        .content span{
            font-family: 'Cortoba', Sans-Serif;
            text-transform: uppercase;
            display: block;
        }
        .top-right a{
            font-size: 18px;
            text-decoration: none;
            background-color: transparent;
            color: gold!important;
            padding: 2px 6px 2px 6px!important;
            margin-left: 2vw;
            border: 3px solid gold;
            font-family: 'Cortoba', Sans-Serif;
            border-radius: 0.5rem;
            transition: transform 0.5s ease-in-out!important;
        }
        .top-right a:hover{
            background-color: gold;
            color: black!important;
            border: 1px solid black;
            box-shadow: 7px 7px 10px gold;
            transform: scale(0.96)!important;
        }
        .title1 {
            font-size: 84px;
            color: #fff;
            letter-spacing: 5px;
            margin-bottom: 20px;
            position: relative;
            animation: text1 3s 1;
        }

        .title2 {
            font-size: 74px;
            font-weight: 1000;
            color: gold;
            letter-spacing: -2px;
            animation: text2 3.1s 1;
        }

        @keyframes text1 {
            0%{
                margin-bottom: -110px;
            }
            30%{
                letter-spacing: 20px;
                margin-bottom: -110px;
            }
            85%{
                letter-spacing: 5px;
                margin-bottom: -110px;
            }
        }
        @keyframes text2 {
            0%{
                color: transparent;
            }
            30%{
                color: transparent;
            }
            85%{
                color: transparent;
            }
        }

    </style>

</head>
<body>
<div class="flex-center position-ref full-height">
    <div style="position: relative;bottom: 40%;left: 28%;">
        @include('flash::message')
    </div>

    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/dashboard') }}">{{__('dashboard.dashboard')}}</a>
            @else
                <a href="{{ route('login') }}">{{__('dashboard.login')}}</a>

            @if (Route::has('register'))
                    <a class="anc" href="{{ route('register') }}">{{__('dashboard.register')}}</a>

            @endif
            @endauth
        </div>
    @endif

    <div class="content">
        <span class="title1">{{__('dashboard.welcometo')}}</span>
        <span class="title2">{{__('dashboard.smartlawyer')}}</span>

    </div>

</div>
<!-- If using flash()->important() or flash()->overlay(), you'll need to pull in the JS for Twitter Bootstrap. -->
<script src="//code.jquery.com/jquery.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script>
    $('#flash-overlay-modal').modal();
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);

</script>
</body>
</html>
