<head>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <title>センパイ管理画面 | @yield('title')</title>
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

    <!-- Styles -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" media="all" href="{{ asset('assets/admin/css/reset.css') }}">

    <!-- 全ページ共通 -->
    <link rel="stylesheet" media="all" href="{{ asset('assets/admin/css/app.css') }}">

    <!-- 全ページ共通 -->
    <link rel="stylesheet" media="all" href="{{ asset('assets/admin/css/common.css') }}">

    <!-- 全ページ共通 -->
    <link rel="stylesheet" media="all" href="{{ asset('assets/admin/css/custom.css') }}">

    <!-- 各ページ共通 -->
    <link rel="stylesheet" media="all" href="{{ asset('assets/admin/css/design.css') }}">

    <!-- ページ個別（1ページのみ） -->
    <link rel="stylesheet" media="all" href="{{ asset('assets/admin/css/page.css') }}">

    <!-- タブレット・スマホ -->
    <link rel="stylesheet" media="all" href="{{ asset('assets/admin/css/responsive.css') }}">

    <!-- スライダー -->
    <link rel="stylesheet" media="all" href="{{ asset('assets/admin/css/swiper_customize.css') }}">

    <!-- モーダル -->
    <link rel="stylesheet" media="all" href="{{ asset('assets/admin/css/modal.css') }}">

    <!-- トークルーム -->
    <link rel="stylesheet" media="all" href="{{ asset('assets/admin/css/talkroom.css') }}">

    <!-- クーポン（アルファ画面） -->
    <link rel="stylesheet" media="all" href="{{ asset('assets/admin/css/coupon.css') }}">

    <!-- よくある質問（ベータ画面） -->
    <link rel="stylesheet" media="all" href="{{ asset('assets/admin/css/faq.css') }}">

    {{--admin--}}
    <link rel="stylesheet" media="all" href="{{ asset('assets/admin/css/common.css') }}">

    {{--ユーザー customize css--}}
    <link rel="stylesheet" media="all" href="{{ asset('assets/admin/css/user_customize.css') }}?{{ \Carbon\Carbon::now()->format('YmdHis') }}">

    <!-- Customize Styles -->
    @yield('page_css')

    <!-- Scripts -->
    <script src="{{ asset('assets/admin/js/jquery/jquery-1.11.0.min.js') }}"></script>

    <script src="{{ asset('assets/admin/js/jquery/jquery-3.1.0.js') }}"></script>
    <script src="{{ asset('assets/admin/js/swiper-4.3.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/jquery/jquery-ui-1.12.0.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.datetimepicker.full.min.js') }}"></script>

    <script src="{{ asset('assets/admin/js/jquery/jquery-1.5.1.js') }}"></script>

    <!-- スライドショー -->
    <script src="{{ asset('assets/admin/js/jquery/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/swiper-4.3.3.min.js') }}"></script>
    <link href="{{ asset('assets/admin/css/swiper.min.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('assets/admin/js/slider.js') }}"></script>

    @yield('page_js')

    <script src="{{ asset('assets/admin/js/plugin.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom.js') }}"></script>


</head>
