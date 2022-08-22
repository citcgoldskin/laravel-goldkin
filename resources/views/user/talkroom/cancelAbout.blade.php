@extends('user.layouts.app')
@section('title', 'キャンセル料が発生する場合について')
<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
    @include('user.layouts.header_under')

    <div id="contents">
        <!--main_-->
        <form action="./" method="post" name="form1" id="form1">

        <section>
            <div class="inner_box">
                <h3 class="fs-18">待ち合わせ圏内とは？</h3>
                <div class="base_txt">
                    <p>
                        待ち合わせに指定された位置ピンから半径200m以内を待ち合わせ圏内といいます。
                    </p>
                </div>
            </div>

            <div class="inner_box">
                <div class="white_box">
                    <ul class="coution_area">
                        <li>
                            <div class="base_txt">
                                <p class="mark_left mark_square">
                                    レッスン開始時刻から15分以降にセンパイが待ち合わせ圏内にいて、コウハイが位置情報を共有していない（<a href="#case_1">ⅰ</a>）又は待ち合わせ圏外にいる状態（<a href="#case_2">ⅱ</a>）でセンパイがキャンセル申請すると、当日キャンセル扱いで後輩にキャンセル料が発生します。
                                </p>
                            </div>
                            <div class="coution_box">
                                <p class="icon_coution">
                                    センパイが待ち合わせ圏内でキャンセル申請をしても、コウハイが待ち合わせ圏内にいる場合（<a href="#case_3">ⅲ</a>）はキャンセル料は発生しません。
                                </p>
                            </div>
                        </li>

                        <li>
                            <div class="base_txt">
                                <p class="mark_left mark_square">
                                    コウハイがキャンセル申請を行った時点で、センパイが待ち合わせ圏内にいない（<a href="#case_4">ⅳ</a>）又は位置情報を共有して以内場合（<a href="#case_5">ⅴ</a>）キャンセル料は発生しません。
                                </p>
                            </div>
                            <div class="coution_box">
                                <p class="icon_coution">
                                    コウハイが待ち合わせ圏外にいたり、位置情報を共有していなくてもセンパイが待ち合わせ圏内にいない、又は位置情報を共有していない場合、キャンセル料は発生しません。
                                </p>
                            </div>
                        </li>

                        <li>
                            <div class="base_txt">
                                <p class="mark_left mark_square">
                                    レッスン時刻から60分経過後どちらもキャンセル申請を行わなかった場合、キャンセル料は発生しません。
                                </p>
                            </div>
                        </li>

                    </ul>

                </div>
            </div>

            <div class="inner_box">
                <ul class="guide_list">
                    <li id="case_1"><img src=" {{ asset('assets/user/img/about/cancel_case/case_01.png') }} " alt=""></li>
                    <li id="case_2"><img src=" {{ asset('assets/user/img/about/cancel_case/case_02.png') }} " alt=""></li>
                    <li id="case_3"><img src=" {{ asset('assets/user/img/about/cancel_case/case_03.png') }} " alt=""></li>
                    <li id="case_4"><img src=" {{ asset('assets/user/img/about/cancel_case/case_04.png') }} " alt=""></li>
                    <li id="case_5"><img src=" {{ asset('assets/user/img/about/cancel_case/case_05.png') }} " alt=""></li>
                </ul>

            </div>

            <div class="inner_box">
                <div class="white_box">
                    <div class="coution_box pt0">
                        <p class="icon_coution_02">
                            キャンセル申請は、先に申請された方の申請を基準に判定を行います。
                        </p>
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

