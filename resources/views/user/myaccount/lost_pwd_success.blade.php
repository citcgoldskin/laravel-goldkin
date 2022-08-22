@extends('user.layouts.app')
@section('content')

<!-- ************************************************************************
本文
************************************************************************* -->


    <div id="completion_wrap" class="no_modal">
        <div class="modal_body completion deco_mail">
            <div class="modal_inner re_send fullscreen-text">
                <h2 class="modal_ttl">メールを送信しました。</h2>
                <p class="modal_txt">
                    mmmmmmm@co.jpへメールを送信しました。<br>
                    メールに記載されたURLからパスワードの変更を完了してください。<br>
                    <br>
                    メールが届かない場合、再度送信してください。
                </p>

                <div class="sub_link">
                    <a href="">再度送信する</a>
                </div>
            </div>



        </div>

        <div class="bottom-posi">
            <div class="button-area">
                <div class="btn_base btn_ok"><a href="{{ route('user.login.form') }}">OK</a></div>
            </div>
        </div>

    </div>

@endsection
