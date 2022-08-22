@extends('user.layouts.app')

@section('title', 'レッスンを出品')

@section('content')
	<script src="{{ asset('assets/user/js/validate.js') }}"></script>

    @php
        use \App\Service\LessonService;
    @endphp

    @include('user.layouts.header_under')

    <!-- ************************************************************************
    本文
    ************************************************************************* -->
    <div id="contents" class="short">
    <!--main_-->
        {{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
            <input type="hidden" name="lesson_id" value="{{isset($lesson_info) ? $lesson_info['lesson_id'] : 0}}">
            <input type="hidden" name="lesson_type" value="0">
            <input type="hidden" id="lesson_state" name="lesson_state" value="0">
            <input type="hidden" id="old_filename" name="old_filename" value="{{isset($lesson_info) ? $lesson_info['lesson_image'] : ''}}">
            <section class="tab_area tab_white {{ !isset($lesson_info) ? 'hide' : '' }}">
                @if(isset($lesson_info))
                <div id="add-area">
                    @if(intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_PUBLIC)
                        <div class="from-approval">
                            <div class="hosoku">
                                <div class="pic"><img src="{{asset('assets/user/img/icon-caution.svg')}}"></div>
                                <div class="text">
                                    <p>
                                        イメージや文章を変更する場合は再度審査が必要です。<br>
                                        <small>※審査中であっても現在の内容でのレッスンを行うことは可能です。</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_CHECK)
                        <div class="from-judding">
                            <div class="readthis">
                                <p>
                                    現在このレッスンは審査中です。<br>
                                    審査中に変更申請を行うことはできません。
                                    審査終了までもうしばらくお待ちください。
                                </p>
                            </div>
                            <div class="hosoku">
                                <div class="pic"><img src="{{asset('assets/user/img/icon-caution.svg')}}"></div>
                                <div class="text">
                                    <p>イメージや文章を変更する場合は再度審査が必要です。<br>
                                        <small>※審査中であっても現在の内容でのレッスンを行うことは可能です。</small></p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_REJECT)
                        <div class="from-non-approval">
                            <div class="readthis">
                                <p>
                                    申請頂いた変更内容に不適切な箇所がありましたため、非承認となりました。<br>
                                    以下の点を修正し再度申請くださいませ。
                                </p>
                                <div class="explain">
                                    <p>説明文を管理画面で入力。<br>説明文を管理画面で入力。<br>説明文を管理画面で入力。<br>説明文を管理画面で入力。<br>説明文を管理画面で入力。</p>
                                </div>
                            </div>
                            <div class="hosoku">
                                <div class="pic"><img src="{{asset('assets/user/img/icon-caution.svg')}}"></div>
                                <div class="text">
                                    <p>
                                        イメージや文章を変更する場合は再度審査が必要です。<br>
                                        <small>※審査中であっても現在の内容でのレッスンを行うことは可能です。</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @endif
                <h3 class="hide">レッスン形式</h3>
                <div class="switch_tab hide">
                    <div class="radio-01">
                        <input type="radio" name="onof-line" id="off-line" checked="checked">
                        <label class="ok" for="off-line">対面レッスン型</label>
                    </div>
                    <div class="radio-02">
                        <input type="radio" name="onof-line" id="on-line" value="{{ route("user.syutupinn.online_edit") }}" onclick="location.href=this.value">
                        <label class="ok" for="on-line">オンライン型</label>
                    </div>
                </div>
            </section>

        <section class="slider_area {{ !isset($lesson_info) ? 'pt-30' : '' }}">
        <h3>レッスンイメージ</h3>
            <!-- Slider main container -->
            <div class="swiper-container for-warning">
                <div class="swiper-inner">
                    <div class="lesson_photo pb10">
                        <ol class="swiper-wrapper lesson_photo_list">
                            <!-- Slides -->
                            <?php $file_arr = array(); if(isset($lesson_info)) $file_arr = explode(',',$lesson_info['lesson_image']);?>
                            @for($i=1; $i<11; $i++)
                                <li class="swiper-slide">
                                    <div class="form_wrap shadow-glay">
                                        <label>
                                            <div class="photo_btn {{$i==1? 'must' : ''}}">
                                                @if(isset($lesson_info) && isset($file_arr[$i-1]))
                                                    <?php $filename = $file_arr[$i-1];?>
                                                    @if(file_exists(storage_path('app\public\lesson\\').$filename))
                                                        <img src="{{asset('storage/lesson/'.$filename)}}" style="height: 100%;">
                                                    @endif
                                                @endif
                                            </div>
                                            <input type="file" class="camera_mark" id="lesson_pic_{{$i}}" name="lesson_pic_{{$i}}" onchange="setPreviewPic(this);">
                                            <input type="hidden" id="file_name_{{$i}}" name="temp_file_name[]" value="{{isset($lesson_info) && file_exists(storage_path('app\public\lesson\\').$filename) ? 'old' : ''}}">
                                        </label>
                                    </div>
                                </li>
                            @endfor
                        </ol>
                    </div>
                </div>
                <p class="warning"></p>
            </div>
        </section>

    <section class="pb0">
        <div class="inner_box for-warning">
            <h3>カテゴリー</h3>
                <div class="form_wrap icon_form type_arrow_right shadow-glay">
                    <button type="button" id="lesson_class_name" name="lesson_class_name" onClick="showCategory();" class="form_btn">{{isset($lesson_info) ? $lesson_info['class_name'] : '指定なし'}}</button>
                    <input type="hidden" id="lesson_class_id" name="lesson_class_id" value="{{isset($lesson_info) ? $lesson_info['lesson_class_id'] : ''}}">
                    <input type="hidden" id="hlcn" name="hlcn" value="{{isset($lesson_info) ? $lesson_info['class_name'] : ''}}">
                </div>
            <p class="warning"></p>
        </div>

        <div class="inner_box for-warning">
            <h3>レッスンタイトル<small>（50文字まで）</small></h3>
            <div class="input-text2 lesson_ttl_textarea shadow-glay">
                <textarea id="lesson_title" name="lesson_title" placeholder="50文字入ります" maxlength="50">{{isset($lesson_info) ? $lesson_info['lesson_title'] : ''}}</textarea>
            </div>
            <p class="warning"></p>
        </div>

        <div class="inner_box">
            <h3>希望する性別</h3>
            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                <select name="lesson_wish_sex">
                    <option value="">選択してください</option>
                    <option value="">指定なし</option>
                    <option value="2" {{isset($lesson_info) && $lesson_info['lesson_wish_sex'] == 2 ? "selected='selected'" : ''}}>男性</option>
                    <option value="1" {{isset($lesson_info) && $lesson_info['lesson_wish_sex'] == 1 ? "selected='selected'" : ''}}>女性</option>
                </select>
            </div>
        </div>

        <div class="inner_box">
            <h3>希望する年代</h3>
            <ul class="select_flex">
                <li>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                        <select name="lesson_wish_minage">
                            <option value="">指定なし</option>
                            @foreach(range(10, 70, 10) as $val)
                                @php
                                    $age = '';
                                    if (isset($lesson_info) && $lesson_info['lesson_wish_minage']) {
                                        $age = (int)($lesson_info['lesson_wish_minage'] / 10);
                                        if ($age == 0) {
                                            $age = 10;
                                        } else if($age > 7) {
                                            $age = 70;
                                        } else {
                                            $age = $age * 10;
                                        }
                                    }
                                @endphp
                                <option value="{{$val}}" {{ old('lesson_wish_minage', $age) == $val ? 'selected' : '' }}>{{ $val.($val == 70 ? "代以上" : "代") }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>～</div>
                </li>
                <li>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                        <select name="lesson_wish_maxage">
                            <option value="">指定なし</option>
                            @foreach(range(10, 70, 10) as $val)
                                @php
                                    $age = '';
                                    if (isset($lesson_info) && $lesson_info['lesson_wish_maxage']) {
                                        $age = (int)($lesson_info['lesson_wish_maxage'] / 10);
                                        if ($age == 0) {
                                            $age = 10;
                                        } else if($age > 7) {
                                            $age = 70;
                                        } else {
                                            $age = $age * 10;
                                        }
                                    }
                                @endphp
                                <option value="{{$val}}" {{ old('lesson_wish_maxage', $age) == $val ? 'selected' : '' }}>{{ $val.($val == 70 ? "代以上" : "代") }}</option>
                            @endforeach
                        </select>
                    </div>
                </li>
            </ul>
        </div>

        <div class="inner_box check-box">
            <div class="clex-box_02">
                <input type="checkbox" onclick="setCheckBox(this)" name="lesson_accept_attend_request" value="{{isset($lesson_info) ? $lesson_info['lesson_accept_attend_request'] : 0}}" id="lesson_accept_attend_request" {{isset($lesson_info) && $lesson_info['lesson_accept_attend_request'] == 1 ? 'checked' : ''}}>
                <label for="lesson_accept_attend_request"><h3>出勤リクエストを受付する</h3></label>
            </div>
        </div>
        <div class="balloon balloon_blue mt0">
            <p>出動リクエストは最終出勤登録日の翌日以降について受け付けます。</p>
        </div>

        <div class="inner_box for-warning mt-30">
            <h3>最低レッスン時間</h3>
            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                <select id="lesson_min_hours" name="lesson_min_hours">
                    <option value="">選択してください</option>
                    @for($i = 1; $i < 25; $i++)
                    <option value="{{$i}}" {{isset($lesson_info) && $lesson_info['lesson_min_hours'] == $i ? "selected='selected'" : ''}}>{{$i}}時間</option>
                    @endfor
                </select>
            </div>
            <p class="warning"></p>
        </div>

        <div class="inner_box for-warning">
            <h3>30分あたりのレッスン料金</h3>
            <div class="input-text">
                <input type="text" id="lesson_30min_fees" name="lesson_30min_fees" min="1000" max="10000" class="w50 shadow-glay" value="{{isset($lesson_info) ? $lesson_info['lesson_30min_fees'] : ''}}">
                <span class="unit">円（1,000〜100,000円）</span>
            </div>
            <p class="warning"></p>
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
                            <select name="lesson_person_num">
                                @for($i = 1; $i < 101; $i++)
                                <option value="{{$i}}" {{isset($lesson_info) && $lesson_info['lesson_person_num'] == $i ? "selected='selected'" : ''}}>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </li>
                    <li>名以下</li>
                </ul>
                <div class="check-box">
                    <div class="clex-box_02 number_people">
                        <input type="checkbox" onclick="setCheckBox(this)" name="lesson_able_with_man" value="{{isset($lesson_info) ? $lesson_info['lesson_able_with_man'] : 0}}" id="lesson_able_with_man" {{isset($lesson_info) && $lesson_info['lesson_able_with_man'] == 1 ? 'checked' : ''}}>
                        <label for="lesson_able_with_man"><p>女性同伴で男性受付可</p></label>
                    </div>
                </div>
            </div>
        </div>

        <div class="inner_box">
            <h3>レッスン場所</h3>
            <div class="white_box">
                <div class="check-box">
                    <div class="clex-box_02">
                        <input type="checkbox" onclick="setCheckBox(this)" name="lesson_accept_without_map" value="{{isset($lesson_info) ? $lesson_info['lesson_accept_without_map'] : 0}}" id="lesson_accept_without_map" {{isset($lesson_info) && $lesson_info['lesson_accept_without_map'] == 1 ? 'checked' : ''}}>
                        <label for="lesson_accept_without_map"><p>レッスン場所を指定せずに相談を受付ける</p></label>
                    </div>
                </div>
                <div class="orange_msg">地図をタップして指定してください。</div>

                <div class="map">
                    <!-- 多分iframeになる予想 -->
                    <div class="dummy"></div>
                </div>

                <div class="form_wrap icon_form type_search mb30">
                    <input type="text" name="lesson_address_and_keyword" value="{{isset($lesson_info) ? $lesson_info['lesson_address_and_keyword'] : ''}}" placeholder="住所やキーワードを入力してください" class="search">
                </div>

                <h3>待ち合わせ場所の詳細<small>（100文字まで）</small></h3>
                <div class="input-text2">
                    <textarea id="lesson_pos_detail" name="lesson_pos_detail" placeholder="待ち合わせ場所の詳細を入力してください。" maxlength="100" class="shadow-glay count-text100">{{isset($lesson_info) ? $lesson_info['lesson_pos_detail'] : ''}}</textarea>
                    <p class="max_length"><span id="num100">0</span>／100</p>
                    <p class="warning"></p>
                </div>

                <div class="check-box">
                    <div class="clex-box_02 lesson_soudan">
                        <input type="checkbox" onclick="setCheckBox(this)" name="lesson_able_discuss_pos" value="{{isset($lesson_info) ? $lesson_info['lesson_able_discuss_pos'] : 0}}" id="lesson_able_discuss_pos" {{isset($lesson_info) && $lesson_info['lesson_able_discuss_pos'] == 1 ? 'checked' : ''}}>
                        <label for="lesson_able_discuss_pos"><p>レッスン場所の相談可</p></label>
                    </div>
                </div>

            </div>
        </div>

        <div class="inner_box">
            <h3>サービス詳細<small>（1,000文字まで）</small></h3>
            <div class="input-text2">
                <textarea name="lesson_service_details" placeholder="" cols="50" rows="10" maxlength="1000" class="shadow-glay count-text">{{isset($lesson_info) ? $lesson_info['lesson_service_details'] : ''}}</textarea>
                <p class="max_length"><span id="num">0</span>／1,000</p>
            </div>
        </div>

        <div class="inner_box">
            <h3>持ち物・その他の費用<small>（200文字まで）</small></h3>
            <div class="input-text2">
                <textarea name="lesson_other_details" placeholder="" cols="50" rows="10"  maxlength="200" class="shadow-glay count-text200">{{isset($lesson_info) ? $lesson_info['lesson_other_details'] : ''}}</textarea>
                <p class="max_length"><span id="num200">0</span>／200</p>
            </div>
        </div>

        <div class="inner_box">
            <h3>購入にあたってのお願い・注意事項<small>（200文字まで）</small></h3>
            <div class="input-text2">
                <textarea name="lesson_buy_and_attentions" placeholder="" cols="50" rows="10"  maxlength="200" class="shadow-glay count-text200-2">{{isset($lesson_info) ? $lesson_info['lesson_buy_and_attentions'] : ''}}</textarea>
                <p class="max_length"><span id="num200-2">0</span>／200</p>
            </div>
        </div>

        <div class="inner_box">
            <div class="kodawari_check">
                <h3>こだわり</h3>
                <div class="check-flex">
                    @foreach($lesson_cond as $k=>$v)
                        @php
                            $i = $k + 1;
                        @endphp
                        <div class="clex-box_01">
                            <input type="checkbox" onclick="setCheckBox(this)" name="lesson_cond_{{$i}}" value="{{isset($lesson_info) ? $lesson_info['lesson_cond_'.$i] : 0}}" id="lesson_cond_{{$i}}" {{isset($lesson_info) && $lesson_info['lesson_cond_'.$i] == 1 ? 'checked' : ''}}>
                            <label for="lesson_cond_{{$i}}"><p>{{$v['lc_name']}}</p></label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @if(isset($lesson_info) && (intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_PUBLIC || intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_CHECK || intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_REJECT))
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
        @endif

    </section>
    <section id="f-white_area">
        <div class="button-area mt20">
        @if(!isset($lesson_info))
            <div class="btn_base btn_orange shadow">
                <button type="button" class="btn-request" onclick="lessonSave({{LessonService::LESSON_STATE_CHECK}});">出品を申請する</button>
            </div>
            <div class="btn_base btn_pale-gray shadow">
                <button type="button" class="btn-draft" onclick="lessonSave({{LessonService::LESSON_STATE_DRAFT}});">下書きに保存</button>
            </div>
        @elseif(isset($lesson_info) && (intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_PUBLIC || intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_CHECK || intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_REJECT))
            <div class="btn_base btn_orange shadow">
                <button type="button"  class="btn-request"  onclick="lessonSave({{LessonService::LESSON_STATE_CHECK}});">変更を保存</button>
            </div>
            <div class="btn_base btn_pale-gray shadow">
                <button type="button" class="modal-syncer button-link" data-target="modal-delete">削除する</button>
            </div>
            <div class="btn_base btn_white shadow">
                <button type="button" class="button-link" onclick="lessonSave({{LessonService::LESSON_STATE_PRIVATE}})">非公開にする</button>
            </div>
        @elseif(isset($lesson_info) && (intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_DRAFT || intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_PRIVATE))
            <div class="btn_base btn_orange shadow">
                <button type="button" class="btn-request" onclick="lessonSave({{LessonService::LESSON_STATE_CHECK}});">出品を申請する</button>
            </div>
            <div class="btn_base btn_pale-gray shadow">
                <button type="button" class="btn-draft" onclick="lessonSave({{LessonService::LESSON_STATE_DRAFT}});">下書きに保存</button>
            </div>
            <div class="btn_base btn_white shadow">
                <button type="button" class="modal-syncer button-link"  data-target="modal-delete">削除する</button>
            </div>
        @endif
        </div>
    </section>

    {{--</form>--}}
    {{ Form::close() }}

    <div name="form2" id="form2" style="display: none">
    <!-- 大阪市 ************************************************** -->
    <section>

    <div class="white_box shadow-glay">

        <div class="ac-margin">
            <div class="check-list">
                @foreach($lesson_classes as $k=>$v)
                <div class="clex-box_02">
                    <input type="radio" name="ctg_radio" value="{{$v['class_id']}}" id="radio{{$v['class_id']}}" {{isset($lesson_info) && $lesson_info['lesson_class_id'] == $v['class_id'] ? 'checked' : ''}}>
                    <label for="radio{{$v['class_id']}}"><p><span class="ctg-nm" style="color:#414042">{{$v['class_name']}}</span>@if($v['cnt'] > 0)（{{$v['cnt']}}）@endif</p></label>
                </div>
                @endforeach
            </div>

        </div>

    </div>

    </section>
    <div style="width: 100%; bottom: 0px; left: 0; position: fixed; z-index: 98;">
        <div id="footer_button_area" class="result">
            <ul>
                <li>
                    <div class="btn_base btn_white clear_btn"><button onclick="cancelCategory();">クリア</button></div>
                </li>
                <li>
                    <div class="btn_base btn_white settei_btn"><button onclick="setLessonClass();">設定する</button></div>
                </li>
            </ul>
        </div>
    </div>
    </div>


    </div><!-- /contents -->

    <div class="modal-wrap completion_wrap">
        <div id="modal-error" class="modal-content">

            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl" id="error_msg">
                        レッスンを<br>
                        非公開にしました
                    </h2>
                </div>
            </div>

            <div class="button-area">
                <div class="btn_base btn_ok">
                    <a  class="modal-close button-link">OK</a>
                </div>
            </div>
        </div>
    </div>

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
                        <button type="button" onclick="LessonDel({{isset($lesson_info) ? $lesson_info['lesson_id'] : 0}})">削除</button>
                    </div>
                    <div class="btn_base btn_gray-line">
                        <a class="modal-close button-link">キャンセル</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if(isset($lesson_info) && (intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_PUBLIC || intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_CHECK || intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_REJECT))
        <!-- モーダル部分  販売手数料について :出品編集人時, 審議中編集人時*********************************************************** -->
        <div class="modal-wrap coupon_modal">
            <div id="modal-sales-commission" class="modal-content">
                <div class="modal_body">
                    <div class="close_btn_area">
                        <a  class="modal-close"><img src="{{asset('assets/user/img/x-mark.svg')}}" alt="閉じる"></a>
                    </div>
                    <div class="modal_inner">
                        <h2 class="modal_ttl">販売手数料について </h2>
                        <section class="case">
                            <h3 class="case_ttl_02">サブタイトル</h3>
                            <p class="modal_txt">テキスト</p>
                        </section>
                    </div>
                    <div class="button-area">
                        <div class="btn_base btn_ok">
                            <a  class="modal-close button-link">OK</a>
                        </div>
                    </div>
                </div><!-- /modal-content -->
            </div>
        </div>
        <!-- ********************************************************* -->
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
                        <a class="modal-close button-link">OK</a>
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
                        <a href="{{route("user.syutupinn.list")}}">OK</a>
                    </div>
                    <div class="btn_base btn_gray-line">
                        <a href="B-10_1.php">続けてオンライン型を出品</a>
                    </div>
                </div>
            </div>
        </div>
        <!--- モーダル部分 / ここまで  :出品編集人時, 審議中編集人時************************************************* -->
    @else
        <!-- モーダル部分  販売手数料について : 追加人時*********************************************************** -->
        <div class="modal-wrap coupon_modal">
            <div id="modal-sales-commission" class="modal-content">
                <div class="modal_body">
                    <div class="close_btn_area">
                        <a  class="modal-close"><img src="{{asset('assets/user/img/x-mark.svg')}}" alt="閉じる"></a>
                    </div>
                    <div class="modal_inner">
                        <h2 class="modal_ttl">販売手数料について</h2>
                        <section class="case">
                            <h3 class="case_ttl_02">サブタイトル</h3>
                            <p class="modal_txt">テキスト</p>
                        </section>
                    </div>
                    <div class="button-area">
                        <div class="btn_base btn_ok">
                            <a  class="modal-close button-link">OK</a>
                        </div>
                    </div>
                </div><!-- /modal-content -->
            </div>
            <!-- ********************************************************* -->
            <div class="modal-wrap completion_wrap">
                <div id="modal-shitagaki" class="modal-content">
                    <div class="modal_body completion">
                        <div class="modal_inner">
                            <h2 class="modal_ttl">
                                レッスンの下書きを<br>
                                保存しました
                            </h2>
                        </div>
                    </div>
                    <div class="button-area">
                        <div class="btn_base btn_ok">
                            <a href="{{route("user.syutupinn.list")}}"  class="button-link">OK</a>
                        </div>
                    </div>
                </div>
            </div>

            <iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
            <div class="modal-wrap completion_wrap">
                <div id="modal-listing" class="modal-content">
                    <div class="modal_body completion">
                        <div class="modal_inner">
                            <h2 class="modal_ttl">
                                出品申請を受け付けました。<br>
                                公開までしばらくお待ちください。
                            </h2>
                        </div>
                    </div>
                    <div class="button-area">
                        <div class="btn_base btn_orange">
                            <a href="{{route("user.syutupinn.list")}}">OK</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- モーダル部分 / ここまで : 追加人時************************************************* -->
    @endif
        <div id="modal-overlay2" style="display: none;"></div>

    <footer>
        @include('user.layouts.fnavi')
    </footer>

    <script>
        var LESSON_STATE_DRAFT = {{LessonService::LESSON_STATE_DRAFT}};
        var LESSON_STATE_PRIVATE = {{LessonService::LESSON_STATE_PRIVATE}};
        var LESSON_STATE_CHECK = {{LessonService::LESSON_STATE_CHECK}};
        var LESSON_STATE_PUBLIC = {{LessonService::LESSON_STATE_PUBLIC}};
        var LESSON_STATE_REJECT = {{LessonService::LESSON_STATE_REJECT}};
        $(document).ready(function () {
            removeWarning();
            if($('#hlcn').val() != ''){
                $('#lesson_class_name').html($('#hlcn').val());
            }
            $('.camera_mark').change(function () {
                $(this).next('input').val($(this).val());
            });

            $('.modal-close').click(function () {
                $('.modal-wrap').fadeOut();
                $(this).parents('.modal-content').fadeOut();
                $('#modal-overlay2').fadeOut();
            });
        });
        File.prototype.convertToBase64 = function(callback){
            var reader = new FileReader();
            reader.onloadend = function (e) {
                callback(e.target.result, e.target.error);
            };
            reader.readAsDataURL(this);
        };
        function setPreviewPic(obj) {
            var selectedFile = obj.files[0];
            selectedFile.convertToBase64(function(base64){
                var html = '<img src="'+base64+'" style="height: 100%;">';
                $(obj).parent().children('div').html(html);
            })
        }

        function showCategory() {
            $('#form1').hide();
            $('#form2').show();
            $('footer').hide();
            $('.header_area h1').html("カテゴリーを選択")
        }

        function cancelCategory(){
            $('#form1').show();
            $('#form2').hide();
            $('footer').show();
            $('.header_area h1').html("レッスンを出品");
        }

        function setLessonClass() {
            if($("input[name='ctg_radio']:checked").val() > 0){
                var lesson_class_name = $("input[name='ctg_radio']:checked").parent().children('label').children('p').children('span').html();
                var lesson_class_id = $("input[name='ctg_radio']:checked").val();
                $('#lesson_class_name').html(lesson_class_name);
                $('#lesson_class_id').val(lesson_class_id);
                $('#hlcn').val(lesson_class_name);
                $('#form1').show();
                $('#form2').hide();
                $('footer').show();
                $('.header_area h1').html("レッスンを出品");
                $('#lesson_class_id').parents('.for-warning').children('.warning').html('');
            }else{
                var msg = 'カテゴリーを<br>選択してください。';
                $('.modal-wrap').fadeIn();
                $('#modal-error #error_msg').html(msg);
                $('#modal-error').fadeIn();
                $('#modal-overlay2').fadeIn();
            }
        }

        function setCheckBox(obj) {
            var val = $(obj).val();
            if(val == 0){
                $(obj).val(1);
            }else{
                $(obj).val(0);
            }
        }

        function validate() {
            var is_error = false;
            if( !checkRequire($("#file_name_1"), "レッスンイメージ")) is_error = true;
            if( !checkSelect($("#lesson_class_id"), "カテゴリー")) is_error = true;
            if( !checkRequire($("#lesson_title"), "レッスンタイトル")) is_error = true;
            if( !checkSelect($("#lesson_min_hours"), "最低レッスン時間")) is_error = true;
            if( !checkRequire($("#lesson_30min_fees"), "30分あたりのレッスン料金")) is_error = true;
            if( !checkInteger($("#lesson_30min_fees"), "30分あたりのレッスン料金")) is_error = true;
            if( !checkMin($("#lesson_30min_fees"), "30分あたりのレッスン料金", 1000)) is_error = true;
            if( !checkMax($("#lesson_30min_fees"), "30分あたりのレッスン料金", 10000)) is_error = true;
            return is_error;
        }

        function lessonSave(state) {
            $('#lesson_state').val(state);
            if(!validate()){
                var form_data = new FormData($('#form1').get(0));
                form_data.append("_token", "{{csrf_token()}}");
                $.ajax({
                    type: "post",
                    url: '{{ route('user.syutupinn.save_lesson') }}',
                    data: form_data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        if ( result.state == 'success' ) {
                            $('.modal-wrap').fadeIn();
                            if(state == LESSON_STATE_CHECK){
                                @if(isset($lesson_info) && (intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_PUBLIC || intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_CHECK || intval($lesson_info['lesson_state']) == LessonService::LESSON_STATE_REJECT))
                                    $('#modal-edit-save').fadeIn();
                                @else
                                    $('#modal-listing').fadeIn();
                                @endif
                            }
                            if(state == LESSON_STATE_PRIVATE){
                                $('#modal-hikoukai').fadeIn();
                            }
                            if(state == LESSON_STATE_DRAFT){
                                $('#modal-shitagaki').fadeIn();
                            }
                            $('#modal-overlay2').fadeIn();
                        }else{
                            var msg = '';
                            if(state == LESSON_STATE_CHECK)
                                msg = '出品申請が<br>失敗しました。';
                            if(state == LESSON_STATE_PRIVATE)
                                msg = '非公開保存が<br>失敗しました。';
                            if(state == LESSON_STATE_DRAFT)
                                msg = '保存が失敗しました。';
                            $('.modal-wrap').fadeIn();
                            $('#modal-error #error_msg').html(msg);
                            $('#modal-error').fadeIn();
                            $('#modal-overlay2').fadeIn();
                        }
                    },
                });
            }

        }

        function LessonDel(lesson_id){
            $.ajax({
                type: "post",
                url: '{{ route('user.syutupinn.del_lesson') }}',
                data: {lesson_id: lesson_id, _token: '{{csrf_token()}}'},
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (result) {
                    if ( result.state == 'success' ) {
                        location.href = '{{route('user.syutupinn.list')}}';
                    }else{
                        var msg = '削除が<br>失敗しました。';
                        $('.modal-wrap').fadeIn();
                        $('#modal-error #error_msg').html(msg);
                        $('#modal-error').fadeIn();
                        $('#modal-overlay2').fadeIn();
                    }
                },
            });
        }

        function removeWarning(){
            $('.for-warning input').keydown(function(){
                $(this).parents('.for-warning').children('.warning').html('');
            });
            $('.for-warning input').change(function(){
                $(this).parents('.for-warning').children('.warning').html('');
            });
            $('.for-warning select').change(function(){
                $(this).parents('.for-warning').children('.warning').html('');
            });
            $('.for-warning textarea').keydown(function(){
                $(this).parents('.for-warning').children('.warning').html('');
            });
        }

    </script>

@endsection

