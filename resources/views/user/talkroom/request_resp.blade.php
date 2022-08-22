@extends('user.layouts.app')
@section('title', 'リクエストに回答する')

<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
    @include('user.layouts.header_under')

    <div id="contents">
        <div id="top-menu" class="talkroom_wrap">
            <time class="change">変更申請時刻:2021年2月27日 17:39</time>
        </div>
        <!--main_-->
        <form action="C-22_23-after2.php" method="post" name="form1" id="form1">
            <section>
                <div class="lesson_info_area pt40">
                    <ul class="teacher_info_02">
                        <li class="icon"><img src="img/icon_02.svg" class="プロフィールアイコン"></li>
                        <li class="about_teacher">
                            <div class="profile_name">
                                <p>ミクククククククククククク<span>（23）女性</span></p>
                            </div>
                            <div>
                                <p class="orange_link icon_arrow orange_right"><a href="A-14.php">プロフィール</a></p>
                            </div>
                        </li>
                    </ul>
                </div>
            </section>
            <section class="pb10">
                <div class="inner_box">
                    <h3 class="summary_ttl"> <span>レッスン概要</span> <span class="shounin_kigen">承認期限：<big>1</big>月<big>31</big>日</span> </h3>
                    <div class="white_box">
                        <div class="lesson_ttl_02">
                            <p> ランニングでダイエットしませんか？出品者のレッスンタイトルが入ります </p>
                        </div>
                    </div>
                </div>
                <div class="inner_box">
                    <h3>レッスン場所</h3>
                    <div class="white_box">
                        <div class="lesson_place">
                            <p> 大阪府大阪市西区江戸堀1-2-16<br>
                                スターバックスコーヒー肥後橋南店 </p>
                        </div>
                        <div class="balloon balloon_blue font-small">
                            <p>入り口の前で待ち合わせよろしくお願いします。</p>
                        </div>
                    </div>
                </div>
                <div class="inner_box">
                    <h3>レッスン日時と料金</h3>
                    <div class="white_box">
                        <div class="icon_check">
                            <h3 class="icon_red">変更前</h3>
                        </div>
                        <ul class="list_box cancel_policy">
                            <li class="nobo-t">
                                <div>
                                    <p class="ls1">2021年2月28日(木) <small>18:00～19:00</small></p>
                                    <p class="space mr0"> <em>4,000円</em> </p>
                                </div>
                                <div>
                                    <p>出張交通費</p>
                                    <p class="space mr0"> <em>500円</em> </p>
                                </div>
                            </li>
                        </ul>
                        <div class="icon_check pt20">
                            <h3 class="icon_blue">変更後</h3>
                        </div>
                        <ul class="list_box cancel_policy">
                            <li class="nobo-t nobo-b">
                                <div>
                                    <p class="ls1">2021年2月28日(木) <small>18:00～19:00</small></p>
                                    <p class="space mr0"> <em>6,000円</em> </p>
                                </div>
                                <div>
                                    <p>出張交通費</p>
                                    <p class="space mr0"> <em>1,500円</em> </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="balloon_area" id="makeImg">
                    <div class="balloon balloon_white">
                        <textarea placeholder="キャンセルの理由を100字以内でご記入ください。" cols="50" rows="10" maxlength="100"></textarea>
                    </div>
                    <p class="form_txt gray_txt"> ※キャンセルの理由は先輩に通知されます </p>
                </div>
            </section>
            <div class="white-bk pt30">
                <div class="check-box">
                    <div class="clex-box_02 pl20">
                        <input type="radio" name="approval" value="1" id="approval-31">
                        <label for="approval-31">
                            <p>変更リクエストを承認する</p>
                        </label>

                        <input type="radio" name="approval" value="1" id="approval-32">
                        <label for="approval-32">
                            <p>変更リクエストを承認しない</p>
                        </label>
                    </div>
                </div>
                <aside class="hosoku pb30"><p>※0000年00月00日 00:00までに回答しなかった場合は承認しなかった扱いになります。</p></aside>
                <div class="button-area">
                    <div class="btn_base btn_orange shadow">
                        <button type="button" class="modal-syncer button-link" data-target="modal-kaitou"> 回答を確定する</button>
                    </div>
                </div>
            </div>
            <!-- モーダル部分 *********************************************************** -->
            <!-- ********************************************************* -->

            <div class="modal-wrap">
                <div id="modal-kaitou" class="modal-content">

                    <div class="modal_body completion">
                        <div class="modal_inner">
                            <h2 class="modal_ttl">
                                変更リクエストを<br>
                                承認します。<br>
                                よろしいですか？
                            </h2>
                        </div>
                    </div>

                    <div class="button-area">
                        <div class="btn_base btn_orange">
                            <button type="submit" class="btn-send2">承認する</button>
                        </div>
                        <div class="btn_base btn_ok no-w">
                            <a id="modal-close" class="button-link">戻る</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- モーダル部分 / ここまで ************************************************* -->
        </form>
    </div>
    <!-- /contents -->

    @include('user.layouts.modal')
@endsection

