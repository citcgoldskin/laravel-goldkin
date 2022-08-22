@extends('user.layouts.app')
@section('title', $title)
@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->

    <div id="contents">

        {{ Form::open(["route"=>"user.login", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

            <div class="tabs form_page">
                <input id="tab-01" type="radio" name="tab_item" @if ( old('tab_item') != 1 ) checked @endif value="0">
                <label class="tab_item" for="tab-01">メールアドレス</label>
                <input id="tab-02" type="radio" name="tab_item" @if ( old('tab_item') == 1 ) checked @endif value="1">
                <label class="tab_item" for="tab-02">電話番号</label>


                <!-- ********************************************************* -->

                <div class="tab_content" id="tab-01_content_m">

                    <section>
                        <ul class="form_area">
                            <li>
                                <h3 id="email">メールアドレス</h3>
                                <h3 id="phone">電話番号</h3>
                                <div class="form_wrap shadow-glay">
                                    <input type="text" placeholder="" name="email" value="{{ old('email') }}" id="input_email">
                                    <input type="text" placeholder="" name="user_phone" value="{{ old('user_phone') }}" id="input_phone">
                                    @error('email')
                                    <p class="error_text"><strong>{{ $message }}</strong></p>
                                    @enderror
                                    @error('user_phone')
                                    <p class="error_text"><strong>{{ $message }}</strong></p>
                                    @enderror
                                </div>
                            </li>

                            <li>
                                <h3>パスワード</h3>
                                <p class="pw_txt">※英字と数字の両方を含めて設定してください。</p>
                                <div class="form_wrap shadow-glay">
                                    <input type="password" value="" placeholder="" name="password" >
                                    @error('password')
                                    <p class="error_text"><strong>{{ $message }}</strong></p>
                                    @enderror
                                </div>
                            </li>
                        </ul>

                        <div class="button-area mt30 w100">
                            <div class="btn_base btn_orange shadow ">
                                <button type="submit" class="btn-send">ログインする</button>
                            </div>
                        </div>

                        <div class="sub_link">
                            <p>
                                <a href="{{ route('password.change') }}">パスワードをお忘れですか？</a>
                            </p>
                        </div>

                    </section>

                </div>

            </div><!-- /tabs -->

        {{ Form::close() }}

    </div><!-- /contents -->

<!-- select email or phone -->
<script>
    $('#tab-01_content_m').show();

    var old_selected = '{{ old('tab_item') }}';

    if ( old_selected == 1 ) {
        $('#email').hide();
        $('#phone').show();
        $('#input_email').hide();
        $('#input_phone').show();
    } else {
        $('#email').show();
        $('#phone').hide();
        $('#input_email').show();
        $('#input_phone').hide();
    }

    $('#tab-01').click(function () {
        $('#email').show();
        $('#phone').hide();
        $('#input_email').show();
        $('#input_phone').hide();
        $('.error_text').hide();
    })

    $('#tab-02').click(function () {
        $('#email').hide();
        $('#phone').show();
        $('#input_email').hide();
        $('#input_phone').show();
        $('.error_text').hide();
    })
</script>

@endsection
