@extends('user.layouts.app')

@section('$page_id', 'home')

@php
    use App\Service\CommonService;
    use App\Service\AreaService;
	use App\Service\LessonService;
@endphp

@section('content')
    <!-- ************************************************************************
    本文
    ************************************************************************* -->
    <p class="back circle"> <a href="#" onclick="history.back(-1);return false;">＜</a></p>

    <div id="contents" class="long nopt">

    <!--main_-->
    <form action="./" method="post" name="form1" id="form1">

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

                      <!-- If we need navigation buttons -->

          <section class="pt20">
              <div class="lesson_info_area">
                <h3 class="senpai-message">{{$data['lesson']['lesson_title']}}</h3>

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
                 <li><p class="orange_link icon_arrow orange_right"><a href="{{route('user.myaccount.profile', ['user_id' => $data['senpai']['id']])}}">プロフィール</a></p></li>
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
                <div class="flex-space-around fs-12 pd-20">
                    <span>○…余裕あり</span>{{--2枠以上あれば--}}
                    <span>△…残りわずか</span>{{--最低のレッスン時間が1枠のみの場合--}}
                    <span>✕…空きなし</span>
                </div>
                <p class="text-01">ご希望の出勤日がありませんか？<br>{{ \Carbon\Carbon::parse($last_attendance_date)->addDay()->format('n月j日') }}以降の出勤をお願いすることが出来ます。</p>
                @if($data['valid'] == 0 && $data['lesson']['lesson_accept_attend_request'])
                    <div class="button-area request-btn" >
                     <p class="btn_base btn_orange shadow"><a href="{{route('user.lesson.setting_attend_request', ['lesson_id' => $lesson_id])}}">出勤をリクエスト</a></p>
                    </div>
                @endif
            </div>

          </section>

          <section id="info_area">

           <div class="inner_box">
            <h3>レッスン料金</h3>
            <div class="white_box base_txt price">
                <p>@if(isset($data['lesson']['lesson_30min_fees']) && !empty($data['lesson']['lesson_30min_fees']))
                        {{CommonService::showFormatNum($data['lesson']['lesson_30min_fees'])}}
                    @endif
                    円<span> / 30分〜</span>
                </p>
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
          @if(isset($data['lesson_conds']) && !empty($data['lesson_conds']))
           <div class="inner_box">
                <h3>こだわり</h3>
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
                              <a href="{{route('user.lesson.lesson_view', ['lesson_id' => $v['lesson_id']])}}">
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
                               </a>
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

        @if($data['valid'] == 0)
        <div id="footer_button_area" class="under_area">
            <ul>
             <li class="send-request">
              <div class="btn_base btn_orange shadow">
                  <a href="{{route('user.lesson.setting_reserve_request', ['lesson_id' => $lesson_id])}}">予約リクエストを送信する</a>
              </div>
             </li>
            </ul>
          </div>
        @endif
    </form>

        {{ Form::open(["route"=>["user.lesson.setting_reserve_request"], "method"=>"get", "name"=>"frm_reserve", "id"=>"frm_reserve"]) }}
            <input type="hidden" name="lesson_id" value="{{ $lesson_id }}">
            <input type="hidden" name="cur_ym" id="cur_ym" value="">
        {{ Form::close() }}

    </div><!-- /contents -->

    <!-- *******************************************************
    フッター（リクエストボタンあり）
    ******************************************************** -->

  <footer>
      @include('user.layouts.fnavi')
  </footer>

@endsection

@section('page_css')
    <style>
        .activated {
            color: #FB7122 !important;
        }
        .not-active {
            color: #B8B8B8 !important;
        }
        .flex-space-around {
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 20px;
        }
    </style>
@endsection

@section('page_js')
	<script type="text/javascript">
        $(document).ready(function () {
            var cur_date = $('#cur_date').val();
            setWeekSchedule(cur_date);

            @if($data['valid'] == 0)
                $('.day-area').on('click', '.lb_date', function() {
                    let month = $(this).attr('data-month');
                    $('#cur_ym').val(month);
                    $('#frm_reserve').submit();
                });
            @endif
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
                    console.log("result *********", result);
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
                        var month = element.month;
                        if(state == 1){
                            class_name = 'ok';
                        }else if(state == 2){
                            //class_name = 'nk';
                            class_name = 'ok';
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
                        week_html += '<label class="lb_date ' + class_name + '" for="day'+ i + '" data-month="'+ month +'">' + WEEK_DAYS[i - 1] + '<br><span>' + day + '</span></label>';
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


