@extends('user.layouts.app')

@section('title', 'レッスンを出品')

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
		<input type="hidden" name="lesson_type" value="{{config('const.lesson_type.online')}}">
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
					<input type="radio" name="onof-line" id="off-line" value="{{ route("user.syutupinn.regLesson") }}" onclick="location.href=this.value">
					<label class="ok" for="off-line">対面レッスン型</label>
				</div>
				<div class="radio-02">
					<input type="radio" name="onof-line" id="on-line" checked="checked">
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
					<button type="button" id="lesson_class_name" name="lesson_class_name" onClick="show_category();" class="form_btn">{{isset($lesson_info) ? $lesson_info['lesson_class']['class_name'] : '指定なし'}}</button>
					<input type="hidden" id="lesson_class_id" name="lesson_class_id" value="{{isset($lesson_info) ? $lesson_info['lesson_class_id'] : ''}}">
					<input type="hidden" id="hlcn" name="hlcn" value="{{isset($lesson_info) ? $lesson_info['lesson_class']['class_name'] : ''}}">
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
								<option value="0">指定なし</option>
								@for($i = 1; $i < 150; $i++)
									<option value="{{$i}}" {{isset($lesson_info) && $lesson_info['lesson_wish_minage'] == $i ? "selected='selected'" : ''}}>{{$i}}</option>
								@endfor
							</select>
						</div>
						<div>～</div>
					</li>
					<li>
						<div class="form_wrap icon_form type_arrow_bottom shadow-glay">
							<select name="lesson_wish_maxage">
								<option value="0">指定なし</option>
								@for($i = 1; $i < 150; $i++)
									<option value="{{$i}}" {{isset($lesson_info) && $lesson_info['lesson_wish_maxage'] == $i ? "selected='selected'" : ''}}>{{$i}}</option>
								@endfor
							</select>
						</div>
					</li>
				</ul>
			</div>

			<div class="inner_box for-warning">
				<h3>最低レッスン時間</h3>
				<div class="form_wrap icon_form type_arrow_bottom shadow-glay">
					<select id="lesson_min_hours" name="lesson_min_hours">
						<option value="">選択してください</option>
						@for($i = 15; $i <= 300; $i=$i+15)
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
				<h3>対応人数最大</h3>
				<p class="base_txt">
					オンラインのレッスンでは<br>
					マンツーマン形式のみとなります。
				</p>
				<input type="hidden" name="lesson_person_num" value="1">
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

			<div class="inner_box check-box">
				<div class="clex-box_02">
					<input type="checkbox" onclick="set_check_box(this)" name="lesson_accept_attend_request" value="{{isset($lesson_info) ? $lesson_info['lesson_accept_attend_request'] : 0}}" id="lesson_accept_attend_request" {{isset($lesson_info) && $lesson_info['lesson_accept_attend_request'] == 1 ? 'checked' : ''}}>
					<label for="lesson_accept_attend_request"><h3>出勤リクエストを受付する</h3></label>
				</div>
			</div>
			<div class="balloon balloon_blue mt0" style="margin-bottom: 30px;">
				<p>指定するレッスン場所によっては出張交通費をお願いされる場合があります。</p>
			</div>
            <div class="inner_box">
                <h3 class="must">クーポンの適用</h3>
                <div class="form_wrap">
                    <ul class="radio-box radio_mark">
                        <li>
                            <input type="radio" name="lesson_coupon" value="1" id="coupon-1" {{isset($lesson_info) && $lesson_info['lesson_coupon'] == 1 ? 'checked' : ''}}>
                            <label for="coupon-1">適用する</label>
                        </li>
                        <li>
                            <input type="radio" name="lesson_coupon" value="0" id="coupon-2" {{isset($lesson_info) ? $lesson_info['lesson_coupon'] == 0 ? 'checked' : '' : 'checked'}}>
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
                @if(!isset($lesson_info))
                    <input type="hidden" id="save_case" value="add">
                    <div class="btn_base btn_orange shadow">
                        <button type="button" class="btn-request" onclick="lesson_save({{config('const.lesson_state.check')}});">出品を申請する</button>
                    </div>
                    <div class="btn_base btn_pale-gray shadow">
                        <button type="button" class="btn-draft" onclick="lesson_save({{config('const.lesson_state.draft')}});">下書きに保存</button>
                    </div>
                @elseif(isset($lesson_info) && (intval($lesson_info['lesson_state']) == config('const.lesson_state.public') || intval($lesson_info['lesson_state']) == config('const.lesson_state.check') || intval($lesson_info['lesson_state']) == config('const.lesson_state.reject')))
                    <input type="hidden" id="save_case" value="update">
                    <div class="btn_base btn_orange shadow">
                        <button type="button"  class="btn-request"  onclick="go_to_form3()">変更を保存</button>
                    </div>
                    <div class="btn_base btn_pale-gray shadow">
                        <button type="button" class="button-link" onclick="open_modal({{config('const.lesson_state.delete')}})">削除する</button>
                    </div>
                    <div class="btn_base btn_white shadow">
                        <button type="button" class="button-link" onclick="lesson_save({{config('const.lesson_state.private')}})">非公開にする</button>
                    </div>
                @elseif(isset($lesson_info) && (intval($lesson_info['lesson_state']) == config('const.lesson_state.draft') || intval($lesson_info['lesson_state']) == config('const.lesson_state.private')))
                    <input type="hidden" id="save_case" value="draft">
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
										<label for="radio{{$v['class_id']}}"><p><span class="ctg-nm" style="color:#414042">{{$v['class_name']}}</span>@if($v['cnt'] > 0)（{{$v['cnt']}}）@endif</p></label>
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
		</div>

        <div action="{{route('user.syutupinn.online_add_confirm')}}" name="form3" id="form3" style="display: none">
            <section>
                <h2 class="pb20 f16">変更申請を受け付けます</h2>
                <p class="form_txt pb30"> ご入力いただいた評価は匿名で他のユーザーと平均化されて表示されます。<br>また、ほかのユーザーにセンパイをオススメする際の基準に利用させて頂きます。</p>
                <div class="inner_box">

                    <h3>受付を継続する</h3>
                    <div class="white_box">
                        <p class="f13 pb10">審査完了まで既存のレッスン内容で受付を継続します。</p>
                        <p class="f13"><small>※審査完了までに出されたリクエストは既存のレッスン内容で行うものとします。</small></p>
                    </div>

                </div>
                <div class="inner_box">

                    <h3>受付を停止する</h3>
                    <div class="white_box">
                        <p class="f13 pb10">審査完了まで当該レッスンのリクエストを停止し、審査が完了した段階で新しいレッスンとして公開されます。</p>
                    </div>

                </div>
            </section>

            <section>
                <div class="inner_box">
                    <div class="button-area">
                        <div class="btn_base btn_orange shadow">
                            <button type="button"  class="button-link" onclick="lesson_save({{config('const.lesson_state.check')}});">受付を継続する</button>
                        </div>

                        <div class="btn_base btn_pale-gray shadow">
                            <button type="button" class="button-link" data-target="modall_teishi" onclick="open_target_modal(this)">受付を停止する</button>
                        </div>

                        <div class="btn_base btn_white shadow">
                            <button type="button" onclick="location.href='<?php /*echo $_SERVER['HTTP_REFERER']; */?>'">入力画面に戻る</button>
                        </div>
                    </div>
                </div>
            </section><!-- /contents -->
        </div>
	</div><!-- /contents -->
    <!-- モーダル部分 *********************************************************** -->
    <div class="modal-wrap completion_wrap">
        <div id="modall_keizoku" class="modal-content">

            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl">
                        受付を継続します。<br>
                        よろしいですか?
                    </h2>
                    <p class="modal_txt">
                        当該のレッスンは変更前の条件で公開を継続されます。
                    </p>

                </div>
            </div>



            <div class="button-area">
                <div class="btn_base btn_orange">
                    <a href="{{route('user.syutupinn.online_add_confirm')}}">OK</a>
                </div>

                <div class="btn_base btn_gray-line">
                    <a class="modal-close button-link">キャンセル</a>
                </div>

            </div>


        </div><!-- /modal-content -->

    </div>

    <div class="modal-wrap completion_wrap">
        <div id="modall_teishi" class="modal-content">

            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl">
                        受付を停止します。<br>
                        よろしいですか?
                    </h2>
                    <p class="modal_txt">
                        当該のレッスンは非公開とされます。
                    </p>

                </div>
            </div>



            <div class="button-area">
                <div class="btn_base btn_orange">
                    <button type="button" onclick="go_to_form1()">OK</button>
                </div>

                <div class="btn_base btn_gray-line">
                    <a class="modal-close button-link">キャンセル</a>
                </div>

            </div>


        </div><!-- /modal-content -->

    </div>
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
						<a href="{{route("user.syutupinn.lesson_list")}}" class="modal-close button-link">OK</a>
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

@section('page_js')
<script src="{{ asset('assets/user/js/validate.js') }}"></script>
<script>
    var LESSON_STATE_DRAFT = {{config('const.lesson_state.draft')}};
    var LESSON_STATE_PRIVATE = {{config('const.lesson_state.private')}};
    var LESSON_STATE_CHECK = {{config('const.lesson_state.check')}};
    var LESSON_STATE_PUBLIC = {{config('const.lesson_state.public')}};
    var LESSON_STATE_REJECT = {{config('const.lesson_state.reject')}};
    var LESSON_STATE_DEL = {{config('const.lesson_state.delete')}};
    $(document).ready(function () {
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
    });
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

    function cancel_category(){
        $('#form1').show();
        $('#form2').hide();
        $('footer').show();
        $('.header_area h1').html("レッスンを出品");
        $('#temp_p').remove();
        $('.header_area .h-icon p:first').show();
    }

    function clear_category(){
        $('input[name=ctg_radio]').prop('checked', false);
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

    function set_check_box(obj) {
        var val = $(obj).val();
        if(val == 0){
            $(obj).val(1);
        }else{
            $(obj).val(0);
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
            if($('#save_case').val() == 'add'){
                $('#modal-listing').fadeIn();
            }else{
                $('#modall_keizoku').fadeIn();
            }
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

    function validate() {
        var no_error = true;
        if( !checkRequire($("#file_name_1"), "レッスンイメージ")) no_error = false;
        if( !checkSelect($("#lesson_class_id"), "カテゴリー")) no_error = false;
        if( !checkRequire($("#lesson_title"), "レッスンタイトル")) no_error = false;
        if( !checkSelect($("#lesson_min_hours"), "最低レッスン時間")) no_error = false;
        if( !checkRequire($("#lesson_30min_fees"), "30分あたりのレッスン料金")) no_error = false;
        if( !checkInteger($("#lesson_30min_fees"), "30分あたりのレッスン料金")) no_error = false;
        if( !checkMin($("#lesson_30min_fees"), "30分あたりのレッスン料金", 1000)) no_error = false;
        if( !checkMax($("#lesson_30min_fees"), "30分あたりのレッスン料金", 10000)) no_error = false;
        return no_error;
    }

    function lesson_save(state) {
        $('#lesson_state').val(state);
        if(validate()){
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

    function go_to_form3() {
        if(validate()){
            $('#form1').hide();
            $('#form2').hide();
            $('#form3').show();
            $('.header_area h1').html("変更申請");
        }
    }

    function go_to_form1() {
        $('.modal-close').click();
        $('#form1').show();
        $('#form2').hide();
        $('#form3').hide();
        $('.header_area h1').html("レッスンを出品");
    }

</script>
@endsection
