@extends('user.layouts.app')
@section('title', 'リクエスト内容の確認変更')
<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
    @include('user.layouts.header_under')

    <div id="contents" >
        <!--main_-->
        <form action="./" method="post" name="form1" id="form1">

            <section>

                <div class="white_box shadow-glay">
                    <span class="choice_lesson">選択中のレッスン！</span>
                    <ul class="reserved_top_box mt10">
                        <li><img src=" {{ asset('assets/user/img/A-24/img_01.png') }}" alt=""></li>
                        <li>
                            <p class="lesson_ttl">ランニングで私とダイエットしませんか</p>
                            <div class="inline_flex">
                                <p class="icon_taimen">対面</p>
                                <p class="orange_link icon_arrow orange_right">
                                    <a href="A-15_16_17.php">詳細を見る</a>
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </section>

            <section>

                <div class="inner_box">
                    <h3>レッスン場所</h3>
                    <div class="white_box">
                        <div class="lesson_place">
                            <p>
                                大阪府大阪市西区江戸堀1-2-16<br>
                                スターバックスコーヒー肥後橋南店
                            </p>
                        </div>
                        <div class="balloon balloon_blue">
                            <p>入り口の前で待ち合わせよろしくお願いします。</p>
                        </div>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>レッスン日時</h3>

                    <div class="board_box effect_none">
                        <ul class="list_area bo pa">
                            <li>
                                <div>1月18日（月）　10:00~11:00</div>
                                <div>6,000円</div>
                            </li>
                            <li>
                                <div>1月20日（水）　16:00~17:30</div>
                                <div>6,000円</div>
                            </li>
                            <li>
                                <div>1月21日（木）　16:00~17:30</div>
                                <div>6,000円</div>
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="inner_box">
                    <h3>リクエストの承認期限</h3>
                    <div class="white_box">
                        <div class="base_txt">
                            <p>2021年1月16日</p>
                        </div>
                    </div>
                </div>


            </section>

            <div class="white-bk">
                <div class="button-area">
                    <div class="btn_base btn_orange shadow">
                        <button type="button" onclick="location.href='D-31.php'">リクエスト内容を変更する</button>
                    </div>
                    <div class="btn_base btn_pale-gray shadow-glay">
                        <button type="button" onclick="location.href='D-33.php'">リクエストをキャンセルする</button>
                    </div>
                </div>
            </div>

        </form>
    </div><!-- /contents -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>
@endsection


