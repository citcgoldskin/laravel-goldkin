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
                    {{ isset($email) && $email ? $email : '' }}へメールを送信しました。<br>
                    メールに記載されたURLからパスワードの変更を完了してください。<br>
                    <br>
                    メールが届かない場合、再度送信してください。
                </p>

                @if(isset($email) && $email)
                    <div class="sub_link">
                        <a href="" id="re-send">再度送信する</a>
                    </div>
                @endif
            </div>

            {{ Form::open(["route"=>"password.email", "method"=>"post", "name"=>"form1", "id"=>"form1" ]) }}

                <input type="hidden" value="{{ isset($email) && $email ? $email : '' }}" placeholder="" name="email">

            {{ Form::close() }}


        </div>

        <div class="bottom-posi">
            <div class="button-area">
                <div class="btn_base btn_ok"><a href="{{ route('user.login.form') }}">OK</a></div>
            </div>
        </div>

    </div>

@endsection

@section('page_js')
    <script>
        $(document).ready (function () {
            $('#re-send').click(function(e) {
                e.preventDefault();
                $('#form1').submit();
            });
        });
    </script>
@endsection
