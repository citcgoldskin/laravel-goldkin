@extends('admin.layouts.auth')
@section('title', 'レッスン詳細')

@php
    use App\Service\CommonService;
    use App\Service\AreaService;
	use App\Service\LessonService;

	$lesson_change_history = LessonService::getChangedItemHistory($lesson_id);
@endphp

@section('content')
    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents" class="long nopt {{ isset($page_type) && $page_type == config('const.lesson_service_browser.new') ? 'lesson-new' : '' }}">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.lesson_examination.index')])

        <!--main_-->
            <section>
                <div class="swiper-container">
                    <div class="swiper-inner">
                        <div class="profile">
                            <ol class="swiper-wrapper pt0 pb0">
                                <!-- Slides -->
                                @foreach($data['lesson_images'] as $k => $v)
                                    @if($v)
                                        <li class="swiper-slide">
                                            <div class="swip_contents_block">
                                                <div class="slider_box">
                                                    <div class="img-box">
                                                        <img src="{{CommonService::getLessonImgUrl($v)}}" alt="プロフィールイメージ画像">
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                            <!-- If we need pagination -->
                            <div class="swiper-pagination"></div>

                            <!-- If we need navigation buttons -->
                        </div>
                    </div>
                </div>
            </section>

            <!-- If we need navigation buttons -->

            <section class="pt20">
                <div class="lesson_info_area">
                    <h3 class="senpai-message {{ isset($lesson_change_history['lesson_title']) ? 'changed' : '' }}">{{$data['lesson']['lesson_title']}}</h3>

                    <ul class="title_info">
                        <li><span class="lesson_category">
                         {{$data['lesson']['lesson_class']['class_name']}}
                     </span></li>
                        <li class="jisseki">
                            <p>レッスン実績 <span>{{CommonService::showFormatNum($data['schedule_count'])}}</span><span>件</span></p>
                        </li>
                        <li class="hyouka">このレッスンの評価を受けた後輩の評価<a href="#evaluation">({{CommonService::showFormatNum($data['evalution_count'])}})</a></li>
                    </ul>

                    <ul class="teacher_info flex-ver">
                        <li><img src="{{ CommonService::getUserAvatarUrl($data['senpai']['user_avatar']) }}" class="プロフィールアイコン"></li>
                        <li class="about_teacher">
                            <div
                                @if(!empty($data['senpai']['userConfirm']) &&  $data['senpai']['userConfirm']['pc_state'] == config('const.pers_conf.confirmed')) class="profile_name kakunin_ok"
                                @else class="profile_name kakunin_no"
                                @endif>
                                <p>{{$data['senpai']['name']}}
                                    <span>（{{CommonService::getAge($data['senpai']['user_birthday'])}}）
                                {{CommonService::getSexStr($data['senpai']['user_sex'])}}</span>
                                </p>
                            </div>
                            <div>
                                <p>{{AreaService::getOneAreaFullName($data['senpai']['user_area_id'])}}</p>
                            </div>
                        </li>
                    </ul>
                </div>


            </section>

            <section id="attendance_schedule">
                <h3>出勤スケジュール</h3>
                <div class="commuting-area">
                    <input type="hidden" id="cur_date" value="{{$week_start}}">
                    <input type="hidden" id="prev_date">
                    <input type="hidden" id="next_date">
                    <div class="date-area">
                        <ul>
                            <li id="before_week_label"><a onclick="prevSchedule()">前の週</a></li>
                            <li></li>
                            <li id="after_week_label"><a onclick="nextSchedule()">次の週</a></li>
                        </ul>
                    </div>
                    <div class="day-area att-schedule">
                        <ul>
                        </ul>
                    </div>
                    @php
                        $last_attendance_date = isset($data['senpai']) ? \App\Service\LessonService::getLastAttendanceDate($data['senpai']['id']) : '';
                        $now = \Carbon\Carbon::now()->format('Y-m-d');
                        if (!$last_attendance_date || $last_attendance_date < $now) {
                            $last_attendance_date = $now;
                        }
                    @endphp
                    <p class="text-01">ご希望の出勤日がありませんか？<br>{{ \Carbon\Carbon::parse($last_attendance_date)->addDay()->format('n月j日') }}以降の出勤をお願いすることが出来ます。</p>
                </div>

            </section>

            <section id="info_area">

                <div class="inner_box">
                    <h3 class="{{ isset($lesson_change_history['lesson_min_hours']) || isset($lesson_change_history['lesson_30min_fees']) ? 'changed' : '' }}">レッスン料金</h3>
                    <div class="white_box base_txt price">
                        <p>@if(isset($data['lesson']['lesson_30min_fees']) && !empty($data['lesson']['lesson_30min_fees']))
                                {{CommonService::showFormatNum($data['lesson']['lesson_30min_fees'])}}
                            @endif
                            円<span> / 30分〜</span>
                        </p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3 class="{{ isset($lesson_change_history['lesson_pos_detail']) ? 'changed' : '' }}">待ち合わせ場所</h3>
                    <div class="white_box base_txt">
                        <p>{{ $data['lesson']['lesson_area_names'] ? implode('/', $data['lesson']['lesson_area_names']) : '' }}</p>
                        @if(isset($data['lesson']['lesson_pos_detail']) && $data['lesson']['lesson_pos_detail'])
                            <div class="balloon balloon_blue">
                                <p>{{$data['lesson']['lesson_pos_detail']}}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if(isset($data['lesson']['lesson_able_discuss_pos']) && $data['lesson']['lesson_able_discuss_pos'])
                    <div class="inner_box">
                        <h3 class="{{ isset($lesson_change_history['lesson_able_discuss_pos']) ? 'changed' : '' }}">相談対応エリア</h3>
                        <div class="white_box base_txt">
                            <p>{{ $data['lesson']['lesson_discuss_area_names'] ? implode('/', $data['lesson']['lesson_discuss_area_names']) : '' }}</p>
                            @if(isset($data['lesson']['lesson_discuss_pos_detail']) && $data['lesson']['lesson_discuss_pos_detail'])
                                <div class="balloon balloon_blue">
                                    <p>{{$data['lesson']['lesson_discuss_pos_detail']}}</p>
                                </div>
                            @endif

                        </div>
                    </div>
                @endif
                @if(isset($data['lesson_conds']) && !empty($data['lesson_conds']))
                    <div class="inner_box">
                        @php
                            $cond_changed = '';
                            foreach($data['lesson_conds'] as $key => $value) {
                                $cond_changed = isset($lesson_change_history['lesson_cond_'.$key]) ? 'changed' : '';
                            }
                        @endphp
                        <h3 class="{{ $cond_changed ? 'changed' : '' }}">こだわり</h3>
                        <div class="kodawari_list">
                            <ul>
                                @foreach($data['lesson_conds'] as $key => $value)
                                    <li>{{$value}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="inner_box">
                    <h3 class="{{ isset($lesson_change_history['lesson_service_details']) ? 'changed' : '' }}">レッスン内容</h3>
                    <div class="white_box base_txt">
                        <p>
                            @if(isset($data['lesson']['lesson_service_details']) && !empty($data['lesson']['lesson_service_details']))
                                {{$data['lesson']['lesson_service_details']}}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3 class="{{ isset($lesson_change_history['lesson_other_details']) ? 'changed' : '' }}">当日の持ち物・その他</h3>
                    <div class="white_box base_txt">
                        <p>
                            @if(isset($data['lesson']['lesson_other_details']) && !empty($data['lesson']['lesson_other_details']))
                                {{$data['lesson']['lesson_other_details']}}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3 class="{{ isset($lesson_change_history['lesson_buy_and_attentions']) ? 'changed' : '' }}">予約にあたってのお願い・注意事項</h3>
                    <div class="white_box base_txt">
                        <p>
                            @if(isset($data['lesson']['lesson_buy_and_attentions']) && !empty($data['lesson']['lesson_buy_and_attentions']))
                                {{$data['lesson']['lesson_buy_and_attentions']}}
                            @endif
                        </p>
                    </div>
                </div>


            </section>
            @if(count($recommend_lessons) > 0)
                <section class="slider_area">
                    <h3 class="fs-16">おすすめのレッスン</h3>
                    <!-- Slider main container -->
                    <div class="swiper-container">
                        <div class="recommend-inner">
                            <div class="recommend">
                                <ol class="swiper-wrapper recommend_list">
                                    <!-- Slides -->
                                    @foreach($recommend_lessons as $k => $v)
                                        <li class="swiper-slide">
                                            <div class="swip_contents_block">
                                                <div class="lesson_box">
                                                    <div class="img-box">
                                                        @php
                                                            $lesson_image = LessonService::getLessonFirstImage($v);
                                                        @endphp
                                                        <img src="{{CommonService::getLessonImgUrl($lesson_image)}}" alt="ウォーキング画像">
                                                        <h4>{{$v['lesson_class']['class_name']}}</h4>
                                                    </div>
                                                    <div class="lesson_info_box">
                                                        <p class="lesson_name">{{$v['lesson_title']}}</p>
                                                        <p class="lesson_price"><em>{{CommonService::showFormatNum($v['lesson_30min_fees'])}}</em><span>円 / <em>30</em>分〜</span></p>
                                                        <div class="teacher_name">
                                                            <div class="icon_s30"><img src="{{ CommonService::getUserAvatarUrl($v['senpai']['user_avatar']) }}" alt=""></div>
                                                            <div>{{$v['senpai']['name']}}（<em>{{CommonService::getAge($v['senpai']['user_birthday'])}}</em>）</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                                <!-- If we need pagination -->
                                <div class="swiper-pagination recommend-pagination"></div>

                                <!-- If we need navigation buttons -->
                            </div>
                        </div>
                    </div>
                </section>

            @endif

            <section id="evaluation">
                <h3>このレッスンを受けた後輩の評価<span>({{$data['evalution_count']}}件)</span></h3>
                <div class="white_box">
                    <ul class="evaluation_list">
                        @if(isset($data['evalution']) && !empty($data['evalution']))
                            @foreach($data['evalution'] as $key => $value)
                                <li>
                                    <div>{{$value['type_name']}}</div>
                                    <div class="score_percent"><span>{{$value['percent']}}</span></div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

            </section>

            <section>
                <div class="white_box">
                    <div class="btn mtb">
                        <button type="button" onclick="location.href='{{ route('admin.lesson_examination.agree', ['lesson_id' => $lesson_id, 'lesson_content_title'=>$data['lesson']['lesson_title']]) }}'">承認する</button>
                    </div>
                    <div class="btn mtb">
                        <button type="button" onclick="location.href='{{ route('admin.lesson_examination.disagree', ['lesson' => $lesson_id, 'del_session'=>1]) }}'">承諾しない</button>
                    </div>
                </div>
            </section>

            {{--<div id="footer_button_area" class="under_area">
                <ul>
                    <li class="send-request">
                        <div class="btn_base btn_orange shadow">
                            <a href="{{route('admin.lesson_examination.agree', ['lesson_id' => $lesson_id])}}">承認する</a>
                            <button type="button" class="modal-syncer button-link" data-target="modal-post">承認する</button>
                            <button type="button" id="btn_confirm_agree" data-lesson-id="{{ $data['lesson']['lesson_id'] }}">承認する</button>
                        </div>
                        <div class="btn_base btn_orange shadow">
                            <a href="{{route('admin.lesson_examination.disagree', ['lesson_id' => $lesson_id])}}">承諾しない</a>
                        </div>
                    </li>
                </ul>--}}
            </div>

        {{ Form::open(["route"=>["admin.lesson_examination.agree"], 'name'=>'frm_agree', 'id'=>'frm_agree', "method"=>"post"]) }}
            <input type="hidden" name="lesson_id" id="lesson_id" value="">
        {{ Form::close() }}

    </div><!-- /contents -->

    <!-- *******************************************************
    フッター（リクエストボタンあり）
    ******************************************************** -->

    <div class="modal-wrap completion_wrap">
        <div id="modal-post" class="modal-content">
            <div class="modal_body">
                <div class="modal_inner">
                    <h4 id="circle-orange_ttl">!</h4>
                    <h2 class="modal_ttl">
                        承認してよろしいですか？
                    </h2>
                </div>

                <div class="button-area">
                    <div class="btn_base btn_orange">
                        <button type="button" id="btn_agree">承認</button>
                    </div>
                    <div class="btn_base btn_gray-line">
                        <a id="modal-close" class="button-link">キャンセル</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--<footer>
        @include('user.layouts.fnavi')
    </footer>--}}

@endsection

@section('page_css')
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
    <style>
        .lesson-new .changed::after {
            background: unset;
        }
        .changed::after {
            content: '';
            background: red;
            width: 6px;
            height: 6px;
            border-radius: 5px;
            position: absolute;
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
            var cur_date = $('#cur_date').val();
            setWeekSchedule(cur_date);

            $('#btn_confirm_agree').click(function() {
                let lesson_id = $(this).attr('data-lesson-id');
                console.log("lesson_id", lesson_id);
                $('#lesson_id').val(lesson_id);
                $('#frm_agree').submit();
            });
        });
        function prevSchedule(){
            var prev_date = $('#prev_date').val();

            // validation (過去へは遡れないようにする。)
            var cur_date = $('#cur_date').val();
            let now = new Date();
            let cur = new Date(cur_date);
            if (cur <= now) {
                return;
            }

            setWeekSchedule(prev_date);
        }

        function nextSchedule(){
            var next_date = $('#next_date').val();

            // validate (未来は8週間先まで。)
            let end_week_day = new Date("{{ $end_week_day }}");
            if (end_week_day <= new Date(next_date)) {
                return;
            }

            setWeekSchedule(next_date);
        }
        function setWeekSchedule(cur_date){
            console.log("setWeekSchedule ----- cur_date", cur_date);
            $.ajax({
                type: "post",
                url: '{{ route('user.lesson.get_week_schedule') }}',
                data: {lesson_id:"{{$lesson_id}}" ,date: cur_date, _token:'{{csrf_token()}}'},
                dataType: 'json',
                success: function (result) {
                    $('#cur_date').val(result.week_info.cur_date);
                    $('#prev_date').val(result.week_info.prev_date);
                    $('#next_date').val(result.week_info.next_date);
                    console.log("cur_date", result.week_info.cur_date);
                    console.log("prev_date", result.week_info.prev_date);
                    console.log("next_date", result.week_info.next_date);

                    // label color (前の週, 次の週)

                    // ---- 前の週 ---
                    let now = new Date();
                    let cur_date = new Date(result.week_info.cur_date);
                    if (cur_date <= now ) {
                        if (!$('#before_week_label').hasClass('not-active')) {
                            $('#before_week_label').addClass('not-active');
                        }
                        if ($('#before_week_label').hasClass('activated')) {
                            $('#before_week_label').removeClass('activated');
                        }
                    } else {
                        if ($('#before_week_label').hasClass('not-active')) {
                            $('#before_week_label').removeClass('not-active');
                        }
                        if (!$('#before_week_label').hasClass('activated')) {
                            $('#before_week_label').addClass('activated');
                        }
                    }

                    // ---- 次の週 ---
                    let end_week_day = new Date("{{ $end_week_day }}");
                    if (end_week_day <= new Date(result.week_info.next_date)) {
                        if (!$('#after_week_label').hasClass('not-active')) {
                            $('#after_week_label').addClass('not-active');
                        }
                        if ($('#after_week_label').hasClass('activated')) {
                            $('#after_week_label').removeClass('activated');
                        }
                    } else {
                        if ($('#after_week_label').hasClass('not-active')) {
                            $('#after_week_label').removeClass('not-active');
                        }
                        if (!$('#after_week_label').hasClass('activated')) {
                            $('#after_week_label').addClass('activated');
                        }
                    }

                    $('.date-area ul li:nth-child(2)').html(result.week_info.ym);
                    var week_html = '';
                    var class_name;
                    var i = 1;
                    let schedule_info = result.week_info.schedule;
                    for(var key in schedule_info){
                        let element = schedule_info[key];
                        var state = element.val;
                        var day = element.day;
                        if(state == 1){
                            class_name = 'ok';
                        }else if(state == 2){
                            class_name = 'nk';
                        }else{
                            class_name = 'ng';
                        }
                        if(i == 1)
                            class_name += ' sunday';
                        else if(i == 7)
                            class_name += ' saturday';
                        week_html += '<li>';
                        week_html += '<div>';
                        week_html += '<input type="radio" name="day-01" id="day' + i + '">';
                        week_html += '<label class="' + class_name + '" for="day'+ i + '">' + WEEK_DAYS[i - 1] + '<br><span>' + day + '</span></label>';
                        week_html += '</div>';
                        week_html += '</li>';
                        i++;
                    }
                    $('.day-area ul').html(week_html);
                }
            });
        }
    </script>
@endsection
