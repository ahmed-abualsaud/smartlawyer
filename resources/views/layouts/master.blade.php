<!DOCTYPE html>
<html lang="en" dir="rtl">
 <head>
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
     <meta name="description" content="">
     <meta name="author" content="">
     <link rel="icon" href="{{asset('images/logo.jpeg')}}">
     <link href="https://fonts.googleapis.com/css2?family=Changa:wght@800&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>@yield('title')</title>
	@include('layouts.styles',['notifications' => []])
</head>
<body class="hold-transition sidebar-mini"style="background-color: whitesmoke" >

    @include('layouts.header')
    @include('layouts.sidebar')
    <div class="wrapper" style="padding-top: 3vh;">
        @yield('content')
    </div>
    @include('layouts.footer')
    @include('layouts.scripts')
    @yield('modals')
    @toastr_render
</body>
</html>
