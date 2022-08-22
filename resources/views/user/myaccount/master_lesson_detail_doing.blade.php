@extends('user.layouts.app')

@section('content')

@include('user.layouts.header_under')


<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents">

    <!--main_-->
    {{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

        <section class="pt10">
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
                                <p>2021年2月28日 18:00〜19:00</p>
                            </div>
                            <div>
                                <p>予約成立日   ：</p>
                                <p>2021年2月3日 11:15</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p>レッスン料</p>
                                <p class="price_mark">4,500</p>
                            </div>

                            <div>
                                <p>クーポン適用</p>
                                <p class="price_mark">-0,000</p>
                            </div>

                            <div>
                                <p class="modal-link">
                                    <a class="modal-syncer" data-target="modal-service">現在の手数料率</a>
                                </p>
                                <p>B</p>
                            </div>

                            <div>
                                <p>出張交通費</p>
                                <p class="price_mark">0,000</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p>売上金（目安）</p>
                                <p class="price_mark"><strong class="f-big">4,225</strong></p>
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
                            大阪府大阪市西区京町堀1-9-10<br>
                            リーガルスクエアビル2階
                        </p>
                    </div>
                    <div class="balloon balloon_blue font-small type_top_10">
                        <p>KARADAというジムの中でのレッスンとなります</p>
                    </div>
                </div>
            </div>


        </section>


        <section id="f-white_area">
            <div class="button-area">
                <div class="btn_base btn_pale-gray shadow-glay">
                    <button type="button" onclick="location.href='{{ route('user.myaccount.cancel_lesson') }}'">
                        このレッスンをキャンセルする
                    </button>
                </div>
            </div>
        </section>

    {{ Form::close() }}

</div><!-- /contents -->

@include('user.layouts.modal')


<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection
