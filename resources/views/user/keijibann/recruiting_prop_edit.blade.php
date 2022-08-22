@extends('user.layouts.app')

@section('title', '提案内容の変更')

@section('content')

    @include('user.layouts.header_under')

    <div id="contents">
        {{ Form::open(["method"=>"post"]) }}

            <section class="propose">
                <div class="white_box pt20">
                    <span class="choice_lesson">この内容に提案中</span>
                    <h3 class="prof-title">ランニングでダイエットしませんか？<br>タイトルが入りますタイト</h3>

                    <ul class="teacher_info_02">
                        <li class="icon"><img src="{{ asset('assets/user/img/icon_photo_01.png') }}" class="プロフィールアイコン"></li>
                        <li class="about_teacher">
                            <div class="profile_name"><p>あやの<span>（24）女性</span></p></div>
                            <div class="honnin_kakunin"><p>本人確認済み</p></div>
                            <div class="t_right pt10">
                                <p class="orange_link icon_arrow orange_right">
                                    <a href="A-14.php">プロフィール</a>
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>


            </section>

            <section>
                <div class="inner_box">
                    <ul class="list_box">
                        <li>
                            <div>
                                <p class="prof-title2">提案期限</p>
                                <p class="limit_txt">1<small>月</small>23<small>日</small> 19:00</p>
                            </div>
                            <div class="yosan">
                                <p>（予算 2,000円〜4,000円）</p>
                            </div>
                            <div >
                                <p class="prof-title2">提案金額</p>
                                <div class="input-text teian_box">
                                    <input type="text" name="name" class="w70 propose-money shadow-glay" value="3,500">円
                                </div>
                            </div>
                        </li>
                    </ul>

                    <ul class="list_box line_top_bottom">
                        <li>
                            <div>
                                <p class="normal">現在の手数料率
                                </p>
                                <p>C</p>
                            </div>
                            <div>
                                <p class=" normal">売上金（目安）</p>
                                <p class="price_mark">2,800</p>
                            </div>
                        </li>
                    </ul>
                    <div class="balloon balloon_blue">
                        <p>レッスン料金には所定の販売手数料がかかります。</p>
                    </div>
                    <p class="modal-link modal-link_blue">
                        <a class="modal-syncer button-link" data-target="modal-sales-commission">
                            販売手数料について
                        </a>
                    </p>

                    <ul class="list_box">
                        <li>
                            <div>
                                <p class="prof-title2">提案日時</p>
                                <p class="limit_txt">1<small>月</small>23<small>日</small></p>
                            </div>


                        </li>
                    </ul>

                    <ul class="select_flex">
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="time">
                                    <option value="00">00</option>
                                </select>
                            </div>
                            <div>：</div>
                        </li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="time">
                                    <option value="00">00</option>
                                </select>
                            </div>
                            <div>～00:00</div>
                        </li>
                    </ul>
                    <div class="balloon balloon_blue">
                        <p>19:00〜20:00の範囲で入力してください。</p>
                    </div>
                </div>


                <div class="inner_box">
                    <h3>メッセージ<small>（任意）</small></h3>
                    <div class="input-text2">
                        <textarea placeholder="アピールしたいポイントや提案の具体的な内容を入力すると成約率が上がります。" cols="50" rows="10" class="count-text shadow-glay"></textarea>
                        <p class="max_length"><span id="num">0</span>／1,000</p>
                    </div>

                    <h3>購入期限</h3>
                    <div class="form_txt gray_txt type_top_10">
                        <p>（募集期限 00月00日00:00〜）</p>
                    </div>
                    <ul class="select_float_box half_box select_area">
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="month">
                                    <option value="00">00</option>
                                </select>
                            </div>
                            <div>月</div>
                        </li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="day">
                                    <option value="00">00</option>
                                </select>
                            </div>
                            <div>日</div>
                        </li>
                    </ul>
                    <ul class="select_float_box half_box select_area pt10">
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="time">
                                    <option value="00">00</option>
                                </select>
                            </div>
                            <div>：</div>
                        </li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="time">
                                    <option value="00">00</option>
                                </select>
                            </div>
                            <div>まで</div>
                        </li>
                    </ul>

                </div>


            </section>
            <div id="footer_button_area" class="under_area">
                <ul>
                    <li class="send-request">
                        <div class="btn_base btn_orange">
                            <a href="{{route('keijibann.confirm')}}">提案内容を変更する</a>
                        </div>
                    </li>
                </ul>
            </div>
        {{ Form::close() }}
    </div><!-- /contents -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

