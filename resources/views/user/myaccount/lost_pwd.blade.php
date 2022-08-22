@extends('user.layouts.app')
@section('content')

    <!--header_str-->
    <header id="header_under_ttl">
        <div class="header_area">
            <h1>パスワードをお忘れの方</h1>
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

            {{ Form::open(["route"=>"user.login.lost_pwd", "method"=>"post", "name"=>"form1", "id"=>"form1" ]) }}

                <div class="inner_box form_txt">
                    <p class="f12">
                        登録時に利用したメールアドレスを入力してください。<br>
                        パスワードを再設定するためのメールをお送りします。
                    </p>
                </div>

                <ul class="form_area">
                    <li>
                        <h3>メールアドレスを入力してください</h3>
                        <div class="form_wrap shadow-glay">
                            <input type="text" value="{{ old('email') }}" placeholder="" name="email">
                            @error('email')
                            <p class="error_text"><strong>{{ $message }}</strong></p>
                            @enderror
                        </div>
                    </li>
                </ul>

            {{ Form::close() }}


            <div class="button-area mt30 w100">
                <div class="btn_base btn_orange shadow ">
                    <button type="submit" class="btn-send">メールを送信する</button>
                </div>
            </div>

        </section>

    </div><!-- /contents-->
@endsection
