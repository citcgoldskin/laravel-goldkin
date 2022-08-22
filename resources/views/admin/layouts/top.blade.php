<!DOCTYPE html>
<html lang="ja">

    {{--head start--}}
    @include('society.layouts.partials._head')
    <link rel="stylesheet" href="{{ asset('assets/society/css/common.css') }}?{{ Carbon::now()->format('YmdHis') }}">
    {{-- head end--}}

    <body id="top">

        {{--header start--}}
        @include('society.layouts.partials.header')
        {{--header end--}}

        @yield('content')

        {{--  footer start--}}
        @include('society.layouts.partials._footer_top')
        {{--  footer end--}}

    </body>

</html>
