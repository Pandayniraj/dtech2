<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
  <head>
    <meta charset="UTF-8">
    <title>{!! env('SHORT_NAME') !!}  | {{ $page_title ?? "Page Title" }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Set a meta reference to the CSRF token for use in AJAX request -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons 4.4.0 -->
    <link href="{{ asset("/bower_components/admin-lte/font-awesome/css/font-awesome.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.1 -->
    <link href="{{ asset("/bower_components/admin-lte/ionicons/css/ionicons.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css" />

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Application CSS-->
    <link href="{{ asset('css/all.css') }}" rel="stylesheet" type="text/css" /> 

    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css'>

        <link rel="stylesheet" href="{{asset('chat/css/style.css')}}">
        <style>
      .skin-blue .sidebar-menu>li.header{
        color: #fff !important;
        font-size: 15px !important;
        background: #1b4f72 !important;
      }
      .material-icons{
          display: inline-flex;
          vertical-align: top;
      }
      .material-icons.medium{
          font-size: 17px;
      }

      .skin-red .sidebar-menu>li.header{
        color: #fff !important;
        font-size: 15px !important;
        background: maroon !important;
      }
      .ui-menu-item:hover{
        background: #007fff !important;
        border: 1px solid #003eff !important;
        color: white !important;


      }


      </style>
    <!-- Head -->
    @include('partials._head')

      <!-- REQUIRED JS SCRIPTS -->

      <!-- jQuery 2.1.4 -->
      <script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
      <!-- Bootstrap 3.3.2 JS -->
      <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>
      <!-- AdminLTE App -->
      <script src="{{ asset ("/bower_components/admin-lte/dist/js/app.min.js") }}" type="text/javascript"></script>

      <!-- Optionally, you can add Slimscroll and FastClick plugins.
            Both of these plugins are recommended to enhance the
            user experience. Slimscroll is required when using the
            fixed layout. -->

      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->

      <!-- Application JS-->
      <script src="{{ asset('js/all.js') }}"></script>

      <!-- Optional header section  -->
      @yield('head_extra')

  </head>

  <!-- Body -->
  @if(isset($app_theme))
  @if($app_theme == 'green')
    @include('themes.green.partials._body')
  @elseif($app_theme == 'red')
    @include('themes.red.partials._body')
  @elseif($app_theme == 'yellow')
    @include('themes.yellow.partials._body')
  @elseif($app_theme == 'purple')
    @include('themes.purple.partials._body')
  @elseif($app_theme == 'black')
    @include('themes.black.partials._body')
  @else
    @include('partials._body')
  @endif
  @else
    @include('partials._body')
  @endif


</html>
