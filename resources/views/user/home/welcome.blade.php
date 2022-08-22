@extends('user.layouts.app')

@section('title', 'センパイ')

@section('content')
    <div id="startup" class="top_page login_page">
        <div class="top_wrap">
            <div class="logo"><img src="{{ asset('assets/user/img/logo.svg') }}" alt="センパイロゴ"></div>

            <div class="top_catchcopy">
                センパイで<br>
                新しい習慣を<br>
                はじめましょう！
            </div>
        </div>

        <div class="button-area type_under w80">
            <div class="btn_base btn_orange"><a href="{{ route('user.register.email.form') }}">新規登録する</a></div>
            <div class="btn_base btn_white"><a href="{{ route('user.login.form') }}">ログインする</a></div>
        </div>
    </div>
@endsection
