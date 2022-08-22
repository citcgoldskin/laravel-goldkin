@extends('user.layouts.app')

@section('title', 'センパイ出品')

@section('content')

    @include('user.layouts.gnavi_under')
    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents">

        <section class="tab_area tab_white mb0">
            <div class="switch_tab three_tab">
                <div class="type_radio radio-01">
                    <input type="radio" name="onof-line" id="off-line" onclick="location.href='C-3_4php'">
                    <label class="ok" for="off-line">出品レッスン</label>
                </div>
                <div class="type_radio radio-02">
                    <input type="radio" name="onof-line" id="on-line-1" onclick="location.href='C-16.php'">
                    <label class="ok" for="on-line-1">出勤カレンダー</label>
                </div>
                <div class="type_radio radio-03">
                    <input type="radio" name="onof-line" id="on-line-2" checked="checked"
                           onclick="location.href='C-20.php'">
                    <label class="ok" for="on-line-2">リクエスト</label>
                    <span class="midoku">99</span>
                </div>
            </div>
        </section>

        <div class="tabs info_wrap mt0">
            <input id="tab-01" type="radio" name="tab_item" checked="checked">
            <label class="tab_item" for="tab-01">
                予約リクエスト<span class="midoku">7</span>
            </label>
            <input id="tab-02" type="radio" name="tab_item">
            <label class="tab_item" for="tab-02">
                出勤リクエスト<span class="midoku">3</span>
            </label>


            <!-- ********************************************************* -->
            <div class="tab_content" id="tab-01_content">

                <section>

                    <div class="board_box">
                        <a href="C-22_23.php">
                            <ul class="teacher_info_03 mt0">
                                <li class="icon_s40"><img src="img/icon_photo_02.png" alt="プロフィールアイコン"></li>
                                <li class="about_teacher">
                                    <div class="profile_name">
                                        <p>まゆゆゆゆゆゆゆゆゆゆ<br><span>（22）女性</span></p>
                                    </div>
                                </li>
                                <li><img src="img/icon_lesson_ttl_01.png" alt="カテゴリーアイコン"></li>
                            </ul>

                            <div>
                                <p class="lesson_ttl">ランニングで私とダイエットしませんか？</p>
                                <p class="target_area">レッスン場所：センパイが指定した場所が入ります</p>
                            </div>

                            <div class="kigen_wrap">
                                <h4>承認期限：1月10日</h4>
                                <ul class="list_area">
                                    <li>
                                        <div>1月12日　10:00~11:00</div>
                                        <div>3,000円</div>
                                    </li>
                                    <li>
                                        <div>1月13日　10:00~12:00</div>
                                        <div>6,000円</div>
                                    </li>
                                </ul>
                        </a>
                    </div>
            </div>

            <div class="board_box">
                <a href="C-22_23.php">
                    <ul class="teacher_info_03 mt0">
                        <li class="icon_s40"><img src="img/icon_02.svg" alt="プロフィールアイコン"></li>
                        <li class="about_teacher">
                            <div class="profile_name">
                                <p>さな<br><span>（41）女性</span></p>
                            </div>
                        </li>
                        <li><img src="img/icon_lesson_ttl_01.png" alt="カテゴリーアイコン"></li>
                    </ul>

                    <div>
                        <p class="lesson_ttl">元水泳部が指導します！</p>
                        <p class="target_area">レッスン場所：コウハイが提案した名称or住所が入ります</p>
                    </div>

                    <div class="kigen_wrap">
                        <h4>承認期限：1月20日</h4>
                        <ul class="list_area">
                            <li>
                                <div>1月12日　10:00~11:00</div>
                                <div>3,000円</div>
                            </li>
                            <li>
                                <div>1月13日　10:00~12:00</div>
                                <div>6,000円</div>
                            </li>
                            </li>
                            <li class="expired">
                                <h5>購入期限：1月16日 </h5>
                                <p><span class="dete">2月14日　11:00〜12:00</span><span class="price">2,000円</span></p>
                            </li>
                        </ul>
                </a>


            </div>
        </div>


        </section>


    </div><!-- /tab_content -->


    <!-- ********************************************************* -->

    <div class="tab_content" id="tab-02_content">

        <section>

            <div class="board_box">
                <a href="C-26.php">
                    <ul class="teacher_info_03 mt0">
                        <li><img src="img/icon_photo_04.png" class="プロフィールアイコン"></li>
                        <li class="about_teacher">
                            <div class="profile_name">
                                <p>大山ひとみ<br><span>（31）女性</span></p>
                            </div>
                        </li>
                        <li><img src="img/icon_lesson_ttl_01.png" class="カテゴリーアイコン"></li>
                    </ul>

                    <div>
                        <p class="lesson_ttl">ランニングで私とダイエットしませんか？タイトルが入りますタイト</p>
                        <p class="target_area">レッスン場所：指定地</p>
                    </div>

                    <div class="kigen_wrap">
                        <h4>承認期限：1月20日</h4>
                        <ul class="list_area">
                            <li>
                                <div>2月10日　9:00~13:00</div>
                            </li>
                            <li>
                                <div>2月11日　9:00~13:00</div>
                            </li>
                            <li>
                                <div>2月12日　9:00~13:00</div>
                            </li>
                            <li>
                                <div>2月13日　9:00~13:00</div>
                            </li>

                        </ul>
                </a>
            </div>

            <div class="about_attention_02">
                <span class="attention_update_02">60~120分 /2,000~4,000円</span>
            </div>
    </div>

    <div class="board_box">
        <a href="C-26.php">
            <ul class="teacher_info_03 mt0">
                <li><img src="img/icon_02.svg" class="プロフィールアイコン"></li>
                <li class="about_teacher">
                    <div class="profile_name">
                        <p>田中正造<br><span>（45）男性</span></p>
                    </div>
                </li>
                <li><img src="img/icon_lesson_ttl_01.png" class="カテゴリーアイコン"></li>
            </ul>

            <div>
                <p class="lesson_ttl">ランニングで私とダイエットしませんか？タイトルが入りますタイト</p>
                <p class="target_area">レッスン場所：コウハイが提案した名称or住所が入ります</p>
            </div>

            <div class="kigen_wrap">
                <h4>承認期限：1月15日</h4>
                <ul class="list_area">
                    <li>
                        <div>2月14日　9:00~12:00</div>
                    </li>
                    <li>
                        <div>2月14日　18:00~22:00</div>
                    </li>
                </ul>
        </a>

        <div class="about_attention_02">
            <span class="attention_update_02">60~120分 /2,000~4,000円</span>
        </div>

        <div class="approval_box">
            <a href="A-24.php">
                <h4>購入期限：1月16日</h4>
                <ul class="list_area">
                    <li>
                        <div>2月14日　11:00~12:00</div>
                        <div>2,000円</div>
                    </li>
                </ul>
            </a>
        </div>
    </div>
    </div>


    </section>


    </div><!-- /tab_content -->



    </div><!-- /tabs -->

    </div><!-- /contents -->


    <footer>
        @include('user.layouts.fnavi')
    </footer>
@endsection
