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
                <h3 class="must">現在のパスワード</h3>
                <div class="form_wrap shadow-glay for-warning">
                    <input type="password" value="" placeholder="" name="old_password" id="old_password">
                    <p class="warning"></p>
                </div>
            </div>

            <ul class="form_area">
                <li>
                    <h3 class="must">新しいパスワード</h3>
                    <p class="pw_txt">※英字と数字の両方を含めて設定してください。</p>
                    <div class="form_wrap shadow-glay for-warning">
                        <input type="password" value="" placeholder="7文字以上の半角英数字" name="password" id="password">
                        <p class="warning"></p>
                    </div>
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


        <section id="button-area">

            <div class="inner_box">

                <div class="button-area">
                    <div class="btn_base btn_orange shadow">
                        <a class="ajax_submit">変更する</a>
                    </div>
                </div>

                <div class="sub_link">
                    <p>
                        <a href="{{ route('password.change') }}">パスワードをお忘れですか？</a>
                    </p>
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
                    パスワードの変更を<br>
                    完了しました
                </h2>

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
    $('.ajax_submit').click(function () {

        var postData = new FormData($("#form1").get(0));
        postData.append("_token", "{{csrf_token()}}");

        $.ajax({
            type: "post",
            url: '{{ route('user.myaccount.set_password') }}',
            data: postData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (result) {
                if ( result.result_code == 'success' ) {
                    showAjaxModal($('#modal_result'));
                } else {
                    console.log(result.res.old_password)
                    if ( result.res.old_password != undefined ) {
                        addError($('#old_password'), result.res.old_password)
                    }

                    if ( result.res.password != undefined ) {
                        addError($('#password'), result.res.password)
                    }
                }
            },
        });
    });
</script>
@endsection
