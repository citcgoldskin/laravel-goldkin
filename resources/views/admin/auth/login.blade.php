@extends('admin.layouts.auth')

@section('title', 'ログイン')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}

        <div class="tabs form_page">
            <label class="tab_item" style="width: 100%;">管理画面</label>

            <div class="tab_content" id="tab-01_content_m">

                <section>
                    <ul class="form_area">
                        <li>
                            <h3 id="email">ログインID</h3>
                            <div class="form_wrap shadow-glay">
                                <input type="text" placeholder="" name="login_id" value="{{ old('login_id') }}" id="input_email">
                                @error('login_id')
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

                </section>

            </div>

        </div><!-- /tabs -->

        {{ Form::close() }}

    </div>
    <style>
        .tab_item {
            border-bottom: 2px solid #f1f1f1 !important;
            border-top: 2px solid #dad8d6 !important;
        }
        section {
            padding-top: 20px;
        }
    </style>
    <script>
        $('#tab-01_content_m').show();
    </script>
@endsection
