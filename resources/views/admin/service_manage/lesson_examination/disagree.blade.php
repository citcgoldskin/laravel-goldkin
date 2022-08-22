@extends('admin.layouts.auth')
@section('title', 'このレッスンを承認出来ない理由')

@php
    use App\Service\CommonService;
    use App\Service\AreaService;
	use App\Service\LessonService;
@endphp

@section('content')

    <div id="contents" class="long nopt">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.lesson_examination.detail', ['lesson_id'=>$obj_lesson->lesson_id])])

        <!--main_-->
            {{ Form::open(["route"=>"admin.lesson_examination.post_reason", "method"=>"post", "name"=>"frm_reason", "id"=>"frm_reason"]) }}
            <input type="hidden" name="agree_type" value="{{ config('const.agree_flag.disagree') }}">
            <input type="hidden" name="lesson_id" value="{{ $obj_lesson->lesson_id }}">
            <input type="hidden" name="lesson_content_title" value="{{ $obj_lesson->lesson_title }}">
            <section id="info_area">

                @error('reason_no_exist')
                    <span class="error_text" style="padding: 0px;margin-bottom: 10px;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <div class="inner_box chk_input_area">
                    <div class="chk_input_label">
                        <div class="check">
                            <input type="checkbox" name="lesson_image" id="lesson_image" {{ old('lesson_image', isset($condition['lesson_image']) ? $condition['lesson_image'] : '') == "on" ? 'checked' : '' }}><label for="lesson_image"><h3>レッスンイメージ</h3></label>
                        </div>
                    </div>
                    <div class="bg-white">
                        <div class="white_box txt_white_box reason_lesson_image base_txt {{ old('lesson_image', isset($condition['lesson_image']) ? $condition['lesson_image'] : '') == "on" ?: 'hide' }}">
                            <textarea id="reason_lesson_image" name="reason_lesson_image" placeholder="理由を入力（500字まで）" maxlength="500">{{ old('reason_lesson_image', isset($condition['reason_lesson_image']) ? $condition['reason_lesson_image'] : '') }}</textarea>
                        </div>
                        @error('reason_lesson_image')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="white_box swiper-container mt-10">
                            <div class="swiper-inner">
                                <div class="lesson_photo pb10">
                                    <ol class="swiper-wrapper lesson_photo_list">
                                        <!-- Slides -->
                                        @php
                                            $file_arr = \App\Service\CommonService::unserializeData($obj_lesson->lesson_image);
                                        @endphp
                                        @foreach($file_arr as $i=>$val)
                                            @if(!is_null($val))
                                                <li class="swiper-slide chk_lsn_img {{ old('lesson_image', isset($condition['lesson_image']) ? $condition['lesson_image'] : '') == "on" ? : 'hide-chk' }}">
                                                    <div class="check">
                                                        <input type="checkbox" name="lesson_image_item[{{ $i+1 }}]" id="lesson_image_{{ $i+1 }}" {{ old('lesson_image_item.'.($i+1), isset($condition['lesson_image_item']) && isset($condition['lesson_image_item'][$i+1]) ? $condition['lesson_image_item'][$i+1] : '') == "on" ? 'checked' : '' }}><label for="lesson_image_{{ $i+1 }}"></label>
                                                    </div>
                                                    <div class="form_wrap shadow-glay">
                                                        <label>
                                                            <div class="photo_btn">
                                                                <img src="{{\App\Service\CommonService::getLessonImgUrl($val)}}" style="height: 100%;">
                                                            </div>
                                                        </label>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                            @error('lesson_image_item')
                                <span class="error_text">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="inner_box chk_input_area">
                    <div class="chk_input_label">
                        <div class="check">
                            <input type="checkbox" name="lesson_title" id="lesson_title" {{ old('lesson_title', isset($condition['lesson_title']) ? $condition['lesson_title'] : '') == "on" ? 'checked' : '' }}><label for="lesson_title"><h3>レッスンタイトル</h3></label>
                        </div>
                    </div>
                    <div class="bg-white">
                        <div class="white_box txt_white_box reason_lesson_title base_txt  {{ old('lesson_title', isset($condition['lesson_title']) ? $condition['lesson_title'] : '') == "on" ?: 'hide' }}">
                            <textarea id="reason_lesson_title" name="reason_lesson_title" placeholder="理由を入力（500字まで）" maxlength="500">{{ old('reason_lesson_image', isset($condition['reason_lesson_image']) ? $condition['reason_lesson_image'] : '') }}</textarea>
                        </div>
                        @error('reason_lesson_title')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="white_box base_txt">
                            <p>{{ $obj_lesson->lesson_title }}</p>
                        </div>
                    </div>
                </div>

                <div class="inner_box chk_input_area">
                    <div class="chk_input_label">
                        <div class="check">
                            <input type="checkbox" name="lesson_service_details" id="lesson_service_details" {{ old('lesson_service_details', isset($condition['lesson_service_details']) ? $condition['lesson_service_details'] : '') == "on" ? 'checked' : '' }}><label for="lesson_service_details"><h3>サービス詳細</h3></label>
                        </div>
                    </div>
                    <div class="bg-white">
                        <div class="white_box txt_white_box reason_lesson_service_details base_txt  {{ old('lesson_service_details', isset($condition['lesson_service_details']) ? $condition['lesson_service_details'] : '') == "on" ?: 'hide' }}">
                            <textarea id="reason_lesson_service_details" name="reason_lesson_service_details" placeholder="理由を入力（500字まで）" maxlength="500">{{ old('reason_lesson_service_details', isset($condition['reason_lesson_service_details']) ? $condition['reason_lesson_service_details'] : '') }}</textarea>
                        </div>
                        @error('reason_lesson_service_details')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="white_box base_txt">
                            <p>{{ $obj_lesson->lesson_service_details }}</p>
                        </div>
                    </div>
                </div>

                <div class="inner_box chk_input_area">
                    <div class="chk_input_label">
                        <div class="check">
                            <input type="checkbox" name="lesson_other_details" id="lesson_other_details" {{ old('lesson_other_details', isset($condition['lesson_other_details']) ? $condition['lesson_other_details'] : '') == "on" ? 'checked' : '' }}><label for="lesson_other_details"><h3>持ち物・その他の費用</h3></label>
                        </div>
                    </div>
                    <div class="bg-white">
                        <div class="white_box txt_white_box reason_lesson_other_details base_txt  {{ old('lesson_other_details', isset($condition['lesson_other_details']) ? $condition['lesson_other_details'] : '') == "on" ?: 'hide' }}">
                            <textarea id="reason_lesson_other_details" name="reason_lesson_other_details" placeholder="理由を入力（500字まで）" maxlength="500">{{ old('reason_lesson_other_details', isset($condition['reason_lesson_other_details']) ? $condition['reason_lesson_other_details'] : '') }}</textarea>
                        </div>
                        @error('reason_lesson_other_details')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="white_box base_txt">
                            <p>{{ $obj_lesson->lesson_other_details }}</p>
                        </div>
                    </div>
                </div>

                <div class="inner_box chk_input_area">
                    <div class="chk_input_label">
                        <div class="check">
                            <input type="checkbox" name="lesson_buy_and_attentions" id="lesson_buy_and_attentions" {{ old('lesson_buy_and_attentions', isset($condition['lesson_buy_and_attentions']) ? $condition['lesson_buy_and_attentions'] : '') == "on" ? 'checked' : '' }}><label for="lesson_buy_and_attentions"><h3>購入にあたってのお願い・注意事項</h3></label>
                        </div>
                    </div>
                    <div class="bg-white">
                        <div class="white_box txt_white_box reason_lesson_buy_and_attentions base_txt  {{ old('lesson_buy_and_attentions', isset($condition['lesson_buy_and_attentions']) ? $condition['lesson_buy_and_attentions'] : '') == "on" ?: 'hide' }}">
                            <textarea id="reason_lesson_buy_and_attentions" name="reason_lesson_buy_and_attentions" placeholder="理由を入力（500字まで）" maxlength="500">{{ old('reason_lesson_buy_and_attentions', isset($condition['reason_lesson_buy_and_attentions']) ? $condition['reason_lesson_buy_and_attentions'] : '') }}</textarea>
                        </div>
                        @error('reason_lesson_buy_and_attentions')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="white_box base_txt">
                            <p>{{ $obj_lesson->lesson_buy_and_attentions }}</p>
                        </div>
                    </div>
                </div>

                <div class="inner_box chk_input_area">
                    <div class="chk_input_label">
                        <div class="check">
                            <input type="checkbox" name="lesson_other" id="lesson_other" {{ old('lesson_other', isset($condition['lesson_other']) ? $condition['lesson_other'] : '') == "on" ? 'checked' : '' }}><label for="lesson_other"><h3>その他当社が不適切と判断した点</h3></label>
                        </div>
                    </div>
                    <div class="bg-white">
                        <div class="white_box txt_white_box reason_lesson_other base_txt  {{ old('lesson_other', isset($condition['lesson_other']) ? $condition['lesson_other'] : '') == "on" ?: 'hide' }}">
                            <textarea id="reason_lesson_other" name="reason_lesson_other" placeholder="理由を入力（500字まで）" maxlength="500">{{ old('reason_lesson_other', isset($condition['reason_lesson_other']) ? $condition['reason_lesson_other'] : '') }}</textarea>
                        </div>
                        @error('reason_lesson_other')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

            </section>

            <section>
                <div class="white_box">
                    <div class="btn mtb">
                        <button type="submit" onclick="location.href=''">通知文を作成する</button>
                    </div>
                    <div class="btn mtb">
                        <button type="button" onclick="location.href='{{ route('admin.lesson_examination.detail', ['lesson_id' => $obj_lesson->lesson_id]) }}'">前の画面に戻る</button>
                    </div>
                </div>
            </section>
            {{ Form::close() }}

            </div>

    </div><!-- /contents -->

@endsection

@section('page_css')
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
    <style>
        .txt_white_box {
            padding: 5px !important;
        }
        .chk_lsn_img label:before {
            z-index: 99;
            top: 0px !important;
        }
        .hide-chk label:before {
            display: none !important;
        }
        .mt-10 {
            margin-top: 10px !important;
        }
        .photo_btn::before {
            display: none;
        }
        .chk_input_label {
            margin-bottom: 10px;
        }
        .activated {
            color: #FB7122 !important;
        }
        .not-active {
            color: #B8B8B8 !important;
        }
        .under_area {
            height: 100px !important;
        }
        #footer_button_area li .btn_base {
            margin-bottom: 5px;
        }
        .teacher_info {
            justify-content: flex-start !important;
        }
        .about_teacher {
            margin-left: 10px;
        }
    </style>
@endsection

@section('page_js')
    <script type="text/javascript">
        $(document).ready(function () {

        });

        $(function(){
            $('.check input[type=checkbox]').change(function(e) {
                let id = $(this).attr('id');
                let txt_area_cls = 'reason_'+id;
                if ($('.'+txt_area_cls).hasClass('hide')) {
                    $('.'+txt_area_cls).removeClass('hide')
                    if (id == "lesson_image") { // レッスンイメージ
                        if ($('.chk_lsn_img').hasClass('hide-chk')) {
                            $('.chk_lsn_img').removeClass('hide-chk');
                        }
                    }
                } else {
                    $('.'+txt_area_cls).addClass('hide');
                    $('#'+txt_area_cls).val("");

                    if (id == "lesson_image") { // レッスンイメージ
                        if (!$('.chk_lsn_img').hasClass('hide-chk')) {
                            $('.chk_lsn_img').addClass('hide-chk');
                        }
                    }
                }

            });
        });

    </script>
@endsection
