@extends('user.layouts.app')

@section('title', 'マイページ')

@section('content')
@include('user.layouts.gnavi_under')

<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents"  class="short">

    @include('user.layouts.flash-message')

    {{ Form::open(["route"=>"user.myaccount.index", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

        <div class="login_area">
            <ul>
                <li><img src="{{ asset('assets/user/img/logo2.svg') }}" alt="ロゴ"></li>
                <li>
                    <p class="form_txt">
                        センパイで<br>
                        新しい習慣をはじめましょう！
                    </p>
                </li>
                <li>
                    <div class="button-area">
                        <div class="btn_base btn_orange">
                            <a href="{{ route('user.register.email') }}">無料で新規登録</a>
                        </div>
                        <div class="btn_base btn_orange-line">
                            <a href="{{ route('user.login.form') }}">ログイン</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <section id="mypage" class="pt30">

            {{--<div class="inner_box">--}}
                {{--<h3 class="icon_setting">設定</h3>--}}

                {{--<ul class="list_area mypage_list icon_setting">--}}

                    {{--<li class="icon_form type_arrow_right">--}}
                        {{--<a href="{{ route('user.myaccount.set_account') }}">アカウント</a>--}}
                    {{--</li>--}}

                    {{--<li class="icon_form type_arrow_right">--}}
                        {{--<a href="{{ route('user.myaccount.push_and_mail') }}">プッシュ通知・メール</a>--}}
                    {{--</li>--}}

                {{--</ul>--}}
            {{--</div>--}}

            <div class="inner_box">
                <h3 class="icon_help">ヘルプ</h3>

                <ul class="list_area">

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.guide', ['type' => config('const.menu_type.senpai')]) }}">ご利用ガイド（センパイ向け）</a>
                    </li>

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.guide', ['type' => config('const.menu_type.kouhai')]) }}">ご利用ガイド（コウハイ向け）</a>
                    </li>

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.ques_cate') }}">よくある質問</a>
                    </li>

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.others') }}">その他</a>
                    </li>

                </ul>
            </div>

        </section>

    {{ Form::close() }}

</div><!-- /contents-->

<footer>
    @include('user.layouts.fnavi')
</footer>
@endsection
