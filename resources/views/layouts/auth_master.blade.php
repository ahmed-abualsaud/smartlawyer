<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.styles')
    @yield('style')
</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <!-- js -->
    @include('layouts.scripts')
    <script type="text/javascript" src="{{asset('js/jquery-2.1.4.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/bootstrap.min.js')}}"></script>
    <script>
        // this for fixing placeholder label issue
        $($(document).ready(function () {
            jQuery("body").trigger("click")
        }))
    </script>
    @yield('scripts')
</body>

</html>
