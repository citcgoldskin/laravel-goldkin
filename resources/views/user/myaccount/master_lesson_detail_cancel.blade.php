@extends('user.layouts.app')

@section('content')

@include('user.layouts.header_under')


<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents">

    <!--main_-->
    {{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

        <section class="pt20">
            <div class="lesson_info_area">

                <ul class="teacher_info_02">
                    <li class="icon"><img src="{{ asset('assets/user/img/icon_02.svg') }}" class="プロフィールアイコン"></li>
                    <li class="about_teacher">
                        <div class="profile_name"><p>コウハイイイイイイイイイ<span>（23）女性</span></p></div>
                        <div><p class="orange_link icon_arrow orange_right"><a href="{{ route('user.lesson.profile') }}">プロフィール</a></p></div>
                    </li>
                </ul>
            </div>


        </section>

        <section>

            <div class="inner_box">
                <h3>レッスン概要</h3>
                <div class="white_box">
                    <ul class="list_box">
                        <li>
                            <ul class="info_ttl_wrap">
                                <li>
                                    <img src="{{ asset('assets/user/img/icon_lesson_ttl_01.png') }}" alt="">
                                </li>
                                <li>
                                    <p class="info_ttl">
                                        ランニングでダイエット<br>
                                        しませんか？タイトルが入ります<br>
                                        タイトルが入ります！
                                    </p>
                                </li>
                            </ul>
                        </li>

                        <li class="lesson_naiyou">
                            <div>
                                <p>レッスン日時：</p>
                                <p>2021年3月18日 16:00〜17:30</p>
                            </div>
                            <div>
                                <p>予約成立日   ：</p>
                                <p>2021年2月13日 22:57</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p>合計（税込）</p>
                                <p class="price_mark"><strong>4,500</strong></p>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="inner_box">
                <h3>レッスン場所</h3>
                <div class="white_box">
                    <div class="lesson_place">
                        <p>
                            大阪府大阪市西区江戸堀1-2-16<br>
                            スターバックス肥後橋南店
                        </p>
                    </div>
                    <div class="balloon balloon_blue font-small">
                        <p>入り口前で待ち合わせよろしくお願いします。</p>
                    </div>
                </div>
            </div>


        </section>

    {{ Form::close() }}

</div><!-- /contents -->

@include('user.layouts.modal')

<div id="footer_comment_area" class="under_area cancel_area">
    <p>
        このレッスンは、<br>
        2021年00月00日00:00にキャンセルしました。
    </p>
</div>

<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection
