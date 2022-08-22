@extends('user.layouts.app')
@section('title', 'パスワード変更')
@section('content')
    @include('user.layouts.header_under', ['page_type'=>'password_reset'])

    <!-- ************************************************************************
本文
************************************************************************* -->


    <div id="contents">
        <form action="{{ route('password.update') }}" method="post" name="form1" id="form1">
            @csrf
            <section>
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="inner_box">
                    <h3 class="must">メールアドレス</h3>
                    <div class="form_wrap shadow-glay for-warning">
                        <input type="text" value="{{ $email }}" placeholder="" name="email" id="email" readonly>
                        <p class="warning"></p>
                    </div>
                </div>

                <ul class="form_area">
                    <li>
                        <h3 class="must">新しいパスワード</h3>
                        <p class="pw_txt">※英字と数字の両方を含めて設定してください。</p>
                        <div class="form_wrap shadow-glay">
                            <input type="password" value="" placeholder="7文字以上の半角英数字" name="password" id="password">
                        </div>
                        @error('password')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </li>

                    <li>
                        <h3 class="must">パスワード</h3>
                        <p class="pw_txt">※確認のため入力してください。</p>
                        <div class="form_wrap shadow-glay">
                            <input type="password" value="" placeholder="7文字以上の半角英数字" name="password_confirmation">
                        </div>
                    </li>
                </ul>

            </section>

            <section>
                <div class="inner_box">
                    <div class="button-area">
                        <div class="btn_base btn_orange shadow">
                            <button type="submit">変更する</button>
                            {{--<a class="ajax_submit">変更する</a>--}}
                        </div>
                    </div>
                </div>
            </section>

        </form>
    </div><!-- /contents -->




    <footer>

        @include('user.layouts.fnavi')

    </footer>

@endsection

@section('page_js')
@endsection
