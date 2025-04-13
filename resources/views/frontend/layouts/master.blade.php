<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Favicon-->
    <link rel="shortcut icon" href="{{ asset('frontend/img/favicon.ico') }}">



    {{--  Head Styles  --}}

    @include('frontend.layouts.head-styles')


  </head>
  <body>
    <div class="page-holder">

      <!-- navbar-->
    @include('frontend.layouts.navbar')


      <!--  Modal -->

    @include('frontend.layouts.model')




      <!-- HERO SECTION-->

    @yield('content')


    {{--    Footer      --}}
    @include('frontend.layouts.footer')


      <!-- JavaScript files-->
    @include('frontend.layouts.footer-scripts')


    </div>
  </body>
</html>
