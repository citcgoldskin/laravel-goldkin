<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>センパイ | @yield('title')</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="title" content="{{ config('app.name', 'センパイ') }}">
        <meta name="keywords" content="{{ config('app.name', 'センパイ') }}" >
        <meta name="description" content="{{ config('app.name', 'センパイ') }}">
        <!--OGP-->
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ config('app.name', 'センパイ') }}">
        <meta property="og:description" content="{{ config('app.name', 'センパイ') }}" >
        <!--<meta property="og:image" content="">-->
        <meta property="og:url" content="">
        <meta property="og:site_name" content="センパイ">
        <meta content="summary" name="twitter:card">
        <meta content="@twitter_acount" name="twitter:site">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!--OGP end-->
        @include('user.layouts.header')
    </head>

    <body>
        <div id="wrapper">
            @yield('content')
        </div><!-- /wrapper -->

        <footer>
            @include('user.layouts.footer')
        </footer>

        @yield('page_js')

    </body>

</html>
