@extends('user.layouts.app')

@section('title', '募集内容の入力')

@section('content')

    @include('user.layouts.header_under')

    <div id="contents">
        <!--main_-->
            {{ Form::open(["route"=>["keijibann.recruiting_edit_comp"], "method"=>"get"]) }}
            <section>

                <div class="inner_box">
                    <h3>カテゴリー</h3>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                        <select name="popular" class="category-item">
                            <option value="ランニング・ウォーキング">ランニング・ウォーキング</option>
                            <option value="サイクリング">サイクリング</option>
                            <option value="水泳">水泳</option>
                            <option value="筋トレ">筋トレ</option>
                            <option value="ゴルフ">ゴルフ</option>
                            <option value="カラオケ">カラオケ</option>
                            <option value="その他・アウトドア">その他・アウトドア</option>
                            <option value="その他・インドア">その他・インドア</option>
                            <option value="パソコン">パソコン</option>
                            <option value="ペット散歩・ふれあい">ペット散歩・ふれあい</option>

                        </select>
                    </div>
                </div>



                <div class="inner_box">
                    <h3>カテゴリーアイコン</h3>
                    <div class="category_img">
                        <p class="c-icon1 active"><img src="{{ asset('assets/user/img/category/icon1.png') }}" alt="ランニング・ウォーキング"></p>
                        <p class="c-icon2"><img src={{ asset('assets/user/img/category/icon2.png') }}"" alt="サイクリング"></p>
                        <p class="c-icon3"><img src="{{ asset('assets/user/img/category/icon3.png') }}" alt="水泳"></p>
                        <p class="c-icon4"><img src="{{ asset('assets/user/img/category/icon4.png') }}" alt="筋トレ"></p>
                        <p class="c-icon5"><img src="{{ asset('assets/user/img/category/icon5.png') }}" alt="ゴルフ"></p>
                        <p class="c-icon6"><img src="{{ asset('assets/user/img/category/icon6.png') }}" alt="カラオケ"></p>
                        <p class="c-icon7"><img src="{{ asset('assets/user/img/category/icon7.png') }}" alt="その他・アウトドア"></p>
                        <p class="c-icon8"><img src="{{ asset('assets/user/img/category/icon8.png') }}" alt="その他・インドア"></p>
                        <p class="c-icon9"><img src="{{ asset('assets/user/img/category/icon9.png') }}" alt="パソコン"></p>
                        <p class="c-icon10"><img src="{{ asset('assets/user/img/category/icon10.png') }}" alt="ペット散歩・ふれあい"></p>



                    </div>
                </div>

                <div class="inner_box">
                    <h3>募集タイトル（50文字まで）</h3>
                    <div class="input-text2">
                        <textarea placeholder="50文字入ります" cols="50" rows="10" maxlength="50" class="shadow-glay">テキストテキストテキスト</textarea>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>レッスン開始日時</h3>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                        <input type="date"  class="form_btn" value="2022-07-22">
                    </div>
                    <ul class="time"><li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="select" class="fourth">
                                    <option value="">10</option>
                                    <option value="">00</option>
                                    <option value="">00</option>
                                </select></div></li>
                        <li>：</li>
                        <li>	 <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="select" class="fourth">
                                    <option value="">10</option>
                                    <option value="">00</option>
                                    <option value="">00</option>
                                </select></div></li>
                        <li>～</li>
                        <li><div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="select" class="fourth">
                                    <option value="">11</option>
                                    <option value="">00</option>
                                    <option value="">00</option>
                                </select></div></li>
                        <li>：</li>
                        <li>	 <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="select" class="fourth">
                                    <option value="">11</option>
                                    <option value="">00</option>
                                    <option value="">00</option>
                                </select></div></li>
                    </ul>
                </div>

                <div class="inner_box">
                    <h3>レッスン時間</h3>
                    <ul class="time2"><li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="select" class="fourth">

                                    <option value="">30分</option>
                                    <option value="">00分</option>
                                </select></div></li>
                        <li>～</li>
                        <li>	 <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="select" class="fourth">

                                    <option value="">60分</option>
                                    <option value="">00分</option>
                                </select></div></li></ul>
                </div>

                <div class="inner_box">
                    <h3>参加人数</h3>
                    <ul class="select_flex flex_w50">
                        <li>
                            <div>男性</div>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="time">
                                    <option value="0">1</option>
                                    <option value="1">2</option>
                                </select>
                            </div>
                            <div>名</div>
                        </li>
                        <li>
                            <div>女性</div>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="time">
                                    <option value="0">1</option>
                                    <option value="1">2</option>
                                </select>
                            </div>
                            <div>名</div>
                        </li>
                    </ul>
                </div>

                <div class="inner_box">
                    <h3>希望金額</h3>
                    <ul class="time2"><li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="select" class="fourth">
                                    <option value="">下限なし</option>
                                    <option value="">30円</option>
                                    <option value="">00円</option>
                                </select></div></li>
                        <li>～</li>
                        <li>	 <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="select" class="fourth">
                                    <option value="">上限なし</option>
                                    <option value="">00円</option>
                                    <option value="">00円</option>
                                </select></div></li></ul>
                </div>

                <div class="inner_box">
                    <h3>レッスン場所</h3>
                    <p class="form_txt ptb10">レッスン場所を指定せずに相談を受付け</p>

                    <div class="form_wrap icon_form type_search">
                        <input type="text" value="" placeholder="キーワードで検索" class="search">
                    </div>

                    <div class="map">
                        <!-- 多分iframeになる予想 -->
                        <div class="dummy"></div>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>待ち合わせ場所の詳細（200文字まで）</h3>
                    <div class="input-text2">
                        <textarea placeholder="待ち合わせ場所の詳細を入力してください。" cols="50" rows="10" maxlength="200" class="shadow-glay">テキストテキストテキスト</textarea>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>募集詳細（1,000文字まで）</h3>
                    <div class="input-text2">
                        <textarea placeholder="" cols="50" rows="10" maxlength="1000" class="shadow-glay">テキストテキストテキスト</textarea>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>希望する性別</h3>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                        <select>
                            <option value="指定なし">指定なし</option>
                            <option value="女性">女性</option>
                            <option value="男性">男性</option>
                        </select>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>希望する年代</h3>
                    <ul class="time2"><li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="select" class="fourth">
                                    <option value="">指定なし</option>
                                    <option value="">18</option>
                                    <option value="">19</option>
                                </select></div></li>
                        <li>～</li>
                        <li>	 <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="select" class="fourth">
                                    <option value="">指定なし</option>
                                    <option value="">18</option>
                                    <option value="">19</option>
                                </select></div></li></ul>
                </div>

                <div class="inner_box">
                    <h3>募集期限</h3>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                        <input type="date"  class="form_btn" value="{{date("Y-m-d")}}">
                    </div>
                </div>



            </section>


            <div class="bk-white">
                <div class="button-area">
                    <div class="btn_base btn_orange shadow">
                        <button type="button" class="modal-syncer button-link" data-target="modal-post">変更する</button>
                    </div>
                    <div class="btn_base btn_pale-gray shadow">
                        <button type="button" class="modal-syncer button-link" data-target="modal-delete">削除する</button>
                    </div>
                    <div class="btn_base btn_white shadow">
                        <button type="button" class="modal-syncer button-link" data-target="modal-shitagaki2">下書きに保存</button>
                    </div>
                </div>
            </div>
        {{ Form::close() }}
    </div><!-- /contents -->

    <!-- モーダル部分 *********************************************************** -->
    <div class="modal-wrap completion_wrap">
        <div id="modal-post" class="modal-content">
            <div class="modal_body">
                <div class="modal_inner">
                    <h4 id="circle-orange_ttl">!</h4>
                    <h2 class="modal_ttl">
                        変更してよろしいですか？
                    </h2>

                    <div class="modal_txt">
                        <p>
                            この変更を行うと<br>
                            現在あなたに提案中のセンパイが<br>
                            全てリセットされます。
                        </p>
                    </div>
                </div>

                <div class="button-area">
                    <div class="btn_base btn_orange">
                        <button type="submit">変更</button>
                    </div>
                    <div class="btn_base btn_gray-line">
                        <a id="modal-close" class="button-link">キャンセル</a>
                    </div>
                </div>
            </div>
        </div>


        <!-- ********************************************************* -->
        <div class="modal-wrap completion_wrap">
            <div id="modal-shitagaki2" class="modal-content">

                <div class="modal_body completion">
                    <div class="modal_inner">
                        <h2 class="modal_ttl">
                            下書きを<br>
                            保存しました
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
            <div id="modal-delete" class="modal-content">

                <div class="modal_body">
                    <div class="modal_inner">
                        <h4 id="circle-orange_ttl">!</h4>
                        <h2 class="modal_ttl">
                            削除してよろしいですか？
                        </h2>

                        <div class="modal_txt">
                            <p>
                                削除を行うと、<br>
                                現在あなたに提案中のセンパイが<br>
                                全てリセットされます。
                            </p>
                        </div>
                    </div>

                    <div class="button-area">
                        <div class="btn_base btn_orange">
                            <button type="button" onClick="location.href='{{route("keijibann.recruiting_del_comp")}}'">削除</button>

                        </div>
                        <div class="btn_base btn_gray-line">
                            <a id="modal-close" class="button-link">キャンセル</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- モーダル部分 / ここまで ************************************************* -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

