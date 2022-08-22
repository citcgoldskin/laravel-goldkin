@extends('user.layouts.app')

@section('title', 'レッスン編集・削除')

@section('content')

    @include('user.layouts.header_under')

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents" class="short">

        <!--main_-->
        <form action="C-10-2.php" method="post" name="form1" id="form1">
            <div class="hosoku-wrap">
                <div class="hosoku">
                    <div class="pic"><img src="img/icon-caution.svg"></div>
                    <div class="text">
                        <p>イメージや文章を変更する場合は再度審査が必要です。<br>
                            <small>※審査中であっても現在の内容でのレッスンを行うことは可能です。</small></p>
                    </div>
                </div>
            </div>
            <section class="tab_area tab_white">

                <h3>レッスン形式</h3>

                <div class="switch_tab">
                    <div class="radio-01">
                        <input type="radio" name="onof-line" id="off-line" value="C-8_10.php"
                               onclick="location.href=this.value">
                        <label class="ok" for="off-line">対面レッスン型</label>
                    </div>
                    <div class="radio-02">
                        <input type="radio" name="onof-line" id="on-line" checked="checked">
                        <label class="ok" for="on-line">オンライン型</label>
                    </div>
                </div>
            </section>

            <section class="slider_area">

                <h3>レッスンイメージ</h3>
                <!-- Slider main container -->
                <div class="swiper-container">
                    <div class="swiper-inner">
                        <div class="lesson_photo pb10">
                            <ol class="swiper-wrapper lesson_photo_list">
                                <!-- Slides -->
                                <li class="swiper-slide">
                                    <div class="form_wrap shadow-glay">
                                        <label>
                                            <div class="photo_btn must"></div>
                                            <input type="file" class="camera_mark">
                                        </label>
                                    </div>
                                </li>
                                <li class="swiper-slide">
                                    <div class="form_wrap shadow-glay">
                                        <label>
                                            <div class="photo_btn"></div>
                                            <input type="file" class="camera_mark">
                                        </label>
                                    </div>
                                </li>
                                <li class="swiper-slide">
                                    <div class="form_wrap shadow-glay">
                                        <label>
                                            <div class="photo_btn"></div>
                                            <input type="file" class="camera_mark">
                                        </label>
                                    </div>
                                </li>
                                <li class="swiper-slide">
                                    <div class="form_wrap shadow-glay">
                                        <label>
                                            <div class="photo_btn"></div>
                                            <input type="file" class="camera_mark">
                                        </label>
                                    </div>
                                </li>
                                <li class="swiper-slide">
                                    <div class="form_wrap shadow-glay">
                                        <label>
                                            <div class="photo_btn"></div>
                                            <input type="file" class="camera_mark">
                                        </label>
                                    </div>
                                </li>
                                <li class="swiper-slide">
                                    <div class="form_wrap shadow-glay">
                                        <label>
                                            <div class="photo_btn"></div>
                                            <input type="file" class="camera_mark">
                                        </label>
                                    </div>
                                </li>
                                <li class="swiper-slide">
                                    <div class="form_wrap shadow-glay">
                                        <label>
                                            <div class="photo_btn"></div>
                                            <input type="file" class="camera_mark">
                                        </label>
                                    </div>
                                </li>
                                <li class="swiper-slide">
                                    <div class="form_wrap shadow-glay">
                                        <label>
                                            <div class="photo_btn"></div>
                                            <input type="file" class="camera_mark">
                                        </label>
                                    </div>
                                </li>
                                <li class="swiper-slide">
                                    <div class="form_wrap shadow-glay">
                                        <label>
                                            <div class="photo_btn"></div>
                                            <input type="file" class="camera_mark">
                                        </label>
                                    </div>
                                </li>
                                <li class="swiper-slide">
                                    <div class="form_wrap shadow-glay">
                                        <label>
                                            <div class="photo_btn"></div>
                                            <input type="file" class="camera_mark">
                                        </label>
                                    </div>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

            </section>

            <section class="pb40">
                <div class="inner_box">
                    <h3>カテゴリー</h3>
                    <div class="form_wrap icon_form type_arrow_right shadow-glay">
                        <button type="button" onClick="location.href='B-4-one.php'" class="form_btn">指定なし</button>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>レッスンタイトル<small>（50文字まで）</small></h3>
                    <div class="input-text2 lesson_ttl_textarea">
                        <textarea placeholder="50文字入ります" maxlength="50"
                                  class=" shadow-glay"></textarea>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>希望する性別</h3>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                        <select name="popular">
                            <option value="選択してください">選択してください</option>
                            <option value="指定なし">指定なし</option>
                            <option value="女性">女性</option>
                            <option value="男性">男性</option>
                        </select>
                    </div>
                </div>
                <div class="inner_box">
                    <h3>希望する年代</h3>
                    <ul class="select_flex">
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="age1">
                                    <option value="0">指定なし</option>
                                    <option value="1">1</option>
                                </select>
                            </div>
                            <div>歳</div>
                            <div>～</div>
                        </li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="age2">
                                    <option value="0">指定なし</option>
                                    <option value="1">1</option>
                                </select>
                            </div>
                            <div>歳</div>
                        </li>
                    </ul>
                </div>

                <div class="inner_box">
                    <h3>最低レッスン時間</h3>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                        <select name="popular">
                            <option value="選択してください">選択してください</option>
                            <option value="1時間">1時間</option>
                        </select>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>30分あたりのレッスン料金</h3>
                    <div class="input-text">
                        <input type="text" name="name" class="w50 shadow-glay">
                        <span class="unit">円（1,000〜100,000円）</span>
                    </div>
                </div>
                <div class="balloon balloon_blue mt0">
                    <p>レッスン料金には所定の販売手数料が発生します。</p>
                </div>

                <p class="modal-link modal-link_blue">
                    <a class="modal-syncer button-link" data-target="modal-sales-commission">販売手数料について</a>
                </p>

                <div class="inner_box">
                    <h3>対応人数最大</h3>
                    <p class="base_txt">
                        オンラインのレッスンでは<br>
                        マンツーマン形式のみとなります。
                    </p>
                </div>

                <div class="inner_box">
                    <h3>サービス詳細<small>（1,000文字まで）</small></h3>
                    <div class="input-text2">
                        <textarea placeholder="" cols="50" rows="10" maxlength="1000"
                                  class="shadow-glay count-text"></textarea>
                        <p class="max_length"><span id="num">0</span>／1,000</p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>持ち物・その他の費用<small>（200文字まで）</small></h3>
                    <div class="input-text2">
                        <textarea placeholder="" cols="50" rows="10" class="shadow-glay count-text200"></textarea>
                        <p class="max_length"><span id="num200">0</span>／200</p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>購入にあたってのお願い・注意事項<small>（200文字まで）</small></h3>
                    <div class="input-text2">
                        <textarea placeholder="" cols="50" rows="10" class="shadow-glay count-text200-2"></textarea>
                        <p class="max_length"><span id="num200-2">0</span>／200</p>
                    </div>
                </div>

                <div class="inner_box check-box">
                    <div class="clex-box_01 bg_none">
                        <input type="checkbox" name="commitment" value="1" id="c1">
                        <label for="c1"><h3>出勤リクエストを受付する</h3></label>
                    </div>
                </div>
                <div class="balloon balloon_blue mt0">
                    <p>指定するレッスン場所によっては出張交通費をお願いされる場合があります。</p>
                </div>


            </section>


            <section id="f-white_area">
                <div class="button-area mt20">
                    <div class="btn_base btn_orange shadow">
                        <button type="submit">変更を申請する</button>
                    </div>
                    <div class="btn_base btn_pale-gray shadow">
                        <button type="button" class="modal-syncer button-link" data-target="modal-delete">削除する</button>
                    </div>
                    <div class="btn_base btn_white shadow">
                        <button type="button" class="modal-syncer button-link" data-target="modal-hikoukai">非公開にする
                        </button>
                    </div>
                </div>
            </section>

        </form>

    </div><!-- /contents -->

    <!-- モーダル部分  販売手数料について*********************************************************** -->
    <div class="modal-wrap coupon_modal">

        <div id="modal-sales-commission" class="modal-content">
            <div class="modal_body">

                <div class="close_btn_area">
                    <a id="modal-close"><img src="img/x-mark.svg" alt="閉じる"></a>
                </div>

                <div class="modal_inner">

                    <h2 class="modal_ttl">
                        販売手数料について
                    </h2>

                    <section class="case">

                        <h3 class="case_ttl_02">
                            サブタイトル
                        </h3>
                        <p class="modal_txt">
                            テキスト
                        </p>


                    </section>


                </div>


                <div class="button-area">
                    <div class="btn_base btn_ok">
                        <a id="modal-close" class="button-link">OK</a>
                    </div>
                </div>

            </div><!-- /modal-content -->

        </div>


        <!-- ********************************************************* -->
        　
        <div class="modal-wrap completion_wrap">
            <div id="modal-delete" class="modal-content">

                <div class="modal_body completion">
                    <div class="modal_inner">
                        <h2 class="modal_ttl">
                            レッスンを<br>
                            削除しました
                        </h2>
                    </div>
                </div>

                <div class="button-area">
                    <div class="btn_base btn_ok">
                        <a id="modal-close" class="button-link">OK</a>
                    </div>
                </div>
            </div>
        </div>


        <!-- ********************************************************* -->
        <div class="modal-wrap completion_wrap">
            <div id="modal-hikoukai" class="modal-content">

                <div class="modal_body completion">
                    <div class="modal_inner">
                        <h2 class="modal_ttl">
                            レッスンを<br>
                            非公開にしました
                        </h2>
                    </div>
                </div>

                <div class="button-area">
                    <div class="btn_base btn_ok">
                        <a id="modal-close" class="button-link">OK</a>
                    </div>
                </div>
            </div>
        </div>


        <!-- ********************************************************* -->
        <!--
            <iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>

                <div class="modal-wrap completion_wrap">
                    <div id="modal-edit-save" class="modal-content">

                    <div class="modal_body completion">
                     <div class="modal_inner">
                      <h2 class="modal_ttl">
                       変更が完了しました。
                      </h2>
                            <div class="modal_txt">
                        <p>
                        変更後のレッスン内容は「センパイ出品の出品レッスン」または、「マイページの出品管理」からご確認ください。
                        </p>
                        </div>
                     </div>
                    </div>

                          <div class="button-area">
                              <div class="btn_base btn_orange">
                              <a href="C-3_4.php">OK</a>
                             </div>

                        </div>
                    </div>
                </div>

        <div id="modal-overlay2" style="display: none;"></div>
        -->

        <!--- モーダル部分 / ここまで ************************************************* -->

        <footer>
            @include('user.layouts.fnavi')
        </footer>

@endsection


