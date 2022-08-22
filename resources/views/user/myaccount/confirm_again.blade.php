@extends('user.layouts.app')
@section('title', '本人確認')

@section('content')
@include('user.layouts.header_under')
<style>
    body{
        background: #FFF;
    }
</style>
<div id="contents">
    {{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1", "target"=>"senddata"]) }}
        <section id="button-area">

            <div class="inner_box">
                <div class="form_txt">
                    <p>
                        更新内容に名前が含まれているため、お手数ですが再度本人確認をお願いいたします。
                    </p>
                </div>
            </div>


            <div id="footer_button_area" class="under_area">
                <ul>
                    <li class="send-request">
                        <div class="btn_base btn_orange">
                            <button type="button" onclick="location.href='{{ route('user.myaccount.confirm') }}'">本人確認を行う</button>
                        </div>
                    </li>
                </ul>
            </div>
            <!--
                    <div class="sub_link">
                     <p>
                      <a href="{{ route('user.login.lost_pwd.form') }}">メールアドレスをお忘れですか？</a>
                     </p>
                    </div>
                  -->
        </section>
    {{ Form::close() }}
</div><!-- /contents -->


<!-- モーダル部分 *********************************************************** -->
<iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
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
            <div class="btn_base btn_ok"><a id="modal-close">OK</a></div>
        </div>

    </div><!-- /modal-content -->

</div>
<div id="modal-overlay" style="display: none;"></div>

<!-- モーダル部分 / ここまで ************************************************* -->

<footer>
    @include('user.layouts.fnavi')
</footer>

@endsection

