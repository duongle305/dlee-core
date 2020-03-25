<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
          type="text/css">
    <link href="{{ asset('css/icomoon.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/colors.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
@stack('plugin_css')
<!-- Core JS files -->
    <script src="{{ asset('js/main/jquery.min.js') }}"></script>
    <script src="{{ asset('js/main/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/plugins/ui/perfect_scrollbar.min.js') }}"></script>
@stack('plugin_js')
<!-- /core JS files -->

    <!-- Theme JS files -->

    <script src="{{ asset('js/app.js') }}"></script>
    <!-- /theme JS files -->

</head>

<body class="navbar-top">

@yield('navbar')
<!-- Page content -->
<div class="page-content">
@yield('sidebar')
<!-- Main content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    <!-- /main content -->

</div>
<!-- /page content -->

@stack('js')

</body>
</html>
