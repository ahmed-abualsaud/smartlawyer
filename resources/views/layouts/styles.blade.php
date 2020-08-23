<!-- Bootstrap 4.0-->
<link rel="stylesheet" href="{{asset('assets/vendor_components/bootstrap/dist/css/bootstrap.css')}}">
<!-- Bootstrap 4.0-->
<link rel="stylesheet" href="{{asset('assets/vendor_components/bootstrap/dist/css/bootstrap-extend.css')}}">
<!-- theme style -->
<link rel="stylesheet" href="{{asset('css/master_style.css')}}">
<!-- Alfa_admin skins. choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="{{asset('css/skins/_all-skins.css')}}">
<!-- date picker -->
<link rel="stylesheet" href="{{asset('assets/vendor_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')}}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('assets/vendor_components/bootstrap-daterangepicker/daterangepicker.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.css')}}">
<!-- Morris charts -->
<link rel="stylesheet" href="{{asset('assets/vendor_components/morris.js/morris.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor_components/select2/dist/css/select2.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('css/master_style.css')}}">
<link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
<!-- Datatable style -->
<link rel="stylesheet" href="{{asset('css/dataTables.bootstrap.min.css')}}">
{{--sweet alert style--}}
<link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="{{asset('css/custom.css')}}">
@toastr_css
@yield('styles')
<style>
    @if(count($notifications) == 0)
        .main-header .messages-menu .dropdown-toggle i::after, .main-header .notifications-menu .dropdown-toggle i::after, .main-header .tasks-menu .dropdown-toggle i::after {
            background-color: transparent;
        }
    @endif
</style>
