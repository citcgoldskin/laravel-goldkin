@extends('user.layouts.app')

@section('content')

<!--header_str-->
<header id="header_under_ttl">
    <div class="header_area">
        <h1>電話番号認証</h1>
        <div class="h-icon">
            <p><button type="button" onclick="history.back()"><img src="{{ asset('assets/user/img/arrow_left2.svg') }}" alt="戻る"></button></p>
        </div>
    </div>
</header>
<!--header_end-->


<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents">

    <section>
        @if ( !$from )
            {{ Form::open(["route"=>"user.register.verify_phone", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
        @else
            {{ Form::open(["route"=>"user.myaccount.verify_phone", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
        @endif

            <div class="inner_box">
                <h3>認証番号</h3>
                <div class="form_txt">
                    <p>
                        SMSで届いた認証コードを入力してください。
                    </p>
                </div>
            </div>

            <div class="inner_box">
                <ul class="form_area certification_number">
                    <li>
                        <div class="form_wrap">
                            <input type="text" value="" placeholder="" maxlength="1" name="code_1">
                            <input type="text" value="" placeholder="" maxlength="1" name="code_2">
                            <input type="text" value="" placeholder="" maxlength="1" name="code_3">
                            <input type="text" value="" placeholder="" maxlength="1" name="code_4">
                        </div>
                    </li>
                </ul>
            </div>

            <div class="form_txt">
                <p class="txt_center">
                    30秒経っても認証番号が届かない方へ
                </p>
            </div>

            <div class="sub_link">
                <p>
                    <a href="@if ( !$from ) {{ route('user.register.phone.form') }} @else {{ route('user.myaccount.show_phone') }} @endif">電話番号を再度入力する</a>
                </p>
            </div>

            <div class="bottom-posi">
                <div class="agree_btn_area btn_base shadow-glay mt20">
                    <div class="btn_base btn_orange shadow">
                        <button type="submit" class="btn-send">認証する</button>
                    </div>
                </div>
            </div>
        {{ Form::close() }}


    </section>


</div><!-- /contents-->
@endsection
