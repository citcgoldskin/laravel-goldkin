@extends('user.layouts.app')

@section('title', 'レッスン出品')

@section('content')

    @include('user.layouts.header_under')

    @php
	$old_files = isset($lesson_info) && is_array($lesson_info['lesson_image'])  ? implode(',', $lesson_info['lesson_image']) : '';
@endphp
<!-- ************************************************************************
本文
************************************************************************* -->
<div id="contents" class="short">
<!--main_-->
	{{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
		<input type="hidden" name="lesson_id" value="{{isset($lesson_info) ? $lesson_info['lesson_id'] : 0}}">
		<input type="hidden" name="lesson_type" value="{{config('const.lesson_type.consult')}}">
		<input type="hidden" id="lesson_state" name="lesson_state" value="{{ config('const.lesson_state.draft')}}">
		<input type="hidden" id="old_filename" name="old_filename" value="{{$old_files}}">
		<section class="tab_area tab_white {{ !isset($lesson_info) ? 'hide' : '' }}">
			@if(isset($lesson_info))
			<div id="add-area">
				@if(intval($lesson_info['lesson_state']) == config('const.lesson_state.public'))
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
				@elseif(intval($lesson_info['lesson_state']) == config('const.lesson_state.check'))
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
				@elseif(intval($lesson_info['lesson_state']) == config('const.lesson_state.reject'))
					<div class="from-non-approval">
						<div class="readthis">
							<p>
								申請頂いた変更内容に不適切な箇所がありましたため、非承認となりました。<br>
								以下の点を修正し再度申請くださいませ。
							</p>
							<div class="explain">
								<p>{{$lesson_info['lesson_reject_reason']}}</p>
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
					<input type="radio" name="onof-line" id="on-line" value="{{route("user.syutupinn.regLesson")}}/{{config('const.lesson_type.online')}}" onclick="location.href=this.value">
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
                            <?php $file_arr = array(); if(isset($lesson_info) && is_array($lesson_info['lesson_image'])) $file_arr = $lesson_info['lesson_image'];?>
                            @for($i=1; $i<11; $i++)
                                <li class="swiper-slide">
                                    <div class="form_wrap shadow-glay">
                                        <label>
                                            <div class="photo_btn {{$i==1? 'must' : ''}}">
                                                @if(isset($lesson_info) && isset($file_arr[$i-1]) && $file_arr[$i-1] != '')
                                                    <?php $filename = $file_arr[$i-1];?>
                                                        <img src="{{\App\Service\CommonService::getLessonImgUrl($filename)}}" style="height: 100%;">
                                                @else
                                                    <?php $filename = NULL;?>
                                                @endif
                                            </div>
                                            <input type="file" class="camera_mark" id="lesson_pic_{{$i}}" name="lesson_pic_{{$i}}" onchange="set_preview_pic(this);">
                                            @php
                                                $sign = '';
                                                $path = storage_path('app\public\lesson\\').$filename;
                                                if(isset($lesson_info) && is_file($path) && file_exists($path))
                                                    $sign =  'old';
                                            @endphp
                                            <input type="hidden" id="file_name_{{$i}}" name="temp_file_name[]" value="{{$sign}}">
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
                        <button type="button" id="lesson_class_name" name="lesson_class_name" onClick="show_category();" class="form_btn">{{isset($lesson_info) && $lesson_info['lesson_class'] ? $lesson_info['lesson_class']['class_name'] : '指定なし'}}</button>
                        <input type="hidden" id="lesson_class_id" name="lesson_class_id" value="{{isset($lesson_info) ? $lesson_info['lesson_class_id'] : ''}}">
                        <input type="hidden" id="hlcn" name="hlcn" value="{{isset($lesson_info) && $lesson_info['lesson_class'] ? $lesson_info['lesson_class']['class_name'] : ''}}">
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
                        <option value="{{config('const.sex.man')}}" {{isset($lesson_info) && $lesson_info['lesson_wish_sex'] == config('const.sex.man') ? "selected='selected'" : ''}}>男性</option>
                        <option value="{{config('const.sex.woman')}}" {{isset($lesson_info) && $lesson_info['lesson_wish_sex'] == config('const.sex.woman') ? "selected='selected'" : ''}}>女性</option>
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
                    <input type="checkbox" onclick="set_check_box(this)" name="lesson_accept_attend_request" value="{{isset($lesson_info) ? $lesson_info['lesson_accept_attend_request'] : 0}}" id="lesson_accept_attend_request" {{isset($lesson_info) && $lesson_info['lesson_accept_attend_request'] == 1 ? 'checked' : ''}}>
                    <label for="lesson_accept_attend_request"><h3>出勤リクエストを受付する</h3></label>
                </div>
            </div>

            <div class="balloon balloon_blue mt0">
                <p>出動リクエストは最終出勤登録日の翌日以降について受け付けます。</p>
            </div>

            <div class="inner_box for-warning mt30">
                <h3>最低レッスン時間</h3>
                <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                    <select id="lesson_min_hours" name="lesson_min_hours">
                        <option value="">選択してください</option>
                        @for($i = 15; $i <= 300; $i+=15)
                        <option value="{{$i}}" {{isset($lesson_info) && $lesson_info['lesson_min_hours'] == $i ? "selected='selected'" : ''}}>{{$i}}分</option>
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
                <a class="button-link" data-target="modal-sales-commission" onclick="open_target_modal(this)">販売手数料について</a>
            </p>

            <div class="inner_box">
                <h3>対応人数</h3>
                <div class="white_box">
                    <ul class="small_select bb">
                        <li>最大</li>
                        <li class="ma-both">
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select name="lesson_person_num" id="lesson_person_num">
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
                            <input type="checkbox" onclick="set_check_box(this)" name="lesson_able_with_man" value="{{isset($lesson_info) ? $lesson_info['lesson_able_with_man'] : 0}}" id="lesson_able_with_man" {{isset($lesson_info) && $lesson_info['lesson_able_with_man'] == 1 ? 'checked' : ''}} {{ !isset($lesson_info) || (isset($lesson_info) && $lesson_info['lesson_person_num'] == 1) ? 'disabled' : '' }}>
                            <label for="lesson_able_with_man"><p>女性同伴で男性受付可</p></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="inner_box">
                <h3>レッスン場所</h3>
                <div class="white_box">
                    <input type="hidden" id="lesson_latitude" name="lesson_latitude" value="0">
                    <input type="hidden" id="lesson_longitude" name="lesson_longitude" value="0">
                    {{--<input type="hidden" id="lesson_area_ids" name="lesson_area_ids" value="40">--}}
                    {{--<input type="hidden" id="lesson_map_address" name="lesson_map_address" value="福岡県">--}}
                    <div class="check-box">
                        <div class="clex-box_02">
                            <input type="checkbox" onclick="set_check_box(this, 'lesson_accept_without_map')" name="lesson_accept_without_map" value="{{isset($lesson_info) ? $lesson_info['lesson_accept_without_map'] : 0}}" id="lesson_accept_without_map" {{isset($lesson_info) && $lesson_info['lesson_accept_without_map'] == 1 ? 'checked' : ''}}>
                            <label for="lesson_accept_without_map"><p>レッスン場所を指定せずに相談を受付ける</p></label>
                        </div>
                    </div>
                    <div class="input-map-address-area {{ isset($lesson_info) && $lesson_info['lesson_accept_without_map'] == 1 ? 'hide' : '' }}">
                        <div class="orange_msg">地図をタップして指定してください。</div>

                        <div id="floating-panel">
                            <input id="latlng" type="textbox" value="">
                        </div>
                        <div id="map" class="{{ isset($lesson_info) && $lesson_info['lesson_accept_without_map'] == 1 ? 'action-disable' : '' }}">
                        </div>

                        <div class="form_wrap icon_form type_search mb30">
                            <input type="text" id="lesson_address_and_keyword" name="lesson_address_and_keyword" value="{{isset($lesson_info) ? $lesson_info['lesson_address_and_keyword'] : ''}}" placeholder="住所やキーワードを入力してください" class="search controls">
                        </div>

                        @php
                            $lesson_areas = old('tags', isset($lesson_info) && is_object($lesson_info) && $lesson_info->lesson_area ? $lesson_info->lesson_area : []);
                        @endphp
                        <div class="select-wrap mb30">
                            <label class="for-warning">
                                <select class="multi-select-tags" name="tags[]" id="tags" multiple="multiple">
                                    @if( count($lesson_areas) > 0)
                                        @foreach($lesson_areas as $lesson_area)
                                            @php
                                                $position = json_decode($lesson_area['position']);
                                            @endphp
                                            @if($position && $position->prefecture)
                                                <option data-lat="{{ $position->lat }}" data-lng="{{ $position->lng }}" data-prefecture="{{ $position->prefecture }}" data-county="{{ $position->county }}" data-locality="{{ $position->locality }}" data-sublocality="{{ $position->sublocality }}" selected>{{ $position->prefecture.$position->county.$position->locality.$position->sublocality }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        <optgroup label="選択されたデータが存在しません。">
                                        </optgroup>
                                    @endif
                                </select>
                                <p class="warning"></p>
                            </label>
                        </div>

                        <h3>待ち合わせ場所の詳細<small>（100文字まで）</small></h3>
                        <div class="input-text2">
                            <textarea id="lesson_pos_detail" name="lesson_pos_detail" placeholder="待ち合わせ場所の詳細を入力してください。" maxlength="100" class="shadow-glay count-text100">{{isset($lesson_info) ? $lesson_info['lesson_pos_detail'] : ''}}</textarea>
                            <p class="max_length"><span id="num100">0</span>／100</p>
                            <p class="warning"></p>
                        </div>
                    </div>

                    <div class="check-box">
                        <div class="clex-box_02 lesson_soudan">
                            <input type="checkbox" onclick="set_check_box(this, 'lesson_able_discuss_pos')" name="lesson_able_discuss_pos" value="{{isset($lesson_info) ? $lesson_info['lesson_able_discuss_pos'] : 0}}" id="lesson_able_discuss_pos" {{isset($lesson_info) && $lesson_info['lesson_able_discuss_pos'] == 1 ? 'checked' : ''}}>
                            <label for="lesson_able_discuss_pos"><p>レッスン場所の相談可</p></label>
                        </div>
                    </div>

                    <div class="input-discuss-pos-area {{ old('lesson_able_discuss_pos', isset($lesson_info) && $lesson_info['lesson_able_discuss_pos'] ? $lesson_info['lesson_able_discuss_pos'] : '' ) == 1 ? '' : 'hide' }}" style="margin-top: 20px;">
                        <div style="margin-bottom: 20px;" data-target="modal-province" class="modal-syncer lesson-discuss-province">
                            <h3>都道府県</h3>
                            <input type="hidden" name="province_id" id="province_id" value="">
                            <div class="form_wrap icon_form type_arrow_right shadow-glay">
                                <button type="button"
                                    {{--onClick="location.href='{{route('keijibann.province', ['prev_url_id' => 5])}}'"--}}
                                    class="form_btn btn_province">
                                    ご希望の都道府県を選択してください
                                </button>
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;" data-target="modal-area" class="modal-syncer lesson-discuss-area action-disable">
                            <h3>エリア</h3>
                            <input type="hidden" name="area_id" id="area_id" value="">
                            <div class="form_wrap icon_form type_arrow_right shadow-glay">
                                <button type="button"
                                        {{--onClick="location.href='{{route('keijibann.area', ['province_id' => $province_id])}}'"--}}
                                        class="form_btn btn_area">
                                        指定なし
                                </button>
                            </div>
                        </div>

                        @php
                            $lesson_discuss_areas = old('discuss_tags', isset($lesson_info) && is_object($lesson_info) && $lesson_info->lesson_discuss_area ? $lesson_info->lesson_discuss_area : []);
                        @endphp
                        <div class="select-wrap mb30">
                            <label class="for-warning">
                                <select class="multi-select-discuss-tags" name="discuss_tags[]" id="discuss_tags" multiple="multiple">
                                    @if( count($lesson_discuss_areas) > 0)
                                        @foreach($lesson_discuss_areas as $lesson_area)
                                            @php
                                                $position = json_decode($lesson_area['position']);
                                            @endphp
                                            @if($position && $position->area_name)
                                                <option data-prefecture="{{ $position->prefecture }}" data-area="{{ $position->area_id }}" selected>{{ $position->area_name }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        <optgroup label="選択されたデータが存在しません。">
                                        </optgroup>
                                    @endif
                                </select>
                                <p class="warning"></p>
                            </label>
                        </div>

                        <h3>レッスン相談場所の詳細<small>（100文字まで）</small></h3>
                        <div class="input-text2">
                            <textarea id="lesson_discuss_pos_detail" name="lesson_discuss_pos_detail" placeholder="相談場所の詳細を入力してください。" maxlength="100" class="shadow-glay count-text100">{{isset($lesson_info) ? $lesson_info['lesson_discuss_pos_detail'] : ''}}</textarea>
                            <p class="max_length"><span id="num100">0</span>／100</p>
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
                        @if($lesson_cond->count())
                            @foreach($lesson_cond as $k=>$v)
                                @php
                                    $i = $k + 1;
                                @endphp
                                <div class="clex-box_01">
                                    <input type="checkbox" onclick="set_check_box(this)" name="lesson_cond_{{$i}}" value="{{isset($lesson_info) ? $lesson_info['lesson_cond_'.$i] : 0}}" id="lesson_cond_{{$i}}" {{isset($lesson_info) && $lesson_info['lesson_cond_'.$i] == 1 ? 'checked' : ''}}>
                                    <label for="lesson_cond_{{$i}}"><p>{{$v['lc_name']}}</p></label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            {{--@if(isset($lesson_info) && (intval($lesson_info['lesson_state']) == config('const.lesson_state.public') || intval($lesson_info['lesson_state']) == config('const.lesson_state.check') || intval($lesson_info['lesson_state']) == config('const.lesson_state.reject')))--}}
                <div class="inner_box">
                    <h3 class="must">クーポンの適用</h3>
                    <div class="form_wrap">
                        <ul class="radio-box radio_mark">
                            <li>
                                <input type="radio" name="lesson_coupon" value="1" id="coupon-1" {{isset($lesson_info) && $lesson_info['lesson_coupon'] == 1 ? 'checked' : ''}}>
                                <label for="coupon-1">適用する</label>
                            </li>
                            <li>
                                <input type="radio" name="lesson_coupon" value="0" id="coupon-2" {{isset($lesson_info) ? $lesson_info['lesson_coupon'] == 0 ? 'checked' : '' : 'checked';}}>
                                <label for="coupon-2">適用しない</label>
                            </li>
                        </ul>
                    </div>
                    <p class="coupon_hoyuu">
                        あなたが保有しているクーポンは<a href="{{ route('user.myaccount.coupon_intro') . '/1' }}">こちら</a>
                    </p>
                </div>
            {{--@endif--}}

        </section>
        <section id="f-white_area">
            <div class="button-area mt20">
            @if(!isset($lesson_info))
                <div class="btn_base btn_orange shadow">
                    <button type="button" class="btn-request" onclick="lesson_save({{config('const.lesson_state.check')}});">出品を申請する</button>
                </div>
                <div class="btn_base btn_pale-gray shadow">
                    <button type="button" class="btn-draft" onclick="lesson_save({{config('const.lesson_state.draft')}});">下書きに保存</button>
                </div>
            @elseif(isset($lesson_info) && (intval($lesson_info['lesson_state']) == config('const.lesson_state.public') || intval($lesson_info['lesson_state']) == config('const.lesson_state.check') || intval($lesson_info['lesson_state']) == config('const.lesson_state.reject')))
                <div class="btn_base btn_orange shadow">
                    <button type="button"  class="btn-request"  onclick="lesson_save({{config('const.lesson_state.check')}});" data-target="modal-edit-save">変更を保存</button>
                </div>
                <div class="btn_base btn_pale-gray shadow">
                    <button type="button" class="button-link" onclick="open_modal({{config('const.lesson_state.delete')}})">削除する</button>
                </div>
                <div class="btn_base btn_white shadow">
                    <button type="button" class="button-link" onclick="lesson_save({{config('const.lesson_state.private')}})">非公開にする</button>
                </div>
            @elseif(isset($lesson_info) && (intval($lesson_info['lesson_state']) == config('const.lesson_state.draft') || intval($lesson_info['lesson_state']) == config('const.lesson_state.private')))
                <div class="btn_base btn_orange shadow">
                    <button type="button" class="btn-request" onclick="lesson_save({{config('const.lesson_state.check')}});" data-target="modal-edit-save">出品を申請する</button>
                </div>
                <div class="btn_base btn_pale-gray shadow">
                    <button type="button" class="btn-draft" onclick="lesson_save({{config('const.lesson_state.draft')}});">下書きに保存</button>
                </div>
                <div class="btn_base btn_white shadow">
                    <button type="button" class="button-link" onclick="open_modal({{config('const.lesson_state.delete')}})">削除する</button>
                </div>
            @endif
            </div>
        </section>
    {{ Form::close() }}

    <div name="form2" id="form2" style="display: none">
    <!-- 大阪市 ************************************************** -->
    <section>

        <div class="white_box shadow-glay">

        <div class="ac-margin">
            <div class="check-list">
                @if($lesson_classes->count())
                    @foreach($lesson_classes as $k=>$v)
                        <div class="clex-box_02">
                            <input type="radio" name="ctg_radio" value="{{$v['class_id']}}" id="radio{{$v['class_id']}}" {{isset($lesson_info) && $lesson_info['lesson_class_id'] == $v['class_id'] ? 'checked' : ''}}>
                            <label for="radio{{$v['class_id']}}"><p><span class="ctg-nm" style="color:#414042">{{$v['class_name']}}</span>（{{$v['cnt']}})</p></label>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>

    </div>

    </section>
    <div style="width: 100%; bottom: 0px; left: 0; position: fixed; z-index: 98;">
        <div id="footer_button_area" class="result">
            <ul>
                <li>
                    <div class="btn_base btn_white clear_btn"><button onclick="clear_category();">クリア</button></div>
                </li>
                <li>
                    <div class="btn_base btn_white settei_btn"><button onclick="set_lesson_class();">設定する</button></div>
                </li>
            </ul>
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
					<button type="button" onclick="Lesson_del({{isset($lesson_info) ? $lesson_info['lesson_id'] : 0}})">削除</button>
				</div>
				<div class="btn_base btn_gray-line">
					<a class="modal-close button-link">キャンセル</a>
				</div>
			</div>

		</div>
	</div>
</div>

    {{----}}

    <div class="modal-wrap coupon_modal">
        <div id="modal-province" class="modal-content ajax-modal-container">
        </div>
    </div>
    <div class="modal-wrap coupon_modal">
        <div id="modal-area" class="modal-content ajax-modal-container">
        </div>
    </div>

@if(isset($lesson_info) && (intval($lesson_info['lesson_state']) == config('const.lesson_state.public') || intval($lesson_info['lesson_state']) == config('const.lesson_state.check') || intval($lesson_info['lesson_state']) == config('const.lesson_state.reject')))
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
                        <h3 class="case_ttl_02">販売手数料とは？</h3>
                        <p class="modal_txt">販売手数料はレッスン料金の7〜20％となります。<br>手数料率は以下の基準により分類されます。</p>
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
					<a href="{{route("user.syutupinn.lesson_list")}}" class="modal-close button-link">OK</a>
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
					<a href="{{route("user.syutupinn.lesson_list")}}">OK</a>
				</div>
				<div class="btn_base btn_gray-line">
					<a href="{{route("user.syutupinn.regLesson")}}">続けてレッスンを出品</a>
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
                    <h2 class="modal_ttl">販売手数料について </h2>
                    <section class="case">
                        <h3 class="case_ttl_02">販売手数料とは？</h3>
                        <p class="modal_txt">販売手数料はレッスン料金の7〜20％となります。<br>手数料率は以下の基準により分類されます。</p>
                        <div class="rate">
                            <strong>料率A</strong>
                            <div class="rate-content">レッスンの最低販売手数料であり、<br>金額は150円です。</div>
                        </div>
                        <div class="rate">
                            <strong>料率B</strong>
                            <div class="rate-content">同一の後輩と14日以内に<br>再び行ったレッスンに適用され、<br>手数料はレッスン料金の7％です。</div>
                        </div>
                        <div class="rate">
                            <strong>料率C</strong>
                            <div class="rate-content">後輩との初回レッスンや、<br>同一の後輩と前回レッスンから15日以降に<br>行ったレッスンに適用され、手数料は<br>レッスン料金の20％です。</div>
                        </div>
                        <div class="rate-other">
                            <p>※料率Aは料率Bまたは料率Cが150円を下回った場合にのみ適用されます。</p>
                            <p>※交通費はレッスン料金とは分離し、一律7％の手数料となります。</p>
                        </div>
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
						<a href="{{route("user.syutupinn.lesson_list")}}"  class="button-link">OK</a>
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
						<a href="{{route("user.syutupinn.lesson_list")}}">OK</a>
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
@endsection

@section('page_css')
    <style>
        .rate-content {
            padding-left: 20px;
        }
        .rate-other {
            font-size: 13px;
            padding-top: 10px;
        }
        .rate {
            font-size: 13px;
            padding-top: 10px;
        }
        .kodawari_check {
            /*margin-top: 0px;*/
            padding-top: 0px;
        }
        .select2-container--default {
            width: 100% !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #F1F9FF;
            border: 1px solid #BCE0FD;
            color: #5BBEEA;
            border-radius: 4px;
            cursor: default;
            float: left;
            margin-right: 5px;
            margin-top: 5px;
            padding: 0 5px;
            font-size: 14px;
        }
        .action-disable {
            opacity: 0.5;
        }
    </style>
@endsection

@section('page_js')
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('const.google_api_key') }}&region=JP&language=ja&callback=initMap&libraries=places" defer></script>
	<script src="{{ asset('assets/user/js/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
	<script>
        var LESSON_STATE_DRAFT = {{config('const.lesson_state.draft')}};
        var LESSON_STATE_PRIVATE = {{config('const.lesson_state.private')}};
        var LESSON_STATE_CHECK = {{config('const.lesson_state.check')}};
        var LESSON_STATE_PUBLIC = {{config('const.lesson_state.public')}};
        var LESSON_STATE_REJECT = {{config('const.lesson_state.reject')}};
        var LESSON_STATE_DEL = {{config('const.lesson_state.delete')}};

        var error_id = '';
        $(document).ready(function () {
            // 選択された address
            $('.multi-select-tags').select2({
            }).on('select2:opening', function (e) {
                var $self = $(this);
                e.preventDefault();
                $self.select2('close');
            });

            $('.multi-select-discuss-tags').select2({
            }).on('select2:opening', function (e) {
                var $self = $(this);
                e.preventDefault();
                $self.select2('close');
            });

            // remove option of map location
            $('.multi-select-tags').on('select2:unselect', function (e) {
                var $self = $(this);
                e.preventDefault();
                $self.select2('close');
                let unselect_text = $(e)[0].params.data.text;
                $('.multi-select-tags').find("option:contains('"+unselect_text+"')").remove();
            });

            $('.multi-select-discuss-tags').on('select2:unselect', function (e) {
                var $self = $(this);
                e.preventDefault();
                $self.select2('close');
                let unselect_text = $(e)[0].params.data.text;
                $('.multi-select-discuss-tags').find("option:contains('"+unselect_text+"')").remove();
            });

            $('.lesson-discuss-province').click(function() {
                $.ajax({
                    type: "post",
                    url: '{{ route('keijibann.province_modal') }}',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    dataType: 'json',
                    success: function (result) {
                        if(result.result_code == 'success') {
                            $('#modal-province').html('');
                            $('#modal-province').append(result.province_detail);

                            if ($('.lesson-discuss-area').hasClass('action-disable')) {
                                $('.lesson-discuss-area').removeClass('action-disable')
                            }
                        } else {
                        }
                    }
                });
            });

            $('.lesson-discuss-area').click(function() {
                let province_id = $('#province_id').val();
                if (province_id == "") {
                    return;
                }
                let area_id_arr = $('#area_id').val();
                $.ajax({
                    type: "post",
                    url: '{{ route('keijibann.area_modal') }}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        province_id: province_id,
                        area_id_arr: area_id_arr
                    },
                    dataType: 'json',
                    success: function (result) {
                        if(result.result_code == 'success') {
                            $('#modal-area').html('');
                            $('#modal-area').append(result.area_detail);

                        } else {
                        }
                    }
                });
            });

            // click province modal close button
            $('.ajax-modal-container').on('click', '.modal-close', function() {
                $('.start-active').addClass('appear');
                $("#modal-province,#modal-overlay").fadeOut("fast", function () {

                    $('#modal-overlay').remove();

                });
            });
            $('.ajax-modal-container').on('click', '.modal-close', function() {
                $('.start-active').addClass('appear');
                $("#modal-area,#modal-overlay").fadeOut("fast", function () {

                    $('#modal-overlay').remove();

                });
            });

            // click province modal province name
            $('.ajax-modal-container').on('click', '.province_name', function() {
                $('.start-active').addClass('appear');
                $("#modal-province,#modal-overlay").fadeOut("fast", function () {

                    $('#modal-overlay').remove();

                });
                let province_id = $(this).attr('data-id');
                let province_name = $(this).attr('data-name');
                $('#province_id').val(province_id);
                $('.btn_province').text(province_name);
            });
            $('.ajax-modal-container').on('click', '.clear_btn', function() {
                var i, j, child_count;
                var count = $("#area_count").val();
                for(j = 1; j <= count; j++){
                    $("#c_" + j).prop('checked', false);
                    $("#area_" + j).val(0);
                }
            });
            $('.ajax-modal-container').on('click', '#btn_area_setting', function() {
                $('.start-active').addClass('appear');
                $("#modal-area,#modal-overlay").fadeOut("fast", function () {

                    $('#modal-overlay').remove();

                });

                let province_name = $('.btn_province').text();
                let area_ids = [];
                let area_names = [];
                $('input[name=area]:checked').each(function(){
                    area_ids.push($(this).attr('data-area-id'));
                    area_names.push($(this).attr('data-area-name'));
                    let area_discuss_name = province_name + $(this).attr('data-area-name');
                    console.log("area_discuss_name", area_discuss_name);
                    if (!$('.multi-select-discuss-tags').find("option:contains('"+area_discuss_name+"')").length) {
                        let newOption = "<option data-prefecture='"+province_name+"' data-area='"+$(this).attr('data-area-id')+"' selected>" + area_discuss_name + "</option>";
                        $('.multi-select-discuss-tags').append(newOption).trigger('change');
                    }
                });
                let area_id = $(this).attr('data-id');
                let area_name = $(this).attr('data-name');
                $('#area_id').val(area_ids.join(','));
                $('.btn_area').text(area_names.join('、'));
            });

            navigator.geolocation.getCurrentPosition(function(position) {

                var latitude =  parseFloat(position.coords.latitude);
                var longitude =  parseFloat(position.coords.longitude);

                map = new google.maps.Map(document.getElementById('map'), {
                    center: new google.maps.LatLng(latitude, longitude),
                    zoom: 15
                });

                // ----- search keyworkd start -----

                const input = document.getElementById("lesson_address_and_keyword");
                const searchBox = new google.maps.places.SearchBox(input);

                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                map.addListener("bounds_changed", () => {
                    searchBox.setBounds(map.getBounds());
                });

                let markers = [];

                searchBox.addListener("places_changed", () => {
                    const places = searchBox.getPlaces();

                    if (places.length == 0) {
                        return;
                    }

                    // Clear out the old markers.
                    markers.forEach((marker) => {
                        marker.setMap(null);
                    });
                    markers = [];

                    // For each place, get the icon, name and location.
                    const bounds = new google.maps.LatLngBounds();

                    places.forEach((place) => {
                        if (!place.geometry || !place.geometry.location) {
                            console.log("Returned place contains no geometry");
                            return;
                        }

                        const icon = {
                            url: place.icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(17, 34),
                            scaledSize: new google.maps.Size(25, 25),
                        };

                        let push_mark = new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        });
                        google.maps.event.addListener(push_mark, 'click', function(event) {
                            geocoder.geocode({
                                'latLng': event.latLng
                            }, function(results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    if (results[0]) {
                                        update_map_location(event, results[0].address_components)
                                    }
                                }
                            });
                        });

                        // Create a marker for each place.
                        markers.push(
                            push_mark
                        );

                        if (place.geometry.viewport) {
                            // Only geocodes have viewport.
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    map.fitBounds(bounds);
                });

                // ----- search keyworkd end -----

                var geocoder = new google.maps.Geocoder();
                geocoder
                    .geocode({ location: { lat:latitude, lng:longitude} })
                    .then((response) => {
                        if (response.results[0]) {
                            map.setZoom(15);
                            var icon = {
                                scaledSize: new google.maps.Size(30, 32), // scaled size
                                origin: new google.maps.Point(0,0), // origin
                                anchor: new google.maps.Point(0,0) // anchor
                            };
                            if(latitude == "" || longitude == ""){
                                return;
                            }

                            var contentHtml = '<div jstcache="33" class="poi-info-window gm-style">' +
                                '<div jstcache="1">' +
                                '<div jstcache="2" jsinstance="0" class="address-line full-width" jsan="7.address-line,7.full-width">' + response.results[0].formatted_address + '</div>' +
                                '<div jstcache="5" style="display:none"></div>';

                            var myInfo = new google.maps.InfoWindow({
                                content: contentHtml,
                                maxWidth: 350
                            });

                            var marker = new google.maps.Marker({
                                map: map,
                                position: new google.maps.LatLng(latitude, longitude),
                                icon: icon,
                            });

                            google.maps.event.addListener(marker, 'click', function(event) {
                                geocoder.geocode({
                                    'latLng': event.latLng
                                }, function(results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        if (results[0]) {
                                            update_map_location(event, results[0].address_components)
                                        }
                                    }
                                });
                            });

                            // Create the initial InfoWindow.
                            let infoWindow = new google.maps.InfoWindow({
                                content: "Click the map to get Lat/Lng!",
                                position: location,
                            });

                            infoWindow.open(map);

                        } else {
                            window.alert("No results found");
                        }
                    })
                    .catch((e) => window.alert("Geocoder failed due to: " + e));

                // map click event
                google.maps.event.addListener(map, 'click', function(event) {
                    geocoder.geocode({
                        'latLng': event.latLng
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                update_map_location(event, results[0].address_components);
                            }
                        }
                    });
                });
            });

            remove_warning();
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

            $('#lesson_person_num').change(function() {
                if ($(this).val() == 1) {
                    $('#lesson_able_with_man').prop("disabled", true);
                    $('#lesson_able_with_man').prop("checked", false);
                    $('#lesson_able_with_man').val(0);
                } else {
                    $('#lesson_able_with_man').prop("disabled", false);
                }
            });
        });

        // map
        function initMap() {

        }

        function update_map_location(event, address_arr) {
            if (address_arr == undefined) {
                return;
            }

            // address 情報取得
            let p_code='', country='', prefecture='', county='', city='', locality='', sublocality='', lat='', lng='';

            for (let i = 0; i < address_arr.length; ++i) {
                if (address_arr[i].types.indexOf("administrative_area_level_1") >= 0 ) {
                    prefecture = address_arr[i].long_name;
                }
                if (address_arr[i].types.indexOf("administrative_area_level_2") >= 0 ) {
                    county = address_arr[i].long_name;
                }
                if (address_arr[i].types.indexOf("locality") >= 0 ) {
                    locality = address_arr[i].long_name;
                }
                if (address_arr[i].types.indexOf("sublocality_level_1") >= 0 ) {
                    sublocality = address_arr[i].long_name;
                }
            }
            let map_location_address = prefecture + county + locality + sublocality;

            // validate 追加
            if (!$('.multi-select-tags').find("option:contains('"+map_location_address+"')").length) {
                let newOption = "<option data-lat='"+event.latLng.lat()+"' data-lng='"+event.latLng.lng()+"' data-prefecture='"+prefecture+"' data-county='"+county+"' data-locality='"+locality+"' data-sublocality='"+sublocality+"' selected>" + map_location_address + "</option>";
                $('.multi-select-tags').append(newOption).trigger('change');
            }
        }

        File.prototype.convertToBase64 = function(callback){
            var reader = new FileReader();
            reader.onloadend = function (e) {
                callback(e.target.result, e.target.error);
            };
            reader.readAsDataURL(this);
        };
        function set_preview_pic(obj) {
            var selectedFile = obj.files[0];
            selectedFile.convertToBase64(function(base64){
                var html = '<img src="'+base64+'" style="height: 100%;">';
                $(obj).parent().children('div').html(html);
            })
        }

        function show_category() {
            $('#form1').hide();
            $('#form2').show();
            $('footer').hide();
            $('.header_area h1').html("カテゴリーを選択")
            $('.header_area .h-icon p').hide();
            $('.header_area .h-icon').append('<p id="temp_p">' +
                '<button type="button" onclick="cancel_category()">' +
                '<img src="{{ asset('assets/user/img/arrow_left2.svg') }}" alt="戻る">' +
                '</button>' +
                '</p>');
        }

        function clear_category(){
            $('input[name=ctg_radio]').prop('checked', false);
        }

        function cancel_category(){
            $('#form1').show();
            $('#form2').hide();
            $('footer').show();
            $('.header_area h1').html("レッスンを出品");
            $('#temp_p').remove();
            $('.header_area .h-icon p:first').show();
        }

        function set_lesson_class() {
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
                open_error_modal(msg);
                $('.modal-wrap').fadeIn();
            }
        }

        function set_check_box(obj, type="") {

            var val = $(obj).val();
            if(val == 0){
                $(obj).val(1);
                // clear map
                if (type == "lesson_accept_without_map") {
                    $('.multi-select-tags').val(null).trigger('change');
                    $('#lesson_pos_detail').val("");
                    if (!$('#map').hasClass('action-disable')) {
                        $('#map').addClass('action-disable');
                    }
                    if(!$('.input-map-address-area').hasClass('hide')) {
                        $('.input-map-address-area').addClass('hide');
                    }
                    $('#lesson_able_discuss_pos').val(1);
                    $('#lesson_able_discuss_pos').prop('checked', true);
                    if($('.input-discuss-pos-area').hasClass('hide')) {
                        $('.input-discuss-pos-area').removeClass('hide')
                    }
                }
                if (type == "lesson_able_discuss_pos") {
                    if($('.input-discuss-pos-area').hasClass('hide')) {
                        $('.input-discuss-pos-area').removeClass('hide')
                    }
                }
            }else{
                $(obj).val(0);
                if (type == "lesson_accept_without_map") {
                    if ($('#map').hasClass('action-disable')) {
                        $('#map').removeClass('action-disable');
                    }
                    if($('.input-map-address-area').hasClass('hide')) {
                        $('.input-map-address-area').removeClass('hide');
                    }
                }
                if (type == "lesson_able_discuss_pos") {
                    $('.multi-select-discuss-tags').val(null).trigger('change');
                    $('#lesson_discuss_pos_detail').val("");
                    if ($('#map').hasClass('action-disable')) {
                        $('#map').removeClass('action-disable');
                    }
                    if($('.input-map-address-area').hasClass('hide')) {
                        $('.input-map-address-area').removeClass('hide');
                    }
                    $('#lesson_accept_without_map').val(0);
                    $('#lesson_accept_without_map').prop('checked', false);

                    if(!$('.input-discuss-pos-area').hasClass('hide')) {
                        $('.input-discuss-pos-area').addClass('hide')
                    }
                }
            }
        }

        function open_target_modal(obj){
            $('.modal-wrap').fadeIn();
            var target = $(obj).attr('data-target');
            $('#'+target).fadeIn();
            $('#modal-overlay2').fadeIn();
        }
        function open_modal(state){
            $('.modal-wrap').fadeIn();
            if(state == LESSON_STATE_CHECK){
				@if(isset($lesson_info) && (intval($lesson_info['lesson_state']) == config('const.lesson_state.public') || intval($lesson_info['lesson_state']) == config('const.lesson_state.check') || intval($lesson_info['lesson_state']) == config('const.lesson_state.reject')))
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
            if(state == LESSON_STATE_DEL){
                $('#modal-delete').fadeIn();
            }
            $('#modal-overlay2').fadeIn();
        }

        function open_error_modal(msg) {
            $('.modal-wrap').fadeIn();
            $('#modal-error #error_msg').html(msg);
            $('#modal-error').fadeIn();
            $('#modal-overlay2').fadeIn();
        }

        function validate(state="") {
            if (state == LESSON_STATE_DRAFT || state == LESSON_STATE_PRIVATE) {
                return true;
            }
            var no_error = true;
            error_id = "";
            if( !checkRequire($("#file_name_1"), "レッスンイメージ")) {
                no_error = false;
                if (error_id == "")
                    error_id = '#file_name_1';
            }
            if( !checkSelect($("#lesson_class_id"), "カテゴリー")) {
                no_error = false;
                if (error_id == "")
                    error_id = '#lesson_class_id';
            }
            if( !checkRequire($("#lesson_title"), "レッスンタイトル")) {
                no_error = false;
                if (error_id == "")
                    error_id = '#lesson_title';
            }
            if( !checkSelect($("#lesson_min_hours"), "最低レッスン時間")) {
                no_error = false;
                if (error_id == "")
                    error_id = '#lesson_min_hours';
            }
            if( !checkRequire($("#lesson_30min_fees"), "30分あたりのレッスン料金")) {
                no_error = false;
                if (error_id == "")
                    error_id = '#lesson_30min_fees';
            }
            if( !checkInteger($("#lesson_30min_fees"), "30分あたりのレッスン料金")) {
                no_error = false;
                if (error_id == "")
                    error_id = '#lesson_30min_fees';
            }
            if( !checkMin($("#lesson_30min_fees"), "30分あたりのレッスン料金", 1000)) {
                no_error = false;
                if (error_id == "")
                    error_id = '#lesson_30min_fees';
            }
            if( !checkMax($("#lesson_30min_fees"), "30分あたりのレッスン料金", 10000)) {
                no_error = false;
                if (error_id == "")
                    error_id = '#lesson_30min_fees';
            }

            // validate map
            let lesson_accept_without_map = $('#lesson_accept_without_map').val();
            if(lesson_accept_without_map != 1) {
                if( !checkRequire($("#tags"), "レッスン場所")) {
                    if (error_id == "")
                        error_id = '#tags';
                    no_error = false;
                }
            }

            // レッスン場所の相談可
            let lesson_able_discuss_pos = $('#lesson_able_discuss_pos').val();
            if(lesson_able_discuss_pos == 1) {
                if( !checkRequire($("#discuss_tags"), "レッスン相談場所")) {
                    if (error_id == "")
                        error_id = '#discuss_tags';
                    no_error = false;
                }
            }

            return no_error;
        }

        function lesson_save(state) {
            // レッスン場所
            var map_location = [];
            $(".multi-select-tags").each(function() {
                let ele_data = $(this).select2('data');
                for(let i=0;i < ele_data.length; i++) {
                    let ele = ele_data[i].element.dataset;
                    map_location.push({
                        lat: ele.lat,
                        lng: ele.lng,
                        prefecture: ele.prefecture,
                        county: ele.county,
                        locality: ele.locality,
                        sublocality: ele.sublocality
                    });
                }
            });

            // レッスン相談場所
            var map_discuss_location = [];
            $(".multi-select-discuss-tags").each(function() {
                let ele_data = $(this).select2('data');
                console.log("ele_data", ele_data);
                for(let i=0;i < ele_data.length; i++) {
                    let ele = ele_data[i].element.dataset;
                    map_discuss_location.push({
                        area_id: ele.area,
                        prefecture: ele.prefecture,
                        area_name: ele_data[i].element.label,
                    });
                }
            });
            console.log("map_discuss_location", map_discuss_location);

            $('#lesson_state').val(state);
            if(validate(state)){
                var form_data = new FormData($('#form1').get(0));
                form_data.append("_token", "{{csrf_token()}}");
                form_data.append("map_location", JSON.stringify(map_location));
                form_data.append("map_discuss_location", JSON.stringify(map_discuss_location));
                $.ajax({
                    type: "post",
                    url: '{{ route('user.syutupinn.save_lesson') }}',
                    data: form_data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        if ( result.state == 'success' ) {
                            open_modal(state);
                        }else{
                            var msg = '';
                            if(state == LESSON_STATE_CHECK)
                                msg = '出品申請が<br>失敗しました。';
                            if(state == LESSON_STATE_PRIVATE)
                                msg = '非公開保存が<br>失敗しました。';
                            if(state == LESSON_STATE_DRAFT)
                                msg = '保存が失敗しました。';
                            open_error_modal(msg);
                        }
                    },
                });
            } else {
                var container = $('#form1');
                var scrollTo = $(error_id);
                var position = scrollTo.offset().top - container.offset().top  + container.scrollTop();
                $('html, body').animate({
                    scrollTop: position
                });
            }

        }

        function Lesson_del(lesson_id){
            $.ajax({
                type: "post",
                url: '{{ route('user.syutupinn.del_lesson') }}',
                data: {lesson_id: lesson_id, _token: "{{csrf_token()}}"},
                dataType: 'json',
                success: function (result) {
                    if ( result.state == 'success' ) {
                        location.href = '{{route('user.syutupinn.lesson_list')}}';
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

        // レッスン場所の相談可=> 都道府県, エリア
        function update_map_location(event, address_arr) {
            if (address_arr == undefined) {
                return;
            }

            // address 情報取得
            let p_code='', country='', prefecture='', county='', city='', locality='', sublocality='', lat='', lng='';

            for (let i = 0; i < address_arr.length; ++i) {
                if (address_arr[i].types.indexOf("administrative_area_level_1") >= 0 ) {
                    prefecture = address_arr[i].long_name;
                }
                if (address_arr[i].types.indexOf("administrative_area_level_2") >= 0 ) {
                    county = address_arr[i].long_name;
                }
                if (address_arr[i].types.indexOf("locality") >= 0 ) {
                    locality = address_arr[i].long_name;
                }
                if (address_arr[i].types.indexOf("sublocality_level_1") >= 0 ) {
                    sublocality = address_arr[i].long_name;
                }
            }
            let map_location_address = prefecture + county + locality + sublocality;

            // validate 追加
            if (!$('.multi-select-tags').find("option:contains('"+map_location_address+"')").length) {
                let newOption = "<option data-lat='"+event.latLng.lat()+"' data-lng='"+event.latLng.lng()+"' data-prefecture='"+prefecture+"' data-county='"+county+"' data-locality='"+locality+"' data-sublocality='"+sublocality+"' selected>" + map_location_address + "</option>";
                $('.multi-select-tags').append(newOption).trigger('change');
            }
        }

	</script>
@endsection

