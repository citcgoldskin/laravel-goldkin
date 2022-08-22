@extends('user.layouts.app')
@section('title', $title)
@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->


<div id="contents">
    {{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
        <section>

            <div class="inner_box">
                <h3>現在のメールアドレス</h3>
                <p class="form_txt">{{ $user_info['email'] }}</p>
            </div>

            <ul class="form_area">
                <li>
                    <h3 class="must">新しいメールアドレス</h3>
                    <div class="form_wrap shadow-glay for-warning">
                        <input type="text" value="{{ old('email') }}" placeholder="" name="email" id="email">
                        <p class="warning"></p>
                    </div>
                </li>

                <li>
                    <h3 class="must">パスワード</h3>
                    <p class="pw_txt">※英字と数字の両方を含めて設定してください。</p>
                    <div class="form_wrap shadow-glay  for-warning">
                        <input type="password" value="" placeholder="7文字以上の半角英数字" name="password" id="input_password">
                        <p class="warning"></p>
                    </div>
                    <div class="check-box">
                        　<div class="clex-box_03 pw_box">
                            　　<input type="checkbox" name="show_password" value="1" id="password">
                            　　<label for="password" id="show_password">
                                <p>パスワードを表示する</p>
                            </label>
                        </div>
                    </div>
                </li>
            </ul>

        </section>


        <section id="button-area">

            <div class="inner_box">
                <div class="form_txt">
                    <p>
                        メールアドレスを変更すると確認メールが送信されます。<br>
                        メール内のURLを押すと変更が完了します。
                    </p>
                </div>
            </div>

            <div class="button-area">
                <div class="btn_base btn_orange shadow">
                    <a class="ajax_submit">変更する</a>
                </div>
            </div>
        </section>

    {{ Form::close() }}
</div><!-- /contents -->



<!-- モーダル部分 *********************************************************** -->
<input type="hidden" class="ajax-modal-syncer" data-target="#modal-mail_henkou" id="modal_result">
<div class="modal-wrap completion_wrap">
    <div id="modal-mail_henkou" class="modal-content">

        <div class="modal_body completion">
            <div class="modal_inner">
                <h2 class="modal_ttl">
                    メールアドレスの変更を<br>
                    完了しました
                </h2>

                <div class="modal_txt">
                    <p>URLから変更を行ってください</p>
                </div>
            </div>
        </div>


        <div class="button-area type_under">
            <div class="btn_base btn_ok"><a id="modal-close" href="{{ route('user.myaccount.set_account') }}">OK</a></div>
        </div>

    </div><!-- /modal-content -->

</div>

<!-- モーダル部分 / ここまで ************************************************* -->
<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection

@section('page_js')
    <script src="{{ asset('assets/user/js/validate.js') }}"></script>

    <script>
        $('#show_password').on('click', function () {
            $('#password').toggleClass('__checked');
            var checked = $('#password').hasClass('__checked');
            if ( checked ) {
                $('#input_password').attr('type', 'text');
            } else {
                $('#input_password').attr('type', 'password');
            }
        });

        $('.ajax_submit').click(function () {

            var postData = new FormData($("#form1").get(0));
            postData.append("_token", "{{csrf_token()}}");

            $.ajax({
                type: "post",
                url: '{{ route('user.myaccount.set_email') }}',
                data: postData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (result) {
                    if ( result.result_code == 'success' ) {
                        showAjaxModal($('#modal_result'));
                    } else {
                        if ( result.res.email != undefined ) {
                            addError($('#email'), result.res.email)
                        }

                        if ( result.res.password != undefined ) {
                            addError($('#input_password'), result.res.password)
                        }

                        if ( result.redirect_url != undefined ) {
                            location.href = result.redirect_url;
                        }
                    }
                },
            });
        });
    </script>
@endsection
