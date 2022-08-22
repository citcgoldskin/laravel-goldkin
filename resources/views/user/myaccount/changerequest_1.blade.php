@extends('user.layouts.app')
@section('title', $title)
@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->
<style>
    .activated {
        color: #FB7122 !important;
    }
    .not-active {
        color: #B8B8B8 !important;
    }
</style>

<div id="contents" class="long pt70" style="padding-bottom: 100px">
    <!--main_-->
    {{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

        <div class="swiper-container">
            <div class="swiper-inner">
                <div class="profile">
                    <ol class="swiper-wrapper pt0 pb0">
                        <!-- Slides -->
                        @if ( isset($data['lesson']['lesson_image']) )
                            @foreach( unserialize($data['lesson']['lesson_image']) as $key => $value )
                            <li class="swiper-slide">
                                <div class="swip_contents_block">
                                    <div class="slider_box">
                                        <div class="img-box">
                                            <img src="{{ \App\Service\CommonService::getLessonImgUrl($value) }}" alt="プロフィールイメージ画像">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        @endif
                    </ol>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>

                    <!-- If we need navigation buttons -->
                </div>
            </div>
        </div>

        <!-- If we need navigation buttons -->

        <section class="pt20">
            <div class="lesson_info_area">
                <h3 class="senpai-message">@if (isset($data['lesson']['lesson_title'])) {{ $data['lesson']['lesson_title'] }} @endif</h3>

                <ul class="title_info">
                    <li><span class="lesson_category">@if (isset($data['lesson']['lesson_class']['class_name'])) {{ $data['lesson']['lesson_class']['class_name'] }} @endif</span></li>
                    <li class="jisseki">
                        <p>レッスン実績 <span>{{ \App\Service\CommonService::showFormatNum($data['schedule_count']) }}</span><span>件</span></p>
                    </li>
                    <li class="hyouka">このレッスンの評価を受けた後輩の評価<a href="#evaluation">({{ \App\Service\CommonService::showFormatNum($data['evaluation_count']) }})</a></li>
                </ul>

                <ul class="teacher_info flex-ver">
                    <li><img src="{{ \App\Service\CommonService::getUserAvatarUrl($data['senpai']['user_avatar']) }}" class="プロフィールアイコン"></li>
                    <li class="about_teacher">
                        <div class="profile_name kakunin_ok">
                            <p>{{ $data['senpai']['name'] }}
                                <span>（{{ \App\Service\CommonService::getAge($data['senpai']['user_birthday']) }}）{{ \App\Service\CommonService::getSexStr($data['senpai']['user_sex']) }}</span>
                            </p>
                        </div>
                        <div><p>{{ \App\Service\AreaService::getOneAreaFullName($data['senpai']['user_area_id']) }}</p></div>
                    </li>
                    <li><p class="orange_link icon_arrow orange_right"><a href="{{ route('user.myaccount.profile', ['user_id' => $data['senpai']['id']]) }}">プロフィール</a></p></li>
                </ul>
            </div>


        </section>

        <section id="attendance_schedule">
            <h3>出勤スケジュール</h3>
            <div class="commuting-area">
                <input type="hidden" id="cur_date" value="{{ date('Y-m-d', strtotime('-1 weeks  Sunday')) }}">
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
                <div class="button-area request-btn" >
                    <p class="btn_base btn_orange shadow"><a href="{{ route('user.lesson.setting_attend_request', ['lesson_id' => $data['lesson']['lesson_id']]) }}">出勤をリクエスト</a></p>
                </div>
            </div>


        </section>



        <section id="info_area">

            <div class="inner_box">
                <h3>レッスン料金</h3>
                <div class="white_box base_txt price">
                    <p>@if ( isset($data['lesson']['lesson_30min_fees']) ) {{ $data['lesson']['lesson_30min_fees'] }} @endif
                        円<span> / 30分〜</span></p>
                </div>
            </div>

            <div class="inner_box">
                <h3>待ち合わせ場所</h3>
                <div class="white_box base_txt">
                    <p>{{ $data['lesson']['lesson_area_names'] ? implode('/', $data['lesson']['lesson_area_names']) : '' }}</p>
                    @if(isset($data['lesson']['lesson_pos_detail']) && $data['lesson']['lesson_pos_detail'])
                        <div class="balloon balloon_blue">
                            <p>{{$data['lesson']['lesson_pos_detail']}}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="inner_box">
                <h3>相談対応エリア</h3>
                <div class="white_box base_txt">
                    <p>{{ $data['lesson']['lesson_discuss_area_names'] ? implode('/', $data['lesson']['lesson_discuss_area_names']) : '' }}</p>
                    @if(isset($data['lesson']['lesson_discuss_pos_detail']) && $data['lesson']['lesson_discuss_pos_detail'])
                        <div class="balloon balloon_blue">
                            <p>{{$data['lesson']['lesson_discuss_pos_detail']}}</p>
                        </div>
                    @endif

                </div>
            </div>

            @if(isset($data['lesson']['lesson_conds']) && !empty($data['lesson']['lesson_conds']))
                <div class="inner_box">
                    <h3>こだわり</h3>
                    <div class="kodawari_list">
                        <ul>
                            @foreach($data['lesson']['lesson_conds'] as $key => $value)
                                <li>{{$value}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="inner_box">
                <h3>レッスン内容</h3>
                <div class="white_box base_txt">
                    <p>
                        @if(isset($data['lesson']['lesson_service_details']) && !empty($data['lesson']['lesson_service_details']))
                            {{$data['lesson']['lesson_service_details']}}
                        @endif
                    </p>
                </div>
            </div>

            <div class="inner_box">
                <h3>当日の持ち物・その他</h3>
                <div class="white_box base_txt">
                    <p>
                        @if(isset($data['lesson']['lesson_other_details']) && !empty($data['lesson']['lesson_other_details']))
                            {{$data['lesson']['lesson_other_details']}}
                        @endif
                    </p>
                </div>
            </div>

            <div class="inner_box">
                <h3>予約にあたってのお願い・注意事項</h3>
                <div class="white_box base_txt">
                    <p>
                        @if(isset($data['lesson']['lesson_buy_and_attentions']) && !empty($data['lesson']['lesson_buy_and_attentions']))
                            {{$data['lesson']['lesson_buy_and_attentions']}}
                        @endif
                    </p>
                </div>
            </div>
        </section>

        <div class="button-area">
            <p class="btn_base btn_orange shadow"><button type="button" onclick="location.href='{{ route('user.myaccount.changerequest_2', ['schedule_id' => isset($schedule_id) ? $schedule_id : '' ]) }}'">リクエスト内容を変更する</button></p>
        </div>
        <aside class="hosoku">
            <ul>
                <li>※変更が承認されない場合もあります。</li>
                <li>※トークルームでお相手と事前に相談をしてから変更をリクエストしてください。</li>
            </ul>
        </aside>


    {{ Form::close() }}


</div><!-- /contents -->

<!-- *******************************************************
フッター（リクエストボタンあり）
******************************************************** -->
<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection

@section('page_js')
<script>
    $(document).ready(function () {
        var cur_date = $('#cur_date').val();
        setWeekSchedule(cur_date);
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
        $.ajax({
            type: "post",
            url: '{{ route('user.lesson.get_week_schedule') }}',
            data: {lesson_id:"{{$data['lesson']['lesson_id']}}" ,date: cur_date, _token:'{{csrf_token()}}'},
            dataType: 'json',
            success: function (result) {
                $('#cur_date').val(result.week_info.cur_date);
                $('#prev_date').val(result.week_info.prev_date);
                $('#next_date').val(result.week_info.next_date);

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
