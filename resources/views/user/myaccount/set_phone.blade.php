@extends('user.layouts.app')
@section('title', $title)
@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->


<div id="contents">
    {{ Form::open(["route"=>"user.myaccount.set_phone", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
    <section>

        <div class="inner_box">
            <p class="form_txt">
                電話番号の認証をしてください。<br>
                本人確認や不正利用防止のために利用します。<br>
                他のユーザーに公開されることはありません。
            </p>
        </div>

        <ul class="form_area">
            <li>
                <h3>現在の携帯電話の番号</h3>
                <p class="form_txt">{{ $phone }}</p>
            </li>

            <li>
                <h3 class="must">新しい携帯電話の番号</h3>
                <div class="form_wrap">
                    @error('phone')
                    <p class="error_text"><strong>{{ $message }}</strong></p>
                    @enderror
                    <input type="text" value="" placeholder="7文字以上の半角英数字" name="phone">
                </div>
            </li>
        </ul>

        <div class="form_txt">
            <p>
                本人確認のため携帯電話のSMSを利用して認証を行います。<br>
                <small>※通信料はお客様のご負担となります。</small>
            </p>
        </div>


    </section>


    <section id="button-area">
        <div class="button-area">
            <div class="btn_base btn_orange shadow">
                <button type="button" class="btn-send">SMSに認証コードを送信</button>
            </div>
        </div>
    </section>

    {{ Form::close() }}

</div><!-- /contents -->
<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection
