@extends('user.layouts.app')

@section('title', 'レッスン編集・削除')

@section('content')

    @include('user.layouts.header_under')
    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents" class="short">

        <form action="" method="post" name="form1" id="form1" target="senddata">

          <section class="tab_area tab_white">
                                <div id="add-area">
                                <div class="from-judding">
                                    <div class="readthis">
                                <p>現在このレッスンは審査中です。<br>
                                    審査中に変更申請を行うことはできません。
                                    審査終了までもうしばらくお待ちください。</p>
                                        </div>
                                    <div class="hosoku">
                                        <div class="pic"><img src="{{asset('assets/user/img/icon-caution.svg')}}"></div>
                                        <div class="text">
                                    <p>イメージや文章を変更する場合は再度審査が必要です。<br>
                                    <small>※審査中であっても現在の内容でのレッスンを行うことは可能です。</small></p>
                                            </div>
                                        </div>
                                </div>

                                    <div class="from-non-approval">
                                                        <div class="readthis">
                                    <p>申請頂いた変更内容に不適切な箇所がありましたため、非承認となりました。<br>
                                        以下の点を修正し再度申請くださいませ。</p>
                                        <div class="explain">
                                        <p>説明文を管理画面で入力。<br>説明文を管理画面で入力。<br>説明文を管理画面で入力。<br>説明文を管理画面で入力。<br>説明文を管理画面で入力。</p>

                                        </div>
                                        </div>
                                            <div class="hosoku">
                                        <div class="pic"><img src="{{asset('assets/user/img/icon-caution.svg')}}"></div>
                                        <div class="text">
                                    <p>イメージや文章を変更する場合は再度審査が必要です。<br>
                                    <small>※審査中であっても現在の内容でのレッスンを行うことは可能です。</small></p>
                                            </div>
                                        </div>
                                </div>
                </div>
            <h3 class="hide">レッスン形式</h3>

            <div class="switch_tab hide">
                <div class="radio-01">
                <input type="radio" name="onof-line" id="off-line" checked="checked">
                <label class="ok" for="off-line">対面レッスン型</label>
                </div>
                <div class="radio-02">
                <input type="radio" name="onof-line" id="on-line" value="C-10-1.php" onclick="location.href=this.value">
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

        <section class="pb0">
            <div class="inner_box">
            <h3>カテゴリー</h3>
                <div class="form_wrap icon_form type_arrow_right shadow-glay">
                 <button type="button" onClick="location.href='B-4-one.php'" class="form_btn">指定なし</button>
                </div>
            </div>

            <div class="inner_box">
            <h3>レッスンタイトル<small>（50文字まで）</small></h3>
                <div class="input-text2 lesson_ttl_textarea shadow-glay">
                    <textarea placeholder="50文字入ります" maxlength="50"></textarea>
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
            <h3>対応人数</h3>
            <div class="white_box">
            <ul class="small_select bb">
            <li>最大</li>
            <li class="ma-both">
                <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                    <select name="popular">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    </select>
                </div>
            </li>
            <li>名以下</li>
            </ul>
                <div class="check-box">
                    <div class="clex-box_02 number_people">
                        <input type="checkbox" name="commitment" value="1" id="c1">
                        <label for="c1"><p>女性同伴で男性受付可</p></label>
                    </div>
                </div>
             </div>
             </div>

            <div class="inner_box">
            <h3>レッスン場所</h3>
            <div class="white_box">
                <div class="check-box">
                    <div class="clex-box_02">
                        <input type="checkbox" name="commitment" value="1" id="c2">
                        <label for="c2"><p>レッスン場所を指定せずに相談を受付ける</p></label>
                    </div>
                </div>
                <div class="orange_msg">地図をタップして指定してください。</div>

                <div class="map">
                 <!-- 多分iframeになる予想 -->
                 <div class="dummy"></div>
                </div>

                <div class="form_wrap icon_form type_search mb30">
                 <input type="text" value="" placeholder="住所やキーワードを入力してください" class="search">
                </div>

            <h3>待ち合わせ場所の詳細<small>（100文字まで）</small></h3>
                <div class="input-text2">
                    <textarea placeholder="待ち合わせ場所の詳細を入力してください。" maxlength="100" class="shadow-glay count-text100"></textarea>
                    <p class="max_length"><span id="num100">0</span>／100</p>
                </div>

                <div class="check-box">
                    <div class="clex-box_02 lesson_soudan">
                        <input type="checkbox" name="commitment" value="1" id="c3">
                        <label for="c3"><p>レッスン場所の相談可</p></label>
                    </div>
                </div>

            </div>
            </div>

            <div class="inner_box">
            <h3>サービス詳細<small>（1,000文字まで）</small></h3>
                <div class="input-text2">
                    <textarea placeholder="" cols="50" rows="10" maxlength="1000" class="shadow-glay count-text"></textarea>
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
                    <div class="clex-box_02">
                        <input type="checkbox" name="commitment" value="1" id="c4">
                        <label for="c4"><h3>出勤リクエストを受付する</h3></label>
                    </div>
                </div>
            <div class="balloon balloon_blue mt0">
                <p>指定するレッスン場所によっては出張交通費をお願いされる場合があります。</p>
            </div>

      <div class="inner_box">
       <div class="kodawari_check">
        <h3>こだわり</h3>
         <div class="check-flex">
          <div class="clex-box_01">
           <input type="checkbox" name="commitment" value="1" id="kodawari-c1">
           <label for="kodawari-c1"><p>手ぶらOK</p></label>
          </div>
          <div class="clex-box_01">
           <input type="checkbox" name="commitment" value="1" id="kodawari-c2">
           <label for="kodawari-c2"><p>顔写真あり</p></label>
          </div>
          <div class="clex-box_01">
           <input type="checkbox" name="commitment" value="1" id="kodawari-c3">
           <label for="kodawari-c3"><p>マスク着用</p></label>
          </div>
          <div class="clex-box_01">
           <input type="checkbox" name="commitment" value="1" id="kodawari-c4">
           <label for="kodawari-c4"><p>複数人対応</p></label>
          </div>
          <div class="clex-box_01">
           <input type="checkbox" name="commitment" value="1" id="kodawari-c5">
           <label for="kodawari-c5"><p>出勤リクエスト<br>受付可</p></label>
          </div>
          <div class="clex-box_01">
           <input type="checkbox" name="commitment" value="1" id="kodawari-c6">
           <label for="kodawari-c6"><p>本人確認必須</p></label>
          </div>
          <div class="clex-box_01">
           <input type="checkbox" name="commitment" value="1" id="kodawari-c7">
           <label for="kodawari-c7"><p>相談エリア内のみ受付可</p></label>
          </div>
          <div class="clex-box_01">
           <input type="checkbox" name="commitment" value="1" id="kodawari-c8">
           <label for="kodawari-c8"><p>相談エリア内<br>出張交通費なし</p></label>
          </div>
          <div class="clex-box_01">
           <input type="checkbox" name="commitment" value="1" id="kodawari-c9">
           <label for="kodawari-c9"><p>相談エリア外<br>でも出張可</p></label>
          </div>
          <div class="clex-box_01">
           <input type="checkbox" name="commitment" value="1" id="kodawari-c10">
           <label for="kodawari-c10"><p>上級者にも対応</p></label>
          </div>
          <div class="clex-box_01">
           <input type="checkbox" name="commitment" value="1" id="kodawari-c11">
           <label for="kodawari-c11"><p>女性同伴で<br>男性受付可</p></label>
          </div>
          <div class="clex-box_01">
           <input type="checkbox" name="commitment" value="1" id="kodawari-c12">
           <label for="kodawari-c12"><p>割引クーポンあり</p></label>
          </div>

        </div>
        </div>
        </div>

                <div class="inner_box">
                 <h3 class="must">クーポンの適用</h3>
                    <div class="form_wrap">
                     <ul class="radio-box radio_mark">
                      <li>
                        <input type="radio" name="coupon" value="1" id="coupon-1">
                        <label for="coupon-1">適用する</label>
                      </li>
                      <li>
                        <input type="radio" name="coupon" value="1" id="coupon-2">
                        <label for="coupon-2">適用しない</label>
                      </li>
                     </ul>
                    </div>
                    <p class="coupon_hoyuu">
                     あなたが保有しているクーポンは<a href="#">こちら</a>
                    </p>
                </div>


            </section>


           <section id="f-white_area">
            <div class="button-area mt20">
              <div class="btn_base btn_orange shadow">
               <button type="submit"  class="btn-send">変更を保存</button>
              </div>
              <div class="btn_base btn_pale-gray shadow">
               <button type="button" class="modal-syncer button-link" data-target="modal-delete">削除する</button>
              </div>
              <div class="btn_base btn_white shadow">
               <button type="button" class="modal-syncer button-link" data-target="modal-hikoukai">非公開にする</button>
              </div>
            </div>
           </section>





    <!-- モーダル部分  販売手数料について*********************************************************** -->
            <div class="modal-wrap coupon_modal">

            <div id="modal-sales-commission" class="modal-content">
             <div class="modal_body">

            <div class="close_btn_area">
              <a id="modal-close"><img src="{{asset('assets/user/img/x-mark.svg')}}" alt="閉じる"></a>
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



                <div class="modal_body">
                 <div class="modal_inner">
                 <h4 id="circle-orange_ttl">!</h4>
                  <h2 class="modal_ttl">
                 削除して<br>
    よろしいですか？
                  </h2>

                </div>

                     <div class="button-area mt50">
                         <div class="btn_base btn_orange">
                         <button type="button" onclick="location.href='C-8_10.php'">削除</button>
                         </div>
                         <div class="btn_base btn_gray-line">
                          <a id="modal-close" class="button-link">キャンセル</a>
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

        <iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>

            <div class="modal-wrap completion_wrap">
                <div id="modal-edit-save" class="modal-content">

                <div class="modal_body completion">
                 <div class="modal_inner">
                  <h2 class="modal_ttl">
                   レッスンの変更を<br>
                   保存しました
                  </h2>
                 </div>
                </div>

                     <div class="button-area">
                         <div class="btn_base btn_orange">
                          <a href="C-3_4.php">OK</a>
                         </div>
                         <div class="btn_base btn_gray-line">
                          <a href="B-10_1.php">続けてオンライン型を出品</a>
                         </div>
                    </div>
                </div>
            </div>

    <div id="modal-overlay2" style="display: none;"></div>


    <!--- モーダル部分 / ここまで ************************************************* -->


    </form>

    </div><!-- /contents -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection


