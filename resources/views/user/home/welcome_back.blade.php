@extends('user.layouts.app')

@section('content')
<!-- ************************************************************************
本文
************************************************************************* -->
    <!--startup-->
    <div id="startup" class="top_page">
        <div class="top_wrap">
            <div class="logo login_page"><img src="{{ asset('assets/user/img/logo.svg') }}" alt="センパイロゴ"></div>

            <div class="top_catchcopy">
                センパイで<br>
                新しい習慣を<br>
                はじめましょう！
            </div>
        </div>

        <div class="button-area type_under w80">
            <div class="btn_base btn_orange"><a href="{{ route('user.register.email.form') }}">新規登録する</a></div>
            <div class="btn_base btn_white"><a href="{{ route('user.login.form') }}">ログインする</a></div>
            <div class="return_area"><a href="#" onclick="javascript:window.history.back(-1);return false;">戻る</a></div>
        </div>

    </div>
        <!--startup end-->
@endsection
