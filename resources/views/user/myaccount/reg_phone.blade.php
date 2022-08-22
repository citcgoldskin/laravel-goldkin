@extends('user.layouts.app')
@section('content')
    <!--header_str-->
    <header id="header_under_ttl">
        <div class="header_area">
            <h1>電話番号の確認</h1>
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

            {{ Form::open(["route"=>"user.register.phone", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

                <div class="inner_box form_txt">
                    <p>
                        電話番号の認証をしてください。<br>
                        本人確認や不正利用防止のために利用します。<br>
                        他のユーザーに公開されることはありません。
                    </p>
                </div>

                <div class="inner_box">
                    <ul class="form_area">
                        <li>
                            <h3>携帯電話の番号</h3>
                            <div class="form_wrap shadow-glay">
                                <input type="text" value="" placeholder="例）08012345678" name="phone">
                                @error('phone')
                                <p class="error_text"><strong>{{ $message }}</strong></p>
                                @enderror
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="form_txt">
                    <p>
                        本人確認のため携帯電話のSMSを利用して認証を行います。<br>
                        <small>※通信料はお客様のご負担となります。</small>
                    </p>
                </div>
                <div class="bottom-posi">
                    <div class="button-area mt30 w100">
                        <div class="btn_base btn_orange shadow ">
                            <button type="submit" class="btn-send">SMSに認証コードを送信</button>
                        </div>
                    </div>
                </div>
            {{ Form::close() }}



        </section>


    </div><!-- /contents-->
@endsection
