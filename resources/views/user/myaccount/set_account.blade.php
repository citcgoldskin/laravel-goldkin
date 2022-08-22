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
                <h3>設定</h3>
                <ul class="list_area">
                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.edit_profile.form') }}">プロフィール</a>
                    </li>

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.lesson.select_pay_method') }}">支払い方法</a>
                    </li>

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.show_email_form') }}">メールアドレス</a>
                    </li>

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.show_password_form') }}">パスワード</a>
                    </li>

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.show_phone') }}">電話番号</a>
                    </li>
                </ul>
            </div>

            <div class="inner_box">
                <h3>本人情報</h3>
                <ul class="list_area">
                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.set_user') }}">ユーザー情報</a>
                    </li>

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.account', ['prev_url_id' => 1]) }}">銀行口座</a>
                    </li>

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.confirm') }}">本人確認</a>
                    </li>
                </ul>
            </div>

            <div class="inner_box">
                <h3>セキュリティ</h3>
                <ul class="list_area">
                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.block_outline', ['del_bl_id' => 0]) }}">ブロック一覧</a>
                    </li>
                </ul>
            </div>

        </section>

    {{ Form::close() }}


</div><!-- /contents-->
<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection
