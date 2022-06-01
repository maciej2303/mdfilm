<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MDfilm - {{__('project_managment_system') }}</title>

    <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('css/font-awasome.css')}}" rel="stylesheet">
    <link href="{{asset('css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/select2/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/select2/select2-bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    @stack('calendar')
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/mdfilm.css')}}" rel="stylesheet">
    <link href="{{asset('css/custom-styles.css')}}" rel="stylesheet">
    <link rel="shortcut" href="{{ asset('favicon.ico') }}">
    @stack('css')
    @toastr_css
    <!-- DatePicker -->
	<link href="{{asset('css/plugins/datapicker/datepicker3.css')}}" rel="stylesheet">

</head>
<body>

<!-- Wrapper-->
    <div id="wrapper">

        <!-- Navigation -->
        @include('layouts.menu')

        <!-- Page wraper -->
        <div id="page-wrapper" class="gray-bg">

            <div id="app">
                <!-- NAVBAR -->
                @include('layouts.navbar')


                <!-- NAVBAR -->
                <!-- Main view  -->
                @yield('content')
            </div>


            <!-- MODAL CZAS PRACY -->
            @include('dashboard.components.add-work-times-modal')
            <!-- /MODAL CZAS PRACY -->

        </div>
        <!-- End page wrapper-->

    </div>
    <!-- End wrapper-->
<script>
   window._lang = "{!! \App::getLocale() !!}";
   window._translations = {!! App\Services\Utils\TranslationService::getJson() !!};
   
</script>
<script src="{!! asset('js/app.js') !!}" type="text/javascript"></script>
<script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
<script src="{{asset('js/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>


<!-- Sweet alert -->
<script src="{{asset('js/plugins/sweetalert/sweetalert.min.js')}}"></script>

<!-- Custom and plugin javascript -->
<script src="{{asset('js/inspinia.js')}}"></script>
<script src="{{asset('js/plugins/pace/pace.min.js')}}"></script>

<!-- Data picker -->
<script src="{{asset('js/plugins/datapicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('js/plugins/datapicker/bootstrap-datepicker.pl.js')}}" defer></script>
<script src="{{asset('js/add-work-times-modal.js')}}" defer></script>

<script src="{{asset('js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>

@toastr_js
@toastr_render
@stack('js')
@stack('errors')
@section('scripts')
@show


</body>
</html>
