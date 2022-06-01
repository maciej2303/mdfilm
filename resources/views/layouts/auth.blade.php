<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

     <title>MDfilm - {{ __('project_managment_system') }}</title>
    <link rel="shortcut" href="{{ asset('favicon.ico') }}">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/font-awasome.css')}}" rel="stylesheet">
    <link href="{{asset('css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/mdfilm.css')}}" rel="stylesheet">
    @toastr_css

</head>

<body class="gray-bg login-bg">

    <div id="wrapper">
        @yield('content')
    </div>

    <!-- Mainly scripts -->
    <script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    @toastr_js
    @toastr_render
</body>

</html>
